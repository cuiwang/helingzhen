<?php

defined('IN_IA') or exit('Access Denied');

global $_W, $_GPC;
load()->model('mc');
mload()->model('activity');

$ops = array('chart', 'display');
$op = in_array(trim($_GPC['op']), $ops) ? trim($_GPC['op']) : 'display';

activity_get_coupon_type();

$starttime = empty($_GPC['time']['start']) ? mktime(0, 0, 0, date('m'), 1, date('Y')) : strtotime($_GPC['time']['start']);
$endtime = empty($_GPC['time']['end']) ? TIMESTAMP : strtotime($_GPC['time']['end']) + 86399;
$num = ($endtime + 1 - $starttime) / 86400;

if ($op == 'display') {
	$stores = pdo_getall('storex_activity_stores', array('uniacid' => $_W['uniacid'], 'source' => COUPON_TYPE), array('id', 'business_name', 'branch_name'), 'id');
	$condition = ' WHERE uniacid = :uniacid AND credittype = :credittype AND createtime >= :starttime AND createtime <= :endtime';
	$params = array(':uniacid' => $_W['uniacid'], ':credittype' => 'credit1', ':starttime' => $starttime, ':endtime' => $endtime);
	$num = intval($_GPC['num']);
	if ($num > 0) {
		if ($num == 1) {
			$condition .= ' AND num >= 0';
		} else {
			$condition .= ' AND num <= 0';
		}
	}
	$min = intval($_GPC['min']);
	if ($min > 0 ) {
		$condition .= ' AND abs(num) >= :minnum';
		$params[':minnum'] = $min;
	}
	
	$max = intval($_GPC['max']);
	if ($max > 0 ) {
		$condition .= ' AND abs(num) <= :maxnum';
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
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_credits_record') . $condition, $params);
	$data = pdo_fetchall('SELECT * FROM ' . tablename('mc_credits_record') . $condition . $limit, $params);
	if (!empty($data)) {
		$uids = array();
		foreach ($data as &$da) {
			if (!in_array($da['uid'], $uids)) {
				$uids[] = $da['uid'];
			}
			$operator = storex_account_change_operator($da['clerk_type'], $da['store_id'], $da['clerk_id']);
			$da['clerk_cn'] = $operator['clerk_cn'];
			$da['store_cn'] = $operator['store_cn'];
		}
		unset($da);
		$users = pdo_getall('mc_members', array('uniacid' => intval($_W['uniacid']), 'uid' => $uids), array('mobile', 'uid', 'realname'), 'uid');
	}
	$pager = pagination($total, $pindex, $psize);
	
	if ($_GPC['export'] != '') {
		$exports = pdo_fetchall("SELECT * FROM " . tablename('mc_credits_record') . $condition . " ORDER BY id DESC", $params);
		if (!empty($exports)) {
			$uids = array();
			foreach ($exports as &$da) {
				if (!in_array($da['uid'], $uids)) {
					$uids[] = $da['uid'];
				}
				$operator = storex_account_change_operator($da['clerk_type'], $da['store_id'], $da['clerk_id']);
				$da['clerk_cn'] = $operator['clerk_cn'];
				$da['store_cn'] = $operator['store_cn'];
			}
			unset($da);
			$users = pdo_getall('mc_members', array('uniacid' => intval($_W['uniacid']), 'uid' => $uids), array('mobile', 'uid', 'realname'), 'uid');
		}
		/* 输入到CSV文件 */
		$html = "\xEF\xBB\xBF";
	
		/* 输出表头 */
		$filter = array(
			'uid' => '会员编号',
			'name' => '姓名',
			'phone' => '手机',
			'type' => '类型',
			'num' => '数量',
			'store_cn' => '消费门店',
			'clerk_cn' => '操作人',
			'createtime' => '操作时间',
			'remark' => '备注'
		);
		foreach ($filter as $title) {
			$html .= $title . "\t,";
		}
		$html .= "\n";
		foreach ($exports as $k => $v) {
			foreach ($filter as $key => $title) {
				if ($key == 'name') {
					$html .= $users[$v['uid']]['realname'] . "\t, ";
				} elseif ($key == 'phone') {
					$html .= $users[$v['uid']]['mobile'] . "\t, ";
				} elseif ($key == 'type') {
					if ($v['num'] > 0) {
						$html .= "充值\t, ";
					} else {
						$html .= "消费\t, ";
					}
				} elseif ($key == 'num') {
					$html .= abs($v[$key]) . "\t, ";
				} elseif ($key == 'store') {
					if ($v['store_id'] > 0) {
						$html .= $stores[$v['store_id']]['business_name'] . '-' . $stores[$v['store_id']]['branch_name'] . "\t, ";
					} else {
						$html .= "未知\t, ";
					}
				} elseif ($key == 'operator') {
					if ($v['clerk_id'] > 0) {
						$html .= $clerks[$v['clerk_id']]['name'] . "\t, ";
					} elseif ($v['clerk_type'] == 1) {
						$html .= "系统\t, ";
					} else {
						$html .= "未知\t, ";
					}
				} elseif ($key == 'createtime') {
					$html .= date('Y-m-d H:i', $v['createtime']) . "\t, ";
				} elseif ($key == 'remark') {
					$html .= cutstr($v['remark'], '30', '...') . "\t, ";
				} else {
					$html .= $v[$key] . "\t, ";
				}
			}
			$html .= "\n";
		}
		/* 输出CSV文件 */
		header("Content-type:text/csv");
		header("Content-Disposition:attachment; filename=全部数据.csv");
		echo $html;
		exit();
	}
}

if ($op == 'chart') {
	$today_recharge = floatval(pdo_fetchcolumn('SELECT SUM(num) FROM ' . tablename('mc_credits_record') . ' WHERE uniacid = :uniacid AND credittype = :credittype AND num > 0 AND createtime >= :starttime AND createtime <= :endtime', array(':uniacid' => $_W['uniacid'], ':credittype' => 'credit1', ':starttime' => strtotime(date('Y-m-d')), ':endtime' => TIMESTAMP)));
	$today_consume = floatval(pdo_fetchcolumn('SELECT SUM(num) FROM ' . tablename('mc_credits_record') . ' WHERE uniacid = :uniacid AND credittype = :credittype AND num < 0 AND createtime >= :starttime AND createtime <= :endtime', array(':uniacid' => $_W['uniacid'], ':credittype' => 'credit1', ':starttime' => strtotime(date('Y-m-d')), ':endtime' => TIMESTAMP)));
	$total_recharge = floatval(pdo_fetchcolumn('SELECT SUM(num) FROM ' . tablename('mc_credits_record') . ' WHERE uniacid = :uniacid AND credittype = :credittype AND num > 0 AND createtime >= :starttime AND createtime <= :endtime', array(':uniacid' => $_W['uniacid'], ':credittype' => 'credit1', ':starttime' => $starttime, ':endtime' => $endtime)));
	$total_consume = floatval(pdo_fetchcolumn('SELECT SUM(num) FROM ' . tablename('mc_credits_record') . ' WHERE uniacid = :uniacid AND credittype = :credittype AND num < 0 AND createtime >= :starttime AND createtime <= :endtime', array(':uniacid' => $_W['uniacid'], ':credittype' => 'credit1', ':starttime' => $starttime, ':endtime' => $endtime)));
	if ($_W['isajax'] && $_W['ispost']) {
		$stat = array();
		for ($i = 0; $i < $num; $i++) {
			$time = $i * 86400 + $starttime;
			$key = date('m-d', $time);
			$stat['consume'][$key] = 0;
			$stat['recharge'][$key] = 0;
		}
		$data = pdo_fetchall("SELECT id, num, credittype, createtime, uniacid FROM " . tablename('mc_credits_record') . " WHERE uniacid = :uniacid AND credittype = :credittype AND createtime >= :starttime AND createtime <= :endtime", array(':uniacid' => $_W['uniacid'], ':credittype' => 'credit1', ':starttime' => $starttime, ':endtime' => $endtime));
	
		if (!empty($data)) {
			foreach ($data as $da) {
				$key = date('m-d', $da['createtime']);
				if ($da['num'] > 0) {
					$stat['recharge'][$key] += $da['num'];
				} else {
					$stat['consume'][$key] += abs($da['num']);
				}
			}
		}
	
		$out['label'] = array_keys($stat['consume']);
		$out['datasets'] = array('recharge' => array_values($stat['recharge']), 'consume' => array_values($stat['consume']));
		exit(json_encode($out));
	}
}
include $this->template('statcredit1');