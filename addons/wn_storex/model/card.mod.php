<?php 
//签到操作
function card_sign_operation($sign_data, $sign_day, $cost = array(), $type = '') {
	global $_W, $_GPC;
	$sign_info = $sign_data['signs'][$sign_day];
	$uid = mc_openid2uid($_W['openid']);
	if ($sign_info['status'] == 1) {
		message(error(-1, '已经签过了，明天再来吧！'), '', 'ajax');
	} else {
		$insert_record = $insert_extra = array(
				'uniacid' => intval($_W['uniacid']),
				'uid' => $uid,
				'credit' => $sign_data['sign']['everydaynum'],
				'addtime' => TIMESTAMP,
				'year' => date('Y'),
				'month' => date('n'),
				'day' => $sign_day,
		);
		if (!empty($sign_data['group'])) {
			foreach ($sign_data['group'] as $k => $val) {
				if (($sign_data['sign_days'] + 1) == $sign_data['sign'][$k] || (($sign_data['sign_days'] + 1) == date('t') && $k == 'full_sign_num')) {
					$insert_extra['remedy'] = 2;
					$tipx = '满签' . $sign_data['sign'][$k] . '天送' . $val . '积分';
					mc_credit_update($uid, 'credit1', $val, array('0', $tipx, 'wn_storex', 0, 0, 1));
					pdo_insert('storex_sign_record', $insert_extra);
					continue;
				}
			}
		}
		if ($type == 'remedy') {
			$insert_record['remedy'] = 1;
			if (!empty($cost)) {
				$tips = '消费' . $cost['remedy_cost'] . '余额，补签第' . $sign_day . '天';
				$return = mc_credit_update($uid, $cost['remedy_cost_type'], -$cost['remedy_cost'], array('0', $tips, 'wn_storex', 0, 0, 1));
				if (is_array($return)) {
					message(error(-1, "积分不足，补签失败！"), '', 'ajax');
				}
			}
			$tip1 = '补签获得积分' . $sign_data['sign']['everydaynum'];
			$tip2 = '补签成功，获得' . $sign_data['sign']['everydaynum'] . '积分';
			$tip3 = '补签失败！';
		} else {
			$tip1 = '签到获得积分' . $sign_data['sign']['everydaynum'];
			$tip2 = '签到成功，获得' . $sign_data['sign']['everydaynum'] . '积分';
			$tip3 = '签到失败！';
		}
		pdo_insert('storex_sign_record', $insert_record);
		$insert_id = pdo_insertid();
		if (!empty($insert_id)) {
			mc_credit_update($uid, 'credit1', $sign_info['credit'], array('0', $tip1, 'wn_storex', 0, 0, 1));
			message(error(0, $tip2), '', 'ajax');
		} else {
			message(error(-1, $tip3), '', 'ajax');
		}
	}
}

//获取签到信息
function card_sign_info($sign_max_day) {
	global $_W, $_GPC;
	$uid = mc_openid2uid($_W['openid']);
	$sign_set = pdo_get('storex_sign_set', array('uniacid' => intval($_W['uniacid'])));
	$sign = iunserializer($sign_set['sign']);
	$sign_data = array();
	$sign_data['sign'] = $sign;
	$sign_data['days'] = date('t');
	$sign_data['month'] = date('n');
	$sign_data['day'] = date('j');
	$sign_data['content'] = $sign_set['content'];
	$sign_data['signs'] = array();
	$sign_record = pdo_getall('storex_sign_record', array('uid' => $uid, 'year' => date('Y'), 'month' => date('n'), 'remedy !=' => 2), array(),'day','day ASC');

	$sign_days = count($sign_record);
	$no_sign = date('t') - $sign_max_day;
	$group = array();
	if (($sign_days + $no_sign) >= $sign['first_group_day']) {
		$group['first_group_day'] = $sign['first_group_num'];
	}
	if (($sign_days + $no_sign) >= $sign['second_group_day']) {
		$group['second_group_day'] = $sign['second_group_num'];
	}
	if (($sign_days + $no_sign) >= $sign['third_group_day']) {
		$group['third_group_day'] = $sign['third_group_num'];
	}
	if (($sign_days + $no_sign) == date('t')) {
		$group['full_sign_num'] = $sign['full_sign_num'];
	}
	$sign_data['group'] = $group;
	$sign_data['sign_days'] = $sign_days;
	for ($i = 1; $i <= $sign_data['days']; $i++) {
		$sign_data['signs'][$i] = array(
			'credit' => $sign['everydaynum'],
		);
		if (!empty($sign_record[$i])) {
			$sign_data['signs'][$i] = array(
				'credit' => $sign_record[$i]['credit'],
				'status' => 1,//已签到
			);
		} else {
			$sign_data['signs'][$i]['status'] = 2;//未签到
			if ($i > $sign_max_day) {
				foreach ($group as $k => $val) {
					if (($i-$sign_max_day+$sign_days) == $sign[$k] || (($i-$sign_max_day+$sign_days) == date('t') && $k == 'full_sign_num')) {
						$sign_data['signs'][$i]['credit'] += $val;
						continue;
					}
				}
			}
		}
	}
	return $sign_data;
}
//通知
function card_notices() {
	global $_W, $_GPC;
	$uid = mc_openid2uid($_W['openid']);
	$notices = pdo_getall('storex_notices', array('uniacid' => intval($_W['uniacid']), 'type' => 1), array(), 'id', 'addtime DESC');
	if (!empty($notices)) {
		$notice_ids = array();
		foreach ($notices as &$info) {
			$info['read_status'] = 0; //未读
			$info['addtime'] = date('Y-m-d', $info['addtime']);
			if (!empty($info['thumb'])) {
				$info['thumb'] = tomedia($info['thumb']);
			}
			$notice_ids[] = $info['id'];
		}
		unset($info);
		$read_record = pdo_getall('storex_notices_unread', array('uid' => $uid, 'uniacid' => intval($_W['uniacid']), 'notice_id IN' => $notice_ids));
		if (!empty($read_record)) {
			foreach ($read_record as $val) {
				if (!empty($notices[$val['notice_id']])) {
					$notices[$val['notice_id']]['read_status'] = 1; //已读
				}
			}
		}
	}
	return $notices;
}

//检查邮箱，手机号是否已经注册过
function card_info_exist($data) {
	global $_W;
	$uid = mc_openid2uid($_W['openid']);
	if (!empty($data['email'])) {
		$email = trim($data['email']);
		$isexist = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('storex_mc_card_members') . ' WHERE uniacid = :uniacid AND email = :email AND uid != :uid', array(':uniacid' => $_W['uniacid'], ':email' => $email, ':uid' => $uid));
		if ($isexist >= 1) {
			message(error(-1, '邮箱已被注册'), '', 'ajax');
		}
	}
	if (!empty($data['mobile'])) {
		$mobile = trim($data['mobile']);
		$isexist = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('storex_mc_card_members') . ' WHERE uniacid = :uniacid AND mobile = :mobile AND uid != :uid', array(':uniacid' => $_W['uniacid'], ':mobile' => $mobile, ':uid' => $uid));
		if ($isexist >= 1) {
			message(error(-1, '手机号已被注册'), '', 'ajax');
		}
	}
}

function card_discount_price($uid, $price) {
	$card_credit = card_return_credit_info();
	if (!empty($card_credit)) {
		$group = card_group_id($uid);
		if (!empty($group) && !empty($card_credit['discounts'][$group['groupid']])) {
			$discounts = $card_credit['discounts'][$group['groupid']];
			if ($card_credit['discount_type'] == 1) {
				if ($price >= $discounts['condition_1']) {
					if ($price > $discounts['discount_1']) {
						$price -= $discounts['discount_1'];
					}
				}
			} elseif ($card_credit['discount_type'] == 2) {
				if ($price >= $discounts['condition_2']) {
					if ($discounts['discount_2'] != 0 && $discounts['discount_2'] > 0) {
						$price *= $discounts['discount_2'] * 0.1;
					}
				}
			}
		}
	}
	$price = sprintf("%1.2f", $price);
	return $price;
}

function card_group_id($uid) {
	global $_W;
	$groups = mc_groups();
	if (!empty($groups)) {
		$members = pdo_get('mc_members', array('uniacid' => intval($_W['uniacid']), 'uid' => $uid));
		if (!empty($members) && !empty($groups[$members['groupid']])) {
			return $groups[$members['groupid']];
		}
	}
}

function card_setting_info() {
	global $_W;
	$cachekey = "wn_storex_mc_card_setting:{$_W['uniacid']}";
	$cache = cache_load($cachekey);
	if (!empty($cache)) {
		return $cache;
	}
	$card_info = pdo_get('storex_mc_card', array('uniacid' => intval($_W['uniacid'])));
	if (empty($card_info)) {
		return array();
	}
	$json_to_array = array(
		'color', 'background', 'fields', 'discount', 'grant', 'nums', 'times',
	);
	foreach ($json_to_array as $val) {
		if (!empty($card_info[$val])) {
			$card_info[$val] = iunserializer($card_info[$val]);
		}
	}
	if (!empty($card_info['params'])) {
		$card_info['params'] = json_decode($card_info['params'], true);
	}
	cache_write($cachekey, $card_info);
	return $card_info;
}

function card_return_credit_info($uid = '') {
	global $_W;
	if (empty($uid)) {
		$uid = mc_openid2uid($_W['openid']);
	}
	if (!pdo_get('storex_mc_card_members', array('uniacid' => $_W['uniacid'], 'uid' => $uid), array('id'))) {
		return '';
	}
	$card_info = card_setting_info();
	$cardActivity = $card_info['params']['cardActivity']['params'];
	if ($card_info['params']['cardRecharge']['params']['recharge_type'] == 1) {
		if ($card_info['params']['cardRecharge']['params']['grant_rate_switch'] == 1) {
			return $cardActivity;
		} else {
			return '';
		}
	} else {
		return $cardActivity;
	}
}

//支付成功后，根据酒店设置的消费返积分的比例给积分
function card_give_credit($uid, $sum_price) {
	load()->model('mc');
	$card_credit = card_return_credit_info($uid);
	if (!empty($card_credit)) {
		$num = $sum_price * $card_credit['grant_rate'];
		$tips = "用户消费{$sum_price}元，支付{$sum_price}，会员每消费1元赠送{$card_credit['grant_rate']}积分,共赠送【{$num}】积分";
		mc_credit_update($uid, 'credit1', $num, array('0', $tips, 'wn_storex', 0, 0, 3));
	}
	return error(0, $num);
}