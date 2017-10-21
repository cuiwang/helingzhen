<?php
global $_W, $_GPC;
$active = 'user';
$uniacid = $_W['uniacid'];
$act = pdo_fetch("select * from " . tablename('enjoy_city_reply') . " where uniacid=" . $uniacid . "");
if (!empty($_GPC['setpwd'])) {
    session_start();
    $uid = $_SESSION['city']['uid'];
    $mobile = trim($_GPC['mobile']);
    $password = trim($_GPC['password']);
    $password2 = trim($_GPC['password2']);
    $fans = pdo_fetch("select * from " . tablename('enjoy_city_fans') . " where uniacid=" . $uniacid . " and uid=" . $uid . "");
    if ($password==$password2) {
        $resm = pdo_fetchcolumn("select count(*) from " . tablename('enjoy_city_fans') . " where uniacid=" . $uniacid . "
        and mobile='" . $mobile . "'");
        if ($resm > 0) {
            $this->newmessage("该手机号码已被占用", $this->createMobileUrl('personal'));
        } else {
            $res = pdo_update('enjoy_city_fans', array(
                'password' => $password,
                'mobile' => $mobile
            ), array(
                'uniacid' => $uniacid,
                'uid' => $uid
            ));
            if ($res > 0) {
                $_SESSION['city']['password'] = $password;
                header("location:" . $this->createMobileUrl('personal') . "");
            }
        }
    } else {
        $this->newmessage("两次输入密码不一致", $this->createMobileUrl('personal'));
    }
}
$sharelink = $_W['siteroot'] . "app/" . $this->createMobileUrl('entry');
$sharetitle = $act['mshare_title'];
$sharecontent = $act['mshare_content'];
$shareicon = $act['mshare_icon'];
include $this->template('setpwd');