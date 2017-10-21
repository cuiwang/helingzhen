<?php
/**
 * 钻石投票-查看过的列表
 *
 * 易/福/源/码/网 www.efwww.com
 * 易/福/源/码/网 www.efwww.com
 */

defined('IN_IA') or exit('Access Denied');
global $_W,$_GPC;
$uniacid=$_W['uniacid'];
$userinfo=$this->oauthuser;


$pindex = max(1, intval($_GPC['page']));
$psize = 20;

$sql = 'SELECT  `u`.`id`,`u`.`rid`,`u`.`nickname`,`u`.`avatar`,`u`.`name` ,`u`.`introduction` FROM ' . tablename($this->tablelooklist) . " AS `l` LEFT JOIN " .tablename($this->tablevoteuser) . " AS `u` ON `l`.`tid` = `u`.`id` WHERE `l`.`uniacid` = :uniacid ORDER BY
			`l`.`createtime` DESC LIMIT ".($pindex - 1) * $psize.",{$psize}";
			
	$list = pdo_fetchall($sql, array(':uniacid' => $uniacid));
if (!empty($list)) {
	$sql2 = 'SELECT  COUNT(*) FROM ' . tablename($this->tablelooklist) . " WHERE `uniacid` = :uniacid ORDER BY
			`createtime` DESC";
	 $total = pdo_fetchcolumn($sql2, array(':uniacid' => $uniacid));
	 $pager = pagination($total, $pindex, $psize); 
 }

 
$_W['page']['sitename']="好友列表";
include $this->template('friendslist');