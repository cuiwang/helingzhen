<?php

defined('IN_IA') or exit('Access Denied');

global $_W, $_GPC;
load()->model('mc');
load()->classs('coupon');
mload()->model('activity');

$ops = array('chart', 'display');
$op = in_array(trim($_GPC['op']), $ops) ? trim($_GPC['op']) : 'display';

activity_get_coupon_type();

$starttime = empty($_GPC['time']['start']) ? mktime(0, 0, 0, date('m'), 1, date('Y')) : strtotime($_GPC['time']['start']);
$endtime = empty($_GPC['time']['end']) ? TIMESTAMP : strtotime($_GPC['time']['end']) + 86399;
$num = ($endtime + 1 - $starttime) / 86400;

if ($op == 'display') {
	$stores = pdo_getall('storex_activity_stores', array('uniacid' => $_W['uniacid'], 'source' => COUPON_TYPE), array('id', 'business_name', 'branch_name'), 'id');
	$condition = ' WHERE uniacid = :uniacid AND createtime >= :starttime AND createtime <= :endtime';
	$params = array(':uniacid' => $_W['uniacid'], ':starttime' => $starttime, ':endtime' => $endtime);
	$min = intval($_GPC['min']);
	if ($_W['user']['clerk_type'] == '3') {
		$current_clerk_id = $_W['user']['clerk_id'];
		$condition .= " AND clerk_type = 3 AND clerk_id = {$current_clerk_id}";
	}
	if ($min > 0) {
		$condition .= ' AND abs(final_fee) >= :minnum';
		$params[':minnum'] = $min;
	}
	
	$max = intval($_GPC['max']);
	if ($max > 0) {
		$condition .= ' AND abs(final_fee) <= :maxnum';
		$params[':maxnum'] = $max;
	}
	$clerk_id = intval($_GPC['clerk_id']);
	if (!empty($clerk_id)) {
		$condition .= ' AND clerk_id = :clerk_id';
		$params[':clerk_id'] = $clerk_id;
	}
	$store_id = trim($_GPC['store_id']);
	if (!empty($store_id)) {
		$condition .= " AND store_id = :store_id";
		$params[':store_id'] = $store_id;
	}
	$user = trim($_GPC['user']);
	if (!empty($user)) {
		$condition .= ' AND (uid IN (SELECT uid FROM ' . tablename('mc_members') . ' WHERE uniacid = :uniacid AND (realname LIKE :username OR uid = :uid OR mobile LIKE :mobile)))';
		$params[':username'] = "%{$user}%";
		$params[':uid'] = intval($user);
		$params[':mobile'] = "%{$user}%";
	}
	
	$psize = 30;
	$pindex = max(1, intval($_GPC['page']));
	$limit = " ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ", {$psize}";
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_cash_record') . $condition, $params);
	$data = pdo_fetchall('SELECT * FROM ' . tablename('mc_cash_record') . $condition . $limit, $params);
	
	if (!empty($data)) {
		load()->model('clerk');
		$uids = array();
		foreach ($data as &$da) {
			if (!in_array($da['uid'], $uids)) {
				$uids[] = $da['uid'];
			}
			$operator = storex_account_change_operator($da['clerk_type'], $da['store_id'], $da['clerk_id']);
			$da['clerk_cn'] = $operator['clerk_cn'];
			$da['store_cn'] = $operator['store_cn'];
			if (empty($da['clerk_type'])) {
				$da['clerk_cn'] = '本人会员卡付款';
			}
		}
		unset($da);
		$users = pdo_getall('mc_members', array('uniacid' => intval($_W['uniacid']), 'uid' => $uids), array('mobile', 'uid', 'realname'), 'uid');
	}
	$pager = pagination($total, $pindex, $psize);
	if ($_GPC['export'] != '') {
		$exports = pdo_fetchall("SELECT * FROM " . tablename ('mc_cash_record') . $condition . " ORDER BY uid DESC", $params);
		if (!empty($exports)) {
			load()->model('clerk');
			$uids = array();
			foreach ($exports as &$da) {
				if (!in_array($da['uid'], $uids)) {
					$uids[] = $da['uid'];
				}
				$operator = storex_account_change_operator ($da['clerk_type'], $da['store_id'], $da['clerk_id']);
				$da['clerk_cn'] = $operator['clerk_cn'];
				$da['store_cn'] = $operator['store_cn'];
				if (empty($da['clerk_type'])) {
					$da['clerk_cn'] = '本人会员卡付款';
				}
			}
			unset($da);
			$users = pdo_getall('mc_members', array('uniacid' => intval($_W['uniacid']), 'uid' => $uids), array('mobile', 'uid', 'realname'), 'uid');
		}
	
		$html = "\xEF\xBB\xBF";
	
		$filter = array (
			'uid' => '会员编号',
			'realname' => '姓名',
			'mobile' => '手机',
			'fee' => '消费金额',
			'final_fee' => '实收金额',
			'credit2' => '余额支付',
			'credit1_fee' => '积分抵消',
			'final_cash' => '实收现金',
			'store_cn' => '消费门店',
			'clerk_cn' => '操作人',
			'createtime' => '操作时间'
		);
		foreach ($filter as $title) {
			$html .= $title . "\t,";
		}
		$html .= "\n";
		foreach ($exports as $k => $v) {
			foreach ($filter as $key => $title) {
				if ($key == 'realname') {
					$html .= $user[$v['uid']]['realname'] . "\t, ";
				} elseif ($key == 'mobile') {
					$html .= $user[$v['uid']]['mobile'] . "\t, ";
				} elseif ($key == 'createtime') {
					$html .= date ('Y-m-d H:i', $v['createtime']) . "\t, ";
				} else {
					$html .= $v[$key] . "\t, ";
				}
			}
			$html .= "\n";
		}
	
		header("Content-type:text/csv");
		header("Content-Disposition:attachment; filename=全部数据.csv");
		echo $html;
		exit();
	}
}

if ($op == 'chart') {
	$today_consume = floatval(pdo_fetchcolumn('SELECT SUM(final_cash) FROM ' . tablename('mc_cash_record') . ' WHERE uniacid = :uniacid AND createtime >= :starttime AND createtime <= :endtime', array(':uniacid' => $_W['uniacid'], ':starttime' => strtotime(date('Y-m-d')), ':endtime' => TIMESTAMP)));
	$total_consume = floatval(pdo_fetchcolumn('SELECT SUM(final_cash) FROM ' . tablename('mc_cash_record') . ' WHERE uniacid = :uniacid AND createtime >= :starttime AND createtime <= :endtime', array(':uniacid' => $_W['uniacid'], ':starttime' => $starttime, ':endtime' => $endtime)));
	if ($_W['isajax'] && $_W['ispost']) {
		$stat = array();
		for ($i = 0; $i < $num; $i++) {
			$time = $i * 86400 + $starttime;
			$key = date('m-d', $time);
			$stat['consume'][$key] = 0;
			$stat['recharge'][$key] = 0;
		}
		$data = pdo_fetchall('SELECT * FROM ' . tablename('mc_cash_record') . ' WHERE uniacid = :uniacid AND createtime >= :starttime AND createtime <= :endtime', array(':uniacid' => $_W['uniacid'], ':starttime' => $starttime, ':endtime' => $endtime));
		if (!empty($data)) {
			foreach ($data as $da) {
				$key = date('m-d', $da['createtime']);
				$stat['consume'][$key] += abs($da['final_cash']);
			}
		}
	
		$out['label'] = array_keys($stat['consume']);
		$out['datasets'] = array('recharge' => array_values($stat['recharge']), 'consume' => array_values($stat['consume']));
		exit(json_encode($out));
	}
}
include $this->template('statcash');