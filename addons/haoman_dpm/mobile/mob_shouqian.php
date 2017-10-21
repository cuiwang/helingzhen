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
    $sex = $cookie['sex'];
}else{
    $from_user = $_W['fans']['from_user'];
    $sex = $_W['fans']['tag']['sex'];
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
        $sex = $userinfo['sex'];
    } else {
        $avatar = $cookie['avatar'];
        $nickname = $cookie['nickname'];
        $from_user = $cookie['openid'];
        $sex = $cookie['sex'];
    }
}

//网页授权借用结束

$page_from_user = base64_encode(authcode($from_user, 'ENCODE'));

$reply = pdo_fetch("select is_b_share,id,isallowip,allowip,liucheng,share_url,viewnum,share_title,mobtitle,share_desc,share_imgurl,picture,mobpicurl,isbaoming,is_showjiabin,isjiabin,isqhb,istoupiao,copyright,bottomwordcolor,bottomcolor,registimg,rules from " . tablename('haoman_dpm_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
$yyy = pdo_fetch("select isyyy from " . tablename('haoman_dpm_yyyreply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));

//检测是否关注
if (!empty($reply['share_url'])) {
    //查询是否为关注用户
    $fansID = $_W['member']['uid'];
    $follow = pdo_fetchcolumn("select follow from " . tablename('mc_mapping_fans') . " where uid=:uid and uniacid=:uniacid order by `fanid` desc", array(":uid" => $fansID, ":uniacid" => $uniacid));

    if ($follow == 0) {
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: " . $reply['share_url'] . "");
        exit();
    }

}


//检测是否为空
$fans = pdo_fetch("select id,zhongjiang,isbaoming from " . tablename('haoman_dpm_fans') . " where rid = '" . $rid . "' and from_user='" . $from_user . "'");
if ($fans == false) {

    if($reply['isbaoming']==1){
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: " . $this->createMobileUrl('go_baoming', array('id' => $rid,'from_user'=>$page_from_user)) . "");
        exit();
    }else{
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: " . $this->createMobileUrl('information', array('id' => $rid,'from_user'=>$page_from_user)) . "");
        exit();
    }
} else {

    if($fans['isbaoming']==1){
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: " . $this->createMobileUrl('ve_baoming', array('id' => $rid,'from_user'=>$page_from_user)) . "");
        exit();
    }
    //增加浏览次数
//          pdo_update('haoman_dpm_reply', array('viewnum' => $reply['viewnum'] + 1), array('id' => $reply['id']));
}

include $this->template('mob_shouqian');
