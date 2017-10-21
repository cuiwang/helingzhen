<?php
global $_GPC, $_W;
$rid = $_GPC['rid'];



$params = array(':rid' => $rid, ':uniacid' => $_W['uniacid']);
if (!empty($_GPC['nickname'])) {
	$where.=' and `nickname` LIKE :nickname';
	$params[':nickname'] = "%{$_GPC['nickname']}%";
}
if (!empty($_GPC['mobile'])) {
	$where.=' and mobile=:mobile';
	$params[':mobile'] = $_GPC['mobile'];
}

if ($_GPC['status']!='') {
	$where.=' and status=:status';
	$params[':status'] = $_GPC['status'];
}



$total = pdo_fetchcolumn("select count(id) from " . tablename('haoman_dpm_cash') . "  where rid = :rid and uniacid=:uniacid " . $where . "", $params);
$pindex = max(1, intval($_GPC['page']));
$psize = 12;
    $pager = pagination($total, $pindex, $psize);
$start = ($pindex - 1) * $psize;
$limit .= " LIMIT {$start},{$psize}";
$list = pdo_fetchall("select * from " . tablename('haoman_dpm_cash') . " where rid = :rid and uniacid=:uniacid " . $where . " order by id desc " . $limit, $params);

//中奖情况
foreach ($list as &$lists) {
    $lists['money'] = $lists['awardname'] - $lists['credit'] ;

	$lists['realname'] = pdo_fetchcolumn("select realname from " . tablename('haoman_dpm_fans') . "  where rid = :rid and from_user=:from_user", array(':rid' => $rid, ':from_user' => $lists['from_user']));
}



//中奖情况
//一些参数的显示
$num1 = pdo_fetchcolumn("select count(id) from " . tablename('haoman_dpm_fans') . "  where rid = :rid and uniacid=:uniacid", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
$num2 = pdo_fetchcolumn("select count(id) from " . tablename('haoman_dpm_fans') . "  where rid = :rid and uniacid=:uniacid and zhongjiang>0", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
$num3 = pdo_fetchcolumn("select count(id) from " . tablename('haoman_dpm_fans') . "  where rid = :rid and uniacid=:uniacid and zhongjiang=0", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));



//    $num4 = pdo_fetchcolumn("select count(id) from " . tablename('haoman_dpm_fans') . "  where rid = :rid and uniacid=:uniacid and zhongjiang=2", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
//一些参数的显示
include $this->template('cashprize');