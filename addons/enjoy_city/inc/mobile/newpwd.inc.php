<?php
global $_W, $_GPC;
$active = 'user';
$uniacid = $_W['uniacid'];
$act = pdo_fetch("select * from " . tablename('enjoy_city_reply') . " where uniacid=" . $uniacid . "");
if (!empty($_GPC['register'])) {
    session_start();
    $uid = $_SESSION['city']['uid'];
    $oldpass = trim($_GPC['oldpass']);
    $password = trim($_GPC['password']);
    $password2 = trim($_GPC['password2']);
    $fans = pdo_fetch("select * from " . tablename('enjoy_city_fans') . " where uniacid=" . $uniacid . " and uid=" . $uid . "");
    if ($password==$password2) {
        if ($fans['password']==$oldpass) {
            $res = pdo_update('enjoy_city_fans', array(
                'password' => $password
            ), array(
                'uniacid' => $uniacid,
                'uid' => $uid
            ));
            if ($res > 0) {
                $_SESSION['city']['password'] = $password;
                header("location:" . $this->createMobileUrl('personal') . "");
            }
        } else {
            $this->newmessage("旧密码输入错误", $this->createMobileUrl('personal'));
        }
    } else {
        $this->newmessage("两次输入密码不一样", $this->createMobileUrl('personal'));
    }
}
$sharelink = $_W['siteroot'] . "app/" . $this->createMobileUrl('entry');
$sharetitle = $act['mshare_title'];
$sharecontent = $act['mshare_content'];
$shareicon = $act['mshare_icon'];
include $this->template('newpwd');