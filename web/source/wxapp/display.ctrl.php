<?php
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
defined('IN_IA') or exit('Access Denied');
load()->model('wxapp');
load()->model('account');

$_W['page']['title'] = '小程序列表';

$dos = array('display', 'switch', 'rank', 'home');
$do = in_array($do, $dos) ? $do : 'display';
if ($do == 'rank' || $do == 'switch') {
	$uniacid = intval($_GPC['uniacid']);
	if (!empty($uniacid)) {
		$wxapp_info = wxapp_fetch($uniacid);
		if (empty($wxapp_info)) {
			itoast('小程序不存在', referer(), 'error');
		}
	}
}
if ($do == 'home') {
	$last_uniacid = uni_account_last_switch();
	$url = url('wxapp/display');
	if (empty($last_uniacid)) {
		itoast('', $url, 'info');
	}
	$permission = uni_permission($_W['uid'], $last_uniacid);
	if (empty($permission)) {
		itoast('', $url, 'info');
	}
	$last_version = wxapp_fetch($last_uniacid);
	if (!empty($last_version)) {
		uni_account_switch($last_uniacid);
		$url = url('wxapp/version/home', array('version_id' => $last_version['version']['id']));
	}
	itoast('', $url, 'info');
} elseif ($do == 'display') {
		$account_info = uni_user_account_permission();

	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$condition = array();
	$keyword = trim($_GPC['keyword']);

	$condition['type'] = array(ACCOUNT_TYPE_APP_NORMAL);

	if (!empty($keyword)) {
		$condition['keyword'] = trim($_GPC['keyword']);
	}
	if(isset($_GPC['letter']) && strlen($_GPC['letter']) == 1) {
		$condition['letter'] = trim($_GPC['letter']);
	}

	$wxapp_account_lists = uni_account_list($condition, array($pindex, $psize));
	
	$wxapp_lists = $wxapp_account_lists['list'];
	$total = $wxapp_account_lists['total'];
	
	if (!empty($wxapp_lists)) {
		$wxapp_cookie_uniacids = array();
		if (!empty($_GPC['__wxappversionids'])) {
			$wxappversionids = json_decode(htmlspecialchars_decode($_GPC['__wxappversionids']), true);
			foreach ($wxappversionids as $version_val) {
				$wxapp_cookie_uniacids[] = $version_val['uniacid'];
			}
		}
	}
	$pager = pagination($total, $pindex, $psize);
	template('wxapp/account-display');
} elseif ($do == 'switch') {
	$module_name = trim($_GPC['module']);
	$version_id = !empty($_GPC['version_id']) ? intval($_GPC['version_id']) : $wxapp_info['version']['id'];
	if (!empty($module_name) && !empty($version_id)) {
		$version_info = wxapp_version($version_id);
		$module_info = array();
		if (!empty($version_info['modules'])) {
			foreach ($version_info['modules'] as $key => $module_val) {
				if ($module_val['name'] == $module_name) {
					$module_info = $module_val;
				}
			}
		}
		if (empty($version_id) || empty($module_info)) {
			itoast('版本信息错误');
		}
		$uniacid = !empty($module_info['account']['uniacid']) ? $module_info['account']['uniacid'] : $version_info['uniacid'];
		uni_account_switch($uniacid, url('home/welcome/ext/', array('m' => $module_name, 'version_id' => $version_id)));
	}
	uni_account_switch($uniacid);
	wxapp_save_switch($uniacid);
	wxapp_update_last_use_version($uniacid, $version_id);
	header('Location: ' . url('wxapp/version/home', array('version_id' => $version_id)));
	exit;
} elseif ($do == 'rank') {
	uni_account_rank_top($uniacid);
	itoast('更新成功', '', '');
}