<?php
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
defined('IN_IA') or exit('Access Denied');
if (in_array($action, array('site', 'mbsite', 'menu', 'attachment', 'systeminfo', 'logs', 'filecheck', 'optimize', 'database', 'scan', 'bom', 'addons', 'ipwhitelist'))) {
	define('FRAME', 'site');
} else {
	define('FRAME', 'system');
}
$_GPC['account_type'] = !empty($_GPC['account_type']) ? $_GPC['account_type'] : ACCOUNT_TYPE_OFFCIAL_NORMAL;
if ($_GPC['account_type'] == ACCOUNT_TYPE_APP_NORMAL) {
	define('ACCOUNT_TYPE', ACCOUNT_TYPE_APP_NORMAL);
	define('ACCOUNT_TYPE_OFFCIAL', 0);
	define('ACCOUNT_TYPE_TEMPLATE', '-wxapp');
} elseif ($_GPC['account_type'] == ACCOUNT_TYPE_OFFCIAL_NORMAL || $_GPC['account_type'] == ACCOUNT_TYPE_OFFCIAL_AUTH) {
	define('ACCOUNT_TYPE', ACCOUNT_TYPE_OFFCIAL_NORMAL);
	$account_type_offcial = $_GPC['account_type'] == ACCOUNT_TYPE_OFFCIAL_NORMAL ? ACCOUNT_TYPE_OFFCIAL_NORMAL : ACCOUNT_TYPE_OFFCIAL_AUTH;
	define('ACCOUNT_TYPE_OFFCIAL', $account_type_offcial);
	define('ACCOUNT_TYPE_TEMPLATE', '');
} else {
	define('ACCOUNT_TYPE', $_GPC['account_type']);
}
