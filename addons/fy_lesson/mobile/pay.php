<?php
/**
 * 支付方式选择页面
 */
//基本设置
$setting = pdo_fetch("SELECT sitename,footnav,copyright,front_color,teacherlist FROM " . tablename($this->table_setting) . " WHERE uniacid =:uniacid LIMIT 1", array(':uniacid' => $uniacid));

if($_GPC['ordertype'] == "buyvip"){
	$orderid = intval($_GPC['orderid']);
	$order = pdo_fetch("SELECT * FROM " . tablename($this->table_member_order) . " WHERE id = :id", array(':id' => $orderid));
	if ($order['status'] != '0') {
		message('抱歉，您的订单已经付款或是被关闭，请重新进入付款！', $this->createMobileUrl('vip'), 'error');
	}

	$params['tid']     = $orderid;
	$params['user']    = $order['openid'];
	$params['fee']     = $order['vipmoney'];
	$params['title']   = '购买['.$order['viptime'].']天VIP服务';
	$params['ordersn'] = $order['ordersn'];
	$params['virtual'] = false;
}else{
	$orderid = intval($_GPC['orderid']);
	$order = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE id = :id", array(':id' => $orderid));
	if ($order['status'] != '0') {
		message('抱歉，您的订单已经付款或是被关闭，请重新进入付款！', $this->createMobileUrl('mylesson'), 'error');
	}

	

	$params['tid']     = $orderid;
	$params['user']    = $order['openid'];
	$params['fee']     = $order['price'];
	$params['title']   = '购买['.$order['bookname'].']课程';
	$params['ordersn'] = $order['ordersn'];
	$params['virtual'] = false;
}

$payswitch = unserialize($setting['paytype']);

include $this->template('pay');

?>