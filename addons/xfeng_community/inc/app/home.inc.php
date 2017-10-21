<?php
/**
 * 微小区模块
 *
 * [晓锋] Copyright (c) 2013 qfinfo.cn
 */
/**
 * 微信端首页
 */
defined('IN_IA') or exit('Access Denied');
	global $_GPC,$_W;		
	$title  = $_W['account']['name'];
	$member = $this->changemember();
	$region = pdo_fetch("SELECT title FROM".tablename('xcommunity_region')."WHERE id='{$member['regionid']}'");
	$title  = $region['title'];
	//菜单
	$list = pdo_fetchall("SELECT * FROM".tablename('xcommunity_nav')."WHERE  uniacid='{$_W['uniacid']}' AND pcate = 0 AND status = 1 order by displayorder asc ");
	$pcate = !empty($_GPC['pcate']) ? $_GPC['pcate'] : $list[0][id];
	$children = array();
	foreach ($list as $key => $value) {
		$sql  = "select *from".tablename("xcommunity_nav")."where uniacid='{$_W['uniacid']}' and  pcate='{$value['id']}' AND status = 1 order by displayorder asc";
		$li = pdo_fetchall($sql);
		foreach ($li as $key => $val) {
			if (substr($val['url'], 0, 5) != 'http:') {
				$val['url'] = $_SERVER['SERVER_NAME'] . $val['url'];
			}
		}
		$children[$value['id']] = $li;
	}
	$styleid = pdo_fetchcolumn("SELECT styleid FROM".tablename('xcommunity_template')."WHERE uniacid='{$_W['uniacid']}'");
	if ($styleid) {
		include $this->template('style/style'.$styleid.'/home/home');exit();
	}
	include $this->template('home');
















