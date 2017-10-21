<?php

defined('IN_IA') or exit('Access Denied');

global $_W, $_GPC;
load()->model('mc');
mload()->model('card');

$ops = array('display', 'record', 'delete', 'modal', 'submit', 'status', 'user', 'consume', 'credit', 'card', 'cardsn');
$op = in_array(trim($_GPC['op']), $ops) ? trim($_GPC['op']) : 'display';

//判断会员卡开启
$extend_switch = extend_switch_fetch();
if ($extend_switch['card'] == 2) {
//	message('会员卡功能未开启', referer(), 'error');
}

$setting = pdo_get('storex_mc_card', array('uniacid' => $_W['uniacid']));

if ($op == 'display') {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	
	$param = array(':uniacid' => $_W['uniacid']);
	$cardsn = trim($_GPC['cardsn']);
	if (!empty($cardsn)) {
		$where .= ' AND a.cardsn LIKE :cardsn';
		$param[':cardsn'] = "%{$cardsn}%";
	}
	$status = isset($_GPC['status']) ? intval($_GPC['status']) : -1;
	if ($status >= 0) {
		$where .= " AND a.status = :status";
		$param[':status'] = $status;
	}
	$num = isset($_GPC['num']) ? intval($_GPC['num']) : -1;
	if ($num >= 0) {
		if (!$num) {
			$where .= " AND a.nums = 0";
		} else {
			$where .= " AND a.nums > 0";
		}
	}
	$endtime = isset($_GPC['endtime']) ? intval($_GPC['endtime']) : -1;
	if ($endtime >= 0) {
		$where .= " AND a.endtime <= :endtime";
		$param[':endtime'] = strtotime($endtime . 'days');
	}
	
	$keyword = trim($_GPC['keyword']);
	if (!empty($keyword)) {
		$where .= " AND (a.mobile LIKE '%{$keyword}%' OR a.realname LIKE '%{$keyword}%')";
	}
	$sql = "SELECT a.*, b.groupid, b.credit1, b.credit2 FROM " . tablename('storex_mc_card_members') . " AS a LEFT JOIN " . tablename('mc_members') . " AS b ON a.uid = b.uid WHERE a.uniacid = :uniacid $where ORDER BY a.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
	$list = pdo_fetchall($sql, $param);
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('storex_mc_card_members') . " AS a LEFT JOIN " . tablename('mc_members') . " AS b ON a.uid = b.uid WHERE a.uniacid = :uniacid $where", $param);
	$pager = pagination($total, $pindex, $psize);
}

if ($op == 'record') {
	$uid = intval($_GPC['uid']);
	$card = pdo_get('storex_mc_card_members', array('uniacid' => $_W['uniacid'], 'uid' => $uid));
	$where = ' WHERE uniacid = :uniacid AND uid = :uid';
	$param = array(':uniacid' => $_W['uniacid'], ':uid' => $uid);
	$type = trim($_GPC['type']);
	if (!empty($type)) {
		$where .= ' AND type = :type';
		$param[':type'] = $type;
	}
	if (empty($_GPC['endtime']['start'])) {
		$starttime = strtotime('-30 days');
		$endtime = TIMESTAMP;
	} else {
		$starttime = strtotime($_GPC['endtime']['start']);
		$endtime = strtotime($_GPC['endtime']['end']) + 86399;
	}
	$where .= ' AND addtime >= :starttime AND addtime <= :endtime';
	$param[':starttime'] = $starttime;
	$param[':endtime'] = $endtime;
	
	$pindex = max(1, intval($_GPC['page']));
	$psize = 30;
	$limit = " ORDER BY id DESC LIMIT " . ($pindex -1) * $psize . ", {$psize}";
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('storex_mc_card_record') . " {$where}", $param);
	$list = pdo_fetchall("SELECT * FROM " . tablename('storex_mc_card_record') . " {$where} {$limit}", $param);
	$pager = pagination($total, $pindex, $psize);
}

if ($op == 'delete') {
	$cardid = intval($_GPC['cardid']);
	if (pdo_delete('storex_mc_card_members', array('id' =>$cardid))) {
		message('删除会员卡成功', referer(), 'success');
	} else {
		message('删除会员卡失败', referer(), 'error');
	}
}

if ($op == 'modal') {
	$uid = intval($_GPC['uid']);
	$card = pdo_get('storex_mc_card_members', array('uniacid' => $_W['uniacid'], 'uid' => $uid));
	if (empty($card)) {
		exit('error');
	}
	include $this->template('cardmodel');
	exit();
}

if ($op == 'submit') {
	load()->model('mc');
	$uid = intval($_GPC['uid']);
	$card = pdo_get('storex_mc_card_members', array('uniacid' => $_W['uniacid'], 'uid' => $uid));
	if (empty($card)) {
		message('用户会员卡信息不存在', referer(), 'error');
	}
	$type = trim($_GPC['type']);
	if ($type == 'nums_plus') {
		$fee = floatval($_GPC['fee']);
		$tag = intval($_GPC['nums']);
		if (!$fee && !$tag) {
			message('请完善充值金额和充值次数', referer(), 'error');
		}
		$total_num = $card['nums'] + $tag;
		pdo_update('storex_mc_card_members', array('nums' => $total_num), array('uniacid' => $_W['uniacid'], 'uid' => $uid));
		$log = array(
			'uniacid' => $_W['uniacid'],
			'uid' => $uid,
			'type' => 'nums',
			'model' => 1,
			'fee' => $fee,
			'tag' => $tag,
			'addtime' => TIMESTAMP,
			'note' => date('Y-m-d H:i') . "充值{$fee}元，管理员手动添加{$tag}次，添加后总次数为{$total_num}次",
			'remark' => trim($_GPC['remark']),
		);
		pdo_insert('storex_mc_card_record', $log);
		mc_notice_nums_plus($card['openid'], $setting['nums_text'], $tag, $total_num);
	}
	
	if ($type == 'nums_times') {
		$tag = intval($_GPC['nums']);
		if (!$tag) {
			message('请填写消费次数', referer(), 'error');
		}
		if ($card['nums'] < $tag) {
			message('当前用户的消费次数不够', referer(), 'error');
		}
		$total_num = $card['nums'] - $tag;
		pdo_update('storex_mc_card_members', array('nums' => $total_num), array('uniacid' => $_W['uniacid'], 'uid' => $uid));
		$log = array(
			'uniacid' => $_W['uniacid'],
			'uid' => $uid,
			'type' => 'nums',
			'model' => 2,
			'fee' => 0,
			'tag' => $tag,
			'addtime' => TIMESTAMP,
			'note' => date('Y-m-d H:i') . "消费1次，管理员手动减1次，消费后总次数为{$total_num}次",
			'remark' => trim($_GPC['remark']),
		);
		pdo_insert('storex_mc_card_record', $log);
		mc_notice_nums_times($card['openid'], $card['cardsn'], $setting['nums_text'], $total_num);
	}
	
	if ($type == 'times_plus') {
		$fee = floatval($_GPC['fee']);
		$endtime = strtotime($_GPC['endtime']);
		$days = intval($_GPC['days']);
		if ($endtime <= $card['endtime'] && !$days) {
			message('服务到期时间不能小于会员当前的服务到期时间或未填写延长服务天数', '', 'error');
		}
		$tag = floor(($endtime - $card['endtime']) / 86400);
		if ($days > 0) {
			$tag = $days;
			if ($card['endtime'] > TIMESTAMP) {
				$endtime = $card['endtime'] + $days * 86400;
			} else {
				$endtime = strtotime($days . 'days');
			}
		}
		pdo_update('storex_mc_card_members', array('endtime' => $endtime), array('uniacid' => $_W['uniacid'], 'uid' => $uid));
		$endtime = date('Y-m-d', $endtime);
		$log = array(
			'uniacid' => $_W['uniacid'],
			'uid' => $uid,
			'type' => 'times',
			'model' => 1,
			'fee' => $fee,
			'tag' => $tag,
			'addtime' => TIMESTAMP,
			'note' => date('Y-m-d H:i') . "充值{$fee}元，管理员手动设置{$setting['times_text']}到期时间为{$endtime},设置之前的{$setting['times_text']}到期时间为".date('Y-m-d', $card['endtime']),
			'remark' => trim($_GPC['remark']),
		);
		pdo_insert('storex_mc_card_record', $log);
		mc_notice_times_plus($card['openid'], $card['cardsn'], $setting['times_text'], $fee, $tag, $endtime);
	}
	
	if ($type == 'times_times') {
		$endtime = strtotime($_GPC['endtime']);
		if ($endtime > $card['endtime']) {
			message("该会员的{$setting['times_text']}到期时间为：" . date('Y-m-d', $card['endtime']) . ",您当前在进行消费操作，设置到期时间不能超过" . date('Y-m-d', $card['endtime']) , '', 'error');
		}
		$flag = intval($_GPC['flag']);
		if ($flag) {
			$endtime = TIMESTAMP;
		}
		$tag = floor(($card['endtime'] - $endtime) / 86400);
		pdo_update('storex_mc_card_members', array('endtime' => $endtime), array('uniacid' => $_W['uniacid'], 'uid' => $uid));
		$endtime = date('Y-m-d', $endtime);
		$log = array(
			'uniacid' => $_W['uniacid'],
			'uid' => $uid,
			'type' => 'times',
			'model' => 2,
			'fee' => 0,
			'tag' => $tag,
			'addtime' => TIMESTAMP,
			'note' => date('Y-m-d H:i') . "管理员手动设置{$setting['times_text']}到期时间为{$endtime},设置之前的{$setting['times_text']}到期时间为".date('Y-m-d', $card['endtime']),
			'remark' => trim($_GPC['remark']),
		);
		pdo_insert('storex_mc_card_record', $log);
		mc_notice_times_times($card['openid'], "您好，您的{$setting['times_text']}到期时间已变更", $setting['times_text'], $endtime);
	}
	message('操作成功', referer(), 'success');
}

if ($op == 'status') {
	if ($_W['ispost']) {
		$cardid = intval($_GPC['cardid']);
		$status = array('status' => intval($_GPC['status']));
		if (false === pdo_update('storex_mc_card_members', $status, array('uniacid' => $_W['uniacid'], 'id' => $cardid))) {
			exit('error');
		}
		exit('success');
	}
}

if ($op == 'user') {
	$type = trim($_GPC['type']);
	if (!in_array($type, array('uid', 'mobile'))) {
		$type = 'mobile';
	}
	$username = trim($_GPC['username']);
	$data = pdo_getall('mc_members', array('uniacid' => $_W['uniacid'], $type => $username));
	if (empty($data)) {
		exit(json_encode(array('error' => 'empty', 'message' => '没有找到对应用户')));
	} elseif (count($data) > 1) {
		exit(json_encode(array('error' => 'not-unique', 'message' => '用户不唯一,请重新输入用户信息')));
	} else {
		$user = $data[0];
		$user['groupname'] = $_W['account']['groups'][$user['groupid']]['title'];
		
		$card = card_setting_info();
		$member = pdo_get('storex_mc_card_members', array('uniacid' => $_W['uniacid'], 'uid' => $user['uid']));
		if (!empty($card) && $card['status'] == 1) {
			if (!empty($member)) {
				$str = "会员卡号:{$member['cardsn']}.";
				$user['discount'] = $card['discount'][$user['groupid']];
				$user['cardsn'] = $member['cardsn'];
				if (!empty($user['discount']) && !empty($user['discount']['discount'])) {
					$str .= "折扣:满{$user['discount']['condition']}元";
					if ($card['discount_type'] == 1) {
						$str .= "减{$user['discount']['discount']}元";
					} else {
						$discount = $user['discount']['discount'] * 10;
						$str .= "打{$discount}折";
					}
					$user['discount_cn'] = $str;
				}
			} else {
				$user['discount_cn'] = '会员未领取会员卡,不能享受优惠';
			}
		} else {
			$user['discount_cn'] = '商家未开启会员卡功能';
		}
		$html = "姓名:{$user['realname']},会员组:{$user['groupname']}<br>";
		$html .= "{$user['discount_cn']}<br>";
		$html .= "余额:{$user['credit2']}元,积分:{$user['credit1']}<br>";
	
		if (!empty($card) && $card['offset_rate'] > 0 && $card['offset_max'] > 0) {
			$html .= "{$card['offset_rate']}积分可抵消1元。最多可抵消{$card['offset_max']}元";
		}
		exit(json_encode(array('error' => 'none', 'user' => $user, 'html' => $html, 'card' => $card, 'group' => $_W['account']['groups'], 'grouplevel' => $_W['account']['grouplevel'])));
	}
}

if ($op == 'cardsn') {
	$uid = intval($_GPC['uid']);
	$cardsn = trim($_GPC['cardsn']);
	$type = trim($_GPC['type']);
	if ($_W['isajax'] && $type == 'check') {
		$data = pdo_get('storex_mc_card_members', array('cardsn' => $cardsn, 'uniacid' => $_W['uniacid']));
		if (!empty($data)) {
			exit(json_encode(array('valid' => false)));
		} else {
			exit(json_encode(array('valid' => true)));
		}
	} else {
		pdo_update('storex_mc_card_members', array('cardsn' => $cardsn), array('uid' => $uid, 'uniacid' => $_W['uniacid']));
		exit('success');
	}
}

if ($_W['isajax'] && in_array($op, array('consume', 'credit', 'card'))) {
	$uid = intval($_GPC['uid']);
	$user = pdo_get('mc_members', array('uniacid' => $_W['uniacid'], 'uid' => $uid));
	if (empty($user)) {
		exit('会员不存在');
	}
}

if ($op == 'consume') {
	$total = $money = floatval($_GPC['total']);
	if (!$total) {
		exit('消费金额不能为空');
	}
	$log = "系统日志:会员消费【{$total}】元";
	$user['groupname'] = $_W['account']['groups'][$user['groupid']]['title'];
	
	$card = array();
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
					$money = sprintf("%.1f", $money);
				}
				if ($money < 0) {
					$money = 0;
				}
				$log .= ",实收金额【{$money}】元";
			}
		}
	}
	$post_money = floatval($_GPC['money']);
	if ($post_money != $money) {
		exit('实收金额错误');
	}
	$post_credit1 = intval($_GPC['credit1']);
	if ($post_credit1 > 0) {
		if ($post_credit1 > $user['credit1']) {
			exit('超过会员账户可用积分');
		}
	}
	$post_offset_money = intval($_GPC['offset_money']);
	$offset_money = 0;
	if ($post_credit1 && $card['offset_rate'] > 0 && $card['offset_max'] > 0) {
		$offset_money = min($card['offset_max'], $post_credit1 / $card['offset_rate']);
		if ($offset_money != $post_offset_money) {
			exit('积分抵消金额错误');
		}
		$credit1 = $post_credit1;
		$log .= ",使用【{$post_credit1}】积分抵消【{$offset_money}】元";
	}
	
	$credit2 = floatval($_GPC['credit2']);
	if ($credit2 > 0) {
		if ($credit2 > $user['credit2']) {
			exit('超过会员账户可用余额');
		}
		$log .= ",使用余额支付【{$credit2}】元";
	}
	
	$cash = floatval($_GPC['cash']);
	$sum = $credit2 + $cash + $offset_money;
	$final_cash = $money - $credit2 - $offset_money;
	$return_cash = $sum - $money;
	if ($sum < $money) {
		exit('支付金额小于实收金额');
	}
	if ($cash > 0) {
		$log .= ",使用现金支付【{$cash}】元";
	}
	if ($return_cash > 0) {
		$log .= ",找零【{$return_cash}】元";
	}
	if (!empty($_GPC['remark'])) {
		$note = "店员备注：{$_GPC['remark']}";
	}
	$log = $note.$log;
	if ($credit2 > 0) {
		$status = mc_credit_update($uid, 'credit2', -$credit2, array(0, $log, 'system', $_W['user']['clerk_id'], $_W['user']['store_id'], $_W['user']['clerk_type']));
		if (is_error($status)) {
			exit($status['message']);
		}
	}
	if ($credit1 > 0) {
		$status = mc_credit_update($uid, 'credit1', -$credit1, array(0, $log, 'system', $_W['user']['clerk_id'], $_W['user']['store_id'], $_W['user']['clerk_type']));
		if (is_error($status)) {
			exit($status['message']);
		}
	}
	
	$data = array(
		'uniacid' => $_W['uniacid'],
		'uid' => $uid,
		'fee' => $total,
		'final_fee' => $money,
		'credit1' => $post_credit1,
		'credit1_fee' => $offset_money,
		'credit2' => $credit2,
		'cash' => $cash,
		'final_cash' => $final_cash,
		'return_cash' => $return_cash,
		'remark' => $log,
		'clerk_id' => $_W['user']['clerk_id'],
		'store_id' => $_W['user']['store_id'],
		'clerk_type' => $_W['user']['clerk_type'],
		'createtime' => TIMESTAMP,
	);
	pdo_insert('mc_cash_record', $data);
	
	$tips = "用户消费{$money}元,使用{$data['credit1']}积分,抵现{$data['credit1_fee']}元,使用余额支付{$data['credit2']}元,现金支付{$data['final_cash']}元";
	$recharges_set = $card['params']['cardRecharge'];
	$grant_rate_switch = intval($recharges_set['params']['grant_rate_switch']);
	//赠送积分（按照会员卡的积分比率进行赠送）,会员卡开启充值优惠设置则不赠送积分,现金消费除外
	$grant_credit1_enable = false;
	$grant_money = $money;
	if (!empty($card) && $card['grant_rate'] > 0 && !empty($member)) {
		if (empty($recharges_set['params']['recharge_type'])) {
			$grant_credit1_enable = true;
		} else {
			if ($grant_rate_switch == '1') {
				$grant_money = $data['cash'] + $data['credit2'];
				$grant_credit1_enable = true;
			} else {
				if (!empty($data['cash'])) {
					$grant_money = $data['cash'];
					$grant_credit1_enable = true;
				}
			}
		}
	}
	if (!empty($grant_credit1_enable)) {
		$num = floor($grant_money * $card['grant_rate']);
		$tips .= "，积分赠送比率为:【1：{$card['grant_rate']}】,共赠送【{$num}】积分";
		mc_credit_update($uid, 'credit1', $num, array(0, $tips, 'system', $_W['user']['clerk_id'], $_W['user']['store_id'], $_W['user']['clerk_type']));
	}
	//通知
	$openid = pdo_fetchcolumn('SELECT openid FROM ' . tablename('mc_mapping_fans') . ' WHERE acid = :acid AND uid = :uid', array(':acid' => $_W['acid'], ':uid' => $uid));
	$consume_tips = array(
		'uid' => $uid,
		'credit2_num' => $money,
		'credit1_num' => $num,
		'store' => '系统后台',
		'remark' => $tips,
	);
	if (!empty($openid)) {
		mc_notice_consume($openid, '会员消费通知', $consume_tips);
	}
	exit('success');
}

if ($op == 'credit') {
	$type = trim($_GPC['type']);
	$num = floatval($_GPC['num']);
	$names = array('credit1' => '积分', 'credit2' => '余额');
	$credits = mc_credit_fetch($uid);
	if ($num < 0 && abs($num) > $credits[$type]) {
		exit("会员账户{$names[$type]}不够");
	}
	$status = mc_credit_update($uid, $type, $num, array($_W['user']['uid'], trim($_GPC['remark']), 'system', $_W['user']['clerk_id'], $_W['user']['store_id'], $_W['user']['clerk_type']));
	if (is_error($status)) {
		exit($status['message']);
	}
	//变更会员组
	if ($type == 'credit1') {
		mc_group_update($uid);
	}
	$openid = pdo_fetchcolumn('SELECT openid FROM ' . tablename('mc_mapping_fans') . ' WHERE acid = :acid AND uid = :uid', array(':acid' => $_W['acid'], ':uid' => $uid));
	if (!empty($openid)) {
		if ($type == 'credit1') {
			mc_notice_credit1($openid, $uid, $num, '管理员后台操作积分');
		}
		if ($type == 'credit2') {
			if ($num > 0) {
				mc_notice_recharge($openid, $uid, $num, '', "管理员后台操作余额,增加{$value}余额");
			} else {
				mc_notice_credit2($openid, $uid, $num, 0, '', '', "管理员后台操作余额,减少{$value}余额");
			}
		}
	}
	exit('success');
}

if ($op == 'card') {
	$card = card_setting_info();
	if (empty($card)) {
		exit('公众号未设置会员卡');
	}
	$member = pdo_get('storex_mc_card_members', array('uniacid' => $_W['uniacid'], 'uid' => $user['uid']));
	if (!empty($member)) {
		exit('该会员已领取会员卡');
	}
	$cardsn = $card['format'];
	preg_match_all('/(\*+)/', $card['format'], $matchs);
	if (!empty($matchs)) {
		foreach ($matchs[1] as $row) {
			$cardsn = str_replace($row, random(strlen($row), 1), $cardsn);
		}
	}
	preg_match('/(\#+)/', $card['format'], $matchs);
	$length = strlen($matchs[1]);
	$pos = strpos($card['format'], '#');
	$cardsn = str_replace($matchs[1], str_pad($card['snpos']++, $length - strlen($number), '0', STR_PAD_LEFT), $cardsn);
	
	$record = array(
		'uniacid' => $_W['uniacid'],
		'openid' => '',
		'uid' => $uid,
		'cid' => $card['id'],
		'cardsn' => $_GPC['username'],
		'status' => '1',
		'createtime' => TIMESTAMP,
		'endtime' => TIMESTAMP
	);
	if (pdo_insert('storex_mc_card_members', $record)) {
		pdo_update('storex_mc_card', array('snpos' => $card['snpos']), array('uniacid' => $_W['uniacid'], 'id' => $card['id']));
		//赠送积分.余额.优惠券
		$notice = '';
		if ($card['grant']['credit1'] > 0) {
			$log = array(
				$uid,
				"领取会员卡，赠送{$card['grant']['credit1']}积分",
				'system',
				$_W['user']['clerk_id'],
				$_W['user']['store_id'],
				$_W['user']['clerk_type']
			);
			mc_credit_update($uid, 'credit1', $card['grant']['credit1'], $log);
		}
		if ($card['grant']['credit2'] > 0) {
			$log = array(
				$uid,
				"领取会员卡，赠送{$card['credit2']['credit1']}余额",
				'system',
				$_W['user']['clerk_id'],
				$_W['user']['store_id'],
				$_W['user']['clerk_type']
			);
			mc_credit_update($uid, 'credit2', $card['grant']['credit2'], $log);
		}
		if (!empty($card['grant']['coupon']) && is_array($card['grant']['coupon'])) {
			foreach ($card['grant']['coupon'] as $grant_coupon) {
				mload()->model('activity');
				activity_user_get_coupon($grant_coupon['coupon'], $uid);
			}
		}
		exit('success');
	}
}
include $this->template('cardmanage');