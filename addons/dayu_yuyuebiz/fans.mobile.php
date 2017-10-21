<?php
		$weid = $_W['uniacid'];
		$openid=$_W['openid'];
		$uid = $_W['member']['uid'];
		
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
		
		$fans = mc_fansinfo($openid,$acid, $weid);
		$profile = mc_fetch($fans['uid'], array('realname', 'nickname', 'avatar', 'mobile', 'groupid', 'credit1'));
		$mcgroups = mc_groups();
		$profile['group'] = $mcgroups[$profile['groupid']];
				
//		$oauth_openid="dayu_yuyuebiz_".$weid;
//		if (empty($_COOKIE[$oauth_openid])) {
//			$this->getCode();
//		}
?>