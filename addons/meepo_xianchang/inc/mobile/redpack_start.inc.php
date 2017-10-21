<?php
/**
 * MEEPO 米波现场
 *
 * 官网 http://meepo.com.cn 作者QQ 284099857
 */
global $_W,$_GPC;
$weid = $_W['uniacid'];
$rid = intval($_GPC['rid']);
$data = array();
if($_W['isajax']){
	$rotate_id = intval($_GPC['rotate_id']);
	if($rotate_id){
		$redpack = pdo_fetch("SELECT `status`,`countdown` FROM ".tablename($this->redpack_rotate_table)." WHERE weid = :weid AND rid=:rid AND id=:id",array(':weid'=>$weid,':rid'=>$rid,':id'=>$rotate_id));
		if(empty($redpack)){
			$data = error(-3,'error');
			die(json_encode($data));
		}
		if($redpack['status']==3){
			$data = error(-2,'game over');
		}else{
			if($redpack['status']==1){
				pdo_update($this->redpack_rotate_table,array('status'=>2),array('weid'=>$weid,'rid'=>$rid,'id'=>$rotate_id));
				$data = error($redpack['countdown'],'game start');
			}else{
				$data = error($redpack['countdown'],'game doing');
			}
		}
	}else{
		$data = error(-3,'error');
	}
	die(json_encode($data));
}