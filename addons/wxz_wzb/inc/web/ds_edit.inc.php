<?php

global $_W,$_GPC;
$uniacid = $_W['uniacid'];
load()->func('tpl');

$rid = intval($_GPC['rid']);
if(empty($rid)){
	message('直播id不存在',$this->createWebUrl('live_list'),'error');
}
$zhibo_list = pdo_fetch("SELECT * FROM ".tablename('wxz_wzb_live_setting')." WHERE uniacid=:uniacid AND rid=:rid",array(':uniacid'=>$uniacid,':rid'=>$rid));
if(empty($zhibo_list)){
	message('此直播不存在或是已经被删除',$this->createWebUrl('live_list'),'error');
}

$list = pdo_fetch("SELECT * FROM ".tablename('wxz_wzb_ds_setting')." WHERE rid=:rid",array(':rid'=>$rid));
if($list){
	$setting = iunserializer($list['settings']);
}else{
	$setting['one'] = '100';
	$setting['two'] = '200';
	$setting['three'] = '300';
	$setting['four'] = '400';
	$setting['five'] = '500';
	$setting['six'] = '600';
}

if (checksubmit('submit')) {
	$data = array();
	$data['rid'] = $_GPC['rid'];
	$data['logo'] = $_GPC['logo'];
	$data['isshow'] = $_GPC['isshow'];
	$data['content'] = $_GPC['content'];
	$data['dateline'] = time();

	$setting['one'] = $_GPC['one'];
	$setting['two'] = $_GPC['two'];
	$setting['three'] = $_GPC['three'];
	$setting['four'] = $_GPC['four'];
	$setting['five'] = $_GPC['five'];
	$setting['six'] = $_GPC['six'];
	$data['settings'] = iserializer($setting);

	if(!empty($list)){
		pdo_update('wxz_wzb_ds_setting',$data,array('rid'=>$rid));
		message('编辑成功',$this->createWebUrl('ds_edit',array('rid'=>$rid)),'success');
	}else{
		$data['dateline'] = time();
		pdo_insert('wxz_wzb_ds_setting',$data);
		message('新增成功',$this->createWebUrl('ds_edit',array('rid'=>$rid)),'success');
	}
}


include $this->template('ds_edit');