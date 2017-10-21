<?php
/**
 * 微小区模块
 *
 * [晓锋] Copyright (c) 2013 qfinfo.cn
 */
/**
 * 微信端个人页面
 */
defined('IN_IA') or exit('Access Denied');
	global $_GPC,$_W;
	
	$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
	
		$title   = '我的社区';
		$member = $this->changemember();
		$region = pdo_fetch("SELECT * FROM".tablename('xcommunity_region')."WHERE id='{$member['regionid']}'");
		load()->classs('weixin.account');
		$obj = new WeiXinAccount();
		$access_token = $obj->fetch_available_token();
		$openid = $_W['openid'];
		$url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
		load()->func('communication');
		$ret = ihttp_get($url);
		if(!is_error($ret)) {
			$auth = @json_decode($ret['content'], true);
		}	
		
	if ($op == 'my') {
		$title   = '修改个人信息';
		$regions = pdo_fetchall("SELECT * FROM".tablename('xcommunity_region')."WHERE weid='{$_W['weid']}'");
		include $this->template('my');
		exit();
	}
	include $this->template('member');
	