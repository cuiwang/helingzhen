<?php
global $_GPC, $_W;
checklogin();
$pici = $_GPC['pici'];
$res = pdo_fetch('select * from ' . tablename('haoman_dpm_pici') . ' where uniacid = :uniacid and pici = :pici', array(':uniacid' => $_W['uniacid'] ,':pici' => $pici));
if($res){
	pdo_delete('haoman_dpm_pici', array('uniacid' => $_W['uniacid'],'pici' => $pici));
	pdo_delete('haoman_dpm_pw', array('uniacid' => $_W['uniacid'],'pici' => $pici));

	message('删除成功',$this->createWebUrl("code"),'success');
}else{
	message('暂无口令',$this->createWebUrl("code"),'error');
}