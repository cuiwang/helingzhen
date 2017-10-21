<?php
/**
 * 我的团队
 */

$mid   = intval($_GPC['mid']);
$level = $_GPC['level']?intval($_GPC['level']):'1';
$url = $_W['siteroot'] .'app/'. $this->createMobileUrl('team', array('mid'=>$mid,'level'=>$level));
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
$setting = pdo_fetch("SELECT level,sitename,sharelink,footnav,copyright,front_color,teacherlist FROM " . tablename($this->table_setting) . " WHERE uniacid =:uniacid LIMIT 1", array(':uniacid' => $uniacid));

/* 分享设置 */
load()->model('mc');
$uid = mc_openid2uid($openid);
$sharelink = unserialize($setting['sharelink']);
$shareurl = $_W['siteroot'] .'app/'. $this->createMobileUrl('index', array('uid'=>$uid));

$title = "我的团队";

$pindex =max(1,$_GPC['page']);
$psize = 10;

$userid = $mid?$mid:$uid;
$member = pdo_fetch("SELECT nickname FROM " .tablename('mc_members'). " WHERE uid='{$userid}'");

$teamlist = pdo_fetchall("SELECT a.uid,a.openid,a.nopay_commission+a.pay_commission AS commission,a.addtime, b.nickname,b.avatar FROM " .tablename($this->table_member). " a LEFT JOIN " .tablename('mc_members'). " b ON a.uid=b.uid WHERE a.uniacid='{$uniacid}' AND a.parentid='{$userid}' ORDER BY a.id DESC LIMIT " . ($pindex-1) * $psize . ',' . $psize);
/* 一级会员人数 */
$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_member). " a LEFT JOIN " .tablename('mc_members'). " b ON a.uid=b.uid WHERE a.uniacid='{$uniacid}' AND a.parentid='{$userid}'");

foreach($teamlist as $k1=>$v1){
	$direct2 = pdo_fetchall("SELECT uid FROM " .tablename($this->table_member). " WHERE uniacid='{$uniacid}' AND parentid='{$v1['uid']}' ");
	/* 二级会员人数 */
	$direct2_num = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_member). " WHERE uniacid='{$uniacid}' AND parentid='{$v1['uid']}' ");
	
	$teamlist[$k1]['recnum']  = $direct2_num;
	$teamlist[$k1]['addtime'] = date('Y-m-d', $v1['addtime']);
}


$sontitle = $level==1?"我的团队成员({$total})":"[".$member['nickname']."]的团队成员({$total})";

if(!$_W['isajax']){
	include $this->template('team');
}else{
	echo json_encode($teamlist);
}


?>