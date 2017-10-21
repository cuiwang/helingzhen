<?php 
global $_W,$_GPC;
$table_members = 'meepo_lottery_members';
if(!empty($_W['fans']['openid'])){
	$sql = 'SELECT members_username, members_thumbnail FROM '.tablename($table_members).'WHERE members_openid = :openid AND uniacid = :uniacid';
	$params = array(':openid' =>$_W['fans']['openid'],':uniacid' =>$_W['uniacid']);
	$member = pdo_fetch($sql,$params);
}
$member['members_credit1'] = $_W['member']['credit1'];
include $this->template('home');