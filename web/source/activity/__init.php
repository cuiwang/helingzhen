<?php
/**
 * [WECHAT 2017]
 
 */
defined('IN_IA') or exit('Access Denied');

load()->model('activity');
load()->classs('coupon');

define('FRAME', 'mc');
$frames = buildframes(array('mc'));
$frames = $frames['mc'];
if($controller == 'activity') {
	if($action == 'coupon') {
		define('ACTIVE_FRAME_URL', url('activity/coupon/display'));
	}
	if($action  == 'exchange') {
		if ($_GPC['type'] == 'coupon' &&  $_GPC['a'] == 'exchange') {
			define('ACTIVE_FRAME_URL', url('activity/exchange/display', array('type' => 'coupon')));
		}
		if ($_GPC['type'] == 'goods' && $_GPC['a'] == 'exchange') {
			define('ACTIVE_FRAME_URL', url('activity/exchange/display', array('type' => 'goods')));
		}
	}
}

$coupon_api = new coupon();
activity_coupon_type_init();
