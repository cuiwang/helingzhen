<?php
  require '../../../framework/bootstrap.inc.php';
  require_once IA_ROOT . "/addons/cgc_gzredbag/WxPay/WxPayPubHelper.php";
  load()->func('logging');
  logging_run("native"); 
  $input = file_get_contents('php://input');
  if (!empty($input)) {
	  $obj = simplexml_load_string($input, 'SimpleXMLElement', LIBXML_NOCDATA);
	  $data = json_decode(json_encode($obj), true);
	  if (empty($data)) {
		 logging_run("error1"); 
		 exit('error1');
	 }	
  }  else {
  	logging_run("error3"); 
  	exit("error3");
  }

  logging_run($data); 

  $sql="SELECT *  from ".tablename('gzredbag_wxpay')."  where id={$data['product_id']}";
  $item = pdo_fetch($sql);
  if (empty($item)){
    logging_run("error2:".$sql); 
    exit();
  } 

  $uniacid = $item['uniacid'];
  $sql="SELECT  `settings` FROM ".tablename('uni_account_modules')
                 ." WHERE uniacid = '{$uniacid}' AND `module`='cgc_gzredbag'";

  $settings = pdo_fetchcolumn($sql);
  $ret['code']=-1;
  if(empty($settings)) {
    logging_run("error3:".$sql); 
    exit();
  }

  $wechat = iunserializer($settings);

  if(empty($wechat) || empty($wechat['appid']) || empty($wechat['secret']) || empty($wechat['mchid'])  || empty($wechat['password'])) {
   logging_run("error4:"); 
   logging_run($wechat); 
   exit();
  }

  $wechat['signkey'] = $wechat['password'];
  logging_run("tt1:");

  ksort($data);
  $string1 = '';
  foreach($data as $k => $v) {
    if($v != '' && $k != 'sign') {
	    $string1 .= "{$k}={$v}&";
	  }
  }

  $sign = strtoupper(md5($string1 . "key={$wechat['signkey']}"));
  if($sign!= $data['sign']) {
    logging_run("sign error"); 
    exit();
  }
  
  logging_run("tt11:");

  $input = array();
  $input['body']= $item['title'];
  $input['attach']=$uniacid;
  $input['out_trade_no']="gz_redbag_".$data['product_id'].date("YmdHis").mt_rand(100,999);
  $input['total_fee']=$item['money']*100;
  $input['time_start']=date("YmdHis");
  $input['time_expire']=date("YmdHis", time() + 600);
  $input['goods_tag']="test";
  $input['trade_type']="NATIVE";
  $input['product_id']=$data['product_id'];
  $input['openid']=$data['openid'];

  $siteroot = htmlspecialchars('http://' . $_SERVER['HTTP_HOST']);
  if(substr($siteroot, -1) != '/') {
	  $siteroot .= '/';
  }

  $input['notify_url']=$siteroot."addons/cgc_gzredbag/WxPay/notify.php";
 

  $result = wechat_build($input, $wechat);
  if (is_error($result)) {
     logging_run("抱歉，发起支付失败，具体原因为：“{$wOpt['errno']}:{$wOpt['message']}”。请及时联系站点管理员。"); 
     logging_run($wechat); 
     logging_run($input); 
	   exit();
  }

  logging_run("result"); 
  logging_run($result); 
  $output = array();
  $output['return_code']="SUCCESS";
  $output['appid']=$wechat['appid'];
  $output['mch_id']=$wechat['mchid'];
  $output['nonce_str']=$data['nonce_str'];
  $output['prepay_id']=$result['prepay_id'];
  $output['result_code']='SUCCESS';
  ksort($output, SORT_STRING);
  $string1 = '';
  foreach($output as $key => $v) {
	  $string1 .= "{$key}={$v}&";
	}

	$string1 .= "key={$wechat['signkey']}";
	$output['sign'] = strtoupper(md5($string1));
	logging_run("output"); 
	logging_run($output); 


	$record = array();
	$record['uniacid'] = $uniacid;
	$record['pay_status'] = '0';
	$record['out_trade_no'] = $input['out_trade_no'];
	$record['money'] = $item['money'];
	$record['title'] = $item['title'];
	$record['wxpay_id'] = $data['product_id'];
	$record['openid'] = $data['openid'];
   $record['createtime'] = time();
  pdo_insert('gzredbag_wxpay_order', $record);
  exit(array2xml($output));