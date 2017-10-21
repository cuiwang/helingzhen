<?php
/**
 * 服务项目详情
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
$from_user = $this->_fromuser;

$goodsid = intval($_GPC['goodsid']);

$url = $_W['siteroot'] .'app/'. $this->createMobileUrl('goodsdetail', array('goodsid' => $goodsid), true);
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

//设置服务点ID
$storeid = intval($_GPC['storeid']);
if(!empty($storeid)){
	$_SESSION['storeid'] = $storeid;
}

//服务项目
$goods = pdo_fetch("SELECT * FROM " .tablename($this->table_goods). " WHERE weid='{$weid}' AND id='{$goodsid}'");
$goods['content'] = htmlspecialchars_decode($goods['content']);

//最新评价
$evaluate = pdo_fetchall("SELECT a.*,b.nickname,b.avatar FROM " .tablename($this->table_goods_evaluate). " a LEFT JOIN " .tablename('mc_members')." b ON a.uid=b.uid WHERE a.weid='{$weid}' AND a.goodsid='{$goodsid}' ORDER BY add_time DESC LIMIT ".$setting['evaluate_num']);
foreach($evaluate as $key=>$value){
	if(!empty($evaluate[$key]['images'])){
		$evaluate[$key]['images'] = explode(',', $value['images']);
	}
}

//服务点信息
$store = pdo_fetch("SELECT title FROM " .tablename($this->table_store). " WHERE weid = :weid AND id = :id", array(':weid'=>$weid, ':id'=>$goods['storeid']));


include $this->template('goodsdetail');