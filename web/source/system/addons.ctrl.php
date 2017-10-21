<?php
/**
 * [WeEngine System] Copyright (c) 2014 WeiZan.Com
 
 */
defined('IN_IA') or exit('Access Denied');
$dos = array('addons');
$do = in_array($do, $dos) ? $do : 'addons';

load()->model('setting');
load()->func('tpl');
$settings = setting_load('addons');
$settings = $settings['addons'];
if(empty($settings) || !is_array($settings)) {
	$settings = array();
}

if ($do == 'addons') {
$_W['page']['title'] = '云服务 - 管理应用商城 - 切换应用商城';
	if(checksubmit('submit')) {
		if($_GPC['addons_site'] == 0){
			$addons_url = 'http://addons.weizancms.com';
			$c_url = 'http://www.012wz.com';
		}elseif($_GPC['addons_site'] == 1){
			$addons_url = 'http://wdl.weizancms.com';
			$c_url = 'http://www.wdlcms.com';
		}elseif($_GPC['addons_site'] == 2){
			$addons_url = 'http://addons.we7cms.cn';
			$c_url = 'http://www.012wz.com';
		}else{
			$addons_url = $_GPC['addons_url'];
		}
		$data = array(
				'addons_site' => $_GPC['addons_site'],
				'addons_url' => $addons_url,
				'c_url' => $c_url,
		);
		setting_save($data, 'addons');
		message('更新设置成功！', 'refresh');
	}
}
template('system/addons');

