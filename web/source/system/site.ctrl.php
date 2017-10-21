<?php 
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
defined('IN_IA') or exit('Access Denied');
$dos = array('copyright');
$do = in_array($do, $dos) ? $do : 'copyright';
$settings = $_W['setting']['copyright'];
if(empty($settings) || !is_array($settings)) {
	$settings = array();
} else {
	$settings['slides'] = iunserializer($settings['slides']);
}

if ($do == 'copyright') {
	$_W['page']['title'] = '站点信息设置 - 系统管理';
	if (checksubmit('submit')) {
		$data = array(
			'status' => $_GPC['status'],
			'demo' => $_GPC['demo'],
			'is_dns' => $_GPC['is_dns'],
			'is_check' => $_GPC['is_check'],
			'verifycode' => $_GPC['verifycode'],
			'reason' => $_GPC['reason'],
			'smname' => $_GPC['smname'],
			'sitename' => $_GPC['sitename'],
			'url' => (strexists($_GPC['url'], 'http://') || strexists($_GPC['url'], 'https://')) ? $_GPC['url'] : "http://{$_GPC['url']}",
			'sitehost' => $_GPC['sitehost'],
			'payhost' => $_GPC['payhost'],
			'statcode' => htmlspecialchars_decode($_GPC['statcode']),
			'footerleft' => htmlspecialchars_decode($_GPC['footerleft']),
			'footerright' => htmlspecialchars_decode($_GPC['footerright']),
			'icon' => $_GPC['icon'],
			'ewm' => $_GPC['ewm'],
			'flogo' => $_GPC['flogo'],
			'slides' => iserializer($_GPC['slides']),
			'notice' => $_GPC['notice'],
			'blogo' => $_GPC['blogo'],
			'baidumap' => $_GPC['baidumap'],
			'company' => $_GPC['company'],
			'companyprofile' => htmlspecialchars_decode($_GPC['companyprofile']),
			'address' => $_GPC['address'],
			'person' => $_GPC['person'],
			'phone' => $_GPC['phone'],
			'qq' => $_GPC['qq'],
			'email' => $_GPC['email'],
			'keywords' => $_GPC['keywords'],
			'description' => $_GPC['description'],
			'showhomepage' => intval($_GPC['showhomepage']),
		);
		$test = setting_save($data, 'copyright');
		itoast('更新设置成功！', url('system/site'), 'success');
	}
}

template('system/site');