<?php

defined('IN_IA') or exit('Access Denied');

global $_W, $_GPC;

$ops = array('display');
$op = in_array(trim($_GPC['op']), $ops) ? trim($_GPC['op']) : 'display';

$starttime = empty($_GPC['time']['start']) ? mktime(0, 0, 0, date('m'), 1, date('Y')) : strtotime($_GPC['time']['start']);
$endtime = empty($_GPC['time']['end']) ? TIMESTAMP : strtotime($_GPC['time']['end']) + 86399;
$num = ($endtime + 1 - $starttime) / 86400;

if ($op == 'display') {
	if ($_W['isajax']) {
		$stat = array();
		for ($i = 0; $i < $num; $i++) {
			$time = $i * 86400 + $starttime;
			$key = date('m-d', $time);
			$stat[$key] = 0;
		}
		$data = pdo_fetchall("SELECT id, createtime FROM " . tablename('storex_mc_card_members') . " WHERE uniacid = :uniacid AND createtime >= :starttime AND createtime <= :endtime", array(':uniacid' => $_W['uniacid'], ':starttime' => $starttime, ':endtime' => $endtime));
		if (!empty($data)) {
			foreach ($data as $da) {
				$key = date('m-d', $da['createtime']);
				$stat[$key] += 1;
			}
		}
		$out['label'] = array_keys($stat);
		$out['datasets'] = array_values($stat);
		exit(json_encode($out));
	}
	$total = floatval(pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('storex_mc_card_members') . " WHERE uniacid = :uniacid", array(':uniacid' => $_W['uniacid'])));
	$today = floatval(pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('storex_mc_card_members') . " WHERE uniacid = :uniacid AND createtime >= :starttime AND createtime <= :endtime", array(':uniacid' => $_W['uniacid'], ':starttime' => strtotime(date('Y-m-d')), ':endtime' => TIMESTAMP)));
	$yesterday = floatval(pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('storex_mc_card_members') . " WHERE uniacid = :uniacid AND createtime >= :starttime AND createtime <= :endtime", array(':uniacid' => $_W['uniacid'], ':starttime' => strtotime(date('Y-m-d')) - 86400, ':endtime' => strtotime(date('Y-m-d')))));
}
include $this->template('statcard');