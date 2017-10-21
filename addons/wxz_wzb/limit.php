<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
define('IN_MOBILE', true);
$input = file_get_contents('php://input');
if (!empty($input) && empty($_GET['out_trade_no'])) {
	if (preg_match('/(\<\!DOCTYPE|\<\!ENTITY)/i', $input)) {
		exit('fail');
	}
	libxml_disable_entity_loader(true);
	$obj = simplexml_load_string($input, 'SimpleXMLElement', LIBXML_NOCDATA);
	$data = json_decode(json_encode($obj), true);
	if (empty($data)) {
		exit('fail');
	}
	if ($data['result_code'] != 'SUCCESS' || $data['return_code'] != 'SUCCESS') {
				exit('fail');
	}
	$get = $data;
} else {
	$get = $_GET;
}
require '../../framework/bootstrap.inc.php';
$_W['uniacid'] = $_W['weid'] = $get['attach'];

ksort($get);
$string1 = '';
foreach($get as $k => $v) {
	if($v != '' && $k != 'sign') {
		$string1 .= "{$k}={$v}&";
	}
}
$setting = uni_setting($_W['uniacid'], array('payment'));
if($setting['payment']['wechat']['switch']==2){
	$setting = uni_setting($setting['payment']['wechat']['borrow'], array('payment'));
}
$wechat = $setting['payment']['wechat'];

$wechat['signkey'] = ($wechat['version'] == 1) ? $wechat['key'] : $wechat['signkey'];
$sign = strtoupper(md5($string1 . "key={$wechat['signkey']}"));
if($sign == $get['sign']) {
	
	$site = WeUtility::createModuleSite('wxz_wzb');
	if(!is_error($site)) {

		pdo_update('wxz_wzb_paylog',array('status'=>'1'),array('id'=>substr($get['out_trade_no'],16)));

		$method = 'payResult';
		if (method_exists($site, $method)) {
			$ret = array();
			$ret['weid'] = $_W['weid'];
			$ret['uniacid'] = $_W['uniacid'];
			$ret['result'] = 'success';
			$ret['type'] = 'wechat';
			$ret['from'] = 'notify';
			$ret['tid'] = $get['out_trade_no'];
			$ret['user'] = $get['openid'];
			$ret['fee'] = $get['total_fee'] / 100;
			$ret['tag'] = array('transaction_id'=>$get['transaction_id']);
			$site->$method($ret);
		}
	}
	exit('success');
}
exit('fail');