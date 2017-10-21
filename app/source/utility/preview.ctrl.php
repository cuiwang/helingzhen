<?php
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
defined('IN_IA') or exit('Access Denied');
$dos = array('home');
$do = in_array($do, $dos) ? $do : exit('Access Denied');

if ($do == 'home') {
	$multiid = intval($_GPC['multiid']);
	$multi = pdo_fetch("SELECT styleid FROM ".tablename('site_multi')." WHERE id = :id", array(':id' => $multiid));
	$sql = 'SELECT `s`.*, `t`.`name` AS `tname`, `t`.`title` FROM ' . tablename('site_styles') . ' AS `s`
			LEFT JOIN ' . tablename('site_templates') . ' AS `t` ON `s`.`templateid` = `t`.`id` WHERE `s`.`uniacid` = :uniacid AND s.id = :styleid';
	$style = pdo_fetch($sql, array(':uniacid' => $_W['uniacid'], ':styleid' => $multi['styleid']), 'id');
	template("../{$style['tname']}/home/home");
}
