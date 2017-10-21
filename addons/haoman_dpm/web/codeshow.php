<?php
global $_W  ,$_GPC;
// checklogin();
$rid = intval($_GPC['rid']);
$turntable = $_GPC['turntable'];
load()->model('reply');

if(empty($turntable)){
	message('请从左侧菜单选择具体的活动类型', '', 'error');
}

if($turntable == 1){
	$awardtitle = '现场抽奖';
}
if($turntable == 2){
	$awardtitle = '抢红包';
}

$t = time();
$pindex = max(1, intval($_GPC['page']));
$psize = 20;
$sql = 'select * from ' . tablename('haoman_dpm_prize') . 'where uniacid = :uniacid and rid = :rid and turntable = :turntable order by sort desc LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
$prarm = array(':uniacid' => $_W['uniacid'] ,':rid' => $rid,':turntable' => $turntable);
$list = pdo_fetchall($sql, $prarm);
// $count = pdo_fetchcolumn('select count(*) from ' . tablename('haoman_dpm_pw') . 'where uniacid = :uniacid and pici = :pici', $prarm);
$pager = pagination($count, $pindex, $psize);

foreach ($list as $k => $v) {
	$keywords = reply_single($v['rid']);
	$list[$k]['rulename'] = $keywords['name'];
}

load()->func('tpl');
include $this->template('codeshow');