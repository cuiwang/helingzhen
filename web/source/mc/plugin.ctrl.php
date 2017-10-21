<?php
/**
 * [WECHAT 2017]
 
 */
defined('IN_IA') or exit('Access Denied');
$dos = array('display', 'enable');
$do = !empty($_GPC['do']) ? $_GPC['do'] : 'display';
uni_user_permission_check('mc_plugin');
load()->model('mc');

$plugins = mc_plugins();
$unisetting = uni_setting_load('mcplugin');
$account_plugins = !empty($unisetting['mcplugin']) ? $unisetting['mcplugin'] : array();

if($do == 'display') {
	$_W['page']['title'] = '会员 - 功能管理';
	template('mc/plugin');
} elseif ($do == 'enable') {
	$name = $_GPC['name'];
	if(empty($plugins[$name])) {
		message('抱歉，你操作的功能插件不存在！');
	}
	if (empty($_GPC['enabled'])) {
		unset($account_plugins[$name]);
	} else {
		$account_plugins[$name] = $name;
	}
	uni_setting_save('mcplugin', $account_plugins);
	message('功能插件操作成功！', referer(), 'success');
}