<?php
/**
 * [WECHAT 2017]
 
 */
defined('IN_IA') or exit('Access Denied');
$_W['page']['title'] = '卡券核销-积分兑换';

load()->model('mc');

uni_user_permission_check("activity_consume_coupon");

$dos = array('consume', 'display', 'del');
$do = in_array($do, $dos) ? $do : 'display';

if (!empty($type)) {
	$type_title = activity_coupon_type_label($type);
}
if($do == 'consume') {
	$recid = intval($_GPC['id']);
	$record = pdo_get('coupon_record', array('uniacid' => $_W['uniacid'], 'id' => $recid));
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
	if ($source == '2') {
		$status = $coupon_api->ConsumeCode(array('code' => $record['code']));
		if(is_error($status)) {
			if (strexists($status['message'], '40127')) {
				$status['message'] = '卡券已失效';
				pdo_update('coupon_record', array('status' => '2'), array('uniacid' => $_W['uniacid'], 'id' => $recid));
			}
			if (strexists($status['message'], '40099')) {
				$status['message'] = '卡券已被核销';
				pdo_update('coupon_record', array('status' => '3'), array('uniacid' => $_W['uniacid'], 'id' => $recid));
			}
			message(error(-1, $status['message']), '', 'ajax');
		}
	}
	$status = pdo_update('coupon_record', $update, array('uniacid' => $_W['uniacid'], 'id' => $recid));
	if (!empty($status)) {
		message(error(0, '核销成功'), referer(), 'ajax');
	}

}

if($do == 'display') {
	$source = (COUPON_TYPE == SYSTEM_COUPON) ? '1' : '2';
	$clerks = pdo_getall('activity_clerks', array('uniacid' => $_W['uniacid']), array('id', 'name'), 'id');
	$stores = pdo_getall('activity_stores', array('uniacid' => $_W['uniacid']), array('id', 'business_name', 'branch_name'), 'id');
	$store_ids = array_keys($stores);
	$nicknames_info = pdo_getall('mc_mapping_fans', array('uniacid' => $_W['uniacid']), array('nickname', 'openid'), 'openid');
	$condition = '';
	$coupons = pdo_fetchall('SELECT id, title FROM ' . tablename('coupon') . ' WHERE uniacid = :uniacid AND source = :source ORDER BY id DESC', array(':uniacid' => $_W['uniacid'], ':source' => $source), 'id');
	$starttime = empty($_GPC['time']['start']) ? strtotime('-1 month') : strtotime($_GPC['time']['start']);
	$endtime = empty($_GPC['time']['end']) ? TIMESTAMP : strtotime($_GPC['time']['end']) + 86399;
	$type = intval($_GPC['type']);
	if (!empty($type)) {
		$condition = "AND b.type = $type";
	}
	$where = " WHERE a.uniacid = {$_W['uniacid']} ".$condition." AND b.source = {$source}";
	$params = array();
	$code = trim($_GPC['code']);
	if (!empty($code)) {
		$where .=' AND a.code=:code';
		$params[':code'] = $code;
	}
	$couponid = intval($_GPC['couponid']);
	if (!empty($couponid)) {
		$where .= " AND a.couponid = {$couponid}";
	}
	$clerk_id = intval($_GPC['clerk_id']);
	if (!empty($clerk_id)) {
		$where .= " AND a.clerk_id = :clerk_id";
		$params[':clerk_id'] = $clerk_id;
	}
	if (!empty($_GPC['nickname'])) {
		$nicknames = pdo_fetchall('SELECT * FROM '. tablename('mc_mapping_fans')." WHERE uniacid = :uniacid AND nickname LIKE :nickname", array(':uniacid' => $_W['uniacid'], ':nickname' => '%'.$_GPC['nickname'].'%'), 'openid');
		$nickname = array_keys($nicknames);
		$nickname = '\''.implode('\',\'', $nickname).'\'';
		$where .= " AND openid in ({$nickname}) ";
	}
	$status = intval($_GPC['status']);
	if (!empty($status)) {
		$where .= " AND a.status = :status";
		$params[':status'] = $status;
	} else {
		$where .= " AND a.status <> :status";
		$params[':status'] = 4;
	}
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$list = pdo_fetchall("SELECT a.status AS rstatus,a.id AS recid, a.*, b.* FROM ".tablename('coupon_record'). ' AS a LEFT JOIN ' . tablename('coupon') . ' AS b ON a.couponid = b.id ' . " $where AND a.code <> '' ORDER BY a.status DESC, a.couponid DESC,a.id DESC LIMIT ".($pindex - 1) * $psize.','.$psize, $params);
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('coupon_record') . ' AS a LEFT JOIN ' . tablename('coupon') . ' AS b ON a.couponid = b.id '. $where ." AND a.code <> ''", $params);
	if(!empty($list)) {
		$uids = array();
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
			if($row['status'] == 2) {
				$operator = mc_account_change_operator($row['clerk_type'], $row['store_id'], $row['clerk_id']);
				$row['clerk_cn'] = $operator['clerk_cn'];
				$row['store_cn'] = $operator['store_cn'];
			}
			$row['extra'] = iunserializer($row['extra']);
			if ($row['type'] == COUPON_TYPE_DISCOUNT){
				$row['extra_notes'] = $row['extra']['discount'] * 0.1 . '折';
			} elseif ($row['type'] == COUPON_TYPE_CASH){
				$row['extra_notes'] = $row['extra']['reduce_cost'] * 0.01 . '元';
			}
			$date = iunserializer($row['date_info']);
			if ($date['time_type'] == 2) {
				$addtime = strtotime(date('Y-m-d', $row['addtime']));
				$row['starttime'] = $addtime + $date['deadline'] * 86400;
				$row['endtime'] = $starttime + ($date['limit'] - 1) * 86400;
				$row['time'] = strtotime(date('Y-m-d'));
			}
		}
		$members = mc_fetch($uids, array('uid', 'nickname'));
		foreach ($list as &$row) {
			$row['nickname'] = $members[$row['uid']]['nickname'];
			$row['logo_url'] = tomedia($row['logo_url']);
		}
	}
	$pager = pagination($total, $pindex, $psize);
	$status = array('1' => '未使用', '2' => '已使用');
	$clerks = pdo_getall('activity_clerks', array('uniacid' => $_W['uniacid']), array('id', 'name'), 'id');
}

if($do == 'del') {
	$recid = intval($_GPC['id']);
	$record = pdo_get('coupon_record', array('uniacid' => $_W['uniacid'], 'id' => $recid));
	if(empty($record)) {
		message(error(-1, '没有要删除的记录'), '', 'ajax');
	}
	$source = intval($_GPC['source']);
	if ($source == '2') {
		$status = $coupon_api->UnavailableCode(array('code' => $record['code']));
		if (is_error($status)) {
			message(error(-1, $status['message']), referer(), 'ajax');
		}
	}
	$status = pdo_delete('coupon_record', array('uniacid' => $_W['uniacid'], 'id' => $recid));
	if (!empty($status)) {
		message(error(0, '删除成功'), referer(), 'ajax');
	}
}

template('activity/consume');