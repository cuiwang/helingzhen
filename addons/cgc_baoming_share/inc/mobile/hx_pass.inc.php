<?php
global $_W, $_GPC;
$op = !empty ($_GPC['op']) ? $_GPC['op'] : "display";
$settings = $this->module['config'];
$uniacid = $_W['uniacid'];
$modulename = $this->modulename;
$userinfo=getFromUser($settings, $modulename);
$userinfo=json_decode($userinfo, true);


$from_user=$userinfo['openid'];
$user_id=intval($_GPC['user_id']);

if (empty($user_id)){
 exit(json_encode(array("code"=>-3,"msg"=>"user_id不得为空")));
}
 //exit(json_encode(array("code"=>-3,"msg"=>"user_id不得为空")));
$activity_id=intval($_GPC['activity_id']);


$cgc_baoming_activity=new cgc_baoming_activity();
  
if (!empty($activity_id)){
  $activity = $cgc_baoming_activity->getOne($activity_id); 
} 


$hx_pass=($_GPC['hx_pass']);

if ($_GPC['hx_pass']!=$activity['hx_pass'] || empty($activity['hx_pass'])){
 exit(json_encode(array("code"=>-2,"msg"=>"核销错误1")));
}

$user=pdo_fetch("select *  from ".tablename("cgc_baoming_user")."  where activity_id=$activity_id and id=$user_id and share_status=1");

if ($user==false){
  exit(json_encode(array("code"=>-1,"msg"=>"用户不符合条件,或者已经核销过了")));
} 


if ($user['hx_status']){
  exit(json_encode(array("code"=>-3,"msg"=>"已经核销")));
} 


if ($user['is_return']){
  exit(json_encode(array("code"=>-3,"msg"=>"处于退款流程，无法核销")));
} 


$ret=pdo_query("update ".tablename("cgc_baoming_user")." set hx_status=1 where id=$user_id");

if ($ret==false){
  exit(json_encode(array("code"=>-2,"msg"=>"核销失败")));
} else {
  exit(json_encode(array("code"=>0,"msg"=>"核销成功")));
}