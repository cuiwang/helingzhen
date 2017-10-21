<?php 
global $_W,$_GPC;
load()->func('db');
load()->func('pdo');
$table_detail = 'meepo_lottery_detail';
if(!empty($_GPC['openid'])){
	 $sql = 'SELECT * FROM '.tablename($table_detail).'WHERE detail_openid = :open_id AND uniacid = :uniacid';
	 $params =array(
	 	':open_id' => $_GPC['openid'],
	 	':uniacid' =>$_W['uniacid']
	 );
	 $user_detail = pdo_fetch($sql,$params);
	 die(json_encode($user_detail));
}