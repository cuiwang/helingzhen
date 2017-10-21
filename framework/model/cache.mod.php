<?php

/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
function cache_build_template() {
	load()->func('file');
	rmdirs(IA_ROOT . '/data/tpl', true);
}


function cache_build_setting() {
	$sql = "SELECT * FROM " . tablename('core_settings');
	$setting = pdo_fetchall($sql, array(), 'key');
	if (is_array($setting)) {
		foreach ($setting as $k => $v) {
			$setting[$v['key']] = iunserializer($v['value']);
		}
		cache_write("setting", $setting);
	}
}


function cache_build_module_status() {
	load()->model('cloud');
	$cloud_modules = cloud_m_query();
	$module_ban = is_array($cloud_modules['pirate_apps']) ? $cloud_modules['pirate_apps'] : array();
	$local_module = setting_load('module_ban');
	$update_modules = array_merge(array_diff($local_module, $module_ban), array_diff($module_ban, $local_module));
	if (!empty($update_modules)) {
		foreach ($update_modules as $module) {
			cache_build_module_info($module);
		}
	}
	setting_save($module_ban, 'module_ban');
	setting_save(array(), 'module_upgrade');
}


function cache_build_account_modules($uniacid = 0) {
	$uniacid = intval($uniacid);
	if (empty($uniacid)) {
				cache_clean(cache_system_key('unimodules'));
		cache_clean(cache_system_key('user_modules'));
	} else {
		cache_delete(cache_system_key("unimodules:{$uniacid}:1"));
		cache_delete(cache_system_key("unimodules:{$uniacid}:"));
		$owner_uid = pdo_getcolumn('uni_account_users', array('role' => 'owner'), 'uid');
		cache_delete(cache_system_key("user_modules:" . $owner_uid));
	}
}

function cache_build_account($uniacid = 0) {
	global $_W;
	$uniacid = intval($uniacid);
	if (empty($uniacid)) {
		$uniacid_arr = pdo_fetchall("SELECT uniacid FROM " . tablename('uni_account'));
		foreach($uniacid_arr as $account){
			cache_delete("uniaccount:{$account['uniacid']}");
			cache_delete("unisetting:{$account['uniacid']}");
			cache_delete("defaultgroupid:{$account['uniacid']}");
		}
	} else {
		cache_delete("uniaccount:{$uniacid}");
		cache_delete("unisetting:{$uniacid}");
		cache_delete("defaultgroupid:{$uniacid}");
	}

}


function cache_build_fansinfo($openid) {
	$openid = trim($openid);
	$cachekey = cache_system_key("fansinfo:{$openid}");
	cache_delete($cachekey);
	return true;
}


function cache_build_memberinfo($uid) {
	$uid = intval($uid);
	$cachekey = cache_system_key(CACHE_KEY_MEMBER_INFO, $uid);
	cache_delete($cachekey);
	return true;
}


function cache_build_users_struct() {
	$base_fields = array(
		'uniacid' => '同一公众号id',
		'groupid' => '分组id',
		'credit1' => '积分',
		'credit2' => '余额',
		'credit3' => '预留积分类型3',
		'credit4' => '预留积分类型4',
		'credit5' => '预留积分类型5',
		'credit6' => '预留积分类型6',
		'createtime' => '加入时间',
		'mobile' => '手机号码',
		'email' => '电子邮箱',
		'realname' => '真实姓名',
		'nickname' => '昵称',
		'avatar' => '头像',
		'qq' => 'QQ号',
		'gender' => '性别',
		'birth' => '生日',
		'constellation' => '星座',
		'zodiac' => '生肖',
		'telephone' => '固定电话',
		'idcard' => '证件号码',
		'studentid' => '学号',
		'grade' => '班级',
		'address' => '地址',
		'zipcode' => '邮编',
		'nationality' => '国籍',
		'reside' => '居住地',
		'graduateschool' => '毕业学校',
		'company' => '公司',
		'education' => '学历',
		'occupation' => '职业',
		'position' => '职位',
		'revenue' => '年收入',
		'affectivestatus' => '情感状态',
		'lookingfor' => ' 交友目的',
		'bloodtype' => '血型',
		'height' => '身高',
		'weight' => '体重',
		'alipay' => '支付宝帐号',
		'msn' => 'MSN',
		'taobao' => '阿里旺旺',
		'site' => '主页',
		'bio' => '自我介绍',
		'interest' => '兴趣爱好',
		'password' => '密码',
	);
	cache_write('userbasefields', $base_fields);
	$fields = pdo_getall('profile_fields', array(), array(), 'field');
	if (!empty($fields)) {
		foreach ($fields as &$field) {
			$field = $field['title'];
		}
		$fields['uniacid'] = '同一公众号id';
		$fields['groupid'] = '分组id';
		$fields['credit1'] ='积分';
		$fields['credit2'] = '余额';
		$fields['credit3'] = '预留积分类型3';
		$fields['credit4'] = '预留积分类型4';
		$fields['credit5'] = '预留积分类型5';
		$fields['credit6'] = '预留积分类型6';
		$fields['createtime'] = '加入时间';
		$fields['password'] = '用户密码';
		cache_write('usersfields', $fields);
	} else {
		cache_write('usersfields', $base_fields);
	}
}

function cache_build_frame_menu() {
	$system_menu_db = pdo_getall('core_menu', array('permission_name !=' => ''), array(), 'permission_name');
	$system_menu = require_once IA_ROOT . '/web/common/frames.inc.php';
	if (!empty($system_menu) && is_array($system_menu)) {
		foreach ($system_menu as $menu_name => $menu) {
			$system_menu[$menu_name]['is_system'] = true;
			$system_menu[$menu_name]['is_display'] = empty($system_menu_db[$menu_name]) || !empty($system_menu_db[$menu_name]['is_display']) ? true : false;

			foreach ($menu['section'] as $section_name => $section) {
				$displayorder = max(count($section['menu']), 1);
				
								if (empty($section['menu'])) {
					$section['menu'] = array();
				}
				$add_menu = pdo_getall('core_menu', array('group_name' => $section_name), array(
					'id', 'title', 'url', 'is_display', 'is_system', 'permission_name', 'displayorder', 'icon',
				), 'permission_name', 'displayorder DESC');
				if (!empty($add_menu)) {
					foreach ($add_menu as $permission_name => $menu) {
						$menu['icon'] = !empty($menu['icon']) ? $menu['icon'] : 'wi wi-appsetting';
						$section['menu'][$permission_name] = $menu;
					}
				}
				foreach ($section['menu']  as $permission_name => $sub_menu) {
					$sub_menu_db = $system_menu_db[$sub_menu['permission_name']];
					$system_menu[$menu_name]['section'][$section_name]['menu'][$permission_name] = array(
						'is_system' => isset($sub_menu['is_system']) ? $sub_menu['is_system'] : 1,
						'is_display' => isset($sub_menu_db['is_display']) ? $sub_menu_db['is_display'] : 1,
						'title' => !empty($sub_menu_db['title']) ? $sub_menu_db['title'] : $sub_menu['title'],
						'url' => $sub_menu['url'],
						'permission_name' => $sub_menu['permission_name'],
						'icon' => $sub_menu['icon'],
						'displayorder' => !empty($sub_menu_db['displayorder']) ? $sub_menu_db['displayorder'] : $displayorder,
						'id' => $sub_menu['id'],
						'sub_permission' => $sub_menu['sub_permission'],
					);
					$displayorder--;
					$displayorder = max($displayorder, 0);
				}
				$system_menu[$menu_name]['section'][$section_name]['menu'] = iarray_sort($system_menu[$menu_name]['section'][$section_name]['menu'], 'displayorder', 'desc');
			}
		}
		$add_top_nav = pdo_getall('core_menu', array('group_name' => 'frame', 'is_system <>' => 1), array('title', 'url', 'permission_name'));
		if (!empty($add_top_nav)) {
			foreach ($add_top_nav as $menu) {
				$menu['blank'] = true;
				$menu['is_display'] = true;
				$system_menu[$menu['permission_name']] = $menu;
			}
		}
		cache_delete('system_frame');
		cache_write('system_frame', $system_menu);
	}
}

function cache_build_module_subscribe_type() {
	global $_W;
	$modules = pdo_fetchall("SELECT name, subscribes FROM " . tablename('modules') . " WHERE subscribes <> ''");
	$subscribe = array();
	if (!empty($modules)) {
		foreach ($modules as $module) {
			$module['subscribes'] = unserialize($module['subscribes']);
			if (!empty($module['subscribes'])) {
				foreach ($module['subscribes'] as $event) {
					if ($event == 'text') {
						continue;
					}
					$subscribe[$event][] = $module['name'];
				}
			}
		}
	}
	$module_ban = $_W['setting']['module_receive_ban'];
	foreach ($subscribe as $event => $module_group) {
		if (!empty($module_group)) {
			foreach ($module_group as $index => $module) {
				if (!empty($module_ban[$module])) {
					unset($subscribe[$event][$index]);
				}
			}
		}
	}
	cache_write('module_receive_enable', $subscribe);
	return $subscribe;
}



function cache_build_cloud_ad() {
	global $_W;
	$uniacid_arr = pdo_fetchall("SELECT uniacid FROM " . tablename('uni_account'));
	foreach($uniacid_arr as $account){
		cache_delete("stat:todaylock:{$account['uniacid']}");
		cache_delete("cloud:ad:uniaccount:{$account['uniacid']}");
		cache_delete("cloud:ad:app:list:{$account['uniacid']}");
	}
	cache_delete("cloud:flow:master");
	cache_delete("cloud:ad:uniaccount:list");
	cache_delete("cloud:ad:tags");
	cache_delete("cloud:ad:type:list");
	cache_delete("cloud:ad:app:support:list");
	cache_delete("cloud:ad:site:finance");
}


function cache_build_uninstalled_module() {
	load()->model('cloud');
	load()->model('extension');
	load()->func('file');
	$installed_module = pdo_getall('modules', array(), array(), 'name');

	$uninstallModules = array('recycle' => array(), 'uninstalled' => array());
	$recycle_modules = pdo_getall('modules_recycle', array(), array(), 'modulename');
	$recycle_modules = array_keys($recycle_modules);
	$cloud_module = cloud_m_query();

	if (!empty($cloud_module) && !is_error($cloud_module)) {
		foreach ($cloud_module as $module) {
			$upgrade_support_module = false;
			$wxapp_support = !empty($module['site_branch']['wxapp_support']) && is_array($module['site_branch']['bought']) && in_array('wxapp', $module['site_branch']['bought']) ? $module['site_branch']['wxapp_support'] : 1;
			$app_support = !empty($module['site_branch']['app_support']) && is_array($module['site_branch']['bought']) && in_array('app', $module['site_branch']['bought']) ? $module['site_branch']['app_support'] : 1;
			if ($wxapp_support ==  1 && $app_support == 1) {
				$app_support = 2;
			}
			if (!empty($installed_module[$module['name']]) && ($installed_module[$module['name']]['app_support'] != $app_support || $installed_module[$module['name']]['wxapp_support'] != $wxapp_support)) {
				$upgrade_support_module = true;
			}
			if (!in_array($module['name'], array_keys($installed_module)) || $upgrade_support_module) {
				$status = in_array($module['name'], $recycle_modules) ? 'recycle' : 'uninstalled';
				if (!empty($module['id'])) {
					$cloud_module_info = array (
						'from' => 'cloud',
						'name' => $module['name'],
						'version' => $module['version'],
						'title' => $module['title'],
						'thumb' => $module['thumb'],
						'wxapp_support' => $wxapp_support,
						'app_support' => $app_support,
						'main_module' => empty($module['main_module']) ? '' : $module['main_module'],
						'upgrade_support' => $upgrade_support_module
					);
					if ($upgrade_support_module) {
						if ($wxapp_support == 2 && $installed_module[$module['name']]['wxapp_support'] != 2) {
							$uninstallModules[$status]['wxapp'][$module['name']] = $cloud_module_info;
						}
						if ($app_support == 2 && $installed_module[$module['name']]['app_support'] != 2) {
							$uninstallModules[$status]['app'][$module['name']] = $cloud_module_info;
						}
					} else {
						if ($wxapp_support == 2) {
							$uninstallModules[$status]['wxapp'][$module['name']] = $cloud_module_info;
						}
						if ($app_support == 2) {
							$uninstallModules[$status]['app'][$module['name']] = $cloud_module_info;
						}
					}
				}
			}
		}
	}
	$path = IA_ROOT . '/addons/';
	mkdirs($path);

	$module_file = glob($path . '*');
	if (is_array($module_file) && !empty($module_file)) {
		foreach ($module_file as $modulepath) {
			$upgrade_support_module = false;
			$modulepath = str_replace($path, '', $modulepath);
			$manifest = ext_module_manifest($modulepath);
			if (!is_array($manifest) || empty($manifest) || empty($manifest['application']['identifie'])) {
				continue;
			}
			$main_module = empty($manifest['platform']['main_module']) ? '' : $manifest['platform']['main_module'];
			$manifest = ext_module_convert($manifest);
			if (!empty($installed_module[$modulepath]) && ($manifest['app_support'] != $installed_module[$modulepath]['app_support'] || $manifest['wxapp_support'] != $installed_module[$modulepath]['wxapp_support'])) {
				$upgrade_support_module = true;
			}
			if (!in_array($manifest['name'], array_keys($installed_module)) || $upgrade_support_module) {
				$module[$manifest['name']] = $manifest;
				$module_info = array(
					'from' => 'local',
					'name' => $manifest['name'],
					'version' => $manifest['version'],
					'title' => $manifest['title'],
					'app_support' => $manifest['app_support'],
					'wxapp_support' => $manifest['wxapp_support'],
					'main_module' => $main_module,
					'upgrade_support' => $upgrade_support_module
				);
				$module_type = in_array($manifest['name'], $recycle_modules) ? 'recycle' : 'uninstalled';
				if ($upgrade_support_module) {
					if ($module_info['app_support'] == 2 && $installed_module[$module_info['name']]['app_support'] != 2) {
						$uninstallModules['uninstalled']['app'][$manifest['name']] = $module_info;
					}
					if ($module_info['wxapp_support'] == 2 && $installed_module[$module_info['name']]['wxapp_support'] != 2) {
						$uninstallModules['uninstalled']['wxapp'][$manifest['name']] = $module_info;
					}
				} else {
					if ($module_info['app_support'] == 2) {
						$uninstallModules[$module_type]['app'][$manifest['name']] = $module_info;
					}
					if ($module_info['wxapp_support'] == 2) {
						$uninstallModules[$module_type]['wxapp'][$manifest['name']] = $module_info;
					}
				}
			}
		}
	}
	$cache = array(
		'cloud_m_count' => $cloud_m_count['module_quantity'],
		'modules' => $uninstallModules,
		'app_count' => count($uninstallModules['uninstalled']['app']),
		'wxapp_count' => count($uninstallModules['uninstalled']['wxapp'])
	);
	cache_write('we7:module:all_uninstall', $cache, CACHE_EXPIRE_LONG);
	return $cache;
}


function cache_build_proxy_wechatpay_account() {
	global $_W;
	load()->model('account');
	if(empty($_W['isfounder'])) {
		$where = " WHERE `uniacid` IN (SELECT `uniacid` FROM " . tablename('uni_account_users') . " WHERE `uid`=:uid)";
		$params[':uid'] = $_W['uid'];
	}
	$sql = "SELECT * FROM " . tablename('uni_account') . $where;
	$uniaccounts = pdo_fetchall($sql, $params);
	$service = array();
	$borrow = array();
	if (!empty($uniaccounts)) {
		foreach ($uniaccounts as $uniaccount) {
			$account = account_fetch($uniaccount['default_acid']);
			$account_setting = pdo_get('uni_settings', array ('uniacid' => $account['uniacid']));
			$payment = iunserializer($account_setting['payment']);
			if (!empty($account['key']) && !empty($account['secret']) && in_array ($account['level'], array (4)) && !empty($payment) && intval ($payment['wechat']['switch']) == 1) {
				if ((!is_bool ($payment['wechat']['switch']) && $payment['wechat']['switch'] != 4) || (is_bool ($payment['wechat']['switch']) && !empty($payment['wechat']['switch']))) {
					$borrow[$account['uniacid']] = $account['name'];
				}
			}
			if (!empty($payment['wechat_facilitator']['switch'])) {
				$service[$account['uniacid']] = $account['name'];
			}
		}
	}
	$cache = array(
		'service' => $service,
		'borrow' => $borrow
	);
	cache_write(cache_system_key("proxy_wechatpay_account:"), $cache);
	return $cache;
}


function cache_build_module_info($module_name) {
	global $_W;
	cache_delete(cache_system_key(CACHE_KEY_MODULE_INFO, $module_name));
	cache_delete(cache_system_key(CACHE_KEY_MODULE_SETTING, $_W['uniacid'], $module_name));
}


function cache_build_uni_group() {
	cache_delete(cache_system_key(CACHE_KEY_UNI_GROUP));
}


function cache_build_cloud_upgrade_module() {
	load()->model('cloud');
	load()->model('extension');

	$module_list = pdo_getall('modules', array(), array(), 'name');
	$cloud_module = cloud_m_query();
	$modules = array();
	if (is_array($module_list) && !empty($module_list)) {
		foreach ($module_list as $module) {
			if (in_array($module['name'], array_keys($cloud_module))) {
				$cloud_version = $cloud_module[$module['name']]['version'];
				$module['from'] = 'cloud';
				if (version_compare($module['version'], $cloud_version) == -1) {
					$module['upgrade'] = true;
				}
				if ($module['upgrade']) {
					$modules[$module['name']] = $module;
				}
			}
		}
	} else {
		return array();
	}
	cache_write(cache_system_key('all_cloud_upgrade_module:'), $modules, 1800);
	return $modules;
}
