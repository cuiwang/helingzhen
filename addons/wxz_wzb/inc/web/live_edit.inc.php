<?php

global $_W,$_GPC;
$uniacid = $_W['uniacid'];
load()->func('tpl');

$rid = intval($_GPC['rid']);
$type = intval($_GPC['type']);
if(empty($rid)){
	message('直播id不存在',$this->createWebUrl('live_list'),'error');
}
$zhibo_list = pdo_fetch("SELECT * FROM ".tablename('wxz_wzb_live_setting')." WHERE uniacid=:uniacid AND rid=:rid",array(':uniacid'=>$uniacid,':rid'=>$rid));
if(empty($zhibo_list)){
	message('此直播不存在或是已经被删除',$this->createWebUrl('live_list'),'error');
}
$id = intval($_GPC['id']);
$menus = pdo_fetch("SELECT * FROM ".tablename('wxz_wzb_live_menu')." WHERE uniacid=:uniacid AND id=:id",array(':uniacid'=>$uniacid,':id'=>$id));
if(!$type){
	$type = $menus['type'];
}
if(!empty($menus)){
	$menus['settings'] = iunserializer($menus['settings']);
}
if (checksubmit('submit')) {
	$data = array();
	$type = $_GPC['type'];
	$post_setting = array();
	if($type=='1'){
		$post_setting['iframe'] = $_GPC['iframe'];
	}elseif($type=='2'){
		$post_setting['content'] = $_POST['content'];
	}elseif($type=='5'){
		$post_setting['list'] = $_GPC['list'];
	}elseif($type=='6'){
		$post_setting['longitude'] = $_GPC['longitude'];
		$post_setting['latitude'] = $_GPC['latitude'];
	}

	$data['type'] = $type;
	$data['rid'] = $rid;
	$data['isshow'] = intval($_GPC['isshow']);
	$data['sort'] = intval($_GPC['sort']);
	$data['name'] = $_GPC['name'];
	$data['uniacid'] = $uniacid;
	$data['settings'] = iserializer($post_setting);

	if(!empty($id)){
		pdo_update('wxz_wzb_live_menu',$data,array('id'=>$id,'uniacid'=>$uniacid));
		message('编辑成功',$this->createWebUrl('live_menu',array('rid'=>$rid)),'success');
	}else{
		$data['dateline'] = time();
		pdo_insert('wxz_wzb_live_menu',$data);
		message('新增成功',$this->createWebUrl('live_menu',array('rid'=>$rid)),'success');
	}

}
include $this->template('live_edit');