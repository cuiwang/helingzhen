<?php
/**
 * 微小区模块
 *
 * [晓锋] Copyright (c) 2013 qfinfo.cn
 */
/**
 * 微信端常用号码
 */
defined('IN_IA') or exit('Access Denied');
	global $_GPC,$_W;   	
	$title = '便民号码';
	$this->changemember();
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$sql    = "select * from ".tablename("xcommunity_phone")."where weid='{$_W['weid']}' LIMIT ".($pindex - 1) * $psize.','.$psize;
	$phones = pdo_fetchall($sql);
	$total = pdo_fetchcolumn('select count(*) from'.tablename("xcommunity_phone")."where weid='{$_W['weid']}'");
	$pager = pagination($total, $pindex, $psize);
	include $this->template('phone');