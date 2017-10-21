<?php

defined('IN_IA') or exit('Access Denied');

global $_W, $_GPC;
load()->model('mc');

$ops = array('login', 'register');
$op = in_array(trim($_GPC['op']), $ops) ? trim($_GPC['op']) : 'error';

check_params();

//登录
if ($op == 'login') {
	$set = get_hotel_set();
	$member = array();
	$username = trim($_GPC['username']);
	if (empty($username)) {
		message(error(-1, '请输入要登录的用户名'), '', 'ajax');
	}
	$member['username'] = $username;
	$member['password'] = $_GPC['password'];
	if (empty($member['password'])) {
		message(error(-1, '请输入登录密码'), '', 'ajax');
	}
	$member['weid'] = $_W['uniacid'];
	$record = hotel_member_single($member);
	if (!empty($record)) {
		if ( ($set['bind'] == 3 && ($record['userbind'] == 1) || $set['bind'] == 2)) {
			if (!empty($record['from_user'])) {
				if ($record['from_user'] != $_W['openid']) {
					message(error(-1, '登录失败，您的帐号与绑定的微信帐号不符！'), '', 'ajax');
				}
			}
		}
		if (empty($record['status'])) {
			message(error(-1, '登录失败，您的帐号被禁止登录！'), '', 'ajax');
		}
		$record['user_set'] = $set['user'];
		//登录成功
		hotel_set_userinfo(0, $record);
		message(error(0, '登录成功！'), '', 'ajax');
	} else {
		message(error(-1, '登录失败，请检查您输入的用户名和密码！'), '', 'ajax');
	}
}
//注册
if ($op == 'register') {
	$set = get_hotel_set();
	$member = array();
	$member['from_user'] = trim($_W['openid'],' ');
	$member['username'] = $_GPC['username'];
	$member['password'] = $_GPC['password'];
	if (!preg_match(REGULAR_USERNAME, $member['username'])) {
		message(error(-1, '必须输入用户名，格式为 3-15 位字符，可以包括汉字、字母（不区分大小写）、数字、下划线和句点。'), '', 'ajax');
	}

	if (hotel_member_check(array('from_user' => $member['from_user'], 'weid' => intval($_W['uniacid'])))) {
		message(error(-1, '非常抱歉，此用微信号已经被注册，你可以直接使用注册时的用户名登录，或者更换微信号注册！'), '', 'ajax');
	}

	if (hotel_member_check(array('username' => $member['username'], 'weid' => intval($_W['uniacid'])))) {
		message(error(-1, '非常抱歉，此用户名已经被注册，你需要更换注册用户名！'), '', 'ajax');
	}

	if (istrlen($member['password']) < 6) {
		message(error(-1, '必须输入密码，且密码长度不得低于6位！'), '', 'ajax');
	}
	$member['salt'] = random(8);
	$member['password'] = hotel_member_hash($member['password'], $member['salt']);
	
	$member['weid'] = intval($_W['uniacid']);
	$member['mobile'] = $_GPC['mobile'];
	$member['realname'] = $_GPC['realname'];
	$member['createtime'] = time();
	$member['status'] = 1;
	$member['isauto'] = 0;
	pdo_insert('storex_member', $member);
	$member['id'] = pdo_insertid();
	$member['user_set'] = $set['user'];
	//注册成功
	hotel_set_userinfo(1, $member);
	message(error(0, '注册成功！'), '', 'ajax');
}