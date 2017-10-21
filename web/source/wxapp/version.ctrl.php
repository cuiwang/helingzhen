<?php
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
defined('IN_IA') or exit('Access Denied');

load()->model('module');
load()->model('wxapp');
load()->model('welcome');

$dos = array('display', 'home', 'module_link_uniacid', 'search_link_account', 'module_unlink_uniacid', 'get_daily_visittrend', 'front_download', 'module_entrance_link');
$do = in_array($do, $dos) ? $do : 'display';
if (in_array($do, array('module_link_uniacid', 'front_download', 'module_entrance_link'))) {
	uni_user_permission_check('wxapp_' . $do, true, 'wxapp');
}
$_W['page']['title'] = '小程序 - 管理';

$uniacid = intval($_GPC['uniacid']);
$version_id = intval($_GPC['version_id']);
if (!empty($uniacid)) {
	$wxapp_info = wxapp_fetch($uniacid);
}
if (!empty($version_id)) {
	$version_info = wxapp_version($version_id);
	$wxapp_info = wxapp_fetch($version_info['uniacid']);
}

if ($do == 'display') {
	$wxapp_version_list = wxapp_version_all($uniacid);
	template('wxapp/version-display');
}

if ($do == 'home') {
	if ($version_info['design_method'] == WXAPP_TEMPLATE) {
		$version_site_info = wxapp_site_info($version_info['multiid']);
	}
	$role = uni_permission($_W['uid'], $wxapp_info['uniacid']);

	$notices = welcome_notices_get();
	template('wxapp/version-home');
}

if ($do == 'module_link_uniacid') {
	$module_name = $_GPC['module_name'];

	$version_info = wxapp_version($version_id);

	if (checksubmit('submit')) {
		if (empty($module_name) || empty($uniacid)) {
			iajax('1', '参数错误！');
		}
		$module = module_fetch($module_name);
		if (empty($module)) {
			iajax('1', '模块不存在！');
		}
		$module_update = array();
		$module_update[$module['name']] = array('name' => $module['name'], 'version' => $module['version'], 'uniacid' => $uniacid);
		pdo_update('wxapp_versions', array('modules' => serialize($module_update)), array('id' => $version_id));
		iajax(0, '关联公众号成功');
	}
	template('wxapp/version-module-link-uniacid');
}

if ($do == 'module_unlink_uniacid') {
	if (!empty($version_info)) {
		$module = current($version_info['modules']);
		$version_modules = array(
				$module['name'] => array(
					'name' => $module['name'],
					'version' => $module['version']
					)
			);
	}
	$version_modules = serialize($version_modules);
	$result = pdo_update('wxapp_versions', array('modules' => $version_modules), array('id' => $version_info['id']));
	if ($result) {
		itoast('删除成功！', referer(), 'success');
	} else {
		itoast('删除失败！', referer(), 'error');
	}
}

if ($do == 'search_link_account') {
	$module_name = trim($_GPC['module_name']);
	if (empty($module_name)) {
		iajax(0, array());
	}
	$account_list = wxapp_search_link_account($module_name);
	iajax(0, $account_list);
}

if ($do == 'get_daily_visittrend') {
	wxapp_update_daily_visittrend();
		$yesterday = date('Ymd', strtotime('-1 days'));
	$yesterday_stat = pdo_get('wxapp_general_analysis', array('uniacid' => $_W['uniacid'], 'type' => '2', 'ref_date' => $yesterday));
	if (empty($yesterday_stat)) {
		$yesterday_stat = array('session_cnt' => 0, 'visit_pv' => 0, 'visit_uv' => 0, 'visit_uv_new' => 0);
	}
	iajax(0, array('yesterday' => $yesterday_stat), '');
}

if ($do == 'front_download') {
	$wxapp_versions_info = wxapp_version($version_id);
	//1上传待审核，2审核成功，3审核失败
	if($wxapp_info['status']==0){
		$show['sc']='未上传';
		$show['sh']='未审核';
		$show['sf']='未发布';
	}elseif($wxapp_info['status']==1){
		$show['sc']='已上传';
		$show['sh']='审核中';
		$show['sf']='未发布';
	}elseif($wxapp_info['status']==2){
		$show['sc']='已上传';
		$show['sh']='审核成功';
		$show['sf']='未发布';
	}elseif($wxapp_info['status']==3){
		$show['sc']='已上传';
		$show['sh']='审核失败';
		$show['sf']='未发布';
	}elseif($wxapp_info['status']==4){
		$show['sc']='已上传';
		$show['sh']='审核成功';
		$show['sf']='已发布';
	}
	if($wxapp_info['fail_reason']){
		$show['reason']=$wxapp_info['fail_reason'];
	}
	
	if(checksubmit()){
		$a_uniacid=intval($_GPC['a_uniacid']);
		pdo_update('account_wxapp',array('a_uniacid'=>$a_uniacid),array('uniacid'=>$wxapp_info['uniacid']));
		itoast('绑定成功！', referer(), 'success');
	}
	load()->model('account');
	$accounts=uni_account_list(array(),array());
	$accounts=$accounts['list'];
	template('wxapp/version-front-download');
}

if ($do == 'module_entrance_link') {
	$wxapp_modules = pdo_getcolumn('wxapp_versions', array('id' => $version_id), 'modules');
	$module_info = array();
	if (!empty($wxapp_modules)) {
		$module_info = iunserializer($wxapp_modules);
		$module_info = pdo_getall('modules_bindings', array('module' => array_keys($module_info), 'entry' => 'page'));
	}
	template('wxapp/version-entrance');
}
