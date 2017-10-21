<?php
global $_GPC, $_W;
$rid = intval($_GPC['rid']);
$inputval = $_GPC['inputval'];
$num = $_GPC['num'];

$user_agent = $_SERVER['HTTP_USER_AGENT'];
if (strpos($user_agent, 'MicroMessenger') === false) {
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: {$this->createMobileUrl('other',array('type'=>1,'id'=>$rid))}");
	exit();
}

$from_user = $_W['fans']['from_user'];
$avatar = $_W['fans']['tag']['avatar'];
$nickname = $_W['fans']['nickname'];

load()->model('account');
$_W['account'] = account_fetch($_W['acid']);
$cookieid = '__cookie_haoman_dpm_201606186_' . $rid;
$cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
if ($_W['account']['level'] != 4) {
    $from_user = authcode(base64_decode($_GPC['from_user']), 'DECODE');
    $avatar = $cookie['avatar'];
	$nickname = $cookie['nickname'];
}


if (empty($from_user)) {
	$this->message(array("success" => 2,'level'=>1, "msg" => '获取不到您的OpenID,请从新进入活动页面'), "");
}


//  $fans = pdo_fetch("select id,mobile from " . tablename('haoman_qib_fans') . " where rid = " . $rid . " and from_user=" . $from_user . "");
$num0 = pdo_fetch("select password from " . tablename('haoman_dpm_reply') . " where rid = :rid", array(':rid' => $rid));
if($num0['password']==0){
	if($rid){
		$temp = pdo_update('haoman_dpm_award', array('status' => 2,'consumetime' => time()), array('rid' => $rid, 'from_user' => $from_user, 'uniacid' => $_W['uniacid'], 'id'=>$num));
		$data = array(
			'success' => 1,
			'msg' => '兑奖成功！',
		);
	}
}else{


	if($inputval == $num0['password']){
		$temp = pdo_update('haoman_dpm_award', array('status' => 2,'consumetime' => time()), array('rid' => $rid, 'from_user' => $from_user, 'uniacid' => $_W['uniacid'], 'id'=>$num));
		$data = array(
			'success' => 1,
			'msg' => '兑奖成功！',
		);
	}
	else{
		$data = array(
			'success' => 0,
			'msg' => $num0,
		);
	}

}

echo json_encode($data);