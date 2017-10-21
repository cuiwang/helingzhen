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
	$lottory_id = intval($_GPC['lottory_id']);
	pdo_delete($this->lottory_user_table,array('rid'=>$rid,'lottory_id'=>$lottory_id));
	die(json_encode(array('data'=>'success')));
}