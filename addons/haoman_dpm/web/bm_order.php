<?php
global $_GPC, $_W;
// checklogin();
$uniacid = $_W['uniacid'];
load()->model('reply');
load()->func('tpl');
$_GPC['do'] = 'payorderlist';
$rid = $_GPC['rid'];


$params = array(':rid' => $rid, ':uniacid' => $_W['uniacid']);

$total = pdo_fetchcolumn("select count(id) from " . tablename('haoman_dpm_pay_order') . "  where rid = :rid and uniacid=:uniacid and status!=3 and pay_type=0" . $where . "", $params);
$pindex = max(1, intval($_GPC['page']));
$psize = 30;
$pager = pagination($total, $pindex, $psize);
$start = ($pindex - 1) * $psize;
$limit .= " LIMIT {$start},{$psize}";
$list = pdo_fetchall("select * from " . tablename('haoman_dpm_pay_order') . " where rid = :rid and uniacid=:uniacid and status!=3 and pay_type=0" . $where . " order by id desc " . $limit, $params);

include $this->template('payorderlist');