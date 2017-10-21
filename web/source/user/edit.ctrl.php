<?php
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
defined('IN_IA') or exit('Access Denied');

load()->model('user');
load()->func('file');

$dos = array('edit_base', 'edit_modules_tpl', 'edit_account');
$do = in_array($do, $dos) ? $do: 'edit_base';
uni_user_permission_check('system_user_post');

$_W['page']['title'] = '编辑用户 - 用户管理';

$uid = intval($_GPC['uid']);
$user = user_single($uid);
if (empty($user)) {
	itoast('访问错误, 未找到该操作员.', url('user/display'), 'error');
} else {
	if ($user['status'] == 1) itoast('访问错误，该用户未审核通过，请先审核通过再修改！', url('user/display/check_display'), 'error');
	if ($user['status'] == 3) itoast('访问错误，该用户已被禁用，请先启用再修改！', url('user/display/recycle_display'), 'error');
}
$founders = explode(',', $_W['config']['setting']['founder']);
$profile = pdo_get('users_profile', array('uid' => $uid));
if (!empty($profile)) $profile['avatar'] = tomedia($profile['avatar']);

if ($do == 'edit_base') {
	$user['last_visit'] = date('Y-m-d H:i:s', $user['lastvisit']);
	$user['joindate'] = date('Y-m-d H:i:s', $user['joindate']);
	$user['end'] = $user['endtime'] == 0 ? '永久' : date('Y-m-d', $user['endtime']);
	$user['endtype'] = $user['endtime'] == 0 ? 1 : 2;
	$user['url'] = user_invite_register_url($uid);
	if(checksubmit('submit')) {
			$update = array(
				'credit2' => $_GPC['credit2'],
			);
			pdo_update('users', $update, array('uid' => $uid));
            itoast('设置成功！', '', 'success');
    }
	$profile = user_detail_formate($profile);
	template('user/edit-base');
}
if ($do == 'edit_modules_tpl') {
	if ($_W['isajax'] && $_W['ispost']) {
		if (intval($_GPC['groupid']) == $user['groupid']){
			iajax(2, '未做更改！');
		}
		if (!empty($_GPC['type']) && !empty($_GPC['groupid'])) {
			$data['uid'] = $uid;
			$data[$_GPC['type']] = intval($_GPC['groupid']);
			if (user_update($data)) {
				$group_info = user_group_detail_info($_GPC['groupid']);
				cache_clean();
				iajax(0, $group_info, '');
			} else {
				iajax(1, '更改失败！', '');
			}
		} else {
			iajax(-1, '参数错误！', '');
		}
	}
	$groups = user_group();
	$group_info = user_group_detail_info($user['groupid']);
	template('user/edit-modules-tpl');
}

if ($do == 'edit_account') {
	$account_detail = user_account_detail_info($uid);
	template('user/edit-account');
}