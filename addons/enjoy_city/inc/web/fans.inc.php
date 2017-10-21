<?php
global $_W, $_GPC;
$gid = $_GPC['gid'];
$uniacid = $_W['uniacid'];
$op = $_GPC['op'];
if ($op=='delete') {
    $uid = $_GPC['uid'];
    $res = pdo_delete('enjoy_city_fans', array(
        'uid' => $uid,
        'uniacid' => $uniacid
    ));
    if ($res > 0) {
        message('用户删除成功！', $this->createWebUrl('fans', array()), 'success');
    }
} else if ($op=='ischeck') {
    $ischeck = $_GPC['ischeck'];
    $uid = $_GPC['uid'];
    if ($ischeck==0) {
        $ischeck = 1;
    } else {
        $ischeck = 0;
    }
    pdo_update("enjoy_city_fans", array(
        "ischeck" => $ischeck
    ), array(
        'uniacid' => $uniacid,
        'uid' => $uid
    ));
    message("操作成功", $this->createWebUrl('fans'), 'success');
}
$pindex = max(1, intval($_GPC['page']));
$psize = 10;
if ($_GPC['nickname']) {
    $where = "and nickname LIKE '%" . $_GPC['nickname'] . "%'";
} else {
    $where = "";
}
$fans = pdo_fetchall("select a.*,b.cnickname,b.id as cid from " . tablename('enjoy_city_fans') . " as a left join " . tablename('enjoy_city_contact') . "
    as b on a.uid=b.uid where 
    a.uniacid=" . $uniacid . " " . $where . " order by uid desc LIMIT 
    " . ($pindex - 1) * $psize . ',' . $psize);
for ($i = 0; $i < count($fans); $i++) {
    $fans[$i]['firm'] = pdo_fetchall("select id,title from " . tablename('enjoy_city_firm') . " where uniacid=" . $uniacid . " and uid=" . $fans[$i][uid] . "");
}
$countadd = pdo_fetchcolumn("select count(*) from " . tablename('enjoy_city_fans') . " where uniacid=" . $uniacid . " " . $where . "");
$pager = pagination($countadd, $pindex, $psize);
$countfans = pdo_fetchcolumn("select count(*) from " . tablename('enjoy_city_fans') . " where uniacid=" . $uniacid . "");
$basic = pdo_fetch("select * from " . tablename('enjoy_city_reply') . " where uniacid=0");
$countcheck = pdo_fetchcolumn("select count(*) from " . tablename('enjoy_city_fans') . " where uniacid=" . $uniacid . " and ischeck=1");
include $this->template('fans');