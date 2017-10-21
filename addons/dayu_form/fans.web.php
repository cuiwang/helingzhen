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
		
	if (pdo_tableexists('dayu_form_skins')){
		$check_skins = pdo_fetchall("SELECT * FROM ".tablename('dayu_form_skins'), array());
		if (empty($check_skins) && $_W['isfounder']) {
			message('请前往【提交页皮肤管理】导入皮肤', url('site/entry', array('do' => 'skins','m' => 'dayu_form_plugin_skins'), true, true), 'error');
//		}elseif (!empty($check_skins) && !$_W['isfounder']){
//			message('请联系管理员', '', 'error');
		}
	}
	$setting = $this->module['config'];