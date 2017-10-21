<?php
global $_W, $_GPC;
$active = 'user';
$uniacid = $_W['uniacid'];
$act = pdo_fetch("select * from " . tablename('enjoy_city_reply') . " where uniacid=" . $uniacid . "");
$act = pdo_fetch("select * from " . tablename('enjoy_city_reply') . " where uniacid=" . $uniacid . "");
session_start();
$uid = $_SESSION['city']['uid'];
if (empty($uid)) {
    header("location:" . $this->createMobileUrl('login') . "");
}
$frims = pdo_fetchall("select * from " . tablename('enjoy_city_firm') . " where uniacid=" . $uniacid . " and uid=" . $uid . "
    and ispay>-1");
$config = $this->module['config']['api'];
for ($i = 0; $i < count($frims); $i++) {
    $frims[$i]['time'] = pdo_fetchcolumn("select createtime from " . tablename('enjoy_city_fansxx') . "
        where fid=" . $frims[$i]['id'] . " and uniacid=" . $uniacid . " order by createtime desc limit 1");
    $frims[$i]['lqtime'] = $frims[$i]['time'] + $config['jgday'] * 60 * 60 * 24 - TIMESTAMP;
}
$sharelink = $_W['siteroot'] . "app/" . $this->createMobileUrl('entry');
$sharetitle = $act['mshare_title'];
$sharecontent = $act['mshare_content'];
$shareicon = $act['mshare_icon'];
include $this->template('bdetail');