<?php
global $_GPC, $_W;
load()->func("tpl");
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$uniacid = $_W['uniacid'];
$basic = pdo_fetch("select * from " . tablename('enjoy_city_reply') . " where uniacid=0");
$exist = pdo_tableexists('enjoy_city_custom');
if (!$exist) {
    exit();
}
if ($operation=='display') {
    if (!empty($_GPC['hot'])) {
        foreach ($_GPC['hot'] as $id => $hot) {
            pdo_update('enjoy_city_custom', array(
                'hot' => $hot
            ), array(
                'id' => $id
            ));
        }
        message("菜单排序更新成功！", $this->createWebUrl('custom', array(
            'op' => 'display'
        )), 'success');
    }
    $children = array();
    $custom = pdo_fetchall("SELECT * FROM " . tablename('enjoy_city_custom') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY hot ASC");
    include $this->template('custom');
} elseif ($operation=='post') {
    $id = intval($_GPC['id']);
    if (!empty($id)) {
        $custom = pdo_fetch("SELECT * FROM " . tablename('enjoy_city_custom') . " WHERE id = '$id'");
    } else {
        $custom = array(
            'hot' => 0
        );
    }
    if (checksubmit("submit")) {
        if (empty($_GPC['catename'])) {
            message('抱歉，请输入菜单名称！');
        }
        $data = array(
            'uniacid' => $_W['uniacid'],
            'name' => $_GPC['catename'],
            'enabled' => intval($_GPC['enabled']),
            'hot' => intval($_GPC['hot']),
            'thumb' => $_GPC['thumb'],
            'wurl' => trim($_GPC['wurl'])
        );
        if (!empty($id)) {
            pdo_update('enjoy_city_custom', $data, array(
                'id' => $id
            ));
            load()->func("file");
            file_delete($_GPC['thumb_old']);
        } else {
            pdo_insert("enjoy_city_custom", $data);
            $id = pdo_insertid();
        }
        message("更新菜单成功！", $this->createWebUrl('custom', array(
            'op' => 'display'
        )), 'success');
    }
    include $this->template('custom');
} elseif ($operation=='delete') {
    $id = intval($_GPC['id']);
    $custom = pdo_fetch("SELECT id FROM " . tablename('enjoy_city_custom') . " WHERE id = '$id'");
    if (empty($custom)) {
        message('抱歉，菜单不存在或是已经被删除！', $this->createWebUrl('custom', array(
            'op' => 'display'
        )), 'error');
    }
    pdo_delete("enjoy_city_custom", array(
        "id" => $id
    ), 'OR');
    message("菜单删除成功！", $this->createWebUrl('custom', array(
        'op' => 'display'
    )), 'success');
}