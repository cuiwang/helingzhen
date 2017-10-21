<?php
global $_W, $_GPC;
$uniacid = $_W['uniacid'];
$active = 'quan';
$act = pdo_fetch("select * from " . tablename('enjoy_city_reply') . " where uniacid=" . $uniacid . "");
$firm[location_x] = empty($firm[location_x]) ? 0 : $firm[location_x];
$firm[location_y] = empty($firm[location_y]) ? 0 : $firm[location_y];
session_start();
$uid = $_SESSION['city']['uid'];
$fid = intval($_GPC['fid']);
$user_agent = $_SERVER['HTTP_USER_AGENT'];
if (empty($uid)) {
    if (strpos($user_agent, 'MicroMessenger')===false) {
        unset($_SESSION['city']);
        header("location:" . $this->createMobileUrl('login') . "");
    } else {
        $userlist = $this->auth();
        if (!empty($userlist)) {
            $username = empty($userlist['username']) ? $userlist['nickname'] : $userlist['username'];
            $_SESSION['city']['username'] = $username;
            $_SESSION['city']['openid'] = $userlist['openid'];
            $_SESSION['city']['uid'] = $userlist['uid'];
            $uid = $userlist['uid'];
        } else {
            $this->newmessage('非法登录', $this->createMobileUrl('login'));
            exit();
        }
    }
}
if (strpos($user_agent, 'MicroMessenger')===false) {
    $w_login = 0;
} else {
    $w_login = 1;
    $userinfo = mc_oauth_userinfo();
    $ouid = pdo_fetchcolumn("select uid from " . tablename('enjoy_city_fans') . " where openid='" . $userinfo[openid] . "'");
    if ($ouid==$uid) {
        $w_pay = 1;
    } else {
        if ($ouid) {
            $w_pay = 0;
        } else {
            $w_pay = 0;
            $w_bang = 1;
        }
    }
}
if (empty($uid)) {
    header("location:" . $this->createMobileUrl('login') . "");
}
$sellers = pdo_fetchall("select * from " . tablename('enjoy_city_seller') . " where uniacid=" . $uniacid . "");
$flag = trim($_GPC['flag']);
if ($flag=='submit') {
    if (!empty($fid)) {
        $arr = "&fid=" . $fid;
    }
    if (empty($uid)) {
        $res['status'] = 0;
        $res['res'] = '请先登录';
        $res['href'] = $this->createMobileUrl('login');
    } else {
        $data = array(
            'uniacid' => $_W['uniacid'],
            'title' => $_GPC['title'],
            'province' => $_GPC['province'],
            'city' => $_GPC['city'],
            'district' => $_GPC['district'],
            'address' => $_GPC['address'],
            'location_x' => $_GPC['location_x'],
            'location_y' => $_GPC['location_y'],
            'intro' => $_GPC['intro'],
            'tel' => $_GPC['tel'],
            'ischeck' => $_GPC['ischeck'],
            'ismoney' => $_GPC['ismoney'],
            'ispay' => 0,
            'paymoney' => $act['fee'],
            'sid' => $_GPC['sid'],
            'parentid' => $_GPC['parentid'],
            'childid' => $_GPC['childid'],
            'wei_num' => $_GPC['wei_num'],
            'wei_sex' => $_GPC['wei_sex'],
            'wei_name' => $_GPC['wei_name'],
            'wei_intro' => $_GPC['wei_intro'],
            'icon' => $_GPC['icon'],
            'img' => $_GPC['img'],
            'wei_avatar' => $_GPC['wei_avatar'],
            'wei_ewm' => $_GPC['wei_ewm'],
            's_name' => $_GPC['s_name'],
            'breaks' => trim($_GPC['breaks']),
            'firmurl' => trim($_GPC['firmurl']),
            'custom' => trim($_GPC['custom']),
            'cflag' => 1,
            'uid' => $uid
        );
        if ($fid > 0) {
            pdo_update('enjoy_city_firm', $data, array(
                'id' => $fid
            ));
            $res['status'] = 1;
            $res['res'] = '更新店铺成功';
            $res['href'] = $this->createMobileUrl('bdetail');
        } else {
            $data['createtime'] = TIMESTAMP;
            $data['stime'] = date('Y-m-d H:i:s', TIMESTAMP);
            $data['etime'] = date('Y-m-d H:i:s', (TIMESTAMP + 365 * 24 * 60 * 60));
            pdo_insert("enjoy_city_firm", $data);
            $newfid = pdo_insertid();
            $message = "有新商户入驻啦，点击审核";
            require_once MB_ROOT . "/controller/weixin.class.php";
            $url = $this->str_murl($this->createMobileUrl("firm", array(
                'fid' => $newfid
            )));
            $config = $this->module['config']['api'];
            $now = date('Y-m-d H:i:s', TIMESTAMP);
            $template = array(
                'touser' => $config['admin'],
                'template_id' => $config['mid'],
                'url' => $url,
                'topcolor' => '#743a3a',
                'data' => array(
                    'first' => array(
                        'value' => urlencode('有新商户入驻啦'),
                        'color' => '#007aff'
                    ),
                    'keyword1' => array(
                        'value' => urlencode($data['title']),
                        'color' => '#007aff'
                    ),
                    'keyword2' => array(
                        'value' => urlencode($data['s_name']),
                        'color' => '#007aff'
                    ),
                    'keyword3' => array(
                        'value' => urlencode($act['fee'] . '元'),
                        'color' => '#007aff'
                    ),
                    'keyword4' => array(
                        'value' => urlencode('未支付'),
                        'color' => '#007aff'
                    ),
                    'keyword5' => array(
                        'value' => urlencode($now),
                        'color' => '#007aff'
                    ),
                    'remark' => array(
                        'value' => urlencode($message),
                        'color' => '#007aff'
                    )
                )
            );
            $api = $this->module['config']['api'];
            $weixin = new class_weixin($_W['account']['key'], $_W['account']['secret']);
            $weixin->send_template_message(urldecode(json_encode($template)));
            $res['status'] = 1;
            $res['res'] = '新增店铺成功';
            $res['href'] = $this->createMobileUrl('kfewm');
        }
    }
    echo json_encode($res);
    exit();
}
$pids = pdo_fetchall("select id,name as text from " . tablename('enjoy_city_kind') . " where uniacid=" . $uniacid . " and parentid=0 and (wurl='' or wurl is null) order by hot asc");
for ($i = 0; $i < count($pids); $i++) {
    $pids[$i]['value'] = $pids[$i]['id'];
    $pids[$i]['children'] = pdo_fetchall("select id,name as text from " . tablename('enjoy_city_kind') . " where uniacid=" . $uniacid . " and parentid=" . $pids[$i]['id'] . "
        and (wurl='' or wurl is null)");
    for ($j = 0; $j < count($pids[$i]['children']); $j++) {
        $pids[$i]['children'][$j]['value'] = $pids[$i]['children'][$j]['id'];
    }
}
$catdata = json_encode($pids);
if ($fid > 0) {
    $firm = pdo_fetch("select * from " . tablename('enjoy_city_firm') . " where uniacid=" . $uniacid . " and
        id=" . $fid . "");
}
$sharelink = $_W['siteroot'] . "app/" . $this->createMobileUrl('entry');
$sharetitle = $act['mshare_title'];
$sharecontent = $act['mshare_content'];
$shareicon = $act['mshare_icon'];
include $this->template('addb');