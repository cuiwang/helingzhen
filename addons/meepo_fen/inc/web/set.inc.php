<?php
global $_W,$_GPC,$_CFG;
include_once INC_PATH.'web/menus.php';
$title = '系统设置';
$row = $_CFG;
$default_groupid = pdo_fetchcolumn('SELECT groupid FROM ' .tablename('mc_groups') . ' WHERE uniacid = :uniacid AND isdefault = 1', array(':uniacid' => $_W['uniacid']));
$condition = '';
$couponss = $list = pdo_fetchall('SELECT * FROM ' . tablename('activity_coupon') . " WHERE uniacid = '{$_W['uniacid']}' AND type = 1 " . $condition . " AND couponid IN (SELECT couponid FROM ".tablename('activity_coupon_allocation')." WHERE groupid = '{$default_groupid}') ORDER BY couponid DESC");
foreach ($couponss as $key=>&$coupon){
	if(empty($coupon)) {
		unset($coupon);
	}elseif($coupon['starttime'] > TIMESTAMP) {
		unset($coupon);
	}elseif($coupon['endtime'] < TIMESTAMP) {
		unset($coupon);
	}
	$coupons[] = $coupon;
}

$where = ' WHERE type = 3 AND uniacid = :uniacid ';
$params = array(':uniacid' => $_W['uniacid']);
$goodss = pdo_fetchall('SELECT * FROM '.tablename('activity_exchange')." $where ORDER BY id DESC", $params);
foreach ($goodss as $key=>&$good){
	if(empty($good)) {
		unset($good);
	}elseif($coupon['starttime'] > TIMESTAMP) {
		unset($good);
	}elseif($coupon['endtime'] < TIMESTAMP) {
		unset($good);
	}
	$goods[] = $good;
}

$couponss2 = pdo_fetchall('SELECT * FROM ' . tablename('activity_coupon') . " WHERE uniacid = '{$_W['uniacid']}' AND type = 2 " . $condition . " AND couponid IN (SELECT couponid FROM ".tablename('activity_coupon_allocation')." WHERE groupid = '{$default_groupid}') ORDER BY couponid DESC");
foreach ($couponss2 as $key=>&$coupon){
	if(empty($coupon)) {
		unset($coupon);
	}elseif($coupon['starttime'] > TIMESTAMP) {
		unset($coupon);
	}elseif($coupon['endtime'] < TIMESTAMP) {
		unset($coupon);
	}
	$coupons2[] = $coupon;
}

mload()->web('set');
mload()->func('tpl');

$set = getset($this->modulename);

if(empty($set)){
	$data = get_default();
}


$tpl = get_tpl($set['sysset']);

if(empty($coupons)){
	$url = array();
	$url['href'] = wurl('activity/coupon/post');
	$url['title'] = '+去添加';
			
	$tpl[] = array('type'=>'tpl_a','label'=>'折扣券','display'=>true,'name'=>'coupon_couponid','value'=>$url);
}else{
	$checkbox = array();
	foreach ($coupons as $coupon){
		$checkbox[] = array('title'=>$coupon['title'],'active'=>$set['sysset']['coupon_couponid'] == $coupon['couponid']?true:false,'value'=>$coupon['couponid']);
	}
	$tpl[] = array('type'=>'tpl_checkbox','label'=>'折扣券','display'=>true,'name'=>'coupon_couponid','value'=>$checkbox);
}

if(empty($coupons2)){
	$url = array();
	$url['href'] = wurl('activity/token/post');
	$url['title'] = '+去添加';
		
	$tpl[] = array('type'=>'tpl_a','label'=>'代金券','display'=>true,'name'=>'token_couponid','value'=>$url);
}else{
	$checkbox = array();
	foreach ($coupons2 as $coupon){
		$checkbox[] = array('title'=>$coupon['title'],'active'=>$set['sysset']['token_couponid'] == $coupon['couponid']?true:false,'value'=>$coupon['couponid']);
	}
	$tpl[] = array('type'=>'tpl_checkbox','label'=>'代金券','display'=>true,'name'=>'token_couponid','value'=>$checkbox);
}

if(empty($goods)){
	$url = array();
	$url['href'] =  wurl('activity/goods/post');
	$url['title'] = '+去添加';
		
	$tpl[] = array('type'=>'tpl_a','label'=>'小礼品','display'=>true,'name'=>'goods_couponid','value'=>$url);
}else{
	$checkbox = array();
	foreach ($goods as $coupon){
		$checkbox[] = array('title'=>$coupon['title'],'active'=>$set['sysset']['goods_couponid'] == $coupon['id']?true:false,'value'=>$coupon['id']);
	}
	$tpl[] = array('type'=>'tpl_checkbox','label'=>'小礼品','display'=>true,'name'=>'goods_couponid','value'=>$checkbox);
}


if($_W['ispost']){
	$date = array();
	unset($_POST['token']);
	$date['sysset'] = iserializer($_POST);
	updateset($date,$this->modulename);
	
	message('提交成功',referer(),'success');
}
include $this->template('post');
