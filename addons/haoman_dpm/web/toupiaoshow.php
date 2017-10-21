<?php
global $_W  ,$_GPC;
// checklogin();
$rid = intval($_GPC['rid']);
load()->model('reply');

$vote = !empty($_GPC['vote']) ? $_GPC['vote'] : '1';


$vote_id = !empty($_GPC['vote_id']) ? $_GPC['vote_id'] : '0';
$t = time();
$pindex = max(1, intval($_GPC['page']));
$psize = 20;
$sql = 'select * from ' . tablename('haoman_dpm_toupiao') . 'where uniacid = :uniacid and rid = :rid and vote_id = :vote_id order by `pid` desc ,`get_num`  desc LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
$prarm = array(':uniacid' => $_W['uniacid'] ,':rid' => $rid,':vote_id'=>$vote_id);
$list = pdo_fetchall($sql, $prarm);
 $count = pdo_fetchcolumn('select count(*) from ' . tablename('haoman_dpm_toupiao') . 'where uniacid = :uniacid and rid = :rid and vote_id = :vote_id', $prarm);
$pager = pagination($count, $pindex, $psize);

foreach ($list as $k => $v) {
    $keywords = reply_single($v['rid']);
    $list[$k]['rulename'] = $keywords['name'];
    $list[$k]['vote'] = pdo_fetchcolumn('select vote_name from ' . tablename('haoman_dpm_newvote') . 'where uniacid = :uniacid and rid = :rid and id =:vote_id', $prarm);

}

load()->func('tpl');
include $this->template('toupiaoshow');