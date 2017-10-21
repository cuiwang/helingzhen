<?php
global $_GPC, $_W;
$fromuser = $_GPC['userid'];
$rid = intval($_GPC['rid']);
$type = intval($_GPC['type']);
$uid = $_GPC['uid'];
$from_user = $_W['openid'];
$uniacid = $_W['uniacid'];


load()->model('account');
$_W['account'] = account_fetch($_W['acid']);
$cookieid = '__cookie_haoman_dpm_201606186_' . $rid;
$cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
if ($_W['account']['level'] != 4) {
    $from_user = $cookie['openid'];
}

if($uid==$from_user){
	$admin = pdo_fetch("select id from " . tablename('haoman_dpm_bpadmin') . "  where admin_openid=:admin_openid and status=0 and rid=:rid", array(':admin_openid' => $from_user,':rid'=>$rid));
    if($admin){
		$rule = pdo_fetch("select id,from_user from " . tablename('haoman_dpm_fans') . " where from_user = :from_user and rid=:rid ", array(':from_user' => $fromuser,':rid'=>$rid));
		if (empty($rule)) {
			$result = array(
				'isResultTrue' => 0,
				'msg'=>1,

			);

			echo json_encode($result);
			exit();
		}
		pdo_update('haoman_dpm_messages', array('is_back' => 1), array('from_user' => $fromuser,'rid'=>$rid));
		pdo_update('haoman_dpm_fans', array('is_back' => 1), array('from_user' => $fromuser,'rid'=>$rid));

		$result = array(
			'isResultTrue' => 1,
			'msg'=>2,

		);

		echo json_encode($result);
		exit();
	}
}
else{
	$result = array(
		'isResultTrue' => 0,
		'msg'=>$type,

	);

	echo json_encode($result);
	exit();
}
