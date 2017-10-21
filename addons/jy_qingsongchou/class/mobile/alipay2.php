<?php
include (GARCIA_PATH."class/apilay/lib/alipay_submit.class.php");

$params = $_GPC['params'];
$params = base64_decode($params);
$params = json_decode($params,true);


$alipay_config['partner']		=  $params['partner'];
$alipay_config['seller_id']	= $params['seller_id'];
$alipay_config['key']			=   $params['key'];
$alipay_config['notify_url'] = $params['notify_url'];
$alipay_config['return_url'] = $params['return_url'];
$alipay_config['sign_type']    = strtoupper('MD5');
$alipay_config['input_charset']= strtolower('utf-8');
$alipay_config['cacert']    = GARCIA_PATH.'class/apilaycacert.pem';
$alipay_config['transport']    = 'http';
$alipay_config['payment_type'] = "1";
$alipay_config['service'] = "create_direct_pay_by_user";
$alipay_config['anti_phishing_key'] = "";
$alipay_config['exter_invoke_ip'] = "";





$parameter = array(
		"service"       => $params['service'],
		"partner"       => $params['partner'],
		"seller_id"  => $params['seller_id'],
		"payment_type"	=> $params['payment_type'],
		"notify_url"	=> $params['notify_url'],
		"return_url"	=> $params['return_url'],
		"anti_phishing_key"=>$params['anti_phishing_key'],
		"exter_invoke_ip"=>$params['exter_invoke_ip'],
		"out_trade_no"	=> $params['WIDout_trade_no'],
		"subject"	=> $params['WIDsubject'],
		"total_fee"	=> $params['WIDtotal_fee'],
		"body"	=> $params['WIDbody'],
		"_input_charset"	=> trim(strtolower($params['input_charset']))


);
// var_dump($alipay_config);
$alipaySubmit = new AlipaySubmit($alipay_config);
$html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
echo $html_text;

 ?>
