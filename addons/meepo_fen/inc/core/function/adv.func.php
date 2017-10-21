<?php
function getadvs($module){
	global $_W;
	$sql = "SELECT * FROM ".tablename('meepo_common_adv')." WHERE uniacid = :uniacid AND module = :module";
	$params = array(':uniacid'=>$_W['uniacid'],':module'=>$module);
	$advs = pdo_fetchall($sql,$params);
	return $advs;
}

function getadv($id){
	$sql = "SELECT * FROM ".tablename('meepo_logistics_adv')." WHERE id = :id";
	$params = array(':id'=>$id);
	$data = pdo_fetch($sql,$params);
	return $data;
}

function insertadv($data,$module){
	
}