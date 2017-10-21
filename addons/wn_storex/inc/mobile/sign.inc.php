<?php

defined('IN_IA') or exit('Access Denied');

global $_W, $_GPC;

$ops = array('sign_info', 'sign', 'remedy_sign', 'sign_record');
$op = in_array(trim($_GPC['op']), $ops) ? trim($_GPC['op']) : 'error';

check_params();
load()->model('mc');
mload()->model('card');
$uid = mc_openid2uid($_W['openid']);

$extend_switch = extend_switch_fetch();

if (empty($extend_switch) || $extend_switch['sign'] != 1) {
	message(error(-1, '没有开启签到！'), '', 'ajax');
}
$sign_max_day = pdo_getall('storex_sign_record', array('uid' => $uid, 'year' => date('Y'), 'month' => date('n'), 'remedy !=' => 2), array(), '', 'day DESC', '1');

if ($op == 'sign_info') {
	$sign_data = card_sign_info($sign_max_day[0]['day']);
	message(error(0, $sign_data), '', 'ajax');
}

if ($op == 'sign') {
	$sign_data = card_sign_info($sign_max_day[0]['day']);
	$sign_day = intval($_GPC['day']);
	if ($sign_day != date('j')) {
		message(error(-1, '参数错误！'), '', 'ajax');
	}
	if (!empty($sign_data['signs'][$sign_day])) {
		card_sign_operation($sign_data, $sign_day);
	} else {
		message(error(-1, '参数错误！'), '', 'ajax');
	}
}

if ($op == 'remedy_sign') {
	$remedy_day = intval($_GPC['day']);
	$sign_data = card_sign_info($sign_max_day[0]['day']);
	if ($sign_data['sign']['remedy'] == 1) {
		$cost = array(
			'remedy' => $sign_data['sign']['remedy'],
			'remedy_cost' => $sign_data['sign']['remedy_cost'],
			'remedy_cost_type' => $sign_data['sign']['remedy_cost_type'],
		);
	} else {
		message(error(-1, '未开启补签功能！'), '', 'ajax');
	}
	if ($remedy_day >= date('j')) {
		message(error(-1, '参数错误！'), '', 'ajax');
	}
	if (!empty($sign_data['signs'][$remedy_day])) {
		card_sign_operation($sign_data, $remedy_day, $cost, 'remedy');
	}
}

if ($op == 'sign_record') {
	$condition = array(
		'uid' => $uid,
		'year' => date('Y'),
	);
	if (!empty($_GPC['month'])) {
		if (intval($_GPC['month']) > 12 || intval($_GPC['month']) <= 0) {
			message(error(-1, '参数错误！'), '', 'ajax');
		} else {
			$condition['month'] = intval($_GPC['month']);
		}
	}
	$sign_record = pdo_getall('storex_sign_record', $condition, array(), '', 'day ASC');
	message(error(0, $sign_record), '', 'ajax');
}