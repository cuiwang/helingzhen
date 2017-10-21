<?php  
   global $_W, $_GPC;
   $uniacid = $_W['uniacid'];
   if ($_GPC['code']=="authdeny" || empty($_GPC['code']))
   {
    exit("授权失败");
   }
  
  load()->func('communication');
  
  $settings=$this->module['config'];
  
  $appid=$settings['appid'];
  $secret=$settings['secret'];

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
  $access_token=$token['access_token'];
   $url=$_COOKIE["xoauthURL"];
  setcookie($this->modulename."_user_".$uniacid,json_encode($token), time()+3600*(24*5)); 
  header("location:$url");
  exit();
  