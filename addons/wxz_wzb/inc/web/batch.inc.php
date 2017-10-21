<?php
global $_W,$_GPC;
if (checksubmit('submit')) {
	$data = array();
	$data['player_weight'] = $_GPC['player_weight'];
	$data['player_height'] = $_GPC['player_height'];

	pdo_update('wxz_wzb_live_video_type',$data,array('uniacid'=>$_W['uniacid']));
	message('编辑成功',$this->createWebUrl('batch'),'success');
}
include $this->template('batch');