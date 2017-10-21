<?php
/**
 * By Toddy
 */


// 获取当期公众号设置
 $sql = "SELECT * FROM ".tablename('uni_settings')." WHERE `uniacid`=:uniacid";


$unisetting  =  pdo_fetch($sql,array(':uniacid'=>$_W['uniacid']));

// 获取粉丝公众号ID
if(!empty($unisetting['oauth'])) {
	$temp = unserialize($unisetting['oauth']);
	$weid = empty($temp['account']) ? $_W['uniacid'] : $temp['account'];
} else {
	$weid = $_W['uniacid'];
}

if($weid != $_W['uniacid']){

	if(!empty($_SESSION['refresh_openid'])){

		$_SESSION['openid'] = $_SESSION['refresh_openid'];
		$_W['openid'] = $_SESSION['refresh_openid'];

	} else if(!empty($_GET['code'])){

		$code = $_GET['code'];
		load()->func('communication');
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$_W['oauth_account']['key']}&secret={$_W['oauth_account']['secret']}&code={$code}&grant_type=authorization_code";
		$response = ihttp_get($url);
		if(!is_error($response)) {
			$oauth = @json_decode($response['content'], true);
			if(is_array($oauth) && !empty($oauth['openid'])) {
				$_SESSION['oauth_openid'] = $oauth['openid'];
				$_SESSION['oauth_acid'] = $_W['oauth_account']['acid'];
			}
		}
		$_SESSION['refresh_openid'] = $oauth['openid'];
		$_SESSION['openid'] = $_SESSION['refresh_openid'];
		$_W['openid'] = $oauth['openid'];

	}else{

		$state = 'toddy';
		$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
		$callback = urlencode($url);
		$forward = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$_W['oauth_account']['key']}&redirect_uri={$callback}&response_type=code&scope=snsapi_base&state={$state}#wechat_redirect";
		header("Location: ".$forward);

	}
}


// 判断是否需要授权
$check = false;

// 获取用户openid
$openid = $_W['openid'];

$url = $_W ['siteroot'].'app/'.substr($this->createMobileUrl('index',array('id'=>$_GPC['id'],'popenid'=>$_GPC['popenid'],'issub'=>$_GPC['issub'])),2);

// 获取用户粉丝信息
$sql = "SELECT * FROM ".tablename('mc_mapping_fans')." WHERE `openid`=:openid ";
$fan = pdo_fetch($sql,array(":openid"=>$openid));

if($fan){
	// 获取会员信息
	$sql = "SELECT * FROM ".tablename('mc_members')." WHERE `uid`=:uid AND `uid`<>0 ";
	$member = pdo_fetch($sql,array(":uid"=>$fan['uid']));
	if($member['nickname']){
		$check = false;
		$_SESSION['authurl'] = "";
	}else{
		$check = true;
		$_SESSION['authurl'] = $url;
	}
}else{
	$check = true;
	$_SESSION['authurl'] = $url;
}


// 跳转到授权页面
if($check){
	echo "<script>window.location.href = '".$_W['siteroot'].'app/'.substr($this->createMobileUrl('auth'),2)."';</script>";
	exit();
}
?>