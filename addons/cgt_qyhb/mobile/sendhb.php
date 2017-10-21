<?php  



  
  function sendhb($settings,$fromUser,$weid){
  	if (empty($settings['tj_amount'])){
  	  return;	
  	}
  
  $amount=$settings['tj_amount'];
   $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
   $pars = array();
   $pars['mch_appid'] =$settings['appid'];
   $pars['mchid'] = $settings['mchid'];
   $pars['nonce_str'] = random(32);
   $pars['partner_trade_no'] = random(10). date('Ymd') . random(3);
   $pars['openid'] =$fromUser;
   $pars['check_name'] = "NO_CHECK";

   $pars['amount'] =$amount;
   $pars['desc'] = "推荐获得的红包";
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
        $extras['CURLOPT_CAINFO'] = MB_ROOT . '/cert/rootca.pem.' . $weid;
        $extras['CURLOPT_SSLCERT'] = MB_ROOT . '/cert/apiclient_cert.pem.' . $weid;
        $extras['CURLOPT_SSLKEY'] = MB_ROOT . '/cert/apiclient_key.pem.' . $weid;

        load()->func('communication');
        $procResult = null; 
        $resp = ihttp_request($url, $xml, $extras);
        
                if(is_error($resp)) {
            $procResult = $resp;
          
        } else {
        	
            $xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp['content'];
      
            $dom = new DOMDocument();
            if($dom->loadXML($xml)) {
                $xpath = new DOMXPath($dom);
                $code = $xpath->evaluate('string(//xml/return_code)');
                $ret = $xpath->evaluate('string(//xml/result_code)');
                if(strtolower($code) == 'success' && strtolower($ret) == 'success') {
                    $procResult = true;
                } else {
                    $error = $xpath->evaluate('string(//xml/err_code_des)');
                    $procResult = error(-2, $error);
               
                }
            } else {
                $procResult = error(-1, 'error response');
            }
        }
    
   }
  

 

  //测试代码
   if (1==1 && empty($_W['fans']['from_user']) && empty($_GPC['test'])){
    $url= $this->createMobileUrl('guanzhu');
  	header("location:".$url);
	exit();
   }
    $op=empty($_GPC['op'])?"display":$_GPC['op'];
    if ($op=="display"){ 
     include $this->template('sendhb');
  	  exit();
    }
    
    
    
      $amount= mt_rand(intval($settings["min_amount"]),intval($settings["max_amount"]));
           
 
  $count=pdo_fetchcolumn("select count(1) from ".tablename("qyhb_user")." where uniacid=".$_W['uniacid']." and status=1 and user_id='{$fromUserJson['user_id']}'");
    
  if ($count>0){
  	  $vars = array();
      $vars['message'] ="suceess";
	  $vars['code'] =2;
	  exit(json_encode($vars));
   }
   
     $ip=getip();
     $count=pdo_fetchcolumn("select count(1) from ".tablename("qyhb_user")." where uniacid=".$_W['uniacid']." and  ipaddr='".$ip."'");
    
 
 
  if ($count>50){
  	  $vars = array();
      $vars['message'] ="suceess";
	  $vars['code'] =2;
	  exit(json_encode($vars));
   }
   
   //测试代码
   if (1!=1){
  	  $vars = array();
      $vars['message'] ="suceess";
	  $vars['code'] =7;
	  $vars['amount'] =sprintf("%.2f",  $amount/100);
	  exit(json_encode($vars));
   }

   //发的钞票

    
   $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
   $pars = array();
   $pars['mch_appid'] =$settings['appid'];
   $pars['mchid'] = $settings['mchid'];
   $pars['nonce_str'] = random(32);
   $pars['partner_trade_no'] = random(10). date('Ymd') . random(3);
   $pars['openid'] =$fromUser;
   $pars['check_name'] = "NO_CHECK";

   $pars['amount'] =$amount;
   $pars['desc'] = empty($settings['notice'])?"现金红包":$settings['notice'];
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
        $extras['CURLOPT_CAINFO'] = MB_ROOT . '/cert/rootca.pem.' . $weid;
        $extras['CURLOPT_SSLCERT'] = MB_ROOT . '/cert/apiclient_cert.pem.' . $weid;
        $extras['CURLOPT_SSLKEY'] = MB_ROOT . '/cert/apiclient_key.pem.' . $weid;

        load()->func('communication');
        $procResult = null; 
        $resp = ihttp_request($url, $xml, $extras);
    
        if(is_error($resp)) {
            $procResult = $resp;
          
        } else {
        	
            $xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp['content'];
            $dom = new DOMDocument();
            if($dom->loadXML($xml)) {
                $xpath = new DOMXPath($dom);
                $code = $xpath->evaluate('string(//xml/return_code)');
                $ret = $xpath->evaluate('string(//xml/result_code)');
                if(strtolower($code) == 'success' && strtolower($ret) == 'success') {
                    $procResult = true;
                } else {
                    $error = $xpath->evaluate('string(//xml/err_code_des)');
                    $procResult = error(-2, $error);
                }
            } else {
                $procResult = error(-1, 'error response');
            }
        }

        if(is_error($procResult)) {
          $vars = array();
		  $vars['message'] = $procResult['message'];		  
		  $vars['code'] =6;
		 // $vars['message']="dd";
		  
		  exit(json_encode($vars));
        } else {
        	
        if 	($_COOKIE["wechatId"]!=$fromUser && !empty($_COOKIE["wechatId"])){
          sendhb($settings,$_COOKIE["wechatId"],$weid);	
        }
        
        pdo_insert("qyhb_user",
          array("uniacid"=>$_W['uniacid'],
           "user_name" => $fromUserJson["user_name"],
           "user_image"=> $fromUserJson["user_image"],
           "user_id"=> $fromUserJson['user_id'],
           "status"=>1,
           "num"=>$amount,
         "ipaddr"=> getip(),
         "referee"=> $_COOKIE["wechatId"],
        "createtime"=>TIMESTAMP
        ));

        	
           $vars = array();
		   $vars['message'] ="suceess";
		   $vars['code'] =7;
		   $vars['amount'] =sprintf("%.2f",  $amount/100);
	
		   exit(json_encode($vars));
        }
   
