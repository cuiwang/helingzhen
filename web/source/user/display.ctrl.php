<?php
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
defined('IN_IA') or exit('Access Denied');

load()->model('user');

$dos = array('display', 'operate');
$do = in_array($do, $dos) ? $do: 'display';

$_W['page']['title'] = '用户列表 - 用户管理';
$founders = explode(',', $_W['config']['setting']['founder']);

if ($do == 'display') {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$type = empty($_GPC['type']) ? 'display' : $_GPC['type'];
	if (in_array($type, array('display', 'check', 'recycle'))) {
		switch ($type) {
			case 'check':
				uni_user_permission_check('system_user_check');
				$condition['status'] = USER_STATUS_CHECK;
				break;
			case 'recycle':
				uni_user_permission_check('system_user_recycle');
				$condition['status'] = USER_STATUS_BAN;
				break;
			default:
				uni_user_permission_check('system_user');
				$condition['status'] = USER_STATUS_NORMAL;
				$condition['founder_groupid'] = array(ACCOUNT_MANAGE_GROUP_GENERAL, ACCOUNT_MANAGE_GROUP_FOUNDER);
				break;
		}
		if (!empty($_GPC['username'])) {
			$condition['username'] = trim($_GPC['username']);
		}

		$user_lists = user_list($condition, array($pindex, $psize));
		$users = $user_lists['list'];
		$total = $user_lists['total'];
		$pager = pagination($total, $pindex, $psize);

		$groups = user_group();
		$users = user_list_format($users);
	}
	template('user/display');
}

if ($do == 'operate') {
	$type = $_GPC['type'];
	$types = array('recycle', 'recycle_delete', 'recycle_restore', 'check_pass');
	if (!in_array($type, $types)) {
		itoast('类型错误！', referer(), 'fail');
	}
	switch ($type) {
		case 'check_pass':
			uni_user_permission_check('system_user_check');
			break;
		case 'recycle':
		case 'recycle_delete':
		case 'recycle_restore':
			uni_user_permission_check('system_user_recycle');
			break;
	}
	$uid = intval($_GPC['uid']);
	$uid_user = user_single($uid);
	if (in_array($uid, $founders)) {
		itoast('访问错误, 无法操作站长.', url('user/display'), 'error');
	}
	if (empty($uid_user)) {
		exit('未指定用户,无法删除.');
	}
	switch ($type) {
		case 'check_pass':
			$data = array('status' => 2);
			pdo_update('users', $data , array('uid' => $uid));
			itoast('更新成功！', referer(), 'success');
			break;
		case 'recycle':			user_delete($uid, true);
			itoast('更新成功！', referer(), 'success');
			break;
		case 'recycle_delete':			user_delete($uid);
			itoast('删除成功！', referer(), 'success');
			break;
		case 'recycle_restore':
			$data = array('status' => 2);
			pdo_update('users', $data , array('uid' => $uid));
			itoast('启用成功！', referer(), 'success');
			break;
	}
}