<?php

function wechat_build($params, $wechat) {
	load()->func('communication');
	$wOpt = array();
	$package = array();
	$package['appid'] = $wechat['appid'];
	$package['mch_id'] = $wechat['mchid'];
	$package['nonce_str'] = random(8);
	$package['body'] = $params['body'];
	$package['attach'] = $params['attach'];
	$package['out_trade_no'] = $params['out_trade_no'];//$params['tid'];
	$package['total_fee'] = $params['total_fee'];
	$package['spbill_create_ip'] = $_SERVER['SERVER_ADDR'];
	$package['time_start'] = date('YmdHis', TIMESTAMP);
	$package['time_expire'] = date('YmdHis', TIMESTAMP + 600);
	$package['notify_url'] = $params['notify_url'];
	$package['trade_type'] = $params['trade_type'];

	ksort($package, SORT_STRING);
	$string1 = '';
	foreach($package as $key => $v) {
			$string1 .= "{$key}={$v}&";
	}
	$string1 .= "key={$wechat['signkey']}";
	$package['sign'] = strtoupper(md5($string1));
	$dat = array2xml($package);

	$response = ihttp_request('https://api.mch.weixin.qq.com/pay/unifiedorder', $dat);
	if (is_error($response)) {
		return $response;
	}
	$xml = @simplexml_load_string($response['content'], 'SimpleXMLElement', LIBXML_NOCDATA);
	
	if (strval($xml->return_code) == 'FAIL') {
    load()->func('logging');
    logging_run("wechat_build1:");
    logging_run($xml);
		return error(-1, strval($xml->return_msg));
	}
	if (strval($xml->result_code) == 'FAIL') {
    load()->func('logging');
    logging_run("wechat_build2:");
    logging_run($xml);
		return error(-1, strval($xml->err_code).': '.strval($xml->err_code_des));
	}
	$prepayid = $xml->prepay_id;
	
	$wOpt['appId'] = $wechat['appid'];
	$wOpt['timeStamp'] = TIMESTAMP;
	$wOpt['nonceStr'] = random(8);
	$wOpt['package'] = 'prepay_id='.$prepayid;
	$wOpt['prepay_id'] = $prepayid;
	$wOpt['signType'] = 'MD5';
	$wOpt['code_url']=$xml->code_url;
	ksort($wOpt, SORT_STRING);
	foreach($wOpt as $key => $v) {
			$string .= "{$key}={$v}&";
	}
	$string .= "key={$wechat['signkey']}";
	$wOpt['paySign'] = strtoupper(md5($string));
	return $wOpt;
}
  //扫码模式1 生成二维码
  function native_pay_one($obj){

 	$uniacid=$obj['uniacid'];
 	$product_id=$obj['product_id'];
 	$ret=array("code"=>-1); 
  $sql="SELECT  `settings` FROM ".tablename('uni_account_modules')
    ." WHERE uniacid = '{$uniacid}' AND `module`='cgc_gzredbag'";  
  $settings = pdo_fetchcolumn($sql);
   if(empty($settings)) {
    $ret['msg']=$sql;
    $ret['code']=-2;
    return   $ret;
  }

  $wechat = iunserializer($settings);

 if(empty($wechat) || empty($wechat['appid']) || empty($wechat['secret']) || empty($wechat['mchid'])  || empty($wechat['password'])) {
    $ret['msg']=json_encode($wechat);
    $ret['code']=-3;
    return   $ret;
  }

  $wechat['signkey']=$wechat['password'];
  $package['appid'] =$wechat['appid'];
  $package['mch_id'] =$wechat['mchid'];
  $package['nonce_str'] ="f6808210402125e30663234f94c87a8c";
  $package['product_id'] =$product_id;
  $package['time_stamp'] =time();
  ksort($package, SORT_STRING);
	$string1 = '';
	foreach($package as $key => $v) {
	  $string1 .= "{$key}={$v}&";
	}
	$string1 .= "key={$wechat['signkey']}";
	$package['sign'] = strtoupper(md5($string1));  	 	
  $qr_url="weixin://wxpay/bizpayurl?appid={$package['appid']}&mch_id={$package['mch_id']}&".
    "nonce_str={$package['nonce_str']}&product_id={$package['product_id']}&time_stamp={$package['time_stamp']}&sign={$package['sign']}";        
  $ret['qr_url']=$qr_url;
  $ret['code']=1;
  return $ret;
 }


 //现金红包接口
   function send_xjhb($settings,$fromUser,$amount,$desc) {
   	   $ret=array();
       $ret['code']=0;
       $ret['message']="success";     
     //  return $ret;
   	
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
        $pars = array();
        $pars['nonce_str'] = random(32);
        $pars['mch_billno'] =random(10). date('Ymd') . random(3);
        $pars['mch_id'] = $settings['mchid'];
        $pars['wxappid'] = $settings['appid'];
        $pars['nick_name'] =   $settings['send_name'];
        $pars['send_name'] = $settings['send_name'];
        $pars['re_openid'] = $fromUser;
        $pars['total_amount'] = $amount;
        $pars['min_value'] = $amount;
        $pars['max_value'] = $amount;
        $pars['total_num'] = 1;
        $pars['wishing'] = $desc;
        $pars['client_ip'] = $settings['ip'];
        $pars['act_name'] =  $settings['act_name'];
        $pars['remark'] = $settings['remark'];

        ksort($pars, SORT_STRING);
        $string1 = '';
        foreach($pars as $k => $v) {
            $string1 .= "{$k}={$v}&";
        }
        $string1 .= "key={$settings['password']}";
        $pars['sign'] = strtoupper(md5($string1));
        $xml = array2xml($pars);
        $extras = array();
       
        $extras['CURLOPT_CAINFO']= $settings['rootca'];
        $extras['CURLOPT_SSLCERT'] =$settings['apiclient_cert'];
        $extras['CURLOPT_SSLKEY'] =$settings['apiclient_key'];


        load()->func('communication');
        $procResult = null; 
        $resp = ihttp_request($url, $xml, $extras);
        if(is_error($resp)) {
            $procResult = $resp["message"];
            $ret['code']=-1;
            $ret['message']=$procResult;
            return $ret;     
        } else {
            $xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp['content'];
            $dom = new DOMDocument();
             if($dom->loadXML($xml)) {
                $xpath = new DOMXPath($dom);
                $code = $xpath->evaluate('string(//xml/return_code)');
                $result = $xpath->evaluate('string(//xml/result_code)');
                if(strtolower($code) == 'success' && strtolower($result) == 'success') {
                    $ret['code']=0;
                    $ret['message']="success";
               
                    return $ret;
                  
                } else {
                    $error = $xpath->evaluate('string(//xml/err_code_des)');
                    $ret['code']=-2;
                    $ret['message']=$error;
                    return $ret;
                 }
            } else {
                $ret['code']=-3;
                $ret['message']="3error3";
                return $ret;
            }
            
        }

     
    }
  
  //企业付款接口
   function send_qyfk($settings,$fromUser,$amount,$desc){
    $ret=array();
    $ret['amount']=$amount;
    $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
    $pars = array();
    $pars['mch_appid'] =$settings['appid'];
    $pars['mchid'] = $settings['mchid'];
    $pars['nonce_str'] = random(32);
    $pars['partner_trade_no'] = random(10). date('Ymd') . random(3);
    $pars['openid'] =$fromUser;
    $pars['check_name'] = "NO_CHECK";
    $pars['amount'] =$amount;
    $pars['desc'] = $desc;
    $pars['spbill_create_ip'] =$settings['ip']; 
    ksort($pars, SORT_STRING);
        $string1 = '';
        foreach($pars as $k => $v) {
            $string1 .= "{$k}={$v}&";
        }
        $string1 .= "key={$settings['password']}";
        $pars['sign'] = strtoupper(md5($string1));
        $xml = array2xml($pars);
        $extras = array();
        $extras['CURLOPT_CAINFO']= $settings['rootca'];
        $extras['CURLOPT_SSLCERT'] =$settings['apiclient_cert'];
        $extras['CURLOPT_SSLKEY'] =$settings['apiclient_key'];
 
     
        load()->func('communication');
        $procResult = null; 
        $resp = ihttp_request($url, $xml, $extras);
        if(is_error($resp)) {
            $procResult = $resp['message'];
            $ret['code']=-1;
            $ret['message']="-1:".$procResult;
            return $ret;            
         } else {        	
            $xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp['content'];
            $dom = new DOMDocument();
            if($dom->loadXML($xml)) {
                $xpath = new DOMXPath($dom);
                $code = $xpath->evaluate('string(//xml/return_code)');
                $result = $xpath->evaluate('string(//xml/result_code)');
                if(strtolower($code) == 'success' && strtolower($result) == 'success') {
                    $ret['code']=0;
                    $ret['message']="success";
                    return $ret;
                  
                } else {
                    $error = $xpath->evaluate('string(//xml/err_code_des)');
                    $ret['code']=-2;
                    $ret['message']="-2:".$error;
                    return $ret;
                 }
            } else {
                $ret['code']=-3;
                $ret['message']="error response";
                return $ret;
            }
        }
    
   }


    //总金额改变
  function updatemoneydata($weid,$amount){
  	load()->func('logging');
  	$totaldata=pdo_fetchcolumn("select count(1) from ".tablename("gzredbag_money")."  where uniacid=".$weid);  	
    if (empty($totaldata)){
      pdo_insert("gzredbag_money",
             array("uniacid"=>$weid,
                   "total_money"=>$amount, 
                   "createtime"=>TIMESTAMP
                  ));
          
    } else {
      $temp=pdo_query("update ".tablename("gzredbag_money").
           " set total_money=total_money+".$amount.",createtime=".TIMESTAMP." where uniacid=".$weid);
      logging_run("gzredbag_moneyend"); 
    
    } 
   }
   
   // 回滚
   function rollback_user($weid,$openid){
  	 load()->func('logging');
     $sql="update ".tablename("gzredbag_user").
          " set openid='$openid',status=0,send_status=0,createtime=".TIMESTAMP .
          " where uniacid={$weid} and openid='$openid'";
     $temp=pdo_query($sql);
   }
  
   // 修改用户状态
   function update_user($obj,$weid,$openid){
  	 load()->func('logging');
  	 $sql="select count(1) from ".tablename("gzredbag_user")."  where uniacid=".$weid." and openid='{$openid}'";
     $exist=pdo_fetchcolumn($sql);
     if (empty($exist)){
         $sql="INSERT INTO ".tablename("gzredbag_user").
          " (uniacid,openid, money,status,send_status,createtime)" .
          " VALUES ('{$weid}', '{$openid}',{$obj},1,1,".TIMESTAMP.")";
          $temp=pdo_query($sql);
         
      if ($temp==false) {          
        logging_run("update_user:".$temp."==".$sql); 
       } 
    } 
    
   }
   
     // 修改线下用户状态
   function update_offline_user($hxcode,$weid,$openid){
  	 load()->func('logging');
     $sql="update ".tablename("gzredbag_hx").
          " set openid='$openid',status=1,createtime=".TIMESTAMP .
          " where uniacid={$weid} and hxcode='$hxcode'";
     $temp=pdo_query($sql);
     if ($temp==false) {          
        logging_run("update_offline_user:".$temp."==".$sql); 
      } 
   }