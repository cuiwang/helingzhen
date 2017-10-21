<?php

/**
 * 这个商户的returl_url页面实现的模板
 * 该页面的业务逻辑是：
 * 1. 当商户收到百付宝支付成功的通知后，调用sdk中预处理操作确定该订单支付成功
 * 2. 确认支付成功后，商户自己的业务逻辑，比如记账之类的。
 * 注意，sdk中的query_order_state()方法，必须商户自己实现，
 * 否则会由于收到多次百付宝的支付结果通知，导致商户自己出现资金的不一致。
 */
require_once 'bfb_sdk.php';

$bfb_sdk = new bfb_sdk();

$bfb_sdk->log(sprintf('get the notify from baifubao, the request is [%s]', print_r($_GET, true)));

if (false === $bfb_sdk->check_bfb_pay_result_notify()) {
	$bfb_sdk->log('get the notify from baifubao, but the check work failed');
	return;
}
$bfb_sdk->log('get the notify from baifubao and the check work success');


/*
 * 此处是商户收到百付宝支付结果通知后需要做的自己的具体业务逻辑，比如记账之类的。 只有当商户收到百付宝支付 结果通知后，
 * 所有的预处理工作都返回正常后，才执行该部分
 */

// 向百付宝发起回执
$bfb_sdk->notify_bfb();


?>