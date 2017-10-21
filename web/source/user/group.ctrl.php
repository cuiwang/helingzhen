<?php
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
defined('IN_IA') or exit('Access Denied');

load()->model('user');

$dos = array('display', 'post', 'del');
$do = !empty($_GPC['do']) ? $_GPC['do'] : 'display';

if ($do == 'display') {
	uni_user_permission_check('system_user_group');
	$_W['page']['title'] = '用户组列表 - 用户组 - 用户管理';
	$condition = '' ;
	$params = array();
	if (!empty($_GPC['name'])) {
		$condition .= "WHERE name LIKE :name";
		$params[':name'] = "%{$_GPC['name']}%";
	}
	if (user_is_vice_founder()) {
		$condition .= "WHERE owner_uid = :owner_uid";
		$params[':owner_uid'] = $_W['uid'];
	}
	$lists = pdo_fetchall("SELECT * FROM " . tablename('users_group').$condition, $params);
	$lists = user_group_format($lists);
	template('user/group-display');
}

if ($do == 'post') {
	uni_user_permission_check('system_user_group_post');
	$id = is_array($_GPC['id']) ? 0 : intval($_GPC['id']);
	$_W['page']['title'] = $id ? '编辑用户组 - 用户组 - 用户管理' : '添加用户组 - 用户组 - 用户管理';
	if (!empty($id)) {
		$group_info = pdo_fetch("SELECT * FROM ".tablename('users_group') . " WHERE id = :id", array(':id' => $id));
		$group_info['package'] = iunserializer($group_info['package']);
		if (!empty($group_info['package']) && in_array(-1, $group_info['package'])) $group_info['check_all'] = true;
	}
	$packages = uni_groups();
	if (!empty($packages)) {
		foreach ($packages as $key => &$package_val) {
			if (!empty($group_info['package']) && in_array($key, $group_info['package'])) {
				$package_val['checked'] = true;
			} else {
				$package_val['checked'] = false;
			}
		}
	}
	unset($package_val);
	if (checksubmit('submit')) {

		$user_group = array(
			'id' => intval($_GPC['id']),
			'name' => $_GPC['name'],
			'package' => $_GPC['package'],
			'maxaccount' => intval($_GPC['maxaccount']),
			'maxwxapp' => intval($_GPC['maxwxapp']),
			'discount' =>  intval($_GPC['discount']),
			'price' =>  $_GPC['price'],
			'timelimit' => intval($_GPC['timelimit']),
			'domain' => intval($_GPC['domain'])
		);

		$user_group_info = user_save_group($user_group);
		if (is_error($user_group_info)) {
			itoast($user_group_info['message'], '', '');
		}
		itoast('用户组更新成功！', url('user/group/display'), 'success');
	}
	template('user/group-post');
}

if ($do == 'del') {
	uni_user_permission_check('system_user_group_del');
	$id = intval($_GPC['id']);
	$result = pdo_delete('users_group', array('id' => $id));
	if(!empty($result)){
		itoast('删除成功！', url('user/group/display'), 'success');
	}else {
		itoast('删除失败！请稍候重试！', url('user/group'), 'error');
	}
	exit;
}