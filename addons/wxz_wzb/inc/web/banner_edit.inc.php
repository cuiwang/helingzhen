<?php
global $_W,$_GPC;
$uniacid = $_W['uniacid'];
load()->func('tpl');
$id = intval($_GPC['id']);
$banner = pdo_fetch("SELECT * FROM ".tablename('wxz_wzb_banner')." WHERE uniacid=:uniacid AND id=:id",array(':uniacid'=>$uniacid,':id'=>$id));
if(empty($banner)){
	$banner = array(
		'isshow'=>1,
	);
}
if (checksubmit('submit')) {
		
	$data = array();
	$data['uniacid'] = $uniacid;
	$data['img'] = $_GPC['img'];
	$data['url'] = $_GPC['url'];
	$data['isshow'] = intval($_GPC['isshow']);
	$data['sort'] = intval($_GPC['sort']);
	if(!empty($id)){
		pdo_update('wxz_wzb_banner',$data,array('id'=>$id,'uniacid'=>$uniacid));
		message('编辑成功',$this->createWebUrl('banner_list'),'success');
	}else{
		$data['dateline'] = time();
		pdo_insert('wxz_wzb_banner',$data);
		message('新增成功',$this->createWebUrl('banner_list'),'success');
	}
}
include $this->template('banner_edit');