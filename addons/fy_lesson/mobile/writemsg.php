<?php
/**
 * 完善手机号码/姓名
 */

$uid = intval($_GPC['uid']);
$url = $_W['siteroot'] .'app/'. $this->createMobileUrl('writemsg', array('uid'=>$uid));
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
$setting = pdo_fetch("SELECT vipserver,vipdesc,sitename,vipdiscount,sharelink,footnav,copyright,front_color,teacherlist FROM " . tablename($this->table_setting) . " WHERE uniacid =:uniacid LIMIT 1", array(':uniacid' => $uniacid));

/* 分享设置 */
load()->model('mc');
$uid = mc_openid2uid($openid);
$sharelink = unserialize($setting['sharelink']);
$shareurl = $_W['siteroot'] .'app/'. $this->createMobileUrl('index', array('uid'=>$uid));

$title = '完善信息';

if($op=='display'){
	$mc_member = pdo_fetch("SELECT mobile,realname FROM " .tablename('mc_members'). " WHERE uniacid='{$uniacid}' AND uid='{$uid}' ");

	if(checksubmit('submit')){
		$mobile	  = trim($_GPC['mobile']);
		$realname = trim($_GPC['realname']);

		$data = array();
		if(empty($mc_member['mobile'])){
			if(!(preg_match("/13\d{9}|14\d{9}|15\d{9}|17\d{9}|18\d{9}/",$mobile))){
				message("您输入的手机号码格式有误");
			}
			$exist = pdo_fetch("SELECT uid FROM " .tablename('mc_members'). " WHERE uniacid='{$uniacid}' AND mobile='{$mobile}' ");
			if(!empty($exist)){
				message("该手机号码已存在，请重新输入其他手机号码");
			}
			$data['mobile'] = $mobile;
		}

		if(empty($realname)){
			message("请输入您的姓名");
		}
		$data['realname'] = $realname;

		$result = pdo_update("mc_members", $data, array('uniacid'=>$uniacid,'uid'=>$uid));
		if($result){
			message("完善信息成功", $this->createMobileUrl('addtoorder', array('id'=>$_GPC['lessonid'])), "success");
		}else{
			message("网络错误，请稍后重试", $this->createMobileUrl('lesson', array('id'=>$_GPC['lessonid'])), "error");
		}
	}
}

include $this->template('writemsg');

?>