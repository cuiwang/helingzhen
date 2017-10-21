<?php
global $_W,$_GPC;
$rid = intval($_GPC['rid']);
$weid = $_W['uniacid'];
if($_W['isajax']){
	$rotate_id = intval($_GPC['rotate_id']);
	$type = $_GPC['type'];
	if($rotate_id){
		if($type=='start'){
		 $check = pdo_fetchcolumn("SELECT `id` FROM ".tablename($this->shake_user_table)." WHERE weid=:weid AND rid=:rid AND rotate_id=:rotate_id",array(':weid'=>$weid,':rid'=>$rid,':rotate_id'=>$rotate_id));
		 if(!empty($check)){
			pdo_update($this->shake_rotate_table,array('status'=>2),array('rid'=>$rid,'weid'=>$weid,'id'=>$rotate_id));
		 }else{
			die(json_encode(error(-2,'fail')));
		 }
		}elseif($type=='reset'){
			pdo_update($this->shake_rotate_table,array('status'=>1),array('rid'=>$rid,'weid'=>$weid,'id'=>$rotate_id));
			pdo_update($this->shake_user_table,array('count'=>0,'award'=>0),array('rid'=>$rid,'weid'=>$weid,'rotate_id'=>$rotate_id));
		}
		die(json_encode(error(0,'success')));
	}else{
		die(json_encode(error(-1,'fail')));
	}
}