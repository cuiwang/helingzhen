<?php
global $_W,$_GPC;
$uniacid = $_W['uniacid'];
$rid = $_GPC['rid'];

load()->func('tpl');

$list = pdo_fetch("SELECT * FROM ".tablename('wxz_wzb_live_setting')." WHERE uniacid=:uniacid AND rid=:rid",array(':uniacid'=>$uniacid,':rid'=>$rid));

if (checksubmit('submit')) {
	$data = array();

	$data['limit'] = $_GPC['limit'];
	$data['password'] = $_GPC['password'];
	$data['amount'] = $_GPC['amount'];
	$data['delayed'] = $_GPC['delayed'];

	if(!empty($rid)){
		pdo_update('wxz_wzb_live_setting',$data,array('rid'=>$rid,'uniacid'=>$uniacid));
		message('编辑成功',referer(),'success');
	}else{
		$data['dateline'] = time();
		pdo_insert('wxz_wzb_live_setting',$data);
		message('新增成功',$this->createWebUrl('limit_edit'),'success');
	}

}
include $this->template('limit_edit');
?>