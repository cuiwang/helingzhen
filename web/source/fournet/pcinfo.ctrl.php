<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 
 */
defined('IN_IA') or exit('Access Denied');

$dos = array('display');
$do = in_array($do, $dos) ? $do : 'display';

if ($do == 'display') {
	$data = uni_setting($_W['uniacid'], array('site_info', 'styleid'));
	$styleid = $data['styleid'];
	$site_info = empty($data['site_info']) ? array('site_info' => array()) : $data['site_info'];
	$styles = pdo_fetchall('SELECT * FROM ' . tablename('fournet_pcstyles') . ' WHERE uniacid = :uniacid', array(':uniacid' => $_W['uniacid']));
	if (checksubmit('submit')) {
		$pdata['site_info'] = iserializer(array(
			'sitename' => $_GPC['sitename'],
			'keywords' => $_GPC['keywords'],
			'description' => $_GPC['description'],
			'footer' => htmlspecialchars_decode($_GPC['footer'])
		));
		$pdata['styleid'] = intval($_GPC['styleid']);
		pdo_update('uni_settings', $pdata, array('uniacid' => $_W['uniacid']));
		cache_delete("unisetting:{$_W['uniacid']}");
		message('信息设置成功！', url('fournet/pcinfo/display'), 'success');
	}
}
template('fournet/pcinfo');

