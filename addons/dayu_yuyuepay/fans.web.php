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
	$uc = pdo_fetch("SELECT `uc`,`passport` FROM ".tablename('uni_settings') . " WHERE uniacid = :uniacid", array(':uniacid' => $_W['uniacid']));
	$passport = @iunserializer($uc['passport']);
	
	if(!is_array($passport)) {
		$passport = array();
	}
//	if (empty($m_card)) {
//		$url = IMS_VERSION<'0.8' ? url('mc/card/editor') : url('home/welcome/ext', array('m' => 'we7_coupon'));
//		message('公众号尚未开启会员卡功能', $url, 'error');
//	}

	if ($settings['sync']!=1) {
		message('需要开启粉丝同步', url('mc/passport/sync'), 'error');
	}
	if (!empty($passport['focusreg'])) {
		message('会员注册设置 需开启【自动注册】', url('mc/passport/passport'), 'error');
	}
	$setting = $this->module['config'];
	if (empty($setting['qqkey'])) {
		message('腾讯地图key不能为空', url('profile/module/setting').'m=dayu_yuyuepay', 'error');
	}