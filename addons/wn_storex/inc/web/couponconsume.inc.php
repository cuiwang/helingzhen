<?php

defined('IN_IA') or exit('Access Denied');

global $_W, $_GPC;
load()->model('mc');
load()->classs('coupon');
mload()->model('activity');
$ops = array('display', 'post', 'detail', 'toggle', 'modifystock', 'consume', 'delete');
$op = in_array(trim($_GPC['op']), $ops) ? trim($_GPC['op']) : 'display';

activity_get_coupon_type();
if ($op == 'display') {
	$stores = pdo_getall('activity_stores', array('uniacid' => $_W['uniacid']), array('id', 'business_name', 'branch_name'), 'id');
	$store_ids = array_keys($stores);
	$nicknames_info = pdo_getall('mc_mapping_fans', array('uniacid' => $_W['uniacid']), array('nickname', 'openid'), 'openid');
	$starttime = empty($_GPC['time']['start']) ? strtotime('-1 month') : strtotime($_GPC['time']['start']);
	$endtime = empty($_GPC['time']['end']) ? TIMESTAMP : strtotime($_GPC['time']['end']) + 86399;
	$condition = '';
	$params = array();
	$condition = ' a.uniacid = :uniacid AND b.source = :source';
	$params[':uniacid'] = $_W['uniacid'];
	$params[':source'] = COUPON_TYPE;
	$type = intval($_GPC['type']);
	if (!empty($type)) {
		$condition .= ' AND b.type = :type';
		$params[':type'] = $type;
	}
	$code = trim($_GPC['code']);
	if (!empty($code)) {
		$condition .=' AND a.code = :code';
		$params[':code'] = $code;
	}
	$couponid = intval($_GPC['couponid']);
	if (!empty($couponid)) {
		$condition .= " AND a.couponid = :couponid";
		$params[':couponid'] = $couponid;
	}
	if (!empty($_GPC['nickname'])) {
		$nicknames = pdo_fetchall("SELECT * FROM " . tablename('mc_mapping_fans') . " WHERE uniacid = :uniacid AND nickname LIKE :nickname", array(':uniacid' => $_W['uniacid'], ':nickname' => '%'.$_GPC['nickname'].'%'), 'openid');
		$nickname = array_keys($nicknames);
		$nickname = '\'' . implode('\',\'', $nickname) . '\'';
		$condition .= " AND openid in ({$nickname}) ";
	}
	$status = intval($_GPC['status']);
	if (!empty($status)) {
		$condition .= " AND a.status = :status";
		$params[':status'] = $status;
	} else {
		$condition .= " AND a.status <> :status";
		$params[':status'] = 4;
	}
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$list = pdo_fetchall("SELECT a.status AS rstatus, a.id AS recid, a.*, b.* FROM " . tablename('storex_coupon_record') . " AS a LEFT JOIN " . tablename('storex_coupon') . " AS b ON a.couponid = b.id WHERE " . $condition . " ORDER BY a.addtime DESC, a.status DESC, a.couponid DESC, a.id DESC LIMIT " . ($pindex - 1) * $psize . "," . $psize, $params);
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('storex_coupon_record') . " AS a LEFT JOIN " . tablename('storex_coupon') . " AS b ON a.couponid = b.id WHERE " . $condition, $params);
	if (!empty($list)) {
		$uids = array();
		$members = mc_fetch($uids, array('uid', 'nickname'));
		foreach ($list as &$row) {
			if (empty($row['store_id'])) {
				$row['store_name'] = '系统';
			} else {
				if (!in_array($row['store_id'], $store_ids)) {
					$row['store_name'] = '<span class="label label-danger">门店已删除</span>';
				} else {
					$row['store_name'] = $stores[$row['store_id']]['business_name'];
				}
			}
			$uids[] = $row['uid'];
			$row['extra'] = iunserializer($row['extra']);
			if ($row['type'] == COUPON_TYPE_DISCOUNT) {
				$row['extra_notes'] = $row['extra']['discount'] * 0.1 . '折';
			} elseif ($row['type'] == COUPON_TYPE_CASH) {
				$row['extra_notes'] = $row['extra']['reduce_cost'] * 0.01 . '元';
			}
			$date = iunserializer($row['date_info']);
			if ($date['time_type'] == 2) {
				$addtime = strtotime(date('Y-m-d', $row['addtime']));
				$row['starttime'] = $addtime + $date['deadline'] * 86400;
				$row['endtime'] = $addtime + ($date['limit'] - 1) * 86400;
				$row['time'] = strtotime(date('Y-m-d'));
			}
		}
		unset($row);
		foreach ($list as &$row) {
			$row['nickname'] = $members[$row['uid']]['nickname'];
		}
		unset($row);
	}

	$pager = pagination($total, $pindex, $psize);
	$status = array('1' => '未使用', '2' => '已使用');
}

if ($op == 'consume') {
	$recid = intval($_GPC['id']);
	$record = pdo_get('storex_coupon_record', array('uniacid' => $_W['uniacid'], 'id' => $recid));
	if(empty($record)) {
		message(error(-1, '兑换记录不存在'), referer(), 'ajax');
	}
	$source = intval($_GPC['source']);
	$clerk_name = trim($_W['user']['name']) ? trim($_W['user']['name']) : trim($_W['user']['username']);
	$update = array(
		'status' => 3,
		'usetime' => TIMESTAMP,
		'clerk_id' => $_W['user']['clerk_id'],
		'clerk_type' => $_W['user']['clerk_type'],
		'store_id' => $_W['user']['store_id'],
		'clerk_name' => $clerk_name,
	);
	if ($source == WECHAT_COUPON) {
		$coupon_api = new coupon();
		$status = $coupon_api->ConsumeCode(array('code' => $record['code']));
		if (is_error($status)) {
			if (strexists($status['message'], '40127')) {
				$status['message'] = '卡券已失效';
				pdo_update('storex_coupon_record', array('status' => '2'), array('uniacid' => $_W['uniacid'], 'id' => $recid));
			}
			if (strexists($status['message'], '40099')) {
				$status['message'] = '卡券已被核销';
				pdo_update('storex_coupon_record', array('status' => '3'), array('uniacid' => $_W['uniacid'], 'id' => $recid));
			}
			message(error(-1, $status['message']), '', 'ajax');
		}
	}
	$status = pdo_update('storex_coupon_record', $update, array('uniacid' => $_W['uniacid'], 'id' => $recid));
	if (!empty($status)) {
		message(error(0, '核销成功'), referer(), 'ajax');
	}
}

if ($op == 'delete') {
	$recid = intval($_GPC['id']);
	$record = pdo_get('storex_coupon_record', array('uniacid' => $_W['uniacid'], 'id' => $recid));
	if (empty($record)) {
		message(error(-1, '没有要删除的记录'), '', 'ajax');
	}
	$source = intval($_GPC['source']);
	if ($source == WECHAT_COUPON) {
		$coupon_api = new coupon();
		$status = $coupon_api->UnavailableCode(array('code' => $record['code']));
		if (is_error($status)) {
			message(error(-1, $status['message']), referer(), 'ajax');
		}
	}
	$status = pdo_delete('storex_coupon_record', array('uniacid' => $_W['uniacid'], 'id' => $recid));
	if (!empty($status)) {
		message(error(0, '删除成功'), referer(), 'ajax');
	}
}

include $this->template('couponconsume');