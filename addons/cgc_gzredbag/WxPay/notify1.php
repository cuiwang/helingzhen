<?php
error_reporting(0);
define('IN_MOBILE', true);
$input = file_get_contents('php://input');

if (!empty($input)) {
	$obj = simplexml_load_string($input, 'SimpleXMLElement', LIBXML_NOCDATA);
	$data = json_decode(json_encode($obj), true);

}

$get=$data;
require '../../../framework/bootstrap.inc.php';


load()->func('logging');
logging_run("notify1:"); 

$settings = uni_setting(2, array('payment'));

foreach($get as $k => $v) {
			if($v != '' && $k != 'sign') {
				$string1 .= "{$k}={$v}&";
			}
		}

		$sign = strtoupper(md5($string1 . "key={$wechat['signkey']}"));
		if($sign == $get['sign']) {
	$input = array();
        $input['body']="body";
        $input['attach']="attach";
        $input['out_trade_no']=date("YmdHis");
        $input['total_fee']=1;
        $input['time_start']=date("YmdHis");
        $input['time_expire']=date("YmdHis", time() + 600);
        $input['goods_tag']="test";
        $input['trade_type']="NATIVE";
        $notifyUrl = $_W['siteroot'] . "addons/cgc_/WxPay/notify.php";
      
        $input['notify_url']=$notifyUrl;
        $settings = uni_setting($_W['uniacid'], array('payment'));
        if(!is_array($settings['payment'])) {
	      exit('没有设定支付参数.');
        }
       $wechat = $settings['payment']['wechat'];
       $sql = 'SELECT `key`,`secret` FROM ' . tablename('account_wechats') . ' WHERE `acid`=:acid';
       $row = pdo_fetch($sql, array(':acid' => $wechat['account']));
       $wechat['appid'] = $row['key'];
       $wechat['secret'] = $row['secret'];
   
        $result = wechat_build($input, $wechat);
