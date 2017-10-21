<?php
global $_W, $_GPC;
$weid = $this->_weid;
$from_user = $this->_fromuser;
$setting = $this->getSetting();
$id = intval($_GPC['orderid']);

$do = 'adminorderdetail';
$method = 'adminorderdetail'; //method
$host = $this->getOAuthHost();
$authurl = $host . 'app/' . $this->createMobileUrl($method, array('orderid' => $id), true) . '&authkey=1';
$url = $host . 'app/' . $this->createMobileUrl($method, array('orderid' => $id), true);
if (isset($_COOKIE[$this->_auth2_openid])) {
    $from_user = $_COOKIE[$this->_auth2_openid];
    $nickname = $_COOKIE[$this->_auth2_nickname];
    $headimgurl = $_COOKIE[$this->_auth2_headimgurl];
} else {
    if (isset($_GPC['code'])) {
        $userinfo = $this->oauth2($authurl);
        if (!empty($userinfo)) {
            $from_user = $userinfo["openid"];
            $nickname = $userinfo["nickname"];
            $headimgurl = $userinfo["headimgurl"];
        } else {
            message('授权失败!');
        }
    } else {
        if (!empty($this->_appsecret)) {
            $this->getCode($url);
        }
    }
}

if (empty($from_user)) {
    message('会话已过期，请重新发送关键字!');
}

$is_permission = false;
$tousers = explode(',', $setting['tpluser']);
if (in_array($from_user, $tousers)) {
    $is_permission = true;
}

$order = $this->getOrderById($id);
if (empty($order)) {
    message('订单不存在');
}
$storeid = intval($order['storeid']);
if ($is_permission == false) {
    $account = pdo_fetch("SELECT * FROM " . tablename($this->table_account) . " WHERE weid = :weid AND from_user=:from_user AND status=2 AND
is_admin_order=1 AND storeid=:storeid LIMIT 1", array(':weid' => $this->_weid, ':from_user' => $from_user, ':storeid' => $storeid));
    if ($account) {
        $is_permission = true;
    }
//    $accounts = pdo_fetch("SELECT storeid FROM " . tablename($this->table_account) . " WHERE weid = :weid AND from_user=:from_user AND
// status=2 AND is_admin_order=1 ORDER BY id DESC ", array(':weid' => $this->_weid, ':from_user' => $from_user));
//    if ($accounts) {
//        $arr = array();
//        foreach ($accounts as $key => $val) {
//            $arr[] = $val['storeid'];
//        }
//        $storeids = implode(',', $arr);
//        $is_permission = true;
//    }
}

if ($is_permission == false) {
    message('对不起，您没有该功能的操作权限!');
}

//if (empty($storeids)) {
//    $order = pdo_fetch("SELECT a.* FROM " . tablename($this->table_order) . " AS a INNER JOIN " . tablename($this->table_stores) . " AS b ON a
//.storeid=b.id  WHERE a.id ={$id} ORDER BY a.id DESC LIMIT 1");
//    if (empty($order)) {
//        message('订单不存在!a');
//    }
//} else {
//    $order = pdo_fetch("SELECT a.* FROM " . tablename($this->table_order) . " AS a INNER JOIN " . tablename($this->table_stores) . " AS b ON a
//.storeid=b.id  WHERE a.id ={$id} AND a.storeid in ('" . $storeids . "') ORDER BY a.id DESC LIMIT 1");
//    if (empty($order)) {
//        message('订单不存在!b');
//    }
//}

$store = pdo_fetch("SELECT * FROM " . tablename($this->table_stores) . " where weid = :weid AND id=:id ORDER BY displayorder DESC", array(':weid' => $weid, ':id' => $storeid));

if ($order['dining_mode'] == 1) {
    $tablesid = intval($order['tables']);
    $table = pdo_fetch("SELECT * FROM " . tablename($this->table_tables) . " where weid = :weid AND id=:id LIMIT 1", array(':weid' => $weid, ':id' => $tablesid));

    $tablezones = pdo_fetch("SELECT * FROM " . tablename($this->table_tablezones) . " where weid = :weid AND id=:id LIMIT 1", array(':weid' => $weid, ':id' => $table['tablezonesid']));
    if (empty($tablezones)) {
        exit('餐桌类型不存在！');
    }
    $table_title = $tablezones['title'] . '-' . $table['title'];
}

if ($order['dining_mode'] == 3) {
    $tablezones = pdo_fetch("SELECT * FROM " . tablename($this->table_tablezones) . " where weid = :weid AND id=:id LIMIT 1", array(':weid' => $weid, ':id' => $order['tablezonesid']));
}
$order['goods'] = pdo_fetchall("SELECT a.*,b.title FROM " . tablename($this->table_order_goods) . " as a left join  " . tablename($this->table_goods) . " as b on a.goodsid=b.id WHERE a.weid = '{$weid}' and a.orderid={$order['id']}");

if ($order['couponid'] != 0) {
    $coupon = pdo_fetch("SELECT a.* FROM " . tablename($this->table_coupon) . "
        a INNER JOIN " . tablename($this->table_sncode) . " b ON a.id=b.couponid
 WHERE a.weid = :weid AND b.id=:couponid ORDER BY b.id
 DESC LIMIT 1", array(':weid' => $weid, ':couponid' => $order['couponid']));
    if (!empty($coupon)) {
        if ($coupon['type'] == 2) {
            $coupon_info = "代金券抵用金额" . $order['discount_money'];
        } else {
            $coupon_info = $coupon['title'];
        }
    }
}

if ($order['dining_mode'] == 2) {
    $deliveryuser = pdo_fetch("SELECT * FROM " . tablename($this->table_account) . " where id=:id LIMIT 1", array(':id' => $order['delivery_id']));
}

//打印数量
$printOrderCount = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename($this->table_print_order) . " WHERE
orderid=:orderid ", array(':orderid' => $id));

$printOrderCount = intval($printOrderCount);

include $this->template($this->cur_tpl . '/admin_orderdetail');