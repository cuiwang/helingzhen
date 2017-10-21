<?php
  /**
   * 根据微擎url获取siteroot
   * @param unknown_type $url
   * @return string
   */
  function getSiteRoot($url){
  	$arr = array('/web', '/app', '/payment/wechat', '/payment/alipay', '/api','/addons');
  	foreach ($arr as $value) {
  		$length = strpos($url, $value);
  		if($length){
  			$url = substr($url, 0, $length);
  			break;
  		}
  	}
  	$url=rtrim($url, "/");
  	return $url;
  }


function  ewei_shop($userinfo,$child_userinfo){
  global $_W,$_GPC; 
  $tablename="ewei_shop_member";

  $openid=$userinfo['openid'];
  $child_openid=$child_userinfo['openid'];
  $parent=pdo_fetch('select * from '.tablename($tablename)." where uniacid={$_W['uniacid']} and openid='$openid'");

  if (!empty($parent)) {
  	  $child=pdo_fetch('select * from '.tablename($tablename)." where uniacid={$_W['uniacid']} and openid='$child_openid'");
  
     if (empty($child)){
        load()->model("mc");     
        $uid = mc_openid2uid($child_openid);
	    $mc = mc_fetch($uid, array('realname', 'mobile', 'avatar', 'resideprovince', 'residecity', 'residedist'));
        $member = array('uniacid' => $_W['uniacid'], 'uid' => $uid,'agentid' => $parent['id'],
        'openid' => $child_openid, 'realname' => !empty($mc['realname']) ? $mc['realname'] : $child_userinfo['realname'], 
        'mobile' => !empty($mc['mobile']) ? $mc['mobile'] : $child_userinfo['tel'], 
        'nickname' => !empty($mc['nickname']) ? $mc['nickname'] : $child_userinfo['nickname'], 
        'avatar' => !empty($mc['avatar']) ? $mc['avatar'] : $child_userinfo['headimgurl'], 
        'gender' => !empty($mc['gender']) ? $mc['gender'] : $child_userinfo['sex'], 'province' => !empty($mc['residecity']) ? $mc['resideprovince'] : $child_userinfo['province'], 
        'city' => !empty($mc['residecity']) ? $mc['residecity'] : $child_userinfo['city'], 'area' => !empty($mc['residedist']) ? $mc['residedist'] : '', 'createtime' => time(), 'status' => 0);	    
	    pdo_insert($tablename, $member);
     } else {    
       if (empty($child['agentid'])){
         pdo_update($tablename, array("agentid"=>$parent['id']), array("id"=>$child['id']));
       }
     } 
  }
 
}


  //是否关注
  function sfgz_user($fromuser,$unionid=""){
  	global $_W;
  	$uniacid=$_W['uniacid'];
  	$con=" unionid='$unionid'";
  	if (empty($unionid)){
     $con=" openid='$fromuser'";
  	}
    $follow=pdo_fetchcolumn("SELECT follow FROM " . tablename('mc_mapping_fans').
    " where uniacid=$uniacid and $con"); 
   	return $follow;
  }
  

  function update_parent($activity,$user=array()){
  	global $_W,$_GPC;
  	$id=$activity['id'];
  	$uniacid=$_W['uniacid'];
	if (empty($activity['yq_mode']) && empty($activity['my_mode'])){
	  return;
	}
	
	$openid=$_COOKIE["diy_baoming_parent_".$uniacid."_".$id];
	
	
	if (empty($openid)){		 
      WeUtility::logging('update_parent', "没有上级"); 
      return;
    } 
	
	$cgc_baoming_user=new cgc_baoming_user();
	if (!empty($activity['max_yq_num'])){
	  $count=$cgc_baoming_user->getTotal("uniacid=$uniacid and openid='$openid' and activity_id=$id");
	  if ($count>=$activity['max_yq_num']){
	  	WeUtility::logging('update_parent', "邀请的抽奖码已经到达最大"); 
	  	return;
	  }
	}
	
	$userinfo=$cgc_baoming_user->selectByUser($openid,$id);
	
	 $data=array("uniacid"=>$_W['uniacid'],
             	"activity_id"=>$activity['id'], 
             	   "openid"=>$userinfo['openid'],        
             	   "nickname"=>$userinfo['nickname'],  
             	    "headimgurl"=>$userinfo['headimgurl'],            	  
             	   "tel"=>trim($userinfo['tel']),  
             	   "realname"=>trim($userinfo['realname']),  
             	   "addr"=>trim($userinfo['addr']),  
             	   "wechat_no"=>trim($userinfo['wechat_no']),  
             	   "yq_type"=>1,            	   
             	   "share_status"=>1,
             	   "byq_nickname"=>$user['nickname'], 
             	   "byq_openid"=>$user['openid'], 
                  "createtime" =>TIMESTAMP
             );
             
             
         $cj_code=getNextAvaliableCjcode($id,$activity['cj_code_start']); 
         $data['cj_code']=$cj_code;
         $data['share_status']=1;

       
                       
		$temp=$cgc_baoming_user->insert($data); 
		if (empty($temp)){
			 exit(json_encode(array("code"=>-6,"msg"=>"22")));
	    
	      
        }		
	
	 if ($activity['ewei_shop']){
	  $user=getFromUser("","diy_baoming");
	  $user=json_decode($user,true);
	  $child_userinfo=$cgc_baoming_user->selectByUser($user['openid'],$id);
	  ewei_shop($userinfo,$child_userinfo);
	 }
	
	
  }
  
  function get_share_url($activity,$url,$from_user,$settings){
  	global $_W;
  	$id=$activity['id'];
  	$main_url=$_W['siteroot'];  	
  	if ($settings['main_domain']){
  	  $main_url=$settings['main_domain']."/";
  	}
  	
  	if (!empty($activity['yq_mode'])){
  	  $url = $main_url . "app/index.php?i=".$_W['uniacid']."&j=".$_W['acid']."&c=entry&m=diy_baoming&do=yq&pid=$from_user&id=$id";
      return $url;
  	}
  	return get_random_domain($url);
  }




  function send_ali_sms($settings,$tel,$code,$product){
  	include IA_ROOT."/addons/diy_baoming/ali_sms/TopSdk.php";
    $item="";
	date_default_timezone_set('Asia/Shanghai'); 
	$c = new TopClient;
    $c->appkey = $settings['ali_appkey'];
    $c->secretKey =$settings['ali_secretkey'];
    $req = new AlibabaAliqinFcSmsNumSendRequest;
    $req->setExtend("123456");
    $req->setSmsType("normal");

    $req->setSmsFreeSignName($settings['ali_smssign']);
  
    $req->setSmsParam("{\"code\":\"$code\",\"product\":\"$product\",\"item\":\"$item\"}");
    $req->setRecNum($tel);
    
    $req->setSmsTemplateCode($settings['ali_smstemplate']);
    $resp = $c->execute($req);
    
    $resp = json_decode(json_encode($resp), true);
    
    	
	if (!empty($resp['result']) && $resp['result']['err_code']==0){
		 return array("code"=>1);
	} else {
		 return array_merge($resp,array("code"=>-1,"msg"=>$resp['msg']));

	}
  	
  }
  
    function valid_sms_post($param = array()) {  
      	
       $yzm=$param['yzm'];
       $tel=$param['tel'];  
       
       if (empty($_SESSION["send_time"]) || ($_SESSION["send_time"]+2*60)<time()){
           return (array("code"=>-1,"msg"=>$_SESSION["send_time"]."验证码过期了".time()));	
       }  
       
           
       if ($_SESSION["yzm"]!=$yzm){
           return (array("code"=>-1,"msg"=>"验证码不对"));	
       }      
       if ($_SESSION["tel"]!=$tel){
         return (array("code"=>-2,"msg"=>"发验证码的手机号不对"));		
       }       
       return (array("code"=>1,"msg"=>"正确"));
    } 
    
    
    function mc_fansinfo_all(){
	global $_W;
	

	if(empty($acid)){
		$acid = $_W['acid'];
	}
	if (empty($uniacid)) {
		$uniacid = $_W['uniacid'];
	}
	
	$params = array();
	$params[':uniacid'] = $uniacid;
	$params[':acid'] = $acid;
	
	
	$sql = 'SELECT * FROM ' . tablename('mc_mapping_fans') . " WHERE `uniacid`=:uniacid AND `acid`=:acid and nickname!='' limit 1000";
	$fans = pdo_fetchall($sql,$params);
   $fans_new=array();
	foreach( $fans as &$fan){
	if(!empty($fan)){
		if (!empty($fan['tag']) && is_string($fan['tag'])) {
			if (is_base64($fan['tag'])){
				$fan['tag'] = @base64_decode($fan['tag']);
			}
			if (is_serialized($fan['tag'])) {
				$fan['tag'] = @iunserializer($fan['tag']);
			}
			if(!empty($fan['tag']['headimgurl'])) {
				$fan['tag']['avatar'] = tomedia($fan['tag']['headimgurl']);
				unset($fan['tag']['headimgurl']);
				$fan['headimgurl']=$fan['tag']['avatar'];
			}
		} else {
			$fan['tag'] = array();
		}
		if (empty($fan['headimgurl'])) continue;
		$fans_new[]=$fan;
	}
  }
	
	return $fans_new;
}
  
  function gbk_to_utf8($str){
    return mb_convert_encoding($str, 'utf-8', 'gbk');
  }
 
  
  function getNextAvaliableCjcode($activity_id,$cj_code_start,$activity=array()) {
    global $_W;
    $code_data = pdo_fetch('SELECT code_id FROM ' . tablename('cgc_baoming_code') 
    . ' WHERE uniacid=:uniacid and activity_id=:activity_id',
    array(':uniacid'=>$_W['uniacid'],':activity_id'=>$activity_id));
    $code_id=$code_data['code_id'];
    WeUtility::logging('getNextAvaliableCjcode', $code_id);
    if (empty($code_data)) {
      $code_id =empty($cj_code_start)?1:$cj_code_start;
      WeUtility::logging('getNextAvaliableCode_id emtpy', $code_id);
      pdo_insert("cgc_baoming_code", array('uniacid'=>$_W['uniacid'], 'code_id'=>$code_id,'activity_id'=>$activity_id,'createtime'=>time()));     
    } else {
      WeUtility::logging('getNextAvaliableCode_id ok', $code_id);
      $cjm_interval=empty($activity['cjm_interval'])?1:$activity['cjm_interval'];
      $code_id=$code_id+$cjm_interval;
      pdo_update("cgc_baoming_code", array('code_id'=>$code_id,"createtime"=>time()), array('uniacid'=>$_W['uniacid'],'activity_id'=>$activity_id));
    } 
    return $code_id;
  }



  function getFromUserTest() 
{ 
  $obj=array("openid"=>"omQybuJmwBGb8-4D3P0KG3AMBaBw",
  "nickname"=>"test1","headimgurl"=>
  "http://wx.qlogo.cn/mmopen/bHACibrA8hAhnlNYETmRhdPTJiaKDCr7OvwoQ5y3oJKuDFD7iafDGWsmpVXCibjia30kc0bibkTU4xHKdrqXP1iaWkYxMmaOmFicHLza/0");
  return json_encode($obj);
}
 



  function get_random_domain($url){
     if (empty($url)){
     	return "";
     }
     $nonceStr=createNonceStr();
     $zdy_domain=explode("|", $url);
     $size=count($zdy_domain);
     if ($size==1){
     	$zdy_domain[0]=str_replace("*",$nonceStr,$zdy_domain[0]);
        return $zdy_domain[0];
     } else {
       $random=mt_rand(1,$size);
      
       $zdy_domain[$random-1]=str_replace("*",$nonceStr,$zdy_domain[$random-1]);
       return $zdy_domain[$random-1];
     }
  }

    function createNonceStr($length = 16)
  {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	$str = "";
	for ($i = 0; $i < $length; $i++) {
	  $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
	}
	return $str;
  }
	
   




//授权获取用户信息。	
 function getParent($id) {
 	 global $_W,$_GPC;	
 	$uniacid = $_W['uniacid'];	
   if (!empty($_GPC['pid']) && empty($_COOKIE["diy_baoming_parent_".$uniacid."_".$id])){ 	
   	  setcookie("diy_baoming_parent_".$uniacid."_".$id,$_GPC['pid'], time()+3600*(24*5)); 
   	  return $_GPC['pid'];
   }	   
   return $_COOKIE["diy_baoming_parent_".$uniacid."_".$id];
 
 }


//授权获取用户信息。	
 function getFromUser($settings,$modulename) 
{
  global $_W,$_GPC;	
  $uniacid = $_W['uniacid'];

 if ($_W['container']!="wechat" && !empty($settings['debug_mode'])){
 	   $user=getFromUserTest();	  
  	   return $user;
  }	
  
  if (!empty($_GPC['test'])){
    $user=getFromUserTest();
 	setcookie($modulename."_user_".$uniacid,$user, time()+3600*(24*5)); 
  	return $user;
  }	

   if (!empty($_GET['user_json'])){ 	
   	  setcookie($modulename."_user_".$uniacid,$_GET['user_json'], time()+3600*(24*5)); 
   	  return $_GET['user_json'];
   }	
   
   
   if (!empty($settings['sys_sq'])){
     load()->model('mc');
     $userinfo=mc_oauth_userinfo();  
     return json_encode($userinfo);
   }
   

 
  
  if(empty($_COOKIE[$modulename."_user_".$uniacid])){
     $url = $_W['siteroot'] . "app/index.php?i=".$_W['uniacid']."&j=".$_W['acid']."&c=entry&m=$modulename&do=xoauth";
     $xoauthURL="http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; 
     setcookie("xoauthURL",$xoauthURL, time()+3600*(24*5));
     if ($settings['jmsq']){
       $oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$settings['appid']."&redirect_uri=".urlencode($url)."&response_type=code&scope=snsapi_base&state=0#wechat_redirect";
     } else {
       $oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$settings['appid']."&redirect_uri=".urlencode($url)."&response_type=code&scope=snsapi_userinfo&state=0#wechat_redirect";
     }
     header("location:$oauth2_code");
     exit;                  
  } else { 	
  	return $_COOKIE[$modulename."_user_".$uniacid];
  }
   
}
 
 



function sendTemplateMsgAward($templateid,$openid,$obj){
       global $_W;   
      	$weid = $_W['acid'];  
        load()->classs('weixin.account');
        $accObj= WeixinAccount::create($weid);
        $access_token=$accObj->fetch_token();
        
         $template_mess= <<<EOF
        {
							           "touser":"$openid",
							           "template_id":"$templateid",
							           "url":"{$obj['url']}",         
							           "data":{
							                   "first": {
							                       "value":"{$obj['first']}"
							                   },
							                   "keyword1":{
							                       "value":"{$obj['keyword1']}"
							                   },
							                   "keyword2": {
							                       "value":"{$obj['keyword2']}"
							                   },
		                                      "keyword3":{
							                       "value":"{$obj['keyword3']}"
							                   },
							                   "keyword4": {
							                       "value":"{$obj['keyword4']}"
							                   },
							                   "remark": {
							                       "value":"{$obj['remark']}"
							                   }
							             
							            }
						       		}
EOF;

	  return send_template_message($template_mess,$access_token);
  	 
   }


	 function send_template_message($data,$access_token){
		$url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token;
		load()->func('communication');
  	    $res=ihttp_request($url, $data);
	    
	    if (is_error($res)){
	      return array("code"=>"-4","msg"=>json_encode($res));
	    }
		
		$res=json_decode($res['content'],true);	
		
		if ($res['errcode'] == '0') {
			return array("code"=>"1","msg"=>json_encode($res));
		} else {
			return array("code"=>"-3","msg"=>json_encode($res));
		}
	
	 }



 function hex2rgb( $color ) {
   if ( $color[0] == '#' ) {
    $color = substr( $color, 1 );
   }
   if ( strlen( $color ) == 6 ) {
     list( $r, $g, $b ) = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
   } elseif ( strlen( $color ) == 3 ) {
     list( $r, $g, $b ) = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
   } else {
     return false;
   }
   $r = hexdec( $r );
   $g = hexdec( $g );
   $b = hexdec( $b );
   return array( 'red' => $r, 'green' => $g, 'blue' => $b );
 }


    function get_wechat_user($param = array()) {     
      $uniacccount = WeAccount::create($param['acid']); 
   	  $userinfo=$uniacccount->fansQueryInfo($param['fromuser']);
      if (is_error($userinfo)){
   	    return array("code"=>-1,"msg"=>$userinfo['message']);
      }     
     return array("code"=>1,"user"=>$userinfo);
    }  	
    
    
    
    function valid_post($param = array()) {  
    	
       $yzm=$param['yzm'];
       $tel=$param['tel'];      
       if ($_SESSION["yzm"]!=$yzm){
           return (array("code"=>-1,"msg"=>"验证码不对"));	
       }      
       if ($_SESSION["tel"]!=$tel){
         return (array("code"=>-2,"msg"=>"发验证码的手机号不对"));		
       }       
       return (array("code"=>1,"msg"=>"正确"));
    } 
    

    
  
 
 
 //现金红包接口
   function send_xjhb($settings,$fromUser,$amount,$desc) {
   	  $Hour = date('G'); 	
   	   $ret=array();
       $ret['code']=0;
       $ret['message']="success";     
       //return $ret;
      	$amount=$amount*100;
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
        $pars = array();
        $pars['nonce_str'] = random(32);
        $pars['mch_billno'] =random(10). date('Ymd') . random(3);
        $pars['mch_id'] = $settings['mchid'];
        $pars['wxappid'] = $settings['appid'];
        $pars['nick_name'] =  $settings['nick_name'];      
        $pars['re_openid'] = $fromUser;
        $pars['total_amount'] = $amount;
        $pars['min_value'] = $amount;
        $pars['max_value'] = $amount;
        $pars['total_num'] = 1;
        $pars['client_ip'] = $settings['ip'];
        $pars['wishing'] = $desc;
        $pars['act_name'] =  $settings['act_name'];
        $pars['remark'] =  $settings['remark'];
        $pars['send_name'] = $settings['send_name'];
       
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
    $amount=$amount*100;
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
            return $resp;            
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
                    return true;
                  
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
 
  

//主动文本回复消息，48小时之内
 function post_send_text($openid,$content) {
	    global $_W;	
		//$weid = $_W['acid'];  
	    $acid=!empty($_W['acid'])?$_W['acid']:$_W['uniacid'];
        load()->classs('weixin.account');
        $accObj= WeixinAccount::create($acid);
        $token=$accObj->fetch_token();
        load()->func('communication');
		$data['touser'] =$openid;
		$data['msgtype'] = 'text';
		$data['text']['content'] = urlencode($content);
		$dat = json_encode($data);
		$dat = urldecode($dat);
		 //客服消息
			$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$token;			
			$ret=ihttp_post($url, $dat);
			$dat = $ret['content'];
			$result = @json_decode($dat, true);
			if ($result['errcode'] == '0') {				
				//message('发送消息成功！', referer(), 'success');
			} else {
			  load()->func('logging');
			  logging_run("post_send_text:");
			  logging_run($dat);
		      logging_run($result);
			}
			return $result;
			
}


 function sendTemplate($item,$url){
     global $_W; 
     $jp_mc = str_replace('"', "'", htmlspecialchars_decode($item['jp_mc']));
		                $_tdata = array(
		                  'first'=>array('value'=>$item['title'],'color'=>'#173177'),
		                  'keyword1'=>array('value'=>$jp_mc,'color'=>'#173177'),
		                  'keyword2'=>array('value'=>date('Y-m-d H:i:s',$item['kj_time']),'color'=>'#173177'),		           
		                  'remark'=>array('value'=>'点击详情进入','color'=>'#173177'),
		                 );  
        return sendTemplate_common($item['openid'],$item['template_id'],$url,$_tdata);
      
	}


    function sendTemplate_common($touser,$template_id,$url,$data){
     	global $_W; 
     	$weid = $_W['acid'];  
        load()->classs('weixin.account');
        $accObj= WeixinAccount::create($weid);
        $ret=$accObj->sendTplNotice($touser, $template_id, $data, $url);
        return $ret;
      
	}
   
   




