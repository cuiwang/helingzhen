<?php

global $_W, $_GPC;
$msg=trim($_GPC['msg']);
$gourl=trim($_GPC['gourl']);
$second=  intval(trim($_GPC['sec']));
$msg or $msg = "操作成功!";
$gourl or $gourl = "close";
$second or $second = 3;
$actionHint = "跳转";
if($gourl == "close"){
	$actionHint = "关闭";
}

$toolsModule = pdo_fetch("SELECT * FROM ".tablename('uni_account_modules')." WHERE module = 'hsh_tools' and uniacid= :weid",array(":weid" => $_W['uniacid']));
$toolsConfig = iunserializer($toolsModule['settings']);
if($toolsConfig['appid'] != "" && $toolsConfig['secret'] !="") {
	$jssdk = new JSSDK($toolsConfig['appid'], $toolsConfig['secret']);
	$signPackage = $jssdk->GetSignPackage();
} else {
	//die('appid与secret,数据错误！['.$_W['uniacid'].']');
}
include $this->template('hint');