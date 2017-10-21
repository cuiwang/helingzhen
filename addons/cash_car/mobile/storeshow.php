<?php
/**
 * 服务点简介
 * ============================================================================
 * 版权所有 2015-2016 米粒源码，并保留所有权利。
 * 网站地址: http://www.webmili.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！已购买用户允许对程序代码进行修改和使用，但是不允许对
 * 程序代码以任何形式任何目的的再发布，作者将保留追究法律责任的权力和最终解
 * 释权。
 * ============================================================================
 */
  
$weid = $this->_weid;
$storeid = intval($_GPC['storeid']);
if (empty($storeid)) {
	message('请先选择服务点', $this->createMobileUrl('storelist'));
}

$url = $_W['siteroot'] .'app/'. $this->createMobileUrl("storeshow", array('storeid' => $storeid), true);
if (isset($_COOKIE[$this->_auth2_openid])) {
	$from_user = $_COOKIE[$this->_auth2_openid];
	$nickname = $_COOKIE[$this->_auth2_nickname];
	$headimgurl = $_COOKIE[$this->_auth2_headimgurl];
} else {
	if (isset($_GPC['code'])) {
		$userinfo = $this->oauth2();
		if (!empty($userinfo)) {
			$from_user = $userinfo["openid"];
			$nickname = $userinfo["nickname"];
			$headimgurl = $userinfo["headimgurl"];
		} else {
			message('授权失败!');
		}
	} else {
		if (!empty($this->_appsecret)) {
			$this->getCode($url);
		}
	}
}

if (empty($from_user)) {
	message('会话已过期，请重新发送关键字!');
}

$store = pdo_fetch("SELECT * FROM " . tablename($this->table_store) . " WHERE id=:id", array(':id' => $storeid));
if (empty($store)) {
	message('未找到指定服务点!');
}

$title = '服务点介绍';


include $this->template('storeshow');