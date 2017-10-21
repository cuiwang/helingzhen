<?php
global $_W, $_GPC;
load()->func("tpl");
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$uniacid = $_W['uniacid'];
$basic = pdo_fetch("select * from " . tablename('enjoy_city_reply') . " where uniacid=0");
if ($op=='display') {
    $list = pdo_fetchall("SELECT * FROM " . tablename('enjoy_city_weitoutiao') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY hot asc");
} elseif ($op=='post') {
    $id = intval($_GPC['id']);
    if (checksubmit("submit")) {
        $data = array(
            'uniacid' => $_W['uniacid'],
            'hot' => $_GPC['hot'],
            'title' => $_GPC['title'],
            'url' => $_GPC['url']
        );
        if (!empty($id)) {
            pdo_update('enjoy_city_weitoutiao', $data, array(
                'id' => $id
            ));
            $message = "更新微头条成功！";
        } else {
            pdo_insert("enjoy_city_weitoutiao", $data);
            $id = pdo_insertid();
            $message = "新增微头条成功！";
        }
        message($message, $this->createWebUrl('weitoutiao', array(
            'op' => 'display'
        )), 'success');
    }
    $weitoutiao = pdo_fetch("SELECT * FROM " . tablename('enjoy_city_weitoutiao') . " WHERE id = '$id' and uniacid = '{$_W['uniacid']}'");
} elseif ($op=='delete') {
    $id = intval($_GPC['id']);
    $weitoutiao = pdo_fetch("SELECT id FROM " . tablename('enjoy_city_weitoutiao') . " WHERE id = '$id' AND uniacid=" . $_W['uniacid'] . "");
    if (empty($weitoutiao)) {
        message('抱歉，微头条不存在或是已经被删除！', $this->createWebUrl('weitoutiao', array(
            'op' => 'display'
        )), 'error');
    }
    pdo_delete("enjoy_city_weitoutiao", array(
        "id" => $id
    ));
    message("微头条删除成功！", $this->createWebUrl('weitoutiao', array(
        'op' => 'display'
    )), 'success');
} else {
    message("请求方式不存在");
}
include $this->template('weitoutiao');