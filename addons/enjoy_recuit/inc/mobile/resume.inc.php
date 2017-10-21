<?php


global $_W;
global $_GPC;

if (!empty($this->module['config']['appid']) && !empty($this->module['config']['appsecret'])) {
	$this->auth();
}
 else {
	$user_agent = $_SERVER['HTTP_USER_AGENT'];

	if (strpos($user_agent, 'MicroMessenger') === false) {
		exit('本页面仅支持微信访问!非微信浏览器禁止浏览!');
	}

}

$openid = $_W['openid'];
$uniacid = $_W['uniacid'];
$pid = $_GPC['pid'];
$com = pdo_fetch('select * from ' . tablename('enjoy_recuit_culture') . ' where uniacid = \'' . $_W['uniacid'] . '\'');
$jssdk = new JSSDK();
$signPackage = $jssdk->GetSignPackage();
$mylist = pdo_fetch('select * from ' . tablename('enjoy_recuit_basic') . ' as a left join ' . tablename('enjoy_recuit_info') . ' as b on a.openid=b.openid' . "\r\n\t\t\t\t" . 'where a.openid=\'' . $openid . '\' and a.uniacid=' . $uniacid . '');

if (empty($mylist['avatar'])) {
	$mylist['avatar'] = pdo_fetchcolumn('select avatar from ' . tablename('enjoy_recuit_fans') . ' where openid=\'' . $openid . '\' and uniacid=' . $uniacid . '');
}
 else {
	$mylist['avatar'] = tomedia($mylist['avatar']);
}

$myexpers = pdo_fetchall('select * from ' . tablename('enjoy_recuit_exper') . ' where openid=\'' . $openid . '\' and uniacid=' . $uniacid . '');
$mylist['exper'] = $myexpers;
$mycard = pdo_fetchall('select * from ' . tablename('enjoy_recuit_card') . ' where openid=\'' . $openid . '\' and uniacid=' . $uniacid . '');
$mylist['card'] = $mycard;
include $this->template('resume');
