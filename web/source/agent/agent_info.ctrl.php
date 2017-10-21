<?php
defined('IN_IA') or exit('Access Denied');
$dos = array('info', 'record', 'recharge', 'disciuntprice', 'changepwd');
$do = in_array($do, $dos) ? $do : 'info';
session_start();
checkagentlogin();
$ids=$_SESSION['id'];
if ($do == 'record') {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	session_start();
	$params = array();
	$where="where 1=1 and agentid=$ids";
	$sql = 'SELECT * FROM ' . tablename('agent_expenserecords') .$where . " LIMIT " . ($pindex - 1) * $psize .',' .$psize;
	$users = pdo_fetchall($sql, $params);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('agent_expenserecords') . $where, $params);
	$pager = pagination($total, $pindex, $psize);

	$founders = explode(',', $_W['config']['setting']['founder']);

	foreach($users as &$user) {
		$user['founder'] = in_array($user['id'], $founders);
	}
	unset($user);

	function m_kind($mana){
		switch($mana){
			case 0:
			return '<font color="red">未成功</font>';
			break;
			case 1:
			return '<font color="green">已成功</font>';
			break;
		}
	}//end function
	template('agent/agent_info');
}
if ($do == 'recharge') {
	template('agent/agent_info');
}
if ($do == 'changepwd') {
	load()->model('setting');
	$user = agent_single($ids);
	if(checksubmit()) {
	if(empty($_GPC['oldpassword'])){
		message('请输入原密码！','');
		}
		if($_GPC['password']!=$_GPC['repassword']){
			message('2次密码输入不一致，请重新输入！','');
			}
		load()->model('user');
		$ids=$_SESSION['id'];
		$record = array();
		$record['id'] = $ids;
		$record['oldpassword'] = $_GPC['oldpassword'];
		$record['password'] = $_GPC['password'];
		$record['repassword'] = $_GPC['repassword'];
		agent_pwd($record);
		message('修改密码成功！', 'agent/agent_show/changepwd');
	}
	template('agent/agent_info');
}
if ($do == 'disciuntprice') {
	template('agent/agent_info');
}
if ($do == 'info') {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$params = array();
	$user = agent_single($ids);
	template('agent/agent_info');
}
