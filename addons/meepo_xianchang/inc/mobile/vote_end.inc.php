<?php
/**
 * MEEPO 米波现场
 *
 * 官网 http://meepo.com.cn 作者QQ 284099857
 */
global $_W,$_GPC;
$weid = $_W['uniacid'];
$rid = intval($_GPC['rid']);
if($_W['isajax']){
	$vote_id = intval($_GPC['vote_id']);
	if($vote_id){
		pdo_update($this->vote_table,array('status'=>3),array('rid'=>$rid,'weid'=>$weid,'id'=>$vote_id));
	}
}