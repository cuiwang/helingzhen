<?php

function text_len($text)
{
    preg_match_all('/./us', $text, $match);
    return count($match[0]);
}

function getAccessToken($settings) {
  global $_W;
  load()->func('communication');
  $cachekey = "accesstoken:{$this->account['acid']}";
		$cache = cache_load($cachekey);
		if (!empty($cache) && !empty($cache['token']) && $cache['expire'] > TIMESTAMP) {
			$this->account['access_token'] = $cache;
			return $cache['token'];
		}
		if (empty($this->account['key']) || empty($this->account['secret'])) {
			return error('-1', '未填写公众号的 appid 或 appsecret！');
		}
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->account['key']}&secret={$this->account['secret']}";
		$content = ihttp_get($url);
		if(is_error($content)) {
			message('获取微信公众号授权失败, 请稍后重试！错误详情: ' . $content['message']);
		}
		$token = @json_decode($content['content'], true);
		if(empty($token) || !is_array($token) || empty($token['access_token']) || empty($token['expires_in'])) {
			$errorinfo = substr($content['meta'], strpos($content['meta'], '{'));
			$errorinfo = @json_decode($errorinfo, true);
			message('获取微信公众号授权失败, 请稍后重试！ 公众平台返回原始数据为: 错误代码-' . $errorinfo['errcode'] . '，错误信息-' . $errorinfo['errmsg']);
		}
		$record = array();
		$record['token'] = $token['access_token'];
		$record['expire'] = TIMESTAMP + $token['expires_in'] - 200;
		$this->account['access_token'] = $record;
		cache_write($cachekey, $record);
		return $record['token'];
	}




function task_cal_red($member,$quan,$adv,$config=array(),$task){
  global $_W,$_GPC;
  $weid=$_W['uniacid'];
  $quan_id=$quan['id'];
  $id=$adv['id'];
  $mid=$member['id']; 
  $rob_index=$adv['rob_users'];
  $rob_plan=explode(',',$adv['rob_plan']);
  $rob_money=$rob_plan[$rob_index];
  $rob_money=$rob_money/100; 
  if($rob_money==0){
    $rob_money ='0.01';
  }
  

 
  if($adv['rob_users']>=$adv['total_num']){
   return array("code"=>0,"msg"=>'手慢了,钱被抢完了');
  }
      
	

  if(empty($rob_money) || $rob_money<=0 || $rob_money>$adv['total_amount']){
    return array("code"=>0,"msg"=>'哎呀~没抢到');
  }
  

     
  $get_money=$rob_money;
  $up_money=0;
  // 计算上级提成
  if($quan['up_rob_fee']>0 && $member['inviter_id']>0){
  	
    $up_money=$rob_money*(($quan['up_rob_fee'])/100);
    
	if($up_money>0 && $up_money<$rob_money){
	  $get_money=$rob_money-$up_money;
	}else{
	 $up_money=0;
	}
  }

  $mine_cold_time=$quan['cold_time'];
  $rob_next_time=time()+$mine_cold_time;
  
 																
  if($member['follow']==1){
    $ret2=pdo_update("cgc_ad_member",array('up_money'=>$member['up_money']+$up_money,'rob'=>$member['rob']+$get_money,'credit'=>$member['credit']+$get_money,'fstatus'=>'1','rob_next_time'=>$rob_next_time),array('id'=>$mid));
  }else{
    $ret2=pdo_update("cgc_ad_member",array('up_money'=>$member['up_money']+$up_money,'rob'=>$member['rob']+$get_money,'credit'=>$member['credit']+$get_money,'rob_next_time'=>$rob_next_time),array('id'=>$mid));
  }
  
 
  if(false===$ret2){
    return array("code"=>0,"msg"=>'好可惜~没抢到');
  }
  
	
  $data=array(
    'weid'=>$weid,
    'quan_id'=>$quan_id,
    'advid'=>$id,
    'mid'=>$mid,
    'money'=>$get_money,
    'up_money'=>$up_money,
    'total_money'=>$rob_money,
    'create_time'=>TIMESTAMP,
   );
  
   $ret1=pdo_insert("cgc_ad_red",$data);
   if(false===$ret2){
      return array("code"=>0,"msg"=>'插入红包表失败');
   }
   
   $cgc_ad_task=new cgc_ad_task(); 
   $cgc_ad_task->modify($task['id'],array("status"=>1,"money"=>$get_money));
			
  // 如果当前开启上级提成，并且我有上级，则需要记录上级提成记录
  if($quan['up_rob_fee']>0 && $member['inviter_id']>0 && $up_money>0){
  	    $inviter_member=pdo_fetch("SELECT * FROM ".tablename('cgc_ad_member')." WHERE weid=".$weid." AND quan_id=".$quan_id." AND id=".$member['inviter_id']);
		$ret3=pdo_update("cgc_ad_member",array('rob'=>$inviter_member['rob']+$up_money,'credit'=>$inviter_member['credit']+$up_money),array('id'=>$member['inviter_id']));
		if(false!==$ret3){
		
		}
  }
	
  if(($adv['rob_users']+1)==$adv['total_num']){
    pdo_update("cgc_ad_adv",array('rob_amount'=>($adv['rob_amount']+$rob_money),'rob_users'=>($adv['rob_users']+1),'rob_end_time'=>TIMESTAMP,'rob_status'=>1),array('id'=>$adv['id']));
    $max_id=pdo_fetchcolumn("SELECT max(money) FROM ".tablename('cgc_ad_red')." WHERE weid=".$weid." AND quan_id=".$quan_id." AND advid=".$id);
    pdo_update("cgc_ad_red",array('is_luck'=>1),array('money'=>$max_id));
    $min_id=pdo_fetchcolumn("SELECT min(money) FROM ".tablename('cgc_ad_red')." WHERE weid=".$weid." AND quan_id=".$quan_id." AND advid=".$id);
    pdo_update("cgc_ad_red",array('is_shit'=>1),array('money'=>$min_id));
  }  else
  {
    pdo_update("cgc_ad_adv",array('rob_amount'=>($adv['rob_amount']+$rob_money),'rob_users'=>($adv['rob_users']+1)),array('id'=>$adv['id']));
  }	
  
   return array("code"=>1,'get_money'=>$get_money,"msg"=>'抢到'.($rob_money).$config['unit_text'].'！','data'=>array(
    'rob_money'=>$rob_money,'last_num'=>$adv['total_num']-$rob_index-1)
  );

}


function cal_red($member,$quan,$adv,$config=array()){
  global $_W,$_GPC;
  $weid=$_W['uniacid'];
  $quan_id=$quan['id'];
  $id=$adv['id'];
  $mid=$member['id']; 
  $rob_index=$adv['rob_users'];
  $rob_plan=explode(',',$adv['rob_plan']);
  $rob_money=$rob_plan[$rob_index];
  $rob_money=$rob_money/100; 
  if($rob_money==0){
    $rob_money ='0.01';
  }
  
  
   $Hour = date('G');
   if ($quan['begin_time']>$Hour || $quan['over_time']<$Hour){
    return array("code"=>0,"msg"=>"活动时间为:".$quan['begin_time']."点到".$quan['over_time']."点");
   }
  
  // 判断我现在是否可抢
  if($member['rob_next_time']>time()){
   return array("code"=>0,"msg"=>'rob_cold'); 
 }
 
 
 
 
 
  // 判断是否已经开枪
  if($adv['rob_start_time']>time()){
  	 return array("code"=>0,"msg"=>'请稍等，还没到开抢时间哦~');
  }
 
  if($adv['rob_users']>=$adv['total_num']){
   return array("code"=>0,"msg"=>'手慢了,钱被抢完了');
  }
      
	
 
  $temp_city=0;		
 					
  if(empty($quan['city'])){
     $temp_city=1;
   } else {
   	$quan['city']=str_replace("市", "", $quan['city']);  
   	$member['last_city']=str_replace("市", "", $member['last_city']);  	
	
	$city_arr=explode('|', $quan['city']);   
    if (empty($member['last_city'])){
  	  return array("code"=>0,"msg"=>'没有抓到你的地区信息');
  }
    
   
    
    $member_city_arr=explode('|', $member['last_city']);
    
    foreach ($member_city_arr as $value) {
	  if(in_array($value, $city_arr)){
         $temp_city=1;
	     break;
      }
   }
  }



  if (empty($temp_city)){
  	return array("code"=>0,"msg"=>'只有'.$quan['city']."范围内才能抢哦~");
  }
  
  
  if(empty($rob_money) || $rob_money<=0 || $rob_money>$adv['total_amount']){
    return array("code"=>0,"msg"=>'哎呀~没抢到');
  }
  
 //必须关注才可以抢 
if($quan['is_follow']==1){
  if($member['follow']==0){
  	 return array("code"=>0,"msg"=>'must_guanzhu',"url"=>$quan['follow_url']);
 
  }
}
  
     
  $get_money=$rob_money;
  $up_money=0;
  // 计算上级提成
  if($quan['up_rob_fee']>0 && $member['inviter_id']>0){
  	
    $up_money=$rob_money*(($quan['up_rob_fee'])/100);
    
	if($up_money>0 && $up_money<$rob_money){
	  $get_money=$rob_money-$up_money;
	}else{
	 $up_money=0;
	}
  }

  $mine_cold_time=$quan['cold_time'];
  $rob_next_time=time()+$mine_cold_time;
  
 																
  if($member['follow']==1){
    $ret2=pdo_update("cgc_ad_member",array('up_money'=>$member['up_money']+$up_money,'rob'=>$member['rob']+$get_money,'credit'=>$member['credit']+$get_money,'fstatus'=>'1','rob_next_time'=>$rob_next_time),array('id'=>$mid));
  }else{
    $ret2=pdo_update("cgc_ad_member",array('up_money'=>$member['up_money']+$up_money,'rob'=>$member['rob']+$get_money,'credit'=>$member['credit']+$get_money,'rob_next_time'=>$rob_next_time),array('id'=>$mid));
  }
  
 
  if(false===$ret2){
    return array("code"=>0,"msg"=>'好可惜~没抢到');
  }
  
	
  $data=array(
    'weid'=>$weid,
    'quan_id'=>$quan_id,
    'advid'=>$id,
    'mid'=>$mid,
    'money'=>$get_money,
    'up_money'=>$up_money,
    'total_money'=>$rob_money,
    'create_time'=>TIMESTAMP,
   );
  
   pdo_insert("cgc_ad_red",$data);
			
  // 如果当前开启上级提成，并且我有上级，则需要记录上级提成记录
  if($quan['up_rob_fee']>0 && $member['inviter_id']>0 && $up_money>0){
  	    $inviter_member=pdo_fetch("SELECT * FROM ".tablename('cgc_ad_member')." WHERE weid=".$weid." AND quan_id=".$quan_id." AND id=".$member['inviter_id']);
		$ret3=pdo_update("cgc_ad_member",array('rob'=>$inviter_member['rob']+$up_money,'credit'=>$inviter_member['credit']+$up_money),array('id'=>$member['inviter_id']));
		if(false!==$ret3){
			/*pdo_insert('cgc_ad_up_red', array(
				'weid'=>$_W['uniacid'],
				'quan_id'=>$quan_id,
				'advid'=>$id,
				'mid'=>$member['id'],
				'up_id'=>$member['inviter_id'],
				'up_fee'=>$quan['up_rob_fee'],
				'up_money'=>$up_money,
				'rob_money'=>$rob_money,
				'create_time'=>time()
			));*/
		}
  }
	
  if(($adv['rob_users']+1)==$adv['total_num']){
    pdo_update("cgc_ad_adv",array('rob_amount'=>($adv['rob_amount']+$rob_money),'rob_users'=>($adv['rob_users']+1),'rob_end_time'=>TIMESTAMP,'rob_status'=>1),array('id'=>$adv['id']));
    $max_id=pdo_fetchcolumn("SELECT max(money) FROM ".tablename('cgc_ad_red')." WHERE weid=".$weid." AND quan_id=".$quan_id." AND advid=".$id);
    pdo_update("cgc_ad_red",array('is_luck'=>1),array('money'=>$max_id));
    $min_id=pdo_fetchcolumn("SELECT min(money) FROM ".tablename('cgc_ad_red')." WHERE weid=".$weid." AND quan_id=".$quan_id." AND advid=".$id);
    pdo_update("cgc_ad_red",array('is_shit'=>1),array('money'=>$min_id));
  }  else
  {
    pdo_update("cgc_ad_adv",array('rob_amount'=>($adv['rob_amount']+$rob_money),'rob_users'=>($adv['rob_users']+1)),array('id'=>$adv['id']));
  }	
  
   return array("code"=>1,"msg"=>'抢到'.($rob_money).$config['unit_text'].'！','data'=>array(
    'rob_money'=>$rob_money,'last_num'=>$adv['total_num']-$rob_index-1)
  );

}


/*function VP_IMAGE_SAVE($path, $dir = '')
{
    global $_W;
    $filePath = ATTACHMENT_ROOT . '/' . $path;
    $key = $path;
    $accessKey = $_W['module_setting']['qn_ak'];
    $secretKey = $_W['module_setting']['qn_sk'];
    $auth = new Qiniu\Auth($accessKey, $secretKey);
    $bucket = empty($dir) ? $_W['module_setting']['qn_bucket'] : $dir;
    $token = $auth->uploadToken($bucket);
    $uploadMgr = new Qiniu\Storage\UploadManager();
    list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
    return array('error' => $err, 'image' => empty($ret) ? '' : $ret['key']);
}
function VP_IMAGE_URL($path, $style = 'm', $dir = '', $driver = '')
{
    global $_W;
    if ('local' == $driver) {
        return $_W['attachurl'] . $path;
    } else {
        return 'http://' . $_W['module_setting']['qn_api'] . '/' . $path . '-' . $style;
    }
}*/
 

  
  //企业付款接口
   function send_qyfk($settings,$fromUser,$amount){
    $ret=array();
  /*  if (!empty($settings['debug_mode'])){
      return array("code"=>"0");
    }*/
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
    $pars['desc'] = $settings['pay_desc'];
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

  
 

   function get_contents($url){
		$ch = curl_init();
		$timeout = 1500;
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_TIMEOUT,2);
		$file_contents = curl_exec($ch);
		curl_close($ch);
		return $file_contents;
	}
	
	
	function cut($from, $start, $end, $lt = false, $gt = false)
	{
		$str = explode($start, $from);
		if (isset($str['1']) && $str['1'] != '') {
			$str = explode($end, $str['1']);
			$strs = $str['0'];
		} else {
			$strs = '';
		}
		if ($lt) {
			$strs = $start . $strs;
		}
		if ($gt) {
			$strs .= $end;
		}
		return $strs;
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
 
  






function getFromUserTest() 
{ 
  $obj=array("openid"=>"test2",
  "nickname"=>"test2","headimgurl"=>
  "http://wx.qlogo.cn/mmopen/bHACibrA8hAhnlNYETmRhdPTJiaKDCr7OvwoQ5y3oJKuDFD7iafDGWsmpVXCibjia30kc0bibkTU4xHKdrqXP1iaWkYxMmaOmFicHLza/0");
  return json_encode($obj);
}
 



//授权获取用户信息。	
 function getFromUser($settings,$modulename) 
{
  global $_W,$_GPC;	
  $uniacid = $_W['uniacid'];
  if ($_W['container']!="wechat" && !empty($settings['debug_mode'])){
    return getFromUserTest();
  }	
 

  if (!empty($_GPC['test11'])){
  	$userinfo=getFromUserTest();
  	setcookie($modulename."_user_".$uniacid,$userinfo, time()+3600*24);
    return $userinfo;
  }	
  

/*  load()->model('mc');
  $userinfo    = mc_oauth_userinfo();
  return json_encode($userinfo);
 */
  
  if(empty($_COOKIE[$modulename."_user_".$uniacid])){
     $url = $_W['siteroot'] . "app/index.php?i=".$_W['uniacid']."&j=".$_W['acid']."&c=entry&m=$modulename&do=xoauth";
     $xoauthURL="http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; 
     setcookie("xoauthURL",$xoauthURL, time()+3600*(24*5));
     $oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$settings['appid']."&redirect_uri=".urlencode($url)."&response_type=code&scope=snsapi_userinfo&state=0#wechat_redirect";
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
   
   
     function sendTemplate_common($touser,$template_id,$url,$data){
     	global $_W; 
     	$weid = $_W['acid'];  
        load()->classs('weixin.account');
        $accObj= WeixinAccount::create($weid);
        $ret=$accObj->sendTplNotice($touser, $template_id, $data, $url);
        return $ret;
      
	}
   
   

function sendTemplate($templateid,$openid,$obj){
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

  //是否关注
  function sfgz_user($fromuser,$settings=array()){
  	global $_W;
  	$fromuser=$_W['openid'];
  	$uniacid=$_W['uniacid'];
   	$follow=pdo_fetchcolumn("SELECT follow FROM " . tablename('mc_mapping_fans').
          " where uniacid=$uniacid and openid='$fromuser'"); 
   	return $follow;
  }

  
  //查看会员
  function select_member($user){
  	global $_W;
  	$uniacid=$_W['uniacid'];
   	$follow=pdo_fetchcolumn("SELECT follow FROM " . tablename('mc_mapping_fans').
          " where uniacid=$uniacid and openid='$fromuser'"); 
   	return $follow;
  }
  
  
  
  

    function get_wechat_user($from_user) { 
      global $_W;
  	  $uniacid=$_W['acid'];    
      $uniacccount = WeAccount::create($uniacid); 
   	  $userinfo=$uniacccount->fansQueryInfo($from_user);
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
    

    
  



  

    function wechat_duizhang($out_trade_no,$settings){
     global $_W, $_GPC;
    load()->func('communication');  
    $uniacid=$_W["uniacid"];

    $password=$settings['password'];
    $appid=$settings['appid'];
    $mch_id=$settings['mchid'];

    $package = array();
    $package['appid'] =$appid;
    $package['mch_id'] = $mch_id;
    $package['nonce_str'] = random(8);
    $package['out_trade_no'] = $out_trade_no;

    ksort($package, SORT_STRING);
    $string1 = '';
    foreach($package as $key => $v) {
      $string1 .= "{$key}={$v}&";
    }
    $string1 .= "key={$password}";
    $package['sign'] = strtoupper(md5($string1));
    $dat = array2xml($package);
    
    $response = ihttp_request('https://api.mch.weixin.qq.com/pay/orderquery', $dat);
    if (is_error($response)) {
      return $response;
    }
    $xml = @simplexml_load_string($response['content'], 'SimpleXMLElement', LIBXML_NOCDATA);
    

    if(($xml->trade_state)=='SUCCESS'){
     $output = json_decode(json_encode($xml), true);
     return $output;
    }
    else{
      return false;
    }
    
   } 
  
 

 //现金红包接口
   function send_xjhb($settings,$fromUser,$amount,$desc) {
   	  $Hour = date('G'); 	
   	  if (intval($Hour)<8){
   	    return send_qyfk($settings,$fromUser,$amount,$desc);
   	   }
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

//返回百度地址
//根据经纬度返回百度地址：http://api.map.baidu.com/geocoder/v2/?ak=qen1OGw9ddzoDQrTX35gote2&location=26.047125,119.330221&output=json
function getAddr($location){
	if (empty($location)){
       return false;
	}

	if (empty($location['x']) && empty($location['location_x'])){
       return false;
	}
    $loc="";
	if (!empty($location['location_x']) && !empty($location['location_y'])){
		$loc=$location['location_x'].",".$location['location_y'];
	}

	if (!empty($location['x']) && !empty($location['y'])){
       $loc=$location['x'].",".$location['y'];
    }

    if (empty($loc)){
      return false;
    }

    $url="http://api.map.baidu.com/geocoder/v2/?ak=qen1OGw9ddzoDQrTX35gote2&location=".$loc."&output=json";
 
    $ret=json_decode(file_get_contents($url),true);
 
    if ($ret['status']!=0) {
       WeUtility::logging("getAddr", "$url==>" . json_encode($ret)); 
      return false;
    }
  
    if (strpos($ret['result']['formatted_address'],$location['addr'])===false){
        WeUtility::logging("getAddr", "error==>" . json_encode($location)); 
      return false;
    } else {
      return true;
    }

}



//返回百度地址
//根据经纬度返回百度地址：http://api.map.baidu.com/geocoder/v2/?ak=qen1OGw9ddzoDQrTX35gote2&location=26.047125,119.330221&output=json
function getNewAddr($location){
	if (empty($location)){
       return false;
	}
	if (empty($location['location_y']) ||  empty($location['location_x'])){
       return false;
	}   
    $loc=$location['location_x'].",".$location['location_y']; 
    $url="http://api.map.baidu.com/geocoder/v2/?ak=qen1OGw9ddzoDQrTX35gote2&location=".$loc."&output=json";
    $ret=json_decode(file_get_contents($url),true);
    if ($ret['status']!=0) {
       WeUtility::logging("getAddr", "$url==>" . json_encode($ret)); 
      return false;
    }
    
    return $ret['result'];
  
  
}






    function sendImage($access_token, $obj) {
  	 load()->func('communication');
  	 $data = array(
       "touser"=>$obj["openid"],
       "msgtype"=>"image",
       "image"=>array("media_id"=>$obj["media_id"])
     );
     WeUtility::logging('sendImage start', json_encode($data));
     $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
     WeUtility::logging('sendResurl', $url);
    
     $ret=ihttp_request($url, json_encode($data));
     $content = @json_decode($ret['content'], true);
    
     WeUtility::logging('sendImage end', $content);
     return $content;
  }
  
  
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
 

