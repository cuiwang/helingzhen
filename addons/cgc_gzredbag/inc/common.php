<?php


   // 修改用户状态
   function update_gz_user($money,$weid,$openid){
  	 load()->func('logging');

  	 $sql="select count(1) from ".tablename("gzredbag_user")."  where uniacid=".$weid." and openid='{$openid}'";
     $exist=pdo_fetchcolumn($sql);
     if (empty($exist)){
         $sql="INSERT INTO ".tablename("gzredbag_user").
          " (uniacid,openid, money,status,send_status,createtime)" .
          " VALUES ('{$weid}', '{$openid}',{$money},1,1,".TIMESTAMP.")";
          $temp=pdo_query($sql);        
       if ($temp==false) {          
        logging_run("update_user:".$temp."==".$sql); 
       } 
       return $temp;
    } 
   }


    //总次数改变
  function update_data($weid){
  	load()->func('logging');

    
    $totaldata=pdo_fetchcolumn("select count(1) from ".tablename("gzredbag_count")."  where uniacid=".$weid);  	
    if (empty($totaldata)){
      $temp=pdo_insert("gzredbag_count",
             array("uniacid"=>$weid,
                   "total_count"=>1, 
                   "createtime"=>TIMESTAMP
                  ));
          
    } else {
      $temp=pdo_query("update ".tablename("gzredbag_count").
           " set total_count=total_count+1,createtime=".TIMESTAMP." where uniacid=".$weid);
    
    } 
     
     return $temp;
   }
  
  
  	//是否可以领取红包
	 function web_sflq($settings,$openid){
	  global $_W;
      $ret=array();
	  $ret['code']=0;
      $ret['message']="success";
	  $curtime=date("Y-m-d H:i",time());
	  $starttime=date("Y-m-d H:i",$settings['starttime']);
	  $endtime=date("Y-m-d H:i",$settings['endtime']);

 
      $addr=$settings['addr'];

	  //时间判断
	  if (!empty($starttime) && !empty($endtime)){
	  	if ($starttime>$curtime){
	  	   $ret['code']=-1;
          $ret['message']="活动还没开始";
          return $ret;
	  	}
	  	
	  	if ($curtime>=$endtime){
	  	 $ret['code']=-2;
         $ret['message']="活动已经结束";
	  	}
	  }
	  
	   if (!empty($settings['start_hour'])   && !empty($settings['end_hour'])){
         $Hour = date('G');
         if ($settings['start_hour']>$Hour || $settings['end_hour']<=$Hour){
           return array("code"=>"-2","msg"=>"当前时间是:".$Hour.";活动时间为:".$settings['start_hour']."点到".$settings['end_hour']."点"); 
   	       }
         }
	  
	  //是否超过总次数
	   $total_count=pdo_fetchcolumn("select total_count from ".tablename('gzredbag_count').
      " where uniacid={$_W['uniacid']} ");
	   if ($total_count>=$settings['total_count']){
	  	 $ret['code']=-33;
         $ret['message']="超过设定总次数";
         return $ret;
	  	}
	  
	
	  //是否超过红包总金额
	  $total_money=pdo_fetchcolumn("select total_money from ".tablename('gzredbag_money')." where uniacid={$_W['uniacid']}");
	  if ($total_money>=round($settings['total_money']+$settings['amount'],2)){
	  	   $ret['code']=-3;
         $ret['message']="红包已经发放完毕";
         return $ret;
	  	}
	 
	  //是否领取过
	   $status=pdo_fetchcolumn("select status from ".tablename('gzredbag_user')." where uniacid={$_W['uniacid']} and openid='$openid'");
	   if (!empty($status)){
	  	   $ret['code']=-4;
         $ret['message']="你已经领取过红包";
         return $ret;
	  	}


     //地址 
     if (!empty($settings['addr'])){ 
         $message['time']=strtotime("-7 day",time());
         $message['uniacid']=$_W['uniacid'];
         $message['from_user']=$openid;

         $location=getLocation($message);
           if  (empty($location)){
           $ret['code']=-7;
           $ret['message']=empty($settings['addr_error'])?"打开地理定位":$settings['addr_error'];
           return $ret;
         }
         $location['addr']= $settings['addr'];
         $result=getAddr($location);
         if  ($result==false){
           $ret['code']=-8;
           $ret['message']=empty($settings['addr_error'])?"地区不符合":$settings['addr_error'];
           return $ret;
         }
    
      }  

      return $ret;
   }
  

  

  //是否关注
  function sfgz_user($fromuser){
  	global $_W;
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
  	  $uniacid=empty($_W['acid'])?$_W['uniacid']:$_W['acid'];    
      $uniacccount = WeAccount::create($uniacid); 
   	  $userinfo=$uniacccount->fansQueryInfo($from_user);
   	  return $userinfo;
     
    }  	
    
    
    
//主动文本回复消息，48小时之内
 function post_send_text($openid,$content) {
	    global $_W;	
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

//授权获取用户信息。	
 function getFromUser($settings,$modulename) 
{
  global $_W,$_GPC;	
  $uniacid = $_W['uniacid'];

 if ($_W['container']!="wechat"){
  	   return json_encode(array("openid"=>'jyopenid'));
  }	
  
  if(empty($_COOKIE[$modulename."_user_".$uniacid])){
     $url = $_W['siteroot'] . "app/index.php?i=".$_W['uniacid']."&j=".$_W['acid']."&c=entry&m=$modulename&do=xoauth";
     $xoauthURL="http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; 
     setcookie("xoauthURL",$xoauthURL, time()+3600*(24*5));
     $oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$settings['appid']."&redirect_uri=".urlencode($url)."&response_type=code&scope=snsapi_base&state=0#wechat_redirect";
     header("location:$oauth2_code");
     exit;                  
  } else { 	
  	return $_COOKIE[$modulename."_user_".$uniacid];
  } 
}
 

