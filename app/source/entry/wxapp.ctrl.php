<?php
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
 
defined('IN_IA') or exit('Access Denied');
if (strexists($_SERVER['HTTP_REFERER'], 'https://servicewechat.com/')) {
	$referer_url = parse_url($_SERVER['HTTP_REFERER']);
	list($appid, $version) = explode('/', ltrim($referer_url['path'], '/'));
}

$site = WeUtility::createModuleWxapp($entry['module']);
if(!is_error($site)) {
	$site->appid = $appid;
	$site->version = $version;
	$method = 'doPage' . ucfirst($entry['do']);
	if (!empty($site->token)) {
		if (!$site->checkSign()) {
			message(error(1, '签名错误'), '', 'ajax');
		}
	}
	if (!empty($_GPC['state']) && strexists($_GPC['state'], 'we7sid-') && (empty($_W['openid']) || empty($_SESSION['openid']))) {
		$site->result(41009, '请登录');
	}
	if (!empty($_W['uniacid'])) {
		$version = trim($_GPC['v']);
		$version_info = pdo_get('wxapp_versions', array('uniacid' => $_W['uniacid'], 'version' => $version), array('id', 'uniacid', 'template', 'modules'));
		if (!empty($version_info['modules'])) {
			$connection = iunserializer($version_info['modules'], true);
			if (!empty($connection[$entry['module']])) {
				$uniacid = intval($connection[$entry['module']]['uniacid']);
				if (!empty($uniacid)) {
					$_W['uniacid'] = $uniacid;
					$_W['account']['link_uniacid'] = $uniacid;
				}
			}
		}
	}
	exit($site->$method());
}
exit();