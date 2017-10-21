<?php
global $_GPC, $_W;
$uniacid = $_W['uniacid'];
$rid = intval($_GPC['rid']);
$cklogin = pdo_fetch("SELECT * FROM " . tablename('haoman_dpm_reply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
if(!empty($_POST['password'])){
    if($_POST['password'] == $cklogin['loginpassword']){
    	$cookieid = '__cookie_haoman_dpmweb_201606186_' . $rid;
		$cookie = array("loginpassword" => $cklogin['loginpassword']);
		setcookie($cookieid, base64_encode(json_encode($cookie)), time() + 3600 * 12);
        $data = array(
			'success' => 1,
			'msg' => '密码正确！',

		);
    }else{
        $data = array(
			'success' => 0,
			'msg' => '密码错误！',

		);
    }
}else{
    $data = array(
		'success' => 0,
		'msg' => '密码错误2！',

	);
}

echo json_encode($data);