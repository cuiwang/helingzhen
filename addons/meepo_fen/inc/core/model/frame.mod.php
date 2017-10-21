<?php
function _calc_current_frames2(&$frames) {
	global $_W,$_GPC;
	if(!empty($frames) && is_array($frames)) {
		foreach($frames as &$frame) {
			foreach($frame['items'] as &$fr) {
				$query = parse_url($fr['url'], PHP_URL_QUERY);
				parse_str($query, $urls);
				if(defined('ACTIVE_FRAME_URL')) {
					$query = parse_url(ACTIVE_FRAME_URL, PHP_URL_QUERY);
					parse_str($query, $get);
				} else {
					$get = $_GET;
				}
				if(!empty($_GPC['a'])) {
					$get['a'] = $_GPC['a'];
				}
				if(!empty($_GPC['c'])) {
					$get['c'] = $_GPC['c'];
				}
				if(!empty($_GPC['do'])) {
					$get['do'] = $_GPC['do'];
				}
				if(!empty($_GPC['doo'])) {
					$get['doo'] = $_GPC['doo'];
				}
				if(!empty($_GPC['act'])) {
					$get['act'] = $_GPC['act'];
				}
				if(!empty($_GPC['state'])) {
					$get['state'] = $_GPC['state'];
				}
				if(!empty($_GPC['op'])) {
					$get['op'] = $_GPC['op'];
				}
				if(!empty($_GPC['m'])) {
					$get['m'] = $_GPC['m'];
				}
				$diff = array_diff_assoc($urls, $get);

				if(empty($diff)) {
					$fr['active'] = ' active';
				}
			}
		}
	}
}

function getModuleFrames($name){
	global $_W;
	$sql = "SELECT * FROM ".tablename('modules')." WHERE name = :name limit 1";
	$params = array(':name'=>$name);
	$module = pdo_fetch($sql,$params);
	
	$sql = "SELECT * FROM ".tablename('modules_bindings')." WHERE module = :name ";
	$params = array(':name'=>$name);
	$module_bindings = pdo_fetchall($sql,$params);
	
	$frames = array();
	
	$frames['manage']['title'] = '管理';
	$frames['manage']['items'] = array();
	
	$frames['setting']['title'] = '设置';
	$frames['setting']['items'] = array();
	
	$frames['setting']['items']['set']['url'] = url('site/entry/set/',array('m'=>$name));
	$frames['setting']['items']['set']['title'] = '系统设置';
	$frames['setting']['items']['set']['actions'] = array();
	$frames['setting']['items']['set']['active'] = '';
	
	$frames['setting']['items']['new']['url'] = url('site/entry/new/',array('m'=>$name));
	$frames['setting']['items']['new']['title'] = '首次关注';
	$frames['setting']['items']['new']['actions'] = array();
	$frames['setting']['items']['new']['active'] = '';
	
	$frames['setting']['items']['old']['url'] = url('site/entry/old/',array('m'=>$name));
	$frames['setting']['items']['old']['title'] = '老会员回归';
	$frames['setting']['items']['old']['actions'] = array();
	$frames['setting']['items']['old']['active'] = '';
	
	$frames['setting']['items']['syswords']['url'] = url('site/entry/syswords/',array('m'=>$name));
	$frames['setting']['items']['syswords']['title'] = '系统变量设置';
	$frames['setting']['items']['syswords']['actions'] = array();
	$frames['setting']['items']['syswords']['active'] = '';
	

	
	return $frames;
}