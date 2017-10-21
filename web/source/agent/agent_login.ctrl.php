<?php 
defined('IN_IA') or exit('Access Denied');
session_start();
if(checksubmit('submit')) {
	login();
}
$setting = $_W['setting'];
template('agent/agent_login');


function login($forward = ''){
	global $_GPC, $_W;
	load()->model('user');
	$member = array();
	$username = trim($_GPC['name']);
	if (!empty($_W['setting']['copyright']['verifycode'])) {
		$verify = trim($_GPC['verify']);
		if(empty($verify)) {
			message('请输入验证码');
		}
		$result = checkcaptcha($verify);
		if (empty($result)) {
			message('输入验证码错误');
		}
	}
	if(empty($username)) {
		message('请输入要登录的用户名');
	}
	$member['name'] = $username;
	$member['password'] = $_GPC['password'];
	if(empty($member['password'])) {
		message('请输入密码');
	}
	$record = agent_single($member);
	$now = time();
	if(!empty($record)) {
		if(empty($forward)) {
			$forward = './index.php?c=agent&a=agent_show';
		}
		$_SESSION['id']=$record['id'];
		$_SESSION['name']=$record['name'];
		message("欢迎回来，{$record['name']}!", $forward);
	}else{
		message('登录失败，请检查您输入的用户名和密码！');
		}
	}
