<?php
global $_W, $_GPC;
load()->func("tpl");
$uniacid = $_W['uniacid'];
$serverip = $this->getServerIP();
$item = pdo_fetch("select * from " . tablename('enjoy_city_reply') . " where uniacid=" . $uniacid . "");
$item['title'] = empty($item['title']) ? '微城市' : $item['title'];
$item['share_title'] = empty($item['share_title']) ? '#firm#强势入驻微城市' : $item['share_title'];
$item['share_content'] = empty($item['share_content']) ? '我向你推荐#firm#，快来看看吧' : $item['share_content'];
$item['mshare_title'] = empty($item['mshare_title']) ? '微城市无所不查，邀您入驻' : $item['mshare_title'];
$item['mshare_content'] = empty($item['mshare_content']) ? '一个神奇的网站' : $item['mshare_content'];
$item['jointel'] = empty($item['jointel']) ? '每日查询500次，加盟联系：#tel#' : $item['jointel'];
$item['share_icon'] = empty($item['share_icon']) ? '#firmlogo#' : $item['share_icon'];
$item['province'] = empty($item['province']) ? '北京' : $item['province'];
$item['city'] = empty($item['city']) ? '北京市' : $item['city'];
$item['district'] = empty($item['district']) ? '海淀区' : $item['district'];
$item['slogo'] = empty($item['slogo']) ? './addons/enjoy_city/public/images/slogo.png' : $item['slogo'];
$item['banner'] = empty($item['banner']) ? './addons/enjoy_city/public/images/banner.jpg' : $item['banner'];
$item['wtt'] = empty($item['wtt']) ? './addons/enjoy_city/public/images/weitoutiao.png' : $item['wtt'];
$item['custimg'] = empty($item['custimg']) ? './addons/enjoy_city/public/images/yh.jpg' : $item['custimg'];
$basic = pdo_fetch("select * from " . tablename('enjoy_city_reply') . " where uniacid=0");
if (checksubmit("submit")) {
    $exist = pdo_fetchcolumn("select count(*) from " . tablename('enjoy_city_reply') . " where uniacid=" . $uniacid . "");
    $data = array(
        'uniacid' => $uniacid,
        'title' => $_GPC['title'],
        'ewm' => trim($_GPC['ewm']),
        'icon' => trim($_GPC['icon']),
        'province' => trim($_GPC['province']),
        'city' => trim($_GPC['city']),
        'district' => trim($_GPC['district']),
        'tel' => trim($_GPC['tel']),
        'copyright' => trim($_GPC['copyright']),
        'slogo' => trim($_GPC['slogo']),
        'banner' => trim($_GPC['banner']),
        'sucai' => $_GPC['sucai'],
        'weixin' => trim($_GPC['weixin']),
        'share_icon' => $_GPC['share_icon'],
        'share_title' => $_GPC['share_title'],
        'share_content' => $_GPC['share_content'],
        'mshare_icon' => $_GPC['mshare_icon'],
        'mshare_title' => $_GPC['mshare_title'],
        'mshare_content' => $_GPC['mshare_content'],
        'jointel' => $_GPC['jointel'],
        'agreement' => $_GPC['agreement'],
        'fee' => $_GPC['fee'],
        'bonus' => trim($_GPC['bonus']),
        'kfewm' => $_GPC['kfewm'],
        'onlinepay' => $_GPC['onlinepay'],
        'isright' => $_GPC['isright'],
        'issmple' => $_GPC['issmple'],
        'custimg' => $_GPC['custimg'],
        'custurl' => $_GPC['custurl'],
        'custimg1' => $_GPC['custimg1'],
        'custurl1' => $_GPC['custurl1'],
        'custimg2' => $_GPC['custimg2'],
        'custurl2' => $_GPC['custurl2'],
        'wtt' => $_GPC['wtt'],
        'isjob' => $_GPC['isjob'],
        'weurl' => $_GPC['weurl'],
        'wstyle' => $_GPC['wstyle'],
        'ispayfirst' => $_GPC['ispayfirst'],
        'createtime' => TIMESTAMP
    );
    if ($exist > 0) {
        $res = pdo_update('enjoy_city_reply', $data, array(
            'uniacid' => $uniacid
        ));
        $message = "更新代理成功";
    } else {
        $res = pdo_insert('enjoy_city_reply', $data);
        $message = "新增代理成功";
    }
    if ($res==1) {
        message($message, $this->createWebUrl('basic'), 'success');
    }
}
include $this->template('basic');