<?php
global $_W,$_GPC;
$rid = intval($_GPC['rid']);
$weid = $_W['uniacid'];
if($_W['isajax']){
	$rotate_id = intval($_GPC['rotate_id']);
	if($rotate_id){
		$user = $_GPC['user'];
		if(!empty($user) && is_array($user)){
			$pnum = pdo_fetchcolumn("SELECT `pnum` FROM ".tablename($this->shake_rotate_table)." WHERE weid=:weid AND rid=:rid AND id=:id",array(':weid'=>$weid,':rid'=>$rid,':id'=>$rotate_id));
			$award_again = pdo_fetchcolumn("SELECT `award_again` FROM ".tablename($this->shake_config_table)." WHERE weid=:weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid));
			foreach($user as $key=>$row){
				$order = $key +1;
				if($key<$pnum && $award_again==2){
					 pdo_update($this->shake_user_table,array('count'=>$row['shakeTime'],'displayorder'=>$order,'award'=>1),array('rid'=>$rid,'rotate_id'=>$rotate_id,'openid'=>$row['client_openid']));
				}else{
					pdo_update($this->shake_user_table,array('count'=>$row['shakeTime'],'displayorder'=>$order),array('rid'=>$rid,'rotate_id'=>$rotate_id,'openid'=>$row['client_openid']));
				}
			}
		}
		pdo_update($this->shake_rotate_table,array('status'=>3),array('rid'=>$rid,'weid'=>$weid,'id'=>$rotate_id));
		die(json_encode(error(0,'游戏结束')));
	}else{
		die(json_encode(error(-1,'error')));
	}
}