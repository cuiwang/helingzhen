<?php
global $_W,$_GPC;
$weid = $_W['uniacid'];
$rid = intval($_GPC['rid']);

$data = array();
if($_W['isajax']){
	pdo_update('weixin_wall_reply',array('bahe_status'=>1),array('rid'=>$rid,'weid'=>$weid));
	die(json_encode(error(0,'start')));
}else{
	die(json_encode(error(-1,'fail')));
}