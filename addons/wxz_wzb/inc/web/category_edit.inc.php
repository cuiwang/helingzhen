<?php
global $_W,$_GPC;
$uniacid = $_W['uniacid'];
load()->func('tpl');
$id = intval($_GPC['id']);
$category = pdo_fetch("SELECT * FROM ".tablename('wxz_wzb_category')." WHERE uniacid=:uniacid AND id=:id",array(':uniacid'=>$uniacid,':id'=>$id));
if(empty($category)){
	$category = array(
		'isshow'=>1,
	);
}
if (checksubmit('submit')) {
		
	$data = array();
	$data['uniacid'] = $uniacid;
	$data['title'] = $_GPC['title'];
	$data['isshow'] = intval($_GPC['isshow']);
	$data['sort'] = intval($_GPC['sort']);

	if(!empty($id)){
		pdo_update('wxz_wzb_category',$data,array('id'=>$id,'uniacid'=>$uniacid));
		message('编辑成功',$this->createWebUrl('category_list'),'success');
	}else{
		$data['dateline'] = time();
		pdo_insert('wxz_wzb_category',$data);
		message('新增成功',$this->createWebUrl('category_list'),'success');
	}
}
include $this->template('category_edit');