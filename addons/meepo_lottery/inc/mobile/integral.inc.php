<?php
global $_W,$_GPC;
load()->model('mc');
load()->func('db');
load()->func('pdo');
$table_setting = 'meepo_lottery_setting';
$sql = 'SELECT lottery_count FROM '.tablename($table_setting).'WHERE uniacid = :uniacid ';
$params = array(':uniacid' =>$_W['uniacid']);
$result = pdo_fetch($sql,$params);
$lottery_count = $result['lottery_count'];
if($lottery_count == ''){
	$lottery_count == -10;
}
if($_W['member']['credit1']<$lottery_count){
	$ajax['error'] = 1;
	$ajax['success'] = 0;
	$ajax['message'] = '您的积分不足！';
	$ajax['credit1'] = $_W['member']['credit1'];
	die(json_encode($ajax));
}
$result = mc_credit_update($_W['member']['uid'],'credit1', -$lottery_count, array($_W['member']['uid'], '抽奖消耗的积分：20'));
if($result){
	$ajax['error'] = 0;
	$ajax['success'] = 1;
	$ajax['message'] = '';
}else{
	$ajax['error'] = 1;
	$ajax['success'] = 0;
	$ajax['message'] = '';
}
die(json_encode($ajax));   