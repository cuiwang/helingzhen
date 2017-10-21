<?php
/**
 * [WECHAT 2017]
 
 */
defined('IN_IA') or exit('Access Denied');
$_W['page']['title'] = '卡券营销';
load()->model('activity');
load()->model('mc');
load()->classs('coupon');
set_time_limit(0);
$dos = array('list', 'post', 'delete', 'get_member_num', 'checkcoupon');
$do = in_array($do, $dos) ? $do : 'list';
if ($do == 'checkcoupon') {
	$coupon_id = intval($_GPC['coupon']);
	$coupon = activity_coupon_info($coupon_id);
	$result = $coupon_api->fetchCard($coupon['card_id']);
	$type = strtolower($result['card_type']);
	if ($result[$type]['base_info']['status'] == 'CARD_STATUS_VERIFY_OK' || empty($coupon_id)) {
		message(error(0, '卡券可用'), '', 'ajax');
	} else {
		message(error(1, $coupon['title']), '', 'ajax');
	}
}
if ($do == 'get_member_num') {
	$type = trim($_GPC['type']);
	$param = $_GPC['param'];
	if ($type == 'cash_time') {
		$param['start'] = strtotime($param['start']);
		$param['end'] = strtotime($param['end']);
	}
	$members = activity_get_member($type, $param);
	message(error(0, $members['total']),'', 'ajax');
}
if($do == 'list') {
	$_W['page']['title'] = '卡券活动列表';
	$pindex = max(1, intval($_GPC['page']));
	$psize = 30;
	$condition = '';
	if (!empty($_GPC['title'])) {
		$condition .= "AND title LIKE '%".$_GPC['title']."%'";
	}
	$list = pdo_fetchall('SELECT * FROM '. tablename('coupon_activity')." WHERE uniacid = {$_W['uniacid']} AND type = ".COUPON_TYPE." ".$condition." ORDER BY id LIMIT ".($pindex-1)*$psize.",". $psize);
	foreach ($list as &$data) {
		$data['members'] = empty($data['members']) ? array() : iunserializer($data['members']);
		if (!empty($data['members'])) {
			if(in_array('group_member', $data['members'])) {
				$groups = pdo_getall('mc_groups', array('uniacid' => $_W['uniacid']), array(), 'groupid');
				$data['members']['group_name'] = $groups[$data['members']['groupid']]['title'];
			}
		}
	}
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM '. tablename('coupon_activity')." WHERE uniacid = {$_W['uniacid']} AND type = 1 ".$condition);
	$pager = pagination($total, $pindex, $psize);
}
elseif ($do == 'post') {
	if (checksubmit('submit')) {
		if (COUPON_TYPE == SYSTEM_COUPON) {
			$post = array(
				'uniacid' => $_W['uniacid'],
				'title' => trim($_GPC['title']),
				'type' => 1,
				'status' => intval($_GPC['status']),
				'thumb' => empty($_GPC['thumb'])? '' : $_GPC['thumb'],
				'coupons' => serialize($_GPC['coupons']),
				'members' => $_GPC['members'],
				'description' => trim($_GPC['description']),
			);
			if (in_array('group_member', $post['members'])) {
				$post['members']['groupid'] = $_GPC['group'];
			}
			if (in_array('openids', $post['members'])) {
				$post['members']['openids'] = json_decode($_COOKIE['fans_openids'.$_W['uniacid']]);
				$array = array();
				for ($i = 0; $i < count($post['members']['openids']); $i++) {
					$array[$i] = '';
				}
				$post['members']['openids'] = array_diff($post['members']['openids'], $array);
				if (empty($post['members']['openids'])) {
					message('请选择粉丝', referer(), 'info');
				}
			}
			if (in_array('cash_time', $post['members'])) {
				$post['members']['cash_time'] = $_GPC['daterange'];
			}
			$post['members'] = serialize($post['members']);
			if (!empty($id)) {
				pdo_update('coupon_activity', $post, array('id' => $id, 'uniacid' => $_W['uniacid']));
				message('更新活动成功', url('activity/market'), 'success');
			} else {
				pdo_insert('coupon_activity', $post);
				message('添加活动成功', url('activity/market'), 'success');
			}
		} else {
			$post = array(
				'uniacid' => $_W['uniacid'],
				'title' => trim($_GPC['title']),
				'type' => 2,
				'status' => 0,
				'coupons' => $_GPC['coupons'],
				'members' => $_GPC['members'],
			);
			if (!empty($_GPC['group'])) {
				$post['members']['groupid'] = $_GPC['group'];
			}
			if (empty($id)) {
				$openids = array();
				$param = array();
				if ($post['members'][0] == 'cash_time') {
					$param['start'] = strtotime($_GPC['daterange']['start']);
					$param['end'] = strtotime($_GPC['daterange']['end']);
				}
				if ($post['members'][0] == 'group_member') {
					$param['groupid'] = intval($_GPC['groupid']);
				}
				$openids = activity_get_member($post['members'][0], $param);
				$openids = $openids['members'];
				$account_api = WeAccount::create();
				foreach ($post['coupons'] as $coupon) {
					$post['members'] = serialize($post['members']);
					$post['coupons'] = serialize($post['coupons']);
					foreach ($openids as $openid) {
						$result = activity_coupon_grant($coupon, $openid);
						$coupon_info = activity_coupon_info($coupon);
						$send['touser'] = $openid;
						$send['msgtype'] = 'text';
						$send['text'] = array('content' => urlencode($_W['account']['name'].'赠送了您一张'.$coupon_info['title'].'，请到会员中心查收'));
						$data = $account_api->sendCustomNotice($send);
					}
					$post['msg_id'] = $result['msg_id'];
					pdo_insert('coupon_activity', $post);
				}
				message('卡券发放成功', url('activity/market'), 'success');
			}
		}
	}
	$id = intval($_GPC['id']);
	$coupons = pdo_getall('coupon', array('uniacid' => $_W['uniacid'], 'source' => COUPON_TYPE));
	foreach ($coupons as $key => &$coupon) {
		$coupon = activity_coupon_info($coupon['id']);
		if (strtotime(date('Y-m-d')) < strtotime(str_replace('.', '-', $coupon['date_info']['time_limit_start'])) || strtotime(date('Y-m-d')) > strtotime(str_replace('.', '-', $coupon['date_info']['time_limit_end']))) {
			if ($coupon['date_info']['time_type'] == 1) {
				unset($coupons[$key]);
			}
		}
		$coupon['extra'] = iunserializer($coupon['extra']);
	}
	if (!empty($id)) {
		$item = pdo_get('coupon_activity', array('id' => $id, 'uniacid' => $_W['uniacid']));
		$item['coupons'] =  empty($item['coupons']) ? array() : iunserializer($item['coupons']);
		foreach ($item['coupons'] as $key => $couponid) {
			$couponid = pdo_get('coupon', array('id' => $couponid));
			if (empty($couponid)) {
				unset($item['coupons'][$key]);
				continue;
			}
			unset($item['coupons'][$key]);
			$item['coupons'][$couponid['id']] = $couponid;
		}
		$item['members'] = iunserializer($item['members']);
		if (COUPON_TYPE == SYSTEM_COUPON) {
			if (!empty($item['members']['openids'])) {
				setcookie('fans_openids'.$_W['uniacid'], json_encode($item['members']['openids']));
			} else {
				setcookie('fans_openids'.$_W['uniacid'], '');
			}
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
if ($do == 'delete') {
	$id = intval($_GPC['id']);
	pdo_delete('coupon_activity', array('id' => $id, 'uniacid' => $_W['uniacid']));
	message('删除活动成功', url('activity/market'), 'success');
}
template('activity/market');