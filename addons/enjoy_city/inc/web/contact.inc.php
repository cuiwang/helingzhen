<?php
global $_W, $_GPC;
$gid = $_GPC['gid'];
$uniacid = $_W['uniacid'];
load()->func("tpl");
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($op=='delete') {
    $id = $_GPC['id'];
    $res = pdo_delete('enjoy_city_contact', array(
        'id' => $id,
        'uniacid' => $uniacid
    ));
    if ($res > 0) {
        message('用户删除成功！', $this->createWebUrl('contact', array()), 'success');
    }
} else if ($op=='ischeck') {
    $ischeck = $_GPC['ischeck'];
    $uid = $_GPC['uid'];
    if ($ischeck==0) {
        $ischeck = 1;
    } else {
        $ischeck = 0;
    }
    pdo_update("enjoy_city_contact", array(
        "ischeck" => $ischeck
    ), array(
        'uniacid' => $uniacid,
        'uid' => $uid
    ));
    message("操作成功", $this->createWebUrl('contact'), 'success');
} else if ($op=='display') {
    $pindex = max(1, intval($_GPC['page']));
    $psize = 10;
    if ($_GPC['nickname']) {
        $where = "and cnickname LIKE '%" . $_GPC['nickname'] . "%'";
    } else {
        $where = "";
    }
    if ($_GPC['unusual']) {
        $where1 = "and ischeck=0";
    } else {
        $where1 = "";
    }
    if (checksubmit("submit")) {
        if (!empty($_GPC['hot'])) {
            foreach ($_GPC['hot'] as $id => $hot) {
                pdo_update('enjoy_city_contact', array(
                    'hot' => $hot
                ), array(
                    'id' => $id
                ));
            }
            message("排序更新成功！", $this->createWebUrl('contact', array(
                'op' => 'display'
            )), 'success');
        }
    }
    $contact = pdo_fetchall("select * from " . tablename('enjoy_city_contact') . " where uniacid=" . $uniacid . " " . $where . $where1 . " order by uid desc LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
    $countadd = pdo_fetchcolumn("select count(*) from " . tablename('enjoy_city_contact') . " where uniacid=" . $uniacid . " " . $where . $where1 . "");
    $pager = pagination($countadd, $pindex, $psize);
    $countcontact = pdo_fetchcolumn("select count(*) from " . tablename('enjoy_city_contact') . " where uniacid=" . $uniacid . "");
    $countcheck = pdo_fetchcolumn("select count(*) from " . tablename('enjoy_city_contact') . " where uniacid=" . $uniacid . " and ischeck=1");
} elseif ($op=='post') {
    $id = intval($_GPC['id']);
    if (checksubmit("submit")) {
        $data = array(
            'uniacid' => $_W['uniacid'],
            'hot' => $_GPC['hot'],
            'cgender' => $_GPC['cgender'],
            'cavatar' => $_GPC['cavatar'],
            'cewm' => $_GPC['cewm'],
            'cweixin' => $_GPC['cweixin'],
            'cnickname' => $_GPC['cnickname'],
            'addtime' => TIMESTAMP,
            'uid' => $_GPC['uid'],
            'descript' => $_GPC['descript'],
            'ischeck' => 1
        );
        if (!empty($id)) {
            pdo_update('enjoy_city_contact', $data, array(
                'id' => $id
            ));
            $message = "更新人脉成功！";
        } else {
            pdo_insert("enjoy_city_contact", $data);
            $id = pdo_insertid();
            $message = "新增人脉成功！";
        }
        message($message, $this->createWebUrl('contact', array(
            'op' => 'display'
        )), 'success');
    }
    $contact = pdo_fetch("SELECT * FROM " . tablename('enjoy_city_contact') . " WHERE id = '$id' and uniacid = '{$_W['uniacid']}'");
} else {
    message("请求方式不存在");
}
$basic = pdo_fetch("select * from " . tablename('enjoy_city_reply') . " where uniacid=0");
include $this->template('contact');