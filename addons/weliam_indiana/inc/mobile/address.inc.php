<?php
	global $_W,$_GPC;
	session_start();
	load()->func('tpl');
	$openid = m('user') -> getOpenid();
	if($_GPC['pid']){
		$_SESSION['pid'] = $_GPC['pid'];
	}
	$addres = pdo_fetchall("select * from ".tablename('weliam_indiana_address')."where openid='{$openid}' and uniacid='{$_W['uniacid']}'");
	
	include $this->template("address");
	
	?>