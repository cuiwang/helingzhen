<?php
global $_W  ,$_GPC;
// checklogin();
$rid = intval($_GPC['rid']);
load()->model('reply');
$turntable = intval($_GPC['turntable']);
if($turntable==2){
    $_GPC['do']='礼物';
}else{
    $_GPC['do']='项目';
}

$t = time();
$pindex = max(1, intval($_GPC['page']));
$psize = 20;
$sql = 'select * from ' . tablename('haoman_dpm_guest') . 'where uniacid = :uniacid and rid = :rid and turntable=:turntable order by `id` desc LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
$prarm = array(':uniacid' => $_W['uniacid'] ,':rid' => $rid,':turntable'=>$turntable);
$list = pdo_fetchall($sql, $prarm);
$count = pdo_fetchcolumn('select count(*) from ' . tablename('haoman_dpm_guest') . 'where uniacid = :uniacid and rid = :rid', $prarm);
$pager = pagination($count, $pindex, $psize);

foreach ($list as $k => $v) {
    $keywords = reply_single($v['rid']);
    $list[$k]['rulename'] = $keywords['name'];
}

load()->func('tpl');
include $this->template('dsshow');