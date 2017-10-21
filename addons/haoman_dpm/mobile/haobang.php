<?php
global $_GPC, $_W;
$rid = intval($_GPC['rid']);
$uniacid = $_W['uniacid'];
$type = $_GPC['type'];


$user_agent = $_SERVER['HTTP_USER_AGENT'];
if (strpos($user_agent, 'MicroMessenger') === false) {

    header("HTTP/1.1 301 Moved Permanently");
    header("Location: {$this->createMobileUrl('other',array('type'=>1,'id'=>$rid))}");
    exit();
}


//网页授权借用开始

load()->model('account');
$_W['account'] = account_fetch($_W['acid']);
$cookieid = '__cookie_haoman_dpm_201606186_' . $rid;
$cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
if ($_W['account']['level'] != 4) {
    $from_user = $cookie['openid'];
    $avatar = $cookie['avatar'];
    $nickname = $cookie['nickname'];
}else{
    $from_user = $_W['fans']['from_user'];
    $avatar = $_W['fans']['tag']['avatar'];
    $nickname = $_W['fans']['nickname'];
}

$code = $_GPC['code'];
$urltype = '';
if (empty($from_user) || empty($avatar) || empty($nickname)) {
    if (!is_array($cookie) || !isset($cookie['avatar']) || !isset($cookie['openid']) || !isset($cookie['nickname'])) {
        $userinfo = $this->get_UserInfo($rid, $code, $urltype);
        $nickname = $userinfo['nickname'];
        $avatar = $userinfo['headimgurl'];
        $from_user = $userinfo['openid'];
    } else {
        $avatar = $cookie['avatar'];
        $nickname = $cookie['nickname'];
        $from_user = $cookie['openid'];
    }
}

//网页授权借用结束

$page_from_user = base64_encode(authcode($from_user, 'ENCODE'));



$reply = pdo_fetch("select * from " . tablename('haoman_dpm_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));

if (empty($reply)) {
    message('非法访问，请重新发送消息进入活动页面！');
}

//检测是否为空
$fans = pdo_fetch("select * from " . tablename('haoman_dpm_fans') . " where rid = '" . $rid . "' and from_user='" . $from_user . "'");
if ($fans == false) {
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: " . $this->createMobileUrl('information', array('id' => $rid,'from_user'=>$page_from_user)) . "");
    exit();
}
$params = array(':rid' => $rid, ':uniacid' => $_W['uniacid']);
if($type=="today"){
$t = time();
$start = mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t));
$end = mktime(23,59,59,date("m",$t),date("d",$t),date("Y",$t));


$where.=' and createtime>=:createtime1 and createtime<=:createtime2 ';

$params[':createtime1'] = $start;
$params[':createtime2'] = $end;

}

$datas = pdo_fetchall("SELECT avatar,nickname,sum(pay_total)as total FROM " . tablename('haoman_dpm_pay_order') . " WHERE uniacid = :uniacid and rid =:rid AND status= 2 AND pay_type != 0 " . $where . " GROUP BY from_user  ORDER BY total DESC ", $params);



$arr = array_slice($datas,0,3);


$arr2 = array_slice($datas,3);

//分享信息
$sharelink = $_W['siteroot'] . 'app/' . $this->createMobileUrl('share', array('rid' => $rid, 'from_user' => $page_from_user));
$sharetitle = empty($reply['share_title']) ? '一起来聊一聊抽奖吧!' : $reply['share_title'];
$sharedesc = empty($reply['share_desc']) ? '亲，一起来聊一聊吧，赢大奖哦！！' : str_replace("\r\n", " ", $reply['share_desc']);
if (!empty($reply['share_imgurl'])) {
    $shareimg = toimage($reply['share_imgurl']);
} else {
    $shareimg = toimage($reply['picture']);
}

$jssdk = new JSSDK();
$package = $jssdk->GetSignPackage();
include $this->template('mob_haobang');