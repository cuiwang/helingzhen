<?php
/**
 * 个人中心
 */

$uid = intval($_GPC['uid']);
$url = $_W['siteroot'] .'app/'. $this->createMobileUrl('self', array('uid'=>$uid));
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

/* 更新微课程会员信息 */
$this->updatelessonmember($openid,$uid);

/* 基本设置 */
$setting = pdo_fetch("SELECT isfollow,is_sale,sale_rank,sitename,sharelink,footnav,teacher_income,copyright,mobilechange,front_color,teacherlist,self_diy FROM " . tablename($this->table_setting) . " WHERE uniacid =:uniacid LIMIT 1", array(':uniacid' => $uniacid));
$config = $this->module['config'];
$self_diy = unserialize($setting['self_diy']);

/* 分享设置 */
load()->model('mc');
$memberid = mc_openid2uid($openid);
$sharelink = unserialize($setting['sharelink']);
$shareurl = $_W['siteroot'] .'app/'. $this->createMobileUrl('index', array('uid'=>$memberid));

/* 系统会员中心 */
$mc_memberurl = $_W['siteroot'] .'app/'."index.php?i={$uniacid}&c=mc";

/* 学号 */
$tmpno = '';
for($i=0;$i<7-strlen($memberid);$i++){
	$tmpno .= 0;
}
$studenno = $tmpno.$memberid;

$memberinfo = pdo_fetch("SELECT uid,credit1,credit2,nickname,avatar FROM " .tablename('mc_members'). " WHERE uniacid='{$uniacid}' AND uid='{$memberid}' LIMIT 1");

/* 课程会员信息 */
$lessonmember = pdo_fetch("SELECT * FROM " .tablename($this->table_member). " WHERE uniacid='{$uniacid}' AND openid='{$openid}'");

/* 检查会员是否讲师身份 */
$teacher = pdo_fetch("SELECT id FROM " .tablename($this->table_teacher). " WHERE uniacid='{$uniacid}' AND openid='{$openid}'");

/* 关注的课程数量 */
$collect_lesson = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_lesson_collect) . " WHERE uniacid='{$uniacid}' AND openid='{$openid}' AND ctype=1");

/* 关注的讲师数量 */
$collect_teacher = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_lesson_collect) . " WHERE uniacid='{$uniacid}' AND openid='{$openid}' AND ctype=2");


if($op=='upheaderimg'){
	$upfans = array();
	if(!empty($headimgurl)){
		$upfans['avatar'] = substr($headimgurl,0,-1)."132";
	}
	if(!empty($headimgurl)){
		pdo_update("mc_members", $upfans, array('uniacid'=>$uniacid,'uid'=>$memberid));
		message("更新粉丝头像成功", $this->createMobileUrl('self'), "success");
	}else{
		message("通信失败，获取用户头像失败", $this->createMobileUrl('self'), "error");
	}
	
}


include $this->template('self');



?>