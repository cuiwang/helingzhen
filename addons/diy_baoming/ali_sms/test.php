<?php
  function send_ali_sms($settings,$tel,$code,$product){
  	include "TopSdk.php";
	date_default_timezone_set('Asia/Shanghai'); 
	$c = new TopClient;
    $c->appkey = $settings['ali_appkey'];
    $c->secretKey =$settings['ali_secretkey'];
    $req = new AlibabaAliqinFcSmsNumSendRequest;
    $req->setExtend("123456");
    $req->setSmsType("normal");

    $req->setSmsFreeSignName($settings['ali_smssign']);
  
    $req->setSmsParam("{\"code\":\"$code\",\"product\":\"$product\"}");
    $req->setRecNum($tel);
    
    //  $req->setRecNum("100087");
    //$req->setSmsTemplateCode("SMS_5003922");
    $req->setSmsTemplateCode($settings['ali_smstemplate']);
    $resp = $c->execute($req);
    $resp = json_decode(json_encode($resp), true);
    
    
	if (!empty($resp['result']) && $resp['result']['err_code']==0){
		 return array("code"=>1);
	} else {
		 return array_merge($resp,array("code"=>-1,"msg"=>$resp['sub_msg']));

	}
  	
  }
  

  
  $resp=send_ali_sms(array("ali_appkey"=>"23314113","ali_smssign"=>"身份验证1",
"ali_secretkey"=>"3f06e3acf2b900a6f21532c8e0669d0d","ali_smstemplate"=>"SMS_5003922"),"15080021325","1234","测试");

    //Array ( [code] => 15 [msg] => Remote service error [sub_code] => isv.SMS_SIGNATURE_ILLEGAL [sub_msg] => 短信签名不合法 [request_id] => ztb1nfin19ew ) 
	print_r($resp);
	
	



	
	
