<?php
/**
 * 服务项目列表
 * ============================================================================
 * 版权所有 2015-2016 米粒源码，并保留所有权利。
 * 网站地址: http://www.webmili.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！已购买用户允许对程序代码进行修改和使用，但是不允许对
 * 程序代码以任何形式任何目的的再发布，作者将保留追究法律责任的权力和最终解
 * 释权。
 * ============================================================================
 */
  
session_start();

$weid = $this->_weid;
$from_user = $this->_fromuser;

if(!empty($_GPC['storeid'])){
	$_SESSION['storeid'] = intval($_GPC['storeid']);
}

if(empty($_SESSION['storeid'])){
	message("请先选择服务点", $this->createMobileUrl('storelist'), 'warning');
}
$store = pdo_fetch("SELECT title FROM " .tablename($this->table_store). " WHERE weid='{$weid}' AND id='{$_SESSION['storeid']}'");
$title = $setting['store_model']==1?'项目列表':$store['title'];

$url = $_W['siteroot'] .'app/'. $this->createMobileUrl('goodslist', array('storeid' => $_SESSION['storeid']), true);
if (isset($_COOKIE[$this->_auth2_openid])) {
	$from_user = $_COOKIE[$this->_auth2_openid];
	$nickname = $_COOKIE[$this->_auth2_nickname];
	$headimgurl = $_COOKIE[$this->_auth2_headimgurl];
} else {
	if (isset($_GPC['code'])) {
		$userinfo = $this->oauth2();
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

//所有服务项目
$condition = " weid='{$weid}' AND status=1 ";
if($setting['store_model']==1){
	$condition .= " AND storeid=0 ";
}elseif($setting['store_model']==2){
	$condition .= " AND storeid='{$_SESSION['storeid']}' ";
}
$goodslist = pdo_fetchall("SELECT * FROM " .tablename($this->table_goods). " WHERE {$condition} ORDER BY displayorder DESC");

//购物车列表
$where = " a.weid='{$weid}' AND a.from_user='{$from_user}' AND a.total>0 ";
if($setting['store_model']==1){
	$where .= " AND a.storeid=0 ";
}elseif($setting['store_model']==2){
	$where .= " AND a.storeid='{$_SESSION['storeid']}' ";
}
$carts = pdo_fetchall("SELECT *,a.id AS cartid FROM " . tablename($this->table_cart) . " a LEFT JOIN " . tablename($this->table_goods) . " b ON a.goodsid=b.id WHERE {$where}");

foreach($carts as $goods){
	if($goods['status']==0){
		pdo_delete($this->table_cart,array('id'=>$goods['cartid']));
	}
}

$cart = pdo_fetchall("SELECT *,a.id AS cartid,a.integral AS cintegral FROM " . tablename($this->table_cart) . " a LEFT JOIN " . tablename($this->table_goods) . " b ON a.goodsid=b.id WHERE {$where}");

$totalAmount = 0;
foreach($cart as $key=>$value){
	if($value['price'] != $value['productprice']){
		pdo_update($this->table_cart, array('price'=>$value['productprice']), array('id'=>$value['cartid']));
	}
	if($value['cintegral'] != $value['integral']){
		pdo_update($this->table_cart, array('integral'=>$value['integral']), array('id'=>$value['cartid']));
	}
	$cart_goods[$key] = $value['goodsid'];
	$totalAmount += $value['productprice'];
}
$totalAmount = $totalAmount?number_format($totalAmount,2):'0.00';
$totalNumber = count($cart);


include $this->template('goodslist');