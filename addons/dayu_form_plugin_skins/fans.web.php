<?php
	$weid = $_W['uniacid'];
	checklogin();
		
	$_accounts = $accounts = uni_accounts();
	load()->model('mc');
	if(empty($accounts) || !is_array($accounts) || count($accounts) == 0){
		message('请指定公众号');
	}
	if(!isset($_GPC['acid'])){
		$account = array_shift($_accounts);
		if($account !== false){
			$acid = intval($account['acid']);
		}
	} else {
		$acid = intval($_GPC['acid']);
		if(!empty($acid) && !empty($accounts[$acid])) {
			$account = $accounts[$acid];
		}
	}
		reset($accounts);
	$m_card = pdo_get('mc_card', array('uniacid' => $_W['uniacid'], 'status' => 1), array());
	$settings = uni_setting($_W['uniacid'], array('sync'));
	$passport = @iunserializer($uc['passport']);
	if(!is_array($passport)) {
		$passport = array();
	}
	$setting = $this->module['config'];