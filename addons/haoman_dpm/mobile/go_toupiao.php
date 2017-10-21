<?php
global $_GPC, $_W;
$rid = intval($_GPC['id']);
$uniacid = $_W['uniacid'];

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


if (empty($rid)) {
    message('抱歉，参数错误！', '', 'error');//调试代码
}

if (empty($from_user)) {
    message('抱歉，获取不到openid，请重新进入页面！', '', 'error');//调试代码
}
$reply = pdo_fetch("select * from " . tablename('haoman_dpm_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
$yyy = pdo_fetch("select isyyy from " . tablename('haoman_dpm_yyyreply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
$vote = pdo_fetch("select vote_status from " . tablename('haoman_dpm_newvote_set') . " where rid = :rid order by `id` asc", array(':rid' => $rid));
$photo = pdo_fetch("select is_phone from " . tablename('haoman_dpm_photo_setting') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
$punishment = pdo_fetch("select is_punishment from " . tablename('haoman_dpm_punishment') . " where rid = :rid and uniacid=:uniacid ", array(':rid'=>$rid,':uniacid'=>$_W['uniacid']));

if($vote['vote_status']==1){
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: " . $this->createMobileUrl('vote_option_list', array('id' => $rid)) . "");
}
if(ISCUSTOM == 1 && CUSTOM_VERSION == 'DS'){
    $dsreply = pdo_fetch("select * from " . tablename('haoman_dpm_ds_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
}
if(ISCUSTOM == 1 && CUSTOM_VERSION == 'ZNL'){
    $znlreply = pdo_fetch( " SELECT * FROM ".tablename('haoman_dpm_znl_reply')." WHERE rid='".$rid."' " );
}

if ($reply == false) {
    message('抱歉，活动已经结束，下次再来吧！', '', 'error');
}
//$t=time();
//$project = pdo_fetch("SELECT id,vote_name,status FROM " . tablename('haoman_dpm_newvote') . " WHERE uniacid=:uniacid AND rid = :rid and vote_status=1  and vote_starttime<:vote_starttime and vote_endtime >:vote_endtime order by vote_pid desc LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid,':vote_starttime'=>$t,':vote_endtime'=>$t));


if($reply['istoupiao']==1){
   message('抱歉，活动还未开启，请留意大屏幕！', '', 'success');
}
$toupiao = pdo_fetchall("select * from " . tablename('haoman_dpm_toupiao') . " where rid = '" . $rid . "' and uniacid = '" . $uniacid . "' and status=0 and vote_id=0  order by id desc , pid desc");

if($toupiao==false){
    message('抱歉，目前没有投票，下次再来吧！', '', 'error');
}

$fans = pdo_fetch("select * from " . tablename('haoman_dpm_fans') . " where rid = '" . $rid . "' and from_user='" . $from_user . "'");
if($fans==false){
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: " . $this->createMobileUrl('information', array('id' => $rid,'from_user'=>$page_from_user)) . "");
    exit();
}
$nowtime = mktime(0, 0, 0);

if ($fans['last_time'] < $nowtime) {

    $fans['tp_times'] = 0;

    $temp = pdo_update('haoman_dpm_fans', array('tp_times' =>$fans['tp_times'],'last_time' => $nowtime), array('id' => $fans['id']));

}

//分享信息
$sharelink = $_W['siteroot'] . 'app/' . $this->createMobileUrl('share', array('rid' => $rid, 'from_user' => $page_from_user));
$sharetitle = empty($reply['share_title']) ? '一起来帮TA投票吧!' : $reply['share_title'];
$sharedesc = empty($reply['share_desc']) ? '亲，一起来帮TA投票吧，还能赢大奖哦！！' : str_replace("\r\n", " ", $reply['share_desc']);
if (!empty($reply['share_imgurl'])) {
    $shareimg = toimage($reply['share_imgurl']);
} else {
    $shareimg = toimage($reply['picture']);
}
$jssdk = new JSSDK();
$package = $jssdk->GetSignPackage();

if(empty($reply['mobpicurl'])){
    $bg = "../addons/haoman_dpm/mobimg/bg2.jpg";
}else{
    $bg = tomedia($reply['mobpicurl']);
}

include $this->template('index9');