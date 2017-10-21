<?php
require '../../../../framework/bootstrap.inc.php';
require IA_ROOT. '/addons/weliam_indiana/defines.php';
require WELIAM_INDIANA_INC.'function.php';

load()->func('communication');

$uniacid = $_GPC['uniacid'];
$op = $_GPC['op'];

$ip_a = $_SERVER["REMOTE_ADDR"];
$ip_b = $_SERVER["HTTP_X_FORWARDED_FOR"];
m('log')->WL_log('machine_apply','访问ip情况',$ip_b,$uniacid);
if($ip_a != '114.215.91.124' && $ip_b != '114.215.91.124'){
	exit('访问出错');
}

if($op == 'examine_pass'){
	//审核通过
	$result_update = pdo_update("weliam_indiana_machineset",array('status'=>1),array('goodsid'=>-2,'uniacid'=>$uniacid));
	if($result_update == 1){
		m('log')->WL_log('machine_apply','审核情况','审核通过',$uniacid);
		exit('审核通过');
	}else{
		m('log')->WL_log('machine_apply','审核情况','审核通过状态修改失败',$uniacid);
		exit('审核通过状态修改失败');
	}
}

if($op == 'examine_nopass'){
	//审核不通过
	$result_delete = pdo_delete("weliam_indiana_machineset",array('goodsid'=>-2,'uniacid'=>$uniacid));
	if($result_delete == 1){
		m('log')->WL_log('machine_apply','审核情况','审核不通过',$uniacid);
		exit('审核不通过');
	}else{
		m('log')->WL_log('machine_apply','审核情况','审核不通过状态修改失败',$uniacid);
		exit('审核不通过状态修改失败');
	}
}
