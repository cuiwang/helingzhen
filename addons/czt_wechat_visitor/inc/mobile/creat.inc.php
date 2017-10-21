<?php
global $_W, $_GPC;
$settings = $this->module['config'];
if (empty($settings['topics'])) {
    message('请完成设置');
}
if ($settings['force_follow'] && empty($_W['fans']['follow'])) {
    header('Location: ' . $settings['guide']);
    exit;
}
$fan = get_fan_info();
if (!empty($_GPC['topic'])) {
    $data                = array();
    $data['uniacid']     = $_W['uniacid'];
    $data['create_time'] = TIMESTAMP;
    $data['topic']       = trim($_GPC['topic']);
    $data['fanid']       = $fan['fanid'];
    $ret                 = pdo_insert('czt_wechat_visitor_lists', $data);
    if (!empty($ret)) {
        $listid = pdo_insertid();
    } else {
        exit('pdo_insert error!');
    }
    header('Location:' . $this->createMobileUrl('index', array(
        'listid' => $listid
    ), true));
}
$topics = explode("\n", $settings['topics']);
array_walk($topics, create_function('&$item', '$item=trim($item);'));
include $this->template('creat');