<?php
/**
 */
defined('IN_IA') or exit('Access Denied');

if (in_array($action, array('permission', 'manage-account'))) {
	define('FRAME', 'account');
	checkaccount();
}
if (in_array($action, array('group', 'manage-system'))) {
	define('FRAME', 'system');
}

$_GPC['account_type'] = !empty($_GPC['account_type']) ? $_GPC['account_type'] : ACCOUNT_TYPE_OFFCIAL_NORMAL;
if ($_GPC['account_type'] == ACCOUNT_TYPE_APP_NORMAL) {
	define('ACCOUNT_TYPE', ACCOUNT_TYPE_APP_NORMAL);
	define('ACCOUNT_TYPE_TEMPLATE', '-wxapp');
} elseif ($_GPC['account_type'] == ACCOUNT_TYPE_OFFCIAL_NORMAL || $_GPC['account_type'] == ACCOUNT_TYPE_OFFCIAL_AUTH) {
	define('ACCOUNT_TYPE', ACCOUNT_TYPE_OFFCIAL_NORMAL);
	define('ACCOUNT_TYPE_TEMPLATE', '');
} else {
	define('ACCOUNT_TYPE', $_GPC['account_type']);
}