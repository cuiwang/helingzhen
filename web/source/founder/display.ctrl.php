<?php
/**
 */
defined('IN_IA') or exit('Access Denied');

load()->model('user');

$dos = array('display', 'del');
$do = in_array($do, $dos) ? $do: 'display';

$_W['page']['title'] = '用户列表 - 用户管理';
$founders = explode(',', $_W['config']['setting']['founder']);

if ($do == 'display') {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;

	$condition['founder_groupid'] = array(ACCOUNT_MANAGE_GROUP_VICE_FOUNDER);
	if (!empty($_GPC['username'])) {
		$condition['username'] = trim($_GPC['username']);
	}

	$user_lists = user_list($condition, array($pindex, $psize));
	$users = $user_lists['list'];
	$total = $user_lists['total'];
	$pager = pagination($total, $pindex, $psize);

	$groups = user_group();
	$users = user_list_format($users);
	template('founder/display');
}

if ($do == 'del') {
	$uid = intval($_GPC['uid']);
	$uid_user = user_single($uid);
	if (in_array($uid, $founders)) {
		itoast('访问错误, 无法操作站长.', url('founder/display'), 'error');
	}
	if (empty($uid_user)) {
		exit('未指定用户,无法删除.');
	}
	user_delete($uid);
	itoast('删除成功！', referer(), 'success');
}