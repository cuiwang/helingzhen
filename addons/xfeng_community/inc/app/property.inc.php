<?php
/**
 * 微小区模块
 *
 * [晓锋] Copyright (c) 2013 qfinfo.cn
 */
/**
 * 微信端团队介绍
 */
defined('IN_IA') or exit('Access Denied');

	global $_W,$_GPC;
	$title = '物业团队介绍';
	//判断是否注册，只有注册后，才能进入
	$this->changemember();
	$list = pdo_fetch("SELECT * FROM".tablename('xcommunity_property')."WHERE weid='{$_W['weid']}'");
	include $this->template('property');
