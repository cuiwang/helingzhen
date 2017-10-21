<?php
/**
 * 红包模块微站定义
 *
 * @author cgt_
 * @url http://bbs.weihezi.cc/
 */

defined('IN_IA') or exit('Access Denied');
define('MOBILE_URL','../addons/cgt_qyhb/template/mobile');
define('MB_ROOT', IA_ROOT . '/addons/cgt_qyhb');

class Cgt_qyhbModuleSite extends WeModuleSite {
	
	public function __construct() {
      global $_W;
    }
    
    //后台管理程序 web文件夹下
    public function __web($f_name){
        global $_W, $_GPC;
        checklogin();
        $weid = $_W['uniacid'];
        include_once 'web/' . strtolower(substr($f_name, 5)) . '.php';
    }
    
    
     //管理员账号
    public function dowebManage($f_name){
        $this->__web(__FUNCTION__);
    }
      
    //手机端程序 mobile文件夹下
    public function __mobile($f_name){
        global $_W, $_GPC;
          
        $weid = $_W['uniacid'];
      //  电脑测试加参数
	    $settings=$this->module['config'];
		
		if ($settings["end"]=="是"){
			header("location:".$settings["endurl"]);
			exit();
		}
		
		include_once 'mobile/ipfunction.php';
		if (!empty($settings["iplimit"])){
		  $ip=getip();
		  if (iplimit($ip,$settings["iplimit"])===false){
		  	 header("location:".$settings["endurl"]);
			 exit();
		  }
		}
	 
       
        if (!empty($_GPC['test'])){
          $fromUserJson=array("user_id"=>"testzz");
        } else {
          $fromUserJson=json_decode($this->getFromUser(),true); 
        }
        $fromUser=$fromUserJson["user_id"]; 
      
        include_once 'mobile/' . strtolower(substr($f_name, 8)) . '.php';
    }
    
    
    //红包首页
    public function doMobileEnter() {   	
    	$this->__mobile(__FUNCTION__);
    }
      
   
   //关注首页
    public function doMobileGuanzhu() {   	
    	$this->__mobile(__FUNCTION__);
    }
     
    
  //发放红包
  public function doMobileSendHb() {   	
    	$this->__mobile(__FUNCTION__);
  }
				
	
 private function getAppInfo(){
   global $_W;
   $settings=$this->module['config'];
   if (!empty($settings['appid']) && !empty($settings['secret'])){
  	 return array("appid"=>$settings['appid'],"appsecret"=>$settings['secret']);
   }  
  load()->model('account');
  $_W['account'] = account_fetch($_W['uniacid']);

  $appId =$_W['account']['key'];
  $appSecret =$_W['account']['secret'];

  if (!empty($appId) && !empty($appSecret)){
  	 return array("appid"=>$appId,"appsercret"=>$appSecret);
  } else { 	
  	 die("授权信息不存在");
  }
}	

private function getFromUserTest() 
{
  $obj=array("openid"=>"ooM9euMP3LBLtzutPcIkuqYQhwcw1",
  "nickname"=>"nick221","headimgurl"=>
  "http://wx.qlogo.cn/mmopen/bHACibrA8hAhnlNYETmRhdPTJiaKDCr7OvwoQ5y3oJKuDFD7iafDGWsmpVXCibjia30kc0bibkTU4xHKdrqXP1iaWkYxMmaOmFicHLza/0",
   );
  return json_encode($obj);
}


//授权获得用户个人信息，记入cookie
function setCookieUserInfo($info){
  $obj=array("user_id"=>$info['openid'],"user_name"=>$info['nickname'],"user_image"=>$info['headimgurl']);
  setcookie("fromUser",json_encode($obj),time()+3600*5);
  return json_encode($obj);
}
	
//授权获取用户信息。	
private function getFromUser() 
{
  global $_W,$_GPC;	
  if (empty($_COOKIE["wechatId"]) && !empty($_GPC["wechatId"])){
	//推荐人写入cookie
    setcookie("wechatId",$_GPC["wechatId"],time()+3600*(24*5));
  }
  //测试状态，不用了
  if (!empty($_GPC["test"])){
  	 return $this->getFromUserTest();
  }	


  if(empty($_COOKIE["fromUser"])){
     $url = $_W['siteroot'] . "app/index.php?i=".$_W['uniacid']."&j=".$_W['acid']."&c=entry&m=cgt_qyhb&do=xoauth";
     $appInfo=$this->getAppInfo();
     setcookie("xoauthURL", "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]", time()+3600*(24*5));
     $oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appInfo['appid']."&redirect_uri=".urlencode($url)."&response_type=code&scope=snsapi_userinfo&state=0#wechat_redirect";
     header("location:$oauth2_code");
     exit;                  
  } else { 	
  	return $_COOKIE["fromUser"];
  } 
}



public function doMobileXoauth() 
{
  global $_W,$_GPC;
  if ($_GPC['code']=="authdeny" || empty($_GPC['code']))
  {
    exit("授权失败");
  }
  
  load()->func('communication');
  
  $appInfo = $this->getAppInfo();
  $appid=$appInfo['appid'];
  $secret=$appInfo['appsecret'];
  $state = $_GPC['state'];
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

  $access_token = $token['access_token'];
  $oauth2_url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$from_user."&lang=zh_CN";
  $content = ihttp_get($oauth2_url);
  $info = @json_decode($content['content'], true);

  if(empty($info) || !is_array($info) || empty($info['openid']) || empty($info['nickname']) || empty($info['headimgurl']) ) 
  {
  	print_r($info);
    echo '<h1>11获取微信公众号授权失败[无法取得info], 请稍后重试！<h1>';
    exit;
  }
  $url=$_COOKIE["xoauthURL"];   
  $this->setCookieUserInfo($info);
  header("location:$url");
  exit();
 }

 
	 
public function doWebdownload() {
     require_once 'download.php';
 }
 
 public function domobileIndex1(){
 	global $_W;
 	  $pindex=1;
  $psize=20;
  $list=pdo_fetchall("select user_id,user_name,user_image,createtime from ".tablename("qyhb_user")." where uniacid=".$_W['uniacid'] .
" order by createtime desc limit ". ($pindex - 1) * $psize . ',' . $psize);

  include $this->template('index1');
 }
 //测试无用
 public function doMobileCookie() 
{
	  global $_W;

   	    $from_user=$_COOKIE["fromUser"];
   	    echo $from_user;
   	      //查询是否为关注用户
			load()->classs('weixin.account');
		    $accObj= WeixinAccount::create($_W['acid']);
		    $access_token = $accObj->fetch_token();
			load()->func('communication');
				echo " $access_token";
			$oauth2_code = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$from_user."&lang=zh_CN";
			$content = ihttp_get($oauth2_code);
			$token = @json_decode($content['content'], true);
			$follow = $token['subscribe'];
			print_r($token);
			echo "zz";
			if($follow==0){
	
            }
              
   	    
   	    
   	  	
			
			return;

 print_r($_COOKIE["fromUser"]);



 if (!empty($_COOKIE["fromUser"])){
 	 setcookie("fromUser","",time()-3600);
 	
 }

 
 exit("good");
}
 
 




}