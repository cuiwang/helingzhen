<?php

defined('IN_IA') or exit('Access Denied');

global $_W, $_GPC;
$ops = array('display', 'exchange', 'mine', 'detail', 'publish', 'opencard', 'addcard');
$op = in_array(trim($_GPC['op']), $ops) ? trim($_GPC['op']) : 'error';

check_params();
load()->model('mc');
mload()->model('activity');
mload()->model('card');
mload()->classs('coupon');
$uid = mc_openid2uid($_W['openid']);
activity_get_coupon_type();

if ($op == 'display') {
	$ids = array();
	$storex_exchange = pdo_getall('storex_activity_exchange', array('uniacid' => intval($_W['uniacid']), 'status' => 1), array(), 'extra');
	if (!empty($storex_exchange)) {
		$ids = array_keys($storex_exchange);
	} else {
		message(error(0, array()), '', 'ajax');
	}
	$storex_coupon = pdo_getall('storex_coupon', array('uniacid' => intval($_W['uniacid']), 'id' => $ids, 'source' => COUPON_TYPE, 'status' => 3), array('id', 'type', 'logo_url', 'title', 'description', 'get_limit', 'date_info', 'sub_title', 'extra', 'quantity'), 'id');
	if (!empty($storex_coupon)) {
		foreach ($storex_coupon as &$value) {
			$value['extra'] = iunserializer($value['extra']);
			if ($value['type'] == COUPON_TYPE_DISCOUNT) {
				$value['discounts'] = $value['extra']['discount'] / 10;
			} elseif ($value['type'] == COUPON_TYPE_CASH) {
				$value['least_cost'] = $value['extra']['least_cost'] / 100;
				$value['reduce_cost'] = $value['extra']['reduce_cost'] / 100;
			}
			$value['date_info'] = iunserializer($value['date_info']);
			if ($value['date_info']['time_type'] == '1') {
				$value['extra_date_info'] = '有效期:' . $value['date_info']['time_limit_start'] . '-' . $value['date_info']['time_limit_end'];
			} else {
				$value['extra_date_info'] = '有效期:领取后' . ($coupon['date_info']['deadline'] == 0 ? '当' : $coupon['date_info']['deadline']) . '天可用，有效期' . $value['date_info']['limit'] . '天';
			}
		}
	}
	//总领取数量
	$coupon_total = pdo_fetchall("SELECT COUNT(*) AS total, couponid FROM " . tablename('storex_coupon_record') . " WHERE uniacid = :uniacid AND couponid IN (" . implode(',', $ids) . ") GROUP BY couponid", array(':uniacid' => intval($_W['uniacid'])), 'couponid');
	//我的领取的数量
	$mine_coupon_num = pdo_fetchall("SELECT COUNT(*) AS sum, couponid FROM " . tablename('storex_coupon_record') . " WHERE uid = :uid GROUP BY couponid", array(':uid' => $uid), 'couponid');
	if (!empty($storex_exchange)) {
		foreach ($storex_exchange as $id => $info) {
			if ($storex_coupon[$id]['quantity'] <= 0) {
				unset($storex_exchange[$id]);
				continue;
			}
			if (!empty($mine_coupon_num[$id]) && $mine_coupon_num[$id]['sum'] >= $info['pretotal']) {
				unset($storex_exchange[$id]);
				continue;
			}
			if ($info['starttime'] > TIMESTAMP || $info['endtime'] < TIMESTAMP) {
				unset($storex_exchange[$id]);
				continue;
			}
			$storex_exchange[$id]['received_total'] = !empty($coupon_total[$id]['total']) ? $coupon_total[$id]['total'] : 0;
			$storex_exchange[$id]['received_num'] = !empty($mine_coupon_num[$id]['sum']) ? $mine_coupon_num[$id]['sum'] : 0;
			$storex_exchange[$id]['coupon'] = $storex_coupon[$id];
		}
	}
	message(error(0, $storex_exchange), '', 'ajax');
}

if ($op == 'exchange') {
	$id = intval($_GPC['id']);
	$storex_exchange = pdo_get('storex_activity_exchange', array('uniacid' => $_W['uniacid'], 'extra' => $id));
	if (empty($storex_exchange)) {
		message(error(-1, '兑换券不存在'), '', 'ajax');
	}
	if ($storex_exchange['status'] != 1) {
		message(error(-1, '未开启兑换'), '', 'ajax');
	}
	$creditnames = array('credit1' => '积分', 'credit2' => '余额');
	$credit = mc_credit_fetch($uid, array($storex_exchange['credittype']));
	if (intval($credit[$storex_exchange['credittype']]) < $storex_exchange['credit']) {
		message(error(-1, $creditnames[$storex_exchange['credittype']] . '不足'), '', 'ajax');
	}
	$received_num = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('storex_coupon_record') . " WHERE `uniacid` = :uniacid AND `uid` = :uid AND `couponid` = :id", array(':id' => $id, ':uid' => $uid, ':uniacid' => intval($_W['uniacid'])));
	if ($received_num >= $storex_exchange['pretotal']) {
		message(error(-1, '兑换次数不足'), '', 'ajax');
	}
	$coupon_info = activity_get_coupon_info($id);
	if ($storex_exchange['starttime'] > TIMESTAMP) {
		message(error(-1, '活动未开始'), '', 'ajax');
	}
	if ($storex_exchange['endtime'] < TIMESTAMP) {
		message(error(-1, '活动已结束'), '', 'ajax');
	}
	$status = activity_user_get_coupon($id, $_W['member']['uid']);
	if (is_error($status)) {
		message(error(-1, $status['message']), '', 'ajax');
	} else {
		mc_credit_update($_W['member']['uid'], $storex_exchange['credittype'], -1 * $storex_exchange['credit']);
		if ($storex_exchange['credittype'] == 'credit1') {
			mc_notice_credit1($_W['openid'], $_W['member']['uid'], -1 * $storex_exchange['credit'], '兑换卡券消耗积分');
		} elseif ($storex_exchange['credittype'] == 'credit2') {
			$card_info = card_setting_info();
			$recharges_set = $card_info['params']['cardRecharge'];
			if (empty($recharges_set['params']['recharge_type'])) {
				$grant_rate = $card_info['grant_rate'];
				mc_credit_update($_W['member']['uid'], 'credit1', $grant_rate * $storex_exchange['credit']);
			}
			mc_notice_credit2($_W['openid'], $_W['member']['uid'], -1 * $storex_exchange['credit'], $grant_rate * $storex_exchange['credit'], '兑换卡券消耗余额');
		}
		message(error(0, '兑换卡券成功!'), '', 'ajax');
	}
}

if ($op == 'mine') {
	$couponlist = activity_get_user_couponlist();
	$coupon_owned['lists'] = $couponlist;
	$coupon_owned['source'] = COUPON_TYPE;
	message(error(0, $coupon_owned), '', 'ajax');
}

if ($op == 'opencard') {
	$coupon_api = new WnCoupon();
	$id = intval($_GPC['id']);
	$code = trim($_GPC['code']);
	if ($_W['isajax'] && $_W['ispost']) {
		$card = $coupon_api->BuildCardExt($id);
		if (is_error($card)) {
			message(error(1, $card['message']), '', 'ajax');
		} else {
			$opencard['card_id'] = $card['card_id'];
			$opencard['code'] = $code;
			message(error(0, $opencard), '', 'ajax');
		}
	}
}
if ($op == 'addcard') {
	$id = intval($_GPC['id']);
	$coupon_api = new WnCoupon();
	if ($_W['isajax'] && $_W['ispost']) {
		$card = $coupon_api->BuildCardExt($id);
		if (is_error($card)) {
			message(error(1, $card['message']), '', 'ajax');
		} else {
			message(error(0, $card), '', 'ajax');
		}
	}
}

if ($op == 'detail') {
	$couponid = intval($_GPC['couponid']);
	$coupon_record = pdo_get('storex_coupon_record', array('id' => intval($_GPC['recid'])));
	$coupon_info = activity_get_coupon_info($couponid);
	$coupon_info['description'] = $coupon_info['description'] ? $coupon_info['description'] : '暂无说明';
	$coupon_info['code'] = $coupon_record['code'];
	if (!empty($coupon_info['location_id_list'])) {
		foreach ($coupon_info['location_id_list'] as $key => $value) {
			$store_names[] = $value['title'];
		}
		$coupon_info['store_info'] = '店铺' . implode(',', $store_names) . '可使用';
	} else {
		$coupon_info['store_info'] = '所有店铺均可使用';
	}
	$coupon_info['limit_info'] = '每人限领' . $coupon_info['get_limit'] . '张';
	if ($coupon_info['type'] == '1') {
		$coupon_info['discount_info'] = '凭此券消费打' . $coupon_info['extra']['discount'] * 0.1 . '折';
	} else {
		$coupon_info['discount_info'] = '价值' . $coupon_info['extra']['reduce_cost'] * 0.01 . '元代金券一张,消费满' . $coupon_info['extra']['least_cost'] * 0.01 . '元可使用';
	}
	if ($coupon_info['date_info']['time_type'] == '1') {
		$coupon_info['detail_date_info'] = $coupon_info['date_info']['time_limit_start'] . '-' . $coupon_info['date_info']['time_limit_end'];
	} else {
		$starttime = $coupon_record['addtime'] + $coupon_info['date_info']['deadline'] * 86400;
		$endtime = $starttime + ($coupon_info['date_info']['limit'] - 1) * 86400;
		$coupon_info['detail_date_info'] = date('Y.m.d', $starttime) . '-' . date('Y.m.d', $endtime);
	}
	$coupon_colors = activity_get_coupon_colors();
	$coupon_info['color_value'] = !empty($coupon_colors[$coupon_info['color']]) ? $coupon_colors[$coupon_info['color']] : '#a9d92d';
	message(error(0, $coupon_info), '', 'ajax');
}

if ($op == 'publish') {
	$id = intval($_GPC['id']);
	$status = activity_user_get_coupon($id, $_W['openid'], $granttype = 2);
	$url = $this->createMobileurl('display');
	if (is_error($status)) {
		$url .= '#/wechat_redirect';
		message($status['message'], $url, 'error');
	} else {
		$record = pdo_get('storex_coupon_record', array('uniacid' => $_W['uniacid'], 'id' => $status), array('id', 'couponid'));
		$store = pdo_getall('storex_bases', array('weid' => $_W['uniacid']), array('id'), '', 'displayorder DESC', array(1,1));
		$url .= '&id=' . $store[0]['id'] . '#/Home/Coupon/' . $record['id'] . '/' . $record['couponid'];
		header("Location: $url");
	}
}