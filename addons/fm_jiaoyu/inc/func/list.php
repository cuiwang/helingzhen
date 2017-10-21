<?php
/**
 * By 高贵血迹
 */
global $_W, $_GPC;
$weid = $_W['uniacid'];
if($_W['isfounder'] || $_W['role'] == 'owner') {
	$where = "WHERE weid = '{$weid}'";
}else{
	$uid = $_W['user']['uid'];	
	$where = "WHERE weid = '{$weid}' And uid = '{$uid}' And is_show = 1 ";		
}

$where1 = "WHERE weid = '{$weid}' And schoolid = '{$id}'";

$schoollist = pdo_fetchall("SELECT id,title,logo FROM " . tablename($this->table_index) . " $where   order by ssort DESC");

$state = pdo_fetch("SELECT * FROM ".tablename('uni_account_users')." WHERE uid = :uid And uniacid = :uniacid", array(':uid' => $_W['uid'],':uniacid' => $_W['uniacid']));
$_W['role']  = $state['role'];

$myadmin = pdo_fetch("SELECT tid,schoolid FROM ".tablename('users')." WHERE uid = :uid And uniacid = :uniacid", array(':uid' => $_W['uid'],':uniacid' => $_W['uniacid']));

$schoolid = intval($_GPC['schoolid']);

$thisindex = pdo_fetch("SELECT uid FROM " . tablename($this->table_index) . " WHERE weid = '{$weid}' And id = '{$schoolid}' ");
if(!$_W['isfounder'] && $_W['role'] != 'owner') {
	if ($myadmin['schoolid'] != $schoolid){
		message('非法访问！', $_W['siteroot'].'addons/fm_jiaoyu/admin');
	}else{
		$mysf = pdo_fetch("SELECT tname,thumb,fz_id FROM ".tablename($this->table_teachers)." WHERE schoolid = :schoolid And id = :id", array(':schoolid' => $schoolid,':id' => $myadmin['tid']));
		$myfz = pdo_fetch("SELECT sname FROM ".tablename($this->table_classify)." WHERE schoolid = :schoolid And sid = :sid", array(':schoolid' => $schoolid,':sid' => $mysf['fz_id']));
	}
}

$fenzu = pdo_fetch("SELECT id FROM ".tablename($this->table_group)." WHERE schoolid = :schoolid And is_zhu = :is_zhu", array(':schoolid' => $schoolid,':is_zhu' => 1));
if($fenzu){
	$code = pdo_fetch("SELECT * FROM ".tablename($this->table_qrinfo)." WHERE gpid = :gpid ", array(':gpid' => $fenzu['id']));
}
?>