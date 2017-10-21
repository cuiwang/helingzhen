<?php
/**
 * [WeEngine System] Copyright (c) 2013 012wz.com
 */
defined('IN_IA') or exit('Access Denied');
if(checksubmit()) {
	_login($_GPC['referer']);
}
$setting = $_W['setting'];
$item = pdo_fetch("SELECT htname,bgcolor,banner1,banner2,banner3,banner4 FROM " . tablename('wx_school_set') . "");
template('user/login');

function _login($forward = '') {
	global $_GPC, $_W;
	load()->model('user');
	if (!empty($_W['setting']['copyright']['verifycode'])) {
		$verify = trim($_GPC['verify']);
		if(empty($verify)) {
			message('请输入验证码');
		}
		$result = checkcaptcha($verify);
		if (empty($result)) {
			message('输入验证码错误');
		}
	}	
	$username = trim($_GPC['username']);
	if(empty($username)) {
		message('请输入要登录的用户名');
	}
	$password = trim($_GPC['password']);
	if(empty($password)) {
		message('请输入密码');
	}
	$member['username'] = $username;
	$member['password'] = $_GPC['password'];

	$record = user_single($member);
	if(!empty($record)) {
		if($record['status'] == 1) {
			message('您的账号正在审核或是已经被系统禁止，请联系网站管理员解决！');
		}
		$founders = explode(',', $_W['config']['setting']['founder']);
		$_W['isfounder'] = in_array($record['uid'], $founders);
		if (!empty($_W['siteclose']) && empty($_W['isfounder'])) {
			message('站点已关闭，关闭原因：' . $_W['setting']['copyright']['reason']);
		}
		$cookie = array();
		$cookie['uid'] = $record['uid'];
		$cookie['lastvisit'] = $record['lastvisit'];
		$cookie['lastip'] = $record['lastip'];
		$cookie['hash'] = md5($record['password'] . $record['salt']);
		$session = base64_encode(json_encode($cookie));
		isetcookie('__session', $session, !empty($_GPC['rember']) ? 7 * 86400 : 0, true);
		$status = array();
		$status['uid'] = $record['uid'];
		$status['lastvisit'] = TIMESTAMP;
		$status['lastip'] = CLIENT_IP;
		user_update($status);

		if(empty($forward)) {
			$forward = $_GPC['forward'];
		}

		if ($record['uid'] != $_GPC['__uid']) {
			isetcookie('__uniacid', '', -7 * 86400);
			isetcookie('__uid', '', -7 * 86400);
		}

		pdo_delete('users_failed_login', array('id' => $failed['id']));
		//message("欢迎回来，{$record['username']}。", $forward);
		//header('Location:' . url('account/switch', array('uniacid' => 3)));
		$uniacid = intval($record['uniacid']);
		$schoolid = intval($record['schoolid']);
		$role = uni_permission($status['uid'], $uniacid);
		if(empty($role)) {
			message('操作失败, 非法访问.');
		}
		if(empty($schoolid)) {
			message('操作失败, 非法访问.');
		}
		$logo = pdo_fetch("SELECT is_openht FROM " . tablename('wx_school_index') . " WHERE id = '{$schoolid}'");	
		if($logo['is_openht'] == 2) {
			message('抱歉!本站点已经关闭,请联系管理员.');
		}		
		isetcookie('__uniacid', $uniacid, 7 * 86400);
		isetcookie('__uid', $status['uid'], 7 * 86400);
		header('Location:'.$_W['siteroot'].'web/index.php?c=site&a=entry&do=start&id='.$schoolid.'&schoolid='.$schoolid.'&m=fm_jiaoyu');
	} else {
		if (empty($failed)) {
			pdo_insert('users_failed_login', array('ip' => CLIENT_IP, 'username' => $username, 'count' => '1', 'lastupdate' => TIMESTAMP));
		} else {
			pdo_update('users_failed_login', array('count' => $failed['count'] + 1, 'lastupdate' => TIMESTAMP), array('id' => $failed['id']));
		}
		message('登录失败，请检查您输入的用户名和密码！');
	}
}

