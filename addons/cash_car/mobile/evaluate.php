<?php
/**
 * 用户评价订单
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

$title = "评价订单";

$orderid = intval($_GPC['orderid']);
$order = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE id = :id AND from_user=:from_user", array(':id' => $orderid, ':from_user'=>$from_user));

if(empty($order)){
	message('抱歉，您的订单不存在！', $this->createMobileUrl('orderlist', array('storeid' => $order['storeid'])), 'error');
}
if($order['is_evaluate']==1){
	message('抱歉，该订单已评价！', $this->createMobileUrl('orderlist'), 'error');
}

$worker = pdo_fetch("SELECT name,mobile FROM " .tablename($this->table_worker). " WHERE weid='{$weid}' AND openid='{$order['worker_openid']}' AND storeid='{$order['storeid']}'");

$goodsid = pdo_fetchall("SELECT goodsid FROM " . tablename($this->table_order_goods) . " WHERE orderid = '{$order['id']}'", array(), 'goodsid');
$goods = pdo_fetchall("SELECT * FROM " . tablename($this->table_goods) . "  WHERE id IN ('" . implode("','", array_keys($goodsid)) . "')");

if(!empty($_POST)){
	foreach($goods as $key=>$value){
		$grade   = $_GPC["grade".$value['id']]; //1.好评 2.中评 3.差评
		$content = $_GPC["content".$value['id']];
		//服务点信息
		$store = pdo_fetch("SELECT title FROM " . tablename($this->table_store) . " WHERE id=:storeid", array(":storeid"=>$value['storeid']));

		$fans = pdo_fetch("SELECT uid FROM " .tablename('mc_mapping_fans'). " WHERE openid='{$order['from_user']}'");

		$evaluate = array(
			'weid'        => $_W['uniacid'],
			'orderid'     => $orderid,
			'ordersn'     => $order['ordersn'],
			'goodsid'     => $value['id'],
			'goods_name'  => $value['title'],
			'goods_price' => $value['productprice'],
			'storeid'     => $value['storeid'],
			'store_name'  => $store['title'],
			'grade'       => $grade,
			'content'     => $content,
			'uid'         => $fans['uid'],
			'from_user'   => $order['from_user'],
			'images'      => $order['images'],
			'worker'      => $order['worker_openid'],
			'add_time'    => time(),
		);

		pdo_insert($this->table_goods_evaluate, $evaluate);
	}

	pdo_update($this->table_order, array('is_evaluate'=>1), array('id'=>$orderid));
	message("评价成功！", $this->createMobileUrl("orderlist"), "success");
	
}

include $this->template('evaluate');