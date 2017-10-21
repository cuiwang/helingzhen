<?php
global $_GPC, $_W;
$rid = intval($_GPC['rid']);
$uniacid = $_W['uniacid'];
$uid = $_GPC['uid'];
$fromuser = $_GPC['from_user'];

$from_user = $_W['openid'];//聊天的人


//$user_agent = $_SERVER['HTTP_USER_AGENT'];
//if (strpos($user_agent, 'MicroMessenger') === false) {
//
//	header("HTTP/1.1 301 Moved Permanently");
//	header("Location: {$this->createMobileUrl('other',array('type'=>1,'id'=>$rid))}");
//	exit();
//}
//
//
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
$fans = pdo_fetch("select id,avatar,nickname,from_user,sex,is_online from " . tablename('haoman_dpm_fans') . " where rid = '" . $rid . "' and from_user='" . $uid . "'");

//$page_from_user = base64_encode(authcode($from_user, 'ENCODE'));
//
$abc = pdo_fetchall( " SELECT * FROM ".tablename('haoman_dpm_bbgift')." WHERE rid=:rid and `type` in(2,3) order by sort desc,bb_price asc",array(':rid'=>$rid) );

foreach ($abc as $k=>$v){
    if($v['type']==2){
        $bbgift[$k] = $v;
    }else{
        $bb_price[$k]=$v;
    }
}
//$bb_price1=$bb_price[0]['bb_price'];
//$bb_price = pdo_fetchall( " SELECT * FROM ".tablename('haoman_dpm_bbgift')." WHERE rid=:rid and type=:type order by sort desc,bb_price asc",array(':rid'=>$rid,':type'=>3) );
//

$num = ceil(count($bbgift)/6);

for ($i=0;$i<$num;$i++){
    $ss = array_slice($bbgift,$i*6,6);
    $new_bbgift[$i]=$ss;
    $ss='';
}
////分享信息
//$sharelink = $_W['siteroot'] . 'app/' . $this->createMobileUrl('share', array('rid' => $rid, 'from_user' => $page_from_user));
//$sharetitle = empty($reply['share_title']) ? '一起来咻一咻抽奖吧!' : $reply['share_title'];
//$sharedesc = empty($reply['share_desc']) ? '亲，一起来咻一咻吧，赢大奖哦！！' : str_replace("\r\n", " ", $reply['share_desc']);
//if (!empty($reply['share_imgurl'])) {
//	$shareimg = toimage($reply['share_imgurl']);
//} else {
//	$shareimg = toimage($reply['picture']);
//}
//
//if(empty($reply['mobpicurl'])){
//	$bg = "../addons/haoman_dpm/mobimg/bg.jpg";
//}else{
//	$bg = tomedia($reply['mobpicurl']);
//}
//
//$jssdk = new JSSDK();
//$package = $jssdk->GetSignPackage();
//if($reply['ismessage']==1){
//    include $this->template('mob_newindex');
//}else{
    include $this->template('mob_express_love');
//}