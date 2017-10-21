<?php 
/**
 * [WECHAT 2017]
 
 */
defined('IN_IA') or exit('Access Denied');
$dos = array('mbcopyright');
$do = in_array($do, $dos) ? $do : 'mbcopyright';
$settings = $_W['setting']['mbcopyright'];
if(empty($settings) || !is_array($settings)) {
	$settings = array();
} else {
	$settings['slides'] = iunserializer($settings['slides']);
}

if ($do == 'mbcopyright') {
	$_W['page']['title'] = '手机站点信息设置 - 系统管理';
	if (checksubmit('submit')) {
		$data = array(
			'statcode' => htmlspecialchars_decode($_GPC['statcode']),
			'footerleft' => htmlspecialchars_decode($_GPC['footerleft']),
			'footerright' => htmlspecialchars_decode($_GPC['footerright']),
			'flogo' => $_GPC['flogo'],
			'slides' => iserializer($_GPC['slides']),
			'notice' => $_GPC['notice'],
			'blogo' => $_GPC['blogo'],
		);
		setting_save($data, 'mbcopyright');
		message('更新设置成功！', url('system/mbsite'));
	}
}

template('system/mbsite');