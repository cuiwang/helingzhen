<?php
error_reporting(0);
define('IN_MOBILE', true);
$input = file_get_contents('php://input');
if (!empty($input) && empty($_GET['out_trade_no'])) {
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

require '../../../framework/bootstrap.inc.php';
require_once IA_ROOT . "/addons/cgc_gzredbag/WxPay/WxPayPubHelper.php";
$_W['uniacid'] = $_W['weid'] = $get['attach'];


load()->func('logging');
logging_run("notify:"); 
logging_run($get); 
$uniacid =$_W['uniacid'];
$sql="SELECT  `settings` FROM ".tablename('uni_account_modules')
                 ." WHERE uniacid = '{$uniacid}' AND `module`='cgc_gzredbag'";
$settings = pdo_fetchcolumn($sql);
if(empty($settings)) {
    logging_run("error1:".$sql); 
    exit('fail');
  }

$wechat = iunserializer($settings);

if(empty($wechat) || empty($wechat['appid']) || empty($wechat['secret']) || empty($wechat['mchid'])  || empty($wechat['password'])) {
   logging_run("error2:"); 
   logging_run($wechat);
   exit('fail'); 
  }

  $wechat['signkey'] = $wechat['password'];
  ksort($get);
  $string1 = '';
  foreach($get as $k => $v) {
	if($v != '' && $k != 'sign') {
		$string1 .= "{$k}={$v}&";
	}
  }

  
  $sign = strtoupper(md5($string1 . "key={$wechat['signkey']}"));
  if($sign == $get['sign']) {
    $out_trade_no = $get['out_trade_no'];
	$sql = 'SELECT * FROM ' . tablename('gzredbag_wxpay_order') . ' WHERE `out_trade_no`=:out_trade_no';
	$params = array();
	$params[':out_trade_no'] = $out_trade_no;
	$log = pdo_fetch($sql, $params);
	if(!empty($log) && $log['pay_status'] == '0') {				
	  $record = array();
	  $record['pay_status'] = '1';
	  $record['transaction_id'] =$get['transaction_id'];
	  pdo_update('gzredbag_wxpay_order', $record, array('out_trade_no' => $out_trade_no ));
      $ret['code']=-1;
	  if (!empty($wechat["max_money"]) && !empty($wechat["min_money"])
             && ($wechat["max_money"]>=$wechat["min_money"])){
        //取最大金额和最小金额的一个随机值
          $amount= mt_rand($wechat["min_money"]*100,$wechat["max_money"]*100);
          $record['get_money']=$amount/100;
		    // 企业付款
           if (empty($wechat['sendtype'])){
              $ret=send_qyfk($wechat,$log['openid'],$amount,"红包来了");
            } else {
         //现金红包
               $ret=send_xjhb($wechat,$log['openid'],$amount,"红包来了");
            }
      }
      $record['send_status']=$ret['code']==0?1:0;
      if ($ret['code']!=0){
           logging_run($ret); 
       }
       pdo_update('gzredbag_wxpay_order', $record, array('out_trade_no' => $out_trade_no));
       exit('success');
    } else {
       exit('success');
    }       
  } else {
  	logging_run("sign error"); 
  	exit('fail');
  }


