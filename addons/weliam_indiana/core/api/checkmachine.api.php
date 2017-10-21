<?php
require '../../../../framework/bootstrap.inc.php';
require IA_ROOT. '/addons/weliam_indiana/defines.php';
require WELIAM_INDIANA_INC.'function.php';
load()->func('communication');

$uniacid = $_GPC['uniacid'];
$sql = "select id,status,createtime from".tablename('weliam_indiana_machineset')."where uniacid=:uniacid  and period_number like '%openmachine%'";
$data = array(
	':uniacid'=>$uniacid
);
$result = pdo_fetch($sql,$data);
$difference_time = time()-$result['createtime'];
if($result['status']==1 && $difference_time > 600){//超过300秒未反应
	$J = ihttp_request($_W["siteroot"].'api/robot.api.php', array('uniacid' => $uniacid),array('Content-Type' => 'application/x-www-form-urlencoded'),1);
	/*************日志文件位置开始************/
	m('log')->WL_log('checkmachine','重启机器人进程',$J,$uniacid);
	/*************日志文件位置结束************/  
}
