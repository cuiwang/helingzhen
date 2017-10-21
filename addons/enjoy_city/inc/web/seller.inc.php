<?php
global $_W, $_GPC;
$gid = $_GPC['gid'];
$uniacid = $_W['uniacid'];
load()->func("tpl");
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($op=='delete') {
    $id = $_GPC['id'];
    $res = pdo_delete('enjoy_city_seller', array(
        'id' => $id,
        'uniacid' => $uniacid
    ));
    if ($res > 0) {
        message('业务员删除成功！', $this->createWebUrl('seller', array()), 'success');
    }
} else if ($op=='display') {
    $pindex = max(1, intval($_GPC['page']));
    $psize = 10;
    if ($_GPC['realname']) {
        $where = "and realname LIKE '%" . $_GPC['realname'] . "%'";
    } else {
        $where = "";
    }
    if ($_GPC['unusual']) {
        $where1 = "and ischeck=0";
    } else {
        $where1 = "";
    }
    $seller = pdo_fetchall("select * from " . tablename('enjoy_city_seller') . " where uniacid=" . $uniacid . " " . $where . $where1 . " LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
    $countadd = pdo_fetchcolumn("select count(*) from " . tablename('enjoy_city_seller') . " where uniacid=" . $uniacid . " " . $where . $where1 . "");
    $pager = pagination($countadd, $pindex, $psize);
    $countseller = pdo_fetchcolumn("select count(*) from " . tablename('enjoy_city_seller') . " where uniacid=" . $uniacid . "");
    $countcheck = pdo_fetchcolumn("select count(*) from " . tablename('enjoy_city_seller') . " where uniacid=" . $uniacid . "");
} elseif ($op=='post') {
    $id = intval($_GPC['id']);
    if (checksubmit("submit")) {
        $data = array(
            'uniacid' => $_W['uniacid'],
            'realname' => $_GPC['realname'],
            'phone' => $_GPC['phone'],
            'openid' => $_GPC['openid'],
            'createtime' => TIMESTAMP
        );
        if (!empty($id)) {
            pdo_update('enjoy_city_seller', $data, array(
                'id' => $id
            ));
            $message = "更新业务员成功！";
        } else {
            pdo_insert("enjoy_city_seller", $data);
            $id = pdo_insertid();
            $message = "新增业务员成功！";
        }
        message($message, $this->createWebUrl('seller', array(
            'op' => 'display'
        )), 'success');
    }
    $seller = pdo_fetch("SELECT * FROM " . tablename('enjoy_city_seller') . " WHERE id = '$id' and uniacid = '{$_W['uniacid']}'");
} else {
    message("请求方式不存在");
}
$message = pdo_fetch("select * from " . tablename('modules_bindings') . " where module='enjoy_city' and do='firm'");
$basic = pdo_fetch("select * from " . tablename('enjoy_city_reply') . " where uniacid=0");
include $this->template('seller');