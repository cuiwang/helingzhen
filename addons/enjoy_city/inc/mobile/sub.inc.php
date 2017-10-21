<?php
global $_W, $_GPC;
$active = 'user';
$uniacid = $_W['uniacid'];
session_start();
$username = $_SESSION['city']['username'];
$uid = $_SESSION['city']['uid'];
$act = pdo_fetch("select * from " . tablename('enjoy_city_reply') . " where uniacid=" . $uniacid . "");
$user_agent = $_SERVER['HTTP_USER_AGENT'];
if (empty($uid)) {
    if (strpos($user_agent, 'MicroMessenger')===false) {
        unset($_SESSION['city']);
        header("location:" . $this->createMobileUrl('login') . "");
    } else {
        $userlist = $this->auth();
        if (!empty($userlist)) {
            $username = empty($userlist['username']) ? $userlist['nickname'] : $userlist['username'];
            $_SESSION['city']['username'] = $username;
            $_SESSION['city']['openid'] = $userlist['openid'];
            $_SESSION['city']['uid'] = $userlist['uid'];
            $uid = $userlist['uid'];
        } else {
            $this->newmessage('非法登录', $this->createMobileUrl('login'));
            exit();
        }
    }
}
if (strpos($user_agent, 'MicroMessenger')===false) {
    $password = $_SESSION['city']['password'];
    $fans = pdo_fetch("select * from " . tablename('enjoy_city_fans') . " where uniacid=" . $uniacid . " and (username='" . $username . "'
       or mobile='" . $username . "')");
    if ($fans['password']==$password) {
    } else {
        unset($_SESSION['city']);
        header("location:" . $this->createMobileUrl('login') . "");
    }
} else {
    $openid = $_SESSION['city']['openid'];
    $fans = pdo_fetch("select * from " . tablename('enjoy_city_fans') . " where uniacid=" . $uniacid . " and uid='" . $uid . "'");
    if (!empty($fans['uid'])) {
    } else {
        header("location:" . $this->createMobileUrl('login') . "");
    }
}
$fans['nickname'] = empty($fans['nickname']) ? $fans['username'] : $fans['nickname'];
$fans['avatar'] = empty($fans['avatar']) ? tomedia('../addons/enjoy_city/public/images/img.png') : $fans['avatar'];
$firms = pdo_fetchall("select a.createtime as acreatetime,b.* from " . tablename('enjoy_city_firmfans') . " as a left join
    " . tablename('enjoy_city_firm') . " as b on a.fid=b.id where a.uniacid=" . $uniacid . " and a.openid='" . $fans[openid] . "'
    and a.flag=1 order by a.createtime desc");
$sharelink = $_W['siteroot'] . "app/" . $this->createMobileUrl('entry');
$sharetitle = $act['mshare_title'];
$sharecontent = $act['mshare_content'];
$shareicon = $act['mshare_icon'];
include $this->template('sub');