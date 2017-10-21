<?php
global $_W, $_GPC;
$active = 'entry';
$uniacid = $_W['uniacid'];
$act = pdo_fetch("select * from " . tablename('enjoy_city_reply') . " where uniacid=" . $uniacid . "");
$allkind = pdo_fetchall("select * from " . tablename('enjoy_city_kind') . " where uniacid=" . $uniacid . " and parentid=0
  and enabled=0 order by hot asc");
$sharelink = $_W['siteroot'] . "app/" . $this->createMobileUrl('entry');
$sharetitle = $act['mshare_title'];
$sharecontent = $act['mshare_content'];
$shareicon = $act['mshare_icon'];
include $this->template('allkind');