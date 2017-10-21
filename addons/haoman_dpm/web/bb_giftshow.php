<?php
global $_W  ,$_GPC;
// checklogin();
$rid = intval($_GPC['rid']);
load()->model('reply');
$bb_gift = intval($_GPC['bb_gift']);
if($bb_gift==1){
    $_GPC['do']='送礼礼物';
}elseif($bb_gift==2){
    $_GPC['do']='表白礼物';
}else{
    $_GPC['do']='表白霸屏';
}

$t = time();
$pindex = max(1, intval($_GPC['page']));
$psize = 20;
$sql = 'select * from ' . tablename('haoman_dpm_bbgift') . 'where uniacid = :uniacid and rid = :rid and type=:type order by `id` desc LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
$prarm = array(':uniacid' => $_W['uniacid'] ,':rid' => $rid,':type'=>$bb_gift);
$list = pdo_fetchall($sql, $prarm);

$count = pdo_fetchcolumn('select count(*) from ' . tablename('haoman_dpm_bbgift') . 'where uniacid = :uniacid and rid = :rid', $prarm);
$pager = pagination($count, $pindex, $psize);

foreach ($list as $k => $v) {
    $keywords = reply_single($v['rid']);
    $list[$k]['rulename'] = $keywords['name'];
}

load()->func('tpl');
include $this->template('bb_giftshow');