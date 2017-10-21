<?php
global $_W, $_GPC;
$active = 'user';
$uniacid = $_W['uniacid'];
$act = pdo_fetch("select * from " . tablename('enjoy_city_reply') . " where uniacid=" . $uniacid . "");
$sharelink = $_W['siteroot'] . "app/" . $this->createMobileUrl('entry');
$sharetitle = $act['mshare_title'];
$sharecontent = $act['mshare_content'];
$shareicon = $act['mshare_icon'];
include $this->template('agreement');