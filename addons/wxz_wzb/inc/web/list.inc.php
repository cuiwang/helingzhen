<?php


global $_W,$_GPC;
$setting = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_list') . ' WHERE `uniacid` = :uniacid', array(':uniacid' => $_W['uniacid']));
$id = pdo_fetch('SELECT eid FROM ' . tablename('modules_bindings') . ' WHERE `module` = :module and `entry` = :entry  and `do` = :do ', array(':module' => 'wxz_wzb',':entry' => 'home',':do' => 'index'));

if(isset($_GPC['item']) && $_GPC['item'] == 'ajax' && $_GPC['key'] == 'setting'){
	
	$data=array(
		'uniacid'=>$_W['uniacid'],
		'title'=>$_GPC['title'],
		'list_share_img'=>$_GPC['list_share_img'],
		'list_share_title'=>$_GPC['list_share_title'],
		'list_share_desc'=>$_GPC['list_share_desc'],
		'dateline'=>time()
	);

	if($setting){
		pdo_update('wxz_wzb_list', $data, array('id'=>$setting['id']));
		message('编辑成功',$this->createWebUrl('list'),'success');
	}else{
		pdo_insert('wxz_wzb_list', $data);
		message('新增成功',$this->createWebUrl('list'),'success');
	}
}

load()->func('tpl');
include $this->template('list');
