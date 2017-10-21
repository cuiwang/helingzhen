<?php
/**
 * 微点餐
 */
defined('IN_IA') or exit('Access Denied');
include "model.php";
include "plugin/feyin/HttpClient.class.php";
include "templateMessage.php";
include "fengniao.php";
include "plugin/ylprint.class.php";
define(EARTH_RADIUS, 6371); //地球半径，平均半径为6371km
define('RES', '../addons/weisrc_dish/template/');
define('CUR_MOBILE_DIR', 'dish/');
define('FEYIN_HOST', 'my.feyin.net');
define('FEYIN_PORT', 80);
define('FEIE_IP', 'dzp.feieyun.com');
define('FEIE_PORT', 80);
define('FEIE_HOSTNAME', '/FeieServer');
require 'inc/func/core.php';

class Weisrc_dishModuleSite extends Core
{
    public $global_sid = 0;
    public $logo = '';
    public $more_store_psize = 10;

    protected function createWebUrl($do, $query = array())
    {
        global $_W, $_GPC;
        $query['do'] = $do;
        $query['m'] = strtolower($this->modulename);

        $url = $_SERVER['REQUEST_URI'];
        if (strexists($url, 'store.php')) {
            $url = './store.php?';
            $url .= "c=site&";
            $url .= "a=entry&";
            if (!empty($do)) {
                $url .= "do={$do}&";
            }
            if (!empty($query)) {
                $queryString = http_build_query($query, '', '&');
                $url .= $queryString;
            }
            return $url;
        } else {
            return wurl('site/entry', $query);
        }

    }

    function __construct()
    {
        global $_W, $_GPC;
        $this->serverip = getServerIP();
        $this->_fromuser = $_W['fans']['from_user']; //debug
        $host = $_SERVER['HTTP_HOST'];
        if ($host == 'localhost' || $host == '127.0.0.1' || $host == '192.168.1.115') {
            $this->_fromuser = 'debug';
        }
        $this->_weid = $_W['uniacid'];
        $account = $_W['account'];
        $this->_auth2_openid = 'auth2_openid_' . $_W['uniacid'];
        $this->_auth2_nickname = 'auth2_nickname_' . $_W['uniacid'];
        $this->_auth2_headimgurl = 'auth2_headimgurl_' . $_W['uniacid'];

        $this->_lat = 'lat_' . $this->_weid;
        $this->_lng = 'lng_' . $this->_weid;

        $this->_appid = '';
        $this->_appsecret = '';
        $this->_accountlevel = $account['level']; //是否为高级号

        if (isset($_COOKIE[$this->_auth2_openid])) {
            $this->_fromuser = $_COOKIE[$this->_auth2_openid];
        }

        if (isset($_COOKIE['global_sid_' . $_W['uniacid']])) {
            $this->global_sid = $_COOKIE['global_sid_' . $_W['uniacid']];
        }

        if ($this->_accountlevel < 4) {
            $setting = uni_setting($this->_weid);
            $oauth = $setting['oauth'];
            if (!empty($oauth) && !empty($oauth['account'])) {
                $this->_account = account_fetch($oauth['account']);
                $this->_appid = $this->_account['key'];
                $this->_appsecret = $this->_account['secret'];
            }
        } else {
            $this->_appid = $_W['account']['key'];
            $this->_appsecret = $_W['account']['secret'];
        }

        $logo = pdo_fetch("SELECT site_logo FROM " . tablename($this->table_setting) . " WHERE weid = :weid", array
        (':weid' => $this->_weid));
        if (empty($logo['site_logo'])) {
            $this->logo = '../addons/weisrc_dish/template/images/logo.png';
        } else {
            $this->logo = tomedia($logo['site_logo']);
        }

        $template = pdo_fetch("SELECT * FROM " . tablename($this->table_template) . " WHERE weid = :weid", array(':weid' => $this->_weid));
        if (!empty($template)) {
            $this->cur_tpl = $template['template_name'];
        }
        $this->cur_res = RES . '/mobile/' . $this->cur_tpl;
        $this->cur_mobile_path = RES . '/mobile/' . $this->cur_tpl;
        $this->_file_sys_tb();
    }

    public function sendfengniao($order, $store, $setting)
    {
        global $_W, $_GPC;

        $rop = new fengniao($setting['fengniao_appid'], $setting['fengniao_key']);
        $rop->requestToken();

        if (empty($setting['fengniao_appid']) || empty($setting['fengniao_key']) || $store['is_fengniao'] == 0) {
            return false;
        }

        $orderid = $order['id'];
        $goodsid = pdo_fetchall("SELECT goodsid,total,price FROM " . tablename($this->table_order_goods) . " WHERE orderid = :orderid", array(':orderid' => $orderid), 'goodsid');
        $goods = pdo_fetchall("SELECT * FROM " . tablename($this->table_goods) . "  WHERE id IN ('" . implode("','", array_keys($goodsid)) . "')");
        $items_json = array();
        foreach ($goods as $goodkey => $goodvalue) {
            $items_json[] = array(
                'item_id' => $goodvalue['id'],
                'item_name' => $goodvalue['title'],
                'item_quantity' => $goodsid[$goodvalue['id']]['total'],
                'item_price' => $goodvalue['marketprice'],
                'item_actual_price' => $goodsid[$goodvalue['id']]['price'],
                'is_need_package' => 1,
                'is_agent_purchase' => 0
            );
        }
        $notify_url = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&do=fengniaonotify&m=weisrc_dish';
        //拼装data数据
        $dataArray = array(
            'partner_remark' => $store['title'],
            'partner_order_code' => $order['id'],     // 第三方订单号, 需唯一
            'notify_url' => $notify_url,     //第三方回调 url地址
            'order_type' => 1,
            'transport_info' => array(
                'transport_name' => $store['title'],
                'transport_address' => $store['address'],
                'transport_longitude' => $store['lng'],
                'transport_latitude' => $store['lat'],
//                'transport_longitude' => '109.1100000000',
//                'transport_latitude' => '29.2200000000',
                'position_source' => 2,
                'transport_tel' => $store['tel'],
                'transport_remark' => '备注'
            ),
            'receiver_info' => array(
                'receiver_name' => $order['username'],
                'receiver_primary_phone' => $order['tel'],
                'receiver_second_phone' => $order['tel'],
                'receiver_address' => $order['address'],
                'position_source' => 2,
                'receiver_longitude' => $order['lng'],
                'receiver_latitude' => $order['lat']
//                'receiver_longitude' => '109.1100000000',
//                'receiver_latitude' => '29.2200000000'
            ),
            'items_json' => $items_json,
            "order_add_time" => intval($order['dateline']) * 1000,
            "order_total_amount" => $order['totalprice'],
            "order_actual_amount" => $order['totalprice'],
            "order_remark" => $order['remark'],
            "is_invoiced" => 0,
            "order_weight" => 2,
            "invoice" => "",
            "order_payment_status" => $order['ispay'],
            "order_payment_method" => 1,
            "is_agent_payment" => 0,
            "require_payment_pay" => 0,
            "goods_count" => $order['totalnum'],
            "require_receive_time" => strtotime('+1 day') * 1000
        );
        $rop->sendOrder($dataArray);  // second 创建订单
    }

    public function doMobilefengniaonotify()
    {
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];

        $str = htmlspecialchars_decode($_GPC['data']);
        $data = json_decode($str, true);
        file_put_contents(IA_ROOT . "/addons/weisrc_dish/fengmiao.log", var_export($data, true) . PHP_EOL, FILE_APPEND);

        $orderid = intval($data['partner_order_code']);
        $order = $this->getOrderById($orderid);
        $item = array(
            'weid' => $order['weid'],
            'storeid' => $order['storeid'],
            'open_order_code' => $data['open_order_code'],
            'partner_order_code' => $data['partner_order_code'],
            'order_status' => $data['order_status'],
            'push_time' => TIMESTAMP,
            'carrier_driver_name' => $data['carrier_driver_name'],
            'carrier_driver_phone' => $data['carrier_driver_phone'],
            'cancel_reason' => $data['cancel_reason'],
            'description' => $data['description'],
            'error_code' => $data['error_code'],
        );
        pdo_insert("weisrc_dish_fengniao", $item);
    }

    public function doWebQueryorder()
    {
        global $_W, $_GPC;
        $sn = $_GPC['sn'];
        $rop = new fengniao('', '');
        $rop->requestToken();
        echo $rop->queryQrder($sn);
    }

    public function doWebcancelorder()
    {
        global $_W, $_GPC;
        $sn = $_GPC['sn'];
        $rop = new fengniao('', '');
        $rop->requestToken();
        echo $rop->cancelQrder($sn);
    }


    public function getdistanceprice($storeid, $distance)
    {
        $strwhere = "  where storeid = :storeid AND weid=:weid AND :distance>=begindistance AND :distance<enddistance ";
        $data = pdo_fetch("select * from " . tablename("weisrc_dish_distance") . " {$strwhere} ORDER BY id DESC LIMIT 1", array(':storeid' => $storeid, ':weid' => $this->_weid, ':distance' => $distance));
        return $data;
    }

    public function getNewLimitPrice($storeid, $price, $mode)
    {
        $strwhere = "  where storeid = :storeid AND weid=:weid AND :price>=gmoney AND type=3 AND :time<endtime ";
        if ($mode == 1) { //店内
            $strwhere .= " AND is_meal=1 ";
        } else if ($mode == 2) { //外卖
            $strwhere .= " AND is_delivery=1 ";
        } else if ($mode == 3) { //预定
            $strwhere .= " AND is_reservation=1 ";
        } else if ($mode == 4) { //快餐
            $strwhere .= " AND is_snack=1 ";
        }

        $coupon = pdo_fetch("select * from " . tablename($this->table_coupon) . " {$strwhere} ORDER BY gmoney desc,id DESC LIMIT 1", array(':storeid' => $storeid, ':weid' => $this->_weid, ':price' => $price, ':time' => TIMESTAMP));
        return $coupon;
    }

    public function getOldLimitPrice($storeid, $price, $mode)
    {
        $strwhere = "  where storeid = :storeid AND weid=:weid AND :price>=gmoney AND type=4 AND :time<endtime ";
        if ($mode == 1) { //店内
            $strwhere .= " AND is_meal=1 ";
        } else if ($mode == 2) { //外卖
            $strwhere .= " AND is_delivery=1 ";
        } else if ($mode == 3) { //预定
            $strwhere .= " AND is_reservation=1 ";
        } else if ($mode == 4) { //快餐
            $strwhere .= " AND is_snack=1 ";
        }

        $coupon = pdo_fetch("select * from " . tablename($this->table_coupon) . " {$strwhere} ORDER BY gmoney desc,id DESC LIMIT 1", array(':storeid' => $storeid, ':weid' => $this->_weid, ':price' => $price, ':time' => TIMESTAMP));
        return $coupon;
    }

    public function isNewUser($storeid)
    {
        $isnewuser = 1;
        $order = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE weid=:weid AND storeid=:storeid AND from_user=:from_user AND
status<>-1 ORDER BY id DESC LIMIT 1", array(':from_user' => $this->_fromuser, ':weid' => $this->_weid, ':storeid' => $storeid));
        if ($order) {
            $isnewuser = 0;
        }
        return $isnewuser;
    }

    public function doMobileUserAddress()
    {
        global $_GPC, $_W;
        $storeid = intval($_GPC['storeid']);
        $mode = intval($_GPC['mode']);
        $tablesid = intval($_GPC['tablesid']);

        $rtype = !isset($_GPC['rtype']) ? 1 : intval($_GPC['rtype']);
        $timeid = intval($_GPC['timeid']);
        $selectdate = trim($_GPC['selectdate']);


        $id = intval($_GPC['id']);
        $backurl = $this->createMobileUrl('wapmenu', array('storeid' => $storeid, 'mode' => $mode, 'tablesid' => $tablesid, 'selectdate' => $selectdate, 'timeid' => $timeid, 'rtype' => $rtype),
            true);
        if ($storeid == 0 || $mode == 0) {
            $backurl = $this->createMobileUrl('usercenter', array(), true);
        }
        $addurl = $this->createMobileUrl('useraddress', array('storeid' => $storeid, 'mode' => $mode, 'tablesid' => $tablesid, 'op' => 'post', 'id' => $id, 'selectdate' => $selectdate, 'timeid' => $timeid, 'rtype' => $rtype), true);

        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        if ($operation == 'display') {
            $sql = "select * from " . tablename($this->table_useraddress) . " WHERE from_user=:from_user ORDER BY dateline DESC ";
            $paras = array(':from_user' => $this->_fromuser);
            $list = pdo_fetchall($sql, $paras);
        } else if ($operation == 'post') {
            $id = intval($_GPC['id']);
            $item = pdo_fetch("SELECT * FROM " . tablename($this->table_useraddress) . " WHERE id=:id", array(":id" => $id));
        } else if ($operation == 'AddAddress') {
            $data = array(
                'weid' => $this->_weid,
                'from_user' => $this->_fromuser,
                'realname' => trim($_GPC['realname']),
                'mobile' => trim($_GPC['mobile']),
                'address' => trim($_GPC['address']),
                'doorplate' => trim($_GPC['doorplate']),
                'isdefault' => 1,
                'lat' => trim($_GPC['lat']),
                'lng' => trim($_GPC['lng']),
                'dateline' => TIMESTAMP
            );
            pdo_update($this->table_useraddress, array('isdefault' => 0), array('from_user' => $this->_fromuser));
            if (!empty($id)) {
                pdo_update($this->table_useraddress, $data, array('id' => $id, 'from_user' => $this->_fromuser));
            } else {
                pdo_insert($this->table_useraddress, $data);
            }
            exit;
        } else if ($operation == 'delete') {
            if ($id != 0) {
                pdo_delete($this->table_useraddress, array('id' => $id, 'from_user' => $this->_fromuser));
                exit;
            }
        } else if ($operation == 'setdefault') {
            if ($id != 0) {
                pdo_update($this->table_useraddress, array('isdefault' => 0), array('from_user' => $this->_fromuser));
                pdo_update($this->table_useraddress, array('isdefault' => 1), array('id' => $id, 'from_user' => $this->_fromuser));
                exit;
            }
        }
        include $this->template($this->cur_tpl . '/address');
    }

    public function doMobileGetgoodsdetail()
    {
        global $_GPC, $_W;
        //查询商品是否存在
        $id = intval($_GPC['id']); //商品id
        $goods = pdo_fetch("SELECT * FROM " . tablename($this->table_goods) . " WHERE id=:id", array(":id" => $id));
        if (empty($goods)) {
            echo json_encode(0);
        } else {
            $goods['content'] = htmlspecialchars_decode($goods['content']);
            $goods['thumb'] = tomedia($goods['thumb']);
            $iscard = $this->get_sys_card($this->_fromuser);
            $goods['dprice'] = $goods['marketprice'];
            if ($iscard == 1 && !empty($goods['memberprice'])) {
                $goods['dprice'] = $goods['memberprice'];
            }
            echo json_encode($goods);
        }
    }

    public function out_order($commoncondition, $paras)
    {
        $sql = "select * from " . tablename($this->table_order)
            . " WHERE $commoncondition ORDER BY status DESC, dateline DESC ";
        $list = pdo_fetchall($sql, $paras);
        $orderstatus = array(
            '-1' => array('css' => 'default', 'name' => '已取消'),
            '0' => array('css' => 'danger', 'name' => '待处理'),
            '1' => array('css' => 'info', 'name' => '已确认'),
            '2' => array('css' => 'warning', 'name' => '已付款'),
            '3' => array('css' => 'success', 'name' => '已完成')
        );

        $paytypes = array(
            '0' => array('css' => 'danger', 'name' => '未选择'),
            '1' => array('css' => 'info', 'name' => '余额支付'),
            '2' => array('css' => 'warning', 'name' => '微信支付'),
            '3' => array('css' => 'success', 'name' => '现金支付'),
            '4' => array('css' => 'warning', 'name' => '支付宝'),
            '5' => array('css' => 'warning', 'name' => '现金'),
            '6' => array('css' => 'warning', 'name' => '银行卡'),
            '7' => array('css' => 'warning', 'name' => '会员卡'),
            '8' => array('css' => 'warning', 'name' => '微信'),
            '9' => array('css' => 'warning', 'name' => '支付宝'),
            '10' => array('css' => 'warning', 'name' => 'pos刷卡'),
            '11' => array('css' => 'warning', 'name' => '挂帐'),
        );

        $i = 0;
        foreach ($list as $key => $value) {
            $store = $this->getStoreById($value['storeid']);
            $arr[$i]['storetitle'] = $store['title'];
            $arr[$i]['ordersn'] = "'" . $value['ordersn'];
            $arr[$i]['transid'] = "'" . $value['transid'];
            $arr[$i]['paytype'] = $paytypes[$value['paytype']]['name'];
            $arr[$i]['status'] = $orderstatus[$value['status']]['name'];
            $arr[$i]['totalnum'] = "'" . $value['totalnum'];
            $arr[$i]['totalprice'] = $value['totalprice'];
            $arr[$i]['goodsprice'] = $value['goodsprice'];
            $arr[$i]['dispatchprice'] = $value['dispatchprice'];
            $arr[$i]['packvalue'] = $value['packvalue'];
            $arr[$i]['tea_money'] = $value['tea_money'];
            $arr[$i]['service_money'] = $value['service_money'];
            $arr[$i]['username'] = $value['username'];
            $arr[$i]['tel'] = $value['tel'];
            $arr[$i]['address'] = $value['address'];
            $arr[$i]['dateline'] = date('Y-m-d H:i:s', $value['dateline']);
            if ($value['delivery_id'] != 0) {
                $deliveryuser = $this->getAccountById($value['delivery_id']);
            }
            if ($value['deliveryareaid'] != 0) {
                $deliveryarea = $this->getDeliveryById($value['deliveryareaid']);
            }

            $arr[$i]['deliveryarea'] = $deliveryarea['title'];
            $arr[$i]['deliveryuser'] = empty($deliveryuser) ? $value['deliveryareaid'] : $deliveryuser['username'];
            $arr[$i]['delivery_money'] = $value['delivery_money'];
            $i++;
        }

        $this->exportexcel($arr, array('所属商家', '订单号', '商户订单号', '支付方式', '状态', '数量', '总价', '商品价格', '配送费', '打包费', '茶位费', '服务费', '真实姓名', '电话号码', '地址', '时间', '配送点', '配送员', '配送佣金'), TIMESTAMP);
        exit();
    }

    public function getIdentityByFans($fans)
    {
        $setting = $this->getSetting();
        $tip = "消费者";
        if ($setting['is_commission'] == 1) { //开启分销
            if ($setting['commission_mode'] == 2) { //代理商模式
                if ($fans['is_commission'] == 2) { //代理商
                    $tip = "代理商";
                    $is_commission = 1;
                    if ($fans['agentid'] == 0) {
                        $tip = "股东";
                    }
                } else { //消费者
                    $tip = "消费者";
                }
            }
        }
        return $tip;
    }

    public function out_goods($commoncondition, $paras)
    {

        $sql = "select * from " . tablename($this->table_order)
            . " WHERE $commoncondition ORDER BY status DESC, dateline DESC ";
        $list = pdo_fetchall($sql, $paras);
        $orderstatus = array(
            '-1' => array('css' => 'default', 'name' => '已取消'),
            '0' => array('css' => 'danger', 'name' => '待处理'),
            '1' => array('css' => 'info', 'name' => '已确认'),
            '2' => array('css' => 'warning', 'name' => '已付款'),
            '3' => array('css' => 'success', 'name' => '已完成')
        );

        $paytypes = array(
            '0' => array('css' => 'danger', 'name' => '未选择'),
            '1' => array('css' => 'info', 'name' => '余额支付'),
            '2' => array('css' => 'warning', 'name' => '微信支付'),
            '3' => array('css' => 'success', 'name' => '现金支付'),
            '4' => array('css' => 'warning', 'name' => '支付宝'),
            '5' => array('css' => 'warning', 'name' => '现金'),
            '6' => array('css' => 'warning', 'name' => '银行卡'),
            '7' => array('css' => 'warning', 'name' => '会员卡'),
            '8' => array('css' => 'warning', 'name' => '微信'),
            '9' => array('css' => 'warning', 'name' => '支付宝'),
            '10' => array('css' => 'warning', 'name' => 'pos刷卡'),
            '11' => array('css' => 'warning', 'name' => '挂帐'),
        );

        $i = 0;
        foreach ($list as $key => $value) {
            $store = $this->getStoreById($value['storeid']);
            if ($value['delivery_id'] != 0) {
                $deliveryuser = $this->getAccountById($value['delivery_id']);
            }
            if ($value['deliveryareaid'] != 0) {
                $deliveryarea = $this->getDeliveryById($value['deliveryareaid']);
            }
            $fans = $this->getFansByOpenid($value['from_user']);
            $identity = $this->getIdentityByFans($fans);
            $agentname = '平台';
            $agentname2 = '平台';
            if ($identity == '股东') {
                $agentname = $value['username'] . '(股东)';
                $agentname2 = $value['username'] . '(股东)';
            }
            if ($fans['agentid'] <> 0) {
                $agent = pdo_fetch("SELECT * FROM " . tablename($this->table_fans) . " WHERE id=:id AND weid=:weid LIMIT 1", array(':id' => $fans['agentid'], ':weid' => $this->_weid));
                if ($identity == "代理商") {
                    $agentname = $value['username'] . '(代理商)';
                    $identity1 = $this->getIdentityByFans($agent);
                    $agentname2 = $agent['username'] . "({$identity1})";
                } else {
                    $identity1 = $this->getIdentityByFans($agent);
                    $agentname = $agent['username'] . "({$identity1})";
                    if ($identity1 == '股东') {
                        $agentname2 = $agentname;
                    } else {
                        $agent2 = pdo_fetch("SELECT * FROM " . tablename($this->table_fans) . " WHERE id=:id AND weid=:weid LIMIT 1", array(':id' => $agent['agentid'], ':weid' => $this->_weid));
                        $identity2 = $this->getIdentityByFans($agent2);
                        $agentname2 = $agent2['username'] . "({$identity2})";
                    }
                }
            }
            $orderid = $value['id'];
            $goods = pdo_fetchall("SELECT a.goodsid,a.price, a.total,b.thumb,b.title,b.id,b.pcate,b.credit,b.commission_money1,b.commission_money2 FROM " . tablename($this->table_order_goods) . "
a INNER JOIN " . tablename($this->table_goods) . " b ON a.goodsid=b.id WHERE a.orderid = :orderid", array(':orderid' => $orderid));
            $j = 0;
            foreach ($goods as $goodkey => $goodvalue) {
                if ($j == 0) {
                    $arr[$i]['storetitle'] = $store['title'];
                    $arr[$i]['ordersn'] = "'" . $value['ordersn'];
                    $arr[$i]['transid'] = "'" . $value['transid'];
                    $arr[$i]['paytype'] = $paytypes[$value['paytype']]['name'];
                    $arr[$i]['status'] = $orderstatus[$value['status']]['name'];
                } else {
                    $arr[$i]['storetitle'] = '';
                    $arr[$i]['ordersn'] = '';
                    $arr[$i]['transid'] = '';
                    $arr[$i]['paytype'] = '';
                    $arr[$i]['status'] = '';
                }

                $arr[$i]['goodsname'] = $goodvalue['title'];
                $arr[$i]['goodstotal'] = $goodvalue['total'];
                $arr[$i]['goodsprice'] = $goodvalue['price'];
                if ($j == 0) {
                    $arr[$i]['totalprice'] = $value['totalprice'];
                } else {
                    $arr[$i]['totalprice'] = '';
                }

                $arr[$i]['dispatchprice'] = $value['dispatchprice'];
                $arr[$i]['packvalue'] = $value['packvalue'];
                $arr[$i]['tea_money'] = $value['tea_money'];
                $arr[$i]['service_money'] = $value['service_money'];
                $arr[$i]['agent1'] = $agentname;;
                $arr[$i]['commission_money1'] = floatval($goodvalue['commission_money1']) * intval($goodvalue['total']);
                $arr[$i]['agent2'] = $agentname2;
                $arr[$i]['commission_money2'] = floatval($goodvalue['commission_money2']) * intval($goodvalue['total']);
                $arr[$i]['username'] = $value['username'];
                $arr[$i]['identity'] = $identity;
                $arr[$i]['tel'] = $value['tel'];
                $arr[$i]['address'] = $value['address'];
                $arr[$i]['dateline'] = date('Y-m-d H:i:s', $value['dateline']);
                $arr[$i]['deliveryarea'] = $deliveryarea['title'];
                $arr[$i]['deliveryuser'] = $deliveryuser['username'];
                $arr[$i]['delivery_money'] = $value['delivery_money'];
                $j++;
                $i++;
            }
        }

        $this->exportexcel($arr, array('所属商家', '订单号', '商户订单号', '支付方式', '状态', '产品详情', '数量', '商品价格', '总价', '配送费', '打包费', '茶位费', '服务费', '一级', '一级佣金', '二级', '二级佣金', '真实姓名', '消费身份', '电话号码', '地址', '时间', '配送点', '配送员', '配送佣金'), TIMESTAMP);
        exit();
    }

    private function _sendDySms($code, $tomobile, $item)
    {
        define("TOP_SDK_WORK_DIR", MODULE_ROOT . '/plugin/alidayu/');
        define("TOP_AUTOLOADER_PATH", MODULE_ROOT . '/plugin/alidayu/');
        require_once MODULE_ROOT . '/plugin/alidayu/Autoloader.php';
        $topclient = new TopClient;
        $topclient->appkey = 'dayuak';
        $topclient->secretKey = 'dayusk';

        $smsrequest = new AlibabaAliqinFcSmsNumSendRequest;
        $smsrequest->setSmsType("normal");
        $smsrequest->setSmsFreeSignName('签名');
        //模版内容
        $smsrequest->setSmsParam(json_encode(array(
            'code' => (string)$code,
            'product' => 'dayuname',
            'item' => $item
        )));
        $smsrequest->setRecNum($tomobile);
        $smsrequest->setSmsTemplateCode('dayumoduleid');//模版id
        return $topclient->execute($smsrequest);
    }

    public function doWebQueryFans()
    {
        global $_W, $_GPC;

        $kwd = $_GPC['keyword'];
        $sql = "SELECT * FROM " . tablename($this->table_fans) . " WHERE `weid`=:weid AND `nickname` LIKE :nickname AND `nickname`<>'' ORDER
BY lasttime DESC,id DESC LIMIT 0,8";
        $params = array();
        $params[':weid'] = $_W['uniacid'];
        $params[':nickname'] = "%{$kwd}%";
        $ds = pdo_fetchall($sql, $params);
        foreach ($ds as &$row) {
            $r = array();
            $r['id'] = $row['id'];
            $r['nickname'] = str_replace('\'', '', $row['nickname']);
            $r['headimgurl'] = $row['headimgurl'];
            $r['from_user'] = $row['from_user'];
            $row['entry'] = $r;

        }
        include $this->template('web/_query_fans');
    }

    public function addRechargePrice($orderid) //充值返现
    {
        $order = $this->getOrderById($orderid);
        if ($order['dining_mode'] == 6) {
            $rechargeid = intval($order['rechargeid']);
            $item = pdo_fetch("SELECT * FROM " . tablename($this->table_recharge) . " where id=:id LIMIT 1", array(':id' => $rechargeid));
            $give_value = floatval($item['give_value']);
            $total = intval($item['total']);
            $givetime = TIMESTAMP;
            if ($item && $give_value > 0) { //充值返现
                if ($total == 0) { //分期
                    $price = $give_value;
                    $data = array(
                        'rechargeid' => $rechargeid,
                        'weid' => $order['weid'],
                        'from_user' => $order['from_user'],
                        'storeid' => $order['storeid'],
                        'orderid' => $orderid,
                        'price' => $price,
                        'status' => 0,
                        'givetime' => $givetime,
                        'dateline' => TIMESTAMP
                    );
                    pdo_insert("weisrc_dish_recharge_record", $data);
                } else { //分多期
                    $price = $give_value / $total;
                    for ($i = 0; $i < $total; $i++) {
                        if ($i > 0) {
                            $givetime = strtotime("+{$i}month");
                        }
                        $data = array(
                            'rechargeid' => $rechargeid,
                            'weid' => $order['weid'],
                            'from_user' => $order['from_user'],
                            'storeid' => $order['storeid'],
                            'orderid' => $orderid,
                            'price' => $price,
                            'status' => 0,
                            'givetime' => $givetime,
                            'dateline' => TIMESTAMP
                        );
                        pdo_insert("weisrc_dish_recharge_record", $data);
                    }
                }
            }
        }
    }

    public function checkRechargePrice($from_user) //用户充值返现
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $list = pdo_fetchall("SELECT * FROM " . tablename("weisrc_dish_recharge_record") . " where weid=:weid AND from_user=:from_user AND status=0 AND
givetime<:givetime", array(':weid' => $weid, ':from_user' => $from_user, ':givetime' => TIMESTAMP));
        foreach ($list as $key => $val) {
            $status = $this->setFansCoin($val['from_user'], $val['price'], "订单编号{$val['orderid']}充值返现");
            if ($status) {
                pdo_update('weisrc_dish_recharge_record', array('status' => 1), array('id' => $val['id']));
            }
        }
    }

    public function sendApplyNotice($setting, $store, $money)
    {
        global $_W, $_GPC;
        $weid = $this->_weid;

        $url = '#';
        $first = "您有新的提现申请";
        $keyword1 = date("Y-m-d日H.i", TIMESTAMP);
        $keyword2 = $money;

        if (!empty($setting['tpluser']) && !empty($setting['tplapplynotice'])) {
            $templateid = $setting['tplapplynotice'];
            $remark = "提现用户：{$store['business_username']}\n";
            if ($store['business_type'] == 1) {
                $remark .= "提现方式：微信账号\n";
            } else {
                $remark .= "提现方式：支付宝\n";
            }
            $remark .= "提现门店：{$store['title']}\n";
            $content = array(
                'first' => array(
                    'value' => $first,
                    'color' => '#a6a6a9'
                ),
                'keyword1' => array(
                    'value' => $keyword1,
                    'color' => '#a6a6a9'
                ),
                'keyword2' => array(
                    'value' => $keyword2,
                    'color' => '#a6a6a9'
                ),
                'remark' => array(
                    'value' => $remark,
                    'color' => '#a6a6a9'
                )
            );

            load()->model('account');
            $access_token = WeAccount::token();
            $templateMessage = new templateMessage();
            $templateMessage->send_template_message($setting['tpluser'], $templateid, $content, $access_token, $url);
        }
    }

    public function sendAdminOperatorNotice($from_user, $tablesid, $type, $setting, $storeid)
    {
        global $_W, $_GPC;
        $weid = $this->_weid;

        $types = array(
            '1' => '呼叫服务员',
            '2' => '我要打包'
        );

        $url = '#';
        $keyword1 = $this->getTableName($tablesid);
        $keyword2 = date("Y-m-d日H.i", TIMESTAMP);
        $store = $this->getStoreById($storeid);

        $templateid = $setting['tploperator'];
        $first = $types[$type];
        $remark = "门店名称：{$store['title']}";

        if ($setting['tpltype'] == 1) {
            $content = array(
                'first' => array(
                    'value' => $first,
                    'color' => '#a6a6a9'
                ),
                'keyword1' => array(
                    'value' => $keyword1,
                    'color' => '#a6a6a9'
                ),
                'keyword2' => array(
                    'value' => $keyword2,
                    'color' => '#a6a6a9'
                ),
                'remark' => array(
                    'value' => $remark,
                    'color' => '#a6a6a9'
                )
            );
        } else {
            $keyword1 = '桌号/房号-' . $this->getTableName($tablesid);
            $keyword2 = '日期-' . date("Y-m-d日H.i", TIMESTAMP);
            $content = array(
                'first' => array(
                    'value' => $first,
                    'color' => '#a6a6a9'
                ),
                'keyword1' => array(
                    'value' => $keyword1,
                    'color' => '#a6a6a9'
                ),
                'keyword2' => array(
                    'value' => $keyword2,
                    'color' => '#a6a6a9'
                ),
                'remark' => array(
                    'value' => $remark,
                    'color' => '#a6a6a9'
                )
            );
        }

        load()->model('account');
        $access_token = WeAccount::token();
        $templateMessage = new templateMessage();
        $templateMessage->send_template_message($from_user, $templateid, $content, $access_token, $url);
//      $result = $result == true ? '发送成功' : $result;
    }

    public function check_hourtime($begintime, $endtime)
    {
        global $_W, $_GPC;
        $nowtime = intval(date("Hi"));
        $begintime = intval(str_replace(':', '', $begintime));
        $endtime = intval(str_replace(':', '', $endtime));

        if ($begintime < $endtime) {
            if ($begintime <= $nowtime && $nowtime <= $endtime) {
                return 1;
            }
        } else {
            if ($begintime <= $nowtime || $nowtime <= $endtime) {
                return 1;
            }
        }
        return 0;
    }

    //365
    public function _365SendFreeMessage($orderid = 0, $position_type = 0)
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        load()->func('communication');

        if ($orderid == 0) {
            return -2;
        }

        $order = $this->getOrderById($orderid);
        if (empty($order)) {
            return -3;
        }

        $storeid = $order['storeid'];
        //打印机配置信息
        $settings = pdo_fetchall("SELECT * FROM " . tablename($this->table_print_setting) . " WHERE storeid = :storeid AND print_status=1 AND type='365' ", array(':storeid' => $storeid));

        if ($settings == false) {
            return -4;
        }

        $dining_mode = $order['dining_mode']; //1:店内2:外卖3:预定4:快餐5:收银
        $paytype = $this->getPayType($dining_mode);
        $ordertype = $this->getOrderType();

        //商品id数组
        $goodsid = pdo_fetchall("SELECT goodsid,total,price FROM " . tablename($this->table_order_goods) . " WHERE orderid = :orderid", array(':orderid' => $orderid), 'goodsid');

        $store = $this->getStoreById($storeid);

        $paystatus = $order['ispay'] == 0 ? '未支付' : '已支付';

        if ($dining_mode == 3) {
            $tablezones = pdo_fetch("SELECT * FROM " . tablename($this->table_tablezones) . " where weid = :weid AND id=:id LIMIT 1", array(':weid' => $weid, ':id' => $order['tablezonesid']));
        }
        if (!empty($order['tables'])) {
            $table_info = $this->getTableName($order['tables']);
        }

        $dining_mode = intval($dining_mode); //1:店内2:外卖3:预定4:快餐
        foreach ($settings as $item => $value) {
            //判断打印订单类型（是否已经支付的单子）
            if ($value['print_type'] == 1 && $order['ispay'] == 0) { //支付模式未支付时
                continue;
            }
            if ($value['print_type'] == 2 && $order['status'] == 0) { //已确认模式未确认订单
                continue;
            }
            if ($position_type == 1 && $value['position_type'] == 2) { //前台模式 后厨打印机
                continue;
            }
            if ($position_type == 2 && $value['position_type'] == 1) { //后厨模式 前台打印机
                continue;
            }

            $target = "http://open.printcenter.cn:8080/addOrder";
            $deviceNo = $value['print_usr'];
            $key = $value['feyin_key'];
            $times = $value['print_nums'];

            $loop_first = 0;
            if ($value['is_meal'] == 1 && $dining_mode == 1) {
                $loop_first = 1;
            }
            if ($value['is_delivery'] == 1 && $dining_mode == 2) {
                $loop_first = 1;
            }
            if ($value['is_reservation'] == 1 && $dining_mode == 3) {
                $loop_first = 1;
            }
            if ($value['is_snack'] == 1 && $dining_mode == 4) {
                $loop_first = 1;
            }
            if ($value['is_shouyin'] == 1 && $dining_mode == 5) {
                $loop_first = 1;
            }

            if ($loop_first == 0) {
                continue;
            }

            $pos = strpos($deviceNo, "kdt1");
            if ($pos === false) {
                $pos = 2;
            }

            $bigfont = "";
            if ($pos == 0) { //s1机器
                $huanhang = "\n";
            } else { //s2机器
                $huanhang = "<BR>";
            }

            $content = '订单编号:' . $order['ordersn'] . $huanhang;
            if ($dining_mode == 4) {
                $content .= '领餐牌号:' . $order['quicknum'] . $huanhang;
            }
            $content .= '订单类型:' . $ordertype[$dining_mode] . $huanhang;
            $content .= '所属门店:' . $store['title'] . $huanhang;
            $content .= '支付方式:' . $paytype[$order['paytype']] . "(" . $paystatus . ")" . $huanhang;
            $content .= '下单日期:' . date('Y-m-d H:i:s', $order['dateline']) . $huanhang;
            if ($dining_mode == 3 && !empty($tablezones)) {
                $content .= "桌台类型：{$tablezones['title']}" . $huanhang;
            }
            if (!empty($order['tables'])) {
                $content .= '桌台信息:' . $table_info . $huanhang;
            }
            if (!empty($order['counts'])) {
                $content .= '用餐人数:' . $order['counts'] . $huanhang;
            }
            if (!empty($order['remark'])) {
//                $content .= '备注:' . $order['remark'] . $huanhang;
                $remark = $this->getPrintContent($order['remark'], 16, $huanhang);
                $content .= $huanhang . '备注:' . $remark . $huanhang . $huanhang;
            }
            $content .= '门店地址:' . $store['address'] . $huanhang;
            $content .= '门店电话:' . $store['tel'] . $huanhang;
            $content2 = "-------------------------" . $huanhang;

            $packvalue = floatval($order['packvalue']);
            $tea_money = floatval($order['tea_money']);
            $service_money = floatval($order['service_money']);
            if ($packvalue > 0 && $dining_mode == 2) {
                $content2 .= "打包费:{$packvalue}元\n";
            }
            if ($tea_money > 0 && $dining_mode == 1) {
                $content2 .= "{$store['tea_tip']}:{$tea_money}元\n";
            }
            if ($service_money > 0 && $dining_mode == 1) {
                $content2 .= "服务费:{$service_money}元\n";
            }

            $content2 .= "总数量:" . $order['totalnum'] . "   总价:" . number_format($order['totalprice'], 2) . "元\n";
            if ($dining_mode != 4 && !empty($order['meal_time'])) {
                $content2 .= '预定时间:' . $order['meal_time'] . $huanhang;
            }
            if ($dining_mode == 2 || $dining_mode == 3) {
                if (!empty($order['username'])) {
                    $content2 .= '姓名:' . $order['username'] . $huanhang;
                }
                if (!empty($order['tel'])) {
                    $content2 .= '手机:' . $order['tel'] . $huanhang;
                }
                if (!empty($order['address'])) {
                    $content2 .= '地址:' . $order['address'] . $huanhang;
                }
            }

            if ($order['status'] == -1) {
                $print_order_data = array(
                    'weid' => $weid,
                    'orderid' => $orderid,
                    'print_usr' => $deviceNo,
                    'print_status' => -1,
                    'dateline' => TIMESTAMP
                );
                pdo_insert($this->table_print_order, $print_order_data);
                $oid = pdo_insertid();

                $cancelcontent = '订单已取消' . $huanhang;
                $cancelcontent .= '编号:' . $order['ordersn'];
                $post_data = "deviceNo=" . $deviceNo . "&key=" . $key . "&printContent=" . $cancelcontent . "&times=" . $times;
                $result = ihttp_request($target, $post_data);
                $_365status = $result['responseCode'];
                pdo_update('weisrc_dish_print_order', array('print_status' => $_365status), array('id' => $oid));
                return;
            }

            if (!empty($value['print_top'])) {
                $print_top = "" . $value['print_top'] . $huanhang;

//                if ($pos == 0) {
//                    $print_top= '^B2' . $value['print_top'] . $huanhang;
//                } else {
//                    $print_top= '<B>' . $value['print_top'] . '</B>' . $huanhang;
//                }
            }
            if (!empty($value['print_bottom'])) {
                $print_bottom = $huanhang . $value['print_bottom'] . "";
            }

            //商品
            if ($value['print_label'] == '0') {
                $goods = pdo_fetchall("SELECT * FROM " . tablename($this->table_goods) . "  WHERE id IN ('" . implode("','", array_keys($goodsid)) . "')");
            } else {
                //餐桌
                $table_label = pdo_fetch("SELECT * FROM " . tablename($this->table_tables) . " WHERE id=:id", array(":id" => $order['tables']));
                if (in_array($table_label['print_label'], explode(',', $value['print_label']))) {
                    $goods = pdo_fetchall("SELECT * FROM " . tablename($this->table_goods) . "  WHERE id IN ('" . implode("','", array_keys($goodsid)) . "')");
                } else {
                    $goods = pdo_fetchall("SELECT * FROM " . tablename($this->table_goods) . "  WHERE id IN ('" . implode("','", array_keys($goodsid)) . "') AND labelid IN(" . $value['print_label'] . ")");
                }
            }
            if (empty($goods) && $dining_mode != 3 && $dining_mode != 5) {
                continue;
            }
            $order['goods'] = $goods;
            if ($goods) {
                if ($order['isvip'] == 1) {
                    $viptip = "(会员)";
                }
                if ($order['is_append'] == 1) {
                    $appendtip = "(加单)";
                }
                $content1 = $huanhang . "商品列表{$viptip}{$appendtip}" . $huanhang;
                $content1 .= "-------------------------" . $huanhang;
            }
            if ($value['is_print_all'] == 1) {
                if ($value['type'] == '365') {
                    $print_order_data = array(
                        'weid' => $weid,
                        'orderid' => $orderid,
                        'print_usr' => $value['print_usr'],
                        'print_status' => -1,
                        'dateline' => TIMESTAMP
                    );
                    pdo_insert($this->table_print_order, $print_order_data);
                    $oid = pdo_insertid();
                }
                foreach ($order['goods'] as $v) {
                    $money = $goodsid[$v['id']]['price'];
                    $money = $money * $goodsid[$v['id']]['total'];
                    $content1 .= "" . $v['title'] . ' ' . $goodsid[$v['id']]['total'] . $v['unitname'] . ' ' . number_format($money, 2) . "元" . $huanhang;
                }
                if (!empty($value['qrcode_url']) && !empty($value['qrcode_status'])) {
                    if ($pos == 0) {
                        $print_bottom .= $huanhang . "^Q+" . $value['qrcode_url'] . $huanhang;
                    } else {
                        $print_bottom .= $huanhang . "<QR>" . $value['qrcode_url'] . "</QR>" . $huanhang;
                    }
                }

                if ($times > 0) {
                    if ($pos == 0) {
                        $times_str = "^F1^N{$times}";
                    }
                }
                $printContent = $times_str . $print_top . $content . $content1 . $content2 . $print_bottom;
                $post_data = "deviceNo=" . $deviceNo . "&key=" . $key . "&printContent=" . $printContent . "&times=" . $times;
                $result = ihttp_request($target, $post_data);
                $_365status = $result['responseCode'];
                pdo_update('weisrc_dish_print_order', array('print_status' => $_365status), array('id' => $oid));
            } else {
                $content = '订单编号:' . $order['ordersn'] . $huanhang;
                $content .= '下单日期:' . date('Y-m-d H:i:s', $order['dateline']) . $huanhang;
                $content .= '用餐人数:' . $order['counts'] . $huanhang;
                if (!empty($order['tables'])) {
                    if ($pos == 0) {
                        $content .= '^B2桌台:' . $this->getTableName($order['tables']) . $huanhang;
                    } else {
                        $content .= '<B>桌台</B>:' . $this->getTableName($order['tables']) . $huanhang;
                    }
                }
                if (!empty($order['remark'])) {
                    $content .= '备注:' . $order['remark'] . $huanhang;
                }

                foreach ($order['goods'] as $v) {
                    if ($value['type'] == '365') { //飞印
                        $print_order_data = array(
                            'weid' => $weid,
                            'orderid' => $orderid,
                            'print_usr' => $value['print_usr'],
                            'print_status' => -1,
                            'dateline' => TIMESTAMP
                        );
                        pdo_insert($this->table_print_order, $print_order_data);
                        $oid = pdo_insertid();
                    }
                    $content1 = '';
                    $content1 .= "-------------------------" . $huanhang;
                    if ($pos == 0) {
                        $content1 .= '^B2' . $v['title'] . $huanhang;
                        $content1 .= '^B2数量:' . $goodsid[$v['id']]['total'] . $v['unitname'] . $huanhang;
                    } else {
                        $content1 .= '<B>' . $v['title'] . '</B>' . $huanhang;
                        $content1 .= '<B>数量:' . $goodsid[$v['id']]['total'] . $v['unitname'] . '</B>' . $huanhang;
                    }


                    $printContent = $content . $content1;
                    $post_data = "deviceNo=" . $deviceNo . "&key=" . $key . "&printContent=" . $printContent . "&times=" . $times;
                    $result = ihttp_request($target, $post_data);
                    $_365status = $result['responseCode'];
                    pdo_update('weisrc_dish_print_order', array('print_status' => $_365status), array('id' => $oid));
                }
            }
        }
    }

    function getPrintContent($str, $len, $char)
    {
        $strlen = $this->print_strlen($str, 'gbk');
        if ($strlen > $len) {
            $content = substr($str, 0, $len);
            $content = $content . $char;
            $subcontent = substr($str, $len - 1);
            $content = $content . $subcontent;
        } else {
            $content = $str;
        }
        return $content;
    }

    //易联云
    public function _yilianyunSendFreeMessage($orderid = 0, $position_type = 0)
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        load()->func('communication');

        if ($orderid == 0) {
            return -2;
        }

        $order = $this->getOrderById($orderid);
        if (empty($order)) {
            return -3;
        }

        $storeid = $order['storeid'];
        //打印机配置信息
        $settings = pdo_fetchall("SELECT * FROM " . tablename($this->table_print_setting) . " WHERE storeid = :storeid AND print_status=1 AND type='yilianyun' ", array(':storeid' => $storeid));

        if ($settings == false) {
            return -4;
        }

        $dining_mode = $order['dining_mode']; //1:店内2:外卖3:预定4:快餐
        $paytype = $this->getPayType($dining_mode);
        $ordertype = $this->getOrderType();

        //商品id数组
        $goodsid = pdo_fetchall("SELECT goodsid,total,price FROM " . tablename($this->table_order_goods) . " WHERE orderid = :orderid", array(':orderid' => $orderid), 'goodsid');

        $store = $this->getStoreById($storeid);
        $paystatus = $order['ispay'] == 0 ? '未支付' : '已支付';
        foreach ($settings as $item => $value) {
            $yilian_type = $value['yilian_type'];
            $content = "";
            if ($dining_mode == 3) {
                $tablezones = pdo_fetch("SELECT * FROM " . tablename($this->table_tablezones) . " where weid = :weid AND id=:id LIMIT 1", array(':weid' => $weid, ':id' => $order['tablezonesid']));
                $content .= "桌台类型：{$tablezones['title']}\n";
            }
            if (!empty($order['tables'])) {
                if ($yilian_type == 1) {
                    $content .= '<FS>桌台信息:' . $this->getTableName($order['tables']) . "</FS>\n";
                } else {
                    $content .= '@@2桌台信息:' . $this->getTableName($order['tables']) . "\n";
                }
            }
            $content .= '订单编号:' . $order['ordersn'] . "\n";
            if ($dining_mode == 4) {
                $content .= '领餐牌号:' . $order['quicknum'] . "\n";
            }
            $content .= '订单类型:' . $ordertype[$dining_mode] . "\n";
            $content .= '支付方式:' . $paytype[$order['paytype']] . "(" . $paystatus . ")\n";
            $content .= '下单日期:' . date('Y-m-d H:i:s', $order['dateline']) . "\n";

            if (!empty($order['counts'])) {
                $content .= '用餐人数:' . $order['counts'] . "\n";
            }
            $content .= '所属门店:' . $store['title'] . "\n";
            if (!empty($order['remark'])) {
                $content2 = '备注:' . $order['remark'] . "\n";
            }

            $content2 .= "-------------------------\n";
            $packvalue = floatval($order['packvalue']);
            $tea_money = floatval($order['tea_money']);
            $service_money = floatval($order['service_money']);
            if ($packvalue > 0 && $dining_mode == 2) {
                $content2 .= "打包费:{$packvalue}元\n";
            }
            if ($tea_money > 0 && $dining_mode == 1) {
                $content2 .= "{$store['tea_tip']}:{$tea_money}元\n";
            }
            if ($service_money > 0 && $dining_mode == 1) {
                $content2 .= "服务费:{$service_money}元\n";
            }
            $content2 .= "<FS>总数量:" . $order['totalnum'] . "   总价:" . number_format($order['totalprice'], 2) . "元</FS>\n";
            if ($dining_mode != 4 && !empty($order['meal_time'])) {
                $content2 .= '预定时间:' . $order['meal_time'] . "\n";
            }
            if ($dining_mode == 2) {
                if (!empty($order['username'])) {
                    $content2 .= '姓名:' . $order['username'] . "\n";
                }
                if (!empty($order['tel'])) {
                    $content2 .= '手机:' . $order['tel'] . "\n";
                }
                if (!empty($order['address'])) {
                    $content2 .= '地址:' . $order['address'] . "\n";
                }
            } else {
                if (!empty($order['username'])) {
                    $content2 .= '姓名:' . $order['username'] . "\n";
                }
                if (!empty($order['tel'])) {
                    $content2 .= '手机:' . $order['tel'] . "\n";
                }
            }
            $content2 .= "-------------------------\n";
            $content2 .= '门店地址:' . $store['address'] . "\n";
            $content2 .= '门店电话:' . $store['tel'] . "\n";


            //判断打印订单类型（是否已经支付的单子）
            if ($value['print_type'] == 1 && $order['ispay'] == 0) { //支付模式未支付时
                continue;
            }
            if ($value['print_type'] == 2 && $order['status'] == 0) { //已确认模式未确认订单
                continue;
            }
            if ($position_type == 1 && $value['position_type'] == 2) { //前台模式 后厨打印机
                continue;
            }
            if ($position_type == 2 && $value['position_type'] == 1) { //后厨模式 前台打印机
                continue;
            }

            $loop_first = 0;
            if ($value['is_meal'] == 1 && $dining_mode == 1) {
                $loop_first = 1;
            }
            if ($value['is_delivery'] == 1 && $dining_mode == 2) {
                $loop_first = 1;
            }
            if ($value['is_reservation'] == 1 && $dining_mode == 3) {
                $loop_first = 1;
            }
            if ($value['is_snack'] == 1 && $dining_mode == 4) {
                $loop_first = 1;
            }
            if ($value['is_shouyin'] == 1 && $dining_mode == 5) {
                $loop_first = 1;
            }
            if ($loop_first == 0) {
                continue;
            }


            $deviceNo = $value['print_usr'];
            $key = $value['feyin_key'];
            $times = $value['print_nums'];
            $member_code = $value['member_code'];
            $api_key = $value['api_key'];

            if ($order['status'] == -1) {
                $print_order_data = array(
                    'weid' => $weid,
                    'orderid' => $orderid,
                    'print_usr' => $value['print_usr'],
                    'print_status' => -1,
                    'dateline' => TIMESTAMP
                );
                pdo_insert($this->table_print_order, $print_order_data);
                $oid = pdo_insertid();
                $cancelcontent = '订单已取消' . "\n";
                $cancelcontent .= '编号:' . $order['ordersn'];
                $print = new Yprint();
                $result = $print->action_print($member_code, $deviceNo, $cancelcontent, $api_key, $key);
                pdo_update('weisrc_dish_print_order', array('print_status' => $result['state']), array('id' => $oid));
                return;
            }

            if (!empty($value['print_top'])) {
                $print_top = "" . $value['print_top'] . "\n";
            }
            if (!empty($value['print_bottom'])) {
                $print_bottom = "\n" . $value['print_bottom'] . "";
            }

            //商品
            if ($value['print_label'] == '0') {
                $goods = pdo_fetchall("SELECT * FROM " . tablename($this->table_goods) . "  WHERE id IN ('" . implode("','", array_keys($goodsid)) . "')");
            } else {
                //餐桌
                $table_label = pdo_fetch("SELECT * FROM " . tablename($this->table_tables) . " WHERE id=:id", array(":id" => $order['tables']));
                if (in_array($table_label['print_label'], explode(',', $value['print_label']))) {
                    $goods = pdo_fetchall("SELECT * FROM " . tablename($this->table_goods) . "  WHERE id IN ('" . implode("','", array_keys($goodsid)) . "')");
                } else {
                    $goods = pdo_fetchall("SELECT * FROM " . tablename($this->table_goods) . "  WHERE id IN ('" . implode("','", array_keys($goodsid)) . "') AND labelid IN(" . $value['print_label'] . ")");
                }
            }
            if (empty($goods) && $dining_mode != 3 && $dining_mode != 5) {
                continue;
            }

            $order['goods'] = $goods;
            if ($value['is_print_all'] == 1) {
                if ($value['type'] == 'yilianyun') {
                    $print_order_data = array(
                        'weid' => $weid,
                        'orderid' => $orderid,
                        'print_usr' => $value['print_usr'],
                        'print_status' => -1,
                        'dateline' => TIMESTAMP
                    );
                    pdo_insert($this->table_print_order, $print_order_data);
                    $oid = pdo_insertid();
                }

                $content .= "-------------------------\n";
                $content1 = "<table><tr><td>品名</td><td>数量</td><td>单价</td></tr>";
                foreach ($order['goods'] as $v) {
                    $money = $goodsid[$v['id']]['price'];
                    $money = $money * $goodsid[$v['id']]['total'];
                    $content1 .= '<tr><td>' . $v['title'] . '</td><td>' . $goodsid[$v['id']]['total'] .
                        $v['unitname'] . '</td><td>' .
                        number_format($money, 2) . "元</td></tr><tr><td></td><td></td><td></td></tr>";
                }
                $content1 .= "</table>\n";

                if (!empty($value['qrcode_url']) && !empty($value['qrcode_status'])) {
                    if ($yilian_type == 1) {
                        $print_bottom .= "<QR>" . $value['qrcode_url'] . "</QR>";
                    } else {
                        $print_bottom .= "<q>" . $value['qrcode_ql'] . "</q>";
                    }
                }

                $times = intval($value['print_nums']);
                if ($times > 0) {
                    if ($yilian_type == 1) {
                        $times_str = "<MN>{$times}</MN>";
                    } else {
                        $times_str = "**{$times}";
                    }
                }
                $printContent = $times_str . $print_top . $content . $content1 . $content2 . $print_bottom;
                $print = new Yprint();
                $result = $print->action_print($member_code, $deviceNo, $printContent, $api_key, $key);
                pdo_update('weisrc_dish_print_order', array('print_status' => $result['state']), array('id' => $oid));
            } else {
                $content = '订单编号:' . $order['ordersn'] . "\n";
                $content .= '下单日期:' . date('Y-m-d H:i:s', $order['dateline']) . "\n";
                $content .= '用餐人数:' . $order['counts'] . "\n";
                if (!empty($order['tables'])) {
                    if ($yilian_type == 1) {
                        $content .= '<FS>桌台:' . $this->getTableName($order['tables']) . "</FS>\n";
                    } else {
                        $content .= '@@2桌台:' . $this->getTableName($order['tables']) . "\n";
                    }
                }

                foreach ($order['goods'] as $v) {
                    if ($value['type'] == 'yilianyun') { //飞印
                        $print_order_data = array(
                            'weid' => $weid,
                            'orderid' => $orderid,
                            'print_usr' => $value['print_usr'],
                            'print_status' => -1,
                            'dateline' => TIMESTAMP
                        );
                        pdo_insert($this->table_print_order, $print_order_data);
                        $oid = pdo_insertid();
                    }
                    $content1 = '';
                    $content1 .= "-------------------------\n";
                    if ($yilian_type == 1) {
                        $content1 .= '<FS>' . $v['title'] . "</FS>\n";
                        $content1 .= '数量:<FS>' . $goodsid[$v['id']]['total'] . $v['unitname'] . "</FS>\n";
                    } else {
                        $content1 .= '@@2' . $v['title'] . "\n";
                        $content1 .= '@@2数量:' . $goodsid[$v['id']]['total'] . $v['unitname'] . "\n";
                    }

                    $deviceNo = $value['print_usr'];
                    $key = $value['feyin_key'];
                    $times = $value['print_nums'];
                    $member_code = $value['member_code'];
                    $api_key = $value['api_key'];
                    $printContent = $content . $content1;
                    $print = new Yprint();
                    $result = $print->action_print($member_code, $deviceNo, $printContent, $api_key, $key);
                    pdo_update('weisrc_dish_print_order', array('print_status' => $result['state']), array('id' => $oid));
                }
            }
        }
    }
//进云物联
    public function _jinyunSendFreeMessage($orderid = 0, $position_type = 0)
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        load()->func('communication');

        if ($orderid == 0) {
            return -2;
        }

        $order = $this->getOrderById($orderid);
        if (empty($order)) {
            return -3;
        }

        $storeid = $order['storeid'];
        //打印机配置信息
        $settings = pdo_fetchall("SELECT * FROM " . tablename($this->table_print_setting) . " WHERE storeid = :storeid AND print_status=1 AND type='jinyun' ", array(':storeid' => $storeid));

        if ($settings == false) {
            return -4;
        }

        $dining_mode = $order['dining_mode']; //1:店内2:外卖3:预定4:快餐
        $paytype = $this->getPayType($dining_mode);
        $ordertype = $this->getOrderType();

        //商品id数组
        $goodsid = pdo_fetchall("SELECT goodsid,total,price FROM " . tablename($this->table_order_goods) . " WHERE orderid = :orderid", array(':orderid' => $orderid), 'goodsid');

        $store = $this->getStoreById($storeid);
        $paystatus = $order['ispay'] == 0 ? '未支付' : '已支付';
        foreach ($settings as $item => $value) {
            $jinyun_type = $value['jinyun_type'];
            $content = "";
            if ($dining_mode == 3) {
                $tablezones = pdo_fetch("SELECT * FROM " . tablename($this->table_tablezones) . " where weid = :weid AND id=:id LIMIT 1", array(':weid' => $weid, ':id' => $order['tablezonesid']));
                $content .= "桌台类型：{$tablezones['title']}\n";
            }
            if (!empty($order['tables'])) {
                if ($jinyun_type == 1) {
                    $content .= '<S2>桌台信息:' . $this->getTableName($order['tables']) . "</S2>\n";
                } else {
                    $content .= '@@2桌台信息:' . $this->getTableName($order['tables']) . "\n";
                }
            }
            $content .= '订单编号:' . $order['ordersn'] . "\n";
            if ($dining_mode == 4) {
                $content .= '领餐牌号:' . $order['quicknum'] . "\n";
            }
            $content .= '订单类型:' . $ordertype[$dining_mode] . "\n";
            $content .= '支付方式:' . $paytype[$order['paytype']] . "(" . $paystatus . ")\n";
            $content .= '下单日期:' . date('Y-m-d H:i:s', $order['dateline']) . "\n";

            if (!empty($order['counts'])) {
                $content .= '用餐人数:' . $order['counts'] . "\n";
            }
            $content .= '所属门店:' . $store['title'] . "\n";
			$content2 = '';
            if (!empty($order['remark'])) {
                $content2 = '备注:' . $order['remark'] . "\n";
            }
            $packvalue = floatval($order['packvalue']);
            $tea_money = floatval($order['tea_money']);
            $service_money = floatval($order['service_money']);
            if ($packvalue > 0 && $dining_mode == 2) {
                $content2 .= "打包费:{$packvalue}元\n";
            }
            if ($tea_money > 0 && $dining_mode == 1) {
                $content2 .= "{$store['tea_tip']}:{$tea_money}元\n";
            }
            if ($service_money > 0 && $dining_mode == 1) {
                $content2 .= "服务费:{$service_money}元\n";
            }
            $content2 .= "<S2>总数量:" . $order['totalnum'] . "   总价:" . number_format($order['totalprice'], 2) . "元</S2>\n";
            if ($dining_mode != 4 && !empty($order['meal_time'])) {
                $content2 .= '预定时间:' . $order['meal_time'] . "\n";
            }
            if ($dining_mode == 2) {
                if (!empty($order['username'])) {
                    $content2 .= '姓名:' . $order['username'] . "\n";
                }
                if (!empty($order['tel'])) {
                    $content2 .= '手机:' . $order['tel'] . "\n";
                }
                if (!empty($order['address'])) {
                    $content2 .= '地址:' . $order['address'] . "\n";
                }
            } else {
                if (!empty($order['username'])) {
                    $content2 .= '姓名:' . $order['username'] . "\n";
                }
                if (!empty($order['tel'])) {
                    $content2 .= '手机:' . $order['tel'] . "\n";
                }
            }
            $content2 .= "-------------------------------\n";
            $content2 .= '门店地址:' . $store['address'] . "\n";
            $content2 .= '门店电话:' . $store['tel'] . "\n";


            //判断打印订单类型（是否已经支付的单子）
            if ($value['print_type'] == 1 && $order['ispay'] == 0) { //支付模式未支付时
                continue;
            }
            if ($value['print_type'] == 2 && $order['status'] == 0) { //已确认模式未确认订单
                continue;
            }
            if ($position_type == 1 && $value['position_type'] == 2) { //前台模式 后厨打印机
                continue;
            }
            if ($position_type == 2 && $value['position_type'] == 1) { //后厨模式 前台打印机
                continue;
            }

            $loop_first = 0;
            if ($value['is_meal'] == 1 && $dining_mode == 1) {
                $loop_first = 1;
            }
            if ($value['is_delivery'] == 1 && $dining_mode == 2) {
                $loop_first = 1;
            }
            if ($value['is_reservation'] == 1 && $dining_mode == 3) {
                $loop_first = 1;
            }
            if ($value['is_snack'] == 1 && $dining_mode == 4) {
                $loop_first = 1;
            }
            if ($value['is_shouyin'] == 1 && $dining_mode == 5) {
                $loop_first = 1;
            }
            if ($loop_first == 0) {
                continue;
            }

			$imei = $value['print_usr'];
            $dtuid = $value['feyin_key'];
            $times = $value['print_nums'];
            $tokend = $value['member_code'];
            $apikey = $value['api_key'];
            if ($order['status'] == -1) {
                $print_order_data = array(
                    'weid' => $weid,
                    'orderid' => $orderid,
                    'print_usr' => $value['print_usr'],
                    'print_status' => -1,
                    'dateline' => TIMESTAMP
                );
                pdo_insert($this->table_print_order, $print_order_data);
                $oid = pdo_insertid();
                $cancelcontent = '订单已取消' . "\n";
                $cancelcontent .= '编号:' . $order['ordersn'];
                $print = new JinYunZN();
		$result = printer($cancelcontent,$this->modulename,$value['printid']);
                pdo_update('weisrc_dish_print_order', array('print_status' => $result['state']), array('id' => $oid));
                return;
            }
			$print_top = '';
			$print_bottom = '';
            if (!empty($value['print_top'])) {
                $print_top = "" . $value['print_top'] . "\n";
            }
            if (!empty($value['print_bottom'])) {
                $print_bottom = "\n" . $value['print_bottom'] . "";
            }

            //商品
            if ($value['print_label'] == '0') {
                $goods = pdo_fetchall("SELECT * FROM " . tablename($this->table_goods) . "  WHERE id IN ('" . implode("','", array_keys($goodsid)) . "')");
            } else {
                //餐桌
                $table_label = pdo_fetch("SELECT * FROM " . tablename($this->table_tables) . " WHERE id=:id", array(":id" => $order['tables']));
                if (in_array($table_label['print_label'], explode(',', $value['print_label']))) {
                    $goods = pdo_fetchall("SELECT * FROM " . tablename($this->table_goods) . "  WHERE id IN ('" . implode("','", array_keys($goodsid)) . "')");
                } else {
                    $goods = pdo_fetchall("SELECT * FROM " . tablename($this->table_goods) . "  WHERE id IN ('" . implode("','", array_keys($goodsid)) . "') AND labelid IN(" . $value['print_label'] . ")");
                }
            }
            if (empty($goods) && $dining_mode != 3 && $dining_mode != 5) {
                continue;
            }

            $order['goods'] = $goods;
            if ($value['is_print_all'] == 1) {
                if ($value['type'] == 'jinyun') {
                    $print_order_data = array(
                        'weid' => $weid,
                        'orderid' => $orderid,
                        'print_usr' => $value['print_usr'],
                        'print_status' => -1,
                        'dateline' => TIMESTAMP
                    );
                    pdo_insert($this->table_print_order, $print_order_data);
                    $oid = pdo_insertid();
                }

                $content .= "-------------------------------\n";
                $content1 = "名称　             数量    单价\n";
                foreach ($order['goods'] as $v) {
                    $money = $goodsid[$v['id']]['price'];
					$str = '';
					$str .= '' . str_pad($v['title'], '23', ' ', STR_PAD_RIGHT);
					$str .= '' . str_pad($goodsid[$v['id']]['total'].$v['unitname'], '5', ' ', STR_PAD_RIGHT);
					$str .= ''. number_format($money, 2) . '元\n';
                    $content1 .= $str;
                }
				$content1 .= "-------------------------------\n";
                if (!empty($value['qrcode_url']) && !empty($value['qrcode_status'])) {
                    if ($jinyun_type == 1) {
                        $print_bottom .= "<AM><QR_T>QR_CODE</QR_T><QR_S>5</QR_S><QR_D>" . $value['qrcode_url'] . "</QR_D></AM>\n";
                    } else {
                        $print_bottom .= " ";
                    }
                }

                $times = intval($value['print_nums']);
                if ($times > 0) {
                    for($i=0;$i<$times;$i++){
						$nums = $i+1;
						if ($jinyun_type == 1) {
							$times_str = "<S3>第{$nums}联--</S3>";
						} else {
							$times_str = "第{$nums}联--";
						}
						$printContent = $times_str . $print_top . $content . $content1 . $content2 . $print_bottom;
						//$result = $print->SendPrintData($tokend, $apikey, $dtuid, $imei, $printContent);
						$result = printer($printContent,$this->modulename,$value['printid']);
					}
                }else{
						$printContent = $times_str . $print_top . $content . $content1 . $content2 . $print_bottom;
						//$result = $print->SendPrintData($tokend, $apikey, $dtuid, $imei, $printContent);
						$result = printer($printContent,$this->modulename,$value['printid']);
				}
                pdo_update('weisrc_dish_print_order', array('print_status' => $result['state']), array('id' => $oid));
            } else {
                $content = '订单编号:' . $order['ordersn'] . "\n";
                $content .= '下单日期:' . date('Y-m-d H:i:s', $order['dateline']) . "\n";
                $content .= '用餐人数:' . $order['counts'] . "\n";
                if (!empty($order['tables'])) {
                    if ($yilian_type == 1) {
                        $content .= '<S3>桌台:' . $this->getTableName($order['tables']) . "</S3>\n";
                    } else {
                        $content .= '@@2桌台:' . $this->getTableName($order['tables']) . "\n";
                    }
                }

                foreach ($order['goods'] as $v) {
                    if ($value['type'] == 'jinyun') {
                        $print_order_data = array(
                            'weid' => $weid,
                            'orderid' => $orderid,
                            'print_usr' => $value['print_usr'],
                            'print_status' => -1,
                            'dateline' => TIMESTAMP
                        );
                        pdo_insert($this->table_print_order, $print_order_data);
                        $oid = pdo_insertid();
                    }
                    $content1 = '';
                    $content1 .= "-------------------------------\n";
                    if ($jinyun_type == 1) {
                        $content1 .= '<S2>' . $v['title'] . "</S2>\n";
                        $content1 .= '数量:<S2>' . $goodsid[$v['id']]['total'] . $v['unitname'] . "</S2>\n";
                    } else {
                        $content1 .= '@@2' . $v['title'] . "\n";
                        $content1 .= '@@2数量:' . $goodsid[$v['id']]['total'] . $v['unitname'] . "\n";
                    }

                    $imei = $value['print_usr'];
                    $dtuid = $value['feyin_key'];
                    $times = $value['print_nums'];
                    $tokend = $value['member_code'];
                    $apikey = $value['api_key'];
                    $printContent = $content . $content1;
                    //$result = $print->SendPrintData($tokend, $apikey, $dtuid, $imei, $printContent);
					$result = printer($printContent,$this->modulename,$value['printid']);
                    pdo_update('weisrc_dish_print_order', array('print_status' => $result['state']), array('id' => $oid));
                }
            }
        }
    }
    //飞鹅
    public function feieSendFreeMessage($orderid = 0, $position_type = 0)
    {
        global $_W, $_GPC;

        if ($orderid == 0) {
            return -2;
        }

        $order = $this->getOrderById($orderid);
        if (empty($order)) {
            return -3;
        }

        $weid = $order['weid'];
        $storeid = $order['storeid'];

        $store = $this->getStoreById($storeid);
        //打印机配置信息
        $settings = pdo_fetchall("SELECT * FROM " . tablename($this->table_print_setting) . " WHERE storeid = :storeid AND print_status=1 AND type='feie' ", array(':storeid' => $storeid));

        if ($settings == false) {
            return -4;
        }

        //1:店内2:外卖3:预定4:快餐
        $dining_mode = $order['dining_mode'];
        $paytype = $this->getPayType($dining_mode);
        $ordertype = $this->getOrderType();

        //商品id数组
        $goodsid = pdo_fetchall("SELECT goodsid,total,price FROM " . tablename($this->table_order_goods) . " WHERE orderid = :orderid", array(':orderid' => $orderid), 'goodsid');

        $paystatus = $order['ispay'] == 0 ? '未支付' : '已支付';

        $content = "订单编号:" . $order['ordersn'] . "<BR>";
        if ($dining_mode == 4) {
            $content .= '领餐牌号:' . $order['quicknum'] . "<BR>";
        }
        $content .= '订单类型:' . $ordertype[$dining_mode] . "<BR>";
        $content .= '所属门店:' . $store['title'] . "<BR>";
        $content .= '支付方式:' . $paytype[$order['paytype']] . "(" . $paystatus . ")<BR>";
        $content .= '下单日期:' . date('Y-m-d H:i:s', $order['dateline']) . "<BR>";
        if ($dining_mode == 3) {
            $tablezones = pdo_fetch("SELECT * FROM " . tablename($this->table_tablezones) . " where weid = :weid AND id=:id LIMIT 1", array(':weid' => $weid, ':id' => $order['tablezonesid']));
            $content .= "桌台类型：{$tablezones['title']}<BR>";
        }
        if (!empty($order['tables'])) {
            $content .= '桌台信息:<B>' . $this->getTableName($order['tables']) . "</B><BR>";
        }
        if (!empty($order['counts'])) {
            $content .= '用餐人数:' . $order['counts'] . "<BR>";
        }
        if (!empty($order['remark'])) {
            $content .= '备注:' . $order['remark'] . "<BR>";
        }
        $content .= '门店地址:' . $store['address'] . "<BR>";
        $content .= '门店电话:' . $store['tel'] . "<BR>";

        $content2 = "-------------------------<BR>";
        $packvalue = floatval($order['packvalue']);
        $tea_money = floatval($order['tea_money']);
        $service_money = floatval($order['service_money']);
        if ($packvalue > 0 && $dining_mode == 2) {
            $content2 .= "打包费:{$packvalue}元<BR>";
        }
        if ($tea_money > 0 && $dining_mode == 1) {
            $content2 .= "{$store['tea_tip']}:{$tea_money}元<BR>";
        }
        if ($service_money > 0 && $dining_mode == 1) {
            $content2 .= "服务费:{$service_money}元<BR>";
        }
        $content2 .= "总数量:" . $order['totalnum'] . "   总价:" . number_format($order['totalprice'], 2) . "元<BR>";
        if ($dining_mode != 4 && !empty($order['meal_time'])) {
            $content2 .= '预定时间:' . $order['meal_time'] . "<BR>";
        }
        if (!empty($order['username'])) {
            $content2 .= '姓名:' . $order['username'] . "<BR>";
        }
        if (!empty($order['tel'])) {
            $content2 .= '手机:' . $order['tel'] . "<BR>";
        }
        if (!empty($order['address'])) {
            $content2 .= '地址:' . $order['address'] . "<BR>";
        }
        $content2 .= "-------------------------<BR>";

        //打印机
        foreach ($settings as $item => $value) {
            //判断打印订单类型（是否已经支付的单子）
            //支付模式未支付时
            if ($value['print_type'] == 1 && $order['ispay'] == 0) {
                continue;
            }
            //已确认模式未确认订单
            if ($value['print_type'] == 2 && $order['status'] == 0) {
                continue;
            }
            //前台模式 后厨打印机
            if ($position_type == 1 && $value['position_type'] == 2) {
                continue;
            }
            //后厨模式 前台打印机
            if ($position_type == 2 && $value['position_type'] == 1) {
                continue;
            }

            $loop_first = 0;
            if ($value['is_meal'] == 1 && $dining_mode == 1) {
                $loop_first = 1;
            }
            if ($value['is_delivery'] == 1 && $dining_mode == 2) {
                $loop_first = 1;
            }
            if ($value['is_reservation'] == 1 && $dining_mode == 3) {
                $loop_first = 1;
            }
            if ($value['is_snack'] == 1 && $dining_mode == 4) {
                $loop_first = 1;
            }
            if ($value['is_shouyin'] == 1 && $dining_mode == 5) {
                $loop_first = 1;
            }
            if ($loop_first == 0) {
                continue;
            }

            $FEIE_IP = 'dzp.feieyun.com';
            $api_type = intval($value['api_type']);
            if ($api_type == 0) {
                $pos = strpos($value['print_usr'], '6');
                if ($pos == 2) {
                    $FEIE_IP = 'api163.feieyun.com';
                }
                $pos = strpos($value['print_usr'], '7');
                if ($pos == 2) {
                    $FEIE_IP = 'api174.feieyun.com';
                }
            } elseif ($api_type == 1) {
                $FEIE_IP = 'dzp.feieyun.com';
            } elseif ($api_type == 2) {
                $FEIE_IP = 'api163.feieyun.com';
            } elseif ($api_type == 3) {
                $FEIE_IP = 'api174.feieyun.com';
            } elseif ($api_type == 4) {
                $FEIE_IP = 'api.feieyun.cn';
            }

            $client = new HttpClient($FEIE_IP, FEIE_PORT);
            if ($order['status'] == -1) {
                $print_order_data = array(
                    'weid' => $weid,
                    'orderid' => $orderid,
                    'print_usr' => $value['print_usr'],
                    'print_status' => -1,
                    'dateline' => TIMESTAMP
                );
                pdo_insert($this->table_print_order, $print_order_data);
                $oid = pdo_insertid();

                $cancelcontent = '^订单已取消' . "\n";
                $cancelcontent .= '编号:' . $order['ordersn'];
                $feie_content = array(
                    'sn' => $value['print_usr'],
                    'printContent' => $cancelcontent,
                    'key' => $value['feyin_key'],
                    'times' => 1//打印次数
                );
                $result = $client->post(FEIE_HOSTNAME . '/printOrderAction', $feie_content);
                $_feiestatus = $result['responseCode'];
                pdo_update('weisrc_dish_print_order', array('print_status' => $_feiestatus), array('id' => $oid));
                return;
            }

            if (!empty($value['print_top'])) {
                $print_top = "<CB>" . $value['print_top'] . "</CB><BR>";
            }
            if (!empty($value['print_bottom'])) {
                $print_bottom = "<C>" . $value['print_bottom'] . "</C>";
            }
            //商品
            if ($value['print_label'] == '0') {
                $goods = pdo_fetchall("SELECT * FROM " . tablename($this->table_goods) . "  WHERE id IN ('" . implode("','", array_keys($goodsid)) . "')");
            } else {
                //餐桌
                $table_label = pdo_fetch("SELECT * FROM " . tablename($this->table_tables) . " WHERE id=:id", array(":id" => $order['tables']));
                if (in_array($table_label['print_label'], explode(',', $value['print_label']))) {
                    $goods = pdo_fetchall("SELECT * FROM " . tablename($this->table_goods) . "  WHERE id IN ('" . implode("','", array_keys($goodsid)) . "')");
                } else {
                    $goods = pdo_fetchall("SELECT * FROM " . tablename($this->table_goods) . "  WHERE id IN ('" . implode("','", array_keys($goodsid)) . "') AND labelid IN(" . $value['print_label'] . ")");
                }
            }
            if (empty($goods) && $dining_mode != 3 && $dining_mode != 5) {
                continue;
            }
            $order['goods'] = $goods;
            if ($goods) {
                if ($order['isvip'] == 1) {
                    $viptip = "(会员)";
                }
                if ($order['is_append'] == 1) {
                    $appendtip = "(加单)";
                }
                $content1 = "商品列表{$viptip}{$appendtip}<BR>";
                $content1 .= "-------------------------<BR>";
            }

            if ($value['is_print_all'] == 1) {
                if ($value['type'] == 'feie') {
                    $print_order_data = array(
                        'weid' => $weid,
                        'orderid' => $orderid,
                        'print_usr' => $value['print_usr'],
                        'print_status' => -1,
                        'dateline' => TIMESTAMP
                    );
                    pdo_insert($this->table_print_order, $print_order_data);
                    $oid = pdo_insertid();
                }
                foreach ($order['goods'] as $v) {
                    $money = $goodsid[$v['id']]['price'];
                    $money = $money * $goodsid[$v['id']]['total'];
                    $content1 .= $v['title'] . ' ' . $goodsid[$v['id']]['total'] . $v['unitname'] . ' ' . number_format($money, 2) . "元\n";
                }
                if (!empty($value['qrcode_url']) && !empty($value['qrcode_status'])) {
                    $print_bottom .= "<QR>" . $value['qrcode_url'] . "</QR>";
                }
                //喇叭
                $a = array("\x1b", "\x64", "\x01", "\x1b", "\x70", "\x30", "\x1e", "\x78");
                $box = implode("", $a);

                $printContent = $print_top . $content . $content1 . $content2 . $print_bottom . $box;

                $feie_content = array(
                    'sn' => $value['print_usr'],
                    'printContent' => $printContent,
                    'key' => $value['feyin_key'],
                    'times' => $value['print_nums']//打印次数
                );

                $result = $client->post(FEIE_HOSTNAME . '/printOrderAction', $feie_content);
                $_feiestatus = $result['responseCode'];
                pdo_update('weisrc_dish_print_order', array('print_status' => $_feiestatus), array('id' => $oid));
            } else { //分单
                $content = '订单编号:' . $order['ordersn'] . "<BR>";
                $content .= '下单日期:' . date('Y-m-d H:i:s', $order['dateline']) . "<BR>";
                if (!empty($order['tables'])) {
                    $content .= '<B>桌台信息:</B><DB>' . $this->getTableName($order['tables']) . "</DB><BR>";
                }
                if (!empty($order['remark'])) {
                    $content .= '<DB>备注:' . $order['remark'] . "</DB><BR>";
                }
                foreach ($order['goods'] as $v) {
                    if ($value['type'] == 'feie') { //飞印
                        $print_order_data = array(
                            'weid' => $weid,
                            'orderid' => $orderid,
                            'print_usr' => $value['print_usr'],
                            'print_status' => -1,
                            'dateline' => TIMESTAMP
                        );
                        pdo_insert($this->table_print_order, $print_order_data);
                        $oid = pdo_insertid();
                    }
                    $content1 = '';
                    $content1 .= "------------------------------------------------<BR>";
                    $content1 .= '<B>名称:' . $v['title'] . "</B><BR>";
                    $content1 .= '<B>数量:' . $goodsid[$v['id']]['total'] . $v['unitname'] . "</B><BR>";
                    $money = $goodsid[$v['id']]['price'];
                    $content1 .= '<B>价格:' . number_format($money, 2) . "元</B><BR>";
                    $content1 .= "------------------------------------------------<BR>";
                    $printContent = $print_top . $content . $content1 . $print_bottom;
                    $feie_content = array(
                        'sn' => $value['print_usr'],
                        'printContent' => $printContent,
                        'key' => $value['feyin_key'],
                        'times' => $value['print_nums']//打印次数
                    );

                    $result = $client->post(FEIE_HOSTNAME . '/printOrderAction', $feie_content);
                    $_feiestatus = $result['responseCode'];
                    pdo_update('weisrc_dish_print_order', array('print_status' => $_feiestatus), array('id' => $oid));
                }
            }
        }
    }

    //飞印
    public function _feiyinSend($orderids, $position_type = 0)
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $totalprice = 0;
        $orderinfo_content = "";
        foreach ($orderids as $k => $id) {
            $order = $this->getOrderById($id);
            $storeid = $order['storeid'];
            $settings = pdo_fetchall("SELECT * FROM " . tablename($this->table_print_setting) . " WHERE storeid = :storeid AND print_status=1 AND type='feiyin' ", array(':storeid' => $storeid));

            $store = $this->getStoreById($storeid);
            $orderinfo_content .= "-------------------------\n";
            $orderinfo_content .= '订单编号:' . $order['ordersn'] . "\n";

            $service_money = floatval($order['service_money']);
            if ($service_money > 0) {
                $service_money_content = "服务费:{$service_money}元\n";
            }

            $tables_content = '桌台信息:' . $this->getTableName($order['tables']) . "\n";
            $totalprice = $totalprice + floatval($order['totalprice']);
            $totalprice_content = "总价:" . number_format($totalprice, 2) . "元\n";

            //商品id数组
            $goodsid = pdo_fetchall("SELECT goodsid,total,price FROM " . tablename($this->table_order_goods) . " WHERE orderid = :orderid", array(':orderid' => $id), 'goodsid');
            $goods = pdo_fetchall("SELECT * FROM " . tablename($this->table_goods) . "  WHERE id IN ('" . implode("','", array_keys($goodsid)) . "')");

            foreach ($goods as $v) {
                $money = $goodsid[$v['id']]['price'];
                $orderinfo_content .= $v['title'] . ' ' . $goodsid[$v['id']]['total'] . $v['unitname'] . ' ' . number_format($money, 2) . "元\n";
            }
        }

        $print_content = "商家小票\n";
        $print_content .= "-------------------------\n";
        $print_content .= $tables_content;
        $print_content .= $orderinfo_content;
        $print_content .= "-------------------------\n";
        $print_content .= $totalprice_content;

        foreach ($settings as $item => $value) {
            $this->member_code = $value['member_code'];
            $this->device_no = $value['print_usr'];
            $this->feyin_key = $value['feyin_key'];

            if ($position_type == 1 && $value['position_type'] == 2) { //前台模式 后厨打印机
                continue;
            }
            if ($position_type == 2 && $value['position_type'] == 1) { //后厨模式 前台打印机
                continue;
            }

            if ($value['is_print_all'] == 1) {
                if ($value['type'] == 'feiyin') { //飞印
                    $print_order_data = array(
                        'weid' => $weid,
                        'orderid' => 0,
                        'print_usr' => $value['print_usr'],
                        'print_status' => -1,
                        'dateline' => TIMESTAMP
                    );
                    pdo_insert($this->table_print_order, $print_order_data);
                    $oid = pdo_insertid();
                }

                $msgNo = time() + 1;
                $freeMessage = array(
                    'memberCode' => $this->member_code,
                    'msgDetail' => $print_content,
                    'deviceNo' => $this->device_no,
                    'msgNo' => $oid,
                );
                $feiyinstatus = $this->sendFreeMessage($freeMessage);
                pdo_update('weisrc_dish_print_order', array('print_status' => $feiyinstatus), array('id' => $oid));
            }
        }

        return $msgNo;
    }

    //飞鹅
    public function _feieSend($orderids, $position_type = 0, $isend = 0)
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $totalprice = 0;
        $orderinfo_content = "";
        $paytype = $this->getPayType(1);
        foreach ($orderids as $k => $id) {
            $order = $this->getOrderById($id);
            $storeid = $order['storeid'];
            $settings = pdo_fetchall("SELECT * FROM " . tablename($this->table_print_setting) . " WHERE storeid = :storeid AND print_status=1 AND type='feie' ", array(':storeid' => $storeid));

            $store = $this->getStoreById($storeid);
            $orderinfo_content .= "-------------------------<BR>";
            $orderinfo_content .= '订单编号:' . $order['ordersn'] . "<BR>";
            if ($isend == 1) {
                $orderinfo_content .= '支付方式:' . $paytype[$order['paytype']] . "<BR>";
            }

            $service_money = floatval($order['service_money']);
            if ($service_money > 0) {
                $service_money_content = "服务费:{$service_money}元<BR>";
            }

            $tables_content = '桌台信息:' . $this->getTableName($order['tables']) . "<BR>";
            $totalprice = $totalprice + floatval($order['totalprice']);
            $totalprice_content = "总价:" . number_format($totalprice, 2) . "元<BR>";

            //商品id数组
            $goodsid = pdo_fetchall("SELECT goodsid,total,price FROM " . tablename($this->table_order_goods) . " WHERE orderid = :orderid", array(':orderid' => $id), 'goodsid');
            $goods = pdo_fetchall("SELECT * FROM " . tablename($this->table_goods) . "  WHERE id IN ('" . implode("','", array_keys($goodsid)) . "')");

            foreach ($goods as $v) {
                $money = $goodsid[$v['id']]['price'];
                $goodstotalprice = intval($goodsid[$v['id']]['total']) * $money;
                $goodstotalprice = number_format($goodstotalprice, 2);
                $orderinfo_content .= $v['title'] . ' ' . $goodsid[$v['id']]['total'] . $v['unitname'] . ' ' .
                    number_format($money, 2) . "元" . ' ' . $goodstotalprice . "元<BR>";
            }
        }
        if ($isend == 1) {
            $print_content = "商家小票(已支付)\n";
        } else {
            $print_content = "商家小票\n";
        }

        $print_content .= "-------------------------<BR>";
        $print_content .= $tables_content;
        $print_content .= $orderinfo_content;
        $print_content .= "-------------------------<BR>";
        $print_content .= $totalprice_content;

        foreach ($settings as $item => $value) {
            $this->member_code = $value['member_code'];
            $this->device_no = $value['print_usr'];
            $this->feyin_key = $value['feyin_key'];

            if ($position_type == 1 && $value['position_type'] == 2) { //前台模式 后厨打印机
                continue;
            }
            if ($position_type == 2 && $value['position_type'] == 1) { //后厨模式 前台打印机
                continue;
            }

            $FEIE_IP = 'dzp.feieyun.com';
            $api_type = intval($value['api_type']);
            if ($api_type == 0) {
                $pos = strpos($value['print_usr'], '6');
                if ($pos == 2) {
                    $FEIE_IP = 'api163.feieyun.com';
                }
                $pos = strpos($value['print_usr'], '7');
                if ($pos == 2) {
                    $FEIE_IP = 'api174.feieyun.com';
                }
            } elseif ($api_type == 1) {
                $FEIE_IP = 'dzp.feieyun.com';
            } elseif ($api_type == 2) {
                $FEIE_IP = 'api163.feieyun.com';
            } elseif ($api_type == 3) {
                $FEIE_IP = 'api174.feieyun.com';
            } elseif ($api_type == 4) {
                $FEIE_IP = 'api.feieyun.cn';
            }
            $client = new HttpClient($FEIE_IP, FEIE_PORT);

            if ($value['is_print_all'] == 1) {
                if ($value['type'] == 'feiyin') { //飞印
                    $print_order_data = array(
                        'weid' => $weid,
                        'orderid' => 0,
                        'print_usr' => $value['print_usr'],
                        'print_status' => -1,
                        'dateline' => TIMESTAMP
                    );
                    pdo_insert($this->table_print_order, $print_order_data);
                    $oid = pdo_insertid();
                }
                $feie_content = array(
                    'sn' => $value['print_usr'],
                    'printContent' => $print_content,
                    'key' => $value['feyin_key'],
                    'times' => 1//打印次数
                );

                $result = $client->post(FEIE_HOSTNAME . '/printOrderAction', $feie_content);
                $_feiestatus = $result['responseCode'];
                pdo_update('weisrc_dish_print_order', array('print_status' => $_feiestatus), array('id' => $oid));;
            }
        }
    }

    //飞印
    public function feiyinSendFreeMessage($orderid = 0, $position_type = 0)
    {
        global $_W, $_GPC;
        $weid = $this->_weid;

        if ($orderid == 0) {
            return -2;
        }

        $order = $this->getOrderById($orderid);

        if (empty($order)) {
            return -3;
        }

        $storeid = $order['storeid'];
        //打印机配置信息
        $settings = pdo_fetchall("SELECT * FROM " . tablename($this->table_print_setting) . " WHERE storeid = :storeid AND print_status=1 AND type='feiyin' ", array(':storeid' => $storeid));

        if ($settings == false) {
            return -4;
        }

        $dining_mode = $order['dining_mode']; //1:店内2:外卖3:预定4:快餐
        $paytype = $this->getPayType($dining_mode);
        $ordertype = $this->getOrderType();

        //商品id数组
        $goodsid = pdo_fetchall("SELECT goodsid,total,price FROM " . tablename($this->table_order_goods) . " WHERE orderid = :orderid", array(':orderid' => $orderid), 'goodsid');

        $store = $this->getStoreById($storeid);

        $paystatus = $order['ispay'] == 0 ? '未支付' : '已支付';
        $content = '订单编号:' . $order['ordersn'] . "\n";
        if ($dining_mode == 4) {
            $content .= '领餐牌号:' . $order['quicknum'] . "\n";
        }
        $content .= '订单类型:' . $ordertype[$dining_mode] . "\n";
        $content .= '所属门店:' . $store['title'] . "\n";
        $content .= '支付方式:' . $paytype[$order['paytype']] . "(" . $paystatus . ")\n";
        $content .= '下单日期:' . date('Y-m-d H:i:s', $order['dateline']) . "\n";
        if ($dining_mode == 3) {
            $tablezones = pdo_fetch("SELECT * FROM " . tablename($this->table_tablezones) . " where weid = :weid AND id=:id LIMIT 1", array(':weid' => $weid, ':id' => $order['tablezonesid']));
            $content .= "桌台类型：{$tablezones['title']}\n";
        }
        if (!empty($order['tables'])) {
            $content .= '桌台信息:' . $this->getTableName($order['tables']) . "\n";
        }
        if (!empty($order['counts'])) {
            $content .= '用餐人数:' . $order['counts'] . "\n";
        }
        if (!empty($order['remark'])) {
            $content .= '备注:' . $order['remark'] . "\n";
        }
        $content .= '门店地址:' . $store['address'] . "\n";
        $content .= '门店电话:' . $store['tel'] . "\n";

        $content2 = "-------------------------\n";
        $packvalue = floatval($order['packvalue']);
        $tea_money = floatval($order['tea_money']);
        $service_money = floatval($order['service_money']);
        if ($packvalue > 0 && $dining_mode == 2) {
            $content2 .= "打包费:{$packvalue}元\n";
        }
        if ($tea_money > 0 && $dining_mode == 1) {
            $content2 .= "{$store['tea_tip']}:{$tea_money}元\n";
        }
        if ($service_money > 0 && $dining_mode == 1) {
            $content2 .= "服务费:{$service_money}元\n";
        }
        $content2 .= "总数量:" . $order['totalnum'] . "   总价:" . number_format($order['totalprice'], 2) . "元\n";
        if ($dining_mode != 4 && !empty($order['meal_time'])) {
            $content2 .= '预定时间:' . $order['meal_time'] . "\n";
        }
        if (!empty($order['username'])) {
            $content2 .= '姓名:' . $order['username'] . "\n";
        }
        if (!empty($order['tel'])) {
            $content2 .= '手机:' . $order['tel'] . "\n";
        }
        if (!empty($order['address'])) {
            $content2 .= '地址:' . $order['address'] . "\n";
        }
        $content2 .= "-------------------------\n";

        foreach ($settings as $item => $value) {
            //判断打印订单类型（是否已经支付的单子）
            if ($value['print_type'] == 1 && $order['ispay'] == 0) { //支付模式未支付时
                continue;
            }
            if ($value['print_type'] == 2 && $order['status'] == 0) { //已确认模式未确认订单
                continue;
            }
            if ($position_type == 1 && $value['position_type'] == 2) { //前台模式 后厨打印机
                continue;
            }
            if ($position_type == 2 && $value['position_type'] == 1) { //后厨模式 前台打印机
                continue;
            }

            $loop_first = 0;
            if ($value['is_meal'] == 1 && $dining_mode == 1) {
                $loop_first = 1;
            }
            if ($value['is_delivery'] == 1 && $dining_mode == 2) {
                $loop_first = 1;
            }
            if ($value['is_reservation'] == 1 && $dining_mode == 3) {
                $loop_first = 1;
            }
            if ($value['is_snack'] == 1 && $dining_mode == 4) {
                $loop_first = 1;
            }
            if ($value['is_shouyin'] == 1 && $dining_mode == 5) {
                $loop_first = 1;
            }
            if ($loop_first == 0) {
                continue;
            }

            if (!empty($value['print_top'])) {
                $print_top = "" . $value['print_top'] . "\n";
            }
            if (!empty($value['print_bottom'])) {
                $print_bottom = "\n" . $value['print_bottom'] . "";
            }

            $this->member_code = $value['member_code'];
            $this->device_no = $value['print_usr'];
            $this->feyin_key = $value['feyin_key'];

            if ($order['status'] == -1) {
                $print_order_data = array(
                    'weid' => $weid,
                    'orderid' => $orderid,
                    'print_usr' => $value['print_usr'],
                    'print_status' => -1,
                    'dateline' => TIMESTAMP
                );
                pdo_insert($this->table_print_order, $print_order_data);
                $oid = pdo_insertid();
                $cancelcontent = '^订单已取消' . "\n";
                $cancelcontent .= '编号:' . $order['ordersn'];
                $freeMessage = array(
                    'memberCode' => $this->member_code,
                    'msgDetail' => $cancelcontent,
                    'deviceNo' => $this->device_no,
                    'msgNo' => $oid,
                );

                $feiyinstatus = $this->sendFreeMessage($freeMessage);
                pdo_update('weisrc_dish_print_order', array('print_status' => $feiyinstatus), array('id' => $oid));
                return;
            }

            //商品
            if ($value['print_label'] == '0') {
                $goods = pdo_fetchall("SELECT * FROM " . tablename($this->table_goods) . "  WHERE id IN ('" . implode("','", array_keys($goodsid)) . "')");
            } else {
                //餐桌
                $table_label = pdo_fetch("SELECT * FROM " . tablename($this->table_tables) . " WHERE id=:id", array(":id" => $order['tables']));
                if (in_array($table_label['print_label'], explode(',', $value['print_label']))) {
                    $goods = pdo_fetchall("SELECT * FROM " . tablename($this->table_goods) . "  WHERE id IN ('" . implode("','", array_keys($goodsid)) . "')");
                } else {
                    $goods = pdo_fetchall("SELECT * FROM " . tablename($this->table_goods) . "  WHERE id IN ('" . implode("','", array_keys($goodsid)) . "') AND labelid IN(" . $value['print_label'] . ")");
                }
            }
            if (empty($goods) && $dining_mode != 3 && $dining_mode != 5) {
                continue;
            }
            $order['goods'] = $goods;
            if ($goods) {
                if ($order['isvip'] == 1) {
                    $viptip = "(会员)";
                }
                if ($order['is_append'] == 1) {
                    $appendtip = "(加单)";
                }
                $content1 = "商品列表{$viptip}{$appendtip}\n";
                $content1 .= "-------------------------\n";
            }

            if ($value['is_print_all'] == 1) {
                if ($value['type'] == 'feiyin') { //飞印
                    $print_order_data = array(
                        'weid' => $weid,
                        'orderid' => $orderid,
                        'print_usr' => $value['print_usr'],
                        'print_status' => -1,
                        'dateline' => TIMESTAMP
                    );
                    pdo_insert($this->table_print_order, $print_order_data);
                    $oid = pdo_insertid();
                }

                foreach ($order['goods'] as $v) {
                    $money = $goodsid[$v['id']]['price'];
                    $money = $money * $goodsid[$v['id']]['total'];
                    $content1 .= $v['title'] . ' ' . $goodsid[$v['id']]['total'] . $v['unitname'] . ' ' . number_format($money, 2) . "元\n";
                }
                $msgDetail = $print_top . $content . $content1 . $content2 . $print_bottom;
                $msgNo = time() + 1;
                $freeMessage = array(
                    'memberCode' => $this->member_code,
                    'msgDetail' => $msgDetail,
                    'deviceNo' => $this->device_no,
                    'msgNo' => $oid,
                );
                $feiyinstatus = $this->sendFreeMessage($freeMessage);
                pdo_update('weisrc_dish_print_order', array('print_status' => $feiyinstatus), array('id' => $oid));
            } else {
                $content = '订单编号:' . $order['ordersn'] . "\n";
                $content .= '所属门店:' . $store['title'] . "\n";
                $content .= '支付方式:' . $paytype[$order['paytype']] . "(" . $paystatus . ")\n";
                $content .= '下单日期:' . date('Y-m-d H:i:s', $order['dateline']) . "\n";
                if (!empty($order['tables'])) {
                    $content .= '桌台信息:' . $this->getTableName($order['tables']) . "\n";
                }
                if (!empty($order['counts'])) {
                    $content .= '用餐人数:' . $order['counts'] . "\n";
                }
                if (!empty($order['remark'])) {
                    $content .= '备注:' . $order['remark'] . "\n";
                }

                foreach ($order['goods'] as $v) {
                    if ($value['type'] == 'feiyin') { //飞印
                        $print_order_data = array(
                            'weid' => $weid,
                            'orderid' => $orderid,
                            'print_usr' => $value['print_usr'],
                            'print_status' => -1,
                            'dateline' => TIMESTAMP
                        );
                        pdo_insert($this->table_print_order, $print_order_data);
                        $oid = pdo_insertid();
                    }
                    $content1 = '';
                    $content1 .= "-------------------------\n";
                    $content1 .= '名称:' . $v['title'] . "\n";
                    $content1 .= '数量:' . $goodsid[$v['id']]['total'] . $v['unitname'] . "\n";
                    $content1 .= "-------------------------\n";

                    $msgDetail = $print_top . $content . $content1 . $content2 . $print_bottom;
                    $msgNo = time() + 1;
                    $freeMessage = array(
                        'memberCode' => $this->member_code,
                        'msgDetail' => $msgDetail,
                        'deviceNo' => $this->device_no,
                        'msgNo' => $oid,
                    );
                    $feiyinstatus = $this->sendFreeMessage($freeMessage);
                    pdo_update('weisrc_dish_print_order', array('print_status' => $feiyinstatus), array('id' => $oid));
                }
            }
        }
        return $msgNo;
    }

    public function getGoodsTitle($str1, $len, $stradd = "")
    {
        $i = 0;
        $str2 = "^B2";
        if ($len % 2 == 1)
            $len = $len + 1;
        $len1 = strlen($str1);
        for ($i = 0; $i < $len1 / $len; $i++) {
            $str2 .= '^B2' . substr($str1, $len * $i, $len) . $stradd;
            $str1 = substr($str1, $len * $i, $len1 - $len * $i);
        }
        return $str2;
    }

    //用户打印机处理订单
    private function feiyinformat($string, $length = 0, $isleft = true)
    {
        $substr = '';
        if ($length == 0 || $string == '') {
            return $string;
        }
        if ($this->print_strlen($string) > $length) {
            for ($i = 0; $i < $length; $i++) {
                $substr = $substr . "  ";
            }
            $string = $string . $substr;
        } else {
            for ($i = $this->print_strlen($string); $i < $length; $i++) {
                $substr = $substr . " ";
            }
            $string = $isleft ? ($string . $substr) : ($substr . $string);
        }
        return $string;
    }

    /**
     * @param string $l
     * @param string $r
     * @return string
     */
    public function formatstr($l = '', $r = '')
    {
        $nbsp = '                              ';
        $llen = $this->print_strlen($l);
        $rlen = $this->print_strlen($r);
        if ($l && $r) {
            $lr = $llen + $rlen;
            $nl = $this->print_strlen($nbsp);
            if ($lr >= $nl) {
                $strtxt = $l . "\r\n" . $this->formatstr(null, $r);
            } else {
                $strtxt = $l . substr($nbsp, $lr) . $r;
            }
        } elseif ($r) {
            $strtxt = substr($nbsp, $rlen) . $r;
        } else {
            $strtxt = $l;
        }
        return $strtxt;
    }

    /**
     * PHP获取字符串中英文混合长度
     * @param $str        字符串
     * @param string $charset 编码
     * @return int 返回长度，1中文=2位(utf-8为3位)，1英文=1位
     */
    private function print_strlen($str, $charset = '')
    {
        global $_W;
        if (empty($charset)) {
            $charset = $_W['charset'];
        }
        if (strtolower($charset) == 'gbk') {
            $charset = 'gbk';
            $ci = 2;
        } else {
            $charset = 'utf-8';
            $ci = 3;
        }
        if (strtolower($charset) == 'utf-8')
            $str = iconv('utf-8', 'GBK//IGNORE', $str);
        $num = strlen($str);
        $cnNum = 0;
        for ($i = 0; $i < $num; $i++) {
            if (ord(substr($str, $i + 1, 1)) > 127) {
                $cnNum++;
                $i++;
            }
        }
        $enNum = $num - ($cnNum * $ci);
        $number = $enNum + $cnNum * $ci;
        return ceil($number);
    }

    public function sendQueueNotice($oid, $status = 1)
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;

        $setting = pdo_fetch("select * from " . tablename($this->table_setting) . " WHERE weid =:weid LIMIT 1", array(':weid' => $weid));
        $order = pdo_fetch("select * from " . tablename($this->table_queue_order) . " WHERE id =:id LIMIT 1", array(':id' => $oid));
        $store = $this->getStoreById($order['storeid']);
        $keyword1 = $order['num'];
        $keyword2 = date("Y-m-d H:i", $order['dateline']);
        $host = $this->getOAuthHost();
        $url = $host . 'app' . str_replace('./', '/', $this->createMobileUrl('queue', array('storeid' => $order['storeid']), true));
        $wait_count = pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename($this->table_queue_order) . " WHERE status=1 AND storeid=:storeid AND id<:id AND queueid=:queueid ORDER BY id DESC", array(':id' => $oid, ':storeid' => $order['storeid'], ':queueid' => $order['queueid']));
        $queueStatus = array(
            '1' => '排队中',
            '2' => '已接受',
            '-1' => '已取消',
            '3' => '已过号'
        );

        if (!empty($setting['tplnewqueue']) && $setting['istplnotice'] == 1) {
            $templateid = $setting['tplnewqueue'];
            if ($setting['tpltype'] == 1) {
                if ($status == 1) { //排队中
                    $first = "排号提醒：编号{$keyword1}已成功领号，您可以点击本消息提前点菜，节约等待时间哦";
                    $remark = "门店名称：{$store['title']}";
                    $remark .= "\n排队号码：" . $this->getQueueName($order['queueid']) . " " . $order['num'];
                    $remark .= "\n前面等待：" . intval($wait_count);
                    $remark .= "\n排队状态：排队中";
                } else if ($status == 2) { //排队提醒
                    $first = "排号提醒：还需等待{$wait_count}桌";
                    $remark = "门店名称：{$store['title']}";
                    $remark .= "\n排队号码：" . $this->getQueueName($order['queueid']) . " " . $order['num'];
                    $remark .= "\n前面等待：" . intval($wait_count) . "桌";
                    $remark .= "\n排队状态：" . $queueStatus[$order['status']];
                } else if ($status == 3) { //取消提醒
                    $first = "排号取消提醒：编号" . $order['num'] . "已取消";
                    $remark = "您在{$store['title']}的排队状态更新为已取消，如有疑问，请联系我们工作人员";
                    $remark .= "\n排队号码：" . $this->getQueueName($order['queueid']) . " " . $order['num'];
                    $remark .= "\n排队状态：已取消";
                }

                $content = array(
                    'first' => array(
                        'value' => $first,
                        'color' => '#a6a6a9'
                    ),
                    'keyword1' => array(
                        'value' => $keyword1,
                        'color' => '#a6a6a9'
                    ),
                    'keyword2' => array(
                        'value' => $keyword2,
                        'color' => '#a6a6a9'
                    ),
                    'remark' => array(
                        'value' => $remark,
                        'color' => '#a6a6a9'
                    ),
                );
            } else {
                $keyword3 = intval($wait_count);
                if ($status == 1) { //排队中
                    $first = "排号提醒：编号{$keyword1}已成功领号，您可以点击本消息提前点菜，节约等待时间哦";
                    $remark = "门店名称：{$store['title']}";
                    $remark .= "\n排队号码：" . $this->getQueueName($order['queueid']) . " " . $order['num'];
                    $remark .= "\n排队状态：排队中";
                } else if ($status == 2) { //排队提醒
                    $first = "排号提醒：还需等待{$wait_count}桌";
                    $remark = "门店名称：{$store['title']}";
                    $remark .= "\n排队号码：" . $this->getQueueName($order['queueid']) . " " . $order['num'];
                    $remark .= "\n排队状态：" . $queueStatus[$order['status']];
                } else if ($status == 3) { //取消提醒
                    $first = "排号取消提醒：编号" . $order['num'] . "已取消";
                    $remark = "您在{$store['title']}的排队状态更新为已取消，如有疑问，请联系我们工作人员";
                    $remark .= "\n排队号码：" . $this->getQueueName($order['queueid']) . " " . $order['num'];
                    $remark .= "\n排队状态：已取消";
                }

                $content = array(
                    'first' => array(
                        'value' => $first,
                        'color' => '#a6a6a9'
                    ),
                    'keyword1' => array(
                        'value' => $keyword1,
                        'color' => '#a6a6a9'
                    ),
                    'keyword2' => array(
                        'value' => $keyword2,
                        'color' => '#a6a6a9'
                    ),
                    'keyword3' => array(
                        'value' => $keyword3,
                        'color' => '#a6a6a9'
                    ),
                    'remark' => array(
                        'value' => $remark,
                        'color' => '#a6a6a9'
                    ),
                );
            }

            pdo_update($this->table_queue_order, array('isnotify' => 1), array('id' => $oid));
            load()->model('account');
            $access_token = WeAccount::token();
            $templateMessage = new templateMessage();
            $templateMessage->send_template_message($order['from_user'], $templateid, $content, $access_token, $url);
        } else {
            if ($status == 1) { //排队中
                $content = '排号提醒：编号' . $keyword1 . '已成功领号，您可以<a href=\"' . $url . '\">点击本消息</a>提前点菜，节约等待时间哦';
                $content .= "\n当前排号：{$keyword1}";
                $content .= "\n取号时间：{$keyword2}";
                $content .= "\n门店名称：{$store['title']}";
                $content .= "\n排队号码：" . $this->getQueueName($order['queueid']) . " " . $order['num'];
                $content .= "\n前面等待：" . intval($wait_count);
                $content .= "\n排队状态：排队中";
            } else if ($status == 2) { //排队提醒
                $content = "排号提醒：还需等待{$wait_count}桌";
                $content .= "\n当前排号：{$keyword1}";
                $content .= "\n取号时间：{$keyword2}";
                $content .= "\n门店名称：{$store['title']}";
                $content .= "\n排队号码：" . $this->getQueueName($order['queueid']) . " " . $order['num'];
                $content .= "\n前面等待：" . intval($wait_count) . "桌";
                $content .= "\n排队状态：" . $queueStatus[$order['status']];
            } else if ($status == 3) { //取消提醒
                $content = "排号取消提醒：编号" . $order['num'] . "已取消";
                $content .= "\n当前排号：{$keyword1}";
                $content .= "\n取号时间：{$keyword2}";
                $content .= "\n您在{$store['title']}的排队状态更新为已取消，如果疑问，请联系我们工作人员";
                $content .= "\n排队号码：" . $this->getQueueName($order['queueid']) . " " . $order['num'];
                $content .= "\n排队状态：已取消";
            }
            $this->sendText($order['from_user'], $content);
        }
    }

    public function sendAdminQueueNotice($oid, $from_user, $setting)
    {
        global $_W, $_GPC;
        $weid = $this->_weid;

        $order = pdo_fetch("select * from " . tablename($this->table_queue_order) . " WHERE id =:id LIMIT 1", array(':id' => $oid));
        $store = $this->getStoreById($order['storeid']);
        $keyword1 = $order['num'];
        $keyword2 = date("Y-m-d H:i", $order['dateline']);
        $url = '';
        $wait_count = pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename($this->table_queue_order) . " WHERE status=1 AND storeid=:storeid AND queueid=:queueid ORDER BY id DESC", array(':storeid' => $order['storeid'], ':queueid' => $order['queueid']));

        if (!empty($setting['tplnewqueue']) && $setting['istplnotice'] == 1 && $setting['is_notice'] == 1 && !empty($from_user)) {
            $templateid = $setting['tplnewqueue'];

            $first = '排号提醒：有新的排号，编号' . $keyword1;
            if ($setting['tpltype'] == 1) {
                $remark = "门店名称：{$store['title']}";
                $remark .= "\n排队号码：" . $this->getQueueName($order['queueid']) . " " . $order['num'];
                $remark .= "\n排队等待：" . intval($wait_count) . '队';

                $content = array(
                    'first' => array(
                        'value' => $first,
                        'color' => '#a6a6a9'
                    ),
                    'keyword1' => array(
                        'value' => $keyword1,
                        'color' => '#a6a6a9'
                    ),
                    'keyword2' => array(
                        'value' => $keyword2,
                        'color' => '#a6a6a9'
                    ),
                    'remark' => array(
                        'value' => $remark,
                        'color' => '#a6a6a9'
                    ),
                );
            } else {
                $remark = "门店名称：{$store['title']}";
                $remark .= "\n排队号码：" . $this->getQueueName($order['queueid']) . " " . $order['num'];

                $keyword3 = intval($wait_count);

                $content = array(
                    'first' => array(
                        'value' => $first,
                        'color' => '#a6a6a9'
                    ),
                    'keyword1' => array(
                        'value' => $keyword1,
                        'color' => '#a6a6a9'
                    ),
                    'keyword2' => array(
                        'value' => $keyword2,
                        'color' => '#a6a6a9'
                    ),
                    'keyword3' => array(
                        'value' => $keyword3,
                        'color' => '#a6a6a9'
                    ),
                    'remark' => array(
                        'value' => $remark,
                        'color' => '#a6a6a9'
                    )
                );
            }

            pdo_update($this->table_queue_order, array('isnotify' => 1), array('id' => $oid));
            load()->model('account');
            $access_token = WeAccount::token();
            $templateMessage = new templateMessage();
            $templateMessage->send_template_message($from_user, $templateid, $content, $access_token, $url);
        } else {
            if (!empty($setting['tpluser'])) { //排队中
                $content = '排号提醒：有新的排号，编号' . $keyword1;
                $content .= "\n当前排号：{$keyword1}";
                $content .= "\n取号时间：{$keyword2}";
                $content .= "\n门店名称：{$store['title']}";
                $content .= "\n排队号码：" . $this->getQueueName($order['queueid']) . " " . $order['num'];
                $content .= "\n排队等待：" . intval($wait_count) . '队';
            }
            $this->sendText($from_user, $content);
        }
    }

    public function sendOrderSms($order)
    {
        global $_W, $_GPC;
        $weid = $order['weid'];

        //发送短信提醒
        $smsSetting = pdo_fetch("SELECT * FROM " . tablename($this->table_setting) . " WHERE weid=:weid LIMIT 1", array(':weid' => $weid));
        $sendInfo = array();
        if (!empty($smsSetting)) {
            if ($smsSetting['sms_enable'] == 1 && !empty($order['tel'])) {
                //模板
                $smsSetting['sms_business_tpl'] = '您的订单：[sn]，收货人：[name] 电话：[tel]，已经成功提交。感谢您的购买！';
                //订单号
                $smsSetting['sms_business_tpl'] = str_replace('[sn]', $order['ordersn'], $smsSetting['sms_business_tpl']);
                $smsSetting['sms_business_tpl'] = str_replace('[name]', $order['username'], $smsSetting['sms_business_tpl']);
                $smsSetting['sms_business_tpl'] = str_replace('[tel]', $order['tel'], $smsSetting['sms_business_tpl']);

                $sendInfo['username'] = $smsSetting['sms_username'];
                $sendInfo['pwd'] = $smsSetting['sms_pwd'];
                $sendInfo['mobile'] = $order['tel'];
                $sendInfo['content'] = $smsSetting['sms_business_tpl'];
                //debug

                $return_result_code = $this->_sendSms($sendInfo);
                $smsStatus = $this->sms_status[$return_result_code];
            }
        }
    }

    public function sendAdminOrderSms($mobile, $order)
    {
        global $_W, $_GPC;
        $weid = $order['weid'];

        //发送短信提醒
        $smsSetting = pdo_fetch("SELECT * FROM " . tablename($this->table_setting) . " WHERE weid=:weid LIMIT 1", array(':weid' => $weid));
        $sendInfo = array();
        if (!empty($smsSetting)) {
            if ($smsSetting['sms_enable'] == 1 && !empty($mobile)) {
                $smsSetting['sms_business_tpl'] = '您有新的订单：[sn]，收货人：[name]，电话：[tel]，请及时确认订单！';
                $smsSetting['sms_business_tpl'] = str_replace('[sn]', $order['ordersn'], $smsSetting['sms_business_tpl']);
                $smsSetting['sms_business_tpl'] = str_replace('[name]', $order['username'], $smsSetting['sms_business_tpl']);
                $smsSetting['sms_business_tpl'] = str_replace('[tel]', $order['tel'], $smsSetting['sms_business_tpl']);

                $sendInfo['username'] = $smsSetting['sms_username'];
                $sendInfo['pwd'] = $smsSetting['sms_pwd'];
                $sendInfo['mobile'] = $mobile;
                $sendInfo['content'] = $smsSetting['sms_business_tpl'];
                //debug

                $return_result_code = $this->_sendSms($sendInfo);
                $smsStatus = $this->sms_status[$return_result_code];
            }
            return $smsStatus;
        }
    }

    public function sendAdminOrderEmail($toemail, $order, $store, $goods_str)
    {
        $firstArr = array(
            '-1' => '已经取消',
            '0' => '已经提交',
            '1' => '已经确认',
            '2' => '已并台',
            '3' => '已经完成'
        );

        $orderStatus = array(
            '-1' => '已取消',
            '0' => '待处理',
            '1' => '已确认',
            '2' => '已并台',
            '3' => '已完成'
        );
        $paytype = array(
            '0' => '现金付款',
            '1' => '余额支付',
            '2' => '微信支付',
            '3' => '现金付款',
            '4' => '支付宝'
        );
        $paystatus = array(
            '0' => '未支付',
            '1' => '已支付'
        );

        //发送邮件提醒
        $emailSetting = pdo_fetch("SELECT * FROM " . tablename($this->table_setting) . " WHERE weid=:weid LIMIT 1", array(':weid' => $order['weid']));

        $keyword1 = $order['ordersn'];
        $keyword2 = $orderStatus[$order['status']];
        $keyword3 = date("Y-m-d H:i", $order['dateline']);

        $email_tpl = "
        您的订单{$order['ordersn']}{$firstArr[$order['status']]}<br/>
        订单号：{$keyword1}<br/>
        订单状态：{$keyword2}<br/>
        时间：{$keyword3}<br/>
        门店名称：{$store['title']}<br/>
        支付方式：{$paytype[$order['paytype']]}<br/>
        支付状态：{$paystatus[$order['ispay']]}<br/>
        ";
        if ($order['dining_mode'] == 3) {
            $email_tpl .= "预定人信息：{$order['username']}－{$order['tel']}<br/>";
            $email_tpl .= "预定时间：{$order['meal_time']}<br/>";
        } else {
            $email_tpl .= "联系方式：{$order['username']}－{$order['tel']}<br/>";
        }
        if ($order['dining_mode'] == 1) {
            $tablename = $this->getTableName($order['tables']);
            $email_tpl .= "桌台信息：{$tablename}<br/>";
        }
        if ($order['dining_mode'] == 2) {
            if (!empty($order['address'])) {
                $email_tpl .= "配送地址：{$order['address']}<br/>";
            }
            if (!empty($order['meal_time'])) {
                $email_tpl .= "配送时间：{$order['meal_time']}<br/>";
            }
        }
        $email_tpl .= "菜单：{$goods_str}<br/>";
        $email_tpl .= "备注：{$order['remark']}<br/>";
        $email_tpl .= "应收合计：{$order['totalprice']}";

        if (!empty($emailSetting) && !empty($emailSetting['email'])) {
            if ($emailSetting['email_host'] == 'smtp.qq.com' || $emailSetting['email_host'] == 'smtp.gmail.com') {
                $secure = 'ssl';
                $port = '465';
            } else {
                $secure = 'tls';
                $port = '25';
            }

            $mail_config = array();
            $mail_config['host'] = $emailSetting['email_host'];
            $mail_config['secure'] = $secure;
            $mail_config['port'] = $port;
            $mail_config['username'] = $emailSetting['email_user'];
            $mail_config['sendmail'] = $emailSetting['email_send'];
            $mail_config['password'] = $emailSetting['email_pwd'];
            $mail_config['mailaddress'] = $toemail;
            $mail_config['subject'] = '订单提醒';
            $mail_config['body'] = $email_tpl;
            $result = $this->sendmail($mail_config);
        }
    }

    public function sendUserDeliveryNotice($order, $setting)
    {
        global $_W, $_GPC;
        $weid = $this->_weid;

        $orderStatus = array(
            '-1' => '已取消',
            '0' => '待处理',
            '1' => '已确认',
            '2' => '已并台',
            '3' => '已完成'
        );

        $site_url = str_replace('addons/bm_payu/', '', $_W['siteroot']);
        $site_url = str_replace('addons/bm_payms/', '', $site_url);

        $url = $site_url . 'app' . str_replace('./', '/', $this->createMobileUrl('orderdetail', array('orderid' => $order['id']), true));
        $keyword1 = $order['ordersn'];
        $keyword2 = $orderStatus[$order['status']];
        $keyword3 = date("Y-m-d H:i", $order['dateline']);

        if (!empty($setting['tplneworder']) && $setting['istplnotice'] == 1) {
            $delivery_id = $order['delivery_id'];
            $deliveryuser = pdo_fetch("SELECT * FROM " . tablename($this->table_account) . " where id=:id LIMIT 1", array(':id' => $delivery_id));

            $templateid = $setting['tplneworder'];
            $first = "配送员已经接单";

            $remark = "配送员：" . $deliveryuser['username'];
            $remark .= "\n联系电话：" . $deliveryuser['mobile'];

            if ($setting['tpltype'] == 1) {
                $content = array(
                    'first' => array(
                        'value' => $first,
                        'color' => '#FF0033'
                    ),
                    'keyword1' => array(
                        'value' => $keyword1,
                        'color' => '#a6a6a9'
                    ),
                    'keyword2' => array(
                        'value' => $keyword2,
                        'color' => '#a6a6a9'
                    ),
                    'keyword3' => array(
                        'value' => $keyword3,
                        'color' => '#a6a6a9'
                    ),
                    'remark' => array(
                        'value' => $remark,
                        'color' => '#a6a6a9'
                    ),
                );
            } else {
                $content = array(
                    'first' => array(
                        'value' => $first,
                        'color' => '#FF0033'
                    ),
                    'keyword1' => array(
                        'value' => $keyword1,
                        'color' => '#a6a6a9'
                    ),
                    'keyword2' => array(
                        'value' => $keyword3,
                        'color' => '#a6a6a9'
                    ),
                    'remark' => array(
                        'value' => $remark,
                        'color' => '#a6a6a9'
                    )
                );
            }

            load()->model('account');
            $access_token = WeAccount::token();
            $templateMessage = new templateMessage();
            $templateMessage->send_template_message($order['from_user'], $templateid, $content, $access_token, $url);
        }
    }

    public function sendOrderNotice($order, $store, $setting)
    {
        global $_W, $_GPC;
        $weid = $this->_weid;

        $firstArr = array(
            '-1' => '已经取消',
            '0' => '已经提交',
            '1' => '已经确认',
            '2' => '已并台',
            '3' => '已经完成'
        );

        $orderStatus = array(
            '-1' => '已取消',
            '0' => '待处理',
            '1' => '已确认',
            '2' => '已并台',
            '3' => '已完成'
        );
        $paytype = array(
            '0' => '现金付款',
            '1' => '余额支付',
            '2' => '微信支付',
            '3' => '现金付款',
            '4' => '支付宝'
        );
        $paystatus = array(
            '0' => '未支付',
            '1' => '已支付'
        );

        $site_url = str_replace('addons/bm_payu/', '', $_W['siteroot']);
        $site_url = str_replace('addons/bm_payms/', '', $site_url);

        $url = $site_url . 'app' . str_replace('./', '/', $this->createMobileUrl('orderdetail', array('orderid' => $order['id']), true));
        $keyword1 = $order['ordersn'];
        $keyword2 = $orderStatus[$order['status']];
        $keyword3 = date("Y-m-d H:i", $order['dateline']);

        if (!empty($setting['tplneworder']) && $setting['istplnotice'] == 1) {
            $templateid = $setting['tplneworder'];
            $first = "您的订单{$order['ordersn']}{$firstArr[$order['status']]}";
            $remark = "门店名称：{$store['title']}";
            $remark .= "\n支付方式：{$paytype[$order['paytype']]}";
            $remark .= "\n支付状态：{$paystatus[$order['ispay']]}";
            if ($order['dining_mode'] == 3) {
                $remark .= "\n预定人信息：{$order['username']}－{$order['tel']}";
                $tablename = $this->getTableName($order['tables']);
                $remark .= "\n桌台信息：{$tablename}";
                $remark .= "\n预定时间：{$order['meal_time']}";
            } else {
                $remark .= "\n联系方式：{$order['username']}－{$order['tel']}";
            }
            if ($order['dining_mode'] == 1) {
                $tablename = $this->getTableName($order['tables']);
                $remark .= "\n桌台信息：{$tablename}";
            }
            if ($order['dining_mode'] == 2) {
                if (!empty($order['address'])) {
                    $remark .= "\n配送地址：{$order['address']}";
                }
                if (!empty($order['meal_time'])) {
                    $remark .= "\n配送时间：{$order['meal_time']}";
                }
            }
            if (!empty($order['remark'])) {
                $remark .= "\n备注：{$order['remark']}";
            }
            if (!empty($order['reply'])) {
                $remark .= "\n商家回复：{$order['reply']}";
            }

            $remark .= "\n应收合计：{$order['totalprice']}元";
            if ($order['credit'] > 0) {
                $remark .= "\n奖励积分：{$order['credit']}";
            }
            if ($setting['tpltype'] == 1) {
                $content = array(
                    'first' => array(
                        'value' => $first,
                        'color' => '#a6a6a9'
                    ),
                    'keyword1' => array(
                        'value' => $keyword1,
                        'color' => '#a6a6a9'
                    ),
                    'keyword2' => array(
                        'value' => $keyword2,
                        'color' => '#a6a6a9'
                    ),
                    'keyword3' => array(
                        'value' => $keyword3,
                        'color' => '#a6a6a9'
                    ),
                    'remark' => array(
                        'value' => $remark,
                        'color' => '#a6a6a9'
                    ),
                );
            } else {
                $remark = "订单状态：" . $keyword2 . "\n" . $remark;
                $content = array(
                    'first' => array(
                        'value' => $first,
                        'color' => '#a6a6a9'
                    ),
                    'keyword1' => array(
                        'value' => $keyword1,
                        'color' => '#a6a6a9'
                    ),
                    'keyword2' => array(
                        'value' => $keyword3,
                        'color' => '#a6a6a9'
                    ),
                    'remark' => array(
                        'value' => $remark,
                        'color' => '#a6a6a9'
                    )
                );
            }

            load()->model('account');
            $access_token = WeAccount::token();
            $templateMessage = new templateMessage();
            $templateMessage->send_template_message($order['from_user'], $templateid, $content, $access_token, $url);
        } else {
            $content = "您的订单{$order['ordersn']}{$firstArr[$order['status']]}";
            $content .= "\n订单号：{$keyword1}";
            $content .= "\n订单状态：{$keyword2}";
            $content .= "\n时间：{$keyword3}";
            $content .= "\n门店名称：{$store['title']}";
            $content .= "\n支付方式：{$paytype[$order['paytype']]}";
            $content .= "\n支付状态：{$paystatus[$order['ispay']]}";

            if ($order['dining_mode'] == 3) {
                if ($order['dining_mode'] == 3) {
                    $tablezones = pdo_fetch("SELECT * FROM " . tablename($this->table_tablezones) . " where weid = :weid AND id=:id LIMIT 1", array(':weid' => $weid, ':id' => $order['tablezonesid']));
                }
                $content .= "\n预定人信息：{$order['username']}－{$order['tel']}";
                $content .= "\n桌台类型：{$tablezones['title']}";
                $content .= "\n预定时间：{$order['meal_time']}";
            } else {
                $content .= "\n联系方式：{$order['username']}－{$order['tel']}";
            }
            if ($order['dining_mode'] == 1) {
                $tablename = $this->getTableName($order['tables']);
                $content .= "\n桌台信息：{$tablename}";
            }
            if ($order['dining_mode'] == 2) {
                if (!empty($order['address'])) {
                    $content .= "\n配送地址：{$order['address']}";
                }
                if (!empty($order['meal_time'])) {
                    $content .= "\n配送时间：{$order['meal_time']}";
                }
            }
            if (!empty($order['remark'])) {
                $content .= "\n备注：{$order['remark']}";
            }
            if (!empty($order['reply'])) {
                $content .= "\n商家回复：{$order['reply']}";
            }

            $content .= "\n应收合计：{$order['totalprice']}元";
            if ($order['credit'] > 0) {
                $content .= "\n奖励积分：{$order['credit']}";
            }
            $this->sendText($order['from_user'], $content);
        }
    }

    public function addTplLog($order, $from_user, $content, $result)
    {
        global $_W, $_GPC;
        $insert = array(
            'weid' => $order['weid'],
            'from_user' => $from_user,
            'storeid' => $order['storeid'],
            'orderid' => $order['id'],
            'ordersn' => $order['ordersn'],
            'content' => $content,
            'result' => $result,
            'dateline' => TIMESTAMP
        );
        pdo_insert($this->table_tpl_log, $insert);
    }

//老板通知
    public function sendBossNotice($date, $from_user, $templateid, $content)
    {
        global $_W, $_GPC;
        $first = $date . $content['store']['title'];
        //营业总额
        $keyword1 = $content['totalNum']['totalPrice'];
        //营业收入
        $keyword2 = $content['totalNum']['totalPrice'];
        //营业数量
        $keyword3 = $content['totalNum']['totalNum'];
        //营业次数
        $keyword4 = intval($content['totalNum']['peopleNum']);
        //二次信息
        $remark = "----------------\n交易详情:\n";
        foreach ($content['payPrice'] as $v) {
            switch ($v['paytype']) {
                case 0:
                    $remark .= "现金支付：" . $v['total'] . "元（{$v['num']}单）\n";
                    break;
                case 1:
                    $remark .= "余额支付：" . $v['total'] . "元（{$v['num']}单）\n";
                    break;
                case 2:
                    $remark .= "微信支付：" . $v['total'] . "元（{$v['num']}单）\n";
                    break;
                case 3:
                    $remark .= "现金支付：" . $v['total'] . "元（{$v['num']}单）\n";
                    break;
                case 4:
                    $remark .= "支付宝支付：" . $v['total'] . "元（{$v['num']}单）\n";
                    break;
                default:
                    $remark .= "其它支付：" . $v['total'] . "元（{$v['num']}单）\n";
                    break;
            }
        }
        $remark .= "---------------";
        $remark .= "\n营业详情";
        $remark .= "\n总单数：" . intval($content['totalNum']['totalNum']);
        $remark .= "\n总交易：" . sprintf("%.2f", $content['totalNum']['totalPrice']) . "元";
        $remark .= "\n总人数：" . intval($content['totalNum']['peopleNum']);
        $remark .= "\n人均消费：" . sprintf("%.2f", $content['totalNum']['avgPrice']);
        $data = array(
            'first' => array(
                'value' => $first,
                'color' => '#a6a6a9'
            ),
            'keyword1' => array(
                'value' => $keyword1,
                'color' => '#a6a6a9'
            ),
            'keyword2' => array(
                'value' => $keyword2,
                'color' => '#a6a6a9'
            ),
            'keyword3' => array(
                'value' => $keyword3,
                'color' => '#a6a6a9'
            ),
            'keyword4' => array(
                'value' => $keyword4,
                'color' => '#a6a6a9'
            ),
            'remark' => array(
                'value' => $remark,
                'color' => '#a6a6a9'
            )
        );
        $url = "";
        load()->model('account');
        $access_token = WeAccount::token();
        $templateMessage = new templateMessage();
        $result = $templateMessage->send_template_message($from_user, $templateid, $data, $access_token, $url);
        $result = $result['errmsg'] == 'ok' ? '发送成功' : $result['errmsg'];
        $this->addTplLog($date, $from_user, '老板信息通知', $result);
    }

    public function sendDeliveryOrderNotice($oid, $from_user, $setting, $isall = 0)
    {
        global $_W, $_GPC;
        $weid = $this->_weid;

        $orderStatus = array(
            '-1' => '已取消',
            '0' => '待处理',
            '1' => '已确认',
            '2' => '已并台',
            '3' => '已完成'
        );
        $paytype = array(
            '0' => '现金付款',
            '1' => '余额支付',
            '2' => '微信支付',
            '3' => '现金付款',
            '4' => '支付宝'
        );
        $ordertype = array(
            '1' => '堂点',
            '2' => '外卖',
            '3' => '预定',
            '4' => '快餐',
            '5' => '收银',
            '6' => '充值'
        );
        $paystatus = array(
            '0' => '未支付',
            '1' => '已支付'
        );

        $order = pdo_fetch("select * from " . tablename($this->table_order) . " WHERE id =:id LIMIT 1", array(':id' => $oid));
        $storeid = $order['storeid'];
        $store = $this->getStoreById($storeid);

        $site_url = str_replace('addons/bm_payu/', '', $_W['siteroot']);
        $site_url = str_replace('addons/bm_payms/', '', $site_url);
        $color = "#FF0033";
        if ($isall == 1) {
            $color = "#0066CC";
            $url = $site_url . 'app' . str_replace('./', '/', $this->createMobileUrl('deliveryorder', array('orderid' => $oid), true));
        } else {
            $url = $site_url . 'app' . str_replace('./', '/', $this->createMobileUrl('deliveryorderdetail', array('orderid' => $oid), true));
        }

        $keyword1 = $order['ordersn'];
        $keyword2 = $orderStatus[$order['status']];
        $keyword3 = date("Y-m-d H:i", $order['dateline']);
        if (!empty($setting['tplneworder']) && $setting['istplnotice'] == 1 && !empty($from_user)) {
            $templateid = $setting['tplneworder'];
            $first = "您有新的配送订单";
            $remark = "门店名称：{$store['title']}";
            if ($order['dining_mode'] == 1) {
                $tablename = $this->getTableName($order['tables']);
                $remark .= "\n桌台信息：{$tablename}";
            }
            $remark .= "\n订单类型：{$ordertype[$order['dining_mode']]}";
            $remark .= "\n支付方式：{$paytype[$order['paytype']]}";
            $remark .= "\n支付状态：{$paystatus[$order['ispay']]}";

            $goods = pdo_fetchall("SELECT a.*,b.title,b.unitname FROM " . tablename($this->table_order_goods) . " as a left join  " . tablename($this->table_goods) . " as b on a.goodsid=b.id WHERE a.weid = :weid and a.orderid=:orderid", array(':weid' => $weid, ':orderid' => $oid));
            if (!empty($goods)) {
                $remark .= "\n商品名称   单价 数量";
                $remark .= "\n－－－－－－－－－－－－－－－－";
                foreach ($goods as $key => $value) {
                    $remark .= "\n{$value['title']} {$value['price']}元 {$value['total']}{$value['unitname']}";
                }
            }

            if ($order['dining_mode'] == 3) {
                if ($order['dining_mode'] == 3) {
                    $tablezones = pdo_fetch("SELECT * FROM " . tablename($this->table_tablezones) . " where weid = :weid AND id=:id LIMIT 1", array(':weid' => $weid, ':id' => $order['tablezonesid']));
                }
                $remark .= "\n预定人信息：{$order['username']}－{$order['tel']}";
                $tablename = $this->getTableName($order['tables']);
                $remark .= "\n桌台信息：{$tablename}";
                $remark .= "\n预定时间：{$order['meal_time']}";
            } else {
                $remark .= "\n联系方式：{$order['username']}－{$order['tel']}";
            }
            if ($order['dining_mode'] == 2) {
                if (!empty($order['address'])) {
                    $remark .= "\n配送地址：{$order['address']}";
                }
                if (!empty($order['meal_time'])) {
                    $remark .= "\n配送时间：{$order['meal_time']}";
                }
            }
            $remark .= "\n备注：{$order['remark']}";
            $remark .= "\n应收合计：{$order['totalprice']}元";

            if ($setting['tpltype'] == 1) {
                $content = array(
                    'first' => array(
                        'value' => $first,
                        'color' => $color
                    ),
                    'keyword1' => array(
                        'value' => $keyword1,
                        'color' => '#a6a6a9'
                    ),
                    'keyword2' => array(
                        'value' => $keyword2,
                        'color' => '#a6a6a9'
                    ),
                    'keyword3' => array(
                        'value' => $keyword3,
                        'color' => '#a6a6a9'
                    ),
                    'remark' => array(
                        'value' => $remark,
                        'color' => '#a6a6a9'
                    )
                );
            } else {
                $remark = "订单状态：" . $keyword2 . "\n" . $remark;
                $content = array(
                    'first' => array(
                        'value' => $first,
                        'color' => $color
                    ),
                    'keyword1' => array(
                        'value' => $keyword1,
                        'color' => '#a6a6a9'
                    ),
                    'keyword2' => array(
                        'value' => $keyword3,
                        'color' => '#a6a6a9'
                    ),
                    'remark' => array(
                        'value' => $remark,
                        'color' => '#a6a6a9'
                    )
                );
            }

            load()->model('account');
            $access_token = WeAccount::token();
            $templateMessage = new templateMessage();
            $result = $templateMessage->send_template_message($from_user, $templateid, $content, $access_token, $url);
            $result = $result['errmsg'] == 'ok' ? '发送成功' : $result['errmsg'];
            $this->addTplLog($order, $from_user, '配送员订单通知', $result);
        } else {
            $content = "您有新的配送订单";
            $content .= "\n订单类型：{$ordertype[$order['dining_mode']]}";
            $content .= "\n订单号：{$keyword1}";
            $content .= "\n订单状态：{$keyword2}";
            $content .= "\n时间：{$keyword3}";
            $content .= "\n门店名称：{$store['title']}";
            if ($order['dining_mode'] == 1) {
                $tablename = $this->getTableName($order['tables']);
                $content .= "\n桌台信息：{$tablename}";
            }
            $content .= "\n支付方式：{$paytype[$order['paytype']]}";
            $content .= "\n支付状态：{$paystatus[$order['ispay']]}";
            $goods = pdo_fetchall("SELECT a.*,b.title,b.unitname FROM " . tablename($this->table_order_goods) . " as a left join  " . tablename($this->table_goods) . " as b on a.goodsid=b.id WHERE a.weid = :weid and a.orderid=:orderid", array(':weid' => $weid, ':orderid' => $oid));
            if (!empty($goods)) {
                $content .= "\n商品名称   单价 数量";
                $content .= "\n－－－－－－－－－－－－－－－－";
                foreach ($goods as $key => $value) {
                    $content .= "\n{$value['title']} {$value['price']} {$value['total']}{$value['unitname']}";
                }
            }
            if ($order['dining_mode'] == 3) {
                if ($order['dining_mode'] == 3) {
                    $tablezones = pdo_fetch("SELECT * FROM " . tablename($this->table_tablezones) . " where weid = :weid AND id=:id LIMIT 1", array(':weid' => $weid, ':id' => $order['tablezonesid']));
                }
                $content .= "\n预定人信息：{$order['username']}－{$order['tel']}";
                $content .= "\n桌台类型：{$tablezones['title']}";
                $content .= "\n预定时间：{$order['meal_time']}";
            } else {
                $content .= "\n联系方式：{$order['username']}－{$order['tel']}";
            }
            if ($order['dining_mode'] == 2) {
                if (!empty($order['address'])) {
                    $content .= "\n配送地址：{$order['address']}";
                }
                if (!empty($order['meal_time'])) {
                    $content .= "\n配送时间：{$order['meal_time']}";
                }
            }
            $content .= "\n备注：{$order['remark']}";
            $content .= "\n应收合计：{$order['totalprice']}元";
            if (!empty($from_user)) {
                $this->sendText($from_user, $content);
            }
        }
    }

    public function sendAdminOrderNotice($oid, $from_user, $setting)
    {
        global $_W, $_GPC;
        $weid = $this->_weid;

        $orderStatus = array(
            '-1' => '已取消',
            '0' => '待处理',
            '1' => '已确认',
            '2' => '已并台',
            '3' => '已完成'
        );
        $paytype = array(
            '0' => '现金付款',
            '1' => '余额支付',
            '2' => '微信支付',
            '3' => '现金付款',
            '4' => '支付宝'
        );
        $ordertype = array(
            '1' => '堂点',
            '2' => '外卖',
            '3' => '预定',
            '4' => '快餐',
            '5' => '收银',
            '6' => '充值'
        );
        $paystatus = array(
            '0' => '未支付',
            '1' => '已支付'
        );

        $order = pdo_fetch("select * from " . tablename($this->table_order) . " WHERE id =:id LIMIT 1", array(':id' => $oid));
        $storeid = $order['storeid'];
        $store = $this->getStoreById($storeid);

        $site_url = str_replace('addons/bm_payu/', '', $_W['siteroot']);
        $site_url = str_replace('addons/bm_payms/', '', $site_url);
        $url = $site_url . 'app' . str_replace('./', '/', $this->createMobileUrl('adminorderdetail', array('orderid' => $oid), true));

        $keyword1 = $order['ordersn'];
        $keyword2 = $orderStatus[$order['status']];
        $keyword3 = date("Y-m-d H:i", $order['dateline']);
        if (!empty($setting['tplneworder']) && $setting['istplnotice'] == 1 && !empty($from_user)) {
            $templateid = $setting['tplneworder'];
            $first = "您有新的订单";
            $remark = "门店名称：{$store['title']}";
            if ($order['dining_mode'] == 1) {
                $tablename = $this->getTableName($order['tables']);
                $remark .= "\n桌台信息：{$tablename}";
            }
            $remark .= "\n订单类型：{$ordertype[$order['dining_mode']]}";
            $remark .= "\n支付方式：{$paytype[$order['paytype']]}";
            $remark .= "\n支付状态：{$paystatus[$order['ispay']]}";

            $goods = pdo_fetchall("SELECT a.*,b.title,b.unitname FROM " . tablename($this->table_order_goods) . " as a left join  " . tablename($this->table_goods) . " as b on a.goodsid=b.id WHERE a.weid = :weid and a.orderid=:orderid", array(':weid' => $weid, ':orderid' => $oid));
            if (!empty($goods)) {
                $remark .= "\n商品名称   单价 数量";
                $remark .= "\n－－－－－－－－－－－－－－－－";
                foreach ($goods as $key => $value) {
                    $remark .= "\n{$value['title']} {$value['price']}元 {$value['total']}{$value['unitname']}";
                }
            }

            if ($order['dining_mode'] == 3) {
                if ($order['dining_mode'] == 3) {
                    $tablezones = pdo_fetch("SELECT * FROM " . tablename($this->table_tablezones) . " where weid = :weid AND id=:id LIMIT 1", array(':weid' => $weid, ':id' => $order['tablezonesid']));
                }
                $remark .= "\n预定人信息：{$order['username']}－{$order['tel']}";
                $tablename = $this->getTableName($order['tables']);
                $remark .= "\n桌台信息：{$tablename}";
                $remark .= "\n预定时间：{$order['meal_time']}";
            } else {
                $remark .= "\n联系方式：{$order['username']}－{$order['tel']}";
            }
            if ($order['dining_mode'] == 2) {
                if (!empty($order['address'])) {
                    $remark .= "\n配送地址：{$order['address']}";
                }
                if (!empty($order['meal_time'])) {
                    $remark .= "\n配送时间：{$order['meal_time']}";
                }
            }
            $remark .= "\n备注：{$order['remark']}";
            $remark .= "\n应收合计：{$order['totalprice']}元";

            if ($setting['tpltype'] == 1) {
                $content = array(
                    'first' => array(
                        'value' => $first,
                        'color' => '#a6a6a9'
                    ),
                    'keyword1' => array(
                        'value' => $keyword1,
                        'color' => '#a6a6a9'
                    ),
                    'keyword2' => array(
                        'value' => $keyword2,
                        'color' => '#a6a6a9'
                    ),
                    'keyword3' => array(
                        'value' => $keyword3,
                        'color' => '#a6a6a9'
                    ),
                    'remark' => array(
                        'value' => $remark,
                        'color' => '#a6a6a9'
                    )
                );
            } else {
                $remark = "订单状态：" . $keyword2 . "\n" . $remark;
                $content = array(
                    'first' => array(
                        'value' => $first,
                        'color' => '#a6a6a9'
                    ),
                    'keyword1' => array(
                        'value' => $keyword1,
                        'color' => '#a6a6a9'
                    ),
                    'keyword2' => array(
                        'value' => $keyword3,
                        'color' => '#a6a6a9'
                    ),
                    'remark' => array(
                        'value' => $remark,
                        'color' => '#a6a6a9'
                    )
                );
            }

            load()->model('account');
            $access_token = WeAccount::token();
            $templateMessage = new templateMessage();
            $result = $templateMessage->send_template_message($from_user, $templateid, $content, $access_token, $url);
            $result = $result['errmsg'] == 'ok' ? '发送成功' : $result['errmsg'];
            $this->addTplLog($order, $from_user, '管理员订单通知', $result);
        } else {
            $content = "您有新的订单";
            $content .= "\n订单类型：{$ordertype[$order['dining_mode']]}";
            $content .= "\n订单号：{$keyword1}";
            $content .= "\n订单状态：{$keyword2}";
            $content .= "\n时间：{$keyword3}";
            $content .= "\n门店名称：{$store['title']}";
            if ($order['dining_mode'] == 1) {
                $tablename = $this->getTableName($order['tables']);
                $content .= "\n桌台信息：{$tablename}";
            }
            $content .= "\n支付方式：{$paytype[$order['paytype']]}";
            $content .= "\n支付状态：{$paystatus[$order['ispay']]}";
            $goods = pdo_fetchall("SELECT a.*,b.title,b.unitname FROM " . tablename($this->table_order_goods) . " as a left join  " . tablename($this->table_goods) . " as b on a.goodsid=b.id WHERE a.weid = :weid and a.orderid=:orderid", array(':weid' => $weid, ':orderid' => $oid));
            if (!empty($goods)) {
                $content .= "\n商品名称   单价 数量";
                $content .= "\n－－－－－－－－－－－－－－－－";
                foreach ($goods as $key => $value) {
                    $content .= "\n{$value['title']} {$value['price']} {$value['total']}{$value['unitname']}";
                }
            }
            if ($order['dining_mode'] == 3) {
                if ($order['dining_mode'] == 3) {
                    $tablezones = pdo_fetch("SELECT * FROM " . tablename($this->table_tablezones) . " where weid = :weid AND id=:id LIMIT 1", array(':weid' => $weid, ':id' => $order['tablezonesid']));
                }
                $content .= "\n预定人信息：{$order['username']}－{$order['tel']}";
                $content .= "\n桌台类型：{$tablezones['title']}";
                $content .= "\n预定时间：{$order['meal_time']}";
            } else {
                $content .= "\n联系方式：{$order['username']}－{$order['tel']}";
            }
            if ($order['dining_mode'] == 2) {
                if (!empty($order['address'])) {
                    $content .= "\n配送地址：{$order['address']}";
                }
                if (!empty($order['meal_time'])) {
                    $content .= "\n配送时间：{$order['meal_time']}";
                }
            }
            $content .= "\n备注：{$order['remark']}";
            $content .= "\n应收合计：{$order['totalprice']}元";
            if (!empty($from_user)) {
                $this->sendText($from_user, $content);
            }
        }
    }

    public function getNewSncode($weid, $sncode)
    {
        global $_W, $_GPC;
        $sn = pdo_fetch("SELECT sncode FROM " . tablename($this->table_sncode) . " WHERE weid = :weid and sncode = :sn ORDER BY `id` DESC limit 1", array(':weid' => $weid, ':sn' => $sncode));
        if (!empty($sn)) {
            $sncode = 'A00' . random(11, 1);
            $this->getNewSncode($weid, $sncode);
        }
        return $sncode;
    }

    public function doMobileOrderlist()
    {
        $url = $this->createMobileUrl('order', array(), true);
        die('<script>location.href = "' . $url . '";</script>');
    }

    //取得购物车中的商品
    public function getDishCountInCart($storeid)
    {
        global $_GPC, $_W;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;

        $dishlist = pdo_fetchall("SELECT * FROM " . tablename($this->table_cart) . " WHERE  storeid=:storeid AND from_user=:from_user AND weid=:weid", array(':from_user' => $from_user, ':weid' => $weid, ':storeid' => $storeid));
        foreach ($dishlist as $key => $value) {
            $arr[$value['goodsid']] = $value['total'];
        }
        return $arr;
    }

    public function doMobilePay()
    {
        global $_W, $_GPC;
        //查当前订单信息 
        if (!$this->inMobile) {
            message('支付功能只能在手机上使用');
        }
        $IMS_VERSION = intval(IMS_VERSION);

        $orderid = intval($_GPC['orderid']);
        $order = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE id = :id", array(':id' => $orderid));
        if ($order['ispay'] == '1' || $order['status'] == '-1' || $order['status'] == '3') {
            message('抱歉，您的订单已经付款或是被关闭，请重新进入付款！', referer(), 'error');
        }

        $tip = '餐后付款';
        if ($order['dining_mode'] == 2) {
            $tip = '货到付款';
        }

        $storeid = intval($order['storeid']);
        $setting = $this->getSetting();
        $store = $this->getStoreById($storeid);

        $is_wechat = 0;
        if ($setting['wechat'] == 1) {
            if ($store['wechat'] == 1) {
                $is_wechat = 1;
            }
            if ($order['dining_mode'] == 3) {
                $is_wechat = 0;
                if ($store['reservation_wechat'] == 1) {
                    $is_wechat = 1;
                }
            }
            if ($order['dining_mode'] == 1) { //店内
                if ($store['is_meal_pay_confirm'] == 1) {//确认后才允许支付
                    $is_wechat = 0;//默认关闭
                    if ($order['status'] > 0) {
                        $is_wechat = 1;
                    }
                }
            }
            if ($order['dining_mode'] == 6) {
                $is_wechat = 1;
            }
        }
        $is_alipay = 0;
        if ($setting['alipay'] == 1) {
            if ($store['alipay'] == 1) {
                $is_alipay = 1;
            }
            if ($order['dining_mode'] == 3) {
                $is_alipay = 0;
                if ($store['reservation_alipay'] == 1) {
                    $is_alipay = 1;
                }
            }
            if ($order['dining_mode'] == 1) { //店内
                if ($store['is_meal_pay_confirm'] == 1) {//确认后才允许支付
                    $is_alipay = 0;//默认关闭
                    if ($order['status'] > 0) {
                        $is_alipay = 1;
                    }
                }
            }
        }
        $is_credit = 0;
        if ($setting['credit'] == 1) {
            if ($store['credit'] == 1) {
                $is_credit = 1;
            }
            if ($order['dining_mode'] == 3) {
                $is_credit = 0;
                if ($store['reservation_credit'] == 1) {
                    $is_credit = 1;
                }
            }
            if ($order['dining_mode'] == 1) { //店内
                if ($store['is_meal_pay_confirm'] == 1) {//确认后才允许支付
                    $is_credit = 0;//默认关闭
                    if ($order['status'] > 0) {
                        $is_credit = 1;
                    }
                }
            }
            if ($order['dining_mode'] == 6) { //收银
                $is_credit = 0;
            }
        }
        $is_delivery = 0;
        if ($setting['delivery'] == 1) {
            if ($store['delivery'] == 1) {
                $is_delivery = 1;
            }
            if ($order['dining_mode'] == 3) {
                $is_delivery = 0;
                if ($store['reservation_delivery'] == 1) {
                    $is_delivery = 1;
                }
            }
            if ($order['dining_mode'] == 5) { //收银
                $is_delivery = 0;
            }
            if ($order['dining_mode'] == 6) { //收银
                $is_delivery = 0;
            }
        }
        $params['tid'] = $orderid;
        $params['user'] = $_W['fans']['from_user'];
        $params['fee'] = $order['totalprice'];
        $params['ordersn'] = $order['ordersn'];
        $params['virtual'] = true;
        $params['module'] = 'weisrc_dish';
        $params['title'] = '餐饮' . $order['ordersn'];

        //美丽心情
        if ($store['is_business'] == 1) {
            $business_id = intval($store['business_id']);
            if ($business_id > 0) {
                $business_url = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&tid=' . $params['tid'] . '&title=' . $params['title'] . '&fee=' . $params['fee'] . '&ordersn=' . $params['ordersn'] . '&user=' . $params['user'] . '&rid=' . $business_id . '&ms=weisrc_dish&do=payex&m=bm_payu';
            }
        }

        //美丽心情
        if ($store['is_bank_pay'] == 1) {
            $bank_pay_id = intval($store['bank_pay_id']);
            if ($bank_pay_id > 0) {
                $bank_pay_url = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&tid=' . $params['tid'] . '&title=' . $params['title'] . '&fee=' . $params['fee'] . '&ordersn=' . $params['ordersn'] . '&user=' . $params['user'] . '&rid=' . $bank_pay_id . '&ms=weisrc_dish&do=payex&m=bm_payms';
            }
        }

        //快捷云支付
        if ($store['is_vtiny_bankpay'] == 1) {
            $vtiny_bankpay_url = $store['vtiny_bankpay_url'] . '&params=' . base64_encode(json_encode($params));
        }
        //test6688  一码付服务商版
        if ($store['is_ld_wxserver'] == 1) {
            $ld_wxserver_url = $store['ld_wxserver_url'] . '&params=' . base64_encode(json_encode($params));
        }

        if ($store['is_jueqi_ymf'] == 1) {
            //需要获取的参数
            $host = $store['jueqi_host'];//本机服务器一码付的域名前缀（可能为独立域名，可能为微赞域名路径，具体看接口文档）
            $uid = 'weisrc_dish';//模块标识
            //获取支付秘钥
            $url = $host . '/index.php?s=/Home/line/getPrikey';//接口基础url
            $url = $url . '/uid/' . $uid;//带参数
            $prikeyResult = json_decode(file_get_contents($url), true);
            $prikey = '';//秘钥
            if ($prikeyResult['result'] == '1') {
                $prikey = $prikeyResult['data'];
                //需要获取的参数
                $selfOrdernum = $params['ordersn'];
                $openId = $_W['fans']['from_user'];

                $customerId = trim($store['jueqi_customerId']);
                $money = $order['totalprice'];
                $back_url = str_replace('pay', 'jueqiBack', 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
                $notifyUrl = base64_encode(urlencode(($back_url)));//异步URL，必须带参数
                $successUrl = base64_encode(urlencode(($back_url)));//成功跳转URL，必须带参数
                $uid = 'weisrc_dish';//模块标识
                $prikey = $prikey;//秘钥
                $goodsName = $params['title'];//商品名称
                $remark = '';//备注
                //跳转到在线支付
                $url = $host . '/index.php?s=/Home/line/m_pay';//集成接口url
                $url = $url . '/selfOrdernum/' . $selfOrdernum . '/openId/' . $openId . '/customerId/' . $customerId . '/money/' . $money . '/notifyUrl/' . $notifyUrl . '/successUrl/' . $successUrl . '/uid/' . $uid . '/prikey/' . $prikey . '/goodsName/' . $goodsName . '/remark/' . $remark;
                $jueqi_ymf_url = $url;
            }
        }

        include $this->template('pay');

    }

    public function doMobileJueqiBack()
    {
        global $_W, $_GPC;
        $data['weid'] = $this->_weid;
        $data['uniacid'] = $_W['uniacid'];
        $data['acid'] = $_W['acid'];
        $data['result'] = 'success';
        $data['type'] = 'wechat';
        $data['from'] = 'notify';
        $data['tid'] = $_GPC['selfOrdernum'];
        $data['uniontid'] = $_GPC['orderno'];
        $data['transaction_id'] = $_GPC['orderno'];
        //$data['trade_type'] =$this->_weid;
        //$data['follow'] =$this->_weid;
        $data['user'] = $_GPC['uuid'];
        $data['fee'] = $_GPC['money'];
        $data['tag']['transaction_id'] = $_GPC['orderno'];
        //$data['is_usecard'] =$this->_weid;
        //$data['card_type'] =$this->_weid;
        //$data['card_fee'] =$this->_weid;
        //$data['card_id'] =$this->_weid;
        //$data['cash_fee'] =$this->_weid;
        $data['total_fee'] = $_GPC['money'];
        $data['paysys'] = 'bm_payms';
        $data['paytime'] = $_GPC['paytime'];
        $this->payResult($data);
    }

    private function sendText($openid, $content)
    {
        $send['touser'] = trim($openid);
        $send['msgtype'] = 'text';
        $send['text'] = array('content' => urlencode($content));
        $acc = WeAccount::create();
        $data = $acc->sendCustomNotice($send);
        return $data;
    }
    public function payResult($params)
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $orderid = $params['tid'];
        $fee = intval($params['fee']);
        $paytype = array('credit' => '1', 'wechat' => '2', 'alipay' => '4', 'baifubao' => '5', 'delivery' => '3');

        // 卡券代金券备注
        if (!empty($params['is_usecard'])) {
            $cardType = array('1' => '微信卡券', '2' => '系统代金券');
            $result_price = ($params['fee'] - $params['card_fee']);
            $data['paydetail'] = '使用' . $cardType[$params['card_type']] . '支付了' . $result_price;
            $data['paydetail'] .= '元，实际支付了' . $params['card_fee'] . '元。';
            $data['totalprice'] = $params['card_fee'];
        }

        $data['paytype'] = $paytype[$params['type']];

        if ($params['type'] == 'alipay') {
            if (!empty($params['transaction_id'])) {
                $data['transid'] = $params['transaction_id'];
            }
        }
        if ($params['type'] == 'wechat') {
            if (!empty($params['tag']['transaction_id'])) {
                $data['transid'] = $params['tag']['transaction_id'];
            }
        }

        if ($params['paysys'] == 'bm_payms') {
            $order = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE ordersn = :ordersn", array(':ordersn' => $params['tid']));
            $orderid = $order['id'];
        } else {
            $order = $this->getOrderById($orderid);
        }
        if (empty($order)) {
            message('订单不存在!!!');
        }

        if ($order['ispay'] == 0) {
            $storeid = $order['storeid'];
            $store = $this->getStoreById($storeid);

            //本订单产品
            $goods = pdo_fetchall("SELECT a.*,b.title,b.unitname FROM " . tablename($this->table_order_goods) . " as a left join  " . tablename($this->table_goods) . " as b on a.goodsid=b.id WHERE a.orderid=:orderid ", array(':orderid' => $orderid));
            $goods_str = '';
            $goods_tplstr = '';
            $flag = false;
            foreach ($goods as $key => $value) {
                if (!$flag) {
                    $goods_str .= "{$value['title']} 价格：{$value['price']} 数量：{$value['total']}{$value['unitname']}";
                    $goods_tplstr .= "{$value['title']} {$value['total']}{$value['unitname']}";
                    $flag = true;
                } else {
                    $goods_str .= "<br/>{$value['title']} 价格：{$value['price']} 数量：{$value['total']}{$value['unitname']}";
                    $goods_tplstr .= ",{$value['title']} {$value['total']}{$value['unitname']}";
                }
            }

            if ($order['dining_mode'] == 1) { //店内
                if ($data['paytype'] == 3) { //现金
                    pdo_update($this->table_tables, array('status' => 2), array('id' => $order['tables']));
                } else {
                    pdo_update($this->table_tables, array('status' => 3), array('id' => $order['tables']));
                }
            }
            $setting = $this->getSettingByWeid($order['weid']);

            //后台通知，修改状态
            if ($params['result'] == 'success' && ($params['from'] == 'notify' || $params['from'] =='return')){
                if ($data['paytype'] == 1 || $data['paytype'] == 2 || $data['paytype'] == 4) { //在线，余额支付
                    $data['ispay'] = 1;
                    $data['paytime'] = TIMESTAMP;
                    if ($store['is_auto_confirm'] == 1 && $order['status'] == 0) {
                        $data['status'] = 1;
                    }
                    pdo_update($this->table_order, $data, array('id' => $orderid));

                    $user = $this->getFansByOpenid($order['from_user']);
                    $touser = empty($user['nickname']) ? $user['from_user'] : $user['nickname'];
                    $this->addOrderLog($orderid, $touser, 1, 1, 2);
                }

                file_put_contents(IA_ROOT . "/addons/weisrc_dish/params.log", var_export($params, true) . PHP_EOL, FILE_APPEND);
                if ($params['paysys'] != 'payu' && $params['paysys'] != 'bm_payms') {
                    if ($params['type'] == 'alipay') {
                        if (empty($params['transaction_id'])) {
                            return false;
                        }
                    }
                    if ($params['type'] == 'wechat') {
                        if (empty($params['tag']['transaction_id'])) {
                            return false;
                        }
                    }
                }

                $this->sendfengniao($order, $store, $setting);

                if ($order['dining_mode'] == 6) { //充值
                    $status = $this->setFansCoin($order['from_user'], $order['totalprice'], "订单编号{$orderid}充值");
                    $this->addRechargePrice($orderid);
                    pdo_update($this->table_order, array('status' => 3), array('id' => $orderid));
                }
//                if ($order['istpl'] == 0) {
                if ($params['type'] == 'credit') {
                    pdo_update($this->table_order, array('istpl' => 1), array('id' => $orderid));
                }

                if ($order['dining_mode'] != 6) {
                    $this->feiyinSendFreeMessage($orderid); //飞印
                    $this->_365SendFreeMessage($orderid); //365打印机
                    $this->feieSendFreeMessage($orderid); //飞鹅
                    $this->_yilianyunSendFreeMessage($orderid); //易联云
					$this->_jinyunSendFreeMessage($orderid); //进云物联
                    $order = $this->getOrderById($orderid);
                    //用户
                    $this->sendOrderNotice($order, $store, $setting);
                    //管理
                    if (!empty($setting)) {
                        //平台提醒
                        if ($setting['is_notice'] == 1) {
                            if (!empty($setting['tpluser'])) {
                                $tousers = explode(',', $setting['tpluser']);
                                foreach ($tousers as $key => $value) {
                                    $this->sendAdminOrderNotice($orderid, $value, $setting);
                                }
                            }
                            if (!empty($setting['email']) && !empty($setting['email_user']) && !empty($setting['email_pwd'])) {
                                $this->sendAdminOrderEmail($setting['email'], $order, $store, $goods_str);
                            }
                            if (!empty($setting['sms_mobile']) && !empty($setting['sms_username']) && !empty($setting['sms_pwd'])) {
                                $this->sendAdminOrderSms($setting['sms_mobile'], $order);
                            }
                        }

                        //门店提醒
                        $accounts = pdo_fetchall("SELECT * FROM " . tablename($this->table_account) . " WHERE weid = :weid AND storeid=:storeid AND status=2 AND is_notice_order=1 ORDER BY id
DESC ", array(':weid' => $this->_weid, ':storeid' => $storeid));
                        foreach ($accounts as $key => $value) {
                            if (!empty($value['from_user'])) {
                                $this->sendAdminOrderNotice($orderid, $value['from_user'], $setting);
                            }
                            if (!empty($value['email']) && !empty($setting['email_user']) && !empty($setting['email_pwd'])) {
                                $this->sendAdminOrderEmail($value['email'], $order, $store, $goods_str);
                            }
                            if (!empty($value['mobile']) && !empty($setting['sms_username']) && !empty($setting['sms_pwd'])) {
                                $smsStatus = $this->sendAdminOrderSms($value['mobile'], $order);
                            }
                        }
                    }

                    if ($order['dining_mode'] == 2 && $setting['delivery_mode'] != 1) { //外卖订单,通知配送
                        $strwhere = '';
                        if ($setting['delivery_mode'] == 2) { //所有配送员
                            $deliverys = pdo_fetchall("SELECT * FROM " . tablename($this->table_account) . " WHERE weid = :weid AND role=4 AND status=2 ORDER BY id DESC ", array(':weid' => $this->_weid));
                            foreach ($deliverys as $key => $value) {
                                $this->sendDeliveryOrderNotice($orderid, $value['from_user'], $setting);
                            }
                        } else if ($setting['delivery_mode'] == 3) { //区域配送员
                            $area = $this->getNearDeliveryArea($order['lat'], $order['lng']);
                            $areaid = intval($area['id']);
                            if ($areaid != 0) {
                                $strwhere = " AND areaid={$areaid} ";
                                $deliverys = pdo_fetchall("SELECT * FROM " . tablename($this->table_account) . " WHERE weid = :weid AND role=4 AND status=2 {$strwhere} ORDER BY id DESC ", array(':weid' => $this->_weid));
                                foreach ($deliverys as $key => $value) {
                                    $this->sendDeliveryOrderNotice($orderid, $value['from_user'], $setting);
                                }
                            }
                        }
                    }
                }
                pdo_update($this->table_order, array('istpl' => 1), array('id' => $orderid));
//                }
            }

            //前台通知
            if ($params['from'] == 'return') {
                if ($order['istpl'] == 0 && $params['type'] == 'delivery') {
                    $data['istpl'] = 1;//
                    if ($data['paytype'] == 3) { //现金
                        if ($store['is_order_autoconfirm'] == 1 && $order['status'] == 0) {
                            $data['status'] = 1;
                        }
                    }

                    pdo_update($this->table_order, $data, array('id' => $orderid));
                    $this->feiyinSendFreeMessage($orderid);
                    $this->_365SendFreeMessage($orderid);
                    $this->feieSendFreeMessage($orderid);
                    $this->_yilianyunSendFreeMessage($orderid);
					$this->_jinyunSendFreeMessage($orderid); //进云物联
                    $this->sendfengniao($order, $store, $setting);

                    $order = $this->getOrderById($orderid);
                    //用户
                    $this->sendOrderNotice($order, $store, $setting);
                    //管理
                    if (!empty($setting)) {
                        //平台提醒
                        if ($setting['is_notice'] == 1) {
                            if (!empty($setting['tpluser'])) {
                                $tousers = explode(',', $setting['tpluser']);
                                foreach ($tousers as $key => $value) {
                                    $this->sendAdminOrderNotice($orderid, $value, $setting);
                                }
                            }
                            if (!empty($setting['email'])) {
                                $this->sendAdminOrderEmail($setting['email'], $order, $store, $goods_str);
                            }
                            if (!empty($setting['sms_mobile'])) {
                                $smsStatus = $this->sendAdminOrderSms($setting['sms_mobile'], $order);
                            }
                        }
                        //门店提醒
                        $accounts = pdo_fetchall("SELECT * FROM " . tablename($this->table_account) . " WHERE weid = :weid AND storeid=:storeid AND status=2 AND is_notice_order=1 ORDER BY id DESC ", array(':weid' => $this->_weid, ':storeid' => $storeid));
                        foreach ($accounts as $key => $value) {
                            if (!empty($value['from_user'])) {
                                $this->sendAdminOrderNotice($orderid, $value['from_user'], $setting);
                            }
                            if (!empty($value['email'])) {
                                $this->sendAdminOrderEmail($value['email'], $order, $store, $goods_str);
                            }
                            if (!empty($value['mobile'])) {
                                $smsStatus = $this->sendAdminOrderSms($value['mobile'], $order);
                            }
                        }
                    }

                    if ($order['dining_mode'] == 2 && $setting['delivery_mode'] != 1) { //外卖
                        $strwhere = '';
                        if ($setting['delivery_mode'] == 2) { //所有配送员
                            $deliverys = pdo_fetchall("SELECT * FROM " . tablename($this->table_account) . " WHERE weid = :weid AND role=4 AND status=2 ORDER BY id DESC ", array(':weid' => $this->_weid));
                            foreach ($deliverys as $key => $value) {
                                $this->sendDeliveryOrderNotice($orderid, $value['from_user'], $setting);
                            }
                        } else if ($setting['delivery_mode'] == 3) { //区域配送员
                            $fans = $this->getFansByOpenid($order['from_user']);
                            $area = $this->getNearDeliveryArea($order['lat'], $order['lng']);
                            $areaid = intval($area['id']);
                            if ($areaid != 0) {
                                $strwhere = " WHERE weid =:weid AND areaid=:areaid AND role=4 AND status=2 ";
                                $deliverys = pdo_fetchall("SELECT * FROM " . tablename($this->table_account) . " {$strwhere} ORDER BY id DESC ", array(':weid' => $this->_weid, ':areaid' => $areaid));
                                foreach ($deliverys as $key => $value) {
                                    $this->sendDeliveryOrderNotice($orderid, $value['from_user'], $setting);
                                }
                            }
                        }
                    }
                }
            }
        }

        $tip_msg = '支付成功';
        if ($params['type'] == 'delivery') {
            $tip_msg = '下单成功';
        }

        $setting = uni_setting($_W['uniacid'], array('creditbehaviors'));
        $credit = $setting['creditbehaviors']['currency'];

        $url = '../../app/' . $this->createMobileUrl('orderdetail', array('orderid' => $orderid));
        if ($order['dining_mode'] == 6) {
            $tip_msg = '充值成功';
            $url = '../../app/' . $this->createMobileUrl('usercenter', array());
        }

        if ($params['type'] == $credit) {

            if ($params['type'] == 'baifubao') {
                message($tip_msg, '../../app/' . $this->createMobileUrl('orderdetail', array('orderid' => $orderid), true), 'success');
            } else {
                message($tip_msg, $this->createMobileUrl('orderdetail', array('orderid' => $orderid), true), 'success');
            }
        } else {

            if ($params['paysys'] == 'payu' || $params['paysys'] == 'bm_payms') {
                Header("Location: {$url}");
            } else {
                message($tip_msg, $url, 'success');
            }
        }
    }

    function register_jssdk_test($debug = false)
    {

        global $_W;

        if (defined('HEADER')) {
            echo '';
            return;
        }

        $sysinfo = array(
            'uniacid' => $_W['uniacid'],
            'siteroot' => $_W['siteroot'],
            'siteurl' => $_W['siteurl'],
            'attachurl' => $_W['attachurl'],
            'cookie' => array('pre' => $_W['config']['cookie']['pre'])
        );
        if (!empty($_W['acid'])) {
            $sysinfo['acid'] = $_W['acid'];
        }
        if (!empty($_W['openid'])) {
            $sysinfo['openid'] = $_W['openid'];
        }
        if (defined('MODULE_URL')) {
            $sysinfo['MODULE_URL'] = MODULE_URL;
        }
        $sysinfo = json_encode($sysinfo);
        $jssdkconfig = json_encode($_W['account']['jssdkconfig']);
        $debug = $debug ? 'true' : 'false';

        $script = <<<EOF

<script src="http://res.wx.qq.com/open/js/jweixin-1.1.0.js"></script>
<script type="text/javascript">
	window.sysinfo = window.sysinfo || $sysinfo || {};

	// jssdk config 对象
	jssdkconfig = $jssdkconfig || {};

	// 是否启用调试
	jssdkconfig.debug = $debug;

	jssdkconfig.jsApiList = [
		'checkJsApi',
		'onMenuShareTimeline',
		'onMenuShareAppMessage',
		'onMenuShareQQ',
		'onMenuShareWeibo',
		'hideMenuItems',
		'showMenuItems',
		'hideAllNonBaseMenuItem',
		'showAllNonBaseMenuItem',
		'translateVoice',
		'startRecord',
		'stopRecord',
		'onRecordEnd',
		'playVoice',
		'pauseVoice',
		'stopVoice',
		'uploadVoice',
		'downloadVoice',
		'chooseImage',
		'previewImage',
		'uploadImage',
		'downloadImage',
		'getNetworkType',
		'openLocation',
		'openAddress',
		'getLocation',
		'hideOptionMenu',
		'showOptionMenu',
		'closeWindow',
		'scanQRCode',
		'chooseWXPay',
		'openProductSpecificView',
		'addCard',
		'chooseCard',
		'openCard'
	];

	wx.config(jssdkconfig);

</script>
EOF;
        echo $script;
    }

    public function getNearDeliveryArea($lat, $lng)
    {
        $weid = $this->_weid;

        $strwhere = " WHERE weid=:weid ";
        $strorder = " ORDER BY dist ";

        $area = pdo_fetch("SELECT *,(lat-:lat) * (lat-:lat) + (lng-:lng) * (lng-:lng) as dist FROM " . tablename($this->table_deliveryarea) . " {$strwhere} {$strorder} LIMIT 1", array(':weid' => $weid, ':lat' => $lat, ':lng' => $lng));

        return $area;
    }

    public function sendOrderEmail($order, $store, $goods_str)
    {
        $firstArr = array(
            '-1' => '已经取消',
            '0' => '已经提交',
            '1' => '已经确认',
            '2' => '已并台',
            '3' => '已经完成'
        );

        $orderStatus = array(
            '-1' => '已取消',
            '0' => '待处理',
            '1' => '已确认',
            '2' => '已并台',
            '3' => '已完成'
        );
        $paytype = array(
            '0' => '现金付款',
            '1' => '余额支付',
            '2' => '微信支付',
            '3' => '现金付款',
            '4' => '支付宝'
        );

        //发送邮件提醒
        $emailSetting = pdo_fetch("SELECT * FROM " . tablename($this->table_setting) . " WHERE weid=:weid AND storeid=:storeid LIMIT 1", array(':weid' => $order['weid'], ':storeid' => $order['storeid']));

        $keyword1 = $order['ordersn'];
        $keyword2 = $orderStatus[$order['status']];
        $keyword3 = date("Y-m-d H:i", $order['dateline']);

        $email_tpl = "
        您的订单{$order['ordersn']}{$firstArr[$order['status']]}<br/>
        订单号：{$keyword1}<br/>
        订单状态：{$keyword2}<br/>
        时间：{$keyword3}<br/>
        门店名称：{$store['title']}<br/>
        支付方式：{$paytype[$order['paytype']]}<br/>
        ";
        if ($order['dining_mode'] == 3) {
            $email_tpl .= "预定人信息：{$order['username']}－{$order['tel']}<br/>";
            $email_tpl .= "预定时间：{$order['meal_time']}<br/>";
        } else {
            $email_tpl .= "联系方式：{$order['username']}－{$order['tel']}<br/>";
        }
        if ($order['dining_mode'] == 1) {
            $tablename = $this->getTableName($order['tables']);
            $email_tpl .= "桌台信息：{$tablename}<br/>";
        }
        if ($order['dining_mode'] == 2) {
            if (!empty($order['address'])) {
                $email_tpl .= "配送地址：{$order['address']}<br/>";
            }
            if (!empty($order['meal_time'])) {
                $email_tpl .= "配送时间：{$order['meal_time']}<br/>";
            }
        }
        $email_tpl .= "菜单：{$goods_str}<br/>";
        $email_tpl .= "备注：{$order['remark']}<br/>";
        $email_tpl .= "应收合计：{$order['totalprice']}";

        if (!empty($emailSetting) && !empty($emailSetting['email'])) {
            if ($emailSetting['email_host'] == 'smtp.qq.com' || $emailSetting['email_host'] == 'smtp.gmail.com') {
                $secure = 'ssl';
                $port = '465';
            } else {
                $secure = 'tls';
                $port = '25';
            }

            $mail_config = array();
            $mail_config['host'] = $emailSetting['email_host'];
            $mail_config['secure'] = $secure;
            $mail_config['port'] = $port;
            $mail_config['username'] = $emailSetting['email_user'];
            $mail_config['sendmail'] = $emailSetting['email_send'];
            $mail_config['password'] = $emailSetting['email_pwd'];
            $mail_config['mailaddress'] = $emailSetting['email'];
            $mail_config['subject'] = '订单提醒';
            $mail_config['body'] = $email_tpl;
            $result = $this->sendmail($mail_config);
        }
    }

    public function showTip($msg, $code = 0)
    {
        $result['code'] = $code;
        $result['msg'] = $msg;
        message($result, '', 'ajax');
    }

    public function showMsg($msg, $status = 0)
    {
        $result = array('msg' => $msg, 'status' => $status);
        echo json_encode($result);
        exit();
    }

    public function doMobileAjaxdelete()
    {
        global $_GPC;
        $delurl = $_GPC['pic'];
        load()->func('file');
        if (file_delete($delurl)) {
            echo 1;
        } else {
            echo 0;
        }
    }

    public function img_url($img = '')
    {
        global $_W;
        if (empty($img)) {
            return "";
        }
        if (substr($img, 0, 6) == 'avatar') {
            return $_W['siteroot'] . "resource/image/avatar/" . $img;
        }
        if (substr($img, 0, 8) == './themes') {
            return $_W['siteroot'] . $img;
        }
        if (substr($img, 0, 1) == '.') {
            return $_W['siteroot'] . substr($img, 2);
        }
        if (substr($img, 0, 5) == 'http:') {
            return $img;
        }
        return $_W['attachurl'] . $img;
    }

    //发送短信
    public function _sendSms($sendinfo)
    {
        global $_W;
        load()->func('communication');
        $weid = $_W['uniacid'];
        $username = $sendinfo['username'];
        $pwd = $sendinfo['pwd'];
        $mobile = $sendinfo['mobile'];
        $content = $sendinfo['content'];
//        $target = "http://www.dxton.com/webservice/sms.asmx/Submit";
        $target = "http://sms.106jiekou.com/utf8/sms.aspx";
        //替换成自己的测试账号,参数顺序和wenservice对应
        $post_data = "account=" . $username . "&password=" . $pwd . "&mobile=" . $mobile . "&content=" . rawurlencode($content);
        //请自己解析$gets字符串并实现自己的逻辑
        //<result>100</result>表示成功,其它的参考文档
//        $this->showMsg($username . '|' . $pwd);
        $result = $this->smsPost($post_data, $target);
//        $xml = simplexml_load_string($result['content'], 'SimpleXMLElement', LIBXML_NOCDATA);
//        $result = (string) $xml->result;
//        $message = (string) $xml->message;
        return $result;
    }

    public function smsPost($curlPost, $url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
        $return_str = curl_exec($curl);
        curl_close($curl);
        return $return_str;
    }

    public function sendmail($config)
    {
        require_once 'plugin/email/class.phpmailer.php';
        $mail = new PHPMailer();
        $mail->CharSet = "utf-8";
        $body = $config['body'];
        $mail->IsSMTP();
        $mail->SMTPAuth = true; // enable SMTP authentication
        $mail->SMTPSecure = $config['secure']; // sets the prefix to the servier
        $mail->Host = $config['host']; // sets the SMTP server
        $mail->Port = $config['port'];
        $mail->Username = $config['sendmail']; // 发件邮箱用户名
        $mail->Password = $config['password']; // 发件邮箱密码
        $mail->From = $config['sendmail']; //发件邮箱
        $mail->FromName = $config['username']; //发件人名称
        $mail->Subject = $config['subject']; //主题
        $mail->WordWrap = 50; // set word wrap
        $mail->MsgHTML($body);
        $mail->AddAddress($config['mailaddress'], ''); //收件人地址、名称
        $mail->IsHTML(true); // send as HTML
        if (!$mail->Send()) {
            $status = 0;
        } else {
            $status = 1;
        }
        return $status;
    }

    public function doMobileValidatecheckcode()
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $_GPC['from_user'];
        $this->_fromuser = $from_user;
        $mobile = trim($_GPC['mobile']);
        $checkcode = trim($_GPC['checkcode']);

        if (empty($mobile)) {
            $this->showMsg('请输入手机号码!');
        }

        if (empty($checkcode)) {
            $this->showMsg('请输入验证码!');
        }

        $item = pdo_fetch("SELECT * FROM " . tablename('weisrc_dish_sms_checkcode') . " WHERE weid = :weid  AND from_user=:from_user AND checkcode=:checkcode ORDER BY `id` DESC limit 1", array(':weid' => $weid, ':from_user' => $from_user, ':checkcode' => $checkcode));

        if (empty($item)) {
            $this->showMsg('验证码输入错误!');
        } else {
            pdo_update('weisrc_dish_sms_checkcode', array('status' => 1), array('id' => $item['id']));
            pdo_update($this->table_fans, array('mobile' => $item['mobile']), array('from_user' => $item['from_user'], 'weid' => $weid));
        }

        $this->showMsg('验证成功!', 1);
    }

    //取得短信验证码
    public function doMobileGetCheckCode()
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = trim($_GPC['from_user']);
        $this->_fromuser = $from_user;
        $mobile = trim($_GPC['mobile']);
        $storeid = intval($_GPC['storeid']);


        if (!preg_match("/^13[0-9]{1}[0-9]{8}$|15[0-9]{1}[0-9]{8}$|17[0-9]{1}[0-9]{8}$|18[0-9]{1}[0-9]{8}$|147[0-9]{8
        }$/", $mobile)
        ) {
            $this->showMsg('手机号码格式不对!');
        }

        $passcheckcode = pdo_fetch("SELECT * FROM " . tablename('weisrc_dish_sms_checkcode') . " WHERE weid = :weid  AND from_user=:from_user AND status=1 ORDER BY `id` DESC limit 1", array(':weid' => $weid, ':from_user' => $from_user));
        if (!empty($passcheckcode)) {
            $this->showMsg('发送成功!', 1);
        }

        $smsSetting = pdo_fetch("SELECT * FROM " . tablename($this->table_setting) . " WHERE weid=:weid LIMIT 1", array(':weid' => $weid));
        if (empty($smsSetting) || empty($smsSetting['sms_username']) || empty($smsSetting['sms_pwd'])) {
            $this->showMsg('商家未开启验证码!');
        }

        $checkCodeCount = pdo_fetchcolumn("SELECT count(1) FROM " . tablename('weisrc_dish_sms_checkcode') . " WHERE weid = :weid  AND from_user=:from_user ", array(':weid' => $weid, ':from_user' => $from_user));
        if ($checkCodeCount >= 3) {
            $this->showMsg('您请求的验证码已超过最大限制..' . $checkCodeCount);
        }

        //判断数据是否已经存在
        $data = pdo_fetch("SELECT * FROM " . tablename('weisrc_dish_sms_checkcode') . " WHERE weid = :weid  AND from_user=:from_user ORDER BY `id` DESC limit 1", array(':weid' => $weid, ':from_user' => $from_user));
        if (!empty($data)) {
            if (TIMESTAMP - $data['dateline'] < 60) {
                $this->showMsg('每分钟只能获取短信一次!');
            }
        }

        //验证码
        $checkcode = random(6, 1);
        $checkcode = $this->getNewCheckCode($checkcode);
        $data = array(
            'weid' => $weid,
            'from_user' => $from_user,
            'mobile' => $mobile,
            'checkcode' => $checkcode,
            'status' => 0,
            'dateline' => TIMESTAMP
        );

        $sendInfo = array();
        $sendInfo['username'] = $smsSetting['sms_username'];
        $sendInfo['pwd'] = $smsSetting['sms_pwd'];
        $sendInfo['mobile'] = $mobile;
        $sendInfo['content'] = "您的验证码是：" . $checkcode . "。如需帮助请联系客服。";
        $return_result_code = $this->_sendSms($sendInfo);
        if ($return_result_code != '100') {
            $code_msg = $this->sms_status[$return_result_code];
            $this->showMsg($code_msg . $return_result_code);
        } else {
            pdo_insert('weisrc_dish_sms_checkcode', $data);
            $this->showMsg('发送成功!', 1);
        }
    }

    public function getNewCheckCode($checkcode)
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $this->_from_user;

        $data = pdo_fetch("SELECT checkcode FROM " . tablename('weisrc_dish_sms_checkcode') . " WHERE weid = :weid AND checkcode = :checkcode AND from_user=:from_user ORDER BY `id` DESC limit 1", array(':weid' => $weid, ':checkcode' => $checkcode, ':from_user' => $from_user));

        if (!empty($data)) {
            $checkcode = random(6, 1);
            $this->getNewCheckCode($checkcode);
        }
        return $checkcode;
    }

    //用户打印机处理订单
    private function stringformat($string, $length = 0, $isleft = true)
    {
        $substr = '';
        if ($length == 0 || $string == '') {
            return $string;
        }
        if (strlen($string) > $length) {
            for ($i = 0; $i < $length; $i++) {
                $substr = $substr . "_";
            }
            $string = $string . '%%' . $substr;
        } else {
            for ($i = strlen($string); $i < $length; $i++) {
                $substr = $substr . " ";
            }
            $string = $isleft ? ($string . $substr) : ($substr . $string);
        }
        return $string;
    }

    public function oauth2($url)
    {
        global $_GPC, $_W;
        load()->func('communication');
        $code = $_GPC['code'];
        if (empty($code)) {
            message('code获取失败.');
        }
        $token = $this->getAuthorizationCode($code);
        $from_user = $token['openid'];
        $userinfo = $this->getUserInfo($from_user);
        $sub = 1;
        if ($userinfo['subscribe'] == 0) {
            //未关注用户通过网页授权access_token
            $sub = 0;
            $authkey = intval($_GPC['authkey']);
            if ($authkey == 0) {
                $oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $this->_appid . "&redirect_uri=" . urlencode($url) . "&response_type=code&scope=snsapi_userinfo&state=0#wechat_redirect";
                header("location:$oauth2_code");
            }
            $userinfo = $this->getUserInfo($from_user, $token['access_token']);
        }

        if (empty($userinfo) || !is_array($userinfo) || empty($userinfo['openid']) || empty($userinfo['nickname'])) {
            echo '<h1>获取微信公众号授权失败[无法取得userinfo], 请稍后重试！ 公众平台返回原始数据为: <br />' . $sub . $userinfo['meta'] . '<h1>';
            exit;
        }

        //设置cookie信息
        setcookie($this->_auth2_headimgurl, $userinfo['headimgurl'], time() + 3600 * 24);
        setcookie($this->_auth2_nickname, $userinfo['nickname'], time() + 3600 * 24);
        setcookie($this->_auth2_openid, $from_user, time() + 3600 * 24);
        setcookie($this->_auth2_sex, $userinfo['sex'], time() + 3600 * 24);
//        print_r($userinfo);
//        exit;
        return $userinfo;
    }

    public function getUserInfo($from_user, $ACCESS_TOKEN = '')
    {
        if ($ACCESS_TOKEN == '') {
            $ACCESS_TOKEN = $this->getAccessToken();
            $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$ACCESS_TOKEN}&openid={$from_user}&lang=zh_CN";
        } else {
            $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$ACCESS_TOKEN}&openid={$from_user}&lang=zh_CN";
        }

        $json = ihttp_get($url);
        $userInfo = @json_decode($json['content'], true);
        return $userInfo;
    }

    public function getAuthorizationCode($code)
    {
        $oauth2_code = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->_appid}&secret={$this->_appsecret}&code={$code}&grant_type=authorization_code";
        $content = ihttp_get($oauth2_code);
        $token = @json_decode($content['content'], true);
        if (empty($token) || !is_array($token) || empty($token['access_token']) || empty($token['openid'])) {
            $oauth2_code = $this->createMobileUrl('waprestlist', array(), true);
            header("location:$oauth2_code");
//            echo '微信授权失败, 请稍后重试! 公众平台返回原始数据为: <br />' . $content['meta'] . '<h1>';
            exit;
        }
        return $token;
    }

    public function getAccessToken()
    {
        global $_W;
        $account = $_W['account'];
        if ($this->_accountlevel < 4) {
            if (!empty($this->_account)) {
                $account = $this->_account;
            }
        }
        load()->classs('weixin.account');
        $accObj = WeixinAccount::create($account['acid']);
        $access_token = $accObj->fetch_token();
        return $access_token;
    }

    public function getCode($url)
    {
        global $_W;
        $url = urlencode($url);
        $oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->_appid}&redirect_uri={$url}&response_type=code&scope=snsapi_base&state=0#wechat_redirect";
        header("location:$oauth2_code");
    }

    public $actions_titles = array(
        'stores' => '门店信息',
//        'statistics' => '统计中心',
        'order' => '订单管理',
        'coupon' => '促销管理',
        'tables' => '餐桌管理',
        'queueorder' => '排号管理',
        'fans' => '会员管理',
        'goods' => '商品管理',
        'category' => '商品类别',
        'intelligent' => '智能推荐',
        'reservation' => '预定管理',
        'feedback' => '评论管理',
        'printsetting' => '打印机设置',
        'businesscenter' => '商户中心',
        'savewine' => '寄存管理',
    );
    public $sms_status = array(
        '100' => '发送成功',
        '101' => '验证失败',
        '102' => '手机号码格式不正确',
        '103' => '会员级别不够',
        '104' => '内容未审核',
        '105' => '内容过多',
        '106' => '账户余额不足',
        '107' => 'Ip受限',
        '108' => '手机号码发送太频繁，请换号或隔天再发',
        '109' => '帐号被锁定',
        '110' => '手机号发送频率持续过高，黑名单屏蔽数日',
        '111' => '系统升级',
    );

    public function doWebDeletemealtime()
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $id = intval($_GPC['id']);
        $storeid = intval($_GPC['storeid']);

        if (empty($storeid)) {
            $url = $this->createWebUrl('stores', array('op' => 'post', 'id' => $storeid));
        }

        pdo_delete('weisrc_dish_mealtime', array('id' => $id, 'weid' => $weid));
        message('操作成功', $url, 'success');
    }

    public function doWebSetAdProperty()
    {
        global $_GPC, $_W;
        $id = intval($_GPC['id']);
        $type = $_GPC['type'];
        $data = intval($_GPC['data']);
        empty($data) ? ($data = 1) : $data = 0;
        if (!in_array($type, array('status'))) {
            die(json_encode(array("result" => 0)));
        }
        pdo_update($this->table_ad, array($type => $data), array("id" => $id, "uniacid" => $_W['uniacid']));
        die(json_encode(array("result" => 1, "data" => $data)));
    }

    //统计中心
    public function doWebStatistics()
    {
        global $_W, $_GPC, $code;
        $weid = $this->_weid;
        $returnid = $this->checkPermission();
        $action = 'statistics';
        $title = '统计中心';
        $storeid = intval($_GPC['storeid']);
        if (empty($storeid)) {
            message('请选择门店!');
        }
        $url = $this->createWebUrl($action, array('op' => 'display'));
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        if ($operation == 'display') {
            $shoptypeid = intval($_GPC['shoptypeid']);
            $areaid = intval($_GPC['areaid']);
            $keyword = trim($_GPC['keyword']);

            if (checksubmit('submit')) { //排序
                if (is_array($_GPC['displayorder'])) {
                    foreach ($_GPC['displayorder'] as $id => $val) {
                        $data = array('displayorder' => intval($_GPC['displayorder'][$id]));
                        pdo_update($this->table_stores, $data, array('id' => $id));
                    }
                }
                message('操作成功!', $url);
            }
            $pindex = max(1, intval($_GPC['page']));
            $psize = 10;
            $where = "WHERE weid = {$weid}";

            if (!empty($keyword)) {
                $where .= " AND title LIKE '%{$keyword}%'";
            }
            if ($shoptypeid != 0) {
                $where .= " AND typeid={$shoptypeid} ";
            }
            if ($areaid != 0) {
                $where .= " AND areaid={$areaid} ";
            }
            if ($returnid != 0) {
                $where .= " AND id={$returnid} ";
            }

            $storeslist = pdo_fetchall("SELECT * FROM " . tablename($this->table_stores) . " {$where} order by displayorder desc,id desc LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
            if (!empty($storeslist)) {
                $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_stores) . " $where");
                $pager = pagination($total, $pindex, $psize);
            }
        } elseif ($operation == 'post') {
            load()->func('tpl');
            $id = intval($_GPC['id']); //门店编号
            $reply = pdo_fetch("select * from " . tablename($this->table_stores) . " where id=:id and weid =:weid", array(':id' => $id, ':weid' => $weid));
            $timelist = pdo_fetchall("SELECT * FROM " . tablename('weisrc_dish_mealtime') . " WHERE weid = :weid AND storeid=:storeid order by id", array(':weid' => $weid, ':storeid' => $id));

            if (empty($reply)) {
                $reply['begintime'] = "09:00";
                $reply['endtime'] = "18:00";
            }

            $piclist = unserialize($reply['thumb_url']);

            if (checksubmit('submit')) {
                $data = array(
                    'weid' => intval($_W['uniacid']),
                    'areaid' => intval($_GPC['area']),
                    'typeid' => intval($_GPC['type']),
                    'title' => trim($_GPC['title']),
                    'info' => trim($_GPC['info']),
                    'from_user' => trim($_GPC['from_user']),
                    'content' => trim($_GPC['content']),
                    'tel' => trim($_GPC['tel']),
                    'announce' => trim($_GPC['announce']),
                    'logo' => trim($_GPC['logo']),
                    'address' => trim($_GPC['address']),
                    'location_p' => trim($_GPC['location_p']),
                    'location_c' => trim($_GPC['location_c']),
                    'location_a' => trim($_GPC['location_a']),
                    'lng' => trim($_GPC['baidumap']['lng']),
                    'lat' => trim($_GPC['baidumap']['lat']),
                    'password' => trim($_GPC['password']),
                    'recharging_password' => trim($_GPC['recharging_password']),
                    'is_show' => intval($_GPC['is_show']),
                    'place' => trim($_GPC['place']),
                    'qq' => trim($_GPC['qq']),
                    'weixin' => trim($_GPC['weixin']),
                    'hours' => trim($_GPC['hours']),
                    'consume' => trim($_GPC['consume']),
                    'level' => intval($_GPC['level']),
                    'enable_wifi' => intval($_GPC['enable_wifi']),
                    'enable_card' => intval($_GPC['enable_card']),
                    'enable_room' => intval($_GPC['enable_room']),
                    'enable_park' => intval($_GPC['enable_park']),
                    'is_meal' => intval($_GPC['is_meal']),
                    'is_delivery' => intval($_GPC['is_delivery']),
                    'is_snack' => intval($_GPC['is_snack']),
                    'is_queue' => intval($_GPC['is_queue']),
                    'is_intelligent' => intval($_GPC['is_intelligent']),
                    'is_reservation' => intval($_GPC['is_reservation']),
                    'is_sms' => intval($_GPC['is_sms']),
                    'is_hot' => intval($_GPC['is_hot']),
                    'btn_reservation' => trim($_GPC['btn_reservation']),
                    'btn_eat' => trim($_GPC['btn_eat']),
                    'btn_delivery' => trim($_GPC['btn_delivery']),
                    'btn_snack' => trim($_GPC['btn_snack']),
                    'btn_queue' => trim($_GPC['btn_queue']),
                    'btn_intelligent' => trim($_GPC['btn_intelligent']),
                    'coupon_title1' => trim($_GPC['coupon_title1']),
                    'coupon_title2' => trim($_GPC['coupon_title2']),
                    'coupon_title3' => trim($_GPC['coupon_title3']),
                    'coupon_link1' => trim($_GPC['coupon_link1']),
                    'coupon_link2' => trim($_GPC['coupon_link2']),
                    'coupon_link3' => trim($_GPC['coupon_link3']),
                    'sendingprice' => trim($_GPC['sendingprice']),
                    'dispatchprice' => trim($_GPC['dispatchprice']),
                    'freeprice' => trim($_GPC['freeprice']),
                    'begintime' => trim($_GPC['begintime']),
                    'endtime' => trim($_GPC['endtime']),
                    'updatetime' => TIMESTAMP,
                    'dateline' => TIMESTAMP,
                    'delivery_within_days' => intval($_GPC['delivery_within_days']),
                    'delivery_radius' => floatval($_GPC['delivery_radius']),
                    'not_in_delivery_radius' => intval($_GPC['not_in_delivery_radius'])
                );

                if (istrlen($data['title']) == 0) {
                    message('没有输入标题.', '', 'error');
                }
                if (istrlen($data['title']) > 30) {
                    message('标题不能多于30个字。', '', 'error');
                }
                if (istrlen($data['tel']) == 0) {
//                    message('没有输入联系电话.', '', 'error');
                }
                if (istrlen($data['address']) == 0) {
                    //message('请输入地址。', '', 'error');
                }

                if (is_array($_GPC['thumbs'])) {
//                    $data['thumb_url'] = serialize($_GPC['thumbs']);
                }

                if (!empty($id)) {
                    unset($data['dateline']);
                    pdo_update($this->table_stores, $data, array('id' => $id, 'weid' => $_W['uniacid']));
                } else {
                    $shoptotal = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_stores) . " WHERE weid=:weid", array(':weid' => $this->_weid));
                    if (!empty($config['storecount'])) {
                        if ($shoptotal >= $config['storecount']) {
                            message('您只能添加' . $config['storecount'] . '家门店');
                        }
                    }
                    $id = pdo_insert($this->table_stores, $data);
                }

                if (is_array($_GPC['begintimes'])) {
                    foreach ($_GPC['begintimes'] as $oid => $val) {
                        $begintime = $_GPC['begintimes'][$oid];
                        $endtime = $_GPC['endtimes'][$oid];
                        if (empty($begintime) || empty($endtime)) {
                            continue;
                        }

                        $data = array(
                            'weid' => $weid,
                            'storeid' => $id,
                            'begintime' => $begintime,
                            'endtime' => $endtime,
                        );
                        pdo_update('weisrc_dish_mealtime', $data, array('id' => $id));
                    }
                }

                //增加
                if (is_array($_GPC['newbegintime'])) {
                    foreach ($_GPC['newbegintime'] as $nid => $val) {
                        $begintime = $_GPC['newbegintime'][$nid];
                        $endtime = $_GPC['newendtime'][$nid];
                        if (empty($begintime) || empty($endtime)) {
                            continue;
                        }

                        $data = array(
                            'weid' => $weid,
                            'storeid' => $id,
                            'begintime' => $begintime,
                            'endtime' => $endtime,
                            'dateline' => TIMESTAMP
                        );
                        pdo_insert('weisrc_dish_mealtime', $data);
                    }
                }
                message('操作成功!', $url);
            }
        } elseif ($operation == 'delete') {
            $id = intval($_GPC['id']);
            $store = pdo_fetch("SELECT id FROM " . tablename($this->table_stores) . " WHERE id = '$id'");
            if (empty($store)) {
                message('抱歉，不存在或是已经被删除！', $this->createWebUrl('stores', array('op' => 'display')), 'error');
            }
            pdo_delete($this->table_stores, array('id' => $id, 'weid' => $_W['uniacid']));
            message('删除成功！', $this->createWebUrl('stores', array('op' => 'display')), 'success');
        }

        $echarts_path = $_W['siteroot'] . "/addons/weisrc_dish/template/js/dist";
        include $this->template('data');
    }

    public function updateFansFirstStore($from_user, $storeid)
    {
        global $_GPC, $_W;
        $weid = $this->_weid;
        $fans = pdo_fetch("SELECT * FROM " . tablename($this->table_fans) . " WHERE from_user = :from_user AND weid=:weid", array(':from_user' => $from_user, ':weid' => $this->_weid));
        if (!empty($fans) && $fans['storeid'] == 0) {
            pdo_update($this->table_fans, array('storeid' => $storeid), array('id' => $fans['id']));
        }
    }

    public function updateFansData($from_user)
    {
        global $_GPC, $_W;
        $weid = $this->_weid;
        $fans = pdo_fetch("SELECT * FROM " . tablename($this->table_fans) . " WHERE from_user = :from_user AND weid=:weid", array(':from_user' => $from_user, ':weid' => $this->_weid));
        if (!empty($fans)) {
            $count = pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename($this->table_order) . "  WHERE weid = :weid AND status=3 AND from_user = :from_user", array(':weid' => $weid, ':from_user' => $from_user));
            $totalprice = pdo_fetchcolumn("SELECT sum(totalprice) FROM " . tablename($this->table_order) . "  WHERE weid = :weid AND status=3 AND from_user = :from_user", array(':weid' => $weid, ':from_user' => $from_user));
            $avgprice = pdo_fetchcolumn("SELECT AVG(totalprice) FROM " . tablename($this->table_order) . "  WHERE weid = :weid AND status=3 AND from_user = :from_user", array(':weid' => $weid, ':from_user' => $from_user));

            pdo_update($this->table_fans, array('totalprice' => $totalprice, 'avgprice' => $avgprice, 'totalcount' => $count), array('id' => $fans['id']));
        }
    }

    public function set_commission($orderid)
    {
        global $_GPC, $_W;
        $weid = $this->_weid;

        $setting = $this->getSetting();
        if ($setting['is_commission'] == 1) {
            $order = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE id=:id AND weid=:weid LIMIT 1", array(':id' => $orderid, ':weid' => $this->_weid));
            if ($order) {
                $fans = pdo_fetch("SELECT * FROM " . tablename($this->table_fans) . " WHERE from_user=:from_user AND weid=:weid LIMIT 1", array(':from_user' => $order['from_user'], ':weid' => $this->_weid));
                if ($fans['agentid'] > 0 || $fans['is_commission'] == 2) { //粉丝有上级或者是代理商
                    if ($fans['is_commission'] == 2 && $setting['commission_money_mode'] == 2) { //用户是代理商,商品佣金模式
                        $agent = $fans;
                    } else {
                        $agent = pdo_fetch("SELECT * FROM " . tablename($this->table_fans) . " WHERE id=:id AND weid=:weid LIMIT 1", array(':id' => $fans['agentid'], ':weid' => $this->_weid));
                    }

                    $is_commission = 1;
                    if ($setting['commission_mode'] == 2) { //代理商模式
                        if ($agent['is_commission'] == 1) {//普通用户
                            $is_commission = 0;
                        }
                    }

                    $totalprice = floatval($order['totalprice']);
                    //1级
                    if ($agent && $is_commission == 1) {
                        $commission1_price = 0;
                        if ($setting['commission_money_mode'] == 1) { //以订单金额计算
                            $commission1_rate_max = floatval($setting['commission1_rate_max']);
                            $commission1_value_max = intval($setting['commission1_value_max']);
                            $commission1_price = $totalprice * $commission1_rate_max / 100;
                            if ($commission1_value_max > 0) {
                                if ($commission1_price > $commission1_value_max) {
                                    $commission1_price = $commission1_value_max;
                                }
                            }
                        } else { //以商品佣金计算
                            $goods = $this->get_commission_money($orderid);
                            foreach ($goods as $key => $val) {
                                $commission_money1 = floatval($val['commission_money1']) * intval($val['total']);
                                $commission_money2 = floatval($val['commission_money2']) * intval($val['total']);
                                if ($agent['agentid'] == 0) { //顶级
                                    $commission1_price = $commission1_price + $commission_money1 + $commission_money2;
                                } else {
                                    $commission1_price = $commission1_price + $commission_money1;
                                }
                            }
                        }

                        $data = array(
                            'weid' => $weid,
                            'storeid' => $order['storeid'],
                            'orderid' => $order['id'],
                            'agentid' => intval($agent['id']),//奖励上级
                            'ordersn' => $order['ordersn'],
                            'level' => 1,
                            'from_user' => $order['from_user'],
                            'price' => $commission1_price,
                            'status' => $setting['commission_settlement'] == 1 ? 1 : 0,
                            'dateline' => TIMESTAMP
                        );
                        pdo_insert($this->table_commission, $data);
                        if ($setting['commission_settlement'] == 1) {
                            $this->updateFansCommission($agent['from_user'], 'commission_price', $commission1_price, "单号{$order['ordersn']}一级佣金奖励");
                        }
                    }
                    //2级
                    if ($setting['commission_level'] > 1) {
                        if ($agent['agentid'] > 0) {
                            $agent2 = pdo_fetch("SELECT * FROM " . tablename($this->table_fans) . " WHERE id=:id AND weid=:weid LIMIT 1", array(':id' => $agent['agentid'], ':weid' => $this->_weid));

                            $is_commission = 1;
                            if ($setting['commission_mode'] == 2) { //代理商模式
                                if ($agent2['is_commission'] == 1) {//普通用户
                                    $is_commission = 0;
                                }
                            }
                            $commission2_price = 0;
                            if ($is_commission == 1) {
                                if ($setting['commission_money_mode'] == 1) { //以订单金额计算
                                    $commission2_rate_max = floatval($setting['commission2_rate_max']);
                                    $commission2_value_max = intval($setting['commission2_value_max']);
                                    $commission2_price = $totalprice * $commission2_rate_max / 100;
                                    if ($commission2_value_max > 0) {
                                        if ($commission2_price > $commission2_value_max) {
                                            $commission2_price = $commission2_value_max;
                                        }
                                    }
                                } else {
                                    $goods = $this->get_commission_money($orderid);
                                    foreach ($goods as $key => $val) {
                                        $commission_money2 = floatval($val['commission_money2']) * intval($val['total']);
                                        $commission2_price = $commission2_price + $commission_money2;
                                    }
                                }

                                $data = array(
                                    'weid' => $weid,
                                    'storeid' => $order['storeid'],
                                    'orderid' => $order['id'],
                                    'agentid' => intval($agent2['id']),//奖励上级用户
                                    'ordersn' => $order['ordersn'],
                                    'level' => 2,
                                    'from_user' => $agent2['from_user'],
                                    'price' => $commission2_price,
                                    'status' => $setting['commission_settlement'] == 1 ? 1 : 0,
                                    'dateline' => TIMESTAMP
                                );
                                pdo_insert($this->table_commission, $data);
                                if ($setting['commission_settlement'] == 1) {
                                    $this->updateFansCommission($agent2['from_user'], 'commission_price', $commission2_price, "单号{$order['ordersn']}二级佣金奖励");
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function updateFansCommission($from_user, $credittype, $price, $log)
    {
        global $_GPC, $_W;
        $weid = $this->_weid;

        $price = floatval($price);
        if (empty($price)) {
            return true;
        }

        $value = pdo_fetchcolumn("SELECT {$credittype} FROM " . tablename($this->table_fans) . " WHERE `from_user` = :from_user AND weid=:weid", array(':from_user' => $from_user, ':weid' => $weid));

        if ($price > 0 || ($value + $price >= 0)) {
            pdo_update($this->table_fans, array($credittype => $value + $price), array('from_user' => $from_user, 'weid' => $weid));
        } else {
            return error('-1', "积分类型为“{$credittype}”的积分不够，无法操作。");
        }

        $data = array(
            'weid' => $_W['uniacid'],
            'from_user' => $from_user,
            'credittype' => $credittype,
            'num' => $price,
            'dateline' => TIMESTAMP,
            'operator' => '',
            'remark' => $log,
        );
        pdo_insert('weisrc_dish_credits_record', $data);
    }

    public function get_commission_money($orderid)
    {
        $goods = pdo_fetchall("SELECT a.goodsid,a.total,b.commission_money1,b.commission_money2 FROM " . tablename($this->table_order_goods) . " a INNER JOIN
" . tablename($this->table_goods) . " b ON a.goodsid=b.id WHERE a.orderid = :orderid", array(':orderid' => $orderid));
        return $goods;
    }

    public function doMobileGetQrcode()
    {
        global $_W, $_GPC;
        $url = $_GPC['url'];
        require(IA_ROOT . '/framework/library/qrcode/phpqrcode.php');
        $errorCorrectionLevel = "L";
        $matrixPointSize = "5";
        QRcode::png($url, false, $errorCorrectionLevel, $matrixPointSize);
        exit();
    }

    public function doWebGetQrcode()
    {
        global $_W, $_GPC;
        $url = $_GPC['url'];
        require(IA_ROOT . '/framework/library/qrcode/phpqrcode.php');
        $errorCorrectionLevel = "L";
        $matrixPointSize = "5";
        QRcode::png($url, false, $errorCorrectionLevel, $matrixPointSize);
        exit();
    }

    //卡号码
    public function get_save_number($weid)
    {
        global $_W, $_GPC;
        $save_number = pdo_fetch("select savenumber from " . tablename($this->table_savewine_log) . " where weid =:weid order by id desc limit 1", array(':weid' => $weid));
        if (!empty($save_number)) {
            return intval($save_number['savenumber']) + 1;
        } else {
            return 1000001;
        }
    }

    public function doWebCheckOrder()
    {
        global $_W, $_GPC;
        $storeid = intval($_GPC['storeid']);
        if ($storeid == 0) {
            $order = pdo_fetch("SELECT 1 FROM " . tablename($this->table_order) . " WHERE weid=:weid AND status=0 LIMIT 1", array(':weid' => $this->_weid));
            $service = pdo_fetch("SELECT content FROM " . tablename($this->table_service_log) . " WHERE weid=:weid AND status=0 ORDER BY id DESC LIMIT 1", array(':weid' => $this->_weid));
        } else {
            $order = pdo_fetch("SELECT 1 FROM " . tablename($this->table_order) . " WHERE weid=:weid AND status=0  AND storeid=:storeid LIMIT 1", array(':weid' => $this->_weid, ':storeid' => $storeid));
            $service = pdo_fetch("SELECT content FROM " . tablename($this->table_service_log) . " WHERE weid=:weid AND status=0 AND storeid=:storeid ORDER BY id DESC LIMIT 1", array(':weid' => $this->_weid, ':storeid' => $storeid));
        }

        if ($service) {
            if (!empty($service['content'])) {
                exit($service['content']);
            }
        }
        if ($order) {
            exit('success');
        }
    }

    public function doWebCheckDeliveryOrder()
    {
        global $_W, $_GPC;
        $weid = $this->_weid;

        $setting = $this->getSetting();
        $limittime = intval($setting['delivery_auto_time']);//定时推送
        if ($limittime > 0) {
            $strwhere = " WHERE weid=:weid AND paytype<>0 AND delivery_status=0 AND delivery_notice=0 AND status<>-1 AND status<>3 AND dining_mode=2 AND   unix_timestamp(now())-dateline>{$limittime}  ";

            $order = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " {$strwhere} ORDER BY
id LIMIT 1 ", array(':weid' => $weid));
            if (!empty($order)) {
                pdo_update($this->table_order, array('delivery_notice' => 1), array('id' => $order['id']));

                $deliverys = pdo_fetchall("SELECT * FROM " . tablename($this->table_account) . " WHERE weid = :weid AND role=4 AND status=2 ORDER BY id DESC ", array(':weid' => $this->_weid));
                foreach ($deliverys as $key => $value) {
                    $this->sendDeliveryOrderNotice($order['id'], $value['from_user'], $setting, 1);
                }

            }
        }

        $delivery_finish_time = intval($setting['delivery_finish_time']);//定时完成
        if ($delivery_finish_time > 0) {
            $strwhere = " WHERE weid = '{$weid}' AND dining_mode=2 AND status<>3 AND status<>-1 AND delivery_finish_time<>0 ";
            $strwhere .= " AND delivery_status=2 ";
            $strwhere .= "  AND unix_timestamp(now())-delivery_finish_time>{$delivery_finish_time} ";
            $orderlist = pdo_fetchall("SELECT * FROM " . tablename($this->table_order) . " {$strwhere} ORDER BY id DESC LIMIT 200");
            foreach ($orderlist as $key => $value) {
                if ($value['isfinish'] == 0) {
                    //计算积分
                    $this->setOrderCredit($value['id']);
                    pdo_update($this->table_order, array('isfinish' => 1), array('id' => $value['id']));
                    $this->set_commission($value['id']);
                    //奖励配送员
                    $delivery_money = floatval($value['delivery_money']);//配送佣金
                    $delivery_id = intval($value['delivery_id']);//配送员
//                    if ($delivery_money > 0) {
//                        $deliveryuser = pdo_fetch("SELECT * FROM " . tablename($this->table_account) . " where weid=:weid AND id=:id LIMIT 1", array(':weid' => $weid, ':id' => $delivery_id));
//                        if (!empty($deliveryuser)) {
//                            $this->updateFansCommission($deliveryuser['from_user'], 'delivery_price', $delivery_money, "单号{$value['ordersn']}配送佣金奖励");
//                        }
//                    }
                    if ($delivery_money > 0) {
                        $data = array(
                            'weid' => $_W['uniacid'],
                            'storeid' => $value['storeid'],
                            'orderid' => $value['id'],
                            'delivery_id' => $delivery_id,
                            'price' => $delivery_money,
                            'dateline' => TIMESTAMP,
                            'status' => 0
                        );
                        pdo_insert("weisrc_dish_delivery_record", $data);
                    }

                    pdo_update($this->table_order, array('status' => 3, 'finishtime' => TIMESTAMP), array('id' => $value['id'], 'weid' => $weid));
                    $this->addOrderLog($value['id'], $_W['user']['username'], 2, 2, 4);
                    $this->updateFansData($value['from_user']);
                    $this->updateFansFirstStore($value['from_user'], $value['storeid']);
                    $value = $this->getOrderById($value['id']);
                    $store = $this->getStoreById($value['storeid']);
                    $this->sendOrderNotice($value, $store, $setting);
                }
            }
        }
    }

    public function doMobileCheckOrder()
    {
        global $_W, $_GPC;
        $storeid = intval($_GPC['storeid']);
        if ($storeid == 0) {
            $order = pdo_fetch("SELECT 1 FROM " . tablename($this->table_order) . " WHERE weid=:weid AND status=0 LIMIT 1", array(':weid' => $this->_weid));
            $service = pdo_fetch("SELECT content FROM " . tablename($this->table_service_log) . " WHERE weid=:weid AND status=0 ORDER BY id DESC LIMIT 1", array(':weid' => $this->_weid));
        } else {
            $order = pdo_fetch("SELECT 1 FROM " . tablename($this->table_order) . " WHERE weid=:weid AND status=0 AND storeid=:storeid LIMIT 1", array(':weid' => $this->_weid, ':storeid' => $storeid));
            $service = pdo_fetch("SELECT content FROM " . tablename($this->table_service_log) . " WHERE weid=:weid AND status=0 AND storeid=:storeid ORDER BY id DESC LIMIT 1", array(':weid' => $this->_weid, ':storeid' => $storeid));
        }

        if ($order) {
            exit('success');
        }
    }

    public function doWebOperatorNotice()
    {
        global $_W, $_GPC;
        $storeid = intval($_GPC['storeid']);
        $returnid = $this->checkPermission($storeid);

        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'new';
        if ($storeid == 0) {
            $service = pdo_fetch("SELECT * FROM " . tablename($this->table_service_log) . " WHERE weid=:weid AND status=0 ORDER by id DESC LIMIT 1", array(':weid' => $this->_weid));
            if ($operation == 'new') {
                pdo_update($this->table_service_log, array('status' => 1), array('id' => $service['id']));
            }
            if ($operation == 'all') {
                pdo_update($this->table_service_log, array('status' => 1), array('weid' => $this->_weid));
            }
            message('操作成功！', $this->createWebUrl('allorder', array('op' => 'display', 'storeid' => $storeid)), 'success');
        } else {
            $service = pdo_fetch("SELECT * FROM " . tablename($this->table_service_log) . " WHERE weid=:weid AND status=0 AND storeid=:storeid ORDER by id DESC LIMIT 1", array(':weid' => $this->_weid, ':storeid' => $storeid));
            if ($operation == 'new') {
                pdo_update($this->table_service_log, array('status' => 1), array('id' => $service['id']));
            }
            if ($operation == 'all') {
                pdo_update($this->table_service_log, array('status' => 1), array('weid' => $this->_weid, 'storeid' => $storeid));
            }
            message('操作成功！', $this->createWebUrl('order', array('op' => 'display', 'storeid' => $storeid)), 'success');
        }
    }

    public function doWebRetreat()
    {
        global $_W, $_GPC;
        $storeid = intval($_GPC['storeid']);
        $this->checkPermission($storeid);

        $orderid = intval($_GPC['orderid']);
        $goodsid = intval($_GPC['goodsid']);
        $goodsnum = intval($_GPC['goodsnum']);//商品数量

        if ($goodsnum == 0) {
            message('商品删除数量不能为0!');
        }

        $order = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE weid=:weid AND id=:orderid
        ORDER by id
DESC LIMIT 1", array(':weid' => $this->_weid, ':orderid' => $orderid));
        if (empty($order)) {
            message('订单不存在!');
        }

        $item = pdo_fetch("SELECT * FROM " . tablename($this->table_order_goods) . " WHERE weid=:weid AND goodsid=:goodsid AND orderid=:orderid ORDER by id
DESC LIMIT 1", array(':weid' => $this->_weid, ':goodsid' => $goodsid, ':orderid' => $orderid));

        if (empty($item)) {
            message('商品不存在!');
        }

        $price = floatval($item['price']);
        $goodsprice = $price * $goodsnum;
        $total = intval($item['total']);

        if ($goodsnum > $total) {
            message('退货数量大于商品数量!');
        }

        if ($total == $goodsnum) {
            pdo_delete($this->table_order_goods, array('id' => $item['id']));
        } else {
            $total = $total - $goodsnum;
            pdo_update($this->table_order_goods, array('total' => $total), array('id' => $item['id']));
        }

        $goods = pdo_fetch("SELECT * FROM " . tablename($this->table_goods) . " WHERE weid=:weid AND id=:goodsid ORDER by id
DESC LIMIT 1", array(':weid' => $this->_weid, ':goodsid' => $item['goodsid']));

        $touser = $_W['user']['username'] . '&nbsp;退菜：' . $goods['title'] . "*" . $goodsnum . ",";
        $this->addOrderLog($orderid, $touser, 2, 2, 1);

        $totalprice = floatval($order['totalprice']) - $goodsprice;
        $goodsprice = floatval($order['goodsprice']) - $goodsprice;
        //更新订单金额
        pdo_update($this->table_order, array('totalprice' => $totalprice, 'goodsprice' => $goodsprice), array('weid' =>
            $this->_weid, 'id' => $orderid));

        $paylog = pdo_fetch("SELECT * FROM " . tablename('core_paylog') . " WHERE tid=:tid AND uniacid=:uniacid AND status=0 AND module='weisrc_dish'
ORDER BY plid
DESC LIMIT 1", array(':tid' => $orderid, ':uniacid' => $this->_weid));
        if (!empty($paylog)) {
            pdo_update('core_paylog', array('fee' => $_GPC['updateprice']), array('plid' => $paylog['plid']));
        }
        if ($storeid == 0) {
            message('操作成功！', $this->createWebUrl('allorder', array('op' => 'detail', 'storeid' => $storeid, 'id' => $orderid)), 'success');
        } else {
            message('操作成功！', $this->createWebUrl('order', array('op' => 'detail', 'id' => $orderid, 'storeid' => $storeid)), 'success');
        }
    }

    public function doMobilePayTip()
    {
        global $_W, $_GPC;
        $orderid = intval($_GPC['orderid']);
		$order = $this->getOrderById($orderid);
        $storeid = $order['storeid'];
		//打印机配置信息
        $settings = pdo_fetchall("SELECT * FROM " . tablename($this->table_print_setting) . " WHERE storeid = :storeid AND print_status=1 AND type='jinyun' ", array(':storeid' => $storeid));
		foreach ($settings as $item => $value) {
			if ($value['print_type'] == '0'){
				$this->_jinyunSendFreeMessage($orderid);
			}
		}
        $url = $this->createMobileUrl('pay', array('orderid' => $orderid), true);
        message('订单创建，等待付款', $url, 'success');
    }

    //设置会员金额
    public function setFansCoin($from_user, $credit2, $remark)
    {
        load()->model('mc');
        load()->func('compat.biz');
        $uid = mc_openid2uid($from_user);
        $fans = mc_fetch($uid, array("credit2"));
        if (!empty($fans)) {
            $uid = intval($fans['uid']);
            $log = array();
            $log[0] = $uid;
            $log[1] = $remark;
            return mc_credit_update($uid, 'credit2', $credit2, $log);
        }
    }

    public function exportexcel($data = array(), $title = array(), $filename = 'report')
    {
        header("Content-type:application/octet-stream");
        header("Accept-Ranges:bytes");
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=" . $filename . ".xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        //导出xls 开始
        if (!empty($title)) {
            foreach ($title as $k => $v) {
                $title[$k] = iconv("UTF-8", "GB2312", $v);
            }
            $title = implode("\t", $title);
            echo "$title\n";
        }
        if (!empty($data)) {
            foreach ($data as $key => $val) {
                foreach ($val as $ck => $cv) {
                    $data[$key][$ck] = iconv("UTF-8", "GB2312", $cv);
                }
                $data[$key] = implode("\t", $data[$key]);
            }
            echo implode("\n", $data);
        }
    }

    //设置订单积分
    public function setOrderCredit($orderid, $add = true)
    {
        $order = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE id=:id LIMIT 1", array(':id' => $orderid));
        if (empty($order)) {
            return false;
        }

        $ordergoods = pdo_fetchall("SELECT goodsid, total FROM " . tablename($this->table_order_goods) . " WHERE orderid = :orderid", array(':orderid' => $orderid));
        if (!empty($ordergoods)) {
            $credit = 0.00;
            $sql = 'SELECT `credit` FROM ' . tablename($this->table_goods) . ' WHERE `id` = :id';
            foreach ($ordergoods as $goods) {
                $goodsCredit = pdo_fetchcolumn($sql, array(':id' => $goods['goodsid']));
                $credit += $goodsCredit * floatval($goods['total']);
            }
        }

        //增加积分
        if (!empty($credit)) {
            load()->model('mc');
            load()->func('compat.biz');
            $uid = mc_openid2uid($order['from_user']);
            $fans = fans_search($uid, array("credit1"));
            if (!empty($fans)) {
                $uid = intval($fans['uid']);
                $remark = $add == true ? '微点餐积分奖励 订单ID:' . $orderid : '微点餐积分扣除 订单ID:' . $orderid;
                $log = array();
                $log[0] = $uid;
                $log[1] = $remark;
                mc_credit_update($uid, 'credit1', $credit, $log);
            }
        }
        pdo_update($this->table_order, array('credit' => $credit), array('id' => $orderid));
        return true;
    }

    //入口设置
    public function doWebSetRule()
    {
        global $_W;
        $rule = pdo_fetch("SELECT id FROM " . tablename('rule') . " WHERE module = 'weisrc_dish' AND weid = '{$_W['uniacid']}' order by id desc");
        if (empty($rule)) {
            header('Location: ' . $_W['siteroot'] . create_url('rule/post', array('module' => 'weisrc_dish', 'name' => '微点餐')));
            exit;
        } else {
            header('Location: ' . $_W['siteroot'] . create_url('rule/post', array('module' => 'weisrc_dish', 'id' => $rule['id'])));
            exit;
        }
    }

    public function doWebUploadExcel()
    {
        global $_GPC, $_W;

        if ($_GPC['leadExcel'] == "true") {
            $filename = $_FILES['inputExcel']['name'];
            $tmp_name = $_FILES['inputExcel']['tmp_name'];

            $flag = $this->checkUploadFileMIME($_FILES['inputExcel']);
            if ($flag == 0) {
                message('文件格式不对.');
            }

            if (empty($tmp_name)) {
                message('请选择要导入的Excel文件！');
            }

            $msg = $this->uploadFile($filename, $tmp_name, $_GPC);

            if ($msg == 1) {
                message('导入成功！', referer(), 'success');
            } else {
                message($msg, '', 'error');
            }
        }
    }

    function uploadFile($file, $filetempname, $array)
    {
        //自己设置的上传文件存放路径
        $filePath = '../addons/weisrc_dish/upload/';
        include 'plugin/phpexcelreader/reader.php';

        $data = new Spreadsheet_Excel_Reader();
        $data->setOutputEncoding('utf-8');

        //注意设置时区
        $time = date("y-m-d-H-i-s"); //去当前上传的时间
        $extend = strrchr($file, '.');
        //上传后的文件名
        $name = $time . $extend;
        $uploadfile = $filePath . $name; //上传后的文件名地址

        if (copy($filetempname, $uploadfile)) {
            if (!file_exists($filePath)) {
                echo '文件路径不存在.';
                return;
            }
            if (!is_readable($uploadfile)) {
                echo("文件为只读,请修改文件相关权限.");
                return;
            }
            $data->read($uploadfile);
            error_reporting(E_ALL ^ E_NOTICE);
            $count = 0;
            for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) { //$=2 第二行开始
                //以下注释的for循环打印excel表数据
                for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
                    //echo "\"".$data->sheets[0]['cells'][$i][$j]."\",";
                }

                $row = $data->sheets[0]['cells'][$i];
                //message($data->sheets[0]['cells'][$i][1]);

                if ($array['ac'] == "category") {
                    $count = $count + $this->upload_category($row, TIMESTAMP, $array);
                } else if ($array['ac'] == "goods") {
                    $count = $count + $this->upload_goods($row, TIMESTAMP, $array);
                } else if ($array['ac'] == "store") {
                    $count = $count + $this->upload_store($row, TIMESTAMP, $array);
                }
            }
        }
        if ($count == 0) {
            $msg = "导入失败,数据已经存在！";
        } else {
            $msg = 1;
        }
        return $msg;
    }

    private function checkUploadFileMIME($file)
    {
        // 1.through the file extension judgement 03 or 07
        $flag = 0;
        $file_array = explode(".", $file ["name"]);
        $file_extension = strtolower(array_pop($file_array));

        // 2.through the binary content to detect the file
        switch ($file_extension) {
            case "xls" :
                // 2003 excel
                $fh = fopen($file ["tmp_name"], "rb");
                $bin = fread($fh, 8);
                fclose($fh);
                $strinfo = @unpack("C8chars", $bin);
                $typecode = "";
                foreach ($strinfo as $num) {
                    $typecode .= dechex($num);
                }
                if ($typecode == "d0cf11e0a1b11ae1") {
                    $flag = 1;
                }
                break;
            case "xlsx" :
                // 2007 excel
                $fh = fopen($file ["tmp_name"], "rb");
                $bin = fread($fh, 4);
                fclose($fh);
                $strinfo = @unpack("C4chars", $bin);
                $typecode = "";
                foreach ($strinfo as $num) {
                    $typecode .= dechex($num);
                }
                echo $typecode . 'test';
                if ($typecode == "504b34") {
                    $flag = 1;
                }
                break;
        }
        return $flag;
    }

    function upload_goods($strs, $time, $array)
    {
        global $_W;
        $insert = array();

        if (empty($strs[1])) {
            return 0;
        }

        $storeid = $array['storeid'];
        $weid = $this->_weid;

        //类别
        $category = pdo_fetch("SELECT id FROM " . tablename($this->table_category) . " WHERE name=:name AND weid=:weid AND storeid=:storeid", array(':name' => trim($strs[2]), ':weid' => $weid, ':storeid' => $storeid));
        //标签
        $label = pdo_fetch("SELECT id FROM " . tablename($this->table_print_label) . " WHERE title=:title AND weid=:weid AND storeid=:storeid", array(':title' => trim($strs[12]), ':weid' => $weid, ':storeid' => $storeid));

        $insert['pcate'] = empty($category) ? 0 : intval($category['id']);
        $insert['title'] = $strs[1];
        $insert['displayorder'] = $strs[3];
        $insert['unitname'] = $strs[4];
        $insert['marketprice'] = $strs[5];
        $insert['memberprice'] = $strs[6];
        $insert['productprice'] = $strs[7];
        $insert['packvalue'] = $strs[8];
        $insert['credit'] = $strs[9];
        $insert['subcount'] = $strs[10];
        $insert['thumb'] = $strs[11];
        $insert['labelid'] = empty($label) ? 0 : intval($label['id']);
        $insert['description'] = $strs[13];
        $insert['counts'] = $strs[14];
        $insert['dateline'] = TIMESTAMP;
        $insert['status'] = 1;
        $insert['recommend'] = 0;
        $insert['ccate'] = 0;
        $insert['deleted'] = 0;
        $insert['storeid'] = $array['storeid'];
        $insert['weid'] = $_W['uniacid'];

        $goods = pdo_fetch("SELECT * FROM " . tablename($this->table_goods) . " WHERE title=:title AND weid=:weid AND storeid=:storeid", array(':title' => $strs[1], 'pcate' => $category['id'], ':weid' => $weid, ':storeid' => $storeid));

        if (empty($goods)) {
            return pdo_insert($this->table_goods, $insert);
        } else {
            return pdo_update($this->table_goods, $insert, array('id' => $goods['id']));
        }
    }

    function upload_category($strs, $time, $array)
    {
        global $_W;
        if (empty($strs[1])) {
            return 0;
        }

        $storeid = $array['storeid'];
        $weid = $this->_weid;

        $insert = array();
        $insert['name'] = $strs[1];
        $insert['parentid'] = 0;
        $insert['displayorder'] = 0;
        $insert['enabled'] = 1;
        $insert['storeid'] = $array['storeid'];
        $insert['weid'] = $_W['uniacid'];

        $category = pdo_fetch("SELECT * FROM " . tablename($this->table_category) . " WHERE name=:name AND weid=:weid AND storeid=:storeid", array(':name' => $strs[1], ':weid' => $weid, ':storeid' => $storeid));

        if (empty($category)) {
            return pdo_insert($this->table_category, $insert);
        } else {
            return pdo_insert($this->table_category, $insert, array('id' => $category['id']));
        }
    }

    function upload_store($strs, $time, $array)
    {
        global $_W;
        $weid = $this->_weid;

        if (empty($strs[1])) {
            return 0;
        }

        //类别
        $type = pdo_fetch("SELECT id FROM " . tablename($this->table_type) . " WHERE name=:name AND weid=:weid ", array(':name' => trim($strs[2]), ':weid' => $weid));

        if (!empty($type)) {
            $typeid = $type['id'];
        } else {
            $data = array(
                'weid' => $weid,
                'name' => trim($strs[2]),
                'thumb' => '',
                'url' => '',
                'displayorder' => 0,
                'parentid' => 0,
            );
            pdo_insert($this->table_type, $data);
            $typeid = pdo_insertid();
        }

        $area = pdo_fetch("SELECT id FROM " . tablename($this->table_area) . " WHERE name=:name AND weid=:weid ", array(':name' => trim($strs[3]), ':weid' => $weid));
        if (!empty($area)) {
            $areaid = $area['id'];
        } else {
            $data = array(
                'weid' => $_W['uniacid'],
                'name' => trim($strs[3]),
                'displayorder' => 0,
                'parentid' => 0,
            );
            pdo_insert($this->table_area, $data);
            $areaid = pdo_insertid();
        }

        $insert = array();
        $insert['weid'] = $_W['uniacid'];
        $insert['title'] = $strs[1];
        $insert['areaid'] = $areaid;
        $insert['typeid'] = $typeid;
        $insert['level'] = intval($strs[4]);
        $insert['consume'] = $strs[5];

        $insert['info'] = $strs[6];
        $insert['logo'] = $strs[7];
        $insert['content'] = $strs[8];

        $insert['tel'] = $strs[9];
        $insert['address'] = $strs[10];
        $insert['begintime'] = $strs[11];
        $insert['endtime'] = $strs[12];
        $insert['place'] = '';
        $insert['hours'] = '';
        $insert['password'] = '';
        $insert['recharging_password'] = '';
        $insert['is_show'] = 1;

        $insert['lng'] = $strs[13];
        $insert['lat'] = $strs[14];
        $insert['enable_wifi'] = 1;
        $insert['enable_card'] = 1;
        $insert['enable_room'] = 1;
        $insert['enable_park'] = 1;
        $insert['is_meal'] = 1;
        $insert['is_delivery'] = 1;
        $insert['is_snack'] = 1;
        $insert['is_queue'] = 1;

        $insert['updatetime'] = TIMESTAMP;
        $insert['dateline'] = TIMESTAMP;

        $store = pdo_fetch("SELECT * FROM " . tablename('weisrc_dish_stores') . " WHERE title=:title AND weid=:weid LIMIT 1", array(':title' => $strs[1], ':weid' => $_W['uniacid']));

        if (empty($store)) {
            return pdo_insert('weisrc_dish_stores', $insert);
        } else {
            return pdo_update('weisrc_dish_stores', $insert, array('id' => $store['id']));
        }
    }

    public function message($error, $url = '', $errno = -1)
    {
        $data = array();
        $data['errno'] = $errno;
        if (!empty($url)) {
            $data['url'] = $url;
        }
        $data['error'] = $error;
        echo json_encode($data);
        exit;
    }

    //----------------------以下是接口定义实现，第三方应用可根据具体情况直接修改----------------------------
    function sendFreeMessage($msg)
    {
        $msg['reqTime'] = number_format(1000 * time(), 0, '', '');
        $content = $msg['memberCode'] . $msg['msgDetail'] . $msg['deviceNo'] . $msg['msgNo'] . $msg['reqTime'] . $this->feyin_key;
        $msg['securityCode'] = md5($content);
        $msg['mode'] = 2;

        return $this->sendMessage($msg);
    }

    function sendFormatedMessage($msgInfo)
    {
        $msgInfo['reqTime'] = number_format(1000 * time(), 0, '', '');
        $content = $msgInfo['memberCode'] . $msgInfo['customerName'] . $msgInfo['customerPhone'] . $msgInfo['customerAddress'] . $msgInfo['customerMemo'] . $msgInfo['msgDetail'] . $msgInfo['deviceNo'] . $msgInfo['msgNo'] . $msgInfo['reqTime'] . $this->feyin_key;

        $msgInfo['securityCode'] = md5($content);
        $msgInfo['mode'] = 1;

        return $this->sendMessage($msgInfo);
    }

    function sendMessage($msgInfo)
    {
        $client = new HttpClient(FEYIN_HOST, FEYIN_PORT);
        if (!$client->post('/api/sendMsg', $msgInfo)) { //提交失败
            return 'faild';
        } else {
            return $client->getContent();
        }
    }

    function queryState($msgNo)
    {
        $now = number_format(1000 * time(), 0, '', '');
        $client = new HttpClient(FEYIN_HOST, FEYIN_PORT);
        if (!$client->get('/api/queryState?memberCode=' . $this->member_code . '&reqTime=' . $now . '&securityCode=' . md5($this->member_code . $now . $this->feyin_key . $msgNo) . '&msgNo=' . $msgNo)) { //请求失败
            return 'faild';
        } else {
            return $client->getContent();
        }
    }

    function listDevice()
    {
        $now = number_format(1000 * time(), 0, '', '');
        $client = new HttpClient(FEYIN_HOST, FEYIN_PORT);
        if (!$client->get('/api/listDevice?memberCode=' . $this->member_code . '&reqTime=' . $now . '&securityCode=' . md5($this->member_code . $now . $this->feyin_key))) { //请求失败
            return 'faild';
        } else {
            /*             * *************************************************
             * 解释返回的设备状态
             * 格式：
             * <device id="4600006007272080">
             * <address>广东**</address>
             * <since>2010-09-29</since>
             * <simCode>135600*****</simCode>
             * <lastConnected>2011-03-09  19:39:03</lastConnected>
             * <deviceStatus>离线 </deviceStatus>
             * <paperStatus></paperStatus>
             * </device>
             * ************************************************ */

            $xml = $client->getContent();
            $sxe = new SimpleXMLElement($xml);
            foreach ($sxe->device as $device) {
                $id = $device['id'];
                echo "设备编码：$id    ";

                $deviceStatus = $device->deviceStatus;
                echo "状态：$deviceStatus";
                echo '<br>';
            }
        }
    }

    function listException()
    {
        $now = number_format(1000 * time(), 0, '', '');
        $client = new HttpClient(FEYIN_HOST, FEYIN_PORT);
        if (!$client->get('/api/listException?memberCode=' . MEMBER_CODE . '&reqTime=' . $now . '&securityCode=' . md5(MEMBER_CODE . $now . $this->feyin_key))) { //请求失败
            return 'faild';
        } else {
            return $client->getContent();
        }
    }

    function feiyinstatus($code)
    {
        switch ($code) {
            case 0:
                $text = "正常";
                break;
            case -1:
                $text = "IP地址不允许";
                break;
            case -2:
                $text = "关键参数为空或请求方式不对";
                break;
            case -3:
                $text = "客户编码不对";
                break;
            case -4:
                $text = "安全校验码不正确";
                break;
            case -5:
                $text = "请求时间失效";
                break;
            case -6:
                $text = "订单内容格式不对";
                break;
            case -7:
                $text = "重复的消息";
                break;
            case -8:
                $text = "消息模式不对";
                break;
            case -9:
                $text = "服务器错误";
                break;
            case -10:
                $text = "服务器内部错误";
                break;
            case -111:
                $text = "打印终端不属于该账户";
                break;
            default:
                $text = "未知";
                break;
        }
        return $text;
    }

    function refund($id)
    {
        global $_W;
        include_once IA_ROOT . '/addons/weisrc_dish/cert/WxPay.Api.php';
        load()->model('account');
        load()->func('communication');

        $WxPayApi = new WxPayApi();
        $input = new WxPayRefund();

        $path_cert = IA_ROOT . '/addons/weisrc_dish/cert/apiclient_cert_' . $_W['uniacid'] . '.pem';
        $path_key = IA_ROOT . '/addons/weisrc_dish/cert/apiclient_key_' . $_W['uniacid'] . '.pem';
        $account_info = $_W['account'];
        $refund_order = $this->getOrderById($id);

        if ($refund_order['paytype'] == 2) {
            $paysetting = uni_setting($_W['uniacid'], array('payment'));
            $wechatpay = $paysetting['payment']['wechat'];

            $mchid = $wechatpay['mchid'];
            $key = $wechatpay['apikey'];
            $appid = $account_info['key'];

            $fee = $refund_order['totalprice'] * 100;
            $refundid = $refund_order['transid'];
            $input->SetAppid($appid);
            $input->SetMch_id($mchid);
            $input->SetOp_user_id($mchid);
            $input->SetRefund_fee($fee);
            $input->SetTotal_fee($fee);
            $input->SetTransaction_id($refundid);
            $input->SetOut_refund_no($refund_order['id']);
            $result = $WxPayApi->refund($input, 6, $path_cert, $path_key, $key);

            if ($result['return_code'] == 'SUCCESS') {
                $input2 = new WxPayOrderQuery();
                $input2->SetAppid($appid);
                $input2->SetMch_id($mchid);
                $input2->SetTransaction_id($refundid);
                $result2 = $WxPayApi->orderQuery($input2, 6, $key);
                if ($result2['return_code'] == 'SUCCESS' && $result2['trade_state'] == 'REFUND') {
                    pdo_update($this->table_order, array('ispay' => 3), array('id' => $refund_order['id']));
                    return 1;
                } else {
                    pdo_update($this->table_order, array('ispay' => 4), array('id' => $refund_order['id']));
                    return 0;
                }
            } else {
                pdo_update($this->table_order, array('ispay' => 4), array('id' => $refund_order['id']));
                print_r($result['err_code_des']);
                exit;
                return 0;
            }
        } else {
            message('非微信支付!');
        }
    }

    //预订订单是否存在
    public function doMobileExistReservationOrder()
    {
        global $_W, $_GPC;
        $storeid = intval($_GPC['storeid']);
        $tables = intval($_GPC['tables']);
        $reservation_time = trim($_GPC['meal_time']);

        $order = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE weid = :weid AND storeid=:storeid AND tables=:tables AND
meal_time=:meal_time AND dining_mode=3 AND status<>-1 AND paytype<>0 LIMIT 1", array(':weid' => $this->_weid, ':storeid' => $storeid, ':meal_time' => $reservation_time, ':tables' => $tables));
        $status = 0;
        if ($order) {
            $status = 1;
        }
        $vars['status'] = $status;
        exit(json_encode($vars));
    }

    public function sendMoney($openid, $money, $re_user_name = '', $desc, $trade_no = '')
    {
        global $_W;
        $paysetting = uni_setting($_W['uniacid'], array('payment'));
        $wechatpay = $paysetting['payment']['wechat'];
        $account_info = $_W['account'];
        $mchid = $wechatpay['mchid'];
        $key = $wechatpay['apikey'];
        $appid = $account_info['key'];

        $desc = isset($desc) ? $desc : '余额提现';
        $money = $money * 100;

        $pars = array();
        $pars['mch_appid'] = $appid;
        $pars['mchid'] = $mchid;
        $pars['nonce_str'] = random(32);
        $pars['partner_trade_no'] = empty($trade_no) ? $mchid . date('Ymd') . rand(1000000000, 9999999999) : $trade_no;
        $pars['openid'] = $openid;
        if (empty($re_user_name)) {
            $pars['check_name'] = 'NO_CHECK';
        } else {
            $pars['check_name'] = 'FORCE_CHECK';
            $pars['re_user_name'] = $re_user_name;
        }
        //NO_CHECK：不校验真实姓名
        //FORCE_CHECK：强校验真实姓名（未实名认证的用户会校验失败，无法转账）
        //OPTION_CHECK：针对已实名认证的用户才校验真实姓名（未实名认证用户不校验，可以转账成功）

        $pars['amount'] = $money;
        $pars['desc'] = $desc;
//        $pars['spbill_create_ip'] = isset($wechat['ip']) ? $wechat['ip'] : $_SERVER['SERVER_ADDR'];

        $pars['spbill_create_ip'] = gethostbyname($_SERVER["SERVER_NAME"]);
//        $pars['spbill_create_ip'] = $_SERVER['SERVER_ADDR'];

        ksort($pars, SORT_STRING);
        $string1 = '';
        foreach ($pars as $k => $v) {
            $string1 .= "{$k}={$v}&";
        }
        $string1 .= "key={$key}";
        $pars['sign'] = strtoupper(md5($string1));
        $xml = array2xml($pars);
        $extras = array();

        $path_cert = IA_ROOT . '/addons/weisrc_dish/cert/apiclient_cert_' . $_W['uniacid'] . '.pem';
        $path_key = IA_ROOT . '/addons/weisrc_dish/cert/apiclient_key_' . $_W['uniacid'] . '.pem';
        $path_rootca = IA_ROOT . '/addons/weisrc_dish/cert/rootca_' . $_W['uniacid'] . '.pem';

        $extras['CURLOPT_CAINFO'] = $path_rootca;
        $extras['CURLOPT_SSLCERT'] = $path_cert;
        $extras['CURLOPT_SSLKEY'] = $path_key;

        load()->func('communication');
        $procResult = null;
        $url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers";
        $response = ihttp_request($url, $xml, $extras);

        if ($response['code'] == 200) {
            $responseObj = simplexml_load_string($response['content'], 'SimpleXMLElement', LIBXML_NOCDATA);
            $responseObj = (array)$responseObj;
            $return['code'] = $responseObj['return_code'];
            $return['result_code'] = $responseObj['result_code'];
            $return['err_code'] = $responseObj['err_code'];
            $return['msg'] = $responseObj['return_msg'];
            $return['trade_no'] = $pars['partner_trade_no'];
            $return['payment_no'] = $responseObj['payment_no'];

            if ($responseObj['result_code'] != 'SUCCESS') {
                print_r($responseObj);
                exit;
            }
            return $return;
        } else {
            echo '证书错误:';
            print_r($response);
            exit;
        }
    }

    function sendRedPack($openid, $money, $send_name = '余额提现', $act_name = '余额提现', $wishing = '祝您生活愉快', $trade_no = '')
    {
        global $_W;
        $paysetting = uni_setting($_W['uniacid'], array('payment'));
        $wechatpay = $paysetting['payment']['wechat'];
        $account_info = $_W['account'];
        $mchid = $wechatpay['mchid'];
        $key = $wechatpay['apikey'];
        $appid = $account_info['key'];

        $money = $money * 100;
        $num = 1;
        $url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack";
        $pars = array();
        $pars['wxappid'] = $appid;
        $pars['mch_id'] = $mchid;
        $pars['nonce_str'] = random(32);
        $pars['mch_billno'] = empty($trade_no) ? $mchid . date('Ymd') . rand(1000000000, 9999999999) : $trade_no;
        $pars['send_name'] = $send_name;
        $pars['re_openid'] = $openid;
        $pars['total_amount'] = $money;
        $pars['total_num'] = $num;
        $pars['wishing'] = $wishing;
//        $pars['client_ip'] = isset($wechat['ip']) ? $wechat['ip'] : $_SERVER['SERVER_ADDR'];
        $pars['client_ip'] = $_SERVER['SERVER_ADDR'];
        $pars['act_name'] = $act_name;
        $pars['remark'] = $act_name;
        ksort($pars, SORT_STRING);
        $string1 = '';
        foreach ($pars as $k => $v) {
            $string1 .= "{$k}={$v}&";
        }
        $string1 .= "key={$key}";
        $pars['sign'] = strtoupper(md5($string1));
        $xml = array2xml($pars);
        $extras = array();

        $path_cert = IA_ROOT . '/addons/weisrc_dish/cert/apiclient_cert_' . $_W['uniacid'] . '.pem';
        $path_key = IA_ROOT . '/addons/weisrc_dish/cert/apiclient_key_' . $_W['uniacid'] . '.pem';
        $path_rootca = IA_ROOT . '/addons/weisrc_dish/cert/rootca_' . $_W['uniacid'] . '.pem';
        $extras['CURLOPT_CAINFO'] = $path_rootca;
        $extras['CURLOPT_SSLCERT'] = $path_cert;
        $extras['CURLOPT_SSLKEY'] = $path_key;

        load()->func('communication');
        $procResult = null;
        $response = ihttp_request($url, $xml, $extras);
        if ($response['code'] == 200) {
            $responseObj = simplexml_load_string($response['content'], 'SimpleXMLElement', LIBXML_NOCDATA);
            $responseObj = (array)$responseObj;
            $return['code'] = $responseObj['return_code'];
            $return['result_code'] = $responseObj['result_code'];
            $return['err_code'] = $responseObj['err_code'];
            $return['msg'] = $responseObj['return_msg'];
            $return['trade_no'] = $pars['mch_billno']; //返回订单号 用于重试
            return $return;
        }
    }

    /*
     * @param unknown $appid 支付宝appid
     * @param unknown $transid 要退款的支付宝单号
     * @param unknown $fee 退款金额
     * @param unknown $ordersn 本地商户单号 ($transid和$ordersn不可同时为空)
     * @param unknown $reason 退款理由
     * @param unknown $prikeyfile 证书绝对路径
     */

    public function alipayRefund($appid, $transid, $fee, $ordersn, $reason, $prikeyfile)
    {
        $set = array();
        $set['app_id'] = $appid;
        $set['method'] = 'alipay.trade.refund';
        $set['charset'] = 'utf-8';
        $set['sign_type'] = 'RSA';
        $set['timestamp'] = date('Y-m-d H:i:s');
        $set['version'] = '1.0';
        $content = array('trade_no' => $transid, 'refund_amount' => $fee, 'refund_reason' => $reason, 'out_request_no' => $ordersn);
        $set['biz_content'] = json_encode($content);
        $string = 'app_id=' . $set['app_id'] . '&biz_content=' . $set['biz_content'] . "&charset=" . $set['charset'] . "&method=" . $set['method'] . "&sign_type=" . $set['sign_type'] . "×tamp=" . $set['timestamp'] . "&version=" . $set['version'];

        $priKey = file_get_contents($prikeyfile);
        $res = openssl_get_privatekey($priKey);
        ($res) or die('您使用的私钥格式错误，请检查RSA私钥配置');
        openssl_sign($string, $sign, $res);
        openssl_free_key($res);
        $set['sign'] = base64_encode($sign);

        load()->func('communication');
        $response = ihttp_get('https://openapi.alipay.com/gateway.do?' . http_build_query($set, '', '&'));
        $res = json_decode($response['content'], true);
        if ($res['alipay_trade_refund_response']['code'] == '10000') {
            die('退款成功！');
        }
    }

    //打印数据
    public function doWebPrint()
    {
        global $_W, $_GPC;
        $weid = $_W['uniacid'];
        $usr = !empty($_GET['usr']) ? $_GET['usr'] : '355839026790719';
        $ord = !empty($_GET['ord']) ? $_GET['ord'] : 'no';
        $sgn = !empty($_GET['sgn']) ? $_GET['sgn'] : 'no';

        header('Content-type: text/html; charset=gbk');

        $print_type_confirmed = 0;
        $print_type_payment = 1;

        //更新打印状态
        if (isset($_GET['sta'])) {
            $id = intval($_GPC['id']); //订单id
            $sta = intval($_GPC['sta']); //状态

            pdo_update($this->table_print_order, array('print_status' => $sta), array('orderid' => $id, 'print_usr' => $usr));
            //id —— 平台下发打印数据的id号,打印机打印后回复打印是否成功带此id号。
            //usr -- 打印机终端系统的IMEI号码或SIM卡的IMSI号码
            //sta —— 打印机状态(0为打印成功, 1为过热,3为缺纸卡纸等)
            exit;
        }

        //打印机配置信息
        $setting = pdo_fetch("SELECT * FROM " . tablename($this->table_print_setting) . " WHERE print_usr = :usr AND print_status=1 AND type='hongxin'", array(':usr' => $usr));
        if ($setting == false) {
            exit;
        }

        //门店id
        $storeid = $setting['storeid'];

        $condition = "";
        if ($setting['print_type'] == $print_type_confirmed) {
            //已确认订单 //status == 1
            $condition = ' AND paytype>0 ';
        } else if ($setting['print_type'] == $print_type_payment) {
            //已付款订单 //已完成
            $condition = ' AND (ispay=1 or status=3) ';
        }

        //根据订单id读取相关订单
        $order = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE  id IN(SELECT orderid FROM "
            . tablename('weisrc_dish_print_order') . " WHERE print_status=-1 AND
print_usr=:print_usr) AND storeid = :storeid {$condition} ORDER BY id DESC limit 1", array(':storeid' => $storeid, ':print_usr' => $usr));

        //没有新订单
        if ($order == false) {
            message('no data!');
            exit;
        }

        //商品id数组
        $goodsid = pdo_fetchall("SELECT goodsid,total,price FROM " . tablename($this->table_order_goods) . " WHERE orderid = :orderid", array(':orderid' => $order['id']), 'goodsid');

        //商品
        $goods = pdo_fetchall("SELECT * FROM " . tablename($this->table_goods) . "  WHERE id IN ('" . implode("','", array_keys($goodsid)) . "')");
        $order['goods'] = $goods;

        if (!empty($setting['print_top'])) {
            $content = "%10" . $setting['print_top'] . "\n";
        } else {
            $content = '';
        }
        $ordertype = array(
            '1' => '堂点',
            '2' => '外卖',
            '3' => '预定',
            '4' => '快餐',
            '5' => '收银',
            '6' => '充值'
        );

        $storeid = $order['storeid'];
        $store = $this->getStoreById($storeid);
        $paytype = array('0' => '线下付款', '1' => '余额支付', '2' => '微信支付', '3' => $order['dining_mode'] == 1 ? '餐后付款' : '货到付款',
            '4' => '支付宝');
        $content .= '%00单号:' . $order['ordersn'] . "\n";
        $content .= '订单类型:' . $ordertype[$order['dining_mode']] . "\n";
        $content .= '所属门店:' . $store['title'] . "\n";
        $content .= '支付方式:' . $paytype[$order['paytype']] . "\n";
        $content .= '下单日期:' . date('Y-m-d H:i:s', $order['dateline']) . "\n";
        $content .= '预约时间:' . $order['meal_time'] . "\n";
        if ($order['dining_mode'] == 3) {
            $tablezones = pdo_fetch("SELECT * FROM " . tablename($this->table_tablezones) . " where id=:id LIMIT 1", array(':id' => $order['tablezonesid']));
            $content .= "桌台类型：{$tablezones['title']}\n";
        }
        if (!empty($order['tables'])) {
            $content .= '桌台信息:' . $this->getTableName($order['tables']) . "\n";
        }
        if (!empty($order['counts'])) {
            $content .= '用餐人数:' . $order['counts'] . "\n";
        }
        if (!empty($order['remark'])) {
            $content .= '%10备注:' . $order['remark'] . "\n";
        }
        if (!empty($goods)) {
            $content .= "%00\n名称              数量  单价 \n";
            $content .= "----------------------------\n%10";
        }

        $content1 = '';
        foreach ($order['goods'] as $v) {
            $money = $goodsid[$v['id']]['price'];
            $money = $money * $goodsid[$v['id']]['total'];
            $content1 .= $this->stringformat($v['title'], 16) . $this->stringformat($goodsid[$v['id']]['total'], 4, false) . $this->stringformat(number_format($money, 2), 7, false) . "\n\n";
        }

        $content2 = "----------------------------\n";
        $content2 .= "%10总数量:" . $order['totalnum'] . "   总价:" . number_format($order['totalprice'], 2) . "元\n%00";
        if (!empty($order['username'])) {
            $content2 .= '姓名:' . $order['username'] . "\n";
        }
        if (!empty($order['tel'])) {
            $content2 .= '手机:' . $order['tel'] . "\n";
        }
        if (!empty($order['address'])) {
            $content2 .= '地址:' . $order['address'] . "\n";
        }

        if (!empty($setting['qrcode_status'])) {
            $qrcode_url = trim($setting['qrcode_url']);
            if (!empty($qrcode_url)) {
                $content2 .= "%%%50372C" . $qrcode_url . "\n";
            }
        }
        //$content2 .= "%%%50372Chttp://www.weisrc.com\n";

        if (!empty($setting['print_bottom'])) {
            $content2 .= "%10" . $setting['print_bottom'] . "\n%00";
        }

        $content = iconv("UTF-8", "GB2312//IGNORE", $content);
        $content1 = iconv("UTF-8", "GB2312//IGNORE", $content1);
        $content2 = iconv("UTF-8", "GB2312//IGNORE", $content2);

        $setting = '<setting>124:' . $setting['print_nums'] . '|134:0</setting>';
        $setting = iconv("UTF-8", "GB2312//IGNORE", $setting);
        echo '<?xml version="1.0" encoding="GBK"?><r><id>' . $order['id'] . '</id><time>' . date('Y-m-d H:i:s', $order['dateline']) . '</time><content>' . $content . $content1 . $content2 . '</content>' . $setting . '</r>';
    }
}