<?php
global $_W, $_GPC;
//判断用户信息是否存在，不存在添加
define('WL_USER_AGENT', m('member')->check_explorer());

m('member') -> checkMember(); 
$fun = check_appcon($name,$dir);

$openid = m('user') -> getOpenid();

if ($_GPC['do'] != 'index' && $_GPC['do']!='endbuy' && $_GPC['do'] != 'allgoods' &&  $_GPC['do'] != 'jump' &&  $_GPC['do'] != 'login' && $_GPC['do'] != 'webhooks') {
	if(WL_USER_AGENT == 'weixin'){
		//微信端访问
		
	}else{
		//wap端访问
		$session = json_decode(base64_decode($_GPC['__login_session']), true);
		$account = $session['account'];
		if(!empty($account)){
			$meaccount = intval($account);
			if(empty($meaccount)){
				$check = 1;
			}else{
				$password = $session['password'];
				$check = pdo_fetch("select mid from".tablename('weliam_indiana_member')."where uniacid=:uniacid and account=:account and password=:password",array(':uniacid'=>$_W['uniacid'],':account'=>$account,':password'=>$password));
			}
		}
		if(!is_array($session) || empty($check)){
			header("Location: index.php?i={$_W['uniacid']}&c=entry&do=login&m=weliam_indiana&op=login");
			exit;
		}
	}
}

//判定是否授权
if($_GPC['do'] == 'login' && $_GPC['op'] == 'accreditLogin' && ($_GPC['token']=='' || empty($_GPC['token']))){
	$cookies = array();
	$re = pdo_fetch("select openid from".tablename("weliam_indiana_member")."where uniacid=:uniacid and appopenid=:appopenid",array(':uniacid'=>$_W['uniacid'],':appopenid'=>$_GPC['openid']));
	$cookies['account'] = $re['openid'];
	isetcookie('__login_session', base64_encode(json_encode($cookies)), 604800, true);
}

$file = $fun['dir'] . $fun['fun'] . '.inc.php';
if(!file_exists($file)) {
	header("Location: index.php?i={$_W['uniacid']}&c=entry&do=index&m=weliam_indiana");
	exit;
}
require $file;