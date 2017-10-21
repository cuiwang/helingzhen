<?php
/**
 * 微小区模块
 *
 * [晓锋] Copyright (c) 2013 qfinfo.cn
 */
/**
 * 注册短信验证
 */
load()->classs('wesession');
defined('IN_IA') or exit('Access Denied');
	global $_GPC,$_W;
	WeSession::start($_W['uniacid'],$_W['fans']['from_user'],60);
	$mobile = $_GPC['mobile'];
	if ($_GPC['type'] == 'verify') {
		$member = pdo_fetch("select * from".tablename("xcommunity_member")."where weid='{$_W['uniacid']}' and mobile=:mobile",array(':mobile' => $mobile));
	}else{
		$member = pdo_fetch("select * from".tablename("xcommunity_business")."where weid='{$_W['uniacid']}' and mobile=:mobile",array(':mobile' => $mobile));
	}
	if (!empty($member)) {
		message('该号码已经注册，请更换号码，重新注册',referer(),'success');exit();
	}
	if($mobile==$_SESSION['mobile']){
		$code=$_SESSION['code'];
	}else{
		$code= random(6,1);
		$_SESSION['mobile']=$mobile;
		$_SESSION['code']=$code;
	}
	//验证是否开启
	if($this->module['config']['verifycode'] || $this->module['config']['businesscode']){
		$mobile    = $_SESSION['mobile'];
		$tpl_id    = $this->module['config']['resgisterid'];
		$tpl_value = urlencode("#code#=$code");
		$appkey    = $this->module['config']['sms_account'];
		$params    = "mobile=".$mobile."&tpl_id=".$tpl_id."&tpl_value=".$tpl_value."&key=".$appkey;
		$url       = 'http://v.juhe.cn/sms/send';
		load()->func('communication');
		$content   = ihttp_post($url,$params);
		return $content;
	}