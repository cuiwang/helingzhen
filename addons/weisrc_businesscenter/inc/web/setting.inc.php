<?php
global $_W, $_GPC;
load()->func('tpl');
$action = 'setting';
$weid = $_W['uniacid'];

$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_setting) . " WHERE weid = :weid ", array(':weid' => $_W['uniacid']));

if (!empty($setting)) {
    $share_image = tomedia($setting['share_image']);
    $logo = tomedia($setting['logo']);
}

if (checksubmit('submit')) {
    $data = array(
        'weid' => $_W['uniacid'],
        'title' => trim($_GPC['title']),
        'announcement' => trim($_GPC['announcement']),
        'tel' => trim($_GPC['tel']),
        'address' => trim($_GPC['address']),
        'location_p' => trim($_GPC['location_p']),
        'location_c' => trim($_GPC['location_c']),
        'location_a' => trim($_GPC['location_a']),
        'place' => trim($_GPC['place']),
        'lng' => trim($_GPC['baidumap']['lng']),
        'lat' => trim($_GPC['baidumap']['lat']),
        'pagesize' => intval($_GPC['pagesize']),
        'topcolor' => trim($_GPC['topcolor']),
        'topbgcolor' => trim($_GPC['topbgcolor']),
        'announcebordercolor' => trim($_GPC['announcebordercolor']),
        'announcebgcolor' => trim($_GPC['announcebgcolor']),
        'announcecolor' => trim($_GPC['announcecolor']),
        'storestitlecolor' => trim($_GPC['storestitlecolor']),
        'storesstatuscolor' => trim($_GPC['storesstatuscolor']),
        'showcity' => intval($_GPC['showcity']),
        'settled' => intval($_GPC['settled']),
        'dateline' => TIMESTAM,
        'feedback_show_enable' => intval($_GPC['feedback_show_enable']),
        'feedback_check_enable' => intval($_GPC['feedback_check_enable']),
        'scroll_announce_enable' => intval($_GPC['scroll_announce_enable']),
        'scroll_announce' => trim($_GPC['scroll_announce']),
        'scroll_announce_link' => trim($_GPC['scroll_announce_link']),
        'scroll_announce_speed' => intval($_GPC['scroll_announce_speed']),
        'copyright' => trim($_GPC['copyright']),
        'copyright_link' => trim($_GPC['copyright_link']),
        'menuname1' => trim($_GPC['menuname1']),
        'menulink1' => trim($_GPC['menulink1']),
        'menuname2' => trim($_GPC['menuname2']),
        'menulink2' => trim($_GPC['menulink2']),
        'menuname3' => trim($_GPC['menuname3']),
        'menulink3' => trim($_GPC['menulink3']),
        'appid' => trim($_GPC['appid']),
        'secret' => trim($_GPC['secret']),
        'statistics' => trim($_GPC['statistics']),
        'share_title' => $_GPC['share_title'],
        'share_desc' => $_GPC['share_desc'],
        'share_cancel' => $_GPC['share_cancel'],
        'share_url' => $_GPC['share_url'],
        'follow_url' => $_GPC['follow_url'],
        'share_image' => trim($_GPC['share_image']),
    );

    if (!empty($_GPC['share_image'])) {
        $data['share_image'] = $_GPC['share_image'];
        load()->func('file');

    }

    if (empty($setting)) {
        pdo_insert($this->table_setting, $data);
    } else {
        unset($data['dateline']);
        pdo_update($this->table_setting, $data, array('weid' => $_W['uniacid']));
    }
    message('操作成功', $this->createWebUrl('setting'), 'success');
}
include $this->template('setting');