<?php
global $_GPC, $_W;
$rid = intval($_GPC['id']);
$uniacid = $_W['uniacid'];
$credit1 = $_W['member']['credit1'];

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

$reply = pdo_fetch("select * from " . tablename('haoman_dpm_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
$yyy = pdo_fetch("select isyyy from " . tablename('haoman_dpm_yyyreply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
$vote = pdo_fetch("select vote_status from " . tablename('haoman_dpm_newvote_set') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
$photo = pdo_fetch("select is_phone from " . tablename('haoman_dpm_photo_setting') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
$punishment = pdo_fetch("select is_punishment from " . tablename('haoman_dpm_punishment') . " where rid = :rid and uniacid=:uniacid ", array(':rid'=>$rid,':uniacid'=>$_W['uniacid']));

if(ISCUSTOM == 1 && CUSTOM_VERSION == 'DS'){
    $dsreply = pdo_fetch("select * from " . tablename('haoman_dpm_ds_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
}
if(ISCUSTOM == 1 && CUSTOM_VERSION == 'ZNL'){
    $znlreply = pdo_fetch( " SELECT * FROM ".tablename('haoman_dpm_znl_reply')." WHERE rid='".$rid."' " );
}
$num = $reply['share_acid'] < 100 ? 100 : $reply['share_acid'];

$fans = pdo_fetch("select * from " . tablename('haoman_dpm_fans') . " where rid = '" . $rid . "' and from_user='" . $from_user . "'");


 $mybobing = pdo_fetchall("select * from " . tablename('haoman_dpm_award') . " where rid = :rid and from_user =:from_user and uniacid = :uniacid ORDER BY id desc",array(':rid'=>$rid,':from_user'=>$from_user,'uniacid'=>$uniacid));
$nums =0;
foreach($mybobing as $k){

	if($k['status']==1&&$k['prizetype']==0){
		$nums +=$k['credit'];
	}
}

//$award = pdo_fetchall("select * from " . tablename('haoman_dpm_award') . " where status = 1 and prizetype = 0 and rid = " . $rid . " and from_user='" . $from_user . "'");
//
//foreach($award as $k){
//    $nums +=$k['credit'];
//}
	$hb_award = pdo_fetchall("select * from " . tablename('haoman_dpm_hb_award') . " where rid = :rid and  status = 1 and prizetype = 0   and from_user=:from_user and uniacid = :uniacid",array(':rid'=>$rid,':from_user'=>$from_user,'uniacid'=>$uniacid));
	$nums_hb =0;
	foreach($hb_award as $v){
		$nums_hb +=$v['credit'];
	}
   $nums= $nums+$nums_hb*100;

	$cashs = pdo_fetchall("select * from " . tablename('haoman_dpm_cash') . " where rid = :rid and from_user =:from_user and uniacid = :uniacid and status = 0",array(':rid'=>$rid,':from_user'=>$from_user,'uniacid'=>$uniacid));
    $numx = 0;
if(empty($cashs)){
	$numx = 0;
}
foreach($cashs as $k){
	$numx += $k['awardname'];
}



if(empty($mybobing)){
		$mybb = 1;
	}


//分享信息
$sharelink = $_W['siteroot'] . 'app/' . $this->createMobileUrl('share', array('rid' => $rid, 'from_user' => $page_from_user));
$sharetitle = empty($reply['share_title']) ? '一起来咻一咻抽奖吧!' : $reply['share_title'];
$sharedesc = empty($reply['share_desc']) ? '亲，一起来咻一咻吧，赢大奖哦！！' : str_replace("\r\n", " ", $reply['share_desc']);
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
if($from_user=='oQAFAwDtRnF7TMgmRiP4sxMvny80'){
    include $this->template('new_index3');
}else{
    include $this->template('index3');
}
