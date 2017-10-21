<?php
/**
 * 用户个人中心
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

$url = $_W['siteroot'] .'app/'. $this->createMobileUrl('self');
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


//当前粉丝会员信息
$member = pdo_fetch("SELECT b.uid,b.mobile,b.nickname,b.avatar FROM " .tablename('mc_mapping_fans'). " a LEFT JOIN " .tablename('mc_members'). " b ON a.uid=b.uid WHERE a.uniacid='{$weid}' AND openid='{$from_user}'");

//会员中心设置
$memberUrl = $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://" .$_SERVER['HTTP_HOST']. "/app/index.php?i=".$weid."&c=mc";

//当前粉丝是否为工作人员
$isworker = pdo_fetchcolumn("SELECT count(*) FROM " .tablename($this->table_worker). " WHERE weid='{$weid}' AND openid='{$from_user}'");

//未支付订单总数
$nopay_total = pdo_fetchcolumn("SELECT count(*) FROM " .tablename($this->table_order). " WHERE weid='{$weid}' AND from_user='{$from_user}' AND status=0");

//已付款订单总数
$pay_total = pdo_fetchcolumn("SELECT count(*) FROM " .tablename($this->table_order). " WHERE weid='{$weid}' AND from_user='{$from_user}' AND (status=1 OR status=2)");

//待评价订单总数
$noevaluate_total = pdo_fetchcolumn("SELECT count(*) FROM " .tablename($this->table_order). " WHERE weid='{$weid}' AND from_user='{$from_user}' AND status=3 AND is_evaluate=0");

include $this->template('self');