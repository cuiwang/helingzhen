<?php
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
define('IN_SYS', true);
require '../framework/bootstrap.inc.php';
require IA_ROOT . '/web/common/bootstrap.sys.inc.php';
load()->web('common');
load()->web('template');

if (empty($_W['isfounder']) && !empty($_W['user']) && ($_W['user']['status'] == USER_STATUS_CHECK || $_W['user']['status'] == USER_STATUS_BAN)) {
	message('您的账号正在审核或是已经被系统禁止，请联系网站管理员解决！');
}
$_W['acl'] = $acl = array(
	'account' => array(
		'default' => '',
		'direct' => array(
			'auth',
			'welcome' 
		),
		'operator' => array(
			'display',
			'manage' 
		) 
	),
	'article' => array(
		'direct' => array(
			'notice-show',
			'news-show',
			'case-show',
			'plug-show',
			'link-show',
			'about-show',
			'agent-show',
			'agent-list',
			'product-show'
		),
		'founder' => array(
			'news',
			'notice',
			'case',
			'plug',
			'link',
			'about',
			'agent'
			
		),
		'vice-founder' => array(
			'notice-show',
			'news-show'
		),
	),
	'cloud' => array(
		'default' => 'touch',
		'direct' => array(
			'touch',
			'dock',
			'download' 
		),
		'founder' => array(
			'diagnose',
			'redirect',
			'upgrade',
			'process',
			'device' 
		),
		'vice-founder' => array(),
	),
	'home' => array(
		'default' => 'welcome',
		'founder' => array(),
		'direct' => array() 
	),
	'platform' => array(
		'default' => 'reply',
		'founder' => array(),
		'direct' => array(
			'link' 
		) 
	),
	'site' => array(
		'default' => '',
		'founder' => array(),
		'direct' => array(
			'entry' 
		) 
	),
	'user' => array(
		'default' => 'display',
		'founder' => array(
			'edit',
			'group',
			'ymmanage'
		),
		'direct' => array(
			'login',
			'register',
			'logout',
			'myxiaji',
			'myxiajiedit',
			'myxiajiadd'
		) 
	),
	'utility' => array(
		'direct' => array(
			'verifycode',
			'code',
			'file',
			'bindcall',
			'subscribe',
			'wxcode',
			'modules' 
		) 
	),
	'module' => array(
		'direct' => array(),
		'founder' => array(),
		'manager' => array(
			'group'
		),
		'operator' => array()
	),
	'system' => array(
		'direct' => array(),
		'founder' => array(
			'attachment',
			'bom',
			'database',
			'filecheck',
			'logs',
			'menu',
			'optimize',
			'scan',
			'site',
			'module'
		),
		'operator' => array(
			'account',
			'updatecache' 
		),
		'manager' => array(
			'account',
			'module-group',
			'platform',
			'updatecache',
			'module' 
		),
		'vice-founder' => array(
			'platform',
			'template',
			'updatecache'
		),
	),
	'website' => array(
		'direct' => array(
			'sdp-show',
			'xiaoqu-show',
			'canting-show',
			'taocan-show',
			'wenda-show',
		),
		'founder' => array(
			'sdp',
			'xiaoqu',
			'canting',
			'taocan',
			'wenda'
			
		)
	),
	'agent' => array(
		'direct' => array(
			'agent_login',
			'agent',
			'agent_info',
			'agent_show',
			'agent_site',
			'agent_user',
			'logout'
		)
	),
	'fournet' => array(
		'direct' => array(
			'wxauth_api'
		)
	),
	'shop' => array(
		'direct' => array(
		),
		'founder' => array(
			'module',
			'muser',
			'mkdel',
			'mpayset',
			'maddmod',
			'taocan',
			'mpayresult',
			'mgetpayresult',
			'mmodset',
		)
	),
	'cron' => array(
		'direct' => array(
			'entry'
		)
	)
);
if (($_W['setting']['copyright']['status'] == 1) && empty($_W['isfounder']) && $controller != 'cloud' && $controller != 'utility' && $controller != 'account') {
	$_W['siteclose'] = true;
	if ($controller == 'account' && $action == 'welcome') {
		template('account/welcome');
		exit();
	}
	if ($controller == 'user' && $action == 'login') {
		if (checksubmit()) {
			require _forward($controller, $action);
		}
		template('user/login');
		exit();
	}
	isetcookie('__session', '', - 10000);
	message('站点已关闭，关闭原因：' . $_W['setting']['copyright']['reason'], url('account/welcome'), 'info');
}

$controllers = array();
$handle = opendir(IA_ROOT . '/web/source/');
if (! empty($handle)) {
	while ($dir = readdir($handle)) {
		if ($dir != '.' && $dir != '..') {
			$controllers[] = $dir;
		}
	}
}
if (! in_array($controller, $controllers)) {
	$controller = 'home';
}

$init = IA_ROOT . "/web/source/{$controller}/__init.php";
if (is_file($init)) {
	require $init;
}

$actions = array();
$handle = opendir(IA_ROOT . '/web/source/' . $controller);
if (! empty($handle)) {
	while ($dir = readdir($handle)) {
		if ($dir != '.' && $dir != '..' && strexists($dir, '.ctrl.php')) {
			$dir = str_replace('.ctrl.php', '', $dir);
			$actions[] = $dir;
		}
	}
}
if (empty($actions)) {
	header('location: ?refresh');
}
if (! in_array($action, $actions)) {
	$action = $acl[$controller]['default'];
}
if (! in_array($action, $actions)) {
	$action = $actions[0];
}

$_W['page'] = array();
$_W['page']['copyright'] = $_W['setting']['copyright'];

if (is_array($acl[$controller]['direct']) && in_array($action, $acl[$controller]['direct'])) {
		require _forward($controller, $action);
	exit();
}
if (is_array($acl[$controller]['founder']) && in_array($action, $acl[$controller]['founder'])) {
		if (! $_W['isfounder']) {
		message('不能访问, 需要创始人权限才能访问.');
	}
}
if (user_is_vice_founder($_W['uid']) && is_array($acl[$controller]['vice-founder']) && !in_array($action, $acl[$controller]['vice-founder'])) {
	message('不能访问, 需要相应的权限才能访问.');
}
checklogin();
require _forward($controller, $action);

define('ENDTIME', microtime());
if (empty($_W['config']['setting']['maxtimeurl'])) {
	$_W['config']['setting']['maxtimeurl'] = 10;
}
if ((ENDTIME - STARTTIME) > $_W['config']['setting']['maxtimeurl']) {
	$data = array(
		'type' => '1',
		'runtime' => ENDTIME - STARTTIME,
		'runurl' => $_W['sitescheme'] . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
		'createtime' => TIMESTAMP 
	);
	pdo_insert('core_performance', $data);
}
function _forward($c, $a) {
	$file = IA_ROOT . '/web/source/' . $c . '/' . $a . '.ctrl.php';
	return $file;
}
function _calc_current_frames(&$frames) {
	global $controller, $action;
	if (! empty($frames['section']) && is_array($frames['section'])) {
		foreach ($frames['section'] as &$frame) {
			if (empty($frame['menu'])) {
				continue;
			}
			foreach ($frame['menu'] as &$menu) {
				$query = parse_url($menu['url'], PHP_URL_QUERY);
				parse_str($query, $urls);
				if (empty($urls)) {
					continue;
				}
				if (defined('ACTIVE_FRAME_URL')) {
					$query = parse_url(ACTIVE_FRAME_URL, PHP_URL_QUERY);
					parse_str($query, $get);
				} else {
					$get = $_GET;
					$get['c'] = $controller;
					$get['a'] = $action;
				}
				if (! empty($do)) {
					$get['do'] = $do;
				}
				$diff = array_diff_assoc($urls, $get);
				if (empty($diff)) {
					$menu['active'] = ' active';
				}
			}
		}
	}
}