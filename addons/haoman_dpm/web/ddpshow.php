<?php
global $_W  ,$_GPC;
// checklogin();
$rid = intval($_GPC['rid']);
load()->model('reply');


$t = time();
$pindex = max(1, intval($_GPC['page']));
$psize = 20;
$sql = 'select * from ' . tablename('haoman_dpm_pair_combination') . 'where uniacid = :uniacid and rid = :rid order by `id` desc LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
$prarm = array(':uniacid' => $_W['uniacid'] ,':rid' => $rid);
$list = pdo_fetchall($sql, $prarm);
$count = pdo_fetchcolumn('select count(*) from ' . tablename('haoman_dpm_pair_combination') . 'where uniacid = :uniacid and rid = :rid', $prarm);
$pager = pagination($count, $pindex, $psize);


load()->func('tpl');
include $this->template('ddpshow');