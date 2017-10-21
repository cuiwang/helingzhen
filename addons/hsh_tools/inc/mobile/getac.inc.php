<?php

global $_W, $_GPC;
$appid=trim($_GPC['appid']);
$secret=trim($_GPC['secret']);
$refresh=trim($_GPC['refresh']);

$tableName="account_wechats";
$returnArray = array();


$account = pdo_fetch("SELECT `key`, `secret`, `acid`, `access_token` FROM ".tablename('account_wechats')." WHERE `key` = :appid AND `secret` = :secret", array(':appid' =>$appid ,':secret' => $secret));
if($account && $refresh != '1') {
	if (!empty($account['access_token'])) {
		$account['access_token'] = unserialize($account['access_token']);
		if ($account['access_token']['expire'] > TIMESTAMP) {
			$returnArray['state'] = 1;
			$returnArray['access_token'] = $account['access_token']['token'];
			$returnArray['expires_time'] = $account['access_token']['expire'];
			$returnArray['expires_in'] = intval($account['access_token']['expire'])-time();
			
			returnJSON($returnArray,'none');
			return ;
		}
	}
}
$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$secret}";
load()->func('communication');
$content = ihttp_get($url);
if(is_error($content)) {
	$error = '获取微信公众号授权失败, 请稍后重试！错误详情: ' . $content['message'];
	$returnArray['state']=0;
	$returnArray['errmsg']=$error;
	returnJSON($returnArray,'none');
	return ;
}
$token = @json_decode($content['content'], true);
if(empty($token) || !is_array($token) || empty($token['access_token']) || empty($token['expires_in'])) {
	$errorinfo = substr($content['meta'], strpos($content['meta'], '{'));
	$errorinfo = @json_decode($errorinfo, true);
	$error = '获取微信公众号授权失败, 请稍后重试！ 公众平台返回原始数据为: 错误代码-' . $errorinfo['errcode'] . '，错误信息-' . $errorinfo['errmsg'];
	$returnArray['state']=0;
	$returnArray['errmsg']=$error;
	$returnArray['errcode']=$errorinfo['errcode'];
	returnJSON($returnArray,'none');
	return ;
}
$record = array();
$record['token'] = $token['access_token'];
$record['expire'] = TIMESTAMP + $token['expires_in'];
$row = array();
$row['access_token'] = iserializer($record);

if($account['acid']) {
	pdo_update('account_wechats', $row, array('acid' => $account['acid']));
}


$tokenStr = $token['access_token'];
if (empty($tokenStr)) {
	$returnArray['state']=0;
	$returnArray['errmsg']="未知错误";
	$returnArray['errcode']=$errorinfo['errcode'];
	returnJSON($returnArray,'none');
	return ;
}
$returnArray['state']=1;
$returnArray['access_token'] = $tokenStr;
$returnArray['expires_time'] = TIMESTAMP + $token['expires_in'];
$returnArray['expires_in'] = $token['expires_in'];
returnJSON($returnArray,'none');
return ;