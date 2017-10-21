<?php

defined('IN_IA') or exit('Access Denied');

global $_W, $_GPC;
$ops = array('recharge_add', 'recharge_pay', 'card_recharge');
$op = in_array(trim($_GPC['op']), $ops) ? trim($_GPC['op']) : 'error';

mload()->model('card');
check_params();
$uid = mc_openid2uid($_W['openid']);

if ($op == 'card_recharge') {
	$card_member_info = pdo_get('storex_mc_card_members', array('uniacid' => $_W['uniacid'], 'openid' => $_W['openid']), array('id'));
	$card_setting = card_setting_info();
	$card_recharge = $card_setting['params']['cardRecharge'] ? $card_setting['params']['cardRecharge'] : array();
	$recharge_lists = array();
	if ($card_recharge['params']['recharge_type'] == 1 && !empty($card_member_info)) {
		$recharge_lists = $card_recharge['params']['recharges'];
	}
	message(error(0, $recharge_lists), '', 'ajax');
}

if ($op == 'recharge_add') {
	$type = trim($_GPC['type']) ? trim($_GPC['type']) : 'credit';
	if ($type == 'credit') {
		$fee = floatval($_GPC['fee']);
		if (empty($fee) || $fee <= 0) {
			message(error(-1, '请输入正确金额'), '', 'ajax');
		}
		$back= floatval($_GPC['back']);
		$backtype = trim($_GPC['backtype']);
		if ($backtype == 0 || $backtype == 1) {
			$card_setting = card_setting_info();
			$card_recharge = $card_setting['params']['cardRecharge'];
			$recharge_lists = array();
			if ($card_recharge['params']['recharge_type'] == 1) {
				$recharge_lists = $card_recharge['params']['recharges'];
			}
			foreach ($recharge_lists as $key => $value) {
				if ($value['backtype'] == $backtype) {
					if ($value['condition'] == $fee) {
						$back = $value['back'];
						break;
					}
				}
			}
		}
		$recharge_record = array(
			'uid' => $uid,
			'openid' => $_W['openid'],
			'uniacid' => $_W['uniacid'],
			'tid' => date('YmdHi') . random(8, 1),
			'fee' => $fee,
			'type' => 'credit',
			'tag' => $back,
			'backtype' => $backtype,
			'status' => 0,
			'createtime' => TIMESTAMP,
		);
		if (!pdo_insert('mc_credits_recharge', $recharge_record)) {
			message(error(-1, '创建充值订单失败'), '', 'ajax');
		}
		$recharge_id = pdo_insertid();
		message(error(0, $recharge_id), '', 'ajax');
	}
}
if ($op == 'recharge_pay') {
	$charge_record = pdo_get('mc_credits_recharge', array('id' => intval($_GPC['id'])));
	$params = array(
		'tid' => $charge_record['tid'],
		'title' => '万能小店余额充值',
		'fee' => $charge_record['fee'],
		'user' => $uid
	);
	$pay_info = $this->pay($params, $mine);
	message(error(0, $pay_info), '', 'ajax');
}
