<?php
	$profile = pdo_fetch("SELECT * FROM " . tablename('tg_member') . " WHERE uniacid ='{$_W['uniacid']}' and from_user = '{$_W['openid']}'");
	load()->model('mc');
	$result = mc_fetch($_W['member']['uid'], array('credit1', 'credit2'));
	load()->model('account');
	$modules = uni_modules();
	include $this->template('person');
?>
