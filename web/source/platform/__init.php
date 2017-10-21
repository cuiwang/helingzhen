<?php
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
defined('IN_IA') or exit('Access Denied');

checkaccount();

if ($action != 'material-post') {
	define('FRAME', 'account');
}
if ($action == 'qr') {
	$platform_qr_permission =  uni_user_permission_check('platform_qr', false);
	if ($platform_qr_permission ===  false) {
		header("Location: ". url('platform/url2qr'));
	}
}

if ($action == 'url2qr') {
	define('ACTIVE_FRAME_URL', url('platform/qr'));
}