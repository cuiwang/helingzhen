<?php
global $_W, $_GPC;
$uniacid = $_W['uniacid'];
$active = 'entry';
$act = pdo_fetch("select * from " . tablename('enjoy_city_reply') . " where uniacid=" . $uniacid . "");
$jointel = $this->jointh($act['jointel'], $act['tel']);
$ad = pdo_fetchall("select * from " . tablename('enjoy_city_ad') . " where uniacid=" . $uniacid . " order by hot asc");
if ($act[ispayfirst]==1) {
    $order = ",ismoney desc";
} else {
    $order = "";
}
$newfirm = pdo_fetchall("select id,title from " . tablename('enjoy_city_firm') . " where uniacid=" . $uniacid . " and ischeck=1
    order by createtime desc " . $order . " limit 50");
$cate = pdo_fetchall("select * from " . tablename('enjoy_city_kind') . " where uniacid=" . $uniacid . " and parentid=0
    and enabled=0 order by hot asc");
$cates = array();
if ($act['wstyle']==0) {
    for ($i = 0; $i < count($cate) / 10; $i++) {
        for ($j = 0; $j < 10; $j++) {
            if ($i * 10 + $j < count($cate)) {
                $cates[$i][$i * 10 + $j] = $cate[$i * 10 + $j];
            }
        }
    }
} else {
    for ($i = 0; $i < count($cate) / 15; $i++) {
        for ($j = 0; $j < 15; $j++) {
            if ($i * 15 + $j < count($cate)) {
                $cates[$i][$i * 15 + $j] = $cate[$i * 15 + $j];
            }
        }
    }
}
$op = !empty($_GPC['op']) ? trim($_GPC['op']) : 'hot';
if ($op=='new') {
    $firms = pdo_fetchall("select * from " . tablename('enjoy_city_firm') . " where uniacid=" . $uniacid . " and ischeck=1
   order by createtime desc " . $order . " limit 50 ");
} elseif ($op=='hot') {
    $firmshot = pdo_fetchall("select * from " . tablename('enjoy_city_firm') . " where uniacid=" . $uniacid . " and ischeck=1
   order by forward desc " . $order . " limit 50");
} elseif ($op=='man') {
    $contact = pdo_fetchall("select * from " . tablename('enjoy_city_contact') . " where uniacid=" . $uniacid . "
    and ischeck=1 order by id desc");
} elseif ($op=='job') {
    $joblist = pdo_fetchall("select a.*,b.title from " . tablename('enjoy_city_job') . " as a 
        left join " . tablename('enjoy_city_firm') . " as b on a.fid=b.id where a.uniacid=" . $uniacid . "
    and a.ischeck=1 and a.isend=0 order by a.updatetime desc limit 50");
} elseif ($op=='dist') {
    $lat = $_COOKIE['lat'];
    $lng = $_COOKIE['lng'];
    if (!empty($lat) && !empty($lng)) {
        $stores = pdo_fetchall("SELECT *,(location_x-:lng) * (location_x-:lng) + (location_y-:lat) * (location_y-:lat) as dist FROM " . tablename('enjoy_city_firm') . " 
            WHERE uniacid = :uniacid  AND ischeck=1 and location_x<>'' and location_y<>'' ORDER BY dist,id DESC " . $order . " limit 50", array(
            ':uniacid' => $uniacid,
            ':lat' => $lat,
            ':lng' => $lng
        ));
    }
}
$customs = pdo_fetchall("select * from " . tablename('enjoy_city_custom') . " where uniacid=" . $uniacid . " 
    and enabled=0 order by hot asc");
$sharelink = $_W['siteroot'] . "app/" . $this->createMobileUrl('entry');
$sharetitle = $act['mshare_title'];
$sharecontent = $act['mshare_content'];
$shareicon = $act['mshare_icon'];
include $this->template('entry');