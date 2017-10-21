<?php
/**
 * MEEPO 米波现场
 *
 * 官网 http://meepo.com.cn 作者QQ 284099857
 */
global $_GPC, $_W;
$weid = $_W['uniacid'];
if($_W['isajax']){
	$rid = intval($_GPC['rid']);
	$rotate_id = intval($_GPC['rotate_id']);
	$max_id = intval($_GPC['max_id']);
	if($rid && $rotate_id){
		$data = pdo_fetchall("SELECT `id`,`nick_name`,`openid`,`avatar` FROM ".tablename($this->shake_user_table)." WHERE rid=:rid AND rotate_id=:rotate_id AND id>:id ORDER BY id ASC",array(':rid'=>$rid,':rotate_id'=>$rotate_id,':id'=>$max_id));
		$count = pdo_fetchcolumn("SELECT count(id) FROM ".tablename($this->shake_user_table)." WHERE rid=:rid AND rotate_id=:rotate_id",array(':rid'=>$rid,':rotate_id'=>$rotate_id));
		$result = array('errno'=>0,'message'=>intval($count),'data'=>$data);
		die(json_encode($result));
	}
}