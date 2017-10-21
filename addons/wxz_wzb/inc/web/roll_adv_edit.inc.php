<?php

global $_W,$_GPC;
$uniacid = $_W['uniacid'];
load()->func('tpl');

$rid = intval($_GPC['rid']);
if(empty($rid)){
	message('直播rid不存在',$this->createWebUrl('live_list'),'error');
}
$zhibo_list = pdo_fetch("SELECT * FROM ".tablename('wxz_wzb_live_setting')." WHERE uniacid=:uniacid AND rid=:rid",array(':uniacid'=>$uniacid,':rid'=>$rid));
if(empty($zhibo_list)){
	message('此直播不存在或是已经被删除',$this->createWebUrl('live_list'),'error');
}

$list = pdo_fetch("SELECT * FROM ".tablename('wxz_wzb_roll_adv')." WHERE uniacid=:uniacid AND rid=:rid",array(':uniacid'=>$uniacid,':rid'=>$rid));

if (checksubmit('submit')) {
	$data = array();
	$data['content'] = $_GPC['content'];
	$data['uniacid'] = $uniacid;
	$data['rid'] = $rid;
	$data['type'] = $_GPC['type'];

	if(!empty($list)){
		pdo_update('wxz_wzb_roll_adv',$data,array('rid'=>$rid,'uniacid'=>$uniacid));
		message('编辑成功',$this->createWebUrl('roll_adv_edit',array('rid'=>$rid)),'success');
	}else{
		$data['dateline'] = time();
		pdo_insert('wxz_wzb_roll_adv',$data);
		message('新增成功',$this->createWebUrl('roll_adv_edit',array('rid'=>$rid)),'success');
	}
}

include $this->template('roll_adv_edit');