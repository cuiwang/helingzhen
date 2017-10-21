<?php 
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
defined('IN_IA') or exit('Access Denied');
set_time_limit(0);
load()->model('cloud');
load()->func('communication');
load()->model('extension');
$r = cloud_prepare();
if(is_error($r)) {
	itoast($r['message'], url('cloud/profile'), 'error');
}

$do = !empty($_GPC['do']) && in_array($do, array('module', 'system')) ? $_GPC['do'] : exit('Access Denied');
if ($do == 'system') {
	$lock = cache_load('checkupgrade:system');
	if (empty($lock) || (TIMESTAMP - 3600 > $lock['lastupdate'])) {
		$upgrade = cloud_build();
		if(!is_error($upgrade) && !empty($upgrade['upgrade'])) {
			$upgrade = array('version' => $upgrade['version'], 'release' => $upgrade['release'], 'upgrade' => 1, 'lastupdate' => TIMESTAMP);
			cache_write('checkupgrade:system', $upgrade);
			cache_delete('cloud:transtoken');
			iajax(0, $upgrade);
		} else {
			$upgrade = array('lastupdate' => TIMESTAMP);
			cache_delete('cloud:transtoken');
			cache_write('checkupgrade:system', $upgrade);
		}
	} else {
		iajax(0, $lock);
	}
}