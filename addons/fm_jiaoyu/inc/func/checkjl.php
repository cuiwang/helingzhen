<?php
/**
 * By 高贵血迹
 */
if (!empty($_W['openid']) && intval($_W['account']['level']) >= 3) {
	load()->classs('weixin.account');
	$accObj = WeiXinAccount::create($_W['account']);
	$userinfo = $accObj->fansQueryInfo($_W['openid']);
}

// 获取当期公众号设置
$sql = "SELECT * FROM ".tablename('uni_settings')." WHERE `uniacid`=:uniacid";
$unisetting  =  pdo_fetch($sql,array('uniacid'=>$_W['uniacid']));

// 获取粉丝公众号ID
if(!empty($unisetting['oauth'])) {
	$temp = unserialize($unisetting['oauth']);
	$weid = empty($temp['account']) ? $_W['uniacid'] : $temp['account'];
} else {
	$weid = $_W['uniacid'];
}

$state = 'toddy';
$code = $_GET['code'];
$from_user = $_W['openid'];

if(empty($code)){
	if($userinfo['subscribe']==0){

		$url = $_W['siteroot'] . 'app/' . substr($this->createMobileUrl('auth'),2);
		$callback = urlencode($url);
		$forward = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$_W['oauth_account']['key'].'&redirect_uri='.$callback.'&response_type=code&scope=snsapi_userinfo&state='.$state.'#wechat_redirect';
		header("Location: ".$forward);
		exit();

	}else{

		$userinfo['nickname'] = stripcslashes($userinfo['nickname']);
		$userinfo['nickname'] = stripslashes($userinfo['nickname']);

		$sql = "SELECT * FROM ".tablename('mc_mapping_fans')." WHERE `openid`=:openid AND `uniacid`=:uniacid AND `openid`<>'' ";
		$fan_temp  = pdo_fetch($sql,array(":openid"=>$from_user,":uniacid"=>$weid));
		if(!empty($userinfo) && !empty($userinfo['nickname'])){
			$userinfo['avatar'] = $userinfo['headimgurl'];
			unset($userinfo['headimgurl']);

			$default_groupid = pdo_fetchcolumn('SELECT groupid FROM ' .tablename('mc_groups') . ' WHERE uniacid = :uniacid AND isdefault = 1', array(':uniacid' => $weid));

			$data = array(
				'uniacid' => $weid,
				'email' => md5($_W['openid']).'@9yetech.com'.$op,
				'salt' => random(8),
				'groupid' => $default_groupid,
				'createtime' => time(),
				'nickname' 		=> $userinfo['nickname'],
				'avatar' 		=> $userinfo['avatar'],
				'gender' 		=> $userinfo['sex'],
				'nationality' 	=> $userinfo['country'],
				'resideprovince'=> $userinfo['province'] . '省',
				'residecity' 	=> $userinfo['city'] . '市',
			);
			$data['password'] = md5($_W['openid'] . $data['salt'] . $_W['config']['setting']['authkey']);

			$sql = "SELECT * FROM ".tablename('mc_members')." WHERE `uid`=:uid AND `uid`<>0";
			$member_temp = pdo_fetch($sql,array(":uid"=>$fan_temp['uid']));

			if(empty($member_temp)){
				pdo_insert('mc_members', $data);
				$uid = pdo_insertid();
			}else{
				pdo_update('mc_members' ,$data ,array('uid'=>$fan_temp['uid']));
				$uid=$fan_temp['uid'];
			}

			$record = array(
				'openid' 		=> $from_user,
				'uid' 			=> $uid,
				'acid' 			=> $weid,
				'uniacid' 		=> $weid,
				'salt' 			=> random(8),
				'updatetime' 	=> time(),
				'nickname' 		=> $userinfo['nickname'],
				'follow' 		=> $userinfo['subscribe'],
				'followtime' 	=> $userinfo['subscribe_time'],
				'unfollowtime' 	=> 0,
				'tag' 			=> base64_encode(iserializer($userinfo))
			);
			$record['uid'] = $uid;
			if(empty($fan_temp)){
				pdo_insert('mc_mapping_fans', $record);
			}else{
				pdo_update('mc_mapping_fans' ,$record ,array('fanid'=>$fan_temp['fanid']));
			}
		}
	}
}else{
	load()->func('communication');
	$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$_W['oauth_account']['key']."&secret=".$_W['oauth_account']['secret']."&code=".$code."&grant_type=authorization_code";
	$response = ihttp_get($url);
	$oauth = @json_decode($response['content'], true);

	$url = "https://api.weixin.qq.com/sns/userinfo?access_token={$oauth['access_token']}&openid={$oauth['openid']}&lang=zh_CN";
	$response = ihttp_get($url);
	if (!is_error($response)) {

		$userinfo = array();
		$userinfo = @json_decode($response['content'], true);

		$userinfo['nickname'] = stripcslashes($userinfo['nickname']);
		$userinfo['nickname'] = stripslashes($userinfo['nickname']);

		$userinfo['avatar'] = $userinfo['headimgurl'];
		unset($userinfo['headimgurl']);

		$_SESSION['userinfo'] = base64_encode(iserializer($userinfo));
		$_SESSION['openid'] = $oauth['openid'];

		if(!empty($userinfo) && !empty($userinfo['nickname'])){

			$sql = "SELECT * FROM ".tablename('mc_mapping_fans')." WHERE `openid`=:openid AND `uniacid`=:uniacid AND `openid`<>'' ";
			$fan_temp  = pdo_fetch($sql,array(":openid"=>$from_user,":uniacid"=>$weid));

			$default_groupid = pdo_fetchcolumn('SELECT groupid FROM ' .tablename('mc_groups') . ' WHERE uniacid = :uniacid AND isdefault = 1', array(':uniacid' => $weid));
			$data = array(
				'uniacid' => $weid,
				'email' => md5($oauth['openid']).'@9yetech.com'.$op,
				'salt' => random(8),
				'groupid' => $default_groupid,
				'createtime' => time(),
				'nickname' 		=> $userinfo['nickname'],
				'avatar' 		=> rtrim($userinfo['avatar'], '0') . 132,
				'gender' 		=> $userinfo['sex'],
				'nationality' 	=> $userinfo['country'],
				'resideprovince'=> $userinfo['province'] . '省',
				'residecity' 	=> $userinfo['city'] . '市',
			);
			$data['password'] = md5($_W['openid'] . $data['salt'] . $_W['config']['setting']['authkey']);

			$sql = "SELECT * FROM ".tablename('mc_members')." WHERE `uid`=:uid AND `uid`<>0";
			$member_temp = pdo_fetch($sql,array(":uid"=>$fan_temp['uid']));

			if(empty($member_temp)){
				$re = pdo_insert('mc_members', $data);
				$uid = pdo_insertid();
			}else{
				$re = pdo_update('mc_members' ,$data ,array('uid'=>$fan_temp['uid']));
				$uid=$fan_temp['uid'];
			}

			$record = array(
				'openid' 		=> $oauth['openid'],
				'uid' 			=> $uid,
				'acid' 			=> $weid,
				'uniacid' 		=> $weid,
				'salt' 			=> random(8),
				'updatetime' 	=> time(),
				'nickname' 		=> $userinfo['nickname'],
				'follow' 		=> $userinfo['subscribe'],
				'followtime' 	=> $userinfo['subscribe_time'],
				'unfollowtime' 	=> 0,
				'tag' 			=> base64_encode(iserializer($userinfo))
			);
			$record['uid'] = $uid;

			if(empty($fan_temp)){
				$re = pdo_insert('mc_mapping_fans', $record);
			}else{
				$re=pdo_update('mc_mapping_fans' ,$record ,array('fanid'=>$fan_temp['fanid']));
			}
		}
	} else {
		message('微信授权获取用户信息失败,请重新尝试: ' . $response['message']);
	}
}

$url = $_SESSION['authurl'];
if(!$url){
	message('非法访问','','error');
}
echo "<script>window.location.href = '".$url."';</script>";
exit();
?>