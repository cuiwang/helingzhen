<?php
global $_GPC, $_W;
// checklogin();
$uniacid = $_W['uniacid'];
load()->model('reply');
load()->func('tpl');
$_GPC['do'] = 'xyhlist';
$rid = $_GPC['rid'];
$where = '';
$starttime = mktime(0,0,0,date('m'),1,date('Y'));
$endtime = time();

$params = array(':rid' => $rid, ':uniacid' => $_W['uniacid']);
if (!empty($_GPC['status'])) {
    $where .= ' and status=:status';
    $params[':status'] = $_GPC['status'];
}

if(!empty($_GPC['nickname'])){
    $where .= ' and nickname=:nickname';
    $params[':nickname'] = $_GPC['nickname'];
}
if (!empty($_GPC['time'])) {
    $starttime = strtotime($_GPC['time']['start']);
    $endtime   = strtotime($_GPC['time']['end']);
    $where .= " AND createtime >= :starttime AND createtime <= :endtime";
    $params[':starttime'] = $starttime;
    $params[':endtime']   = $endtime;
}

$total = pdo_fetchcolumn("select count(id) from " . tablename('haoman_dpm_xyhm') . "  where rid = :rid and uniacid=:uniacid " . $where . "", $params);
$pindex = max(1, intval($_GPC['page']));
$psize = 20;
$pager = pagination($total, $pindex, $psize);
$start = ($pindex - 1) * $psize;
$limit .= " LIMIT {$start},{$psize}";
$list = pdo_fetchall("select * from " . tablename('haoman_dpm_xyhm') . " where rid = :rid and uniacid=:uniacid" . $where . " order by id desc " . $limit, $params);
foreach ($list as $k => $v) {
    $keywords = reply_single($v['rid']);
    $list[$k]['rulename'] = $keywords['name'];
}

include $this->template('xyhlist');