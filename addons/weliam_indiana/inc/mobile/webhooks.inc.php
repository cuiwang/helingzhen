<?php
/* *
 * Ping++ Server SDK
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写, 并非一定要使用该代码。
 * 该代码仅供学习和研究 Ping++ SDK 使用，只是提供一个参考。
 */
global $_W,$_GPC;
require IA_ROOT. '/addons/weliam_indiana/pay/init.php';

// 验证 webhooks 签名方法
function verify_signature($raw_data, $signature, $pub_key_path) {
    $pub_key_contents = $pub_key_path;
    // php 5.4.8 以上，第四个参数可用常量 OPENSSL_ALGO_SHA256
    return openssl_verify($raw_data, base64_decode($signature), $pub_key_contents, 'sha256');
}

$raw_data = file_get_contents('php://input');

$headers = \Pingpp\Util\Util::getRequestHeaders();
// 签名在头部信息的 x-pingplusplus-signature 字段
$signature = isset($headers['X-Pingplusplus-Signature']) ? $headers['X-Pingplusplus-Signature'] : NULL;
if(empty($signature)){
	$signature = isset($headers['x-pingplusplus-signature']) ? $headers['x-pingplusplus-signature'] : NULL;
}

// 请从 https://dashboard.pingxx.com 获取「Ping++ 公钥」
$pub_key_path = IA_ROOT. "/addons/weliam_indiana/pay/rsa_public_key.pem";//暂时不用
$pub_key = $this->module['config']['PUBLIC_KEY'];
$result = verify_signature($raw_data, $signature, $pub_key);
if ($result === 1) {
    // 验证通过
} elseif ($result === 0) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
    echo 'verification failed';
    exit;
} else {
    header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
    echo 'verification error';
    exit;
}

$event = json_decode($raw_data, true);
if ($event['type'] == 'charge.succeeded') {
	//支付成功
    $charge = $event['data']['object'];
    // ...
    $param = array();
	$param['fee'] = $charge['amount'];
	$param['tid'] = $charge['order_no'];
	$param['type'] = $charge['channel'];
	$cztype = $charge['body'];
	$openid = $charge['extra']['open_id'];
	$param['openid'] = $openid;
	//模板消息开始 
	$datam = array(
		"first"=>array( "value"=> "您好，您已经通过第三方支付购买成功","color"=>"#173177"),
		"name"=>array('value' => '第三方支付成功！预祝中大奖', "color" => "#4a5077"),
		"remark"=>array("value"=>'点击查看详情', "color" => "#4a5077"),
	);
	$url2 = $_W['siteroot'] . 'app/' . $this -> createMobileUrl('order_get');
	$tpl_id_short = $this->module['config']['m_pay'];
	m('common')->sendTplNotice($openid,$tpl_id_short,$datam,$url2,'');    
	//模板消息结束
/*	file_put_contents(WELIAM_INDIANA."/filename.log", var_export($raw_data, true).PHP_EOL, FILE_APPEND);
	if($cztype == 1){
		switch($charge['channel']){
			case 'alipay_wap': $paytype=2;break;
			case 'wx_pub': $paytype=3;break;
			case 'bfb_wap': $paytype=5;break;
			case 'jdpay_wap': $paytype=4;break;
		}
		$data = array(
			'uniacid' => $_W['uniacid'],
			'openid' => $openid,
			'num' => $charge['amount'],
			'createtime' => time(),
			'status' => 1,
			'paytype' => $paytype,
			'ordersn' => $charge['order_no'],
			'type' => 1,
		);
		pdo_insert('weliam_indiana_rechargerecord',$data);
	}*/
    $this->othrtpayResult($param);
    header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK');
} elseif ($event['type'] == 'refund.succeeded') {
	//退款成功
    $refund = $event['data']['object'];
    // ...
    header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK');
    /*http_response_code(200);*/ // PHP 5.4 or greater
} elseif($event['type'] == 'transfer.succeeded'){
	//提现成功
	$transfer = $event['data']['object'];
	$order_no = $transfer['order_no'];
	$openid = $transfer['recipient'];
	$amount = $transfer['amount']/100;
	pdo_update('weliam_indiana_withdraw',array('status' => 2),array('order_no' => $order_no));	
	//模板消息开始 
	$datam = array(
		"first"=>array( "value"=> "您好，您提现已经成功","color"=>"#173177"),
		"money"=>array('value' => $amount.'元', "color" => "#4a5077"),
		"timet"=>array('value' => date('Y-m-d H:i:s' , time()), "color" => "#4a5077"),
		"remark"=>array("value"=>'点击查看详情', "color" => "#4a5077"),
	);
	$url2 = $_W['siteroot'] . 'app/' . $this -> createMobileUrl('person');
	$tpl_id_short = $this->module['config']['m_money'];
	m('common')->sendTplNotice($openid,$tpl_id_short,$datam,$url2,'');
	//模板消息结束
	header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK');
} else {
    /**
     * 其它类型 ...
     * - summary.daily.available
     * - summary.weekly.available
     * - summary.monthly.available
     * - transfer.succeeded
     * - red_envelope.sent
     * - red_envelope.received
     * ...
     */
    header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK');

    // 异常时返回非 2xx 的返回码
    // http_response_code(400);
}