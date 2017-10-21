<?php

defined('IN_IA') || die('Access Denied');
define('HT', '../addons/hetu_seckill/template/');
define('AUTH', '');
class Hetu_SeckillModuleSite extends WeModuleSite
{
    public $uniacid = '';
    public $psize = 20;
    public $openid = '';
    public $avatar = '';
    public $nickname = '';
    public $stage = 'hetu_seckill_stage';
    public $goods = 'hetu_seckill_goods';
    public $peis = 'hetu_seckill_peis';
    public $order = 'hetu_seckill_order';
    public $modules = 'modules';
    public $cardtype = 'hetu_halfoff_cardtype';
    public $getcard = 'hetu_halfoff_getcard';
    public function __construct()
    {
        global $_W;
        $this->uniacid = $_W['uniacid'];
        $this->openid = $_W['openid'];
    }
    public function __web($f_name)
    {
        global $_GPC;
        global $_W;
        $config = $this->module['config']['cont'];
        $this->auth();
        if ($config['wz_show']) {
            if (!$this->get_modules()) {
                message('该程序没有安装”五折卡“ 模块，请停用五折卡关联！');
            }
        }
        include_once 'core/htweb/' . strtolower(substr($f_name, 5)) . '.php';
    }
    public function __mobile($f_name)
    {
        global $_GPC;
        global $_W;
        $durl = $_W['siteroot'] . 'web/resource/';
        $this->is_weixin();
        $this->remind_user();
        $this->auto_take_goods();
        $config = $this->module['config']['cont'];
        $this->oauth_userinfo();
        $wxapi = $this->get_weixinapi();
        if (empty($this->openid)) {
            message('无法获取用户信息，请重新刷新此页面！');
        }
        $sql = ' SELECT COUNT(*) FROM ' . tablename('hetu_seckill_user') . ' WHERE uniacid=:uniacid AND openid=:openid ';
        $num = pdo_fetchcolumn($sql, array(':uniacid' => $this->uniacid, ':openid' => $this->openid));
        if (!$num) {
            $this->add_member_info();
        }
        $share_url = $_W['siteroot'] . 'app/index.php?i=' . $this->uniacid . '&c=entry&do=seckill_home&m=hetu_seckill&wxref=mp.weixin.qq.com#wechat_redirect';
        include_once 'core/htmobile/' . strtolower(substr($f_name, 8)) . '.php';
    }
    public function doMobileSeckill_home()
    {
        $this->__mobile('doMobileSeckill_home');
    }
    public function doMobileSeckill_goods()
    {
        $this->__mobile('doMobileSeckill_goods');
    }
    public function doMobileSeckill_user()
    {
        $this->__mobile('doMobileSeckill_user');
    }
    public function doWebSeckill_goods()
    {
        $this->__web('doWebSeckill_goods');
    }
    public function doWebSeckill_order()
    {
        $this->__web('doWebSeckill_order');
    }
    public function doWebSeckill_user()
    {
        $this->__web('doWebSeckill_user');
    }
    public function doWebSeckill_stage()
    {
        $this->__web('doWebSeckill_stage');
    }
    public function doWebSeckill_peis()
    {
        $this->__web('doWebSeckill_peis');
    }
    public function payResult($params)
    {
        global $_GPC;
        global $_W;
        $time = time();
        $config = $this->module['config']['cont'];
        if (empty($params['result']) || $params['result'] != 'success') {
            message('支付参数错误，请联系网站管理员！', '', 'error');
        }
        if ($params['result'] == 'success' || $params['from'] == 'notify') {
            pdo_update($this->order, array('status' => 2), array('uniacid' => $this->uniacid, 'order_no' => $params['tid']));
        }
        $order_sql = ' select * from ' . tablename($this->order) . ' where uniacid = :uniacid and order_no= :order_no and status = 2';
        $item = pdo_fetch($order_sql, array(':uniacid' => $this->uniacid, ':order_no' => $params['tid']));
        $sql = ' SELECT * FROM ' . tablename($this->goods) . ' WHERE uniacid=:uniacid AND id=:id ';
        $goods = pdo_fetch($sql, array(':uniacid' => $this->uniacid, ':id' => $item['goods_id']));
        if ('0.00' < $item['order_yunfei']) {
            $total_price = $item['order_yunfei'] + $item['order_totalprice'] . '元（含运费' . $item['order_yunfei'] . '元）';
        } else {
            $total_price = $item['order_yunfei'] + $item['order_totalprice'] . '元';
        }
        if ($item['goods_nature']) {
            $nature = '秒杀--' . $goods['name'] . ' ' . $item['goods_nature'] . '*' . $item['goods_number'] . $goods['unit'];
        } else {
            $nature = '秒杀--' . $goods['name'] . ' ' . '*' . $item['goods_number'] . $goods['unit'];
        }
        if ($params['from'] == 'return') {
            if ($params['result'] == 'success') {
                if ($config['pay_temp']) {
                    $member_list = $this->get_member_info($this->openid, 'openid');
                    $url = '';
                    $data = array('first' => array('value' => '您的商品秒杀成功！', 'color' => '#173177'), 'keyword1' => array('value' => $member_list['realname'], 'color' => '#173177'), 'keyword2' => array('value' => $item['order_no'], 'color' => '#173177'), 'keyword3' => array('value' => $total_price, 'color' => '#173177'), 'keyword4' => array('value' => $nature, 'color' => '#173177'), 'remark' => array('value' => '感谢您的购买!', 'color' => '#173177'));
                    $this->get_templatesend($config['pay_temp'], $this->openid, $url, $data);
                }
                $this->stock_info($item['goods_id']);
                if ($config['bb_show'] == 1) {
                    pdo_update($this->order, array('status' => 3, 'delivery_time' => $time), array('uniacid' => $this->uniacid, 'order_id' => $item['order_id']));
                    $this->send_temp_info($item['order_id']);
                }
                if ($goods['credit']) {
                    load()->model('mc');
                    mc_credit_update(mc_openid2uid($this->openid), 'credit1', $goods_list['credit'], '掌上秒杀赠送');
                }
                if ($this->get_modules()) {
                    if ($goods['cardtype_id']) {
                        $sql = 'SELECT *  FROM ' . tablename($this->cardtype) . ' where uniacid = :uniacid and status = 1 and cardtype_id = :cardtype_id';
                        $cardtype_list = pdo_fetch($sql, array(':uniacid' => $this->uniacid, ':cardtype_id' => $goods['cardtype_id']));
                        if ($cardtype_list) {
                            $csql = 'select * from ' . tablename($this->getcard) . ' where uniacid = :uniacid and openid = :openid ';
                            $card_item = pdo_fetch($csql, array(':uniacid' => $this->uniacid, ':openid' => $this->openid));
                            load()->model('mc');
                            $user_id = $this->findUserId($this->openid);
                            $payno = $time . $user_id;
                            $jyime = 60 * 60 * 24 * $cardtype_list['days'];
                            $data = array('payno' => $payno, 'uniacid' => $this->uniacid, 'openid' => $this->openid, 'cardtype_id' => $cardtype_list['cardtype_id'], 'start_time' => $time, 'end_time' => $time + $jyime, 'openid' => $this->openid, 'code' => $this->randomkeys(9, 'wuzhek'), 'status' => 1, 'gettype' => 1, 'agentid' => 0);
                            if ($card_item['card_no']) {
                                $data['card_no'] = $card_item['card_no'];
                                $hh = '续费';
                            } else {
                                $data['card_no'] = $this->get_cardno(9);
                                $hh = '开通';
                            }
                            if (pdo_insert($this->getcard, $data)) {
                                if ($config['hoaff_temp']) {
                                    $url = '';
                                    $data = array('first' => array('value' => '您好，您的' . $cardtype_list['type'] . '已被' . $hh . '!', 'color' => '#173177'), 'keyword1' => array('value' => $data['card_no'], 'color' => '#173177'), 'keyword2' => array('value' => date('Y-m-d H:i:s', $time), 'color' => '#173177'), 'remark' => array('value' => '如有疑问请联系客服!', 'color' => '#173177'));
                                    $this->get_templatesend($config['hoaff_temp'], $this->openid, $url, $data);
                                    pdo_update($this->order, array('status' => 4, 'delivery_time' => $time), array('uniacid' => $this->uniacid, 'order_id' => $item['order_id']));
                                }
                            }
                        }
                    }
                }
                message('支付成功！', $this->createMobileUrl('seckill_home'), 'success');
                return;
            }
            message('支付失败！', referer(), 'error');
        }
    }
    public function oauth_userinfo()
    {
        global $_W;
        load()->model('mc');
        $fansInfo = mc_oauth_userinfo($_W['acid']);
        $this->openid = $fansInfo['openid'];
        $this->avatar = $fansInfo['headimgurl'];
        $this->nickname = $fansInfo['nickname'];
        $this->sex = $fansInfo['sex'];
    }
    public function is_weixin()
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'MicroMessenger') === false) {
            message('非法访问，请通过微信打开！');
            return;
        }
        return true;
    }
    public function getAccessToken()
    {
        global $_W;
        load()->classs('weixin.account');
        $accObj = WeixinAccount::create($_W['acid']);
        $access_token = $accObj->fetch_token();
        return $access_token;
    }
    public function get_templatesend($template_id, $openid, $url, $data)
    {
        global $_W;
        if (!$url) {
            $url = $data;
        }
        $arr = explode(',', $openid);
        foreach ($arr as $k => $val) {
            $tjsrt = array('template_id' => $template_id, 'touser' => $val, 'url' => $url, 'topcolor' => '#FF0000');
            $tjsrt['data'] = $data;
            $jsonData = json_encode($tjsrt);
            load()->func('communication');
            $acessToken = $this->getAccessToken();
            $apiUrl = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $acessToken;
            $result = ihttp_request($apiUrl, $jsonData);
        }
    }
    public function get_goods_name($goods_id)
    {
        $sql = ' SELECT name FROM ' . tablename('hetu_seckill_goods') . ' WHERE uniacid=:uniacid AND id=:id ';
        return pdo_fetchcolumn($sql, array(':uniacid' => $this->uniacid, ':id' => $goods_id));
    }
    public function get_peis_name($peis_id)
    {
        $sql = ' SELECT name FROM ' . tablename('hetu_seckill_peis') . ' WHERE uniacid=:uniacid AND id=:id ';
        return pdo_fetchcolumn($sql, array(':uniacid' => $this->uniacid, ':id' => $peis_id));
    }
    public function get_member_info($key, $type)
    {
        if ($type == 'openid') {
            $where = ' AND openid=\'' . $key . '\' ';
        } else {
            $where = ' AND id=' . $key . ' ';
        }
        $sql = ' SELECT * FROM ' . tablename('hetu_seckill_user') . ' WHERE uniacid=:uniacid ' . $where;
        $member_info = pdo_fetch($sql, array(':uniacid' => $this->uniacid));
        return $member_info;
    }
    public function mod_member_info($id, $data)
    {
        $result = pdo_update('hetu_seckill_user', $data, array('uniacid' => $this->uniacid, 'id' => $id));
        if ($result === false) {
            return false;
        }
        return true;
    }
    public function add_member_info($address = '', $realname = '', $phone = '', $status = 1)
    {
        $data['uniacid'] = $this->uniacid;
        $data['openid'] = $this->openid;
        $data['avatar'] = $this->avatar;
        $data['nickname'] = $this->nickname;
        $data['sex'] = $this->sex;
        $data['status'] = $status;
        if (!empty($phone)) {
            $data['phone'] = $phone;
        } else {
            $sql = ' SELECT user.mobile FROM ' . tablename('mc_members') . ' user LEFT JOIN ' . tablename('mc_mapping_fans') . ' fans ON user.uid=fans.uid WHERE fans.openid=:openid ';
            $mobile = pdo_fetchcolumn($sql, array(':openid' => $this->openid));
            if (!empty($mobile)) {
                $data['phone'] = $mobile;
            }
        }
        if (!empty($address)) {
            $data['address'] = $address;
        }
        if (!empty($realname)) {
            $data['realname'] = $realname;
        }
        $res = pdo_insert('hetu_seckill_user', $data);
        if ($res) {
            return true;
        }
        return false;
    }
    public function get_order_num($id)
    {
        $time = date('Y-m-d H:i:s', time());
        $year = (int) substr($time, 0, 4);
        $month = (int) substr($time, 5, 2);
        $day = (int) substr($time, 8, 2);
        $day = sprintf('%02d', $day);
        $hour = (int) substr($time, 11, 2);
        $minute = (int) substr($time, 14, 2);
        $second = (int) substr($time, 17, 2);
        $goods_id = sprintf('%04d', $id);
        $sql = ' SELECT id FROM ' . tablename('hetu_seckill_user') . ' WHERE uniacid=:uniacid AND openid=:openid ';
        $user_id = pdo_fetchcolumn($sql, array(':uniacid' => $this->uniacid, ':openid' => $this->openid));
        $user_id = sprintf('%04d', $user_id);
        return $year . $month . $day . $hour . $minute . $second . $goods_id . $user_id;
    }
    public function form_field_district($name, $values = array())
    {
        $html = '';
        if (!defined('TPL_INIT_DISTRICT')) {
            $html .= '
' . '                     <script type="text/javascript">' . '
' . '                     require(["jquery", "district"], function($, dis){' . '
' . '                     $(".tpl-district-container").each(function(){' . '
' . '                     var elms = {};' . '
' . '                     elms.province = $(this).find(".tpl-province")[0];' . '
' . '                     elms.city = $(this).find(".tpl-city")[0];' . '
' . '                     elms.district = $(this).find(".tpl-district")[0];' . '
' . '                     var vals = {};' . '
' . '                     vals.province = $(elms.province).attr("data-value");' . '
' . '                     vals.city = $(elms.city).attr("data-value");' . '
' . '                     vals.district = $(elms.district).attr("data-value");' . '
' . '                     dis.render(elms, vals, {withTitle: true});' . '
' . '                 });' . '
' . '                 });' . '
' . '                     </script>';
            define('TPL_INIT_DISTRICT', true);
        }
        if (empty($values) || !is_array($values)) {
            $values = array('province' => '', 'city' => '', 'district' => '');
        }
        if (empty($values['province'])) {
            $values['province'] = '';
        }
        if (empty($values['city'])) {
            $values['city'] = '';
        }
        if (empty($values['district'])) {
            $values['district'] = '';
        }
        $html .= '
' . '                 <div class="row row-fix tpl-district-container">' . '
' . '                 <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">' . '
' . '                 <select name="' . $name . '[province]" data-value="' . $values['province'] . '" class="form-control tpl-province" style="width:70px;height=30px">' . '
' . '                 </select>' . '
' . '                 </div>' . '
' . '                 <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">' . '
' . '                 <select name="' . $name . '[city]" data-value="' . $values['city'] . '" class="form-control tpl-city" style="width:70px;height=30px">' . '
' . '                 </select>' . '
' . '                 </div>' . '
' . '                 <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">' . '
' . '                 <select name="' . $name . '[district]" data-value="' . $values['district'] . '" class="form-control tpl-district" style="width:70px;height=30px">' . '
' . '                 </select>' . '
' . '                 </div>' . '
' . '                 </div>';
        return $html;
    }
    public function stock_info($goods)
    {
        global $_W;
        global $_GPC;
        $config = $this->module['config']['cont'];
        if ($config['low_stocks']) {
            if ($config['admin_openid']) {
                $num_warning = $config['num_warning'];
                $sql = ' SELECT COUNT(*) FROM ' . tablename('hetu_seckill_order') . ' WHERE uniacid=:uniacid AND goods_id=:goods_id ';
                $order_num = pdo_fetchcolumn($sql, array(':uniacid' => $this->uniacid, ':goods_id' => $goods));
                $sql = ' SELECT total,unit,sn FROM ' . tablename('hetu_seckill_goods') . ' WHERE uniacid=:uniacid AND id=:id ';
                $goods_info = pdo_fetch($sql, array(':uniacid' => $this->uniacid, ':id' => $goods));
                $remain_num = $goods_info['total'] - $order_num;
                if ($remain_num <= $num_warning) {
                    $openid_arr = explode(',', $config['admin_openid']);
                    $url = '';
                    $data = array('first' => array('value' => '管理员您好，商品【' . $this->get_goods_name($goods) . '】库存量低于' . $remain_num . $goods_info['unit'] . '，请及时补充库存！', 'color' => '#173177'), 'keyword1' => array('value' => $goods_info['sn'], 'color' => '#173177'), 'keyword2' => array('value' => $this->get_goods_name($goods), 'color' => '#173177'), 'keyword3' => array('value' => $remain_num . $goods_info['unit'], 'color' => '#173177'), 'remark' => array('value' => '如有疑问，请及时联系值班人员!', 'color' => '#173177'));
                    foreach ($openid_arr as $k => $v) {
                        if ($this->get_templatesend($config['low_stocks'], $v, $url, $data)) {
                            return false;
                        }
                    }
                    return;
                }
                return true;
            }
            return true;
        }
        return true;
    }
    public function send_temp_info($order_id)
    {
        global $_W;
        $config = $this->module['config']['cont'];
        $order_sql = ' select * from ' . tablename($this->order) . ' where uniacid = :uniacid and order_id= :order_id and status = 3';
        $item = pdo_fetch($order_sql, array(':uniacid' => $this->uniacid, ':order_id' => $order_id));
        if ($item) {
            $sql = ' SELECT * FROM ' . tablename('hetu_seckill_goods') . ' WHERE uniacid=:uniacid AND id=:id ';
            $goods = pdo_fetch($sql, array(':uniacid' => $this->uniacid, ':id' => $item['goods_id']));
            $peis_type_sql = ' select  name,peis_type  from ' . tablename($this->peis) . ' where uniacid = :uniacid and id = :id';
            $peis = pdo_fetch($peis_type_sql, array(':uniacid' => $this->uniacid, 'id' => $item['peis']));
            if ($item['goods_nature']) {
                $nature = '秒杀--' . $goods['name'] . ' ' . $item['goods_nature'] . '*' . $item['goods_number'] . $goods['unit'];
            } else {
                $nature = '秒杀--' . $goods['name'] . ' ' . '*' . $item['goods_number'] . $goods['unit'];
            }
            if ($config['bb_show'] == 1) {
                $address = '自提 地址：' . $goods['since_address'];
                $total_pice = $item['order_totalprice'];
            } else {
                $address = '快递：' . $peis['name'] . '  快递单号：' . $item['kd_no'] . ' ' . $item['address'];
                $total_pice = $item['order_totalprice'] + $item['order_yunfei'] . '（含运费）';
            }
            $data = array('first' => array('value' => '您的订单已经标记发货，请留意查收。', 'color' => '#173177'), 'orderProductPrice' => array('value' => $total_pice, 'color' => '#173177'), 'orderProductName' => array('value' => $nature, 'color' => '#173177'), 'orderAddress' => array('value' => $address, 'color' => '#173177'), 'orderName' => array('value' => $item['order_no'], 'color' => '#173177'), 'remark' => array('value' => '如有疑问，请及时联系客服!', 'color' => '#173177'));
            if ($config['send_temp']) {
                $member_info = $this->get_member_info($item['member'], 'id');
                $this->get_templatesend($config['send_temp'], $member_info['openid'], $url, $data);
                return;
            }
        } else {
            return false;
        }
    }
    public function get_available_num($goods, $stage_id, $type = 1)
    {
        global $_W;
        $sql = ' SELECT * FROM ' . tablename('hetu_seckill_goods') . ' WHERE uniacid=:uniacid AND id=:id ';
        $goods_list = pdo_fetch($sql, array(':uniacid' => $this->uniacid, ':id' => $goods));
        if ($type == 1) {
            if (!$goods_list['totalcnf']) {
                $where = ' AND status in (1, 2, 3,4) ';
            } else {
                $where = ' AND status in (2, 3,4) ';
            }
        }
        $sql = ' SELECT sum(goods_number)  FROM ' . tablename('hetu_seckill_order') . ' WHERE uniacid=:uniacid AND goods_id=:goods_id and stage_id = :stage_id ' . $where;
        $goods_number = intval(pdo_fetchcolumn($sql, array(':uniacid' => $this->uniacid, ':goods_id' => $goods, ':stage_id' => $stage_id)));
        return $goods_list['total'] - $goods_number - $goods_list['sales'];
    }
    public function get_user_goods($goods_id, $stage_id)
    {
        global $_W;
        $member_list = $this->get_member_info($this->openid, 'openid');
        $count_order = pdo_fetchcolumn('SELECT count(*) FROM ' . tablename($this->order) . ' WHERE uniacid=:uniacid AND goods_id=:goods_id and  member = :member and stage_id=:stage_id', array(':uniacid' => $this->uniacid, ':goods_id' => $goods_id, ':member' => $member_list['id'], ':stage_id' => $stage_id));
        $usermaxbuy = pdo_fetchcolumn('SELECT usermaxbuy FROM ' . tablename($this->goods) . ' WHERE uniacid=:uniacid AND id=:id ', array(':uniacid' => $this->uniacid, ':id' => $goods_id));
        if ($usermaxbuy) {
            if ($usermaxbuy <= $count_order) {
                return false;
            }
            return true;
        }
        return true;
    }
    public function get_modules()
    {
        global $_W;
        $sql = 'SELECT COUNT(*) FROM ' . tablename($this->modules) . ' WHERE name =\'hetu_halfoff\'';
        return $count = pdo_fetchcolumn($sql, array(':name' => 'hetu_halfoff'));
    }
    public function get_cardnotype($cardtype_id)
    {
        $sql = 'SELECT no_type from ' . tablename($this->cardtype) . ' where uniacid=:uniacid and cardtype_id=:cardtype_id ';
        return $no_type = pdo_fetchcolumn($sql, array(':uniacid' => $this->uniacid, ':cardtype_id' => $cardtype_id));
    }
    public function randomkeys($length, $type = 'number', $zimu_num = 0)
    {
        $pattern = '1234567890abcdefghijklmnopqrstuvwxyz';
        $temp = $length - $zimu_num;
        $i = 0;
        while ($i < $length) {
            if ($type == 'number') {
                $key .= $pattern[mt_rand(0, 9)];
            } else {
                if ($zimu_num == 0) {
                    $key .= $pattern[mt_rand(0, 35)];
                } else {
                    if ($i < $temp) {
                        $key .= $pattern[mt_rand(0, 9)];
                    }
                }
            }
            ++$i;
        }
        $j = 0;
        while ($j < $zimu_num) {
            $temp_key = $pattern[mt_rand(10, 35)];
            $key = rand_in_str($key, $temp_key);
            ++$j;
        }
        return $key;
    }
    public function get_cardno($num)
    {
        $sql = ' SELECT max(card_id) FROM ' . tablename($this->getcard) . ' WHERE uniacid=:uniacid ';
        $maxid = pdo_fetchcolumn($sql, array(':uniacid' => $this->uniacid));
        $reg = '%0' . $num . 'd';
        $number = sprintf($reg, $maxid + 1);
        return $number;
    }
    public function get_weixinapi()
    {
        global $_W;
        return $_W['account']['jssdkconfig'];
    }
    public function remind_user()
    {
        $sql = ' SELECT * FROM ' . tablename('hetu_seckill_remind') . ' WHERE uniacid=:uniacid AND status=0 ';
        $list = pdo_fetchall($sql, array(':uniacid' => $this->uniacid));
        foreach ($list as $k => $v) {
            $timestart = $this->get_miaosha_starttime($v['stageid']);
            if (time() <= $v['createtime']) {
                pdo_update('hetu_seckill_remind', array('status' => 1), array('id' => $v['id']));
            } else {
                $config = $this->module['config']['cont'];
                $remind_time = $config['remind_time'];
                $remind_temp = $config['remind_temp'];
                if (intval(strtotime($timestart) - time()) <= intval($remind_time) * 60) {
                    if ($remind_temp) {
                        if ($remain_num <= $num_warning) {
                            $url = '';
                            $data = array('first' => array('value' => '您好，您预约秒杀的商品【' . $this->get_goods_name($v['goodsid']) . '】即将开始秒杀，请做好准备！', 'color' => '#173177'), 'keyword1' => array('value' => '秒杀活动', 'color' => '#173177'), 'keyword2' => array('value' => $timestart, 'color' => '#173177'), 'remark' => array('value' => '请提前安排时间!', 'color' => '#173177'));
                            $sql = ' SELECT openid FROM ' . tablename('hetu_seckill_user') . ' WHERE uniacid=:uniacid AND id=:id ';
                            $openid = pdo_fetchcolumn($sql, array(':uniacid' => $this->uniacid, ':id' => $v['userid']));
                            $this->get_templatesend($remind_temp, $openid, $url, $data);
                            pdo_update('hetu_seckill_remind', array('status' => 1), array('id' => $v['id']));
                        }
                    }
                }
            }
        }
    }
    public function auth()
    {
        global $_W;
    }
    public function is_ziti($id)
    {
        $sql = ' SELECT since_address FROM ' . tablename('hetu_seckill_goods') . ' WHERE uniacid=:uniacid AND id=:id ';
        $add = pdo_fetchcolumn($sql, array(':uniacid' => $this->uniacid, ':id' => $id));
        if (empty($add)) {
            return false;
        }
        return true;
    }
    public function get_goods_unit($id)
    {
        $sql = ' SELECT unit FROM ' . tablename('hetu_seckill_goods') . ' WHERE uniacid=:uniacid AND id=:id ';
        return pdo_fetchcolumn($sql, array(':uniacid' => $this->uniacid, ':id' => $id));
    }
    public function get_miaosha_starttime($id)
    {
        $sql = ' SELECT datetime,timestart FROM ' . tablename('hetu_seckill_stage') . ' WHERE uniacid=:uniacid AND id=:id ';
        $stage_info = pdo_fetch($sql, array(':uniacid' => $this->uniacid, ':id' => $id));
        return date('Y-m-d', $stage_info['datetime']) . ' ' . $stage_info['timestart'];
    }
    public function auto_take_goods()
    {
        $cfg = $this->module['config']['cont'];
        $sql = ' SELECT * FROM ' . tablename('hetu_seckill_order') . ' WHERE uniacid=:uniacid AND status=3 ';
        $list = pdo_fetchall($sql, array(':uniacid' => $this->uniacid));
        foreach ($list as $k => $v) {
            if (intval($cfg['auto_take'] * 24 * 60 * 60) < intval(time() - $v['delivery_time'])) {
                pdo_update('hetu_seckill_order', array('status' => 4), array('uniacid' => $this->uniacid, 'order_id' => $v['order_id']));
            }
        }
    }
    public function exportexcel($data = array(), $title = array(), $filename = 'report')
    {
        header('Content-type:application/octet-stream');
        header('Accept-Ranges:bytes');
        header('Content-type:application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename=' . $filename . '.xls');
        header('Pragma: no-cache');
        header('Expires: 0');
        if (!empty($title)) {
            foreach ($title as $k => $v) {
                $title[$k] = iconv('UTF-8', 'GB2312', $v);
            }
            $title = implode('	', $title);
            echo $title . '
';
        }
        if (!empty($data)) {
            foreach ($data as $key => $val) {
                foreach ($val as $ck => $cv) {
                    $data[$key][$ck] = iconv('UTF-8', 'GB2312', $cv);
                }
                $data[$key] = implode('	', $data[$key]);
            }
            echo implode('
', $data);
        }
    }
}