<?php
global $_GPC,$_W;
// $this->checkFollow;
// $this->checkBowser;
$rid = intval($_GPC['id']);

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
    $sex = $cookie['sex'];
}else{
    $from_user = $_W['fans']['from_user'];
    $avatar = $_W['fans']['tag']['avatar'];
    $sex = $_W['fans']['tag']['sex'];
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
        $sex = $userinfo['sex'];
    } else {
        $avatar = $cookie['avatar'];
        $nickname = $cookie['nickname'];
        $from_user = $cookie['openid'];
        $sex = $cookie['sex'];
    }
}
$sex = empty($sex)?'0':$sex;
//网页授权借用结束

$page_from_user = base64_encode(authcode($from_user, 'ENCODE'));


$reply = pdo_fetch( " SELECT * FROM ".tablename('haoman_dpm_reply')." WHERE rid='".$rid."' " );

$num1 = pdo_fetchcolumn("select count(id) from " . tablename('haoman_dpm_fans') . "  where rid = :rid and uniacid=:uniacid", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));

if($num1>=$reply['bm_pnumber']&&$reply['bm_pnumber']!=0&&$reply['isbaoming']==1){
    message('您好，报名人数已经满了，下次再来吧！!', '', 'error');
    exit();
}
$xysreply = pdo_fetch( " SELECT is_pair,is_sex,is_pair,is_turntable FROM ".tablename('haoman_dpm_xysreply')." WHERE rid='".$rid."' " );
if($xysreply['is_sex']==1){
    $is_sex =1;
}
// $fans = pdo_fetch("select * from " . tablename('haoman_dpm_fans') . " where rid = '" . $rid . "' and from_user='" . $from_user . "'");
//分享信息
$sharelink = $_W['siteroot'] . 'app/' . $this->createMobileUrl('share', array('rid' => $rid, 'from_user' => $page_from_user));
$sharetitle = empty($reply['share_title']) ? '一起来报名参加活动吧，还能有大奖!' : $reply['share_title'];
$sharedesc = empty($reply['share_desc']) ? '亲，一起来报名参加活动吧，还能有大奖！！' : str_replace("\r\n", " ", $reply['share_desc']);
if (!empty($reply['share_imgurl'])) {
    $shareimg = toimage($reply['share_imgurl']);
} else {
    $shareimg = toimage($reply['picture']);
}

$jssdk = new JSSDK();
$package = $jssdk->GetSignPackage();

if(empty($reply['mobpicurl'])){
    $bg = "../addons/haoman_dpm/mobimg/bg.jpg";
}else{
    $bg = tomedia($reply['mobpicurl']);
}

include $this->template('mob_bm');