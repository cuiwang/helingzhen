<?php 
	global $_W,$_GPC;
	session_start();
	if($_GPC['pid']){
		$_SESSION['pid'] = $_GPC['pid'];
	}
	$openid = m('user') -> getOpenid();
	$addaddres = pdo_fetch("select * from ".tablename('weliam_indiana_address')."where id='{$_GPC['id']}' and uniacid='{$_W['uniacid']}'");
	if(empty($addaddres)){
		$addaddres = array(
		'id' => '-1',
		'isdefault' => '0'
		);
	}
	include $this->template("addaddress");
	?>