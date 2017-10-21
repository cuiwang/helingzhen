<?php 
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
$_W['page']['title'] = '云服务诊断 - 云服务';
$dos = array('display', 'testapi');
$do = in_array($do, $dos) ? $do : 'display';

if ($do == 'testapi') {
	load()->model('cloud');
	$starttime = microtime(true);
	$response = cloud_request(ADDONS_URL);
	$endtime = microtime(true);
	message('请求接口成功，耗时 '.(round($endtime - $starttime, 5)).' 秒', '', 'ajax');
} else {
	if(checksubmit()) {
		load()->model('setting');
		setting_save('', 'site');
		message('成功清除站点记录.', 'refresh');
	}
	if (checksubmit('updateserverip')) {
		load()->model('setting');
		if (!empty($_GPC['ip'])) {
			setting_save($_GPC['ip'], 'cloudip');
		} else {
			setting_save('', 'cloudip');
		}
		message('修改云服务ip成功.', 'refresh');
	}
	if(empty($_W['setting']['site'])) {
		$_W['setting']['site'] = array();
	}
	template('cloud/diagnose');
}
