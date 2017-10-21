<?php
global $_W,$_GPC;
$uniacid = $_W['uniacid'];
$id = $_GPC['id'];

load()->func('tpl');

$list = pdo_fetch("SELECT * FROM ".tablename('wxz_wzb_live_setting')." WHERE id=:id",array(':id'=>$id));

if (checksubmit('submit')) {
	$data = array();
	$data['islinkurl'] = $_GPC['islinkurl'];
	$data['linkurl'] = $_GPC['linkurl'];
	$data['isshow'] = $_GPC['isshow'];
	$data['sort'] = $_GPC['sort'];

	if(!empty($id)){
		
		$r = pdo_update('wxz_wzb_live_setting',$data,array('id'=>$id));

		message('编辑成功',referer(),'success');
	}else{
		message('直播间不存在',$this->createWebUrl('clive_edit'),'error');
	}

}
include $this->template('clive_edit');
?>