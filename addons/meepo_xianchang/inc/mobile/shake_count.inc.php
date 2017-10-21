<?php
global $_W,$_GPC;
$rid = intval($_GPC['rid']);
$weid = $_W['uniacid'];
if($_W['isajax']){
	$rotate_id = intval($_GPC['rotate_id']);
	$openid = $_W['openid'];
	$status = pdo_fetchcolumn("SELECT `status` FROM ".tablename($this->shake_rotate_table)." WHERE weid=:weid AND rid=:rid AND id=:id",array(':weid'=>$weid,':rid'=>$rid,':id'=>$rotate_id));
	if($status==1){
		die(json_encode(error(-1,'游戏还未开始')));
	}
	if($rotate_id){
	 $point = pdo_fetchcolumn("SELECT `point` FROM ".tablename($this->shake_config_table)." WHERE weid=:weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid));
	 $check_nums = pdo_fetch("SELECT * FROM ".tablename($this->shake_user_table)." WHERE weid=:weid AND rid=:rid AND rotate_id=:rotate_id AND count>=:count",array(':weid'=>$weid,':rid'=>$rid,':rotate_id'=>$rotate_id,':count'=>$point));
	 if(empty($check_nums)){
		pdo_query("UPDATE ".tablename($this->shake_user_table)." SET count = count + 1 WHERE weid = 
			:weid AND rotate_id=:rotate_id  AND openid=:openid",array(':weid'=>$weid,':rotate_id'=>$rotate_id,':openid'=>$openid));
		die(json_encode(error(0,'游戏开始啦')));
	 }else{
		pdo_update($this->shake_rotate_table,array('status'=>3),array('rid'=>$rid,'weid'=>$weid,'id'=>$rotate_id));
		die(json_encode(error(1,'游戏结束啦')));
	 }
	}else{
		die(json_encode(error(-3,'error')));
	}
}
die(json_encode(error(-3,'error')));