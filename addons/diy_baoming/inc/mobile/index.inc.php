<?php
require_once IA_ROOT . "/addons/".$this->modulename."/inc/common.php"; 
global $_W, $_GPC;

$op=!empty($_GPC['op'])?$_GPC['op']:"display";
$from_user=$_W['fans']['from_user'];
$settings=$this->module['config'];
$uniacid=$_W['uniacid'];
$modulename=$this->modulename;

if ($settings['more_activity']){
  message("无权限");
}

$userinfo=getFromUser($settings,$modulename);
$userinfo=json_decode($userinfo,true);

if (empty($userinfo['openid'])){
	message("没抓到用户信息，可能借用授权服务号没配置好，或者入口错误");
}




$from_user=$userinfo['openid'];
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$uniacid=$_W["uniacid"];

  	
if ($op=='display') { 		
  $pindex = max(1, intval($_GPC['page']));	
  $psize= 30;
  $con="uniacid=$uniacid";	     			  
  $con.=" and status=0";
  
  if(isset($_GPC['actType'])){
  	$con.=" and activity_type=".$_GPC['actType'];
  }
  	  	    	      
  $total=0; 
  $cgc_baoming_activity=new cgc_baoming_activity();
  $list = $cgc_baoming_activity->getAll($con, $pindex,$psize,$total); 
 
  $cgc_baoming_user=new cgc_baoming_user();
  $user_count=$cgc_baoming_user->selectByGroupUser();
  include $this->template('index'); 
}	



        
     