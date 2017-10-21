<?php

$openid = $_W ['openid'];

$title = "核销";
$code = $_GPC ['sn'];
$userid = intval ( $_GPC ['userid'] );

if (! $openid || ! $code) {
	$background = MODULE_URL . "public/mobile/images/dianyuanfailure.jpg";
	include $this->template ( 'msg' );
	exit();
}

$user = pdo_fetch ( "SELECT * FROM " . tablename ( $this->table_user ) . " WHERE sn = :sn AND id = :id ORDER BY `id` DESC", array (
		':sn' => $code,
		':id' => $userid 
) );

if (! $user) {
	$background = MODULE_URL . "public/mobile/images/dianyuanfailure.jpg";
	include $this->template ( 'msg' );
	exit ();
}

$verifier = pdo_fetch ( 'SELECT * FROM ' . tablename ( $this->table_verifier ) . ' where openid=:openid and prizeid=:prizeid', array (
		':openid' => $openid,
		':prizeid' => $user ['prizeid'] 
) );

if (! $verifier) {
	$background = MODULE_URL . "public/mobile/images/dianyuanfailure.jpg";
	include $this->template ( 'msg' );
	exit ();
}

if ($user ['status'] == 2) {
	$re = pdo_update ( $this->table_user, array (
			'status' => '3' 
	), array (
			'sn' => $code,
			'id' => $userid 
	) );
} else if ($user ['status'] == 3) {
	$error = "奖品已经兑换完毕，若有疑问请联系工作人员";
	include $this->template ( 'no' );
	exit ();
} else {
	$re = pdo_update ( $this->table_user, array (
			'status' => '2' 
	), array (
			'sn' => $code,
			'id' => $userid 
	) );
	$re = false;
}
if ($re) {
	$background = MODULE_URL . "public/mobile/images/dianyuansuccess.png";
	include $this->template ( 'msg' );
	exit ();
} else {
	$background = MODULE_URL . "public/mobile/images/dianyuanfailure.jpg";
	include $this->template ( 'msg' );
	exit ();
}
?>