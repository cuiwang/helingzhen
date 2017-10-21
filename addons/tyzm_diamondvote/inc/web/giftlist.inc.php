<?php
/**
 * 钻石投票模块-投票数据
 *
 * 易/福/源/码/网 www.efwww.com
 * 易/福/源/码/网 www.efwww.com
 */

defined('IN_IA') or exit('Access Denied');
global $_GPC, $_W;
$rid=intval($_GPC['rid']);
$id=intval($_GPC['id']);
$uniacid=$_W['uniacid'];
$this->authorization();
$reply = pdo_fetch("SELECT config FROM " . tablename($this->tablereply) . " WHERE uniacid=:uniacid AND rid = :rid ORDER BY `id` DESC", array(':uniacid' => $uniacid,':rid' => $rid));
$reply=unserialize($reply['config']);
$voteuser = pdo_fetch("SELECT * FROM " . tablename($this->tablevoteuser) . " WHERE  id = :id AND uniacid = :uniacid AND rid = :rid", array(':id' => $id,':uniacid' => $uniacid,':rid' => $rid));
$pindex = max(1, intval($_GPC['page']));
$psize = 20;
$condition="";
if (!empty($_GPC['keyword'])) {
	$condition .= " AND CONCAT(`nickname`,`openid`,`user_ip`,`uniontid`) LIKE '%{$_GPC['keyword']}%'";
}

if (!empty($id)) {
	$condition .= " AND tid = '{$id} ' ";
}

$condition .=" ORDER BY id DESC ";

if($_GPC['id']==""){
	$ztotal=pdo_fetchcolumn("SELECT sum(fee) FROM ".tablename($this->tablegift)." WHERE rid = :rid  AND ispay=:ispay", array(':rid' => $rid,':ispay' =>1));
}

$list = pdo_fetchall("SELECT * FROM ".tablename($this->tablegift)." WHERE uniacid = '{$uniacid} ' AND rid = '{$rid} ' $condition   LIMIT ".($pindex - 1) * $psize.",{$psize}");

if (!empty($list)) {
	 $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->tablegift) . " WHERE uniacid = '{$uniacid}' AND rid = '{$rid} '  $condition");
	 $pager = pagination($total, $pindex, $psize); 
 }
		 
include $this->template('giftlist');

