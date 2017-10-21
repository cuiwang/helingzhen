<?php

global $_W,$_GPC;
$uniacid = $_W['uniacid'];
load()->func('tpl');
$rid = intval($_GPC['rid']);
if(empty($rid)){
	message('直播id不存在',$this->createWebUrl('list_list'),'error');
}
$zhibo_list = pdo_fetch("SELECT * FROM ".tablename('wxz_wzb_live_setting')." WHERE uniacid=:uniacid AND rid=:rid",array(':uniacid'=>$uniacid,':rid'=>$rid));
if(empty($zhibo_list)){
	message('此直播不存在',$this->createWebUrl('live_manage'),'error');
}

$sum = pdo_fetchcolumn('SELECT sum(a.fee) as fee FROM ' . tablename('wxz_wzb_ds') . ' as a inner JOIN ' . tablename('wxz_wzb_user') . ' AS b ON a.uid=b.id WHERE a.rid='.$rid.' and a.status=1');

$pindex = max(1, intval($_GPC['page']));
$psize = 10;
$total = pdo_fetchcolumn('SELECT count(*) as c FROM ' . tablename('wxz_wzb_ds') . ' as a inner JOIN ' . tablename('wxz_wzb_user') . ' AS b ON a.uid=b.id WHERE a.rid='.$rid.' and a.status=1');
$start = ($pindex - 1) * $psize;

$sql='SELECT b.nickname,b.id,b.headimgurl,a.fee as fee,a.dateline as dateline FROM ' . tablename('wxz_wzb_ds') . ' as a inner JOIN ' . tablename('wxz_wzb_user') . ' AS b ON a.uid=b.id WHERE a.rid='.$rid.' and a.status=1 ORDER BY a.dateline DESC limit '.$start.','.$psize;

$ds = pdo_fetchall($sql);
$pager = pagination($total, $pindex, $psize);

include $this->template('ds');