<?php
global $_W,$_GPC;
$foo = $_GPC['foo'];
$role = $_W['role'];
if(empty($foo)){
	$sql = "SELECT * FROM ".tablename('meepo_message_replace')." WHERE 1 ";
	$params = array();
	$ds = pdo_fetchall($sql,$params);
	include $this->template('setting');
}

if($foo == 'delete'){
	$id = intval($_GPC['id']);
	if($role != 'founder'){
		message('对不起，你无权操作');
	}
	if(empty($id)){
		message('参数错误',$this->createWebUrl('setting'),'error');
	}
	pdo_delete('meepo_message_replace',array('id'=>$id));
	message('删除成功',$this->createWebUrl('setting'),'success');
}

if($foo == 'one'){
	$settings = array();
	$settings[] = array('replace'=>'#当前时间#','name'=>'time','pro'=>'<p>会员会员中心现有余额</p>');
	$settings[] = array('replace'=>'#当前时间#','name'=>'time','pro'=>'<p>会员会员中心现有余额</p>');
}

if($foo == 'create'){
	$id = intval($_GPC['id']);
	if($role != 'founder'){
		message('对不起，你无权操作');
	}
	$sql = "SELECT * FROM ".tablename('meepo_message_replace')." WHERE id = :id";
	$params = array(':id'=>$id);
	$setting = pdo_fetch($sql,$params);

	if($_W['ispost']) {
		$input = array_elements(array('replace', 'name','pro'), $_GPC);
		$input['pro'] = htmlspecialchars_decode($input['pro']);
		if(empty($id)){
			pdo_insert('meepo_message_replace',$input);
			message('新增成功',$this->createWebUrl('setting'),'success');
		}else{
			pdo_update('meepo_message_replace',$input,array('id'=>$id));
			message('更新成功',$this->createWebUrl('setting'),'success');
		}
	}

	include $this->template('setting_create');
}