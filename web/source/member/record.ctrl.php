<?php
/**
 * [Weizan System] Copyright (c) 2014 WEIZANCMS.COM
 
 */
defined('IN_IA') or exit('Access Denied');
include_once 'common/common.inc.php';
global $_W,$_GPC;
checklogin();
$uid=$_W['uid'];
$_W['page']['title'] = "消费记录";
$ops = array("record","cz");
$op = in_array($_GPC["do"], $ops) ? $_GPC["do"] : "record";
if("record" == $op) {
//    $index = max(1, intval($_GPC['page']));
//    $size = 15;
//    $page = ($index - 1) * $size;
    if($_W['isfounder']){
		$list = pdo_fetchall("SELECT * FROM ".tablename("users_credits_record")." ORDER BY createtime DESC ");
	}
	else{
	    $list = pdo_fetchall("SELECT * FROM ".tablename("users_credits_record")." WHERE uid=:uid ORDER BY createtime DESC ",array(":uid"=>$_W["uid"]));
	}
	$uni = pdo_fetchall("SELECT name,uniacid FROM ".tablename("account_wechats"),array(),'uniacid');
	$user =pdo_fetchall("SELECT username,uid FROM ".tablename("users"),array(),'uid');
//    $total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename("users_credits_record")." WHERE uid=:uid", array(":uid"=>$uid));
//    $pager = pagination($total, $index, $size);
}
elseif("cz" == $op) {
//    $index = max(1, intval($_GPC['page']));
 //   $size = 15;
//    $page = ($index - 1) * $size;
    if($_W['isfounder']){
		$list = pdo_fetchall("SELECT * FROM ".tablename("uni_payorder")." ORDER BY order_time DESC ");
	    
	}
	else{
		$list = pdo_fetchall("SELECT * FROM ".tablename("uni_payorder")." WHERE uid=:uid ORDER BY order_time DESC ",array(":uid"=>$_W["uid"]));
	}
//    $total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename("uni_payorder")." WHERE uid=:uid", array(":uid"=>$uid));
//    $pager = pagination($total, $index, $size);
}
template('member/record');