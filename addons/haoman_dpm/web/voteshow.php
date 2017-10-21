<?php
global $_W  ,$_GPC;
// checklogin();
$rid = intval($_GPC['rid']);
load()->model('reply');


$t = time();
$pindex = max(1, intval($_GPC['page']));
$psize = 20;
$sql = 'select * from ' . tablename('haoman_dpm_newvote') . 'where uniacid = :uniacid and rid = :rid order by `vote_pid` desc LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
$prarm = array(':uniacid' => $_W['uniacid'] ,':rid' => $rid);
$list = pdo_fetchall($sql, $prarm);
 $count = pdo_fetchcolumn('select count(*) from ' . tablename('haoman_dpm_newvote') . 'where uniacid = :uniacid and rid = :rid', $prarm);
$pager = pagination($count, $pindex, $psize);

foreach ($list as $k => $v) {
    $list[$k]['vote_number'] = pdo_fetchcolumn("select count(*) from " . tablename('haoman_dpm_toupiao') . " where rid = '" . $rid . "' and uniacid = '" . $_W['uniacid'] . "'  and vote_id='".$v['id']."'");

    $keywords = reply_single($v['rid']);
    $list[$k]['rulename'] = $keywords['name'];

}

load()->func('tpl');
include $this->template('voteshow');