<?php
/**
 * [WECHAT 2017]
 
 */
defined('IN_IA') or exit('Access Denied');
uni_user_permission_check('mc_group');
$dos = array('display');
$do = in_array($do, $dos) ? $do : 'display';

if($do == 'display') {
	if(checksubmit()) {
		$data = array(
			'user_reg' => intval($_GPC['user_reg']),
			'verify_mobile' => intval($_GPC['verify_mobile']),
			'verify_email' => intval($_GPC['verify_email']),
			'grant_credit_rate' => intval($_GPC['grant_credit_rate']),
			'consume_credit_rate' => intval($_GPC['consume_credit_rate']),
			'consume_credit_max' => intval($_GPC['consume_credit_max']),
		);
		$data = iserializer($data);
		pdo_update('uni_settings', array('creditrule' => $data), array('uniacid' => $_W['uniacid']));
		message('更新积分规则成功', referer(), 'success');
	}
	$setting = uni_setting($_W['uniacid'], '*', true);
	$item = $setting['creditrule'];
}

template('mc/creditrule');