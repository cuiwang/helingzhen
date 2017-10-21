<?php
global $_GPC, $_W;
$rid = $_GPC['rid'];

$params = array(':rid' => $rid, ':uniacid' => $_W['uniacid']);
if (!empty($_GPC['nickname'])) {
	$where.=' and `nickname` LIKE :nickname';
	$params[':nickname'] = "%{$_GPC['nickname']}%";
}
if (!empty($_GPC['from_user'])) {
	$where.=' and from_user = :from_user';
	$params[':from_user'] = $_GPC['from_user'];
}
if (!empty($_GPC['mobile'])) {
	$where.=' and mobile=:mobile';
	$params[':mobile'] = $_GPC['mobile'];
}
if ($_GPC['zhongjiang']==1||$_GPC['zhongjiang']==2) {
    $where.=' and zhongjiang=:zhongjiang';
    if($_GPC['zhongjiang']==1){
        $_GPC['zhongjiang']==0;
    }
    if($_GPC['zhongjiang']==2){
        $_GPC['zhongjiang']==1;
    }
    $params[':zhongjiang'] = $_GPC['zhongjiang'];
}
if ($_GPC['sex']==1||$_GPC['sex']==2) {
    $where.=' and sex=:sex';
    if($_GPC['sex']==1){
        $_GPC['sex']==0;
    }
    if($_GPC['sex']==2){
        $_GPC['sex']==1;
    }
    $params[':sex'] = $_GPC['sex'];
}

$reply = pdo_fetch("select isbaoming from " . tablename('haoman_dpm_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
$fanshb = pdo_fetch("select is_setadmin from " . tablename('haoman_dpm_hb_setting') . " where rid = :rid order by `id` asc", array(':rid' => $rid));

$total = pdo_fetchcolumn("select count(id) from " . tablename('haoman_dpm_fans') . "  where rid = :rid and uniacid=:uniacid " . $where . "", $params);
$pindex = max(1, intval($_GPC['page']));
$psize = 12;
$pager = pagination($total, $pindex, $psize);
$start = ($pindex - 1) * $psize;
$limit .= " LIMIT {$start},{$psize}";
$list = pdo_fetchall("select * from " . tablename('haoman_dpm_fans') . " where rid = :rid and uniacid=:uniacid " . $where . " order by id desc " . $limit, $params);
//中奖情况
foreach ($list as &$lists) {

	$lists['awardinfo'] = pdo_fetchcolumn("select count(id) from " . tablename('haoman_dpm_award') . "  where rid = :rid and from_user=:from_user", array(':rid' => $rid, ':from_user' => $lists['from_user']));
	$lists['awardinfo'] = pdo_fetchcolumn("select count(id) from " . tablename('haoman_dpm_award') . "  where rid = :rid and from_user=:from_user", array(':rid' => $rid, ':from_user' => $lists['from_user']));
	$lists['is_admin'] = pdo_fetchcolumn("select count(id) from " . tablename('haoman_dpm_bpadmin') . "  where rid = :rid and admin_openid=:admin_openid", array(':rid' => $rid, ':admin_openid' => $lists['from_user']));
}
//中奖情况
//一些参数的显示
$num1 = pdo_fetchcolumn("select count(id) from " . tablename('haoman_dpm_fans') . "  where rid = :rid and uniacid=:uniacid", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
$num2 = pdo_fetchcolumn("select count(id) from " . tablename('haoman_dpm_fans') . "  where rid = :rid and uniacid=:uniacid and zhongjiang>0", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
$num3 = pdo_fetchcolumn("select count(id) from " . tablename('haoman_dpm_fans') . "  where rid = :rid and uniacid=:uniacid and zhongjiang=0", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
//    $num4 = pdo_fetchcolumn("select count(id) from " . tablename('haoman_dpm_fans') . "  where rid = :rid and uniacid=:uniacid and zhongjiang=2", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
//一些参数的显示



include $this->template('fanslist');