<?php
global $_W, $_GPC;
$active = 'user';
$user_agent = $_SERVER['HTTP_USER_AGENT'];
$uniacid = $_W['uniacid'];
$act = pdo_fetch("select * from " . tablename('enjoy_city_reply') . " where uniacid=" . $uniacid . "");
if (strpos($user_agent, 'MicroMessenger')===false) {
    $this->newmessage("请从微信客户端登录，谢谢", $this->createMobileUrl('login'));
} else {
    $userlist = $this->auth();
    if (!empty($userlist)) {
        session_start();
        $username = empty($userlist['username']) ? $userlist['nickname'] : $userlist['username'];
        $_SESSION['city']['username'] = $username;
        $_SESSION['city']['openid'] = $userlist['openid'];
        $_SESSION['city']['uid'] = $userlist['uid'];
        header("location:" . $this->createMobileUrl('personal', array(
            'uid' => $userlist['uid']
        )) . "");
    } else {
        $this->newmessage('非法登录', $this->createMobileUrl('login'));
        exit();
    }
}
$sharelink = $_W['siteroot'] . "app/" . $this->createMobileUrl('entry');
$sharetitle = $act['mshare_title'];
$sharecontent = $act['mshare_content'];
$shareicon = $act['mshare_icon'];
include $this->template('wlogin');