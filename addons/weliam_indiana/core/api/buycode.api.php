<?php
require '../../../../framework/bootstrap.inc.php';
require IA_ROOT. '/addons/weliam_indiana/defines.php';
require WELIAM_INDIANA_INC.'function.php';

$uniacid = $_GPC['uniacid'];
$openid = $_GPC['openid'];
$tid = $_GPC['tid'];
if(empty($uniacid) || empty($openid) || empty($tid)){
	echo '不能有空参数';exit;
}
m('codes')->code($openid,$tid,$uniacid);
?>