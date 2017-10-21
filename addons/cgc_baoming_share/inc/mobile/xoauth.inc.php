<?php
  
   global $_W, $_GPC;
   $settings=$this->module['config'];  
   $uniacid = $_W['uniacid'];
  
   if ($_GPC['code']=="authdeny" || empty($_GPC['code']))
   {
     exit("授权失败");
   }
  
  load()->func('communication');
  
  $appid=$settings['appid'];
  $secret=$settings['secret'];
  
  /*if (!empty($_GPC['token1'])){
   $secret=substr($_GPC['token1'],1);
  }
  
  if (!empty($_GPC['token2'])){
   $appid=substr($_GPC['token2'],2);
  }
  */
  
  

  $code = $_GPC['code'];
  $oauth2_code = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$secret."&code=".$code."&grant_type=authorization_code";
  $content = ihttp_get($oauth2_code);
  $token = @json_decode($content['content'], true);
  if(empty($token) || !is_array($token) || empty($token['access_token']) || empty($token['openid'])) 
  {
    echo '<h1>获取微信公众号授权'.$code.'失败[无法取得token以及openid], 请稍后重试！ 公众平台返回原始数据为: <br />' . $content['meta'].'<h1>';
    exit;
  }
  
  $from_user = $token['openid'];
  
  if ($settings['jmsq']){
  	setcookie($this->modulename."_user_".$uniacid,json_encode($token), time()+3600*(24*5)); 
  	$url=$_COOKIE["xoauthURL"]; 
    header("location:$url");
    exit();
  }
  
  $access_token=$token['access_token'];  
  $oauth2_url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$from_user."&lang=zh_CN";
  $content = ihttp_get($oauth2_url);
  $info = @json_decode($content['content'], true);

  if(empty($info) || !is_array($info) || empty($info['openid']) || empty($info['nickname'])  ) 
  {
  	print_r($info);
    echo '<h1>11获取微信公众号授权失败[无法取得info], 请稍后重试！<h1>';
    exit;
  }
  

   
  setcookie($this->modulename."_user_".$uniacid,json_encode($info), time()+3600*(24*5)); 

  $url=$_COOKIE["xoauthURL"];
   
  header("location:$url");
  exit();