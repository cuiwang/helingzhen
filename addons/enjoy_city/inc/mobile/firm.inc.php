<?php
global $_W, $_GPC;
$active = '';
$fid = intval($_GPC['fid']);
$uniacid = $_W['uniacid'];
$fip = trim($_GPC['fip']);
$ip = CLIENT_IP;
$now = TIMESTAMP;
$userinfo = mc_oauth_userinfo();
$follow = intval($_W['fans']['follow']);
$openid = $userinfo['openid'];
$exist = pdo_tableexists('modules_newmean');
if (!$exist) {
    exit();
}
if ($fip!=$ip && (!empty($fip))) {
    $ctime = pdo_fetchcolumn("select createtime from " . tablename('enjoy_city_forward') . " where uniacid=" . $uniacid . "
         and ip='" . $ip . "' and fid=" . $fid . " order by createtime desc limit 1");
    if (empty($ctime)) {
        $data = array(
            'uniacid' => $uniacid,
            'fid' => $fid,
            'ip' => $ip,
            'createtime' => TIMESTAMP
        );
        $resf = pdo_insert('enjoy_city_forward', $data);
    } else {
        $temp = $now - $ctime;
        if ($temp > 24 * 60 * 60) {
            $data = array(
                'uniacid' => $uniacid,
                'fid' => $fid,
                'ip' => $ip,
                'createtime' => TIMESTAMP
            );
            $resf = pdo_insert('enjoy_city_forward', $data);
        }
    }
    if ($resf > 0) {
        pdo_query("update " . tablename('enjoy_city_firm') . " set forward=forward+1 where uniacid=" . $uniacid . "
            and id=" . $fid . "");
    }
}
session_start();
$act = pdo_fetch("select * from " . tablename('enjoy_city_reply') . " where uniacid=" . $uniacid . "");
$firm = pdo_fetch("select * from " . tablename('enjoy_city_firm') . " where uniacid=" . $uniacid . " and id=" . $fid . "");
$config = $this->module['config']['api'];
if ($userinfo['openid']==$config['admin']) {
} else {
    if ($firm['ischeck']==0) {
        $this->newmessage($firm[title] . '商户还未通过审核，请等待', $this->createMobileUrl('entry'));
    } elseif (strtotime($firm['etime']) < TIMESTAMP) {
        $this->newmessage($firm[title] . '商户有效期已过，请联系管理员', $this->createMobileUrl('entry'));
    }
}
$act = pdo_fetch("select * from " . tablename('enjoy_city_reply') . " where uniacid=" . $uniacid . "");
$firm['province'] = empty($firm['province']) ? $act['province'] : $firm['province'];
$firm['city'] = empty($firm['city']) ? $act['city'] : $firm['city'];
$firm['district'] = empty($firm['district']) ? $act['district'] : $firm['district'];
$fname = pdo_fetchcolumn("select name from " . tablename('enjoy_city_kind') . " where uniacid=" . $uniacid . " and id=" . $firm['parentid'] . "");
$tj = pdo_fetchall("select * from " . tablename('enjoy_city_firm') . " where uniacid=" . $uniacid . " and parentid=" . $firm['parentid'] . "
    and id!=" . $firm[id] . " and ischeck=1 order by hot desc limit 30");
$fimg = pdo_fetchall("select * from " . tablename('enjoy_city_fimg') . " where fid=" . $fid . " and uniacid=" . $uniacid . "");
$uid = $_SESSION['city']['uid'];
$firmfans = pdo_fetchall("select favatar,fnickname from " . tablename('enjoy_city_firmfans') . " where fid=" . $fid . "
    and uniacid=" . $uniacid . " and flag=1");
$firmfanscount = pdo_fetchcolumn("select count(*) from " . tablename('enjoy_city_firmfans') . " where fid=" . $fid . "
    and uniacid=" . $uniacid . " and flag=1");
$isfans = pdo_fetchcolumn("select count(*) from " . tablename('enjoy_city_firmfans') . " where fid=" . $fid . " and
     openid='" . $openid . "' and flag=1");
pdo_query("update " . tablename("enjoy_city_firm") . " set browse=browse+1 where uniacid=" . $uniacid . " and id=" . $fid . "");
$tags = pdo_fetchall("select * from " . tablename('enjoy_city_firmlabel') . " where uniacid=" . $uniacid . " 
    and fid=" . $fid . " and checked=1 order by times desc limit 10");
for ($i = 0; $i < count($tags); $i++) {
    $tags[$i]['flag'] = pdo_fetchcolumn("select flag from " . tablename('enjoy_city_taglap') . " where uniacid=" . $uniacid . "
        and openid='" . $openid . "' and tagid=" . $tags[$i]['id'] . "");
    $tags[$i]['flag'] = intval($tags[$i]['flag']);
}
$openurl = $_W['siteroot'] . 'app/index.php?c=entry&do=firm&m=enjoy_city&i=' . $_W['uniacid'];
$url_info = urldecode($openurl);
$firm['qrurl'] = urlencode($url_info . "&fid=" . $fid);
$joblist = pdo_fetchall("select * from " . tablename('enjoy_city_job') . " where uniacid=" . $uniacid . " and fid=" . $fid . "
    and ischeck=1 and isend=0");
$fopenid = pdo_fetchcolumn("select openid from " . tablename('enjoy_city_fans') . " where uid=" . $firm[uid] . " and 
uniacid=" . $uniacid . "");
$op = $_GPC['op'];
if ($op=='ischeck') {
    $checked = $_GPC['checked'];
    pdo_update("enjoy_city_firm", array(
        "ischeck" => $checked
    ), array(
        'uniacid' => $uniacid,
        'id' => $fid
    ));
    if ($checked==1) {
        if (!empty($fopenid)) {
            $message = "恭喜,您的店铺通过审核了，请尽快完善店铺信息，以获取更多的展示效果";
            require_once MB_ROOT . "/controller/weixin.class.php";
            $url = $this->str_murl($this->createMobileUrl("firm", array(
                'fid' => $fid
            )));
            $config = $this->module['config']['api'];
            $now = date('Y-m-d', TIMESTAMP);
            $template = array(
                'touser' => $fopenid,
                'template_id' => $config['mid2'],
                'url' => $url,
                'topcolor' => '#743a3a',
                'data' => array(
                    'first' => array(
                        'value' => urlencode('通过审核，请尽快完善店铺信息'),
                        'color' => '#007aff'
                    ),
                    'keyword1' => array(
                        'value' => urlencode($firm['title']),
                        'color' => '#007aff'
                    ),
                    'keyword2' => array(
                        'value' => urlencode('通过'),
                        'color' => '#007aff'
                    ),
                    'keyword3' => array(
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
        }
        $this->newmessage('通过审核', $this->createMobileUrl('firm', array(
            'fid' => $fid
        )));
    } elseif ($checked==0) {
        $this->newmessage('店铺已下架', $this->createMobileUrl('firm', array(
            'fid' => $fid
        )));
    }
}
$sharelink = $_W['siteroot'] . "app/" . $this->createMobileUrl('firm', array(
    'fid' => $fid,
    'fip' => $ip
));
$firm['icon'] = empty($firm['icon']) ? $act['icon'] : $firm['icon'];
$shareicon = $this->shareth1($act['share_icon'], $firm['icon']);
$sharetitle = $this->shareth($act['share_title'], $firm['title']);
$sharecontent = $this->shareth($act['share_content'], $firm['title']);
include $this->template('firm');