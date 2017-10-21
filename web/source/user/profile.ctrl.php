<?php
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
defined('IN_IA') or exit('Access Denied');
load()->model('user');
load()->func('file');

$dos = array('base', 'post');
$do = in_array($do, $dos) ? $do : 'base';
$_W['page']['title'] = '账号信息 - 我的账户 - 用户管理';

if ($do == 'post' && $_W['isajax'] && $_W['ispost']) {
	$type = trim($_GPC['type']);

	if ($_W['isfounder']) {
		$uid = is_array($_GPC['uid']) ? 0 : intval($_GPC['uid']);
	} else {
		$uid = $_W['uid'];
	}
	if (empty($uid) || empty($type)) {
		iajax(40035, '参数错误，请刷新后重试！', '');
	}
	$user = user_single($uid);
	if (empty($user)) {
		iajax(-1, '用户不存在或已经被删除！', '');
	}

	$users_profile_exist = pdo_get('users_profile', array('uid' => $uid));

	if ($type == 'birth') {
		if ($users_profile_exist['year'] == $_GPC['year'] && $users_profile_exist['month'] == $_GPC['month'] && $users_profile_exist['day'] == $_GPC['day']) iajax(0, '未作修改！', '');
	} elseif ($type == 'reside') {
		if ($users_profile_exist['province'] == $_GPC['province'] && $users_profile_exist['city'] == $_GPC['city'] && $users_profile_exist['district'] == $_GPC['district']) iajax(0, '未作修改！', '');
	} else {
		if (in_array($type, array('username', 'password'))) {
			if ($user[$type] == $_GPC[$type] && $type != 'password') iajax(0, '未做修改！', '');
		} else {
			if ($users_profile_exist[$type] == $_GPC[$type]) iajax(0, '未作修改！', '');
		}
	}
	switch ($type) {
		case 'avatar':
		case 'realname':
		case 'address':
		case 'qq':
		case 'mobile':
			if ($type == 'mobile') {
				$match = preg_match(REGULAR_MOBILE, trim($_GPC[$type]));
				if (empty($match)) {
					iajax(-1, '手机号不正确', '');
				}
			}
			if ($users_profile_exist) {
				$result = pdo_update('users_profile', array($type => trim($_GPC[$type])), array('uid' => $uid));
			} else {
				$data = array(
					'uid' => $uid,
					'createtime' => TIMESTAMP,
					$type => trim($_GPC[$type])
				);
				$result = pdo_insert('users_profile', $data);
			}
			break;
		case 'username':
			$founders = explode(',', $_W['config']['setting']['founder']);
			if (in_array($uid, $founders)) {
				iajax(1, '用户名不可与网站创始人同名！', '');
			}
			$username = trim($_GPC['username']);
			$name_exist = pdo_get('users', array('username' => $username));
			if (!empty($name_exist)) {
				iajax(2, '用户名已存在，请更换其他用户名！', '');
			}
			$result = pdo_update('users', array('username' => $username), array('uid' => $uid));
			break;
		case 'vice_founder_name':
			$owner_uid = user_get_uid_byname($_GPC['vice_founder_name']);
			if (empty($owner_uid)) {
				iajax(1, '创始人不存在', '');
			}
			$result = pdo_update('users', array('owner_uid' => $owner_uid), array('uid' => $uid));
			break;
		case 'remark':
			$result = pdo_update('users', array('remark' => trim($_GPC['remark'])), array('uid' => $uid));
			break;
		case 'password':
			if ($_GPC['newpwd'] !== $_GPC['renewpwd']) iajax(2, '两次密码不一致！', '');
			if (!$_W['isfounder']) {
				$pwd = user_hash($_GPC['oldpwd'], $user['salt']);
				if ($pwd != $user['password']) iajax(3, '原密码不正确！', '');
			}
			$newpwd = user_hash($_GPC['newpwd'], $user['salt']);
			if ($newpwd == $user['password']) {
				iajax(0, '未作修改！', '');
			}
			$result = pdo_update('users', array('password' => $newpwd), array('uid' => $uid));
			break;
		case 'endtime' :
			if ($_GPC['endtype'] == 1) {
				$endtime = 0;
			} else {
				$endtime = strtotime($_GPC['endtime']);
			}
			$result = pdo_update('users', array('endtime' => $endtime), array('uid' => $uid));
			$uni_account_user = pdo_getall('uni_account_users', array('uid' => $uid, 'role' => 'owner'));
			if (!empty($uni_account_user)) {
				foreach ($uni_account_user as $account) {
					cache_delete("uniaccount:{$account['uniacid']}");
				}
			}
			break;
		case 'birth':
			if ($users_profile_exist) {
				$result = pdo_update('users_profile', array('birthyear' => intval($_GPC['year']), 'birthmonth' => intval($_GPC['month']), 'birthday' => intval($_GPC['day'])), array('uid' => $uid));
			} else {
				$data = array(
					'uid' => $uid,
					'createtime' => TIMESTAMP,
					'birthyear' => intval($_GPC['year']),
					'birthmonth' => intval($_GPC['month']),
					'birthday' => intval($_GPC['day'])
				);
				$result = pdo_insert('users_profile', $data);
			}
			break;
		case 'reside':
			if ($users_profile_exist) {
				$result = pdo_update('users_profile', array('resideprovince' => $_GPC['province'], 'residecity' => $_GPC['city'], 'residedist' => $_GPC['district']), array('uid' => $uid));
			} else {
				$data = array(
					'uid' => $uid,
					'createtime' => TIMESTAMP,
					'resideprovince' => $_GPC['province'],
					'residecity' => $_GPC['city'],
					'residedist' => $_GPC['district']
				);
				$result = pdo_insert('users_profile', $data);
			}
			break;
	}
	if ($result) {
		pdo_update('users_profile', array('edittime' => TIMESTAMP), array('uid' => $uid));
		iajax(0, '修改成功！', '');
	} else {
		iajax(1, '修改失败，请稍候重试！', '');
	}
}

if ($do == 'base') {
	$user_type = !empty($_GPC['user_type']) ? trim($_GPC['user_type']) : PERSONAL_BASE_TYPE;
		$user = user_single($_W['uid']);
	if (empty($user)) {
		itoast('抱歉，用户不存在或是已经被删除！', url('user/profile'), 'error');
	}
	$user['last_visit'] = date('Y-m-d H:i:s', $user['lastvisit']);
	$user['joindate'] = date('Y-m-d H:i:s', $user['joindate']);
	$user['url'] = user_invite_register_url($_W['uid']);

	$profile = pdo_get('users_profile', array('uid' => $_W['uid']));

	$profile = user_detail_formate($profile);

		if ($_W['user']['founder_groupid'] == ACCOUNT_MANAGE_GROUP_VICE_FOUNDER) {
		$groups = user_founder_group();
		$group_info = user_founder_group_detail_info($user['groupid']);
	} else {
		$groups = user_group();
		$group_info = user_group_detail_info($user['groupid']);
	}

		$account_detail = user_account_detail_info($_W['uid']);

	template('user/profile');
}