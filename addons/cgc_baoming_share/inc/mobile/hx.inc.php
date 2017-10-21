<?php
global $_W, $_GPC;
$op = !empty ($_GPC['op']) ? $_GPC['op'] : "display";
$settings = $this->module['config'];
$uniacid = $_W['uniacid'];
$modulename = $this->modulename;
$userinfo=getFromUser($settings, $modulename);
$userinfo=json_decode($userinfo, true);

if (empty($settings['hx_openid']) || !strexists($settings['hx_openid'],$userinfo['openid'])){
  message("没有权限");
}

$from_user=$_GPC['openid'];
$id=intval($_GPC['activity_id']);

$user=pdo_fetch("select * from ".tablename("cgc_baoming_user")."  where uniacid =$uniacid and  activity_id=$id and openid='$from_user' and share_status=1 and  hx_status=0");


$ret=pdo_query("update ".tablename("cgc_baoming_user")." set hx_status=1 where uniacid =$uniacid  and activity_id=$id and openid='$from_user'");

if ($ret==false){
 // message("核销失败",referer(),"error");
   message("核销失败");
} else {
 message("核销成功");
}