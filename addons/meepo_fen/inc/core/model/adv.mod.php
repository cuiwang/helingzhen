<?php
function getadvs($module){
	global $_W;
	$sql = "SELECT * FROM ".tablename('meepo_common_adv')." WHERE uniacid = :uniacid AND module = :module";
	$params = array(':uniacid'=>$_W['uniacid'],':module'=>$module);
	$advs = pdo_fetchall($sql,$params);
	return $advs;
}

function getadv($id){
	$sql = "SELECT * FROM ".tablename('meepo_common_adv')." WHERE id = :id";
	$params = array(':id'=>$id);
	$data = pdo_fetch($sql,$params);
	return $data;
}

function deladv($id = 0){
	global $_GPC;
	if(empty($id)){
		$id = intval($_GPC['id']);
	}
	if(empty($id)){
		return error(-1,'所选操作不存在或已删除');
	}
	if(pdo_delete('meepo_common_adv',array('id'=>$id))){
		return true;
	}
	return error(-2,'操作失败');
}
