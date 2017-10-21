<?php
require_once IA_ROOT . "/addons/" . $this->modulename . "/inc/common.php";
global $_W, $_GPC;
$op = !empty ($_GPC['op']) ? $_GPC['op'] : "display";
$settings = $this->module['config'];
$uniacid = $_W['uniacid'];
$modulename = $this->modulename;
$userinfo=getFromUser($settings, $modulename);
$userinfo=json_decode($userinfo, true);


$from_user=$_GPC['openid'];
$id=intval($_GPC['id']);

$activity_id=intval($_GPC['activity_id']);


$cgc_baoming_activity=new cgc_baoming_activity();
  
if (!empty($id)){
  $activity = $cgc_baoming_activity->getOne($activity_id); 
} 


$hx_pass=($_GPC['hx_pass']);

if ($_GPC['hx_pass']!=$activity['hx_pass'] || empty($activity['hx_pass'])){
 exit(json_encode(array("code"=>-2,"msg"=>"核销错误1")));
}

$user=pdo_fetch("select *  from".tablename("cgc_baoming_user")."  where activity_id=$activity_id and id=$id and share_status=1 and hx_status=0");

if ($user==false){
  exit(json_encode(array("code"=>-1,"msg"=>"用户不符合条件,或者已经核销过了")));
} 

$ret=pdo_query("update ".tablename("cgc_baoming_user")." set hx_status=1 where id=$id");

if ($ret==false){
  exit(json_encode(array("code"=>-2,"msg"=>"核销失败")));
} else {
  exit(json_encode(array("code"=>0,"msg"=>"核销成功")));
}