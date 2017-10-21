<?php
/**
 * 支付方式选择页面
 * ============================================================================
 * 版权所有 2015-2016 米粒源码，并保留所有权利。
 * 网站地址: http://www.webmili.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！已购买用户允许对程序代码进行修改和使用，但是不允许对
 * 程序代码以任何形式任何目的的再发布，作者将保留追究法律责任的权力和最终解
 * 释权。
 * ============================================================================
 */
  
if($_GPC['ordertype'] == "onecard"){
	$orderid = intval($_GPC['orderid']);
	$order = pdo_fetch("SELECT * FROM " . tablename($this->table_onecard_order) . " WHERE id = :id", array(':id' => $orderid));
	if ($order['status'] != '0') {
		message('抱歉，您的订单已经付款或是被关闭，请重新进入付款！', $this->createMobileUrl('onecardlist'), 'error');
	}

	$params['tid'] = $orderid;
	$params['user'] = $order['from_user'];
	$params['fee'] = $order['amount'];
	$params['title'] = $order['title'];
	$params['ordersn'] = $order['order_sn'];
	$params['virtual'] = false;
}else{
	$orderid = intval($_GPC['orderid']);
	$order = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE id = :id", array(':id' => $orderid));
	if ($order['status'] != '0') {
		message('抱歉，您的订单已经付款或是被关闭，请重新进入付款！', $this->createMobileUrl('orderlist', array('storeid' => $order['storeid'])), 'error');
	}

	//检查服务点订单是否已满
	$meal_date = $order['meal_date']; //预约日期
	$storeid   = $order['storeid'];
	$strtime = explode("~", $order['meal_time']);
	$starttime = $meal_date + $strtime[0] * 3600; //预约时间段开始时间戳
	$this->checkorder($meal_date, $starttime, $storeid, $type='old', $order);

	$params['tid'] = $orderid;
	$params['user'] = $order['from_user'];
	$params['fee'] = $order['totalprice'];
	$params['title'] = '预约洗车';
	$params['ordersn'] = $order['ordersn'];
	$params['virtual'] = false;
}

$payswitch = unserialize($setting['paytype']);

include $this->template('pay');