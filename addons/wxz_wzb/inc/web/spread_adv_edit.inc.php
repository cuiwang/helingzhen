<?php

global $_W,$_GPC;
$uniacid = $_W['uniacid'];
load()->func('tpl');

$fans = pdo_fetchAll("SELECT * FROM ".tablename('wxz_wzb_share'));


$rid = intval($_GPC['rid']);
if(empty($rid)){
	message('直播id不存在',$this->createWebUrl('live_list'),'error');
}
$zhibo_list = pdo_fetch("SELECT * FROM ".tablename('wxz_wzb_live_setting')." WHERE uniacid=:uniacid AND rid=:rid",array(':uniacid'=>$uniacid,':rid'=>$rid));
if(empty($zhibo_list)){
	message('此直播不存在或是已经被删除',$this->createWebUrl('live_list'),'error');
}

$list = pdo_fetch("SELECT * FROM ".tablename('wxz_wzb_spread_adv')." WHERE uniacid=:uniacid AND rid=:rid",array(':uniacid'=>$uniacid,':rid'=>$rid));

if (checksubmit('submit')) {
	$data = array();
	$data['images'] = $_GPC['images'];
	$data['uniacid'] = $uniacid;
	$data['rid'] = $rid;
	$data['type'] = $_GPC['type'];
	$data['url'] =$_GPC['url'];
	$data['bgcolor'] =$_GPC['bgcolor'];
	$data['color'] =$_GPC['color'];
	$data['timecolor'] =$_GPC['timecolor'];
	$data['count_time'] = intval($_GPC['count_time']);

	if(!empty($list)){
		pdo_update('wxz_wzb_spread_adv',$data,array('rid'=>$rid,'uniacid'=>$uniacid));
		message('编辑成功',$this->createWebUrl('spread_adv_edit',array('rid'=>$rid)),'success');
	}else{
		$data['dateline'] = time();
		pdo_insert('wxz_wzb_spread_adv',$data);
		message('新增成功',$this->createWebUrl('spread_adv_edit',array('rid'=>$rid)),'success');
	}
}
$setting = iunserializer($list['settings']);

include $this->template('spread_adv_edit');