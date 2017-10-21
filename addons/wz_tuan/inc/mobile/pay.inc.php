<?php
	global $_W, $_GPC;
	$this->getuserinfo();
	$orderid = $_GPC['orderid'];
	if (empty($orderid)) {
        message('抱歉，参数错误！', '', 'error');
    }else{
    		$order = pdo_fetch("SELECT * FROM " . tablename('wz_tuan_order') . " WHERE id ='{$orderid}'");
			$goods = pdo_fetch("SELECT * FROM " . tablename('wz_tuan_goods') . " WHERE id ='{$order['g_id']}'");
    		$params['tid'] = $order['orderno'];
    		$params['user'] = $_W['fans']['from_user'];
    		$params['fee'] = $order['pay_price'];
    		$params['title'] = $goods['gname'];
    		$params['ordersn'] = $order['orderno'];
    }
	$params['module'] = "wz_tuan";
	include $this->template('pay');
?>