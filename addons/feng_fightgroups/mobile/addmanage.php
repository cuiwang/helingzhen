<?php
	$openid = $_W['openid'];
	$op = $_GPC['op'];
	$g_id = $_GPC['g_id'];
	$groupnum = $_GPC['groupnum'];
	if($op == 'changeaddres'){
		$all = array(
			'g_id' =>$g_id,
			'groupnum' =>$groupnum
		);
	}
	$address = pdo_fetchall("select * from " . tablename('tg_address')."where openid='$openid' ");
	include $this->template('addmanage');

	