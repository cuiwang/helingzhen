<?php

global $_W,$_GPC;
$uniacid = $_W['uniacid'];
load()->func('tpl');

$rid = intval($_GPC['rid']);
if(empty($rid)){
	message('直播id不存在',$this->createWebUrl('live_list'),'error');
}
$list = pdo_fetch("SELECT * FROM ".tablename('wxz_wzb_live_setting')." WHERE uniacid=:uniacid AND rid=:rid",array(':uniacid'=>$uniacid,':rid'=>$rid));
if(empty($list)){
	message('此直播不存在或是已经被删除',$this->createWebUrl('live_list'),'error');
}

if (checksubmit('submit')) {
	$data = array();
	$data['total_num'] = $_GPC['total_num'];
	

	if(!empty($list)){
		pdo_update('wxz_wzb_live_setting',$data,array('rid'=>$rid,'uniacid'=>$uniacid));
		message('编辑成功',$this->createWebUrl('resetnum',array('rid'=>$rid)),'success');
	}
}

include $this->template('resetnum');