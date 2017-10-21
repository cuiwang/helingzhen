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

		if (empty($reply)) {
	message('非法访问，请重新发送消息进入活动页面！');
}

if($reply['isqhb']==1){
    message('抱歉！抢红包活动还未开启，请留意大屏幕',$this->createMobileUrl('index',array('id'=>$rid)),'success');
}


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
$fans = pdo_fetch("select * from " . tablename('haoman_dpm_fans') . " where rid = '" . $rid . "' and from_user='" . $from_user . "'");
if ($fans == false) {
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: " . $this->createMobileUrl('information', array('id' => $rid,'from_user'=>$page_from_user)) . "");
	exit();
} else {
	//增加浏览次数
	pdo_update('haoman_dpm_reply', array('viewnum' => $reply['viewnum'] + 1), array('id' => $reply['id']));
}


//if($reply['most_num_times'] > 0) {
//	$Lcount = $reply['most_num_times'] - $fans['todaynum'];
//}  else {
//	$Lcount = 999999;
//}

$Lcount = 999999;

$Lcount = $Lcount < 0 ? 0 : $Lcount;

if (empty($fans['todaynum'])) {
	$fans['todaynum'] = 0;
}


$addad = pdo_fetchall("select * from " . tablename('haoman_dpm_addad') . " where rid = :rid ", array(':rid' => $rid));
$num1 = array_rand($addad);
$addad_img = $addad[$num1][adlogo];
$addad_url = $addad[$num1][adlink];


//卡券
$cardArry = $this->getCardTicket($rid,$from_user);

$awardlist = pdo_fetchall("SELECT * FROM " . tablename('haoman_dpm_award') . " WHERE rid = :rid and uniacid = :uniacid ORDER BY createtime DESC limit 15 ",array(':rid'=>$rid,':uniacid'=>$uniacid));
foreach ($awardlist as &$lists) {
	$lists['nickname'] = pdo_fetchcolumn("select nickname from " . tablename('haoman_dpm_fans') . " where from_user = :from_user and rid = :rid ", array(':from_user' => $lists['from_user'],':rid'=>$rid));
	$lists['avatar'] = pdo_fetchcolumn("select avatar from " . tablename('haoman_dpm_fans') . " where from_user = :from_user and rid = :rid ", array(':from_user' => $lists['from_user'],':rid'=>$rid));
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

if(empty($reply['mobqhbbg'])){
	if($reply['isqhb']==0){
		$bg = "../addons/haoman_dpm/images/bg.jpg";
	}else{
		$bg = "../addons/haoman_dpm/images/yaoyiyaobg.jpg";
	}
	
}else{
	$bg = tomedia($reply['mobqhbbg']);
}

include $this->template('index');