<?php

defined('IN_IA') or exit('Access Denied');

global $_W, $_GPC;
load()->model('mc');
mload()->model('card');

$ops = array('display', 'query', 'checkpay', 'check');
$op = in_array(trim($_GPC['op']), $ops) ? trim($_GPC['op']) : 'display';

$_W['page']['title'] = '刷卡支付-微信收款';
if ($op == 'display') {
	if ($_W['isajax']) {
		$post = $_GPC['__input'];
		$fee = trim($post['fee']) ? trim($post['fee']) : message(error(-1, '订单金额不能为空'), '', 'ajax');
		$body = trim($post['body']) ? trim($post['body']) : message(error(-1, '商品名称不能为空'), '', 'ajax');
		$code = trim($post['code']);
		$uid = intval($post['member']['uid']);
		
		if ($post['cash'] > 0 && empty($post['code'])) {
			message(error(-1, '授权码不能为空'), '', 'ajax');
		}
		$total = $money = floatval($post['fee']);
		if (!$total) {
			message(error(-1, '消费金额不能为空'), '', 'ajax');
		}
		$log = "系统日志:会员消费【{$total}】元";
		if ($uid > 0) {
			$user = pdo_get('mc_members', array('uniacid' => $_W['uniacid'], 'uid' => $uid));
			if (empty($user)) {
				message(error(-1, '用户不存在'), '', 'ajax');
			}
			$user['groupname'] = $_W['account']['groups'][$user['groupid']]['title'];
			$card = card_setting_info();
			$member = pdo_get('storex_mc_card_members', array('uniacid' => $_W['uniacid'], 'uid' => $user['uid']));
			if (!empty($card) && $card['status'] == 1 && !empty($member)) {
				$user['discount'] = $card['discount'][$user['groupid']];
				if (!empty($user['discount']) && !empty($user['discount']['discount'])) {
					if ($total >= $user['discount']['condition']) {
						$log .= ",所在会员组【{$user['groupname']}】,可享受满【{$user['discount']['condition']}】元";
						if ($card['discount_type'] == 1) {
							$log .= "减【{$user['discount']['discount']}】元";
							$money = $total - $user['discount']['discount'];
						} else {
							$discount = $user['discount']['discount'] * 10;
							$log .= "打【{$discount}】折";
							$money = $total * $user['discount']['discount'];
						}
						if ($money < 0) {
							$money = 0;
						}
						$log .= ",实收金额【{$money}】元";
					}
				}
				$post_money = strval($post['fact_fee']);
				if ($post_money != $money) {
					message(error(-1, '实收金额错误'), '', 'ajax');
				}

				$post_credit1 = intval($post['credit1']);
				if ($post_credit1 > 0) {
					if ($post_credit1 > $user['credit1']) {
						message(error(-1, '超过会员账户可用积分'), '', 'ajax');
					}
				}

				$post_offset_money = trim($post['offset_money']);
				$offset_money = 0;
				if ($post_credit1 && $card['offset_rate'] > 0 && $card['offset_max'] >= 0) {
					if ($card['offset_max'] == '0') {
						$offset_money = $post_credit1 / $card['offset_rate'];
					} else {
						$offset_money = min($card['offset_max'], $post_credit1 / $card['offset_rate']);
					}
					if ($offset_money != $post_offset_money) {
						message(error(-1, '积分抵消金额错误'), '', 'ajax');
					}
					$credit1 = $post_credit1;
					$log .= ",使用【{$post_credit1}】积分抵消【{$offset_money}】元";
				}
			}
			$credit2 = floatval($post['credit2']);
			if ($credit2 > 0) {
				if ($credit2 > $user['credit2']) {
					message(error(-1, '超过会员账户可用余额'), '', 'ajax');
				}
				$log .= ",使用余额支付【{$credit2}】元";
			}
		} else {
			$post['cash'] = $post['fee'];
		}
		$cash = floatval($post['cash']);
		$sum = strval($credit2 + $cash + $offset_money);
		$money = strval($money);
		if ($sum != $money) {
			message(error(-1, '支付金额不等于实收金额'), '', 'ajax');
		}
		$realname = $post['member']['realname'] ? $post['member']['realname'] : $post['member']['realname'];
		if ($cash <= 0) {
			//直接扣除积分和余额
			$data = array(
				'uniacid' => $_W['uniacid'],
				'uid' => $member['uid'],
				'status' => 0,
				'type' => 'wechat',
				'trade_type' => 'micropay',
				'fee' => $total,
				'final_fee' => $money,
				'credit1' => $post_credit1,
				'credit1_fee' => $offset_money,
				'credit2' => $credit2,
				'cash' => $cash,
				'body' => $body,
				'openid' => $member['openid'],
				'nickname' => $realname,
				'remark' => $log,
				'clerk_id' => $_W['user']['clerk_id'],
				'store_id' => $_W['user']['store_id'],
				'clerk_type' => $_W['user']['clerk_type'],
				'createtime' => TIMESTAMP,
				'status' => 1,
				'paytime' => TIMESTAMP,
				'credit_status' => 1,
			);
			pdo_insert('storex_paycenter_order', $data);
			if ($post_credit1 > 0) {
				$status = mc_credit_update($member['uid'], 'credit1', -$post_credit1, array(0, "会员刷卡消费,使用积分抵现,扣除{$post_credit1积分}", 'system', $_W['user']['clerk_id'], $_W['user']['store_id'], $_W['user']['clerk_type']));
			}
			if ($credit2 > 0) {
				$status = mc_credit_update($member['uid'], 'credit2', -$credit2, array(0, "会员刷卡消费,使用余额支付,扣除{$credit2}余额", 'system', $_W['user']['clerk_id'], $_W['user']['store_id'], $_W['user']['clerk_type']));
				mc_notice_credit2($member['openid'], $member['uid'], $credit2, '', '收银台消费');
			}
			message(error(0, '支付成功'), $this->createWeburl('paycenterwxmicro'), 'ajax');
		} else {
			$log .= ",使用刷卡支付【{$cash}】元";
			if (!empty($_GPC['remark'])) {
				$note = "店员备注：{$_GPC['remark']}";
			}
			$log = $note.$log;

			$isexist = pdo_get('storex_paycenter_order', array('uniacid' => $_W['uniacid'], 'auth_code' => $code));
			if ($isexist) {
				message(error(-1, '每个二维码仅限使用一次，请刷新再试'), '', 'ajax');
			}
			$data = array(
				'uniacid' => $_W['uniacid'],
				'uid' => $member['uid'],
				'status' => 0,
				'type' => 'wechat',
				'trade_type' => 'micropay',
				'fee' => $total,
				'final_fee' => $money,
				'credit1' => $post_credit1,
				'credit1_fee' => $offset_money,
				'credit2' => $credit2,
				'cash' => $cash,
				'remark' => $log,
				'body' => $body,
				'openid' => $member['openid'],
				'nickname' => $realname,
				'auth_code' => $code,
				'clerk_id' => $_W['user']['clerk_id'],
				'store_id' => $_W['user']['store_id'],
				'clerk_type' => $_W['user']['clerk_type'],
				'createtime' => TIMESTAMP,
			);
			pdo_insert('storex_paycenter_order', $data);
			$id = pdo_insertid();
			load()->classs('pay');
			$pay = Pay::create();
			$params = array(
				'tid' => $id,
				'module' => 'paycenter',
				'type' => 'wechat',
				'fee' => $cash,
				'body' => $body,
				'auth_code' => $code,
			);
			$pid = $pay->buildPayLog($params);
			if (is_error($pid)) {
				message($pid, '', 'ajax');
			}
			$log = pdo_get('core_paylog', array('plid' => $pid));
			pdo_update('storex_paycenter_order', array('pid' => $pid, 'uniontid' => $log['uniontid']), array('id' => $id));
			$data = array(
				'out_trade_no' => $log['uniontid'],
				'body' => $body,
				'total_fee' => $log['fee'] * 100,
				'auth_code' => $code,
				'uniontid' => $log['uniontid']
			);
			
			$result = $pay->buildMicroOrder($data);
			if ($result['result_code'] == 'SUCCESS') {
				if (is_error($result)) {
					message($result, '', 'ajax');
				} else {
					$status = $pay->NoticeMicroSuccessOrder($result);
					if (is_error($status)) {
						message($status, '', 'ajax');
					}
					message(error(0, '支付成功'), $this->createWeburl('paycenterwxmicro'), 'ajax');
				}
			} else {
				message($result, '', 'ajax');
			}
		}
		exit();
	}
	$paycenter_records = pdo_fetchall("SELECT * FROM " . tablename('storex_paycenter_order') . " WHERE uniacid = :uniacid AND clerk_id = :clerk_id ORDER BY id DESC LIMIT 0, 10", array(':uniacid' => $_W['uniacid'], ':clerk_id' => $_W['user']['clerk_id']));
	$today_credit_total = pdo_fetchall("SELECT credit2 FROM " . tablename('storex_paycenter_order') . " WHERE uniacid = :uniacid AND clerk_id = :clerk_id AND paytime > :starttime AND paytime < :endtime AND credit2 <> ''", array(':uniacid' => $_W['uniacid'], ':clerk_id' => trim($_W['user']['clerk_id']), ':starttime' => strtotime(date('Ymd')), ':endtime' => time()));
	$today_wechat_total = pdo_fetchall("SELECT cash FROM " . tablename('storex_paycenter_order') . " WHERE uniacid = :uniacid AND clerk_id = :clerk_id AND paytime > :starttime AND paytime < :endtime AND cash <> ''", array(':uniacid' => $_W['uniacid'], ':clerk_id' => trim($_W['user']['clerk_id']), ':starttime' => strtotime(date('Ymd')), ':endtime' => time()));
	foreach ($today_wechat_total as $val) {
		$wechat_total += $val['cash'];
	}
	foreach ($today_credit_total as $val) {
		$credit_total += $val['credit2'];
	}
	$wechat_total = $wechat_total ? $wechat_total : '0';
	$credit_total = $credit_total ? $credit_total : '0';
	$card_set = card_setting_info();
	// $card_params = json_decode($card_set['params'], true);
	$grant_rate = $card_set['grant_rate'];
	unset($card_set['params'], $card_set['nums'], $card_set['times'], $card_set['business'], $card_set['html'], $card_set['description'], $card_set['card_id']);
	$card_set_str = json_encode($card_set);
}

if ($op == 'query') {
	if ($_W['isajax']) {
		$post = $_GPC['__input'];
		$uniontid = trim($post['uniontid']);
		load()->classs('pay');
		$pay = Pay::create();
		$result = $pay->queryOrder($uniontid, 2);
		if (is_error($result)) {
			message($result, '', 'ajax');
		}
		if ($result['trade_state'] == 'SUCCESS') {
			$status = $pay->NoticeMicroSuccessOrder($result);
			if (is_error($status)) {
				message($status, '', 'ajax');
			}
			message(error(0, '支付成功'), '', 'ajax');
		}
		message(error(-1, '支付失败,当前订单状态:' . $result['trade_state']), '', 'ajax');
	}
}

if ($op == 'checkpay') {
	if ($_W['isajax']) {
		$post = $_GPC['__input'];
		$uniontid = trim($post['uniontid']);
		load()->classs('pay');
		$pay = Pay::create();
		$result = $pay->queryOrder($uniontid, 2);
		if (is_error($result)) {
			message($result, '', 'ajax');
		}
		if ($result['trade_state'] == 'SUCCESS') {
			$status = $pay->NoticeMicroSuccessOrder($result);
			if (is_error($status)) {
				message($status, '', 'ajax');
			}
			message($result, '', 'ajax');
		}
		message($result, '', 'ajax');
	}
}

if ($op == 'check') {
	$set = card_setting_info();
	if (is_error($set)) {
		message($set, '', 'ajax');
	}
	$_GPC = $_GPC['__input'];
	$cardsn = trim($_GPC['cardsn']);
	$card_member = pdo_getall('storex_mc_card_members', array('uniacid' => $_W['uniacid'], 'cardsn' => $cardsn));
	if (empty($card_member)) {
		message(error(-1, '卡号不存在或已经删除'), '', 'ajax');
	}
	if (count($card_member) > 1) {
		message(error(-1, '卡号对应用户不唯一'), '', 'ajax');
	}
	$card_member = $card_member[0];
	if ($card_member['status'] != 1) {
		message(error(-1, '该会员卡已被禁用'), '', 'ajax');
	}
	$member = pdo_get('mc_members', array('uniacid' => $_W['uniacid'], 'uid' => $card_member['uid']));
	if (empty($member)) {
		message(error(-1, '会员卡对应的会员不存在'), '', 'ajax');
	}
	$member['openid'] = $card_member['openid'];
	$member['createtime'] = $card_member['createtime'];
	$member['cardsn'] = $card_member['cardsn'];
	$member['groupname'] = $_W['account']['groups'][$member['groupid']]['title'];
	$member['discount_type'] = 0;
	$member['discount'] = array();
	$member['discount_cn'] = '暂无';
	$member['credit1'] = floatval($member['credit1']);
	$member['credit2'] = floatval($member['credit2']);
	$member['offset_rate'] = $set['offset_rate'];
	$member['offset_max'] = $set['offset_max'];
	if ($set['discount_type'] > 0 && !empty($set['discount'])) {
		$discount = $set['discount'][$member['groupid']];
		if (!empty($discount)) {
			$member['discount'] = $discount;
			$member['discount_type'] = $set['discount_type'];
			if ($set['discount_type'] == 1 ) {
				$member['discount_cn'] = "满 {$discount['condition']}元减 {$discount['discount']}元";
			} else {
				$zhe = $discount['discount'] * 10;
				$member['discount_cn'] = "满 {$discount['condition']}元打 {$zhe}折";
			}
		}
	}
	message(error(0, $member), '', 'ajax');
}

include $this->template('paycenterwxmicro');