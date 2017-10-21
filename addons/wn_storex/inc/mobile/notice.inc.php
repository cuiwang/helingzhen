<?php

defined('IN_IA') or exit('Access Denied');

global $_W, $_GPC;
load()->model('mc');
mload()->model('card');

$ops = array('notice_list', 'read_notice');
$op = in_array(trim($_GPC['op']), $ops) ? trim($_GPC['op']) : 'error';
check_params();
$uid = mc_openid2uid($_W['openid']);

if ($op == 'notice_list') {
	$notices = card_notices();
	message(error(0, $notices), '', 'ajax');
}

if ($op == 'read_notice') {
	$id = intval($_GPC['id']);
	$notice = pdo_get('storex_notices', array('uniacid' => intval($_W['uniacid']), 'id' => $id));
	if (!empty($notice)) {
		$read_record = pdo_get('storex_notices_unread', array('uniacid' => intval($_W['uniacid']), 'notice_id' => $id, 'uid' => $uid));
		if (empty($read_record)) {
			$insert_record = array(
				'uniacid' => intval($_W['uniacid']),
				'uid' => $uid,
				'notice_id' => $id,
				'is_new' => 1,
				'type' => $notice['type'],
			);
			pdo_insert('storex_notices_unread', $insert_record);
		}
		message(error(0, $notice), '', 'ajax');
	} else {
		message(error(-1, '通知已过期！'), '', 'ajax');
	}
}