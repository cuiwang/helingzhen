<?php
/**
 * 讲师中心
 */

$url = $_W['siteroot'] .'app/'. $this->createMobileUrl('teachercenter', array('op'=>$_GPC['op']));
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
$setting = pdo_fetch("SELECT isfollow,teacher_income,cash_lower,cash_type,sitename,sharelink,footnav,copyright,front_color,teacherlist FROM " . tablename($this->table_setting) . " WHERE uniacid =:uniacid LIMIT 1", array(':uniacid' => $uniacid));

/* 分享设置 */
load()->model('mc');
$uid = mc_openid2uid($openid);
$sharelink = unserialize($setting['sharelink']);
$shareurl = $_W['siteroot'] .'app/'. $this->createMobileUrl('index', array('uid'=>$uid));

$teacher = pdo_fetch("SELECT a.*,b.avatar,b.nickname FROM " .tablename($this->table_teacher). " a LEFT JOIN " .tablename('mc_members'). " b ON a.uid=b.uid WHERE a.uniacid='{$uniacid}' AND a.openid='{$openid}'");
if($op=='display'){
	if(empty($teacher)){
		if($setting['teacher_income']==0){
			message("系统没有开启讲师入驻！", "", "warning");
		}else{
			header("Location:".$this->createMobileUrl('applyteacher'));
		}
	}

	$member = pdo_fetch("SELECT * FROM " .tablename($this->table_member). " WHERE uniacid='{$uniacid}' AND openid='{$openid}'");

	$title = "讲师个人中心";

	/* 我的课程 */
	$mylessoncount = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_lesson_parent) . " WHERE uniacid=:uniacid AND teacherid=:teacherid ", array(':uniacid'=>$uniacid, ':teacherid'=>$teacher['id']));

	include $this->template('teachercenter');

}elseif($op=='account'){
	$title = "讲师平台帐号管理";

	if(checksubmit('submit')){
		$account = trim($_GPC['account']);
		$password = $_GPC['password'];
		if(empty($teacher['account']) && (strlen($account)<6 || strlen($account)>16)){
			message("登陆账号长度必须介于6~16位", $this->createMobileUrl('teachercenter', array('op'=>'account')), "error");
		}
		if(strlen($password)<6 || strlen($password)>16){
			message("登陆密码长度必须介于6~16位", $this->createMobileUrl('teachercenter', array('op'=>'account')), "error");
		}

		$isExist = pdo_fetch("SELECT id FROM " .tablename($this->table_teacher). " WHERE uniacid=:uniacid AND account=:account LIMIT 1", array(':uniacid'=>$uniacid, ':account'=>$account));
		if($isExist && $account!=$teacher['account']){
			message("该登录帐号已被占用，请重新输入登录帐号", $this->createMobileUrl('teachercenter', array('op'=>'account')), "error");
		}

		$update = array('password'=>md5($password.$_W['config']['setting']['authkey']));
		if(empty($teacher['account'])){
			$update['account'] = $account;
		}

		$res = pdo_update($this->table_teacher, $update, array('uniacid'=>$uniacid,'openid'=>$openid));
		if($res){
			message("保存成功", $this->createMobileUrl('teachercenter'), "success");
		}else{
			message("保存失败", $this->createMobileUrl('teachercenter', array('op'=>'account')), "error");
		}
	}


	include $this->template('teacheraccount');

}
	

?>