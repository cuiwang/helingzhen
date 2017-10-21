<?php
/**
 * 微小区模块
 *
 * [晓锋] Copyright (c) 2013 qfinfo.cn
 */
/**
 * 微信端常用查询
 */
defined('IN_IA') or exit('Access Denied');
	global $_W,$_GPC;
	$title = '常用查询';
	//判断是否注册
	$this->changemember();
	$list = pdo_fetchAll("SELECT * FROM".tablename("xcommunity_search")."WHERE status='1' AND weid='{$_W['weid']}'");
	include $this->template('search');