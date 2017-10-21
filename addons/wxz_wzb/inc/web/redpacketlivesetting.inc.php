<?php
global $_W,$_GPC;
$uniacid = $_W['uniacid'];
$rid = $_GPC['rid'];

load()->func('tpl');
$rid = intval($_GPC['rid']);
$item = pdo_fetch("SELECT * FROM ".tablename('wxz_wzb_live_red_packet')." WHERE uniacid=:uniacid AND rid=:rid",array(':uniacid'=>$uniacid,':rid'=>$rid));

if (checksubmit('submit')) {
	$data = array();
	$data['uniacid'] = $_W['uniacid'];
	$data['min'] = intval($_GPC['min']);
	$data['max'] = intval($_GPC['max']);
	$data['rid'] = trim($_GPC['rid']);
	$data['type'] = trim($_GPC['type']);
	$data['withdraw_min'] = trim($_GPC['withdraw_min']);
	$data['reward_amount_min'] = trim($_GPC['reward_amount_min']);
	$data['reward_amount_max'] = trim($_GPC['reward_amount_max']);
	$data['pool_amount'] = trim($_GPC['pool_amount']);
	$data['packet_rule'] = trim($_POST['packet_rule']);
	$data['createtime'] = time();

	if(empty($item)){
		pdo_insert('wxz_wzb_live_red_packet',$data);
		message('新增成功',$this->createWebUrl('redpacketlivesetting'),'success');
	}else{
		pdo_update('wxz_wzb_live_red_packet',$data,array('rid' => $rid));
		message('编辑成功',referer(),'success');
	}
}
include $this->template('redpacketlivesetting');
?>