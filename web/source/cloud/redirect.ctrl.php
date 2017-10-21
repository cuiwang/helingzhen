<?php 
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */

if(empty($_W['isfounder'])) {
	message('访问非法.');
}
$do = in_array($do, array('profile', 'device', 'callback', 'appstore', 'buyversion', 'buybranch', 'sms')) ? $do : 'profile';
$authurl = 'http://addons2.weizancms.com/web/index.php?c=auth&a=passwort';

$auth = array();
$auth['key'] = '';
$auth['password'] = '';
$auth['url'] = rtrim($_W['siteroot'], '/');
$auth['referrer'] = intval($_W['config']['setting']['referrer']);
$auth['version'] = IMS_VERSION;
if(!empty($_GPC['op'])){
	$auth['extra']['do']=trim($_GPC['op']);
}
if(!empty($_GPC['module'])){
	$auth['extra']['module']=trim($_GPC['module']);
}
if(!empty($_W['setting']['site']['key']) && !empty($_W['setting']['site']['token'])) {
	$auth['key'] = $_W['setting']['site']['key'];
	$auth['password'] = md5($_W['setting']['site']['key'] . $_W['setting']['site']['token']);
}

if($do == 'profile') {
	$auth['forward'] = 'profile';
	$iframe = __to($auth);
	$title = '注册站点';
}

if($do == 'sms') {
	$auth['forward'] = 'sms';
	$iframe = __to($auth);
	$title = '云短信';
}
if($do == 'appstore') {
	$auth['forward'] = 'appstore';
	$iframe = __to($auth);
	$title = '应用商城';
	header("Location: $iframe");
	exit;
}

if($do == 'device') {
	$auth['forward'] = 'device';
	$iframe = __to($auth);
	$title = '微信设备';
}

if($do == 'promotion') {
	if(empty($_W['setting']['site']['key']) || empty($_W['setting']['site']['token'])) {
		message("你的程序需要在系统云服务平台注册你的站点资料, 来接入云平台服务后才能使用推广功能.", url('cloud/profile'), 'error');
	}
	$auth['forward'] = 'promotion';
	$iframe = __to($auth);
	$title = '我要推广';
}

if ($do == 'buyversion') {
	load()->func('communication');
	$auth['forward'] = 'buyversion';
	$auth['name'] = $_GPC['m'];
	$auth['is_upgrade'] = 1;
	$auth['version'] = $_GPC['version'];
	$url = __to($auth);
	$response = ihttp_request($url);
	if (is_error($response)) {
		message($response['message'], '', 'error');
	}
	$response = json_decode($response['content'], true);
	switch ($response['message']['errno']) {
		case '-1':
		case '-2':
			message('模块不存在或是未有更新的版本。', url('extension/module'), 'error');
		break;
		case '-3':
			message('您的微币不足以支付此次升级费用。', url('extension/module'), 'error');
		break;
		case '2':
			message('您已经购买过此升级版本，系统将直接跳转至升级界面。', url('cloud/process', array('m' => $auth['name'], 'is_upgrade' => 1, 'is_buy' => 1)), 'success');
			break;
		case '1':
			message('购买模块升级版本成功，系统将直接跳转至升级界面。', url('cloud/process', array('m' => $auth['name'], 'is_upgrade' => 1, 'is_buy' => 1)), 'success');
			exit;
		break;
	}
	message($response['message']['message']);
}

if ($do == 'buybranch') {
	load()->func('communication');
	$auth['forward'] = 'buybranch';
	$auth['name'] = $_GPC['m'];
	$auth['branch'] = intval($_GPC['branch']);
	$url = __to($auth);

	$response = ihttp_request($url);
	$response = json_decode($response['content'], true);

	if (is_error($response['message'])) {
		message($response['message']['message'], url('extension/module'), 'error');
	}

	$params = array(
		'is_upgrade' => 1,
		'is_buy' => 1,
	);
	if (trim($_GPC['type']) == 'theme') {
		$params['t'] = $auth['name'];
	} else {
		$params['m'] = $auth['name'];
	}

	message($response['message']['message'], url('cloud/process', $params), 'success');
}

if($do == 'callback') {
	$secret = $_GPC['token'];
	if(strlen($secret) == 32) {
		$cache = cache_read('cloud:auth:transfer');
		cache_delete('cloud:auth:transfer');
		if(!empty($cache) && $cache['secret'] == $secret) {
			$site = array_elements(array('key', 'token'), $cache);
			setting_save($site, 'site');
			$auth['key'] = $site['key'];
			$auth['password'] = md5($site['key'] . $site['token']);
			$auth['forward'] = 'profile';
			header('location: ' . __to($auth));
			exit();
		}
	}
	message('访问错误.');
}

template('cloud/frame');

function __to($auth) {
	global $authurl;
	$query = base64_encode(json_encode($auth));
	return $authurl . '&__auth=' . $query;
}
