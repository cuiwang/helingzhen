<?php
global $_W  ,$_GPC;
// checklogin();
$rid = intval($_GPC['rid']);
$pici = intval($_GPC['pici']);
load()->model('reply');
if(empty($pici)){
    message('您选择的场次不存在，请确认！');
}

$t = time();
$pindex = max(1, intval($_GPC['page']));
$psize = 20;
$sql = 'select * from ' . tablename('haoman_dpm_yyyuser') . 'where uniacid = :uniacid and rid = :rid and pici =:pici order by `point` desc,endtime ASC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
$prarm = array(':uniacid' => $_W['uniacid'] ,':rid' => $rid,':pici'=>$pici);
$list = pdo_fetchall($sql, $prarm);
$count = pdo_fetchcolumn('select count(*) from ' . tablename('haoman_dpm_yyyuser') . 'where uniacid = :uniacid and rid = :rid and pici =:pici', $prarm);
$pager = pagination($count, $pindex, $psize);

load()->func('tpl');
include $this->template('yyydetails');