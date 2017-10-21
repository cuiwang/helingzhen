<?php
/**
 * [WECHAT 2017]
 
 */
defined('IN_IA') or exit('Access Denied');
load()->func('tpl');

$id = $uniacid = intval($_GPC['uniacid']);
if(!empty($id)) {
	$state = uni_permission($_W['uid'], $id);
	if($state != 'founder' && $state != 'manager') {
		message('没有该公众号操作权限！');
	}
	
	if(empty($_W['isfounder']) && is_error($permission = uni_create_permission($_W['uid'], 2))) {
		message($permission['message'], '' , 'error');
	}	
} else {
	message('主公众号信息丢失');
}


$step = intval($_GPC['step']) ? intval($_GPC['step']) : 2;
if($step == 1) {
	$account = pdo_fetch("SELECT * FROM ".tablename('uni_account')." WHERE uniacid = :id", array(':id' => $id));
	$settings = uni_setting($id, array('notify', 'groupdata'));
	$groupdata = $settings['groupdata'] ? $settings['groupdata'] : array('isexpire' => 0, 'oldgroupid' => '' ,'endtime' => TIMESTAMP);
	$notify = $settings['notify'] ? $settings['notify'] : array();
	$group = array();
	if (empty($_W['isfounder'])) {
		$group = pdo_fetch("SELECT * FROM ".tablename('users_group')." WHERE id = '{$_W['user']['groupid']}'");
		$group['package'] = uni_groups((array)iunserializer($group['package']));
	} else {
		$group['package'] = uni_groups();
	}
	
} elseif($step == 2) {
	$_GPC['step'] = 2;
} elseif($step == 3) {
	$flag = intval($_GPC['flag']);	
	$acid = intval($_GPC['acid']);
	if(checksubmit('submit') && $flag == 1) {
		load()->func('file');
		
		$type = intval($_GPC['type']) == 1 ? 'wechat' : 'yixin';
		$username = trim($_GPC['wxusername']);
		$password = md5(trim($_GPC['wxpassword']));
		if(!empty($username) && !empty($password)) {
			if ($type == 'wechat') {
				$loginstatus = account_weixin_login($username, $password, $_GPC['verify']);
				if(is_error($loginstatus)) {
					message($loginstatus['message'], url('account/post-acid', array('uniacid' => $uniacid, 'step' => 2)), 'error');
				}
				$basicinfo = account_weixin_basic($username);
			} elseif ($_GPC['type'] == 'yixin') {
				$loginstatus = account_yixin_login($username, $password, $_GPC['verify']);
				if(is_error($loginstatus)) {
					message($loginstatus['message'], url('account/post-acid', array('uniacid' => $uniacid, 'step' => 2)), 'error');
				}
				$basicinfo = account_yixin_basic($username);
			}
			if (empty($basicinfo['name'])) {
				message('一键获取信息失败,请手动设置公众号信息！', url('account/post-acid/', array('uniacid' => $uniacid, 'step' => 3)), 'error');
			}
			$account['username'] = trim($_GPC['wxusername']);
			$account['password'] = md5(trim($_GPC['wxpassword']));
			$account['lastupdate'] = TIMESTAMP;
			$account['name'] = $basicinfo['name'];
			$account['account'] = $basicinfo['account'];
			$account['original'] = $basicinfo['original'];
			$account['signature'] = $basicinfo['signature'];
			$account['key'] = $basicinfo['key'];
			$account['secret'] = $basicinfo['secret'];
			$account['type'] = intval($_GPC['type']);
			$account['level'] = $basicinfo['level'];
			$acid = account_create($uniacid, $account);
			if(is_error($acid)) {
				message('添加公众号信息失败', '', url('account/post-acid/', array('uniacid' => $uniacid, 'step' => 2), 'error'));
			}
			$oauth = uni_setting($uniacid, array('oauth'));
			if($acid && !empty($account['key']) && !empty($account['secret']) && empty($oauth['account']) && $account['level'] == 4) {
				pdo_update('uni_settings', array('oauth' => iserializer(array('status' => 1, 'account' => $acid))), array('uniacid' => $uniacid));
			}

						if (!empty($basicinfo['headimg'])) {
				file_write('headimg_'.$acid.'.jpg', $basicinfo['headimg']);
			}
			if (!empty($basicinfo['qrcode'])) {
				file_write('qrcode_'.$acid.'.jpg', $basicinfo['qrcode']);
			}
		} else {
			message('请填写公众平台用户名和密码', url('account/post-acid', array('uniacid' => $uniacid, 'step' => 2)), 'error');
		}
	}
	if(checksubmit('submit') && $flag == 2) {
		load()->func('file');
		$update['name'] = trim($_GPC['name']);
		if(empty($update['name'])) {
			message('公众号名称必须填写');
		}
		$update['account'] = trim($_GPC['account']);
		$update['original'] = trim($_GPC['original']);
		$update['level'] = intval($_GPC['level']);
		$update['key'] = trim($_GPC['key']);
		$update['secret'] = trim($_GPC['secret']);
		$update['type'] = intval($_GPC['type']);
		$account = account_fetch($acid);
		if(empty($account)) {
			$acid = account_create($uniacid, $update);
			if(is_error($acid)) {
				message('添加公众号信息失败', '', url('account/post-acid/', array('uniacid' => intval($_GPC['uniacid']), 'step' => 3, 'flag' => 1), 'error'));
			}
			$oauth = uni_setting($uniacid, array('oauth'));
			if($acid && !empty($update['key']) && !empty($update['secret']) && empty($oauth['oauth']['account'])) {
				pdo_update('uni_settings', array('oauth' => iserializer(array('status' => 1, 'account' => $acid))), array('uniacid' => $uniacid));
			}

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
		} else {
			pdo_update('account', array('type' => intval($_GPC['type']), 'hash' => ''), array('acid' => $acid, 'uniacid' => $uniacid));
	
			if($update['type'] == 1) {
				unset($update['type']);
				pdo_update('account_wechats', $update, array('acid' => $acid, 'uniacid' => $uniacid));
			} else if($update['type'] == 2) {
				unset($update['type']);
				pdo_update('account_yixin', $update, array('acid' => $acid, 'uniacid' => $uniacid));
	
			}else if($update['type'] == 3) {
				unset($update['type']);
				pdo_update('account_alipay', $update, array('acid' => $acid, 'uniacid' => $uniacid));
	
			}
			$oauth = uni_setting($uniacid, array('oauth'));
			if($acid && !empty($update['key']) && !empty($update['secret']) && empty($oauth['account'])) {
				pdo_update('uni_settings', array('oauth' => iserializer(array('status' => 1, 'account' => $acid))), array('uniacid' => $uniacid));
			}

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
	}
	if(!empty($acid)) {
		$account = account_fetch($acid);
	}
	
	if($flag == '2' && $acid) {
		header('Location:' . url('account/post-acid/', array('uniacid' => intval($_GPC['uniacid']), 'step' => 4, 'acid' => $acid)));
	}
} elseif($step == 4) {
	$uniacid = intval($_GPC['uniacid']);
	$acid = intval($_GPC['acid']);
	if(!$acid) {
		message('未填写公众号信息', '', url('account/post-acid/', array('uniacid' => intval($_GPC['uniacid']), 'step' => 3), 'error'));
	}
	$account = account_fetch($acid);
} 
template('account/post-acid');
