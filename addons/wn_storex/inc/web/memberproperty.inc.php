<?php

defined('IN_IA') or exit('Access Denied');

global $_W, $_GPC;
load()->model('mc');
mload()->model('activity');

$ops = array('display');
$op = in_array(trim($_GPC['op']), $ops) ? trim($_GPC['op']) : 'display';

if ($op == 'display') {
	for ($i = 1; $i <= 24; $i++) {
		$nums[$i] = $i;
	}
	$nums = json_encode($nums);
	$current_property_info = pdo_get('storex_mc_member_property', array('uniacid' => $_W['uniacid']));
	$property = $current_property_info['property'];
	if (empty($property)) {
		$property = activity_storex_member_propertys();
		$property = json_encode($property);
	}
	if ($_W['isajax'] && $_W['ispost']) {
		$member_property = $_GPC['param'];
		$insert_data = array(
			'property' => json_encode($member_property)
		);
		if (!empty($current_property_info)) {
			$status = pdo_update('storex_mc_member_property', $insert_data, array('id' => $current_property_info['id']));
		} else {
			$insert_data['uniacid'] = $_W['uniacid'];
			$status = pdo_insert('storex_mc_member_property', $insert_data);
		}
		if (is_error($status)) {
			message(error(-1, $status), '', 'ajax');
		}
		message(error(0, ''), '', 'ajax');
	}
	include $this->template('memberproperty');
}