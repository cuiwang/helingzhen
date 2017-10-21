<?php
global $_GPC, $_W;
checklogin();
$res = pdo_fetch('select * from ' . tablename('haoman_dpm_pw') . ' where uniacid = :uniacid and status = 2', array(':uniacid' => $_W['uniacid']));
if($res){
	pdo_delete('haoman_dpm_pw',array('uniacid' => $_W['uniacid'] ,'status' =>'2'));
	message('删除成功',$this->createWebUrl("code"),'success');
}else{
	message('暂无已失效口令',$this->createWebUrl("code"),'error');
}