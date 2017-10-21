<?php
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */

defined('IN_IA') or exit('Access Denied');

$dos = array('oauth', 'save_oauth', 'uc_setting', 'upload_file');
$do = in_array($do, $dos) ? $do : 'oauth';
uni_user_permission_check('profile_setting');
$_W['page']['title'] = '公众平台oAuth选项 - 会员中心';

if ($do == 'save_oauth') {
	$type = $_GPC['type'];
	$account = trim($_GPC['account']);
	if ($type == 'oauth') {
		$host = $_GPC['host'];
		$host = rtrim($host,'/');
		if(!empty($host) && !preg_match('/^http(s)?:\/\//', $host)) {
			$host = $_W['sitescheme'].$host;
		}
		$data = array(
			'host' => $host,
			'account' => $account,
		);
		pdo_update('uni_settings', array('oauth' => iserializer($data)), array('uniacid' => $_W['uniacid']));
		cache_delete("unisetting:{$_W['uniacid']}");
	}
	if ($type == 'jsoauth') {
		pdo_update('uni_settings', array('jsauth_acid' => $account), array('uniacid' => $_W['uniacid']));
		cache_delete("unisetting:{$_W['uniacid']}");
	}
	iajax(0, '');
}

if ($do == 'oauth') {
	$user_have_accounts = uni_user_accounts($_W['uid']);
	$oauth_accounts = array();
	$jsoauth_accounts = array();
	if(!empty($user_have_accounts)) {
		foreach($user_have_accounts as $account) {
			if(!empty($account['key']) && !empty($account['secret'])) {
				if (in_array($account['level'], array(4))) {
					$oauth_accounts[$account['acid']] = $account['name'];
				}
				if (in_array($account['level'], array(3, 4))) {
					$jsoauth_accounts[$account['acid']] = $account['name'];
				}
			}
		}
	}
	$oauth = pdo_fetchcolumn('SELECT `oauth` FROM '.tablename('uni_settings').' WHERE `uniacid` = :uniacid LIMIT 1',array(':uniacid' => $_W['uniacid']));
	$oauth = iunserializer($oauth) ? iunserializer($oauth) : array();
	$jsoauth = pdo_getcolumn('uni_settings', array('uniacid' => $_W['uniacid']), 'jsauth_acid');
}

template('profile/passport');
