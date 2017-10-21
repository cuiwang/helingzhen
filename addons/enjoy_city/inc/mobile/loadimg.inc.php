<?php
global $_W, $_GPC;
$uniacid = $_W['uniacid'];
$fid = intval($_GPC['fid']);
$act = pdo_fetch("select * from " . tablename('enjoy_city_reply') . " where uniacid=" . $uniacid . "");
$fimg = pdo_fetchall("select * from " . tablename('enjoy_city_fimg') . " where fid=" . $fid . " and uniacid=" . $uniacid . "");
for ($i = 1; $i <= 9; $i++) {
    $loadimg[$i] = $fimg[$i - 1];
    $loadimg[$i]['num'] = $i;
}
$sharelink = $_W['siteroot'] . "app/" . $this->createMobileUrl('entry');
$sharetitle = $act['mshare_title'];
$sharecontent = $act['mshare_content'];
$shareicon = $act['mshare_icon'];
include $this->template('loadimg');