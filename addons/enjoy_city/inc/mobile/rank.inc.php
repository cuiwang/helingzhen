<?php
global $_GPC, $_W;
$active = 'rank';
$uniacid = $_W['uniacid'];
$act = pdo_fetch("select * from " . tablename('enjoy_city_reply') . " where uniacid=" . $uniacid . "");
$uniacid = $_W['uniacid'];
$op = empty($_GPC['op']) ? 'd' : trim($_GPC['op']);
if ($op=='f') {
    $ranks = pdo_fetchall("select a.*,count(b.fid) as bc from " . tablename('enjoy_city_firm') . " as a left join
        " . tablename('enjoy_city_firmfans') . "as b on b.fid=a.id where a.uniacid=" . $uniacid . " and
        a.ischeck=1 and b.flag=1 group by b.fid order by bc desc limit 20");
} else {
    if ($op=='d') {
        $stime = strtotime("-1 day");
    } elseif ($op=='w') {
        $stime = strtotime("-1 week");
    } elseif ($op=='m') {
        $stime = strtotime("-1 month");
    } elseif ($op=='y') {
        $stime = strtotime("-1 year");
    }
    $ranks = pdo_fetchall("select b.*,count(a.fid) as ac from " . tablename('enjoy_city_forward') . " as a left join " . tablename('enjoy_city_firm') . "
    as b on a.fid=b.id where a.uniacid=" . $uniacid . " and b.ischeck=1
    and a.createtime>='" . $stime . "' and a.createtime<='" . TIMESTAMP . "' group by a.fid order by ac desc limit 20");
}
$sharelink = $_W['siteroot'] . "app/" . $this->createMobileUrl('entry');
$sharetitle = $act['mshare_title'];
$sharecontent = $act['mshare_content'];
$shareicon = $act['mshare_icon'];
include $this->template('rank');