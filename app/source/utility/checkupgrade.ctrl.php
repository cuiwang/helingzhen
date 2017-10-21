<?php 
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
defined('IN_IA') or exit('Access Denied');

load()->model('cloud');
load()->func('communication');

$do = !empty($_GPC['do']) && in_array($do, array('module', 'system')) ? $_GPC['do'] : exit('Access Denied');

$result = cloud_prepare();

if (is_error($result)) {
	message($result['message'], '', 'ajax');
}

if ($do == 'module') {
	$info = cloud_m_info(trim($_GPC['m']));
	if (is_error($info) && $info['errno'] == -10) {
		message($info, '', 'ajax');
	}
}