
<?php
global $_W,$_GPC;
load()->func('db');
load()->func('pdo');
$table_setting = 'meepo_lottery_setting';
$sql = 'SELECT lottery_count FROM '.tablename($table_setting).'WHERE uniacid = :uniacid';
$params = array(':uniacid'=>$_W['uniacid']);
$result = pdo_fetch($sql,$params);
$lottery_count = $result['lottery_count'];
if($lottery_count == ''){
	$lottery_count == 10;
}
if($_W['member']['credit1']<$lottery_count){
	$ajax['success'] = 0;
	die(json_encode($ajax));
}else{
	$ajax['success'] = 1;
	die(json_encode($ajax));
}