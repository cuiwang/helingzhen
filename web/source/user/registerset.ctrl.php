<?php
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
defined('IN_IA') or exit('Access Denied');
load()->model('setting');

uni_user_permission_check('system_user_registerset');
$_W['page']['title'] = '注册选项 - 用户管理';


if (checksubmit('submit')) {
	setting_save(array('open' => intval($_GPC['open']), 'verify' => intval($_GPC['verify']), 'code' => intval($_GPC['code']), 'groupid' => intval($_GPC['groupid'])), 'register');
	cache_delete("defaultgroupid:{$_W['uniacid']}");
	itoast('更新设置成功！', url('user/registerset'), 'success');
}
$settings = $_W['setting']['register'];
$groups = user_group();

template('user/registerset');