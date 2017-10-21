<?php
global $_W,$_GPC;
$uniacid = $_W['uniacid'];
$rid = $_GPC['rid'];

load()->func('tpl');
$rid = intval($_GPC['rid']);
$list = pdo_fetch("SELECT * FROM ".tablename('wxz_wzb_setting')." WHERE uniacid=:uniacid AND rid=:rid",array(':uniacid'=>$uniacid,':rid'=>$rid));

if (checksubmit('submit')) {
	$data = array();

	$data['share_img'] = $_GPC['share_img'];
	$data['share_title'] = $_GPC['share_title'];
	$data['share_desc'] = $_GPC['share_desc'];

	if(!empty($rid)){
		pdo_update('wxz_wzb_setting',$data,array('rid'=>$rid,'uniacid'=>$uniacid));
		message('编辑成功',referer(),'success');
	}else{
		$data['dateline'] = time();
		pdo_insert('wxz_wzb_setting',$data);
		message('新增成功',$this->createWebUrl('share_edit'),'success');
	}

}
include $this->template('share_edit');
?>