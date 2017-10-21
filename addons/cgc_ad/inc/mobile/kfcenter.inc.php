<?php

global $_W, $_GPC;

$weid = $_W['uniacid'];
$quan_id = intval($_GPC['quan_id']);
$member = $this->get_member();
$from_user = $member['openid'];
$quan = $this->get_quan();
$mid = $member['id'];
$config = $this->settings;
$op = empty($_GPC['op']) ? "display" : $_GPC['op'];
$weid = $_W['uniacid'];
$adv = $this->get_adv();

if (empty($member['is_kf'])) {
    $this->returnError("没权限");
}

if ($_GPC['dopost'] == 'top') {
    $top_level = $_GPC['status'];
    if ($top_level == 1) {
        $top_level = $adv['total_amount'] * 100;
    }
    $ret = pdo_update('cgc_ad_adv', array('top_level' => $top_level), array('id' => $_GPC['id'], 'weid' => $weid, 'quan_id' => $_GPC['quan_id']));

    message('操作成功', $this->createMobileUrl('detail', array('id' => $_GPC['id'], 'quan_id' => $_GPC['quan_id'])), 'success');
} else if ($_GPC['dopost'] == 'bad') {
    pdo_update('cgc_ad_adv', array('status' => '2'), array('id' => $_GPC['id'], 'weid' => $weid, 'quan_id' => $_GPC['quan_id']));
    message('操作成功', $this->createMobileUrl('index', array('quan_id' => $_GPC['quan_id'])), 'success');
} else if ($_GPC['dopost'] == 'is_open') {
    pdo_update('cgc_ad_adv', array('is_open' => $_GPC['status']), array('id' => $_GPC['id'], 'weid' => $weid, 'quan_id' => $_GPC['quan_id']));
    message('操作成功', $this->createMobileUrl('detail', array('id' => $_GPC['id'], 'quan_id' => $_GPC['quan_id'])), 'success');
} else if ($_GPC['dopost'] == 'bat_send') {
    $siteroot = $_W['siteroot'];
    $_userlist = pdo_fetchall('SELECT openid FROM ' . tablename('cgc_ad_member') . " where weid=" . $weid . " and quan_id={$quan['id']} and type=1 and message_notify =1 and status=1");
    $_url = $siteroot . 'app/' . substr($this->createMobileUrl('foo', array('form' => 'detail', 'quan_id' => $adv['quan_id'], 'id' => $adv['id'], 'model' => $adv['model'])), 2);
    $htt = str_replace('"', "'", htmlspecialchars_decode($config['tuisong']));
    //红包通知信息更换 by 20170212
    $msg = str_replace('{num}', $adv['total_num'], $config['tuisong']);
    $msg = str_replace('{money}', $adv['total_amount'], $msg);
    $msg = str_replace('{type}', $this->get_modelName($adv['model']), $msg);
    $_tdata = array(
        'first' => array('value' => '系统通知', 'color' => '#173177'),
        'keyword1' => array('value' => $msg, 'color' => '#173177'),
        'keyword2' => array('value' => date('Y-m-d H:i:s', time()), 'color' => '#173177'),
        'keyword3' => array('value' => '抢钱通知', 'color' => '#173177'),
        'remark' => array('value' => '点击详情进入', 'color' => '#173177'),
    );
    foreach ($_userlist as $key => $r) {
        if ($config['is_type'] == 1) {
            $a = sendTemplate_common($r['openid'], $config['template_id'], $_url, $_tdata);
        } else {
            $a = post_send_text($r['openid'], $htt);
        }
    }
    message('发送成功', $this->createMobileUrl('detail', array('id' => $_GPC['id'], 'quan_id' => $_GPC['quan_id'])), 'success');
}

