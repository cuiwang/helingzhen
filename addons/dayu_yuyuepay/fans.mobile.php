<?php
		load()->model('mc');
		$weid = $this->_weid;
		$openid = $this->_openid;
		
		$authurl = $_W['siteurl'].'&authkey=1';
		$url = $_W['siteurl'];
        if (isset($_COOKIE[$this->_auth2_openid])) {
            $openid = $_COOKIE[$this->_auth2_openid];
            $nickname = $_COOKIE[$this->_auth2_nickname];
            $headimgurl = $_COOKIE[$this->_auth2_headimgurl];
        } else {
            if (isset($_GPC['code'])) {
                $userinfo = $this->oauth2($authurl);
                if (!empty($userinfo)) {
                    $openid = $userinfo["openid"];
                    $nickname = $userinfo["nickname"];
                    $headimgurl = $userinfo["headimgurl"];
                } else {
					$this->showMessage('授权失败.');
                }
            } else {
                if (!empty($this->_appsecret)) {
                    $this->get_Code($url);
                }
            }
        }
		
		$_accounts = $accounts = uni_accounts();
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
//		$this->checkAuth($openid,$nickname,$headimgurl);
//		print_r($_W['fans']);
		$fans = mc_fansinfo($openid,$acid, $weid);
//		print_r($openid);
		$setting = $this->module['config'];
		
		$uid = !empty($_W['member']['uid']) ? $_W['member']['uid']:$fans['uid'];
		$member = mc_fetch($uid);
		$member['avatar'] = !empty($member['avatar']) ? $member['avatar'] : $headimgurl;
		$mcgroups = mc_groups();
		$member['group'] = $mcgroups[$member['groupid']];
		$isstaff = $this->get_staff($openid);
		$sid = $setting['store']==1 ? intval($_GPC['sid']) : '';
		if($setting['store']==1 && pdo_tableexists('dayu_yuyuepay_plugin_store_store') && !empty($sid)){
			$store = $this->get_store($sid);
			$store['score_num'] = $store['score_num'] == 0 ? 5 : round(($store['total_score']/$store['score_num']),0);
		}
		$index_url = $setting['store']==1 && pdo_tableexists('dayu_yuyuepay_plugin_store_store') ? murl('entry', array('do' => 'store', 'm' => 'dayu_yuyuepay_plugin_store'), true, true) : $this->createMobileUrl('list');
		$manage_url = $setting['store']==1 && pdo_tableexists('dayu_yuyuepay_plugin_store_store') ? murl('entry', array('do' => 'boss', 'm' => 'dayu_yuyuepay_plugin_store'), true, true) : $this->createMobileUrl('manager');
?>