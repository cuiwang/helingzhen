<?php

// 获取当前用户Openid
$openid = $_W ['openid'];
$prizeid = $_GPC ['prizeid'];
$mask = intval ( $_GPC ['mask'] );
// 第一步验证，时间以及prizeid是否存在
if ((time () - $mask) > 3600 || ! $openid) {
	$error = '二维码已失效，请重新获取';
	include $this->template ( 'no' );
	exit ();
}
$prize = pdo_fetch ( 'SELECT * FROM ' . tablename ( $this->table_prize ) . ' where id = ' . $prizeid );
if (! $prize) {
	$error = '不存在该券';
	include $this->template ( 'no' );
	exit ();
}
$temp = pdo_fetch ( 'SELECT * FROM ' . tablename ( $this->table_verifier ) . ' where rid=:rid and openid=:openid and prizeid=:prizeid', array (
		':rid' => $prize ['rid'],
		':openid' => $openid,
		':prizeid' => $prize ['id'] 
) );
if ($temp) {
	$re = pdo_delete ( $this->table_verifier, $temp );
	if ($re) {
		$title = "核销人员解绑";
		$background = MODULE_URL . "public/mobile/images/unbindsuccess.jpg";
		include $this->template ( 'msg' );
		exit ();
	} else {
		$error = '操作失败！';
		include $this->template ( 'no' );
		exit ();
	}
} else {
	$data = array ();
	$data ['rid'] = $prize ['rid'];
	$data ['uniacid'] = $_W ['uniacid'];
	$data ['replyid'] = $prize['replyid'];
	$data ['prizeid'] = $prize ['id'];
	$data ['openid'] = $openid;
	$data ['uid'] = $fan ['uid'];
	$re = pdo_insert ( $this->table_verifier, $data );
	if ($re) {
		$title = "核销人员绑定";
		$background = MODULE_URL . "public/mobile/images/bindsuccess.jpg";
		include $this->template ( 'msg' );
		exit ();
	} else {
		$error = '操作失败！';
		include $this->template ( 'no' );
		exit ();
	}
}
?>