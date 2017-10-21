<?php
global $_W, $_GPC;
$active = 'entry';
$pid = intval($_GPC['pid']);
$cid = empty($_GPC['cid']) ? 0 : intval($_GPC['cid']);
$uniacid = $_W['uniacid'];
$act = pdo_fetch("select * from " . tablename('enjoy_city_reply') . " where uniacid=" . $uniacid . "");
$pname = pdo_fetchcolumn("select name from " . tablename('enjoy_city_kind') . " where id=" . $pid . " and uniacid=" . $uniacid . "
  and enabled=0 order by hot asc");
$kinds = pdo_fetchall("select * from " . tablename('enjoy_city_kind') . " where parentid=" . $pid . " and uniacid=" . $uniacid . "
  and enabled=0 order by hot asc");
$kinds1 = pdo_fetchall("select * from " . tablename('enjoy_city_kind') . " where parentid=" . $pid . " and uniacid=" . $uniacid . "
  order by hot asc");
$kcount = pdo_fetchcolumn("select count(*) from " . tablename('enjoy_city_kind') . " where parentid=" . $pid . " and uniacid=" . $uniacid . "
    and headimg <> ''");
if ($cid!=0) {
    $firms = pdo_fetchall("select id,title,icon,breaks,starnums,starscores,intro from " . tablename('enjoy_city_firm') . " where parentid=" . $pid . " and childid=" . $cid . " and 
        uniacid=" . $uniacid . " and ischeck=1 order by hot asc");
} else {
    $firms = pdo_fetchall("select id,title,icon,breaks,starnums,starscores,intro from " . tablename('enjoy_city_firm') . " where parentid=" . $pid . " and
        uniacid=" . $uniacid . " and ischeck=1 order by hot asc limit 200");
}
$ad = pdo_fetchall("select * from " . tablename('enjoy_city_ad') . " where uniacid=" . $uniacid . " order by hot asc");
$parentkind = pdo_fetch("select * from " . tablename('enjoy_city_kind') . " where id=" . $pid . " and uniacid=" . $uniacid . "");
$sharelink = $_W['siteroot'] . "app/" . $this->createMobileUrl('entry');
$sharetitle = $act['mshare_title'];
$sharecontent = $act['mshare_content'];
$shareicon = $act['mshare_icon'];
include $this->template('kind');