<?php
global $_GPC,$_W;
$rid = intval($_GPC['id']);

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

$fans = pdo_fetch("select * from " . tablename('haoman_dpm_fans') . " where rid = '" . $rid . "' and from_user='" . $from_user . "'");

$reply = pdo_fetch( " SELECT * FROM ".tablename('haoman_dpm_reply')." WHERE rid='".$rid."' " );

$message = $_GPC['message'];
$picture = $_GPC['picture'];


if(empty($message)){
	$data = array(
		'success' => 100,
		'msg' => "留言不能为空",
	);

	echo json_encode($data);
	exit;
}

if($reply['isckmessage']==0){
	$status = 0;
}else{
	$status = 1;
}

if(empty($nickname) || empty($avatar)){
    $nickname = $fans['nickname'];
    $avatar = tomedia($fans['avatar']);
}

//		if(!empty($fans['realname'])){
//			$nickname = $fans['realname'];
//		}
$type = $_GPC['type'];
   $is_xys = 0;
   if($type==1){
       $is_xys=1;
   }else{
       $is_xys=0;
   }

$insert = array(
	'uniacid' => $_W['uniacid'],
	'avatar' => $avatar,
	'nickname' => $nickname,
	'from_user' => $from_user,
	'word' => $message,
	'wordimg' => $picture,
	'rid' => $rid,
	'status' => $status,
	'is_back' => $fans['is_back'],
	'is_xy' =>$is_xys,
	'is_bp' =>0,
	'type' =>0,
	'gift' =>0,
	'createtime' => time(),
);
$temp = pdo_insert('haoman_dpm_messages',$insert);

if($temp == false){
	$data = array(
		'success' => 100,
		'msg' => "上墙失败，请从新发送",
	);
}else{
	$data = array(
		'success' => 1,
		'msg' => "发言成功",
	);
}

echo json_encode($data);