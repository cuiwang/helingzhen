<?php
/**
 * [WECHAT 2017]
 
 */
define('FRAME', 'account');
if(!empty($_GPC['f']) && $_GPC['f'] == 'multi') {
	define('ACTIVE_FRAME_URL', url('fournet/pcmulti/display'));
}
$sysmodules = system_modules();
if(!empty($_GPC['styleid'])) {
	define('ACTIVE_FRAME_URL', url('fournet/pcstyle/styles'));
}

if($controller == 'fournet') {
	$m = $_GPC['m'];
	if(!empty($m)) {
		if(in_array($m, $sysmodules)) {
			define('FRAME', 'platform');
			define('CRUMBS_NAV', 2);
			define('ACTIVE_FRAME_URL', url('platform/reply/', array('m' => $m)));
		} else {
			if($action == 'nav' && $_COOKIE['ext_type'] == 1) {
				$do = trim($_GPC['do']);
				define('ACTIVE_FRAME_URL', url("fournet/pcnav/{$do}", array('m' => $m)));
			}
		}
	}
}

if ($action == 'editor' && $_GPC['type'] == '4') {
	define('ACTIVE_FRAME_URL', url('fournet/pceditor/uc'));
}
if (!empty($_GPC['multiid'])) {
	define('ACTIVE_FRAME_URL', url('fournet/pcmulti/display'));
}