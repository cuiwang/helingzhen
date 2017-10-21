<?php
/**
 * ajax更新购物车
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
$from_user = $_GPC['from_user'];
$this->_fromuser = $from_user;

$goodsid = intval($_GPC['goodsid']); //项目id
$total = intval($_GPC['number']); //更新数量
$storeid = intval($_GPC['storeid']); //更新数量

if (empty($from_user)) {
	$result['msg'] = '会话已过期，请重新发送关键字!';
	message($result, '', 'ajax');
}

//查询项目是否存在
$goods = pdo_fetch("SELECT * FROM " . tablename($this->table_goods) . " WHERE  id=:id", array(":id" => $goodsid));
if (empty($goods)) {
	$result['msg'] = '没有相关服务项目';
	message($result, '', 'ajax');
}

//查询购物车有没该商品
$condition = " weid='{$weid}' AND goodsid='{$goodsid}' AND from_user='{$from_user}' ";
if($setting['store_model']==2){
	$condition .= " AND storeid='{$storeid}' ";
}
$cart = pdo_fetch("SELECT * FROM " . tablename($this->table_cart) . " WHERE {$condition}");

if (empty($cart)) {
	//不存在的话增加项目点击量
	pdo_query("UPDATE " . tablename($this->table_goods) . " SET subcount=subcount+1 WHERE id=:id", array(':id' => $goodsid));
	//添加进购物车
	$data = array(
		'weid' => $weid,
		'goodsid' => $goods['id'],
		'price' => $goods['productprice'],
		'integral' => $goods['integral'],
		'from_user' => $from_user,
		'total' => 1
	);
	if($setting['store_model']==2){
		$data['storeid'] = $storeid;
	}
	pdo_insert($this->table_cart, $data);
} else {
	//更新项目在购物车中的数量
	pdo_query("UPDATE " . tablename($this->table_cart) . " SET total=" . $total . " WHERE id=:id", array(':id' => $cart['id']));

	pdo_delete($this->table_cart, array('weid' => $weid, 'from_user' => $from_user, 'total' => 0));
}

//重新计算购物车商品总价
$where = " weid='{$weid}' AND from_user='{$from_user}' ";
if($setting['store_model']==2){
	$where .= " AND storeid='{$storeid}' ";
}
$cartlist = pdo_fetchall("SELECT price FROM " . tablename($this->table_cart) . " WHERE {$where}");
$totalAmount = 0;
foreach($cartlist as $item){
	$totalAmount += $item['price'];
}

$result['code'] = 0;
$result['totalAmount'] = number_format($totalAmount,2);
$result['totalNumber'] = count($cartlist);
message($result, '', 'ajax');