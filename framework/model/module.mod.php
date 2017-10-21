<?php
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
defined('IN_IA') or exit('Access Denied');


function module_types() {
	static $types = array(
		'business' => array(
			'name' => 'business',
			'title' => '主要业务',
			'desc' => ''
		),
		'customer' => array(
			'name' => 'customer',
			'title' => '客户关系',
			'desc' => ''
		),
		'activity' => array(
			'name' => 'activity',
			'title' => '营销活动',
			'desc' => ''
		),
		'services' => array(
			'name' => 'services',
			'title' => '常用服务',
			'desc' => ''
		),
		'biz' => array(
			'name' => 'biz',
			'title' => '行业方案',
			'desc' => ''
		),
		'enterprise' => array(
			'name' => 'enterprise',
			'title' => '企业应用',
			'desc' => ''
		),
		'wdlgame' => array(
			'name' => 'wdlgame',
			'title' => 'H5游戏',
			'desc' => ''
		),
		'other' => array(
			'name' => 'other',
			'title' => '其他',
			'desc' => ''
		)
	);
	return $types;
}


function module_entries($name, $types = array(), $rid = 0, $args = null) {
	load()->func('communication');

	global $_W;
	$ts = array('rule', 'cover', 'menu', 'home', 'profile', 'shortcut', 'function', 'mine');
	if(empty($types)) {
		$types = $ts;
	} else {
		$types = array_intersect($types, $ts);
	}
	$bindings = pdo_getall('modules_bindings', array('module' => $name, 'entry' => $types), array(), '', 'displayorder DESC, eid ASC');
	$entries = array();
	foreach($bindings as $bind) {
		if(!empty($bind['call'])) {
			$extra = array();
			$extra['Host'] = $_SERVER['HTTP_HOST'];
			load()->func('communication');
			$urlset = parse_url($_W['siteurl']);
			$urlset = pathinfo($urlset['path']);
			$response = ihttp_request($_W['sitescheme'] . '127.0.0.1/'. $urlset['dirname'] . '/' . url('utility/bindcall', array('modulename' => $bind['module'], 'callname' => $bind['call'], 'args' => $args, 'uniacid' => $_W['uniacid'])), array(), $extra);
			if (is_error($response)) {
				continue;
			}
			$response = json_decode($response['content'], true);
			$ret = $response['message'];
			if(is_array($ret)) {
				foreach($ret as $et) {
					if (empty($et['url'])) {
						continue;
					}
					$et['url'] = $et['url'] . '&__title=' . urlencode($et['title']);
					$entries[$bind['entry']][] = array('title' => $et['title'], 'do' => $et['do'], 'url' => $et['url'], 'from' => 'call', 'icon' => $et['icon'], 'displayorder' => $et['displayorder']);
				}
			}
		} else {
			if($bind['entry'] == 'cover') {
				$url = murl('entry', array('eid' => $bind['eid']));
			}
			if($bind['entry'] == 'menu') {
				$url = wurl("site/entry", array('eid' => $bind['eid']));
			}
			if($bind['entry'] == 'mine') {
				$url = $bind['url'];
			}
			if($bind['entry'] == 'rule') {
				$par = array('eid' => $bind['eid']);
				if (!empty($rid)) {
					$par['id'] = $rid;
				}
				$url = wurl("site/entry", $par);
			}
			if($bind['entry'] == 'home') {
				$url = murl("entry", array('eid' => $bind['eid']));
			}
			if($bind['entry'] == 'profile') {
				$url = murl("entry", array('eid' => $bind['eid']));
			}
			if($bind['entry'] == 'shortcut') {
				$url = murl("entry", array('eid' => $bind['eid']));
			}
			if(empty($bind['icon'])) {
				$bind['icon'] = 'fa fa-puzzle-piece';
			}
			$entries[$bind['entry']][] = array('eid' => $bind['eid'], 'title' => $bind['title'], 'do' => $bind['do'], 'url' => $url, 'from' => 'define', 'icon' => $bind['icon'], 'displayorder' => $bind['displayorder'], 'direct' => $bind['direct']);
		}
	}
	return $entries;
}

function module_app_entries($name, $types = array(), $args = null) {
	global $_W;
	$ts = array('rule', 'cover', 'menu', 'home', 'profile', 'shortcut', 'function');
	if(empty($types)) {
		$types = $ts;
	} else {
		$types = array_intersect($types, $ts);
	}
	$bindings = pdo_getall('modules_bindings', array('module' => $name, 'entry' => $types));
	$entries = array();
	foreach($bindings as $bind) {
		if(!empty($bind['call'])) {
			$extra = array();
			$extra['Host'] = $_SERVER['HTTP_HOST'];
			load()->func('communication');
			$urlset = parse_url($_W['siteurl']);
			$urlset = pathinfo($urlset['path']);
			$response = ihttp_request($_W['sitescheme'] . '127.0.0.1/'. $urlset['dirname'] . '/' . url('utility/bindcall', array('modulename' => $bind['module'], 'callname' => $bind['call'], 'args' => $args, 'uniacid' => $_W['uniacid'])), array('W'=>base64_encode(iserializer($_W))), $extra);
			if (is_error($response)) {
				continue;
			}
			$response = json_decode($response['content'], true);
			$ret = $response['message'];
			if(is_array($ret)) {
				foreach($ret as $et) {
					$et['url'] = $et['url'] . '&__title=' . urlencode($et['title']);
					$entries[$bind['entry']][] = array('title' => $et['title'], 'url' => $et['url'], 'from' => 'call');
				}
			}
		} else {
			if($bind['entry'] == 'cover') {
				$url = murl("entry", array('eid' => $bind['eid']));
			}
			if($bind['entry'] == 'home') {
				$url = murl("entry", array('eid' => $bind['eid']));
			}
			if($bind['entry'] == 'profile') {
				$url = murl("entry", array('eid' => $bind['eid']));
			}
			if($bind['entry'] == 'shortcut') {
				$url = murl("entry", array('eid' => $bind['eid']));
			}
			$entries[$bind['entry']][] = array('title' => $bind['title'], 'do' => $bind['do'], 'url' => $url, 'from' => 'define');
		}
	}
	return $entries;
}

function module_entry($eid) {
	$sql = "SELECT * FROM " . tablename('modules_bindings') . " WHERE `eid`=:eid";
	$pars = array();
	$pars[':eid'] = $eid;
	$entry = pdo_fetch($sql, $pars);
	if(empty($entry)) {
		return error(1, '模块菜单不存在');
	}
	$module = module_fetch($entry['module']);
	if(empty($module)) {
		return error(2, '模块不存在');
	}
	$querystring = array(
		'do' => $entry['do'],
		'm' => $entry['module'],
	);
	if (!empty($entry['state'])) {
		$querystring['state'] = $entry['state'];
	}

	$entry['url'] = murl('entry', $querystring);
	$entry['url_show'] = murl('entry', $querystring, true, true);
	return $entry;
}


function module_build_form($name, $rid, $option = array()) {
	$rid = intval($rid);
	$m = WeUtility::createModule($name);
	if(!empty($m)) {
		return $m->fieldsFormDisplay($rid, $option);
	}else {
		return null;
	}

}


function module_save_group_package($package) {
	global $_W;
	load()->model('user');
	load()->model('cache');

	if (empty($package['name'])) {
		return error(-1, '请输入套餐名');
	}

	if (user_is_vice_founder()) {
		$package['owner_uid'] = $_W['uid'];
	}
	if (!empty($package['modules'])) {
		$package['modules'] = iserializer($package['modules']);
	}

	if (!empty($package['templates'])) {
		$templates = array();
		foreach ($package['templates'] as $template) {
			$templates[] = $template['id'];
		}
		$package['templates'] = iserializer($templates);
	}

	if (!empty($package['id'])) {
		$name_exist = pdo_get('uni_group', array('uniacid' => 0, 'id <>' => $package['id'], 'name' => $package['name']));
	} else {
		$name_exist = pdo_get('uni_group', array('uniacid' => 0, 'name' => $package['name']));
	}

	if (!empty($name_exist)) {
		return error(-1, '套餐名已存在');
	}

	if (!empty($package['id'])) {
		pdo_update('uni_group', $package, array('id' => $package['id']));
		cache_build_account_modules();
	} else {
		pdo_insert('uni_group', $package);
	}

	cache_build_uni_group();
	return error(0, '添加成功');
}

function module_fetch($name) {
	global $_W;
	$cachekey = cache_system_key(CACHE_KEY_MODULE_INFO, $name);
	$module = cache_load($cachekey);
	if (empty($module)) {
		$module_info = pdo_get('modules', array('name' => $name));
		if (empty($module_info)) {
			return array();
		}
		if (!empty($module_info['subscribes'])) {
			$module_info['subscribes'] = (array)unserialize ($module_info['subscribes']);
		}
		if (!empty($module_info['handles'])) {
			$module_info['handles'] = (array)unserialize ($module_info['handles']);
		}
		$module_info['isdisplay'] = 1;

		if (file_exists (IA_ROOT . '/addons/' . $module_info['name'] . '/icon-custom.jpg')) {
			$module_info['logo'] = tomedia (IA_ROOT . '/addons/' . $module_info['name'] . '/icon-custom.jpg') . "?v=" . time ();
		} else {
			$module_info['logo'] = tomedia (IA_ROOT . '/addons/' . $module_info['name'] . '/icon.jpg') . "?v=" . time ();
		}

		$module_info['main_module'] = pdo_getcolumn ('modules_plugin', array ('name' => $module_info['name']), 'main_module');
		if (!empty($module_info['main_module'])) {
			$main_module_info = module_fetch ($module_info['main_module']);
			$module_info['main_module_logo'] = $main_module_info['logo'];
		} else {
			$module_info['plugin_list'] = pdo_getall ('modules_plugin', array ('main_module' => $module_info['name']), array (), 'name');
			if (!empty($module_info['plugin_list'])) {
				$module_info['plugin_list'] = array_keys ($module_info['plugin_list']);
			}
		}
		if ($module_info['app_support'] != 2 && $module_info['wxapp_support'] != 2) {
			$module_info['app_support'] = 2;
		}
		$module_info['is_relation'] = $module_info['app_support'] ==2 && $module_info['wxapp_support'] == 2 ? true : false;
		$module_ban = setting_load('module_ban');
		if (in_array($name, $module_ban['module_ban'])) {
			$module_info['is_ban'] = true;
		}
		$module_upgrade = setting_load('module_upgrade');
		if (in_array($name, array_keys($module_upgrade['module_upgrade']))) {
			$module_info['is_upgrade'] = true;
		}
		$module = $module_info;
		cache_write($cachekey, $module_info);
	}
		if (!empty($module) && !empty($_W['uniacid'])) {
		$setting_cachekey = cache_system_key(CACHE_KEY_MODULE_SETTING, $_W['uniacid'], $name);
		$setting = cache_load($setting_cachekey);
		if (empty($setting)) {
			$setting = pdo_get('uni_account_modules', array('module' => $name, 'uniacid' => $_W['uniacid']));
			if (!empty($setting)) {
				cache_write($setting_cachekey, $setting);
			}
		}
		$module['config'] = !empty($setting['settings']) ? iunserializer($setting['settings']) : array();
		$module['enabled'] = $module['issystem'] || !isset($setting['enabled']) ? 1 : $setting['enabled'];
		$module['shortcut'] = $setting['shortcut'];
	}
	return $module;
}


function module_build_privileges() {
	load()->model('account');
	$uniacid_arr = pdo_fetchall("SELECT uniacid FROM " . tablename('uni_account'));
	foreach($uniacid_arr as $row){
		$modules = uni_modules_by_uniacid($row['uniacid'], false);
				$mymodules = pdo_getall('uni_account_modules', array('uniacid' => $row['uniacid']), array('module'), 'module');
		$mymodules = array_keys($mymodules);
		foreach($modules as $module){
			if(!in_array($module['name'], $mymodules) && empty($module['issystem'])) {
				$data = array();
				$data['uniacid'] = $row['uniacid'];
				$data['module'] = $module['name'];
				$data['enabled'] = 1;
				$data['settings'] = '';
				pdo_insert('uni_account_modules', $data);
			}
		}
	}
	return true;
}



function module_get_all_unistalled($status, $cache = true)  {
	global $_GPC;
	load()->func('communication');
	load()->model('cloud');
	$status = $status == 'recycle' ? 'recycle' : 'uninstalled';
	$uninstallModules =  cache_load(cache_system_key('module:all_uninstall'));
	if ($_GPC['c'] == 'system' && $_GPC['a'] == 'module' && $_GPC['do'] == 'not_installed' && $status == 'uninstalled') {
	} else {
		if(is_array($uninstallModules)){
			$cloud_m_count = $uninstallModules['cloud_m_count'];
		}
	}
	if (ACCOUNT_TYPE == ACCOUNT_TYPE_APP_NORMAL) {
		$account_type = 'wxapp';
	} elseif (ACCOUNT_TYPE == ACCOUNT_TYPE_OFFCIAL_NORMAL) {
		$account_type = 'app';
	}
	if (!is_array($uninstallModules) || empty($uninstallModules['modules'][$status][$account_type]) || intval($uninstallModules['cloud_m_count']) !== intval($cloud_m_count) || is_error($get_cloud_m_count)) {
		$uninstallModules = cache_build_uninstalled_module();
	}

	if (!empty($account_type)) {
		$uninstallModules['modules'] = (array)$uninstallModules['modules'][$status][$account_type];
		$uninstallModules['module_count'] = $uninstallModules[$account_type . '_count'];
	}
	return $uninstallModules;
}


function module_permission_fetch($name) {
	$module = module_fetch($name);
	$data = array();
	if ($module['permissions']) {
		$data[] = array('title' => '权限设置', 'permission' => $name.'_permissions');
	}
	if($module['settings']) {
		$data[] = array('title' => '参数设置', 'permission' => $name.'_settings');
	}
	if($module['isrulefields']) {
		$data[] = array('title' => '回复规则列表', 'permission' => $name.'_rule');
	}
	$entries = module_entries($name);
	if(!empty($entries['home'])) {
		$data[] = array('title' => '微站首页导航', 'permission' => $name.'_home');
	}
	if(!empty($entries['profile'])) {
		$data[] = array('title' => '个人中心导航', 'permission' => $name.'_profile');
	}
	if(!empty($entries['shortcut'])) {
		$data[] = array('title' => '快捷菜单', 'permission' => $name.'_shortcut');
	}
	if(!empty($entries['cover'])) {
		foreach($entries['cover'] as $cover) {
			$data[] = array('title' => $cover['title'], 'permission' => $name.'_cover_'.$cover['do']);
		}
	}
	if(!empty($entries['menu'])) {
		foreach($entries['menu'] as $menu) {
			$data[] = array('title' => $menu['title'], 'permission' => $name.'_menu_'.$menu['do']);
		}
	}
	unset($entries);
	if(!empty($module['permissions'])) {
		$module['permissions'] = (array)iunserializer($module['permissions']);
		foreach ($module['permissions'] as $permission) {
			$data[] = array('title' => $permission['title'], 'permission' => $name . '_permission_' . $permission['permission']);
		}
	}
	return $data;
}


function module_uninstall($module_name, $is_clean_rule = false) {
	global $_W;
	load()->model('cloud');
	if (empty($_W['isfounder'])) {
		return error(1, '您没有卸载模块的权限！');
	}
	$module_name = trim($module_name);
	$module = module_fetch($module_name);
	if (empty($module)) {
		return error(1, '模块已经被卸载或是不存在！');
	}
	if (!empty($module['issystem'])) {
		return error(1, '系统模块不能卸载！');
	}
	if (!empty($module['plugin_list'])) {
		pdo_delete('modules_plugin', array('main_module' => $module_name));
	}
	$modulepath = IA_ROOT . '/addons/' . $module_name . '/';
	$manifest = ext_module_manifest($module_name);
	if (empty($manifest)) {
		$r = cloud_prepare();
		if (is_error($r)) {
			itoast($r['message'], url('cloud/profile'), 'error');
		}
		$packet = cloud_m_build($module_name, 'uninstall');
		if ($packet['sql']) {
			pdo_run(base64_decode($packet['sql']));
		} elseif ($packet['script']) {
			$uninstall_file = $modulepath . TIMESTAMP . '.php';
			file_put_contents($uninstall_file, base64_decode($packet['script']));
			require($uninstall_file);
			unlink($uninstall_file);
		}
	} elseif (!empty($manifest['uninstall'])) {
		if (strexists($manifest['uninstall'], '.php')) {
			if (file_exists($modulepath . $manifest['uninstall'])) {
				require($modulepath . $manifest['uninstall']);
			}
		} else {
			pdo_run($manifest['uninstall']);
		}
	}
	pdo_insert('modules_recycle', array('modulename' => $module_name));
	pdo_delete('uni_account_modules', array('module' => $module_name));
	ext_module_clean($module_name, $is_clean_rule);
	cache_build_module_subscribe_type();
	cache_build_uninstalled_module();
	cache_build_module_info($module_name);

	return true;
}


function module_get_plugin_list($module_name) {
	$module_info = module_fetch($module_name);
	if (!empty($module_info['plugin_list']) && is_array($module_info['plugin_list'])) {
		$plugin_list = array();
		foreach ($module_info['plugin_list'] as $plugin) {
			$plugin_info = module_fetch($plugin);
			if (!empty($plugin_info)) {
				$plugin_list[$plugin] = $plugin_info;
			}
		}
		return $plugin_list;
	} else {
		return array();
	}
}


function module_status($module) {
	load()->model('cloud');
	$module_status = array('upgrade' => array('upgrade' => 0), 'ban' => 0);

	$cloud_m_query = cloud_m_query($module);
	$cloud_m_query['pirate_apps'] = is_array($cloud_m_query['pirate_apps']) ? $cloud_m_query['pirate_apps'] : array();
	$module_status['ban'] = in_array($module, $cloud_m_query['pirate_apps']) ? 1 : 0;

	$cloud_m_info = cloud_m_info($module);
	$module_info = module_fetch($module);
	if (!empty($cloud_m_info) && !empty($cloud_m_info['version']['version'])) {
		if (version_compare($module_info['version'], $cloud_m_info['version']['version'])) {
			$module_status['upgrade'] = array('name' => $module_info['title'], 'version' => $cloud_m_info['version']['version'], 'upgrade' => 1);
		}
	} else {
		$manifest = ext_module_manifest($module);
		if (!empty($manifest)) {
			if (version_compare($module_info['version'], $manifest['application']['version'])) {
				$module_status['upgrade'] = array('name' => $module_info['title'], 'version' => $manifest['application']['version'], 'upgrade' => 1);
			}
		}
	}

	$cache_build_module = false;
	$module_ban_setting = setting_load('module_ban');
	$module_ban_setting = is_array($module_ban_setting['module_ban']) ? $module_ban_setting['module_ban'] : array();
	if (!in_array($module, $module_ban_setting) && !empty($module_status['ban'])) {
		$module_ban_setting[] = $module;
		$cache_build_module = true;
		setting_save($module_ban_setting, 'module_ban');
	}
	if (in_array($module, $module_ban_setting) && empty($module_status['ban'])) {
		$key = array_search($module, $module_ban_setting);
		unset($module_ban_setting[$key]);
		$cache_build_module = true;
		setting_save($module_ban_setting, 'module_ban');
	}

	$module_upgrade_setting = setting_load('module_upgrade');
	$module_upgrade_setting = is_array($module_upgrade_setting['module_upgrade']) ? $module_upgrade_setting['module_upgrade'] : array();
	if (!in_array($module, array_keys($module_upgrade_setting)) && !empty($module_status['upgrade']['upgrade'])) {
		$module_upgrade_setting[$module] = $module_status['upgrade'];
		$cache_build_module = true;
		setting_save($module_upgrade_setting, 'module_upgrade');
	}
	if (in_array($module, array_keys($module_upgrade_setting)) && empty($module_status['upgrade']['upgrade'])) {
		unset($module_upgrade_setting[$module]);
		$cache_build_module = true;
		setting_save($module_upgrade_setting, 'module_upgrade');
	}

	if ($cache_build_module) {
		cache_build_module_info($module);
	}
	return $module_status;
}


function module_filter_upgrade($module_list) {
	$modules = array();
	$installed_module = pdo_getall('modules', array('name' => $module_list), array('version', 'name'), 'name');
	$all_upgrade_cloud_module = cache_load(cache_system_key('all_cloud_upgrade_module:'));
	if (empty($all_upgrade_cloud_module)) {
		$all_upgrade_cloud_module = cache_build_cloud_upgrade_module();
	}
	if (!empty($module_list) && is_array($module_list) && !empty($installed_module)) {
		foreach ($module_list as $key => $module) {
			if (empty($installed_module[$module])) {
				continue;
			}
			$manifest = ext_module_manifest($module);
			if (!empty($manifest)&& is_array($manifest)) {
				$module = array('name' => $module);
				$module['from'] = 'local';
				if (version_compare($installed_module[$module['name']]['version'], $manifest['application']['version']) == '-1') {
					$module['upgrade'] = true;
					$module['upgrade_branch'] = true;
					$modules[$module['name']] = $module;
				}
			} else {
				if (is_array($all_upgrade_cloud_module) && !empty($all_upgrade_cloud_module[$module])) {
					$modules[$module] = $all_upgrade_cloud_module[$module];
				}
			}
		}
	}
	return $modules;
}

function module_upgrade_new($type = 'account') {
	if ($type == 'wxapp') {
		$module_list = user_module_by_account_type('wxapp');
	} else {
		$module_list = user_module_by_account_type('account');
	}
	$upgrade_modules = module_filter_upgrade(array_keys($module_list));
	if (!empty($upgrade_modules)) {
		foreach ($upgrade_modules as $key => &$module) {
			$module_fetch = module_fetch($key);
			$module['logo'] = $module_fetch['logo'];
			$module['link'] = url('module/manage-system/module_detail', array('name' => $module['name'], 'show' => 'upgrade'));
		}
		unset($module);
	}
	return $upgrade_modules;
}


function module_get_user_account_list($uid, $module_name) {
	$accounts_list = array();
	$uid = intval($uid);
	$module_name = trim($module_name);
	if (empty($uid) || empty($module_name)) {
		return $accounts_list;
	}
	$module_info = module_fetch($module_name);
	if (empty($module_info)) {
		return $accounts_list;
	}
	$accounts = user_account_detail_info($uid);
	if (empty($accounts)) {
		return $accounts_list;
	}
	if (!empty($accounts['wxapp'])) {
		foreach ($accounts['wxapp'] as $wxapp_value) {
			if (empty($wxapp_value['uniacid'])) {
				continue;
			}
			$wxapp_modules = uni_modules_by_uniacid($wxapp_value['uniacid']);
			$wxapp_modules = array_keys($wxapp_modules);
			$module_permission_exist = uni_user_menu_permission($uid, $wxapp_value['uniacid'], $module_name);
			if (in_array($module_name, $wxapp_modules) && (in_array('all',$module_permission_exist) || !empty($module_permission_exist))) {
				$accounts_list[$wxapp_value['uniacid']] = $wxapp_value;
			}
		}
	}
	if (!empty($accounts['wechat'])) {
		foreach ($accounts['wechat'] as $wechat_value) {
			if (empty($wechat_value['uniacid'])) {
				continue;
			}
			$wechat_modules = uni_modules_by_uniacid($wechat_value['uniacid']);
			$wechat_modules = array_keys($wechat_modules);
			$module_permission_exist = uni_user_menu_permission($uid, $wxapp_value['uniacid'], $module_name);
			if (in_array($module_name, $wechat_modules) && (in_array('all',$module_permission_exist) || !empty($module_permission_exist))) {
				$accounts_list[$wechat_value['uniacid']] = $wechat_value;
			}
		}
	}

	foreach ($accounts_list as $key => $account_value) {
		if ($module_info['wxapp_support'] == MODULE_SUPPORT_WXAPP && $module_info['app_support'] == MODULE_SUPPORT_ACCOUNT) {
			continue;
		} elseif ($module_info['wxapp_support'] == MODULE_SUPPORT_WXAPP && $account_value['type'] != ACCOUNT_TYPE_APP_NORMAL) {
			unset($accounts_list[$key]);
		} elseif ($module_info['app_support'] == MODULE_SUPPORT_ACCOUNT && !in_array($account_value['type'], array(ACCOUNT_TYPE_OFFCIAL_NORMAL, ACCOUNT_TYPE_OFFCIAL_AUTH))) {
			unset($accounts_list[$key]);
		}
	}

	return $accounts_list;
}


function module_link_uniacid_fetch($uid, $module_name) {
	$result = array();
	$uid = intval($uid);
	$module_name = trim($module_name);
	if (empty($uid) || empty($module_name)) {
		return $result;
	}
	$accounts_list = module_get_user_account_list($uid, $module_name);
	if (empty($accounts_list)) {
		return $result;
	}
	$accounts_link_result = array();
	foreach ($accounts_list as $key => $account_value) {
		if ($account_value['type'] == ACCOUNT_TYPE_APP_NORMAL) {
			$account_value['versions'] = wxapp_version_all($account_value['uniacid']);
			if (empty($account_value['versions'])) {
				$accounts_link_result[$key] = $account_value;
				continue;
			}
			foreach ($account_value['versions'] as $version_key => $version_value) {
				if (empty($version_value['modules'])) {
					continue;
				}
				if ($version_value['modules'][0]['name'] != $module_name) {
					continue;
				}
				if (empty($version_value['modules'][0]['account']) || !is_array($version_value['modules'][0]['account'])) {
					$accounts_link_result[$key] = $account_value;
					continue;
				}
				if (!empty($version_value['modules'][0]['account']['uniacid'])) {
					$accounts_link_result[$version_value['modules'][0]['account']['uniacid']][] = array(
						'uniacid' => $key,
						'version' => $version_value['version'],
						'version_id' => $version_value['id'],
						'name' => $account_value['name'],
					);
					unset($account_value['versions'][$version_key]);
				}

			}
		}
		if ($account_value['type'] == ACCOUNT_TYPE_OFFCIAL_NORMAL || $account_value['type'] == ACCOUNT_TYPE_OFFCIAL_AUTH) {
			if (empty($accounts_link_result[$key])) {
				$accounts_link_result[$key] = $account_value;
			} else {
				$link_wxapp = $accounts_link_result[$key];
				$accounts_link_result[$key] = $account_value;
				$accounts_link_result[$key]['link_wxapp'] = $link_wxapp;
			}
		}
	}
	if (!empty($accounts_link_result)) {
		foreach ($accounts_link_result as $link_key => $link_value) {
			if (in_array($link_value['type'], array(ACCOUNT_TYPE_OFFCIAL_NORMAL, ACCOUNT_TYPE_OFFCIAL_AUTH)) && !empty($link_value['link_wxapp']) && is_array($link_value['link_wxapp'])) {
				foreach ($link_value['link_wxapp'] as $value) {
					$result[] = array(
						'app_name' => $link_value['name'],
						'wxapp_name' => $value['name'] . ' ' . $value['version'],
						'uniacid' => $link_value['uniacid'],
						'version_id' => $value['version_id'],
					);
				}
			} elseif ($link_value['type'] == ACCOUNT_TYPE_APP_NORMAL && !empty($link_value['versions']) && is_array($link_value['versions'])) {
				foreach ($link_value['versions'] as $value) {
					$result[] = array(
						'app_name' => '',
						'wxapp_name' => $link_value['name'] . ' ' . $value['version'],
						'uniacid' => $link_value['uniacid'],
						'version_id' => $value['id'],
					);
				}
			} else {
				$result[] = array(
					'app_name' => $link_value['name'],
					'wxapp_name' => '',
					'uniacid' => $link_value['uniacid'],
					'version_id' => '',
				);
			}
		}
	}

	return $result;
}


function module_save_switch($module_name, $uniacid = 0, $version_id = 0) {
	global $_W, $_GPC;
	if (empty($_GPC['__switch'])) {
		$_GPC['__switch'] = random(5);
	}

	$cache_key = cache_system_key(CACHE_KEY_ACCOUNT_SWITCH, $_GPC['__switch']);
	$cache_lastaccount = cache_load($cache_key);
	if (empty($cache_lastaccount)) {
		$cache_lastaccount = array(
			$module_name => array(
				'module_name' => $module_name,
				'uniacid' => $uniacid,
				'version_id' => $version_id
			)
		);
	} else {
		$cache_lastaccount[$module_name] = array(
			'module_name' => $module_name,
			'uniacid' => $uniacid,
			'version_id' => $version_id
		);
	}
	cache_write($cache_key, $cache_lastaccount);
	isetcookie('__switch', $_GPC['__switch'], 7 * 86400);
	return true;
}


function module_last_switch($module_name) {
	global $_GPC;
	$module_name = trim($module_name);
	if (empty($module_name)) {
		return array();
	}
	$cache_key = cache_system_key(CACHE_KEY_ACCOUNT_SWITCH, $_GPC['__switch']);
	$cache_lastaccount = (array)cache_load($cache_key);
	return $cache_lastaccount[$module_name];
}


function module_clerk_info($module_name) {
	global $_W;
	$user_permissions = array();
	$module_name = trim($module_name);
	if (empty($module_name)) {
		return $user_permissions;
	}
	$params = array(
			':role' => ACCOUNT_MANAGE_NAME_CLERK,
			':type' => $module_name,
			':uniacid' => $_W['uniacid']
	);
	$sql = "SELECT u.uid, p.permission FROM " . tablename('uni_account_users') . " u," . tablename('users_permission') . " p WHERE u.uid = p.uid AND u.uniacid = p.uniacid AND u.role = :role AND p.type = :type AND u.uniacid = :uniacid";
	$user_permissions = pdo_fetchall($sql, $params, 'uid');
	if (!empty($user_permissions)) {
		foreach ($user_permissions as $key => $value) {
			$user_permissions[$key]['user_info'] = user_single($value['uid']);
		}
	}
	return $user_permissions;
}