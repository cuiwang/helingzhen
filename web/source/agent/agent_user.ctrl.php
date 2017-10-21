<?php
defined('IN_IA') or exit('Access Denied');
session_start();
checkagentlogin();
$dos = array('edit', 'delete', 'list', 'add', 'groups', 'groupset',  'wxuser');
$do = in_array($do, $dos) ? $do : 'list';
if ($do == 'list') {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	session_start();
	$ids=$_SESSION['id'];
	$where="where agentid=$ids";
	$params = array();
	$sql = 'SELECT * FROM ' . tablename('users') .$where . " LIMIT " . ($pindex - 1) * $psize .',' .$psize;
	$users = pdo_fetchall($sql, $params);
	
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('users') . $where, $params);
	$pager = pagination($total, $pindex, $psize);
$usergroups = pdo_fetchall("SELECT id, name FROM ".tablename('users_group'), array(), 'id');
	$founders = explode(',', $_W['config']['setting']['founder']);

	foreach($users as &$user) {
		$user['founder'] = in_array($user['id'], $founders);
	}
	unset($user);
	template('agent/agent_user');
}

if ($do == 'wxuser') {
	template('agent/agent_user');
}
if ($do == 'groups') {
	template('agent/agent_user');
}
if ($do == 'groupset') {
	template('agent/agent_user');
}

if ($do == 'discountprice') {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	
	$ids=$_SESSION['id'];
	$params = array();
	$sql = 'SELECT * FROM ' . tablename('users_group') .$where . " LIMIT " . ($pindex - 1) * $psize .',' .$psize;
	$users = pdo_fetchall($sql, $params);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('users_group') . $where, $params);
	$pager = pagination($total, $pindex, $psize);

	$founders = explode(',', $_W['config']['setting']['founder']);

	foreach($users as &$user) {
		$user['founder'] = in_array($user['id'], $founders);
	}
	unset($user);
	template('agent/agent_user');
}

if ($do == 'add') {
	if(checksubmit('submit')) {
		load()->model('user');
		$ids=$_SESSION['id'];
		$user = array();
		$user['username'] = trim($_GPC['name']);
		if(!preg_match(REGULAR_USERNAME, $user['username'])) {
			message('必须输入用户名，格式为 3-15 位字符，可以包括汉字、字母（不区分大小写）、数字、下划线和句点。');
		}
		if(user_check(array('username' => $user['username']))) {
			message('非常抱歉，此用户名已经被注册，你需要更换注册名称！');
		}
		$user['password'] = $_GPC['password'];
		if(istrlen($user['password']) < 8) {
			message('必须输入密码，且密码长度不得低于8位。');
		}
		$time=time();
		$user['remark'] = $_GPC['remark'];
		$user['starttime']= $time;
		$user['agentid']= $ids;
		$user['groupid']= $_GPC['groupid'];
		$user['endtime'] = strtotime($_GPC['endtime']);
		$timeadd = 0;  
		$uid = user_register($user);
		if($uid > 0) {
			unset($user['password']);
			message('用户增加成功！', url('agent/agent_user'));
		}
		message('增加用户失败，请稍候重试或联系网站管理员解决！');
	}
	$groups = pdo_fetchall("SELECT id, name FROM ".tablename('users_group')." ORDER BY id ASC");
	template('agent/agent_user');
}
if ($do == 'edit') {
	$id = intval($_GPC['uid']);
	$user = user_single($id);
	$founders = explode(',', $_W['config']['setting']['founder']);
	if(checksubmit('submit')) {
		load()->model('user');
		$record = array();
				if(!empty($_GPC['endtime'])) {
			$record['endtime'] = strtotime($_GPC['endtime']);
		}
		$record['uid'] = $id;
		if(!empty($_GPC['password'])||$_GPC['password']==$_GPC['repassword']){
			$record['password'] = $_GPC['password'];
			}
		
		$record['salt'] = $user['salt'];
		$record['remark'] = $_GPC['remark'];
		$record['endtime']= strtotime($_GPC['endtime']);
		$record['groupid'] = $_GPC['groupid'];
		user_update($record);
		message('保存用户资料成功！');
	}
	template('agent/agent_user');
	exit();
}

if ($do == 'delete') {
	$id = intval($_GPC['uid']);
	$user = user_single($id);
	$founders = explode(',', $_W['config']['setting']['founder']);
	if($_W['ispost'] && $_W['isajax']) {
		if (in_array($uid, $founders)) {
			message('访问错误, 无法操作站长.', url('agent/agent_show/uesr'), 'error');
		}
		if (empty($user)) {
			exit('未指定用户,无法删除.');
		}
		$founders = explode(',', $_W['config']['setting']['founder']);
		if(in_array($uid, $founders)) {
			exit('站长不能删除.');
		}
		if(pdo_delete('users', array('uid' => $id)) === 1) {
						cache_build_account_modules();
			pdo_delete('uni_account_users', array('id' => $id));
			pdo_delete('users_profile', array('id' => $id));
			exit('success');
		}
	}
}