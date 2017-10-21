<?php
defined('IN_IA') or exit('Access Denied');
wl_load()->model('address');
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

session_start();
$goodsid = $_SESSION['goodsid'];
$openid = $_W['openid'];
$pagetitle = !empty($config['tginfo']['sname']) ? '我的收货地址 - '.$config['tginfo']['sname'] : '我的收货地址';

if($goodsid){
	$bakurl = app_url('order/orderconfirm');
}else{
	$bakurl = app_url('member/home');
}

if($op == 'display'){
	$address = address_get_list("openid = '{$openid}'");
	include wl_template('address/addmanage');
}

if($op == 'select'){
	$id = $_GPC['id'];
	address_set_by_id($id,$openid);
	header("location:".app_url('address/addmanage'));
}
