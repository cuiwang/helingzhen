<?php
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
defined('IN_IA') or exit('Access Denied');
load()->model('user');
$dos = array('rank', 'display', 'switch');
$do = in_array($_GPC['do'], $dos)? $do : 'display' ;
$_W['page']['title'] = '公众号列表 - 公众号';

$state = uni_permission($_W['uid'], $_W['uniacid']);
$account_info = uni_user_account_permission();

if($do == 'switch') {
	$uniacid = intval($_GPC['uniacid']);
	$role = uni_permission($_W['uid'], $uniacid);
	if(empty($role)) {
		itoast('操作失败, 非法访问.', '', 'error');
	}
	uni_account_save_switch($uniacid);
	$module_name = trim($_GPC['module_name']);
	$version_id = intval($_GPC['version_id']);
	if (empty($module_name)) {
		$url = url('home/welcome');
	} else {
		$url = url('home/welcome/ext', array('m' => $module_name, 'version_id' => $version_id));
	}
	uni_account_switch($uniacid, $url);
}

if ($do == 'rank' && $_W['isajax'] && $_W['ispost']) {
	$uniacid = intval($_GPC['id']);

	$exist = pdo_get('uni_account', array('uniacid' => $uniacid));
	if (empty($exist)) {
		iajax(1, '公众号不存在', '');
	}
	uni_account_rank_top($uniacid);
	iajax(0, '更新成功！', '');
}

if ($do == 'display') {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 15;
	$condition = array();
	$condition['type'] = array(ACCOUNT_TYPE_OFFCIAL_NORMAL, ACCOUNT_TYPE_OFFCIAL_AUTH);
	
	$keyword = trim($_GPC['keyword']);
	if (!empty($keyword)) {
		$condition['keyword'] = $keyword;
	}
	
	if(isset($_GPC['letter']) && strlen($_GPC['letter']) == 1) {
		$condition['letter'] = trim($_GPC['letter']);
	}

	$account_lists = uni_account_list($condition, array($pindex, $psize));
	$account_list = $account_lists['list'];

	if ($_W['isajax'] && $_W['ispost']) {
		iajax(0, $account_list);
	}
}

template('account/display');