<?php
global $_GPC, $_W;
$uniacid = $_W['uniacid'];
$rid = $_GPC['rid'];
$turntable = $_GPC['turntable'];
load()->model('reply');
$_GPC['do']='draw_default';

$params = array(':rid' => $rid, ':uniacid' => $_W['uniacid'],':turntable'=>$turntable);
$total = pdo_fetchcolumn("select count(id) from " . tablename('haoman_dpm_draw_default') . "  where rid = :rid and uniacid=:uniacid and turntable=:turntable", $params);
$pindex = max(1, intval($_GPC['page']));
$psize = 12;
$pager = pagination($total, $pindex, $psize);
$start = ($pindex - 1) * $psize;
$limit .= " LIMIT {$start},{$psize}";
$list = pdo_fetchall("select * from " . tablename('haoman_dpm_draw_default') . " where rid = :rid and uniacid=:uniacid and turntable=:turntable order by id desc " . $limit, $params);
foreach ($list as &$k){
    $k['prizename'] = pdo_fetchcolumn("select prizename from " . tablename('haoman_dpm_prize') . "  where rid = :rid and uniacid=:uniacid and turntable=:turntable and id=:id", array(':id'=>$k['prizeid'],':rid' => $rid, ':uniacid' => $_W['uniacid'],':turntable'=>$turntable));

}
unset($k);
include $this->template('draw_default');