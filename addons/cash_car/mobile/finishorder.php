<?php
/**
 * 用户完成订单
 * ============================================================================
 * 版权所有 2015-2016 米粒源码，并保留所有权利。
 * 网站地址: http://www.webmili.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！已购买用户允许对程序代码进行修改和使用，但是不允许对
 * 程序代码以任何形式任何目的的再发布，作者将保留追究法律责任的权力和最终解
 * 释权。
 * ============================================================================
 */
  
$weid = $this->_weid;
checkauth();
$from_user = $this->_fromuser;

$orderid = intval($_GPC['orderid']);
$order = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE id = :id AND from_user=:from_user", array(':id' => $orderid, ':from_user'=>$from_user));

if(empty($order)){
	message('抱歉，您的订单不存在！', $this->createMobileUrl('orderlist', array('storeid' => $order['storeid'])), 'error');
}

$result = pdo_update($this->table_order, array('status'=>3,'isfinish'=>1,'finisher'=>'user','finish_time'=>time()), array('id'=>$orderid));
if($result){
	//如果订单赠送积分大于0，则进行积分赠送
	if($order['totalintegral']>0){
		load()->model('mc');
		$uid = mc_openid2uid($order['from_user']);
		mc_credit_update($uid, 'credit1', $order['totalintegral'], array('1'=>'洗车订单:'.$order['id']));
	}

	message('完成订单成功！', $this->createMobileUrl('orderlist', array('storeid' => $order['storeid'])), 'success');
}else{
	message('完成订单失败！', $this->createMobileUrl('orderlist', array('storeid' => $order['storeid'])), 'error');
}