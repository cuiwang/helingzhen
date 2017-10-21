<?php
/**
 * 悟空源 码论 坛bbs . xte c.cc
 *
 * @author 微赞
 * @url
 */
function superman_format_price($price, $showcut = false) {
    if ($showcut && $price > 10000) {
        $price = $price / 10000;
        $price .= '万';
    }
    return str_replace('.00', '', $price);
}
function superman_attachment_root()
{
    global $_W;
    if (!defined('ATTACHMENT_ROOT')) {
        $path = IA_ROOT . '/attachment/';
        define('ATTACHMENT_ROOT', $path);
        return $path;
    }
    if (substr(ATTACHMENT_ROOT, -1, 1) != '/') {
        return ATTACHMENT_ROOT . '/';
    }
    return ATTACHMENT_ROOT;
}
function superman_img_placeholder($returnsrc = true)
{
    global $_W;
    $src = $_W['siteroot'] . "addons/superman_creditmall/template/mobile/images/placeholder.gif";
    if ($returnsrc) {
        return $src;
    } else {
        return "<img src='{$src}'/>";
    }
}
function superman_hide_mobile($mobile)
{
    return preg_replace('/(\d{3})(\d{4})/', "$1****", $mobile);
}
function superman_hide_nickname($nickname, $suffix = '**')
{
    return $nickname == '' ? '***' : cutstr($nickname, 1) . $suffix;
}
function superman_random_float($min = 0, $max = 1)
{
    return $min + mt_rand() / mt_getrandmax() * ($max - $min);
}
function superman_is_redpack($type)
{
    return in_array($type, array(
        5,
        6
    ));
}
function superman_is_virtual($product)
{
    if ($product['isvirtual'] == 1) {
        return true;
    }
    return false;
}
function superman_task_url($task)
{
    global $_W, $_GPC;
    $url = '';
    if ($task['name'] == 'superman_sign') {
        if (!$task['extend']['rid']) {
            return ERRNO::TASK_NOT_BINDING;
        }
        $url = $_W['siteroot'] . 'app/' . $task['url'] . '&rid=' . $task['extend']['rid'] . '&__from=superman_creditmall';
    } else if ($task['name'] == 'superman_house') {
        if (!$task['extend']['houseid']) {
            return ERRNO::TASK_NOT_BINDING;
        }
        $url = $_W['siteroot'] . 'app/' . $task['url'] . '&id=' . $task['extend']['houseid'] . '&__from=superman_creditmall';
    } else if ($task['name'] == 'superman_creditmall_task6') {
        $url = $_W['account']['subscribeurl'];
    } else if ($task['builtin']) {
        $url = $_W['siteroot'] . 'app/' . $task['url'] . '&__from=superman_creditmall';
    } else if ($task['url']) {
        $url = $_W['siteroot'] . 'app/' . $task['url'] . '&__from=superman_creditmall';
    } else {
        $url = $_W['siteroot'] . 'app/' . murl('entry//task', array(
            'type' => $task['type'],
            'm' => 'superman_creditmall',
            '__from' => 'superman_creditmall'
        ));
    }
    return $url;
}
function superman_fix_path($path)
{
    global $_W;
    $path = strpos($path, 'http://') !== false || strpos($path, 'https://') !== false ? str_replace($_W['attachurl'], '', $path) : $path;
    $path = strpos($path, 'http://') !== false || strpos($path, 'https://') !== false ? str_replace($_W['siteroot'], '', $path) : $path;
    return $path;
}
function superman_format_countdown($starttime)
{
    $dd        = $hh = $mm = $ss = 0;
    $starttime = $starttime * 1000;
    $nowtime   = superman_get_millisecond();
    if ($starttime > $nowtime) {
        $ts = $starttime - $nowtime;
        $dd = intval($ts / 1000 / 60 / 60 / 24);
        $hh = intval($ts / 1000 / 60 / 60 % 24);
        $mm = intval($ts / 1000 / 60 % 60);
        $ss = intval($ts / 1000 % 60);
        $dd = $dd > 1 && $dd < 10 ? "0$dd" : $dd;
        $hh = $hh > 1 && $hh < 10 ? "0$hh" : $hh;
        $mm = $mm > 1 && $mm < 10 ? "0$mm" : $mm;
        $ss = $ss > 1 && $ss < 10 ? "0$ss" : $ss;
    }
    return $dd . '天' . $hh . '时' . $mm . '分' . $ss . '秒';
}
function superman_get_millisecond()
{
    list($s1, $s2) = explode(' ', microtime());
    return (float) sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
}
function superman_uid2openid($uid)
{
    $fans = mc_fansinfo($uid);
    return $fans && $fans['openid'] ? $fans['openid'] : '';
}
function superman_qrcode_png($text, $outfile = false, $level = QR_ECLEVEL_L, $size = 10, $margin = 4, $saveandprint = false)
{
    include_once(IA_ROOT . '/framework/library/qrcode/phpqrcode.php');
    QRcode::png($text, $outfile, $level, $size, $margin, $saveandprint);
}
function superman_cover_share_filename($filename)
{
    if ($filename == '' || strpos($filename, '.') === false) {
        return '';
    }
    $arr = explode('.', $filename);
    return $arr[0] . '_share.' . $arr[1];
}
function superman_get_location_by_ip($IP)
{
    load()->func('communication');
    $loc    = '';
    $url    = 'http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip=' . $IP;
    $result = ihttp_get($url);
    if ($result['status'] == 'OK') {
        $location = json_decode($result['content']);
        $loc      = $location->country . ' ' . $location->province . ' ' . $location->city;
    }
    return $loc;
}
