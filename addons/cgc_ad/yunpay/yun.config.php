<?php
 //↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
require_once '../../../framework/bootstrap.inc.php';
if(empty($uniacid)){
	$uniacid = $_POST['uniacid'];
}

$setting = pdo_fetch("SELECT * FROM ".tablename('cgc_ad_setting')." WHERE weid=".$uniacid);

if(empty($setting)){
	WeUtility::logging('cgc_ad yun.config', "setting empty error");
	exit('fail');
}

//合作身份者id
$yun_config['partner']		= $setting['yunpay_pid'];

//安全检验码
$yun_config['key']			= $setting['yunpay_key'];

//云会员账户（邮箱）
$seller_email = $setting['yunpay_no'];

//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑

?>