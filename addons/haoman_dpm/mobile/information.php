<?php
global $_GPC,$_W;
// $this->checkFollow;
// $this->checkBowser;
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
    $avatar = $_W['fans']['tag']['avatar'];
    $sex = $_W['fans']['tag']['sex'];
    $nickname = $_W['fans']['nickname'];
}

$code = $_GPC['code'];
$urltype = '';
if (empty($from_user) || empty($avatar) || empty($nickname)) {
    if (!is_array($cookie) || !isset($cookie['avatar']) || !isset($cookie['openid']) || !isset($cookie['nickname'])|| !isset($cookie['sex'])) {
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

if($reply['is_openbbm']==1){
    message('抱歉，签到已停止，下次早点哦！', '', 'error');
}

//2017-05-03 新增免签到

$max_qd = pdo_fetchcolumn("select count(id) from " . tablename('haoman_dpm_fans') . "  where rid = :rid and uniacid=:uniacid ", array(':rid'=>$rid,':uniacid'=>$_W['uniacid']));
$max_qd = ($max_qd%2000)+1;
if($reply['ziliao']==4){
    $insert = array(
        'uniacid' => $_W['uniacid'],
        'from_user' => $from_user,
        'avatar' => $avatar,
        'nickname' => $nickname,
        'rid' => $rid,
        'is_back' => 0,
        'sex' => $sex,
        'awardingid' => $max_qd,
        'createtime' => time(),
    );


    pdo_insert('haoman_dpm_fans',$insert);
    pdo_update('haoman_dpm_reply', array('fansnum' => $reply['fansnum'] + 1, 'viewnum' => $reply['viewnum'] + 1), array('id' => $reply['id']));

    $uniacid=$_W['uniacid'];
    $url ="../addons/haoman_dpm/sign/$uniacid/$rid/";



    $filename=$url."sign.txt";

    $handle=fopen($filename,"a+");
    $avatar =empty($avatar)?"{$_W['siteroot']}addons/haoman_dpm/common/item2.jpg":$avatar;
    $str=fwrite($handle,$from_user."|".$nickname."|".$avatar."|".date('Y/m/d H:i',time())."\n");

    fclose($handle);
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: " . $this->createMobileUrl('messagesindex', array('id' => $rid,'from_user'=>$page_from_user)) . "");
    exit();
}

$xysreply = pdo_fetch( " SELECT is_pair,is_sex,is_pair,is_turntable FROM ".tablename('haoman_dpm_xysreply')." WHERE rid='".$rid."' " );
    if($xysreply['is_sex']==1){
        $is_sex =1;
    }
// $fans = pdo_fetch("select * from " . tablename('haoman_dpm_fans') . " where rid = '" . $rid . "' and from_user='" . $from_user . "'");
//分享信息
$sharelink = $_W['siteroot'] . 'app/' . $this->createMobileUrl('share', array('rid' => $rid, 'from_user' => $page_from_user));
$sharetitle = empty($reply['share_title']) ? '一起来参与抽奖吧!' : $reply['share_title'];
$sharedesc = empty($reply['share_desc']) ? '亲，一起来参与，赢大奖哦！！' : str_replace("\r\n", " ", $reply['share_desc']);
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
$shouqian = pdo_fetch("SELECT status FROM " . tablename('haoman_dpm_shouqianBase') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));


include $this->template('mob_qd');