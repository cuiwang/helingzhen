<?php
require_once IA_ROOT . "/addons/" . $this->modulename . "/inc/common.php";
global $_W, $_GPC;
session_start();
$op = !empty ($_GPC['op']) ? $_GPC['op'] : "display";
$from_user = $_W['fans']['from_user'];
$settings = $this->module['config'];
$uniacid = $_W['uniacid'];
$id = $_GPC['id'];
$modulename = $this->modulename;

if ($op == "display" && !empty($settings['zdy_domain']) && empty($_GET['user_json'])) {
	message("多域名用户丢失");
}


$cgc_baoming_activity = new cgc_baoming_activity();

$activity = $cgc_baoming_activity->getOne($id);

$userinfo = getFromUser($settings, $modulename);

$userinfo = json_decode($userinfo, true);

if ($op == "display") {
	if (empty ($userinfo['openid'])) {
		message("没抓到用户信息，可能借用授权服务号没配置好，或者入口错误");
	}
	
	$from_user = $userinfo['openid'];
	
	if (empty ($id)) {
		message("id不得为空");
	}
	
	$con = " and is_pay=1";
	if($activity['activity_type']<>2){
		$con = " and share_status=1";
	}
	
	$num = pdo_fetchcolumn("SELECT count(1) FROM ". tablename("cgc_baoming_user")
		." WHERE uniacid=$uniacid and openid=:openid and activity_id=:activity_id $con",
		array(':openid'=>$from_user,':activity_id'=>$id));
		
	if(empty($_GPC['user_id'])){
		$user = pdo_fetch("SELECT * FROM ". tablename("cgc_baoming_user")
    ." WHERE uniacid=$uniacid and openid=:openid and activity_id=:activity_id and share_status=1",
    array(':openid'=>$from_user,':activity_id'=>$id));
	}else{
		$user = pdo_fetch("SELECT * FROM ". tablename("cgc_baoming_user")
    ." WHERE uniacid=$uniacid and id=:id  and share_status=1",
    array(':id'=>$_GPC['user_id']));
	}
	
	
	include $this->template('coupon');
	exit ();
}
