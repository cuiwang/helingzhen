<?php
global $_W,$_GPC;
$rid = intval($_GPC['rid']);
$weid = $_W['uniacid'];
if($_W['isajax']){
	$rotate_id = intval($_GPC['rotate_id']);
	if($rotate_id){
		pdo_update($this->shake_rotate_table,array('status'=>3),array('rid'=>$rid,'weid'=>$weid,'id'=>$rotate_id));
		die(json_encode(error(0,'success')));
	}else{
		die(json_encode(error(-1,'fail')));
	}
}