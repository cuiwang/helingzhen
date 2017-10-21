<?php
/**
 * 钻石投票模块-投票数据
 *

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
	$condition .= " AND tid = '{$id} ' AND CONCAT(`nickname`,`openid`,`user_ip`) LIKE '%{$_GPC['keyword']}%'";
}
if($_GPC['ty']=="votenum"){
	$condition .= " AND votetype=0  AND tid = '{$id} '";
}elseif($_GPC['ty']=="diamondnum"){
	$condition .= " AND votetype=1 AND ispay=1  AND tid = '{$id} '";
}elseif($_GPC['ty']=="order" && $_GPC['ispay']==""){
	$condition .= " AND votetype>0 ";
}elseif($_GPC['ty']=="order" && $_GPC['ispay']=="0"){
	$condition .= " AND votetype>0 AND ispay=0";
}elseif($_GPC['ty']=="order" && $_GPC['ispay']=="1"){
	$condition .= " AND votetype>0  AND ispay=1";
}
$condition .=" ORDER BY id DESC ";

if($_GPC['ty']=="order"){
	$ztotal=pdo_fetchcolumn("SELECT sum(fee) FROM ".tablename($this->tablevotedata)." WHERE rid = :rid AND votetype=:votetype AND ispay=:ispay", array(':rid' => $rid,':votetype' =>1,':ispay' =>1));
}

$list = pdo_fetchall("SELECT * FROM ".tablename($this->tablevotedata)." WHERE uniacid = '{$uniacid} ' AND rid = '{$rid} ' $condition   LIMIT ".($pindex - 1) * $psize.",{$psize}");
if (!empty($list)) {
	 $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->tablevotedata) . " WHERE uniacid = '{$uniacid}' AND rid = '{$rid} '  $condition");
	 $pager = pagination($total, $pindex, $psize); 
 }
		 
include $this->template('votedata');

