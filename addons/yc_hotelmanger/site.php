<?php

defined('IN_IA') or exit('Access Denied');
define("YC_TMP", "../addons/yc_hotelmanger/template/style/");

class Yc_hotelmangerModuleSite extends WeModuleSite {

    public $temp_url = 'hotel';
    public $hotel = 'yc_hotel';
    public $room = 'yc_hotel_room';
    public $momy = 'yc_roommoney';
    public $order = 'yc_room_order';
    public $shop = 'yc_shop_goods';
    public $shoporder = 'yc_shop_order';
    public $setting = 'yc_hotel_setting';
    public $acmcou = 'activity_coupon_modules';
    public $coupon = 'activity_coupon';
    public $record = 'activity_coupon_record';
    public $members = 'mc_members';
    public $mlevel = 'yc_hotel_member_level';
    public $credits_record = 'mc_credits_record';
    public $adv = 'yc_adv';
    public $hotelmanager = 'yc_hotel_manager';
    public $hotelComment = 'yc_hotel_comment';
    public $ycadmin = 'yc_admin';
    public $hotel_level = array(5 => '五星级酒店', 4 => '四星级酒店', 3 => '三星级酒店', 2 => '两星级以下', 15 => '豪华酒店', 14 => '高档酒店', 13 => '舒适酒店', 12 => '经济型酒店');
    public $devices = array(
        array('isdel' => 0, 'value' => '有线上网'),
        array('isdel' => 0, 'isshow' => 0, 'value' => 'WIFI无线上网'),
        array('isdel' => 0, 'isshow' => 0, 'value' => '可提供早餐'),
        array('isdel' => 0, 'isshow' => 0, 'value' => '免费停车场'),
        array('isdel' => 0, 'isshow' => 0, 'value' => '会议室'),
        array('isdel' => 0, 'isshow' => 0, 'value' => '健身房'),
        array('isdel' => 0, 'isshow' => 0, 'value' => '游泳池')
    );
    public $hotelroom = array(
        array('isdel' => 0, 'value' => '房间面积', 'danwei' => '平方米'),
        array('isdel' => 0, 'isshow' => 0, 'value' => '楼层', 'danwei' => '平方米'),
        array('isdel' => 0, 'isshow' => 0, 'value' => '床位', 'danwei' => '2米大床'),
        array('isdel' => 0, 'isshow' => 0, 'value' => '是否加床', 'danwei' => '加床说明'),
        array('isdel' => 0, 'isshow' => 0, 'value' => '无烟', 'danwei' => '无烟说明'),
        array('isdel' => 0, 'isshow' => 0, 'value' => '宽带', 'danwei' => '无线（有线）'),
        array('isdel' => 0, 'isshow' => 0, 'value' => '其他', 'danwei' => '其他信息')
    );
    public $_weid = '';
    public $psize = 15;
    public $openid = '';
    public $menu='';
    public function __construct() {
        global $_W; 
        $this->_weid = $_W['uniacid'];
        $this->openid = $_W['openid']; 
        $this->seearr = pdo_fetch('SELECT * FROM ' . tablename($this->setting) . ' WHERE uniacid = ' . $this->_weid);
        $menu=pdo_fetchall('SELECT * FROM ' . tablename('modules_bindings') . ' where entry = :menu and  module = :module order by eid ',array(':menu'=>'menu',':module'=>'yc_hotelmanger'));
        $this->menu=$menu;
    } 
     

    public function payResult($params) {
        global $_GPC;
        global $_W;
        if (!$this->is_weixin()) {
            message('请在微信中打开');
        }
        $seearr = $this->seearr;
        $shopin=FALSE;
        $list = pdo_fetch('SELECT * FROM ' . tablename($this->order) . 'WHERE  uniacid =' . $this->_weid . ' and openid =\'' . $this->openid . '\' and order_on =' . $params['tid']);        
        if (($params['result'] == 'success') || ($params['from'] == 'notify')) {        
            if($list){
                $data['order_status'] = 1;
                $data['mode'] = $params['type'];
                pdo_update($this->order, $data, array('openid' => $this->openid, 'uniacid' => $this->_weid, 'order_on' => $params['tid']));
            }else{                
                $shopin = TRUE; 
                $data['order_status'] = 2;
                $data['pay_time']=time();
                pdo_update($this->shoporder, $data, array('openid' => $this->openid, 'uniacid' => $this->_weid, 'ordersn' => $params['tid']));
            }
            
        } 
//        if (empty($params['result']) || ($params['result'] != 'success')) {
//            message('支付参数错误，请联系网站管理员！', '', 'error');
//        }
        if ($params['from'] == 'return') { 
            if ($params['result'] == 'success') { 
                if($shopin){ 
                    $data['order_status'] = 2;
                    $data['pay_time']=time();
                    pdo_update($this->shoporder, $data, array('openid' => $this->openid, 'uniacid' => $this->_weid, 'ordersn' => $params['tid']));
                    message("支付成功！", $this->createMobileUrl('shoporder', array('op' => 'list')), 'success');
                    return;
                } 
                if ($seearr['istplnotice']) {
                    $this->get_sendorderok($seearr, $params['tid']);
                    $this->get_hotelordergl($seearr, $params['tid']);
                } 
                //更新积分
                message("支付成功！", $this->createMobileUrl('orderinfo', array('order_id' => $list['order_id'])), 'success');
                return;
            } 
             if ($params['type'] == 'delivery') {  
                if(!$list){                     
                    message("酒店商品基本都是一次性用品，不支持货到付款，请谅解！", $this->createMobileUrl('shoporder', array('op' => 'list')), 'error');
                    return;
                }else{                    
                       $data['order_status'] = 2; 
                       $data['mode'] = $params['type'];
                       pdo_update($this->order, $data, array('openid' => $this->openid, 'uniacid' => $this->_weid, 'order_on' => $params['tid']));
                      if ($seearr['istplnotice']) {
                            $this->get_sendorderok($seearr, $params['tid']);
                            $this->get_hotelordergl($seearr, $params['tid']);
                        } 
                        //更新积分
                        message("预订成功，请提前到店确认！", $this->createMobileUrl('orderinfo', array('order_id' => $list['order_id'])), 'success');
                        return;
                }
              
            }
            
            message("支付失败！", $this->createMobileUrl('ordeeList'), 'error');
        }
    }
 

    public function get_settingMall() {
        $setting = pdo_fetch('SELECT is_mihua_mall FROM ' . tablename($this->setting) . ' WHERE uniacid = ' . $this->_weid);
        return $setting['is_mihua_mall'];
    }
    public function get_hotelname($id) {
        $where['uniacid'] = $this->_weid;
        $where['id'] = $id;
        $hotel_list = pdo_fetch('SELECT * FROM ' . tablename($this->hotel) . 'WHERE uniacid =' . $this->_weid . ' and id=' . $id);
        return $hotel_list['title'];
    }
    public function get_hotelAddress($id) {
        $where['uniacid'] = $this->_weid;
        $where['id'] = $id;
        $hotel_list = pdo_fetch('SELECT address FROM ' . tablename($this->hotel) . 'WHERE uniacid =' . $this->_weid . ' and id=' . $id);
        return $hotel_list['address'];
    }
      public function get_hotelPhone($id) {
        $where['uniacid'] = $this->_weid;
        $where['id'] = $id;
        $hotel_list = pdo_fetch('SELECT phone FROM ' . tablename($this->hotel) . 'WHERE uniacid =' . $this->_weid . ' and id=' . $id);
        return $hotel_list['phone'];
    }
    public function get_hotelLat($id) {
        $where['uniacid'] = $this->_weid;
        $where['id'] = $id;
        $hotel_list = pdo_fetch('SELECT lat FROM ' . tablename($this->hotel) . 'WHERE uniacid =' . $this->_weid . ' and id=' . $id);
        return $hotel_list['lat'];
    }
    public function get_hotelLng($id) {
        $where['uniacid'] = $this->_weid;
        $where['id'] = $id;
        $hotel_list = pdo_fetch('SELECT lng FROM ' . tablename($this->hotel) . 'WHERE uniacid =' . $this->_weid . ' and id=' . $id);
        return $hotel_list['lng'];
    }
     public function get_hotelThumb($id) {
        $where['uniacid'] = $this->_weid;
        $where['id'] = $id;
        $hotel_list = pdo_fetch('SELECT thumb FROM ' . tablename($this->hotel) . 'WHERE uniacid =' . $this->_weid . ' and id=' . $id);
        return $hotel_list['thumb'];
    }

    public function get_roomtitle($id) {
        $where['uniacid'] = $this->_weid;
        $where['id'] = $id;
        $room_list = pdo_fetch('SELECT * FROM ' . tablename($this->room) . 'WHERE uniacid =' . $this->_weid . ' and id=' . $id);
        return $room_list['title'];
    }

    public function get_momytitle($id) {
        $where['uniacid'] = $this->_weid;
        $where['id'] = $id;
        $momy_list = pdo_fetch('SELECT * FROM ' . tablename($this->momy) . 'WHERE uniacid =' . $this->_weid . ' and id=' . $id);
        return $momy_list['motitle'];
    }

    public function build_order_no() {
        return date("Ymd") . substr(implode(NULL, array_map("ord", str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }

    public function getAccessToken() {
        global $_W;
        load()->classs("weixin.account");
        $accObj = WeixinAccount::create($_W['acid']);
        $access_token = $accObj->fetch_token();
        return $access_token;
    }

    public function sendTengmub($data) {
        $jsonData = json_encode($data);
        load()->func("communication");
        $acessToken = $this->getAccessToken();
        $apiUrl = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $acessToken;
        $result = ihttp_request($apiUrl, $jsonData);
    }

    public function get_sendorderok($seearr, $order_on) {
        global $_GPC;
        global $_W;
        $sql = 'SELECT o.*,h.title,h.phone as hphone,h.address FROM ' . tablename($this->order) . ' o left join ' . tablename($this->hotel) . ' h  on o.hotelid = h.id where o.order_on = ' . $order_on;
        $order = pdo_fetch($sql);
        $url = $_W['siteroot'] . 'app/index.php?i=' . $this->_weid . '&c=entry&&order_id=' . $order['order_id'] . '&do=orderinfo&m=yc_hotelmanger';
        $tjsrt = array('template_id' => $seearr['jdyd'], 'touser' => $order['openid'], 'url' => $url, 'topcolor' => '#FF0000');
        $data = array(
            'first' => array('value' => $order['order_name'] . '先生/女士，你的订房订单已确认。', 'color' => '#DC143C'),
           'keyword1' => array('value' => $order['ordertime'], 'color' => '#173177'),
            'keyword2' => array('value' => $order['goods_name'] . $order['yu_legth'] . '间,时间'.date('Y-m-d', $order['sintdate']) . '-' . date('Y-m-d', $order['soutdate']), 'color' => '#173177'),   
            'remark' => array('value' => "\n". '订单信息：金额：￥' . $order['totalcpice'] . '元' . "\n" .'订单号：'.$order['order_on'] . "\n" .'期待您的光临！如有疑问请致电' . $order['title'] . '客服电话：' . $order['hphone'] . '！', 'color' => '#228B22')
        );
        $tjsrt['data'] = $data;
        $jsonData = json_encode($tjsrt);
        load()->func("communication");
        $acessToken = $this->getAccessToken();
        $apiUrl = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $acessToken;
        $result = ihttp_request($apiUrl, $jsonData);
    }

    public function get_hotelordergl($seearr, $order_on) {
        $tpluserarr = explode(',', $seearr['tpluser']);
        $sql = 'SELECT o.*,h.title,h.phone as hphone,h.address FROM ' . tablename($this->order) . ' o left join ' . tablename($this->hotel) . ' h  on o.hotelid = h.id where o.order_on = ' . $order_on;
        $order = pdo_fetch($sql);

        if ($order['mode'] == 'wechat') {
            $type = '微信已支付';
        } else if ($order['mode'] == 'credit') {
            $type = '余额支付';
        }


        foreach ($tpluserarr as $val) {
            $tjsrt = array('template_id' => $seearr['xindan'], 'touser' => $val, 'url' => '', 'topcolor' => '#228B22');
            $data = array(
                'first' => array('value' => $order['title'] . '产生新订单啦！', 'color' => '#173177'),
                'keyword1' => array('value' => $order['ordertime'], 'color' => '#173177'),
                'keyword2' => array('value' => $order['goods_name'] . $order['yu_legth'] . '间,时间'.date('Y-m-d', $order['sintdate']) . '-' . date('Y-m-d', $order['soutdate']), 'color' => '#173177'),                  
                'remark' => array('value' => '订单信息：' . "\n" .'客户姓名：'.$order['order_name'].  "\n" .'手机号：'.$order['phone'] . "\n" .'金额：￥' . $order['totalcpice'] . '元' . "\n" .'订单号：'.$order['order_on'] . "\n" .'支付方式：' . $type  . "\n" . '请做好服务工作!', 'color' => '#DC143C')
            );
            $tjsrt['data'] = $data;
            $jsonData = json_encode($tjsrt);
            load()->func("communication");
            $acessToken = $this->getAccessToken();
            $apiUrl = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $acessToken;
            $result = ihttp_request($apiUrl, $jsonData);
        }
    }

    public function get_hotelroomtui($seearr, $order_id, $type = 1) {
        $tpluserarr = explode(',', $seearr['tpluser']);
        $order = pdo_fetch('SELECT o.*,h.title,h.phone as hphone,h.address FROM ' . tablename($this->order) . ' o left join ' . tablename($this->hotel) . ' h  on o.hotelid = h.id where o.order_id = ' . $order_id);

        foreach ($tpluserarr as $val) {
            $tjsrt = array('template_id' => $seearr['gtuifang'], 'touser' => $val, 'url' => '', 'topcolor' => '#FF0000');

            if ($type == 1) {
                $data = array(
                    'first' => array('value' => '客人因行程改变已退订订单。', 'color' => '#173177'),
                    'keyword1' => array('value' => $order['order_on'], 'color' => '#173177'),
                    'keyword2' => array('value' => $order['title'], 'color' => '#173177'),
                    'keyword3' => array('value' => $order['goods_name'] . $order['yu_legth'] . '间。', 'color' => '#173177'),
                    'keyword4' => array('value' => date('Y-m-d', $order['sintdate']), 'color' => '#173177'),
                    'keyword5' => array('value' => date('Y-m-d', $order['soutdate']), 'color' => '#173177'),
                    'remark' => array('value' => '订单已退订 请取消预订登记，不用保留客房。', 'color' => '#173177')
                );
            } else {
                $data = array(
                    'first' => array('value' => '客人因行程改变已申请订单退款。', 'color' => '#173177'),
                    'keyword1' => array('value' => $order['order_on'], 'color' => '#173177'),
                    'keyword2' => array('value' => $order['title'], 'color' => '#173177'),
                    'keyword3' => array('value' => $order['goods_name'] . $order['yu_legth'] . '间。', 'color' => '#173177'),
                    'keyword4' => array('value' => date('Y-m-d', $order['sintdate']), 'color' => '#173177'),
                    'keyword5' => array('value' => date('Y-m-d', $order['soutdate']), 'color' => '#173177'),
                    'remark' => array('value' => '订单已申请退款 请取消预订登记，后台确认退款。', 'color' => '#173177')
                );
            }

            $tjsrt['data'] = $data;
            $jsonData = json_encode($tjsrt);
            load()->func("communication");
            $acessToken = $this->getAccessToken();
            $apiUrl = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $acessToken;
            $result = ihttp_request($apiUrl, $jsonData);
        }
    }

    public function get_hotelutud($seearr, $order_id) {
        $order = pdo_fetch('SELECT o.*,h.title,h.phone as hphone,h.address FROM ' . tablename($this->order) . ' o left join ' . tablename($this->hotel) . ' h  on o.hotelid = h.id where o.order_id = ' . $order_id);
        $url = $_W['siteroot'] . 'app/index.php?i=' . $this->_weid . '&c=entry&&order_id=' . $order['order_id'] . '&do=orderinfo&m=yc_hotelmanger';
        $tjsrt = array('template_id' => $seearr['tuid'], 'touser' => $order['openid'], 'url' => $url, 'topcolor' => '#FF0000');
        $data = array(
            'first' => array('value' => $order['title'] . '预定退房成功！', 'color' => '#173177'),
            'keyword1' => array('value' => $order['title'] . $order['goods_name'] . $order['yu_legth'] . '间', 'color' => '#173177'),
            'keyword2' => array('value' => $order['totalcpice'] . '（进店付款）', 'color' => '#173177'),
            'keyword3' => array('value' => '退房成功', 'color' => '#173177'),
            'remark' => array('value' => '感谢您的惠顾，' . $order['title'] . '欢迎您下次光临！客服电话：' . $order['phone'], 'color' => '#173177')
        );
        $tjsrt['data'] = $data;
        $jsonData = json_encode($tjsrt);
        load()->func("communication");
        $acessToken = $this->getAccessToken();
        $apiUrl = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $acessToken;
        $result = ihttp_request($apiUrl, $jsonData);
    }

    public function get_hoteluserkk($seearr, $order_id, $type = 1) {
        $order = pdo_fetch('SELECT o.*,h.title,h.phone as hphone,h.address FROM ' . tablename($this->order) . ' o left join ' . tablename($this->hotel) . ' h  on o.hotelid = h.id where o.order_id = ' . $order_id);
        $url = $_W['siteroot'] . 'app/index.php?i=' . $this->_weid . '&c=entry&&order_id=' . $order['order_id'] . '&do=orderinfo&m=yc_hotelmanger';
        $tjsrt = array('template_id' => $seearr['tkzhi'], 'touser' => $order['openid'], 'url' => $data, 'topcolor' => '#FF0000');

        if ($order['mode'] == 'wechat') {
            $stype = '微信已支付';
        } else if ($order['mode'] == 'credit') {
            $stype = '余额支付';
        }


        if ($type == 1) {
            $data = array(
                'first' => array('value' => '您好，您的' . $order['title'] . '订单正在退款申请中，请耐心等待！', 'color' => '#173177'),
                'keyword1' => array('value' => $order['title'], 'color' => '#173177'),
                'keyword2' => array('value' => $order['goods_name'], 'color' => '#173177'),
                'keyword3' => array('value' => $order['yu_legth'] . '间', 'color' => '#173177'),
                'keyword4' => array('value' => $order['totalcpice'] . '元', 'color' => '#173177'),
                'keyword5' => array('value' => $stype, 'color' => '#173177'),
                'remark' => array('value' => '如有疑问请致电' . $order['title'] . '  客服电话：' . $order['hphone'], 'color' => '#173177')
            );
        } else {
            $data = array(
                'first' => array('value' => '您好，您的' . $order['title'] . '订单退款成功，自动存入会员卡余额！', 'color' => '#173177'),
                'keyword1' => array('value' => $order['title'], 'color' => '#173177'),
                'keyword2' => array('value' => $order['goods_name'], 'color' => '#173177'),
                'keyword3' => array('value' => $order['yu_legth'] . '间', 'color' => '#173177'),
                'keyword4' => array('value' => $order['totalcpice'] . '元', 'color' => '#173177'),
                'keyword5' => array('value' => $stype, 'color' => '#173177'),
                'remark' => array('value' => '如有疑问请致电' . $order['title'] . '  客服电话：' . $order['hphone'], 'color' => '#173177')
            );
        }

        $tjsrt['data'] = $data;
        $jsonData = json_encode($tjsrt);
        load()->func("communication");
        $acessToken = $this->getAccessToken();
        $apiUrl = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $acessToken;
        $result = ihttp_request($apiUrl, $jsonData);
    }

    public function get_couponlist($status, $type) {
        global $_W;
        load()->model("mc");  
        $uid = mc_openid2uid($this->openid);
           if($_W['fans']['tag']['subscribe']==0){
            message("积分卡券功能需要关注公众号才能使用",$this->createMobileUrl('center',array('op'=>'ewm')), 'error');
        } 
        $sql = 'SELECT r.*, c.title, c.thumb,c.discount ,c.condition,c.endtime from ' . tablename($this->record) . ' r left join' . tablename($this->coupon) . ' c on  r.couponid = c.couponid   where r.status = ' . $status . ' and c.type = ' . $type . '  and r.uniacid = ' . $this->_weid . ' and r.uid =' . $_W['member']['uid'] . ' GROUP BY c.couponid DESC';
        $couarray = pdo_fetchall($sql);
        $list = array();
       
        foreach ($couarray as $k => $val) {
            $msql = 'select * from ' . tablename($this->acmcou) . 'where uniacid = ' . $this->_weid . ' and module = \'yc_hotelmanger\' and couponid =' . $val['couponid'];
            $ms = pdo_fetch($msql);

            if ($ms) {
                $list[] = $val;
                $list[$k]['total'] = pdo_fetchcolumn('select count(*) from ' . tablename($this->record) . 'where uniacid = ' . $this->_weid . ' and status = ' . $status . ' and  couponid=' . $val['couponid'] . ' and uid = ' . $_W['member']['uid']);
            }
        }

        return $list;
    }

    public function get_couponshiyong($status, $type) {
        global $_W;
        $sql = 'SELECT r.*, c.title, c.thumb,c.discount ,c.condition,c.endtime from ' . tablename($this->record) . ' r left join' . tablename($this->coupon) . ' c on  r.couponid = c.couponid   where r.status = ' . $status . ' and c.type = ' . $type . '  and r.uniacid = ' . $this->_weid . ' and r.uid =' . $_W['member']['uid'] . ' GROUP BY c.couponid DESC';
        $couarray = pdo_fetchall($sql);
        $list = array();

        foreach ($couarray as $k => $val) {
            $list[] = $val;
            $list[$k]['total'] = pdo_fetchcolumn('select count(*) from ' . tablename($this->record) . 'where uniacid = ' . $this->_weid . ' and status = ' . $status . ' and  couponid=' . $val['couponid'] . ' and uid = ' . $_W['member']['uid']);
        }

        return $list;
    }

    public function get_couoiund($type) {
        global $_W;
        $sql = 'SELECT c.*  FROM ' . tablename($this->coupon) . ' c left join ' . tablename($this->acmcou) . ' m on c.couponid = m.couponid   WHERE c.type = ' . $type . ' and c.credittype = \'credit1\' and m.module =\'yc_hotelmanger\'  and c.uniacid=' . $this->_weid;
        $couponlist = pdo_fetchall($sql);
        load()->model("mc");  
        $uid = mc_openid2uid($this->openid);
        $list = array();
        if($_W['fans']['tag']['subscribe']==0){
            message("积分卡券功能需要关注公众号才能使用",$this->createMobileUrl('center',array('op'=>'ewm')), 'error');
        }   
        foreach ($couponlist as $k => $val) {
            $list[] = $val;
            $list[$k]['total'] = pdo_fetchcolumn('select count(*) from ' . tablename($this->record) . 'where uniacid = ' . $this->_weid . '  and  couponid=' . $val['couponid'] . ' and uid = ' . $uid);
        }

        return $list;
    }

    public function get_hotelcount($hotelid) {
        return pdo_fetchcolumn('select sum(score) as roral from ' . tablename($this->room) . 'where uniacid = ' . $this->_weid . ' and hotelid = ' . $hotelid);
    }

    protected function exportexcel($data = array(), $title = array(), $filename = 'report') {
        header('Content-type:application/octet-stream');
        header("Accept-Ranges:bytes");
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=" . $filename . '.xls');
        header("Pragma: no-cache");
        header("Expires: 0");

        if (!empty($title)) {
            foreach ($title as $k => $v) {
                $title[$k] = iconv('UTF-8', 'GB2312', $v);
            }

            $title = implode("\t", $title);
            echo $title . "\n";
        }


        if (!empty($data)) {
            foreach ($data as $key => $val) {
                foreach ($val as $ck => $cv) {
                    $data[$key][$ck] = iconv('UTF-8', 'GB2312', $cv);
                }

                $data[$key] = implode("\t", $data[$key]);
            }

            echo implode("\n", $data);
        }
    }

    public function is_weixin() {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        if (strpos($user_agent, 'MicroMessenger') === false) {
            return false;
        }


        return true;
    }

    public function get_hoteljifen($order_id) {
        global $_W;
        load()->model("mc");
        load()->model("activity");
        $sql = ' select o.*, h.integral from ' . tablename($this->order) . ' o left join ' . tablename($this->hotel) . ' h on h.id = o.hotelid where o.order_status > 0 and o.uniacid = :uniacid and o.order_id = :order_id';
        $list = pdo_fetch($sql, array('uniacid' => $this->_weid, ':order_id' => $order_id));
        $uid = mc_openid2uid($list['openid']);
        $user = mc_credit_fetch($uid);

        if (1 <= $list['totalcpice']) {
            $count = floor($list['totalcpice'] / $list['integral']);
            $data = array('uid' => $uid, 'uniacid' => $this->_weid, 'credittype' => 'credit3', 'num' => $count, 'operator' => $uid, 'module' => 'yc_hotelmanger', 'clerk_id' => 0, 'store_id' => 0, 'createtime' => time(), 'remark' => 'yc_hotel订单赠送', 'clerk_type' => 1);
            $sata['credit1'] = $user['credit1'] + $count;
            $sata['credit3'] = $user['credit3'] + $count;

            if (pdo_update($this->members, $sata, array('uid' => $uid, 'uniacid' => $this->_weid))) {
                pdo_insert($this->credits_record, $data);
            }
        }
    } 
    
     public function get_zk($uni,$oid) {
          load()->model("mc"); 
            $uid= mc_openid2uid($oid);
            $userjf= pdo_fetch('SELECT uid,credit3 FROM ' . tablename($this->members) . ' WHERE uniacid = ' . $uni . ' and uid= '.$uid);
            $levelt=pdo_fetchall('SELECT levelname,ordercount,discount FROM ' . tablename($this->mlevel) . ' WHERE uniacid = ' . $uni .' ORDER BY ordercount DESC'); 
            $zk='';
            foreach ($levelt as $key => $level) {
                if($level['ordercount']>$userjf['credit3']){
                    continue;
                }else{
                    $zk= $level['discount'];
                    break;
                }
            } 
            return $zk;
     }

}

?>