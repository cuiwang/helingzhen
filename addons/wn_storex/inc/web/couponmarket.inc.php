<?php

defined('IN_IA') or exit('Access Denied');

global $_W, $_GPC;

load()->model('mc');
load()->classs('coupon');
mload()->model('activity');

$ops = array('display', 'post', 'get_member_num', 'checkcoupon', 'delete');
$op = in_array(trim($_GPC['op']), $ops) ? trim($_GPC['op']) : 'display';

activity_get_coupon_type();
$types = activity_get_coupon_label();
$propertys = activity_storex_member_propertys();
if ($op == 'checkcoupon') {
	$coupon_id = intval($_GPC['coupon']);
	$coupon = activity_get_coupon_info($coupon_id);
	$coupon_api = new coupon();
	$result = $coupon_api->fetchCard($coupon['card_id']);
	$type = strtolower($result['card_type']);
	if ($result[$type]['base_info']['status'] == 'CARD_STATUS_VERIFY_OK' || empty($coupon_id)) {
		message(error(0, '卡券可用'), '', 'ajax');
	} else {
		message(error(1, $coupon['title']), '', 'ajax');
	}
}

if ($op == 'display') {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$condition = '';
	if (!empty($_GPC['title'])) {
		$condition .= " AND title LIKE '%".$_GPC['title']."%'";
	}
	$list = pdo_getall('storex_coupon_activity', array('uniacid' => intval($_W['uniacid']), 'type' => COUPON_TYPE, 'title LIKE' => '%'.$_GPC['title'].'%'), array(), '', 'id ASC', array($pindex, $psize));
	foreach ($list as &$data) {
		$data['members'] = empty($data['members']) ? array() : iunserializer($data['members']);
		if (!empty($data['members'])) {
			if (in_array('group_member', $data['members'])) {
				$groups = pdo_getall('mc_groups', array('uniacid' => $_W['uniacid']), array(), 'groupid');
				$data['members']['group_name'] = $groups[$data['members']['groupid']]['title'];
			}
		}
	}
	unset($data);
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('storex_coupon_activity') . " WHERE uniacid = :uniacid AND type = :type " . $condition, array(':uniacid' => intval($_W['uniacid']), ':type' => COUPON_TYPE));
	$pager = pagination($total, $pindex, $psize);
}

if ($op == 'post') {
	$id = intval($_GPC['id']);
	if (checksubmit('submit')) {
		$post = array(
			'uniacid' => $_W['uniacid'],
			'title' => trim($_GPC['title']),
			'type' => COUPON_TYPE,
			'status' => intval($_GPC['status']),
			'coupons' => intval($_GPC['coupon']),
			'members' => $_GPC['members'],
			'thumb' => empty($_GPC['thumb'])? '' : $_GPC['thumb'],
		);
		if (empty($id)) {
			if (COUPON_TYPE == SYSTEM_COUPON) {
				$post['description'] = trim($_GPC['description']);
			}
			$openids = array();
			$param = array();
			if (in_array('group_member', $post['members'])) {
				$post['members']['groupid'] = $_GPC['groupid'];
				$param['groupid'] = intval($_GPC['groupid']);
			}
			if (in_array('cash_time', $post['members'])) {
				$post['members']['cash_time'] = $_GPC['daterange'];
				$param['start'] = strtotime($_GPC['daterange']['start']);
				$param['end'] = strtotime($_GPC['daterange']['end']);
			}
			if (in_array('openids', $post['members'])) {
				$post['members']['openids'] = json_decode($_COOKIE['fans_openids'.$_W['uniacid']]);
				$compare_array = array();
				for ($i = 0; $i < count($post['members']['openids']); $i++) {
					$compare_array[$i] = '';
				}
				$post['members']['openids'] = array_diff($post['members']['openids'], $compare_array);
				if (empty($post['members']['openids'])) {
					message('请选择粉丝', referer(), 'info');
				}
			}
			$openids = activity_get_member_by_type($post['members'][0], $param);
			$post['members'] = serialize($post['members']);
			$openids = $openids['members'];
			$account_api = WeAccount::create();
			foreach ($openids as $openid) {
				$result = activity_user_get_coupon($post['coupons'], $openid, 3);
				$coupon_info = activity_get_coupon_info($post['coupons']);
				$send['touser'] = $openid;
				$send['msgtype'] = 'text';
				$send['text'] = array('content' => urlencode($_W['account']['name'].'赠送了您一张'.$coupon_info['title'].'，请到会员中心查收'));
				$data = $account_api->sendCustomNotice($send);
			}
			if (is_array($result)) {
				$post['msg_id'] = $result['errno'];
			}
			pdo_insert('storex_coupon_activity', $post);
			message('添加卡券派发活动成功', $this->createWeburl('couponmarket', array('op' => 'display')), 'success');
		}
	}
	
	//获取可以选择派发的卡券
	$coupons = pdo_getall('storex_coupon', array('uniacid' => intval($_W['uniacid']), 'source' => COUPON_TYPE, 'status' => '3', 'is_display' => '1', 'quantity >' => '0'), array(), '', 'id DESC');
	foreach ($coupons as $key => &$coupon) {
		$coupon = activity_get_coupon_info($coupon['id']);
		if (strtotime(date('Y-m-d')) < strtotime(str_replace('.', '-', $coupon['date_info']['time_limit_start'])) || strtotime(date('Y-m-d')) > strtotime(str_replace('.', '-', $coupon['date_info']['time_limit_end']))) {
			if ($coupon['date_info']['time_type'] == 1) {
				unset($coupons[$key]);
			}
		}
		$coupon['extra'] = iunserializer($coupon['extra']);
	}
	unset($coupon);
	if (!empty($id)) {
		$item = pdo_get('storex_coupon_activity', array('id' => $id, 'uniacid' => $_W['uniacid']));
		$item['members'] = iunserializer($item['members']);
		if (COUPON_TYPE == SYSTEM_COUPON) {
			if (!empty($item['members']['openids'])) {
				setcookie('fans_openids'.$_W['uniacid'], json_encode($item['members']['openids']));
			} else {
				setcookie('fans_openids'.$_W['uniacid'], '');
			}
		}
		if (!empty($item['coupons'])) {
			$item['coupon_info'] = activity_get_coupon_info($item['coupons']);
		}
	} else {
		setcookie('fans_openids'.$_W['uniacid'], '');
	}
	if (COUPON_TYPE == SYSTEM_COUPON) {
		$groups = pdo_getall('mc_groups', array('uniacid' => $_W['uniacid']), array(), 'groupid');
	} else {
		$groups = mc_fans_groups();
		foreach ($groups as &$group) {
			$group['groupid'] = $group['id'];
		}
		unset($group);
	}
}

if ($op == 'get_member_num') {
	$type = trim($_GPC['type']);
	$param = $_GPC['param'];
	if ($type == 'cash_time') {
		$param['start'] = strtotime($param['start']);
		$param['end'] = strtotime($param['end']);
	}
	$members = activity_get_member_by_type($type, $param);
	message(error(0, $members['total']),'', 'ajax');
}

if ($op == 'delete') {
	$id = intval($_GPC['id']);
	pdo_delete('storex_coupon_activity', array('id' => $id, 'uniacid' => $_W['uniacid']));
	message('删除活动成功', $this->createWeburl('couponmarket', array('op' => 'display')), 'success');
}
include $this->template('couponmarket');