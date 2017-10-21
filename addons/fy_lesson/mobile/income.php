<?php
/**
 * 讲师收入明细
 */

$url = $_W['siteroot'] .'app/'. $this->createMobileUrl('income', array('op'=>$_GPC['op']));
if (isset($_COOKIE[$this->_auth2_openid])) {
	$openid = $_COOKIE[$this->_auth2_openid];
	$nickname = $_COOKIE[$this->_auth2_nickname];
	$headimgurl = $_COOKIE[$this->_auth2_headimgurl];
} else {
	if (isset($_GPC['code'])) {
		$userinfo = $this->oauth2();
		if (!empty($userinfo)) {
			$openid = $userinfo["openid"];
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

/* 基本设置 */
$setting = pdo_fetch("SELECT sitename,sharelink,footnav,copyright,front_color,teacherlist FROM " . tablename($this->table_setting) . " WHERE uniacid =:uniacid LIMIT 1", array(':uniacid' => $uniacid));

/* 分享设置 */
load()->model('mc');
$uid = mc_openid2uid($openid);
$sharelink = unserialize($setting['sharelink']);
$shareurl = $_W['siteroot'] .'app/'. $this->createMobileUrl('index', array('uid'=>$uid));

$pindex =max(1,$_GPC['page']);
$psize = 10;

$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_teacher_income). " WHERE uniacid='{$uniacid}' AND openid='{$openid}' ORDER BY id DESC LIMIT " . ($pindex-1) * $psize . ',' . $psize);
foreach($list as $key=>$value){
	$list[$key]['remark']  = "课程价格：".$value['orderprice']."元，收入提成：".$value['teacher_income']."%";
	$list[$key]['addtime'] = date("Y-m-d", $value['addtime']);
}
$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_teacher_income). " WHERE uniacid='{$uniacid}' AND openid='{$openid}'");

$title = "我的收入明细(".$total.")";

if(!$_W['isajax']){
	include $this->template('incomelog');
}else{
	echo json_encode($list);
}


?>