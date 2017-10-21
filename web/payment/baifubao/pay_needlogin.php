<?php

/**
 * 这个是调用bfb_sdk里生成百付宝即时到账支付接口URL(需要登录)的DEMO
 *
 */

if (!defined("BFB_SDK_ROOT"))
{
	define("BFB_SDK_ROOT", dirname(__FILE__) . DIRECTORY_SEPARATOR);
}

require_once(BFB_SDK_ROOT . 'bfb_sdk.php');
require_once(BFB_SDK_ROOT . 'bfb_pay.cfg.php');

$bfb_sdk = new bfb_sdk();

$order_create_time = date("YmdHis");
$expire_time = date('YmdHis', strtotime('+2 day'));
$order_no = $order_create_time . sprintf ( '%06d', rand(0, 999999));
$goods_category = $_POST['goods_category'];
$good_name = $_POST['goods_name'];
$good_desc = $_POST['goods_desc'];
$goods_url = $_POST['goods_url']; 
$unit_amount = $_POST['unit_amount'];
$unit_count = $_POST['unit_count'];
$transport_amount = $_POST['transport_amount'];
$total_amount = $_POST['total_amount'];
$buyer_sp_username = $_POST['buyer_sp_username'];
$return_url = $_POST['return_url'];
$page_url = $_POST['page_url'];
$pay_type = $_POST['pay_type'];
$bank_no = $_POST['bank_no'];
$extra = $_POST['extra'];

/*
 * 字符编码转换，百付宝默认的编码是GBK，商户网页的编码如果不是，请转码。涉及到中文的字段请参见接口文档
 * 步骤：
 * 1. URL转码
 * 2. 字符编码转码，转成GBK
 * 
 * $good_name = iconv("UTF-8", "GBK", urldecode($good_name));
 * $good_desc = iconv("UTF-8", "GBK", urldecode($good_desc));
 * 
 */

// 用于测试的商户请求支付接口的表单参数，具体的表单参数各项的定义和取值参见接口文档
$params = array (
		'service_code' => sp_conf::BFB_PAY_INTERFACE_SERVICE_ID,
		'sp_no' => sp_conf::SP_NO,
		'order_create_time' => $order_create_time,
		'order_no' => $order_no,
		'goods_category' => $goods_category,
		'goods_name' => $good_name,
		'goods_desc' => $good_desc,
		'goods_url' => $goods_url,
		'unit_amount' => $unit_amount,
		'unit_count' => $unit_count,
		'transport_amount' => $transport_amount,
		'total_amount' => $total_amount,
		'currency' => sp_conf::BFB_INTERFACE_CURRENTCY,
		'buyer_sp_username' => $buyer_sp_username,
		'return_url' => $return_url,
		'page_url' => $page_url,
		'pay_type' => $pay_type,
		'bank_no' => $bank_no,
		'expire_time' => $expire_time,
		'input_charset' => sp_conf::BFB_INTERFACE_ENCODING,
		'version' => sp_conf::BFB_INTERFACE_VERSION,
		'sign_method' => sp_conf::SIGN_METHOD_MD5,
		'extra' =>$extra
);

$order_url = $bfb_sdk->create_baifubao_pay_order_url($params, sp_conf::BFB_PAY_DIRECT_LOGIN_URL);

if(false === $order_url){
	$bfb_sdk->log('create the url for baifubao pay interface failed');
}
else {
	$bfb_sdk->log(sprintf('create the url for baifubao pay interface success, [URL: %s]', $order_url));
	echo "<script>window.location=\"" . $order_url . "\";</script>";
}

?>