<?php
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
if ($action != 'display') {
	define('FRAME', 'system');
}
if ($controller == 'account' && $action == 'manage') {
	if ($_GPC['account_type'] == ACCOUNT_TYPE_APP_NORMAL) {
		define('ACTIVE_FRAME_URL', url('account/manage/display', array('account_type' => ACCOUNT_TYPE_APP_NORMAL)));
	}
}
$_GPC['account_type'] = !empty($_GPC['account_type']) ? $_GPC['account_type'] : ACCOUNT_TYPE_OFFCIAL_NORMAL;
if ($_GPC['account_type'] == ACCOUNT_TYPE_APP_NORMAL) {
	define('ACCOUNT_TYPE', ACCOUNT_TYPE_APP_NORMAL);
	define('ACCOUNT_TYPE_OFFCIAL', 0);
	define('ACCOUNT_TYPE_NAME', '小程序');
	define('ACCOUNT_TYPE_TEMPLATE', '-wxapp');
	define('ACCOUNT_TYPE_SUPPORT', 'wxapp_support');
} elseif (empty($_GPC['account_type']) || $_GPC['account_type'] == ACCOUNT_TYPE_OFFCIAL_NORMAL || $_GPC['account_type'] == ACCOUNT_TYPE_OFFCIAL_AUTH) {
	define('ACCOUNT_TYPE', ACCOUNT_TYPE_OFFCIAL_NORMAL);
	$account_type_offcial = $_GPC['account_type'] == ACCOUNT_TYPE_OFFCIAL_NORMAL ? ACCOUNT_TYPE_OFFCIAL_NORMAL : ACCOUNT_TYPE_OFFCIAL_AUTH;
	define('ACCOUNT_TYPE_OFFCIAL', $account_type_offcial);
	define('ACCOUNT_TYPE_NAME', '公众号');
	define('ACCOUNT_TYPE_TEMPLATE', '');
	define('ACCOUNT_TYPE_SUPPORT', 'app_support');
}
