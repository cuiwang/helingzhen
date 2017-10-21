<?php
/**
 * 我的团队
 */

$uid = intval($_GPC['uid']);
$pindex =max(1,$_GPC['page']);
$psize = 10;
$member = pdo_fetch("SELECT a.uid,a.openid,a.nopay_commission+a.pay_commission AS commission,a.addtime, b.nickname,b.realname,b.mobile,b.avatar FROM " .tablename($this->table_member). " a LEFT JOIN " .tablename('mc_members'). " b ON a.uid=b.uid WHERE a.uniacid='{$uniacid}' AND a.uid='{$uid}'");

/* 一级会员人数 */
$teamlist = pdo_fetchall("SELECT a.*, b.nickname,b.realname,b.mobile,b.avatar FROM " .tablename($this->table_member). " a LEFT JOIN " .tablename('mc_members'). " b ON a.uid=b.uid WHERE a.uniacid='{$uniacid}' AND a.parentid='{$uid}'");
$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_member). " a LEFT JOIN " .tablename('mc_members'). " b ON a.uid=b.uid WHERE a.uniacid='{$uniacid}' AND a.parentid='{$uid}'");


foreach($teamlist as $k1=>$v1){
	/* 二级会员人数 */
	$direct2_num = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_member). " WHERE uniacid='{$uniacid}' AND parentid='{$v1['uid']}' ");
	
	$teamlist[$k1]['recnum']  = $direct2_num;
}


include $this->template('team');

?>