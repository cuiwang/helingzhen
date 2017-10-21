<?php

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


function send_ali_sms($settings,$tel,$code,$product){
	include IA_ROOT."/addons/cgc_ad/source/ali_sms/TopSdk.php";
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


/* 获取图片的url访问地址 $driver:默认采用第三方图片服务，local(本地图片) */
	function VP_IMAGE_URL($path, $style = 'm', $dir = '', $driver = '')
	{
		global $_W;
		return tomedia($path);
		//|| !empty($_W['setting']['remote']['type'])
		if ('local' == $driver) {
			return $_W['attachurl'] . $path;
		} else {
			return 'http://' . $_W['module_setting']['qn_api'] . '/' . $path . '-' . $style;
		}
	}

	function judge_forbidden_addr($ipforbidden) {
	  global $_W, $_GPC;
	  $ret=false;
	  $ip=getip();
	  $city=getIPLoc_sina($ip) ;
	  if (!empty ($ipforbidden)) {
	    $ipforbidden = explode("|", $ipforbidden);
		foreach ($ipforbidden as $value) {
		  $value = str_replace("市", "", $value);
		  $value = str_replace("省", "", $value);
		  $pos = strexists($city, $value);
		  if ($pos == true) {
		    $ret = true;
		    break;
		  }
		}
	   }
       return  $ret;
	}

	function getIPLoc_sina($queryIP, & $city = '') {

		$url = 'http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip=' . $queryIP;
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_ENCODING, 'utf8');
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 获取数据返回  
		$location = curl_exec($ch);
		$location = json_decode($location);
		curl_close($ch);
		$loc = "";
		if ($location === FALSE)
			return "";
		if (empty ($location->desc)) {
			$loc = $location->country .$location->province . $location->city . $location->district;
		} else {
			$loc = $location->desc;
		}
		$city = $location->city;
		return $loc;

	}

  //增加粉丝积分
  function add_fans_score($obj){
  	load()->model('mc');
    $openid=$obj['openid'];  
    $uid=mc_openid2uid($openid);		
	mc_credit_update($uid,"credit1",$obj['credit1'],array($uid,"广告圈")); 	
	$data=mc_credit_fetch($uid);
	return $data['credit1'];
  }
  
 //增加粉丝余额
  function add_fans_money($obj){
  	load()->model('mc');
    $openid=$obj['openid'];  
    $uid=mc_openid2uid($openid);		
	mc_credit_update($uid,"credit2",$obj['credit2'],array($uid,"广告圈"));	
	$data=mc_credit_fetch($uid);
	return $data['credit2'];
  }
  

 function getCardTicket($access_token){
 	    global $_W;
 	    $acid=$_W['acid'];
		$cachekey = "wx_card_jsticket:$acid";
		$cache = cache_load($cachekey);
		if(!empty($cache) && !empty($cache['ticket']) && $cache['expire'] > TIMESTAMP) {
			return $cache['ticket'];
		}
	
		$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$access_token}&type=wx_card";
		$content = ihttp_get($url);
		if(is_error($content)) {
			return error(-1, '调用接口获取微信公众号 jsapi_ticket 失败, 错误信息: ' . $content['message']);
		}
		$result = @json_decode($content['content'], true);
		if(empty($result) || intval(($result['errcode'])) != 0 || $result['errmsg'] != 'ok') {
			return error(-1, '获取微信公众号 jsapi_ticket 结果错误, 错误信息: ' . $result['errmsg']);
		}
		$record = array();
		$record['ticket'] = $result['ticket'];
		$record['expire'] = TIMESTAMP + $result['expires_in'] - 200;
		
		cache_write($cachekey, $record);
		return $record['ticket'];
	}


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


//撒红包提成
function send_red_commission($member,$quan,$adv,$config=array()){
	global $_W,$_GPC;
  	$weid=$_W['uniacid'];
  	$quan_id=$quan['id'];
  	$id=$adv['id'];
  	$mid=$member['id']; 
  	$up_money=0;
  	
  	$send_money = $adv['total_amount'];
  	
  	// 计算上级提成
	if($quan['up_send_fee']>0 && $member['inviter_id']>0){
	  	
	    $up_money=$send_money*(($quan['up_send_fee'])/100);
	    
		if($up_money>0 && $up_money<$send_money){
		  	$get_money=$send_money-$up_money;
		}else{
		 	$up_money=0;
		}
	}
	
	// 如果当前开启上级提成，并且我有上级，则需要记录上级提成记录
  	if($quan['up_send_fee']>0 && $member['inviter_id']>0 && $up_money>0){
    	$inviter_member=pdo_fetch("SELECT * FROM ".tablename('cgc_ad_member')." WHERE weid=".$weid." AND quan_id=".$quan_id." AND id=".$member['inviter_id']);
    	
    	$data=array(
		    'weid'=>$weid,
		    'quan_id'=>$quan_id,
		    'advid'=>$id,
		    'mid'=>$inviter_member['id'],
		    'openid'=> $inviter_member['openid'],
		    'nickname'=> $inviter_member['nickname'],
		    'headimgurl'=> $inviter_member['headimgurl'],
		    'money'=>$up_money,
		    'money_before'=>$inviter_member['credit'],
		    'money_after'=>$inviter_member['credit']+$up_money,
		    'channel'=>2,
		    'remark'=>'撒钱提成',
		    'create_time'=>TIMESTAMP,
	   	);
	   	
	   	$ret1=pdo_insert("cgc_ad_money_log",$data);
		if(false===$ret1){
			return array("code"=>0,"msg"=>'插入金额变动日志失败');
		}
    		$ret2=pdo_update("cgc_ad_member",array('rob'=>$inviter_member['rob']+$up_money,'credit'=>$inviter_member['credit']+$up_money),array('id'=>$member['inviter_id']));
    		
    	$ret3=pdo_update("cgc_ad_member",array('up_money'=>$member['up_money']+$up_money),array('id'=>$mid));
		//提成信息
		send_fc($member,$inviter_member,$quan,$up_money,$config); 
  	}
  
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
   if(false===$ret1){
      return array("code"=>0,"msg"=>'插入红包表失败');
   }
  
 														  
   $cgc_ad_task=new cgc_ad_task(); 
   $cgc_ad_task->modify($task['id'],array("status"=>1,"money"=>$get_money));
			
  // 如果当前开启上级提成，并且我有上级，则需要记录上级提成记录
  if($quan['up_rob_fee']>0 && $member['inviter_id']>0 && $up_money>0){
    $inviter_member=pdo_fetch("SELECT * FROM ".tablename('cgc_ad_member')." WHERE weid=".$weid." AND quan_id=".$quan_id." AND id=".$member['inviter_id']);
    $ret3=pdo_update("cgc_ad_member",array('rob'=>$inviter_member['rob']+$up_money,'credit'=>$inviter_member['credit']+$up_money),array('id'=>$member['inviter_id']));
    
	//提成信息
	send_fc($member,$inviter_member,$quan,$up_money,$config); 
  }
	

  if(($adv['rob_users']+1)==$adv['total_num']){
    pdo_update("cgc_ad_adv",array('rob_amount'=>($adv['rob_amount']+$rob_money),'rob_users'=>($adv['rob_users']+1),'rob_end_time'=>TIMESTAMP,'rob_status'=>1),array('id'=>$adv['id']));
    if (empty($adv['allocation_way'])){
  /*    $max_id=pdo_fetchcolumn("SELECT max(money) FROM ".tablename('cgc_ad_red')." WHERE weid=".$weid." AND quan_id=".$quan_id." AND advid=".$id);
      pdo_update("cgc_ad_red",array('is_luck'=>1),array('money'=>$max_id));*/
    }

  }  else
  {
     pdo_update("cgc_ad_adv",array('rob_amount'=>($adv['rob_amount']+$rob_money),'rob_users'=>($adv['rob_users']+1)),array('id'=>$adv['id']));
  }	
  
    
    //怕前面太卡出问题了，红包被多抢，这里在判断一次 ，保险起见 
    $adv=pdo_fetch("SELECT * FROM ".tablename('cgc_ad_adv')." where weid=$weid and id=$id and del=0");   
    if(empty($adv) || $adv['rob_users']>$adv['total_num']){
     return array("code"=>0,"msg"=>'手慢了,钱被抢光啊');
    }
      
    if (empty($adv['fl_type'])){
      $ret2=pdo_update("cgc_ad_member",array('up_money'=>$member['up_money']+$up_money,'rob'=>$member['rob']+$get_money,'credit'=>$member['credit']+$get_money,'fstatus'=>'1','rob_next_time'=>$rob_next_time),array('id'=>$mid));
    } else if ($adv['fl_type']==1){
      $ret2=pdo_update("cgc_ad_member",array('credit1'=>$member['credit1']+$get_money,'rob_next_time'=>$rob_next_time),array('id'=>$mid));
      $obj=array("credit1"=>$get_money,"openid"=>$member['openid']);
      $ret2=add_fans_score($obj);
    } else if ($adv['fl_type']==2){
      $ret2=pdo_update("cgc_ad_member",array('credit2'=>$member['credit2']+$get_money,'rob_next_time'=>$rob_next_time),array('id'=>$mid));
      $obj=array("credit2"=>$get_money,"openid"=>$member['openid']);
      $ret2=add_fans_money($obj);
    }
    
    if(false===$ret2){
      return array("code"=>0,"msg"=>'好可惜~没抢到');
    }
   
    return array("code"=>1,'get_money'=>$get_money,"msg"=>'抢到'.($rob_money).$config['unit_text'].'！','data'=>array(
    'rob_money'=>$rob_money,'last_num'=>$adv['total_num']-$rob_index-1));

}


function qr_task_cal_red($member,$quan,$adv,$config=array(),$task){
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
   if(false===$ret1){
      return array("code"=>0,"msg"=>'插入红包表失败');
   }
  
 														  
   $cgc_ad_qr_task=new cgc_ad_qr_task(); 
   $cgc_ad_qr_task->modify($task['id'],array("status"=>1,"money"=>$get_money));
			
  // 如果当前开启上级提成，并且我有上级，则需要记录上级提成记录
  if($quan['up_rob_fee']>0 && $member['inviter_id']>0 && $up_money>0){
    $inviter_member=pdo_fetch("SELECT * FROM ".tablename('cgc_ad_member')." WHERE weid=".$weid." AND quan_id=".$quan_id." AND id=".$member['inviter_id']);
    $ret3=pdo_update("cgc_ad_member",array('rob'=>$inviter_member['rob']+$up_money,'credit'=>$inviter_member['credit']+$up_money),array('id'=>$member['inviter_id']));
    
	//提成信息
	send_fc($member,$inviter_member,$quan,$up_money,$config); 
  }
	

  if(($adv['rob_users']+1)==$adv['total_num']){
    pdo_update("cgc_ad_adv",array('rob_amount'=>($adv['rob_amount']+$rob_money),'rob_users'=>($adv['rob_users']+1),'rob_end_time'=>TIMESTAMP,'rob_status'=>1),array('id'=>$adv['id']));
    if (empty($adv['allocation_way'])){
 /*     $max_id=pdo_fetchcolumn("SELECT max(money) FROM ".tablename('cgc_ad_red')." WHERE weid=".$weid." AND quan_id=".$quan_id." AND advid=".$id);
      pdo_update("cgc_ad_red",array('is_luck'=>1),array('money'=>$max_id));*/
    }

  }  else
  {
     pdo_update("cgc_ad_adv",array('rob_amount'=>($adv['rob_amount']+$rob_money),'rob_users'=>($adv['rob_users']+1)),array('id'=>$adv['id']));
  }	
  
    
    //怕前面太卡出问题了，红包被多抢，这里在判断一次 ，保险起见 
    $adv=pdo_fetch("SELECT * FROM ".tablename('cgc_ad_adv')." where weid=$weid and id=$id and del=0");   
    if(empty($adv) || $adv['rob_users']>$adv['total_num']){
     return array("code"=>0,"msg"=>'手慢了,钱被抢光啊');
    }
      
    if (empty($adv['fl_type'])){
      $ret2=pdo_update("cgc_ad_member",array('up_money'=>$member['up_money']+$up_money,'rob'=>$member['rob']+$get_money,'credit'=>$member['credit']+$get_money,'fstatus'=>'1','rob_next_time'=>$rob_next_time),array('id'=>$mid));
    } else if ($adv['fl_type']==1){
      $ret2=pdo_update("cgc_ad_member",array('credit1'=>$member['credit1']+$get_money,'rob_next_time'=>$rob_next_time),array('id'=>$mid));
      $obj=array("credit1"=>$get_money,"openid"=>$member['openid']);
      $ret2=add_fans_score($obj);
    } else if ($adv['fl_type']==2){
      $ret2=pdo_update("cgc_ad_member",array('credit2'=>$member['credit2']+$get_money,'rob_next_time'=>$rob_next_time),array('id'=>$mid));
      $obj=array("credit2"=>$get_money,"openid"=>$member['openid']);
      $ret2=add_fans_money($obj);
    }
    
    if(false===$ret2){
      return array("code"=>0,"msg"=>'好可惜~没抢到');
    }
   
    return array("code"=>1,'get_money'=>$get_money,"msg"=>'抢到'.($rob_money).$config['unit_text'].'！','data'=>array(
    'rob_money'=>$rob_money,'last_num'=>$adv['total_num']-$rob_index-1));

}


// 是否有资格抢
function fkz_yun_money($quan,$member,$adv){
  global $_W,$_GPC;
  if ($quan['yun_fkz']==1){
    if (!empty( $item['yun_rule'])){
  	  $item['yun_rule']=unserialize($item['yun_rule']);
  	  foreach($item['yun_rule'] as $value){
  	    if ($value['member_level']==$member['yun_level']){
  	      if ($adv['total_amount']>$value['rob_money']){
  	        return false;
  	      }
  	    }
  	  }
	 }
   }
   
   return true;
}





function auth_red($member,$quan,$adv,$config=array()){
  global $_W,$_GPC;

  
  $ret=fkz_yun_money($quan,$member,$adv);
  
  if (!$ret){
    return array("code"=>0,"msg"=>"会员等级不够，无法抢此级别的红包");
  }

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
  $member['last_city']=str_replace("市", "", $member['last_city']);  	
  $member['last_city']=str_replace("县", "", $member['last_city']);  
						
  if(empty($quan['city'])){
     $temp_city=1;
   } else {
   	$quan['city']=str_replace("市", "", $quan['city']);  
   	$quan['city']=str_replace("县", "", $quan['city']); 
   	$quan['city']=str_replace("或", "|", $quan['city']); 

	$city_arr=explode('|', $quan['city']);   
    if (empty($member['last_city'])){
  	  return array("code"=>0,"msg"=>$member['openid'].'没有抓到你的地区信息');
  }
    

    $member_city_arr=explode('|', $member['last_city']);
    
    foreach ($member_city_arr as $value) {
	  if(in_array($value, $city_arr)){
         $temp_city=1;
	     break;
      }
   }
  }
  
  if ($temp_city){
    if (empty($adv['city'])){
       $temp_city=1;
     } else {
      $temp_city=0;
   	  $adv['city']=str_replace("市", "", $adv['city']);  
   	  $adv['city']=str_replace("或", "|", $adv['city']); 
   	  $adv['city']=str_replace("县", "", $adv['city']);	
	  $city_arr=explode('|', $adv['city']);   
      if (empty($member['last_city'])){
  	    return array("code"=>0,"msg"=>$member['openid'].'没有抓到你的地区信息');
      }  
      $member_city_arr=explode('|', $member['last_city']);    
      foreach ($member_city_arr as $value) {
	    if(in_array($value, $city_arr)){
          $temp_city=1;
	      break;
       }
     }
    }
  }

  if (empty($temp_city)){
  	return array("code"=>0,"msg"=> $member['last_city'].'只有'.$quan['city'].$adv['city']."范围内才能抢哦~");
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

 	return array("code"=>1);	

}

function cal_red($member,$quan,$adv,$config=array()){
  global $_W,$_GPC;
  $weid=$_W['uniacid'];
  $quan_id=$quan['id'];
  $id=$adv['id'];
  $mid=$member['id'];  
  $openid=$member['id']; 
  if (empty($_GPC['rob_token']) || $_SESSION['rob_token']!=$_GPC['rob_token']){
 //   return array("code"=>0,"msg"=>$_GPC['rob_token'].'非法提交，请重新进入本页面'.$_SESSION['rob_token']); 
  }
    
  $_SESSION['rob_token']="";
   
  $ret=fkz_yun_money($quan,$member,$adv);
  if (!$ret){
    return array("code"=>0,"msg"=>"会员等级不够，无法抢此级别的红包");
  }

   //判断地区
  $ret=judge_addr($member,$quan,$adv,$config);
  
  if ($ret['code']!=1){
    return $ret;
  }
 
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
   
  if(empty($rob_money) || $rob_money<=0 || $rob_money>$adv['total_amount']){
    return array("code"=>0,"msg"=>'哎呀~没抢到');
  }
  
  //必须关注才可以抢 
 if($quan['is_follow']==1){
   if($member['follow']==0){
     return array("code"=>0,"msg"=>'must_guanzhu',"url"=>$quan['follow_url']);
    }
  }

  $my=pdo_fetch("SELECT * FROM ".tablename('cgc_ad_red')." WHERE weid=".$weid." AND quan_id=".$quan_id." AND advid=".$id." AND mid=".$mid);
    	 	 	
  if(!empty($my)){
    return array("code"=>0,"msg"=>'本次您已经抢过了，不能重复抢'); 
  }
  
  $ip_num_limit = intval($quan['ip_num_limit']);//ip限制次数
  if($ip_num_limit>0){
  		$total_ip = 0;
  		if($adv['model']=='8'){
  			$total_ip=pdo_fetchcolumn("SELECT count(id) FROM ".tablename('cgc_ad_couponc')." WHERE weid=".$weid." AND quan_id=".$quan_id." AND advid=".$id." AND ip='".getip()."'");
  		}else{
  			$total_ip=pdo_fetchcolumn("SELECT count(id) FROM ".tablename('cgc_ad_red')." WHERE weid=".$weid." AND quan_id=".$quan_id." AND advid=".$id." AND ip='".getip()."'");
  		}
   		
   		if($total_ip>=$ip_num_limit){
   			return array("code"=>0,"msg"=>'同一IP已达抢钱上限，不能再抢');
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
  
   if (empty($member['vip_id'])){
     $member['vip_rob']=$quan['regular_user_momeny'];
   }
  
   if ($quan['vip_valid'] &&  $member['rob']>$quan['regular_user_momeny'] && ($member['vip_rob']<$member['rob']+$get_money)){
   	
   	 $info="你的已抢金额是:".($member['rob']+$get_money)."已经超过了可抢金额:".$member['vip_rob'].",点击确定充值。";
   	 return array("code"=>0,"msg"=>"vip",'data'=>$info);      
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
    'ip'=>getip(),
   );
  
   pdo_insert("cgc_ad_red",$data);
   
  if(($adv['rob_users']+1)==$adv['total_num']){
    pdo_update("cgc_ad_adv",array('rob_amount'=>($adv['rob_amount']+$rob_money),'rob_users'=>($adv['rob_users']+1),'rob_end_time'=>TIMESTAMP,'rob_status'=>1),array('id'=>$adv['id']));
    if (empty($adv['allocation_way'])){
/*      $max_id=pdo_fetchcolumn("SELECT max(money) FROM ".tablename('cgc_ad_red')." WHERE weid=".$weid." AND quan_id=".$quan_id." AND advid=".$id); 
      pdo_update("cgc_ad_red",array('is_luck'=>1),array('money'=>$max_id));*/
    }    
  }  else
  {
    pdo_update("cgc_ad_adv",array('rob_amount'=>($adv['rob_amount']+$rob_money),'rob_users'=>($adv['rob_users']+1)),array('id'=>$adv['id']));
  }	
  
			
  // 如果当前开启上级提成，并且我有上级，则需要记录上级提成记录
  if($quan['up_rob_fee']>0 && $member['inviter_id']>0 && $up_money>0){ 	
    $inviter_member=pdo_fetch("SELECT * FROM ".tablename('cgc_ad_member')." WHERE weid=".$weid." AND quan_id=".$quan_id." AND id=".$member['inviter_id']);
    if (empty($adv['fl_type'])){
       $ret3=pdo_update("cgc_ad_member",array('rob'=>$inviter_member['rob']+$up_money,'credit'=>$inviter_member['credit']+$up_money),array('id'=>$member['inviter_id']));
    } else if ($adv['fl_type']==1){
       $ret3=pdo_update("cgc_ad_member",array('rob'=>$inviter_member['credit1']+$up_money),array('id'=>$member['inviter_id']));
    }else if ($adv['fl_type']==2){
      $ret3=pdo_update("cgc_ad_member",array('rob'=>$inviter_member['credit2']+$up_money),array('id'=>$member['inviter_id']));
    }   
    //提成信息
    send_fc($member,$inviter_member,$quan,$up_money,$config);
  }
	 
  //怕前面太卡出问题了，红包被多抢，这里在判断一次 ，保险起见 
  $adv=pdo_fetch("SELECT * FROM ".tablename('cgc_ad_adv')." where weid=$weid and id=$id and del=0");
  
  if(empty($adv) || $adv['rob_users']>$adv['total_num']){
   return array("code"=>0,"msg"=>'手慢了,钱被抢光啊');
  }
     
    
    $msg='抢到'.($rob_money).$config['unit_text'].'！'; 
      
   if (empty($adv['fl_type'])){
      $ret2=pdo_update("cgc_ad_member",array('up_money'=>$member['up_money']+$up_money,'rob'=>$member['rob']+$get_money,'credit'=>$member['credit']+$get_money,'fstatus'=>'1','rob_next_time'=>$rob_next_time),array('id'=>$mid));
    } else if ($adv['fl_type']==1){
      $config['unit_text']="积分";
      $obj=array("credit1"=>$get_money,"openid"=>$member['openid']);
      $ret2=pdo_update("cgc_ad_member",array('credit1'=>$member['credit1']+$get_money,'rob_next_time'=>$rob_next_time),array('id'=>$mid));
      $ret2=add_fans_score($obj);
       $msg='抢到'.($rob_money).$config['unit_text'].".当前积分:".$ret2.'！'; 
    } else if ($adv['fl_type']==2){
      $config['unit_text']="余额";
      $ret2=pdo_update("cgc_ad_member",array('credit2'=>$member['credit2']+$get_money,'rob_next_time'=>$rob_next_time),array('id'=>$mid));
      $obj=array("credit2"=>$get_money,"openid"=>$member['openid']);
      $ret2=add_fans_money($obj);
      $msg='抢到'.($rob_money).$config['unit_text'].".当前余额:".$ret2.'！'; 
    }
    
  // $ret2=pdo_update("cgc_ad_member",array('up_money'=>$member['up_money']+$up_money,'rob'=>$member['rob']+$get_money,'credit'=>$member['credit']+$get_money,'fstatus'=>'1','rob_next_time'=>$rob_next_time),array('id'=>$mid));
   
   if(false===$ret2){
     return array("code"=>0,"msg"=>'好可惜~没抢到');
   }
  
  
   return array("code"=>1,"msg"=>$msg,'data'=>array(
    'rob_money'=>$rob_money,'last_num'=>$adv['total_num']-$rob_index-1)
  );

}

  //判断地区
  function judge_addr($member,$quan,$adv,$config=array()){
     $temp_city=0;		
 
  $member['last_city']=str_replace("市", "", $member['last_city']);  	
  $member['last_city']=str_replace("县", "", $member['last_city']); 
  
   $msg="";
 					
  if(empty($quan['city'])){
     $temp_city=1;
   } else {
   	$quan['city']=str_replace("市", "", $quan['city']);  
   	$quan['city']=str_replace("或", "|", $quan['city']); 
   	$quan['city']=str_replace("县", "", $quan['city']);   	 	 
    if (empty($member['last_city'])){
  	  return array("code"=>0,"msg"=>$member['openid'].'没有抓到你的地区信息');
    } 
     
    $member_city_arr=explode('|', $member['last_city']);    
    foreach ($member_city_arr as $value) {
	  if(strexists($value, $quan['city']) || strexists($quan['city'], $value)){
         $temp_city=1;
	     break;
      }
   }
  }
  
   if ($temp_city){
    if (empty($adv['city'])){
       $temp_city=1;
     } else {
      $temp_city=0;
   	  $adv['city']=str_replace("市", "", $adv['city']);  
   	  $adv['city']=str_replace("县", "", $adv['city']);	
	  $city_arr=explode(',', $adv['city']); 
	  //上传的城市居然有点小问题。 
	  $city=trim($city_arr[count($city_arr)-1]);
     // $msg=".广告地区:".$city."||用户地区:".$member['last_city'];
      if (empty($member['last_city'])){
  	    return array("code"=>0,"msg"=>$member['openid'].'没有抓到你的地区信息1');
      }
       if(strexists($member['last_city'], $city)){
        $temp_city=1;
	  }

   }
  }


	 

    if (empty($temp_city)){
  	 return array("code"=>0,"msg"=>"你不在活动范围".$msg);
    } 
   	return array("code"=>1);
  }
	
// 判断地区
// 先判断本广告,再判断平台
function judge_addr2($member, $quan, $adv, $config = array()) {
	$temp_city = 0;
	
	$member ['last_city'] = str_replace ( "市", "", $member ['last_city'] );
	$member ['last_city'] = str_replace ( "县", "", $member ['last_city'] );
	
	$msg = "";
	
	if (empty ( $adv ['city'] )) {
		$temp_city = 1;
	} else {
		$temp_city = 0;
		$adv ['city'] = str_replace ( "市", "", $adv ['city'] );
		$adv ['city'] = str_replace ( "县", "", $adv ['city'] );
		$city_arr = explode ( ',', $adv ['city'] );
		// 上传的城市居然有点小问题。
		$city = trim ( $city_arr [count ( $city_arr ) - 1] );
		// $msg=".广告地区:".$city."||用户地区:".$member['last_city'];
		if (empty ( $member ['last_city'] )) {
			return array (
					"code" => 0,
					"msg" => $member ['openid'] . '没有抓到你的地区信息1'
			);
		}
		if (strexists ( $member ['last_city'], $city )) {
			return array ("code" => 1);
		}
	}
	
	if ($temp_city) {
		if (empty ( $quan ['city'] )) {
			$temp_city = 1;
		} else {
			$temp_city = 0;
			$quan ['city'] = str_replace ( "市", "", $quan ['city'] );
			$quan ['city'] = str_replace ( "或", "|", $quan ['city'] );
			$quan ['city'] = str_replace ( "县", "", $quan ['city'] );
			if (empty ( $member ['last_city'] )) {
				return array (
						"code" => 0,
						"msg" => $member ['openid'] . '没有抓到你的地区信息'
				);
			}
		
			$member_city_arr = explode ( '|', $member ['last_city'] );
			foreach ( $member_city_arr as $value ) {
				if (strexists ( $value, $quan ['city'] ) || strexists ( $quan ['city'], $value )) {
					$temp_city = 1;
					break;
				}
			}
		}
	}
	
	if (empty ( $temp_city )) {
		return array (
				"code" => 0,
				"msg" => "你不在活动范围" . $msg 
		);
	}
	return array ("code" => 1);
}
  

  //发送分成消息
  function send_fc($member,$inviter_member,$quan,$up_money,$config=array()){
  	global $_W,$_GPC;
  	$msg="你收到小弟".$member['nickname']."的上供". $up_money."元";
    if($config['is_type']==1){
  	   $_tdata = array(
				'first'=>array('value'=>'收到提成！','color'=>'#173177'),
				'keyword1'=>array('value'=>'收到提成','color'=>'#173177'),
				'keyword2'=>array('value'=>$msg,'color'=>'#173177'),
				'remark'=>array('value'=>'请查看提成内容。','color'=>'#173177'),
		);
		//$_url= murl('entry', array('m' => 'cgc_ad', 'quan_id'=>$quan['id'],'op'=>'down','do'=>'geren'),true,true);
		
		$_url= murl('entry', array('m' => 'cgc_ad', 'quan_id'=>$quan['id'],'op'=>'down','form'=>'my','do'=>'foo'),true,true);
		$a = sendTemplate_common($inviter_member['openid'],$config['percentage_template_id'],$_url,$_tdata);
    } else {
     //   $a = post_send_text($inviter_member['openid'],$msg);
    }
  }
  
  
   //发送阅读消息
  function send_read_fc($member,$inviter_member,$quan,$up_money,$config=array()){
  	global $_W,$_GPC;
  	$msg=$member['nickname']."点击了你的链接，你获得了". $up_money."元";
    if($config['is_type']==1){
  	   $_tdata = array(
				'first'=>array('value'=>'收到提成！','color'=>'#173177'),
				'keyword1'=>array('value'=>'收到提成！','color'=>'#173177'),
				'keyword2'=>array('value'=>$msg,'color'=>'#173177'),
				'remark'=>array('value'=>'请点击查看。','color'=>'#173177'),
		);
		//$_url= murl('entry', array('m' => 'cgc_ad', 'quan_id'=>$quan['id'],'do'=>'geren'),true,true);
		$_url= murl('entry', array('m' => 'cgc_ad', 'quan_id'=>$quan['id'],'form'=>'geren','do'=>'foo'),true,true);
		$a = sendTemplate_common($inviter_member['openid'],$config['percentage_template_id'],$_url,$_tdata);
    } else {
      //  $a = post_send_text($inviter_member['openid'],$msg);
    }
  }
  //发送小弟消息
  function send_xd($member,$quan,$config=array(),$_url){
  	global $_W,$_GPC;
  	if(empty($config['recruit_template_id']) || empty($member['inviter_id'])){
  	  return;
  	}
  	$weid=$_W['uniacid'];
  	$inviter_member=pdo_fetch("SELECT * FROM ".tablename('cgc_ad_member')." WHERE weid=".$weid." AND quan_id=".$quan['id']." AND id=".$member['inviter_id']);
       //招小弟信息
    	$msg="你有一个新小弟 :". $member['nickname'];
    	$_url= murl('entry', array('m' => 'cgc_ad', 'quan_id'=>$quan['id'],'op'=>'down','form'=>'geren','do'=>'foo'),true,true);
    	//if($config['recruit_template_id']==1){
	  	   	$_tdata = array(
					'first'=>array('value'=>'招募小弟！','color'=>'#173177'),
					'keyword1'=>array('value'=>'招募小弟','color'=>'#173177'),
					'keyword2'=>array('value'=>$msg,'color'=>'#173177'),
					'remark'=>array('value'=>'请查看小弟信息。','color'=>'#173177'),
			);
			$a = sendTemplate_common($inviter_member['openid'],$config['recruit_template_id'],$_url,$_tdata);
	   /* } else {
	        $a = post_send_text($inviter_member['openid'],$msg);
	    }*/
  }

  
  //企业付款接口
   function send_qyfk($settings,$fromUser,$amount){
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
           /* $procResult = $resp['message'];
            $ret['code']=-1;
            $ret['message']="-1:".$procResult;*/
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
                    $procResult = error(-2, $ret['message']);
                    return $procResult;
                 }
            } else {
                $ret['code']=-3;
                $ret['message']="error response";
                $procResult = error(-3, $ret['message']);
                return $procResult;
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
  global $_W,$_GPC;	
  $obj=array("openid"=>"test1",
  "nickname"=>"测试或者全网用户","headimgurl"=>
  "http://wx.qlogo.cn/mmopen/bHACibrA8hAhnlNYETmRhdPTJiaKDCr7OvwoQ5y3oJKuDFD7iafDGWsmpVXCibjia30kc0bibkTU4xHKdrqXP1iaWkYxMmaOmFicHLza/0");
  return json_encode($obj);
}
 

 


 //授权获取用户信息。	
function getFromUser($settings,$modulename,$sys_settings) 
{
  global $_W,$_GPC;	
  $uniacid = $_W['uniacid'];

  if((!empty($_GPC['test11'])) || ($_W['container']!="wechat" && !empty($sys_settings['all_net']))){ 
  	$userinfo=getFromUserTest(); 
  	setcookie($modulename."_user_".$uniacid,$userinfo, time()+3600);
    return $userinfo;
  }	
  
  
  
  if (!empty($_COOKIE[$modulename."_user_".$uniacid])){
    return $_COOKIE[$modulename."_user_".$uniacid];
  }
    
 
 
 if (empty($sys_settings['sys_oauth'])){
    load()->model('mc');
    $userinfo=mc_oauth_userinfo();  
    return json_encode($userinfo);
 
 }
 
  
/*  if(empty($_COOKIE[$modulename."_user_".$uniacid])){*/
     $url = $_W['siteroot'] . "app/index.php?i=".$_W['uniacid']."&j=".$_W['acid']."&c=entry&m=$modulename&do=xoauth";
     $xoauthURL="http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; 
     setcookie("xoauthURL",$xoauthURL, time()+3600*(24*5));
     $oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$settings['appid']."&redirect_uri=".urlencode($url)."&response_type=code&scope=snsapi_userinfo&state=0#wechat_redirect";
     header("location:$oauth2_code");
     exit;                  
 /* } else { 	
  	return $_COOKIE[$modulename."_user_".$uniacid];
  } */
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
  	$uniacid=$_W['uniacid'];
  	$openid=$_W['openid'];
   	$follow=pdo_fetchcolumn("SELECT follow FROM " . tablename('mc_mapping_fans').
          " where uniacid=$uniacid and openid='$openid'"); 
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

  function base64_url($data) {
    $find = array('+', '/');
    $replace = array('-', '_');
    return str_replace($find, $replace, base64_encode($data));
}
  
  
   function getMedia($serverId){
	   global $_W;	
	   $acid=!empty($_W['acid'])?$_W['acid']:$_W['uniacid'];
	   load()->func('file');
	   $path = "../attachment/images/cgc_ad/" . $acid . '/' . date("Ymd")."/";
	   if (!is_dir($path)) {
		 load()->func('file');
		 mkdirs($path);
	   }
        load()->classs('weixin.account');
        $accObj= WeixinAccount::create($acid);
        $token=$accObj->fetch_token();
    
        load()->func('communication');
		 $url="http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=$token&media_id=$serverId";
		 $resp = ihttp_request($url);
		if(is_error($resp)) {
		  
          return $resp['message'];
		 }
		 if ($resp['headers']['Content-Type']=="image/png"){
		 	$ftype="png";
		 } else if ($resp['headers']['Content-Type']=="image/jpg") {
		 	$ftype="jpg";
		 } else if ($resp['headers']['Content-Type']=="image/jpeg") {
		 	$ftype="jpeg";
		 } else if ($resp['headers']['Content-Type']=="audio/amr") {
		  	$ftype="amr";
		 }
		 $savePath=$path.time().rand(1,1000).".".$ftype;
		 $fp2=@fopen($savePath,'w');  
         fwrite($fp2,$resp['content']);  
         fclose($fp2); 
         return $savePath;      
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

 function time_diff($time) {
   $diff=time()-$time;  
   $times=array(  
	        '31536000'=>'年',  
	        '2592000'=>'月',  
	        '604800'=>'星期',  
	        '86400'=>'天',  
	        '3600'=>'小时',  
	        '60'=>'分钟',  
	        '1'=>'秒'  
	    );  
   foreach ($times as $k=>$v)    {  
     if (0!=$z=floor($diff/(int)$k)) {  
	   return $z.$v.'前';  
	  }
	 }
  }

function subtext($text, $length)
{
  if(mb_strlen($text, 'utf8') > $length) {
    return mb_substr($text, 0, $length, 'utf8').'...';
  } else {
   return $text;
  }
}
 //获得二级分类
 function gettwolevel($quan_id,$id){
   global $_W;
   $weid=$_W['uniacid'];  
   $con1 = " and parent_id=$id";  
   $type_list =pdo_fetchall("select id,parent_id,info_type_name value,info_type_pic ,info_type_color,info_type_icon,info_type_url from ".tablename('cgc_ad_info_type')." where quan_id=".$quan_id." and status=0 $con1");	
   return $type_list;
 }
