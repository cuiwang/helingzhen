<?php

defined('IN_IA') or die('Access Denied');
global $_GPC, $_W;
$rid = intval($_GPC['rid']);
$id = intval($_GPC['id']);
$uniacid = $_W['uniacid'];
$this->authorization();
$vipfee = pdo_fetchcolumn("SELECT sum(fee) FROM " . tablename($this->tableviporder) . " WHERE uniacid = :uniacid AND ispay=1 ", array(':uniacid' => $uniacid));
$pindex = max(1, intval($_GPC['page']));
$psize = 20;
$condition = "";
if (!empty($_GPC['keyword'])) {
	$condition .= " AND tid = '{$id} ' AND CONCAT(`nickname`,`oauth_openid`,`ptid`,`uniontid`) LIKE '%{$_GPC['keyword']}%'";
}
$condition .= " ORDER BY id DESC ";
$list = pdo_fetchall("SELECT * FROM " . tablename($this->tableviporder) . " WHERE uniacid = '{$uniacid} '  {$condition}   LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
if (!empty($list)) {
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->tableviporder) . " WHERE uniacid = '{$uniacid}'  {$condition}");
	$pager = pagination($total, $pindex, $psize);
}
include $this->template('viporder');