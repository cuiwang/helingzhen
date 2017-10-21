<?php
global $_W  ,$_GPC;
// checklogin();
$rid = intval($_GPC['rid']);
$pici = $_GPC['pici'];
load()->model('reply');



$t = time();
$pindex = max(1, intval($_GPC['page']));
$psize = 20;
$sql = 'select * from ' . tablename('haoman_dpm_xyhm') . 'where uniacid = :uniacid and rid = :rid and pici = :pici order by createtime desc';
$prarm = array(':uniacid' => $_W['uniacid'] ,':rid' => $rid,':pici' => $pici);
$list = pdo_fetch($sql, $prarm);

$newlist = explode(',',ltrim(rtrim($list['number'], ","), ","));
foreach($newlist as $k=>$v){
	$newlists[$k]['number']=$v;
}

// $count = pdo_fetchcolumn('select count(*) from ' . tablename('haoman_dpm_xyhm') . 'where uniacid = :uniacid and pici = :pici', $prarm);
$pager = pagination($count, $pindex, $psize);




//print_r($newlists);
load()->func('tpl');
include $this->template('codeshow2');