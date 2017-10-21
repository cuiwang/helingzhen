<?php
/**
 * [WECHAT 2017]
 
 */
defined('IN_IA') or exit('Access Denied');
$dos = array('post', 'delete', 'display', 'details');
$do = in_array($_GPC['do'], $dos) ? $_GPC['do']: 'display';
$acid = intval($_GPC['acid']);
$uniacid = intval($_GPC['uniacid']);

if(!empty($uniacid)) {
	$uniaccount = pdo_fetch("SELECT * FROM ".tablename('uni_account')." WHERE uniacid = :uniacid", array(':uniacid' => $uniacid));
	if(empty($uniaccount)) {
		message('公众号不存在或已被删除！');
	}
	$state = uni_permission($_W['uid'],$uniacid);
	if($state != 'founder' && $state != 'manager') {
		message('没有该公众号操作权限！');
	}
}

$settings = uni_setting($uniacid, array('notify', 'groupdata'));
$groupdata = $settings['groupdata'] ? $settings['groupdata'] : array('isexpire' => 0, 'oldgroupid' => '' ,'endtime' => TIMESTAMP);
$notify = $settings['notify'] ? $settings['notify'] : array();
$data = uni_groups();
$groups = array();
foreach($data as $da){
	$groups[$da['id']] = $da;
}
$groups[0] = array('id' => 0, 'name' => '基础服务');
$groups[-1] = array('id' => -1, 'name' => '所有服务');

if ($do == 'post') {
	$_W['page']['title'] = '编辑子公众号 - 编辑主公众号';
		if(empty($acid)) {
		$_W['page']['title'] = '添加子公众号 - 编辑主公众号';
		if(empty($_W['isfounder']) && is_error($error = uni_create_permission($_W['uid'], 2))) {
			message($error['message'], '' , 'error');
		}
	}
	load()->func('tpl');
	load()->func('file');
	if (checksubmit('submit')) {
		if ($_GPC['type'] == 2) {
			$tablename = 'account_yixin';
			$type = 'yixin';
		} else {
			$tablename = 'account_wechats';
			$type = 'wechat';
		}
		$account = array();
				if (!empty($_GPC['model']) && $_GPC['model'] == 2) {
			$username = $_GPC['wxusername'];
			$password = md5($_GPC['wxpassword']);
			
			if (!empty($username) && !empty($password)) {
				if ($type == 'wechat') {
					$loginstatus = account_weixin_login($username, $password, $_GPC['verify']);
					$basicinfo = account_weixin_basic($username);
				} elseif ($_GPC['type'] == 'yixin') {
					$loginstatus = account_yixin_login($username, $password, $_GPC['verify']);
					$basicinfo = account_yixin_basic($username);
				}
				if (empty($basicinfo['name'])) {
					message('一键获取信息失败，请手动添加该公众帐号并反馈此信息给管理员！');
				}
				$account['username'] = $_GPC['wxusername'];
				$account['password'] = md5($_GPC['wxpassword']);
				$account['lastupdate'] = TIMESTAMP;
				$account['name'] = $basicinfo['name'];
				$account['account'] = $basicinfo['account'];
				$account['original'] = $basicinfo['original'];
				$account['signature'] = $basicinfo['signature'];
				$account['key'] = $basicinfo['key'];
				$account['secret'] = $basicinfo['secret'];
				$account['type'] = intval($_GPC['type']);
				$account['level'] = $basicinfo['level'];
			}
		} else {
			if (empty($_GPC['name'])) {
				message('抱歉，名称和公众号账号为必填项请返回填写！');
			}
			if (!empty($_GPC['subscribeurl']) && (!preg_match("/^(http|https|)\:\/\/[^\s]*\.[^\s]*\.[^\s]/", $_GPC['subscribeurl']))) {
				message('抱歉，引导素材链接必须为合法的URL！');
			}
			$account['name'] = $_GPC['name'];
			$account['account'] = $_GPC['account'];
			$account['original'] = $_GPC['original'];
			$account['subscribeurl'] = $_GPC['subscribeurl'];
			$account['level'] = intval($_GPC['level']);
			$account['key'] = $_GPC['key'];
			$account['secret'] = $_GPC['secret'];
			$account['type'] = intval($_GPC['type']);
			$account['topad'] = $_GPC['topad'];
			$account['footad'] = $_GPC['footad'];
		}
		if (empty($acid)) {
			$acid = account_create($uniacid, $account);
			$oauth = uni_setting($uniacid, array('oauth'));
			if($acid && !empty($account['key']) && !empty($account['secret']) && empty($oauth['oauth']['account']) && $account['level'] == 4) {
				pdo_update('uni_settings', array('oauth' => iserializer(array('status' => 1, 'account' => $acid))), array('uniacid' => $uniacid));
			}
		} else {
			$account['token'] = $_GPC['wetoken'];
			$account['encodingaeskey'] = $_GPC['encodingaeskey'];
			unset($account['type']);
			pdo_update($tablename, $account, array('acid' => $acid, 'uniacid' => $uniacid));
			$oauth = uni_setting($uniacid, array('oauth'));
			if(!is_array($oauth['oauth'])) {
				$oauth['oauth'] = array();
			}
			if(!empty($account['key']) && !empty($account['secret']) && empty($oauth['oauth']['account']) && $account['level'] == 4) {
				pdo_update('uni_settings', array('oauth' => iserializer(array('status' => 1, 'account' => $acid))), array('uniacid' => $uniacid));
			} elseif($oauth['oauth']['account'] == $acid && (empty($account['secret']) || empty($account['key']) || $account['level'] != 4)) {
				$account_u = pdo_fetch('SELECT * FROM ' . tablename($tablename) . " WHERE uniacid = :id AND level = 4 AND secret != '' AND `key` != '' ", array(':id' => $uniacid));
				if(!empty($account_u)) {
					$oauth_u = iserializer(array('status' => 1, 'account' => $account_u['acid']));
				} else {
					$oauth_u = '';
				}
				pdo_update('uni_settings', array('oauth' => $oauth_u), array('uniacid' => $uniacid));
			}
		} 
		
		if ($_GPC['model'] == 2) {
			if (!empty($basicinfo['headimg'])) {
				file_write('headimg_'.$acid.'.jpg', $basicinfo['headimg']);
			}
			if (!empty($basicinfo['qrcode'])) {
				file_write('qrcode_'.$acid.'.jpg', $basicinfo['qrcode']);
			}
		
			if (!empty($loginstatus)) {
								if ($type == 'wechat') {
					$account['id'] = $acid;
					$result = account_weixin_interface($account['username'], $account);
					if (is_error($result)) {
						$error = $result['message'];
					}
					if (!empty($result)) {
						pdo_update('account', array('isconnect' => 1), array('acid' => $acid));
					}
				}
			}
		} else {
			if (!empty($_FILES['qrcode']['tmp_name'])) {
				$_W['uploadsetting'] = array();
				$_W['uploadsetting']['image']['folder'] = '';
				$_W['uploadsetting']['image']['extentions'] = array('jpg');
				$_W['uploadsetting']['image']['limit'] = $_W['config']['upload']['image']['limit'];
				$upload = file_upload($_FILES['qrcode'], 'image', "qrcode_{$acid}");
			}
			if (!empty($_FILES['headimg']['tmp_name'])) {
				$_W['uploadsetting'] = array();
				$_W['uploadsetting']['image']['folder'] = '';
				$_W['uploadsetting']['image']['extentions'] = array('jpg');
				$_W['uploadsetting']['image']['limit'] = $_W['config']['upload']['image']['limit'];
				$upload = file_upload($_FILES['headimg'], 'image', "headimg_{$acid}");
			}
			
		}
		
		message('更新子公众号成功！', url('account/bind/post', array('acid' => $acid, 'uniacid' => $uniacid)), 'success');
	}
	$account = account_fetch($acid);
	template('account/bind');
}

if ($do == 'delete') {
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('account') . ' WHERE uniacid = :uniacid', array(':uniacid' => $uniacid));
	if($total == 1) {
		message('主公众号下必须有一个子公号,不能删除该子公号', referer(), 'info');
	}
	$account = account_fetch($acid);
	pdo_delete('account', array('acid' => $acid, 'uniacid' => $uniacid));
	if ($account['type'] == '1') {
		pdo_delete('account_wechats', array('acid' => $acid, 'uniacid' => $uniacid));
	} elseif ($account['type'] == '2') {
		pdo_delete('account_yixin', array('acid' => $acid, 'uniacid' => $uniacid));
	} else {
		pdo_delete('account_wechats', array('acid' => $acid, 'uniacid' => $uniacid));
		pdo_delete('account_yixin', array('acid' => $acid, 'uniacid' => $uniacid));
	}
	$oauth = uni_setting($uniacid, array('oauth'));
	if($oauth['oauth']['account'] == $acid && $account['type'] == 1) {
		$account_u = pdo_fetch('SELECT * FROM ' . tablename('account_wechats') . " WHERE uniacid = :id AND level = 4 AND secret != '' AND `key` != ''", array(':id' => $uniacid));
		if(!empty($account_u)) {
			$oauth_u = iserializer(array('status' => 1, 'account' => $account_u['acid']));
		} else {
			$oauth_u = '';
		}
		pdo_update('uni_settings', array('oauth' => $oauth_u), array('uniacid' => $uniacid));
	}

	@unlink(IA_ROOT . '/attachment/qrcode_'.$acid.'.jpg');
	@unlink(IA_ROOT . '/attachment/headimg_'.$acid.'.jpg');
	message('删除子公众号成功！', referer(), 'success');
}

if ($do == 'details') {
	load()->func('tpl');
	$account = account_fetch($acid);
	if(empty($account)) {
		message('公众号不存在或已被删除', '', 'error');
	}
	
	$_W['page']['title'] = $account['name'] . ' - 公众号详细信息';
	$uniaccount = pdo_fetchcolumn('SELECT name FROM ' . tablename('uni_account') . ' WHERE uniacid = :uniacid', array(':uniacid' => $account['uniacid']));
	$uid = pdo_fetchcolumn('SELECT uid FROM ' . tablename('uni_account_users') . ' WHERE uniacid = :uniacid', array(':uniacid' => $account['uniacid']));
	$username = pdo_fetchcolumn('SELECT username FROM ' . tablename('users') . ' WHERE uid = :uid', array(':uid' => $uid));
	
	$scroll = intval($_GPC['scroll']);
	$add_num = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_mapping_fans') . ' WHERE acid = :acid AND uniacid = :uniacid AND follow = :follow AND followtime >= :starttime AND followtime <= :endtime', array(':acid' => $acid, ':uniacid' => $uniacid, ':starttime' => strtotime(date('Y-m-d')) - 86400, ':endtime' => strtotime(date('Y-m-d')), ':follow' => 1));
	$cancel_num = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_mapping_fans') . ' WHERE acid = :acid AND uniacid = :uniacid AND follow = :follow AND unfollowtime >= :starttime AND unfollowtime <= :endtime', array(':acid' => $acid, ':uniacid' => $uniacid, ':starttime' => strtotime(date('Y-m-d')) - 86400, ':endtime' => strtotime(date('Y-m-d')), ':follow' => 0));
	$jing_num = $add_num - $cancel_num;
	$total_num = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_mapping_fans') . ' WHERE acid = :acid AND uniacid = :uniacid AND follow = :follow AND followtime <= :endtime', array(':acid' => $acid, ':uniacid' => $uniacid, ':endtime' => strtotime(date('Y-m-d')), ':follow' => 1));

	$today_add_num = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_mapping_fans') . ' WHERE acid = :acid AND uniacid = :uniacid AND follow = :follow AND followtime >= :starttime AND followtime <= :endtime', array(':acid' => $acid, ':uniacid' => $uniacid, ':starttime' => strtotime(date('Y-m-d')), ':endtime' => TIMESTAMP, ':follow' => 1));
	$today_cancel_num = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_mapping_fans') . ' WHERE acid = :acid AND uniacid = :uniacid AND follow = :follow AND unfollowtime >= :starttime AND unfollowtime <= :endtime', array(':acid' => $acid, ':uniacid' => $uniacid, ':starttime' => strtotime(date('Y-m-d')), ':endtime' => TIMESTAMP, ':follow' => 0));
	$today_jing_num = $today_add_num - $today_cancel_num;
	$today_total_num = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_mapping_fans') . ' WHERE acid = :acid AND uniacid = :uniacid AND follow = :follow AND followtime <= :endtime', array(':acid' => $acid, ':uniacid' => $uniacid, ':endtime' => TIMESTAMP, ':follow' => 1));

	$st = $_GPC['datelimit']['start'] ? strtotime($_GPC['datelimit']['start']) : strtotime('-30day');
	$et = $_GPC['datelimit']['end'] ? strtotime($_GPC['datelimit']['end']) : strtotime(date('Y-m-d'));
	$starttime = min($st, $et);
	$endtime = max($st, $et);
	$day_num = ($endtime - $starttime) / 86400 + 1;
	$endtime += 86399;
	$type = intval($_GPC['type']) ? intval($_GPC['type']) : 1;
		if($_W['isajax'] && $_W['ispost']) {
		$days = array();
		$datasets = array();
		for($i = 0; $i < $day_num; $i++){
			$key = date('m-d', $starttime + 86400 * $i);
			$days[$key] = 0;
			$datasets['flow1'][$key] = 0;
			$datasets['flow2'][$key] = 0;
			$datasets['flow3'][$key] = 0;
			$datasets['flow4'][$key] = 0;
		}

				$data = pdo_fetchall('SELECT * FROM ' . tablename('mc_mapping_fans') . ' WHERE acid = :acid AND uniacid = :uniacid AND follow = :follow AND followtime >= :starttime AND followtime <= :endtime', array(':acid' => $acid, ':uniacid' => $uniacid, ':starttime' => $starttime, ':endtime' => $endtime, ':follow' => 1));
		foreach($data as $da) {
			$key = date('m-d', $da['followtime']);
			if(in_array($key, array_keys($days))) {
				$datasets['flow1'][$key]++;
			}
		}

				$data = pdo_fetchall('SELECT * FROM ' . tablename('mc_mapping_fans') . ' WHERE acid = :acid AND uniacid = :uniacid AND follow = :follow AND unfollowtime >= :starttime AND unfollowtime <= :endtime', array(':acid' => $acid, ':uniacid' => $uniacid, ':starttime' => $starttime, ':endtime' => $endtime, ':follow' => 0));
		foreach($data as $da) {
			$key = date('m-d', $da['unfollowtime']);
			if(in_array($key, array_keys($days))) {
				$datasets['flow2'][$key]++;
			}
		}

				$data0 = pdo_fetchall('SELECT * FROM ' . tablename('mc_mapping_fans') . ' WHERE acid = :acid AND uniacid = :uniacid AND follow = :follow AND unfollowtime >= :starttime AND unfollowtime <= :endtime', array(':acid' => $acid, ':uniacid' => $uniacid, ':starttime' => $starttime, ':endtime' => $endtime, ':follow' => 0));
		$data1 = pdo_fetchall('SELECT * FROM ' . tablename('mc_mapping_fans') . ' WHERE acid = :acid AND uniacid = :uniacid AND follow = :follow AND followtime >= :starttime AND followtime <= :endtime', array(':acid' => $acid, ':uniacid' => $uniacid, ':starttime' => $starttime, ':endtime' => $endtime, ':follow' => 1));
		foreach($data1 as $da) {
			$key = date('m-d', $da['followtime']);
			if(in_array($key, array_keys($days))) {
				$day[date('m-d', $da['followtime'])] ++;
				$datasets['flow3'][$key]++;
			}
		}
		foreach($data0 as $da) {
			$key = date('m-d', $da['unfollowtime']);
			if(in_array($key, array_keys($days))) {
				$datasets['flow3'][$key]--;
			}
		}

				for($i = 0; $i < $day_num; $i++){
			$key = date('m-d', $starttime + 86400 * $i);
			$datasets['flow4'][$key] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_mapping_fans') . ' WHERE acid = :acid AND uniacid = :uniacid AND follow = :follow AND followtime < ' . ($starttime + 86400 * $i + 86439), array(':acid' => $acid, ':uniacid' => $uniacid, ':follow' => 1));;
		}

		$shuju['label'] = array_keys($days);
		$shuju['datasets'] = $datasets;
		
		if ($day_num == 1) {
			$day_num = 2;
			$shuju['label'][] = $shuju['label'][0];
			
			foreach ($shuju['datasets']['flow1'] as $ky => $va) {
				$k = $ky;
				$v = $va;
			}
			$shuju['datasets']['flow1']['-'] = $v;
			
			foreach ($shuju['datasets']['flow2'] as $ky => $va) {
				$k = $ky;
				$v = $va;
			}
			$shuju['datasets']['flow2']['-'] = $v;
			
			foreach ($shuju['datasets']['flow3'] as $ky => $va) {
				$k = $ky;
				$v = $va;
			}
			$shuju['datasets']['flow3']['-'] = $v;
			
			foreach ($shuju['datasets']['flow4'] as $ky => $va) {
				$k = $ky;
				$v = $va;
			}
			$shuju['datasets']['flow4']['-'] = $v;
		}

		$shuju['datasets']['flow1'] = array_values($shuju['datasets']['flow1']);
		$shuju['datasets']['flow2'] = array_values($shuju['datasets']['flow2']);
		$shuju['datasets']['flow3'] = array_values($shuju['datasets']['flow3']);
		$shuju['datasets']['flow4'] = array_values($shuju['datasets']['flow4']);
		exit(json_encode($shuju));
	}
	template('account/details');
}
