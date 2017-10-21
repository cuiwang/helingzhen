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
			pdo_update($this->redpack_rotate_table,array('status'=>3),array('weid'=>$weid,'rid'=>$rid,'id'=>$rotate_id));	
	}
}