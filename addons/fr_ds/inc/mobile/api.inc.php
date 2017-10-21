<?php
// +----------------------------------------------------------------------
// | Author: 微赞 
// +----------------------------------------------------------------------
defined('IN_IA') or exit('Access Denied');
include MODULE_ROOT . '/inc/common.php';
$uniacid = $_W["uniacid"];
$openid = $_W['openid'];
$title = "";
$referer = $_SERVER['HTTP_REFERER'];
$referer_parse = parse_url($referer);

if (empty($referer) || $referer_parse['host'] != $_SERVER['HTTP_HOST'] || empty($_GPC['sign']) || empty($_GPC['module_name']) || Agent::isMicroMessage() == Agent::MICRO_MESSAGE_NOT || ($this->module['config']['payment_method'] == 0 && !empty($_W['account']['oauth']) && $_W['account']['oauth']['level'] != '4')) {
    die();//'不允许跨域名访问'
}
$act = $_GPC['act'];
$skin = $_GPC['skin'] ? trim($_GPC['skin']) : 'default';
$style_url = get_style_url($skin);
$result = get_url_params($referer);

$result['skin'] = $skin;
$result['openid'] = $_W['openid'];
$result['style_url'] = $style_url;
$result['module_name'] = trim($_GPC['module_name']);
$result['referer'] = $referer;
$result['sign'] = md5($_GPC['sign'] . $result['module_name']);
$result['title'] = isset($_GPC['title']) ? urldecode(trim($_GPC['title'])) : (empty($result['title']) ? '' : $result['title']);
$result['sub_title'] = isset($_GPC['sub_title']) ? urldecode(trim($_GPC['sub_title'])) : (empty($result['sub_title']) ? '' : $result['sub_title']);
$result['author'] = isset($_GPC['author']) ? urldecode(trim($_GPC['author'])) : (empty($result['author']) ? $_W['account']['name'] : $result['author']);
$result['avatar'] = isset($_GPC['avatar']) ? urldecode(trim($_GPC['avatar'])) : (empty($result['avatar']) ? __IMG__ . '/100.gif' : $result['avatar']);
if ($act == 'check') {
    $res = getRow('record', " AND status = 1 AND sign = '{$result['sign']}' AND openid = {$_W['openid']}");
    if (empty($res)) {
        echo('no');
    }else{
        echo('yes');
    }
    exit();
}
$_SESSION['__fr_ds_session'] = iserializer($result);
$list = getPageList('record', 1, " AND status = 1 AND sign = '{$result['sign']}'", ' id DESC', 18);
if ($list['list']) {
    foreach ($list['list'] as $key => $value) {
        $list['list'][$key]['avatar'] = getMemberAvatar($value['openid']);
    }
}
include $this->template('api');