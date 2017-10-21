<?php
global $_W, $_GPC;
load()->func("tpl");
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$uniacid = $_W['uniacid'];
$basic = pdo_fetch("select * from " . tablename('enjoy_city_reply') . " where uniacid=0");
if ($op=='display') {
    $list = pdo_fetchall("SELECT * FROM " . tablename('enjoy_city_ad') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY hot asc");
} elseif ($op=='post') {
    $id = intval($_GPC['id']);
    if (checksubmit("submit")) {
        $data = array(
            'uniacid' => $_W['uniacid'],
            'hot' => $_GPC['hot'],
            'title' => $_GPC['title'],
            'pic' => $_GPC['pic'],
            'url' => $_GPC['url']
        );
        if (!empty($id)) {
            pdo_update('enjoy_city_ad', $data, array(
                'id' => $id
            ));
            $message = "更新广告成功！";
        } else {
            pdo_insert("enjoy_city_ad", $data);
            $id = pdo_insertid();
            $message = "新增广告成功！";
        }
        message($message, $this->createWebUrl('ad', array(
            'op' => 'display'
        )), 'success');
    }
    $ad = pdo_fetch("SELECT * FROM " . tablename('enjoy_city_ad') . " WHERE id = '$id' and uniacid = '{$_W['uniacid']}'");
} elseif ($op=='delete') {
    $id = intval($_GPC['id']);
    $ad = pdo_fetch("SELECT id FROM " . tablename('enjoy_city_ad') . " WHERE id = '$id' AND uniacid=" . $_W['uniacid'] . "");
    if (empty($ad)) {
        message('抱歉，广告不存在或是已经被删除！', $this->createWebUrl('ad', array(
            'op' => 'display'
        )), 'error');
    }
    pdo_delete("enjoy_city_ad", array(
        "id" => $id
    ));
    message("广告删除成功！", $this->createWebUrl('ad', array(
        'op' => 'display'
    )), 'success');
} else {
    message("请求方式不存在");
}
include $this->template('ad');