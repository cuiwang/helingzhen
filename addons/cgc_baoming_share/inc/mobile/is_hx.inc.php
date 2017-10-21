<?php
require_once IA_ROOT . "/addons/" . $this->modulename . "/inc/common.php";
global $_W, $_GPC;
session_start();
$op = !empty ($_GPC['op']) ? $_GPC['op'] : "display";
$settings = $this->module['config'];
$uniacid = $_W['uniacid'];
$id = $_GPC['id'];
$modulename = $this->modulename;


$cgc_baoming_activity = new cgc_baoming_activity();
$activity = $cgc_baoming_activity->getOne($id);
$userinfo = getFromUser($settings, $modulename);
$userinfo = json_decode($userinfo, true);
$openid = $userinfo['openid'];

if ($op == "display") {
   if (empty ($userinfo['openid'])) {
		message("没抓到用户信息，可能借用授权服务号没配置好，或者入口错误");
	}

	
	if (empty ($id)) {
	  message("id不得为空");
	}
	
	if (!empty($_GPC['user_id'])){
	  $user = pdo_fetch("SELECT * FROM ". tablename("cgc_baoming_user") 
      ." WHERE uniacid=$uniacid and openid=:openid and activity_id=:activity_id and id=:user_id",
      array(':user_id'=>$_GPC['user_id'],':openid'=>$userinfo['openid'],':activity_id'=>$id));
	} else {
	  if($activity['activity_type']!=1){
	      $user = pdo_fetch("SELECT * FROM ". tablename("cgc_baoming_user") 
        ." WHERE uniacid=$uniacid and openid=:openid and activity_id=:activity_id and hx_status=0 and (zj_status=1 or is_pay=1) ",
        array(':openid'=>$userinfo['openid'],':activity_id'=>$id));
	  } else {
	      $user = pdo_fetch("SELECT * FROM ". tablename("cgc_baoming_user") 
         ." WHERE uniacid=$uniacid and openid=:openid and activity_id=:activity_id",
         array(':openid'=>$userinfo['openid'],':activity_id'=>$id));
	  }
	}
	
	 if(empty($user)){
     message("不存在记录!");
  }
   
	
  if(empty($user['share_status'])){
     message("分享状态不得为空!");
  }
   
	if($activity['activity_type']!=1 && empty($user['zj_status']) && empty($user['is_pay'])){
	  message("你未中奖或者未支付!");
	}
	
 	
    include $this->template('hx_page');
	exit ();
}

if ($op == "hx") {
	

 

$hx_pass=($_GPC['hx_pass']);

if ($_GPC['hx_pass']!=$activity['hx_pass'] || empty($activity['hx_pass'])){
 exit(json_encode(array("code"=>-2,"msg"=>"核销密码没有设置，或者不正确")));
}

$user=pdo_fetch("select *  from".tablename("cgc_baoming_user")."  where uniacid=$uniacid and activity_id=$id and openid='{$openid}' and id={$_GPC['user_id']}");




if (empty($user)){
  exit(json_encode(array("code"=>-1,"msg"=>"没发现此用户")));
} 

if (empty($user['share_status'])){
  exit(json_encode(array("code"=>-1,"msg"=>"分享状态为空")));
} 



if ($user['is_return']){
  exit(json_encode(array("code"=>-3,"msg"=>"处于退款流程，无法核销")));
}

if ($user['hx_status']==1){
  exit(json_encode(array("code"=>-1,"msg"=>"已经核销过了")));
} 

if (empty($id)){
  exit(json_encode(array("code"=>-3,"msg"=>"活动id不得为空")));
} 


$ret=pdo_query("update ".tablename("cgc_baoming_user")." set hx_status=1 where uniacid=$uniacid and activity_id=$id and id='{$_GPC['user_id']}'");

if ($ret==false){
  exit(json_encode(array("code"=>-2,"msg"=>"核销失败")));
} else {
  exit(json_encode(array("code"=>0,"msg"=>"核销成功")));
}
	
	
}
