<?php
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
defined('IN_IA') or exit('Access Denied');


function uni_create_permission($uid, $type = ACCOUNT_TYPE_OFFCIAL_NORMAL) {
	$groupid = pdo_fetchcolumn('SELECT groupid FROM ' . tablename('users') . ' WHERE uid = :uid', array(':uid' => $uid));
	$groupdata = pdo_fetch('SELECT maxaccount, maxsubaccount, maxwxapp FROM ' . tablename('users_group') . ' WHERE id = :id', array(':id' => $groupid));
	$list = pdo_fetchall('SELECT d.type, count(*) AS count FROM (SELECT u.uniacid, a.default_acid FROM ' . tablename('uni_account_users') . ' as u RIGHT JOIN '. tablename('uni_account').' as a  ON a.uniacid = u.uniacid  WHERE u.uid = :uid AND u.role = :role ) AS c LEFT JOIN '.tablename('account').' as d ON c.default_acid = d.acid WHERE d.isdeleted = 0 GROUP BY d.type', array(':uid' => $uid, ':role' => 'owner'));
	foreach ($list as $item) {
		if ($item['type'] == ACCOUNT_TYPE_APP_NORMAL) {
			$wxapp_num = $item['count'];
		} else {
			$account_num = $item['count'];
		}
	}
		if ($type == ACCOUNT_TYPE_OFFCIAL_NORMAL || $type == ACCOUNT_TYPE_OFFCIAL_AUTH) {
		if ($account_num >= $groupdata['maxaccount']) {
			return error('-1', '您所在的用户组最多只能创建' . $groupdata['maxaccount'] . '个主公众号');
		}
	} elseif ($type == ACCOUNT_TYPE_APP_NORMAL) {
		if ($wxapp_num >= $groupdata['maxwxapp']) {
			return error('-1', '您所在的用户组最多只能创建' . $groupdata['maxwxapp'] . '个小程序');
		}
	}
	return true;
}

function uni_owned($uid = 0) {
	global $_W;
	$uid = intval($uid) > 0 ? intval($uid) : $_W['uid'];
	$uniaccounts = array();

	$user_accounts = uni_user_accounts($uid);
	if (empty($user_accounts)) {
		return $uniaccounts;
	}

	if (user_is_vice_founder($uid)) {
		$user_type = ACCOUNT_MANAGE_NAME_VICE_FOUNDER;
	} elseif (user_is_founder($uid)) {
		$user_type = ACCOUNT_MANAGE_NAME_FOUNDER;
	} else {
		$user_type = ACCOUNT_MANAGE_NAME_OWNER;
	}

	foreach ($user_accounts as $key => $user_account) {
		if ($user_type == ACCOUNT_MANAGE_NAME_FOUNDER) {
			continue;
		} elseif ($user_type == ACCOUNT_MANAGE_NAME_VICE_FOUNDER) {
			if ($user_account['role'] != ACCOUNT_MANAGE_NAME_OWNER && $user_account['role'] != ACCOUNT_MANAGE_NAME_VICE_FOUNDER) {
				unset($user_accounts[$key]);
			}
		} else {
			if ($user_account['role'] != ACCOUNT_MANAGE_NAME_OWNER) {
				unset($user_accounts[$key]);
			}
		}
	}

	if (!empty($user_accounts)) {
		foreach ($user_accounts as $row) {
			$uniaccounts[$row['uniacid']] = uni_fetch($row['uniacid']);
		}
	}
	return $uniaccounts;
}


function uni_user_accounts($uid) {
	global $_W;
	$result = array();
	$uid = intval($uid) > 0 ? intval($uid) : $_W['uid'];
	$cachekey = cache_system_key("user_accounts:{$uid}");
	$cache = cache_load($cachekey);
	if (!empty($cache)) {
		return $cache;
	}
	$field = '';
	$where = '';
	$params = array();
	$user_is_founder = user_is_founder($uid);
	if (empty($user_is_founder) || user_is_vice_founder($uid)) {
		$field .= ', u.role';
		$where .= " LEFT JOIN " . tablename('uni_account_users') . " u ON u.uniacid = w.uniacid WHERE u.uid = :uid";
		$params[':uid'] = $uid;
	}
	$where .= !empty($where) ? " AND a.isdeleted <> 1 AND u.role IS NOT NULL" : " WHERE a.isdeleted <> 1";

	$sql = "SELECT w.acid, w.uniacid, w.key, w.secret, w.level, w.name" . $field . " FROM " . tablename('account_wechats') . " w LEFT JOIN " . tablename('account') . " a ON a.acid = w.acid AND a.uniacid = w.uniacid" . $where;
	$result = pdo_fetchall($sql, $params);
	cache_write($cachekey, $result);
	return $result;
}


function account_owner($uniacid = 0) {
	global $_W;
	$uniacid = intval($uniacid);
	if (empty($uniacid)) {
		return array();
	}
	$ownerid = pdo_getcolumn('uni_account_users', array('uniacid' => $uniacid, 'role' => 'owner'), 'uid');
	if (empty($ownerid)) {
		$ownerid = pdo_getcolumn('uni_account_users', array('uniacid' => $uniacid, 'role' => 'vice_founder'), 'uid');
		if (empty($ownerid)) {
			$founders = explode(',', $_W['config']['setting']['founder']);
			$ownerid = $founders[0];
		}
	}
	$owner = user_single($ownerid);
	if (empty($owner)) {
		return array();
	}
	return $owner;
}

function uni_accounts($uniacid = 0) {
	global $_W;
	$uniacid = empty($uniacid) ? $_W['uniacid'] : intval($uniacid);
	$account_info = pdo_get('account', array('uniacid' => $uniacid));
	if (!empty($account_info)) {
		$accounts = pdo_fetchall("SELECT w.*, a.type, a.isconnect FROM " . tablename('account') . " a INNER JOIN " . tablename(uni_account_tablename($account_info['type'])) . " w USING(acid) WHERE a.uniacid = :uniacid AND a.isdeleted <> 1 ORDER BY a.acid ASC", array(':uniacid' => $uniacid), 'acid');
	}
	return !empty($accounts) ? $accounts : array();
}


function uni_fetch($uniacid = 0) {
	global $_W;
	load()->model('mc');
	load()->model('user');

	$uniacid = empty($uniacid) ? $_W['uniacid'] : intval($uniacid);
	$cachekey = "uniaccount:{$uniacid}";
	$cache = cache_load($cachekey);
	if (!empty($cache)) {
		return $cache;
	}
	$account = uni_account_default($uniacid);
	$owneruid = pdo_fetchcolumn("SELECT uid FROM ".tablename('uni_account_users')." WHERE uniacid = :uniacid AND role = 'owner'", array(':uniacid' => $uniacid));
	$owner = user_single(array('uid' => $owneruid));
	$account['uid'] = $owner['uid'];

	$account['starttime'] = $owner['starttime'];
	$account['endtime'] = $owner['endtime'];
	$account['groups'] = mc_groups($uniacid);

	$account['setting'] = uni_setting($uniacid);
	$account['grouplevel'] = $account['setting']['grouplevel'];

	$account['logo'] = tomedia('headimg_'.$account['acid']. '.jpg').'?time='.time();
	$account['qrcode'] = tomedia('qrcode_'.$account['acid']. '.jpg').'?time='.time();

	cache_write($cachekey, $account);
	return $account;
}



function uni_modules_by_uniacid($uniacid, $enabled = true) {
	global $_W;
	load()->model('user');
	load()->model('module');
	$cachekey = cache_system_key(CACHE_KEY_ACCOUNT_MODULES, $uniacid, $enabled);
	$modules = cache_load($cachekey);

	if (empty($modules)) {
		$founders = explode(',', $_W['config']['setting']['founder']);
		$owner_uid = pdo_getcolumn('uni_account_users',  array('uniacid' => $uniacid, 'role' => 'owner'), 'uid');
		$condition = "WHERE 1";

		if (!empty($owner_uid) && !in_array($owner_uid, $founders)) {
			$uni_modules = array();
			$packageids = pdo_getall('uni_account_group', array('uniacid' => $uniacid), array('groupid'), 'groupid');
			$packageids = array_keys($packageids);

			if (!in_array('-1', $packageids)) {
				$uni_groups = pdo_fetchall("SELECT `modules` FROM " . tablename('uni_group') . " WHERE " .  "id IN ('".implode("','", $packageids)."') OR " . " uniacid = '{$uniacid}'");
				if (!empty($uni_groups)) {
					foreach ($uni_groups as $group) {
						$group_module = (array)iunserializer($group['modules']);
						$uni_modules = array_merge($group_module, $uni_modules);
					}
				}
				$user_modules = user_modules($owner_uid);
				$modules = array_merge(array_keys($user_modules), $uni_modules);
				if (!empty($modules)) {
					$condition .= " AND a.name IN ('" . implode("','", $modules) . "')";
				} else {
					$condition .= " AND a.name = ''";
				}
			}
		}
		$condition .= $enabled ?  " AND (b.enabled = 1 OR b.enabled is NULL) OR a.issystem = 1" : " OR a.issystem = 1";
		$sql = "SELECT a.name FROM " . tablename('modules') . " AS a LEFT JOIN " . tablename('uni_account_modules') . " AS b ON a.name = b.module AND b.uniacid = :uniacid " . $condition . " ORDER BY b.displayorder DESC, b.id DESC";
		$modules = pdo_fetchall($sql, array(':uniacid' => $uniacid), 'name');
		cache_write($cachekey, $modules);
	}

	$module_list = array();
	if (!empty($modules)) {
		foreach ($modules as $name => $module) {
			$module_info = module_fetch($name);
			if (!empty($module_info)) {
				$module_list[$name] = $module_info;
			}
		}
	}
	$module_list['core'] = array('title' => '系统事件处理模块', 'name' => 'core', 'issystem' => 1, 'enabled' => 1, 'isdisplay' => 0);
	return $module_list;
}


function uni_modules($enabled = true) {
	global $_W;
	return uni_modules_by_uniacid($_W['uniacid'], $enabled);
}

function uni_modules_app_binding() {
	global $_W;
	$cachekey = cache_system_key(CACHE_KEY_ACCOUNT_MODULES_BINDING, $_W['uniacid']);
	$cache = cache_load($cachekey);
	if (!empty($cache)) {
		return $cache;
	}
	load()->model('module');
	$result = array();
	$modules = uni_modules();
	if(!empty($modules)) {
		foreach($modules as $module) {
			if($module['type'] == 'system') {
				continue;
			}
			$entries = module_app_entries($module['name'], array('home', 'profile', 'shortcut', 'function', 'cover'));
			if(empty($entries)) {
				continue;
			}
			if($module['type'] == '') {
				$module['type'] = 'other';
			}
			$result[$module['name']] = array(
				'name' => $module['name'],
				'type' => $module['type'],
				'title' => $module['title'],
				'entries' => array(
					'cover' => $entries['cover'],
					'home' => $entries['home'],
					'profile' => $entries['profile'],
					'shortcut' => $entries['shortcut'],
					'function' => $entries['function']
				)
			);
			unset($module);
		}
	}
	cache_write($cachekey, $result);
	return $result;
}


function uni_groups($groupids = array(), $show_all = false) {
	load()->model('module');
	global $_W;
	$cachekey = cache_system_key(CACHE_KEY_UNI_GROUP);
	$list = cache_load($cachekey);
	if (empty($list)) {
		$condition = ' WHERE uniacid = 0';
		$list = pdo_fetchall("SELECT * FROM " . tablename('uni_group') . $condition . " ORDER BY id DESC", array(), 'id');
		if (!empty($groupids)) {
			if (in_array('-1', $groupids)) {
				$list[-1] = array('id' => -1, 'name' => '所有服务', 'modules' => array('title' => '系统所有模块'), 'templates' => array('title' => '系统所有模板'));
			}
			if (in_array('0', $groupids)) {
				$list[0] = array('id' => 0, 'name' => '基础服务', 'modules' => array('title' => '系统模块'), 'templates' => array('title' => '系统模板'));
			}
		}
		if (!empty($list)) {
			foreach ($list as $k=>&$row) {
				$row['wxapp'] = array();
				if (!empty($row['modules'])) {
					$modules = iunserializer($row['modules']);
					if (is_array($modules)) {
						$module_list = pdo_getall('modules', array('name' => $modules), array(), 'name');
						$row['modules'] = array();
						if (!empty($module_list)) {
							foreach ($module_list as $key => &$module) {
								$module = module_fetch($key);
								if ($module['wxapp_support'] == MODULE_SUPPORT_WXAPP) {
									$row['wxapp'][$module['name']] = $module;
								}
								if ($module['app_support'] == MODULE_SUPPORT_ACCOUNT) {
									if (!empty($module['main_module'])) {
										continue;
									}
									$row['modules'][$module['name']] = $module;
									if (!empty($module['plugin'])) {
										$group_have_plugin = array_intersect($module['plugin_list'], array_keys($module_list));
										if (!empty($group_have_plugin)) {
											foreach ($group_have_plugin as $plugin) {
												$row['modules'][$plugin] = module_fetch($plugin);
											}
										}
									}
								}
							}
						}
					}
				}

				if (!empty($row['templates'])) {
					$templates = iunserializer($row['templates']);
					if (is_array($templates)) {
						$row['templates'] = pdo_getall('site_templates', array('id' => $templates), array('id', 'name', 'title'), 'name');
					}
				}
			}
		}
		cache_write($cachekey, $list);
	}
	$group_list = array();
	if (!empty($groupids)) {
		foreach ($groupids as $id) {
			$group_list[$id] = $list[$id];
		}
	} else {
		if (user_is_vice_founder() && empty($show_all)) {
			foreach ($list as $group_key => $group) {
				if ($group['owner_uid'] != $_W['uid']) {
					unset($list[$group_key]);
					continue;
				}
			}
		}
		$group_list = $list;
	}
	return $group_list;
}


function uni_templates() {
	global $_W;
	$owneruid = pdo_fetchcolumn("SELECT uid FROM ".tablename('uni_account_users')." WHERE uniacid = :uniacid AND role = 'owner'", array(':uniacid' => $_W['uniacid']));
	load()->model('user');
		$owner = user_single(array('uid' => $owneruid));
	if (empty($owner) || user_is_founder($owner['uid'])) {
		$groupid = '-1';
	} else {
		$groupid = $owner['groupid'];
	}
	$extend = pdo_getall('uni_account_group', array('uniacid' => $_W['uniacid']), array(), 'groupid');
	if (!empty($extend) && $groupid != '-1') {
		$groupid = '-2';
	}
	if (empty($groupid)) {
		$templates = pdo_fetchall("SELECT * FROM " . tablename('site_templates') . " WHERE name = 'default'", array(), 'id');
	} elseif ($groupid == '-1') {
		$templates = pdo_fetchall("SELECT * FROM " . tablename('site_templates') . " ORDER BY id ASC", array(), 'id');
	} else {
		$group = pdo_fetch("SELECT id, name, package FROM ".tablename('users_group')." WHERE id = :id", array(':id' => $groupid));
		$packageids = iunserializer($group['package']);
		if (!empty($extend)) {
			foreach ($extend as $extend_packageid => $row) {
				$packageids[] = $extend_packageid;
			}
		}
		if(is_array($packageids)) {
			if (in_array('-1', $packageids)) {
				$templates = pdo_fetchall("SELECT * FROM " . tablename('site_templates') . " ORDER BY id ASC", array(), 'id');
			} else {
				$wechatgroup = pdo_fetchall("SELECT `templates` FROM " . tablename('uni_group') . " WHERE id IN ('".implode("','", $packageids)."') OR uniacid = '{$_W['uniacid']}'");
				$ms = array();
				$mssql = '';
				if (!empty($wechatgroup)) {
					foreach ($wechatgroup as $row) {
						$row['templates'] = iunserializer($row['templates']);
						if (!empty($row['templates'])) {
							foreach ($row['templates'] as $templateid) {
								$ms[$templateid] = $templateid;
							}
						}
					}
					$ms[] = 1;
					$mssql = " `id` IN ('".implode("','", $ms)."')";
				}
				$templates = pdo_fetchall("SELECT * FROM " . tablename('site_templates') .(!empty($mssql) ? " WHERE $mssql" : '')." ORDER BY id DESC", array(), 'id');
			}
		}
	}
	if (empty($templates)) {
		$templates = pdo_fetchall("SELECT * FROM " . tablename('site_templates') . " WHERE id = 1 ORDER BY id DESC", array(), 'id');
	}
	return $templates;
}


function uni_setting_save($name, $value) {
	global $_W;
	if (empty($name)) {
		return false;
	}
	if (is_array($value)) {
		$value = serialize($value);
	}
	$unisetting = pdo_get('uni_settings', array('uniacid' => $_W['uniacid']), array('uniacid'));
	if (!empty($unisetting)) {
		pdo_update('uni_settings', array($name => $value), array('uniacid' => $_W['uniacid']));
	} else {
		pdo_insert('uni_settings', array($name => $value, 'uniacid' => $_W['uniacid']));
	}
	$cachekey = "unisetting:{$_W['uniacid']}";
	cache_delete($cachekey);
	return true;
}


function uni_setting_load($name = '', $uniacid = 0) {
	global $_W;
	$uniacid = empty($uniacid) ? $_W['uniacid'] : $uniacid;
	$cachekey = "unisetting:{$uniacid}";
	$unisetting = cache_load($cachekey);
	if (empty($unisetting)) {
		$unisetting = pdo_get('uni_settings', array('uniacid' => $uniacid));
		if (!empty($unisetting)) {
			$serialize = array('site_info', 'stat', 'oauth', 'passport', 'uc', 'notify',
				'creditnames', 'default_message', 'creditbehaviors', 'payment',
				'recharge', 'tplnotice', 'mcplugin');
			foreach ($unisetting as $key => &$row) {
				if (in_array($key, $serialize) && !empty($row)) {
					$row = (array)iunserializer($row);
				}
			}
		}
		cache_write($cachekey, $unisetting);
	}
	if (empty($unisetting)) {
		return array();
	}
	if (empty($name)) {
		return $unisetting;
	}
	if (!is_array($name)) {
		$name = array($name);
	}
	return array_elements($name, $unisetting);
}

if (!function_exists('uni_setting')) {
	function uni_setting($uniacid = 0, $fields = '*', $force_update = false) {
		global $_W;
		load()->model('account');
		if ($fields == '*') {
			$fields = '';
		}
		return uni_setting_load($fields, $uniacid);
	}
}


function uni_account_default($uniacid = 0) {
	global $_W;
	$uniacid = empty($uniacid) ? $_W['uniacid'] : intval($uniacid);
	$uni_account = pdo_fetch("SELECT * FROM ".tablename('uni_account')." a LEFT JOIN ".tablename('account')." w ON a.uniacid = w.uniacid AND a.default_acid = w.acid WHERE a.uniacid = :uniacid", array(':uniacid' => $uniacid));
		if (empty($uni_account)) {
		$uni_account = pdo_fetch("SELECT * FROM ".tablename('uni_account')." a LEFT JOIN ".tablename('account')." w ON a.uniacid = w.uniacid WHERE a.uniacid = :uniacid ORDER BY w.acid DESC", array(':uniacid' => $uniacid));
	}
	if (!empty($uni_account)) {
		$account = pdo_get(uni_account_tablename($uni_account['type']), array('acid' => $uni_account['acid']));
		$account['type'] = $uni_account['type'];
		$account['isconnect'] = $uni_account['isconnect'];
		$account['isdeleted'] = $uni_account['isdeleted'];
		return $account;
	}
}

function uni_account_tablename($type) {
	switch ($type) {
		case ACCOUNT_TYPE_OFFCIAL_NORMAL:
		case ACCOUNT_TYPE_OFFCIAL_AUTH:
			return 'account_wechats';
		case ACCOUNT_TYPE_APP_NORMAL:
			return 'account_wxapp';
	}
}

function uni_user_account_role($uniacid, $uid, $role) {
	$vice_account = array(
		'uniacid' => intval($uniacid),
		'uid' => intval($uid),
		'role' => trim($role)
	);
	$account_user = pdo_get('uni_account_users', $vice_account, array('id'));
	if (!empty($account_user)) {
		return false;
	}
	return pdo_insert('uni_account_users', $vice_account);
}


function uni_permission($uid = 0, $uniacid = 0) {
	global $_W;
	load()->model('user');
	$role = '';
	$uid = empty($uid) ? $_W['uid'] : intval($uid);

	if (user_is_founder($uid) && !user_is_vice_founder($uid)) {
		return ACCOUNT_MANAGE_NAME_FOUNDER;
	}

	if (user_is_vice_founder($uid)) {
		return ACCOUNT_MANAGE_NAME_VICE_FOUNDER;
	}

	if (!empty($uniacid)) {
		$role = pdo_getcolumn('uni_account_users', array('uid' => $uid, 'uniacid' => $uniacid), 'role');
		if ($role == ACCOUNT_MANAGE_NAME_OWNER) {
			$role = ACCOUNT_MANAGE_NAME_OWNER;
		} elseif ($role == ACCOUNT_MANAGE_NAME_VICE_FOUNDER) {
			$role = ACCOUNT_MANAGE_NAME_VICE_FOUNDER;
		} elseif ($role == ACCOUNT_MANAGE_NAME_MANAGER) {
			$role = ACCOUNT_MANAGE_NAME_MANAGER;
		} elseif ($role == ACCOUNT_MANAGE_NAME_OPERATOR) {
			$role = ACCOUNT_MANAGE_NAME_OPERATOR;
		} elseif ($role == ACCOUNT_MANAGE_NAME_CLERK) {
			$role = ACCOUNT_MANAGE_NAME_CLERK;
		}
	} else {
		$roles = pdo_getall('uni_account_users', array('uid' => $uid), array('role'), 'role');
		$roles = array_keys($roles);
		if (in_array(ACCOUNT_MANAGE_NAME_VICE_FOUNDER, $roles)) {
			$role = ACCOUNT_MANAGE_NAME_VICE_FOUNDER;
		} elseif (in_array(ACCOUNT_MANAGE_NAME_OWNER, $roles)) {
			$role = ACCOUNT_MANAGE_NAME_OWNER;
		} elseif (in_array(ACCOUNT_MANAGE_NAME_MANAGER, $roles)) {
			$role = ACCOUNT_MANAGE_NAME_MANAGER;
		} elseif (in_array(ACCOUNT_MANAGE_NAME_OPERATOR, $roles)) {
			$role = ACCOUNT_MANAGE_NAME_OPERATOR;
		} elseif (in_array(ACCOUNT_MANAGE_NAME_CLERK, $roles)) {
			$role = ACCOUNT_MANAGE_NAME_CLERK;
		}
	}
	return $role;
}


function uni_user_permission_exist($uid = 0, $uniacid = 0) {
	global $_W;
	load()->model('user');
	$uid = intval($uid) > 0 ? $uid : $_W['uid'];
	$uniacid = intval($uniacid) > 0 ? $uniacid : $_W['uniacid'];
	if (user_is_founder($uid)) {
		return false;
	}
	if (FRAME == 'system') {
		return true;
	}
	$is_exist = pdo_get('users_permission', array('uid' => $uid, 'uniacid' => $uniacid), array('id'));
	if(empty($is_exist)) {
		return false;
	} else {
		return true;
	}
}


function uni_user_permission($type = 'system') {
	global $_W;
	$user_permission = pdo_getcolumn('users_permission', array('uid' => $_W['uid'], 'uniacid' => $_W['uniacid'], 'type' => $type), 'permission');
	if (!empty($user_permission)) {
		$user_permission = explode('|', $user_permission);
	} else {
		$user_permission = array('account*', 'wxapp*');
	}
	$permission_append = frames_menu_append();
		if (!empty($permission_append[$_W['role']])) {
		$user_permission = array_merge($user_permission, $permission_append[$_W['role']]);
	}
		if (empty($_W['role']) && empty($_W['uniacid'])) {
		$user_permission = array_merge($user_permission, $permission_append['operator']);
	}
	return (array)$user_permission;
}


function uni_user_menu_permission($uid, $uniacid, $type) {
	$user_menu_permission = array();

	$uid = intval($uid);
	$uniacid = intval($uniacid);
	$type = trim($type);
	if (empty($uid) || empty($uniacid) || empty($type)) {
		return error(-1, '参数错误！');
	}
	$permission_exist = uni_user_permission_exist($uid, $uniacid);
	if (empty($permission_exist)) {
		return array('all');
	}
	if ($type == 'modules') {
		$user_menu_permission = pdo_fetchall("SELECT * FROM " . tablename('users_permission') . " WHERE uniacid = :uniacid AND uid  = :uid AND type != '" . PERMISSION_ACCOUNT . "' AND type != '" . PERMISSION_WXAPP . "'", array(':uniacid' => $uniacid, ':uid' => $uid), 'type');
	} else {
		$module = uni_modules_by_uniacid($uniacid);
		$module = array_keys($module);
		if (in_array($type, $module) || in_array($type, array(PERMISSION_ACCOUNT, PERMISSION_WXAPP, PERMISSION_SYSTEM))) {
			$menu_permission = pdo_getcolumn('users_permission', array('uniacid' => $uniacid, 'uid' => $uid, 'type' => $type), 'permission');
			if (!empty($menu_permission)) {
				$user_menu_permission = explode('|', $menu_permission);
			}
		}
	}

	return $user_menu_permission;
}


function uni_permission_name() {
	load()->model('system');
	$menu_permission = array();

	$menu_list = system_menu_permission_list();
	$middle_menu = array();
	$middle_sub_menu = array();
	if (!empty($menu_list)) {
		foreach ($menu_list as $nav_id => $section) {
			foreach ($section['section'] as $section_id => $section) {
				if (!empty($section['menu'])) {
					$middle_menu[] = $section['menu'];
				}
			}
		}
	}

	if (!empty($middle_menu)) {
		foreach ($middle_menu as $menu) {
			foreach ($menu as $menu_val) {
				$menu_permission[] = $menu_val['permission_name'];
				if (!empty($menu_val['sub_permission'])) {
					$middle_sub_menu[] = $menu_val['sub_permission'];
				}
			}
		}
	}

	if (!empty($middle_sub_menu)) {
		foreach ($middle_sub_menu as $sub_menu) {
			foreach ($sub_menu as $sub_menu_val) {
				$menu_permission[] = $sub_menu_val['permission_name'];
			}
		}
	}
	return $menu_permission;
}


function uni_update_user_permission($uid, $uniacid, $data) {
	global $_GPC;
	$uid = intval($uid);
	$uniacid = intval($uniacid);
	if (empty($uid) || empty($uniacid) || !in_array($data['type'], array(PERMISSION_ACCOUNT, PERMISSION_WXAPP, PERMISSION_SYSTEM))) {
		return error('-1', '参数错误！');
	}
	$user_menu_permission = uni_user_menu_permission($uid, $uniacid, $data['type']);
	if (is_error($user_menu_permission)) {
		return error('-1', '参数错误！');
	}

	if (empty($user_menu_permission)) {
		$insert = array(
			'uniacid' => $uniacid,
			'uid' => $uid,
			'type' => $data['type'],
			'permission' => $data['permission'],
		);
		$result = pdo_insert('users_permission', $insert);
	} else {
		$update = array(
			'permission' => $data['permission'],
		);
		$result = pdo_update('users_permission', $update, array('uniacid' => $uniacid, 'uid' => $uid, 'type' => $data['type']));
	}
	return $result;
}


function uni_user_see_more_info($user_type, $see_more = false) {
	global $_W;
	if (empty($user_type)) {
		return false;
	}
	if ($user_type == ACCOUNT_MANAGE_NAME_VICE_FOUNDER && !empty($see_more) || $_W['role'] != $user_type) {
		return true;
	}

	return false;
}

function uni_user_permission_check($permission_name, $show_message = true, $action = '') {
	global $_W, $_GPC;
	$user_has_permission = uni_user_permission_exist();
	if (empty($user_has_permission)) {
		return true;
	}
	$modulename = trim($_GPC['m']);
	$do = trim($_GPC['do']);
	$entry_id = intval($_GPC['eid']);

	if ($action == 'reply') {
		$system_modules = system_modules();
		if (!empty($modulename) && !in_array($modulename, $system_modules)) {
			$permission_name = $modulename . '_rule';
			$users_permission = uni_user_permission($modulename);
		}
	} elseif ($action == 'cover' && $entry_id > 0) {
		load()->model('module');
		$entry = module_entry($entry_id);
		if (!empty($entry)) {
			$permission_name = $entry['module'] . '_cover_' . trim($entry['do']);
			$users_permission = uni_user_permission($entry['module']);
		}
	} elseif ($action == 'nav') {
				if(!empty($modulename)) {
			$permission_name = "{$modulename}_{$do}";
			$users_permission = uni_user_permission($modulename);
		} else {
			return true;
		}
	} elseif ($action == 'wxapp') {
		$users_permission = uni_user_permission('wxapp');
	} else {
		$users_permission = uni_user_permission('system');
	}
	if (!isset($users_permission)) {
		$users_permission = uni_user_permission('system');
	}
	if ($users_permission[0] != 'all' && !in_array($permission_name, $users_permission)) {
		if ($show_message) {
			itoast('您没有进行该操作的权限', referer(), 'error');
		} else {
			return false;
		}
	}
	return true;
}


function uni_user_module_permission_check($action = '', $module_name = '') {
	global $_GPC;
	$status = uni_user_permission_exist();
	if(empty($status)) {
		return true;
	}
	$a = trim($_GPC['a']);
	$do = trim($_GPC['do']);
	$m = trim($_GPC['m']);
		if ($a == 'module' && $do == 'setting' && !empty($m)) {
		$permission_name = $m . '_setting';
		$users_permission = uni_user_permission($m);
		if ($users_permission[0] != 'all' && !in_array($permission_name, $users_permission)) {
			return false;
		}
			} elseif (!empty($do) && !empty($m)) {
		$is_exist = pdo_get('modules_bindings', array('module' => $m, 'do' => $do, 'entry' => 'menu'), array('eid'));
		if(empty($is_exist)) {
			return true;
		}
	}
	if(empty($module_name)) {
		$module_name = IN_MODULE;
	}
	$permission = uni_user_permission($module_name);
	if(empty($permission) || ($permission[0] != 'all' && !empty($action) && !in_array($action, $permission))) {
		return false;
	}
	return true;
}


function uni_user_account_permission($uid = 0) {
	global $_W;
	$uid = intval($uid);
	if ($uid <= 0) {
		$user = $_W['user'];
	} else {
		load()->model('user');
		$user = user_single($uid);
	}
	if (user_is_vice_founder($user['uid']) && empty($uid)) {
		$role = ACCOUNT_MANAGE_NAME_VICE_FOUNDER;
		$group = pdo_get('users_founder_group', array('id' => $user['groupid']));
		$group_num = uni_owner_account_nums($user['uid'], $role);
		$uniacid_limit = max((intval($group['maxaccount']) - $group_num['account_num']), 0);
		$wxapp_limit = max((intval($group['maxwxapp']) - $group_num['wxapp_num']), 0);
	} else {
		$role = ACCOUNT_MANAGE_NAME_OWNER;
		$group = pdo_get('users_group', array('id' => $user['groupid']));
		$group_num = uni_owner_account_nums($user['uid'], $role);
		$uniacid_limit = max((intval($group['maxaccount']) - $group_num['account_num']), 0);
		$wxapp_limit = max((intval($group['maxwxapp']) - $group_num['wxapp_num']), 0);
		if (empty($_W['isfounder'])) {
			if (!empty($user['owner_uid'])) {
				$role = ACCOUNT_MANAGE_NAME_VICE_FOUNDER;
				$group_id = pdo_getcolumn('users', array('uid' => $user['owner_uid']), 'groupid');
				$group_vice = pdo_get('users_founder_group', array('id' => $group_id));
				$account_vice_num = uni_owner_account_nums($user['owner_uid'], $role);

				$uniacid_limit_vice = max((intval($group_vice['maxaccount']) - $account_vice_num['account_num']), 0);
				$wxapp_limit_vice = max((intval($group_vice['maxwxapp']) - $account_vice_num['wxapp_num']), 0);

				$uniacid_limit = min($uniacid_limit, $uniacid_limit_vice);
				$wxapp_limit = min($wxapp_limit, $wxapp_limit_vice);

				$group['maxaccount'] = min(intval($group['maxaccount']), intval($group_vice['maxaccount']));
				$group['maxwxapp'] = min(intval($group['maxwxapp']), intval($group_vice['maxwxapp']));
			}
		}
	}
	$data = array(
		'group_name' => $group['name'],
		'vice_group_name' => $group_vice['name'],
		'maxaccount' => $group['maxaccount'],
		'uniacid_num' => $group_num['account_num'],
		'uniacid_limit' => $uniacid_limit,
		'maxwxapp' => $group['maxwxapp'],
		'wxapp_num' => $group_num['wxapp_num'],
		'wxapp_limit' => $wxapp_limit,
	);
	return $data;
}


function uni_owner_account_nums($uid, $role) {
	$account_num = $wxapp_num = 0;
	$condition = array('uid' => $uid, 'role' => $role);
	$uniacocunts = pdo_getall('uni_account_users', $condition, array(), 'uniacid');
	if (!empty($uniacocunts)) {
		$all_account = pdo_fetchall('SELECT * FROM (SELECT u.uniacid, a.default_acid FROM ' . tablename('uni_account_users') . ' as u RIGHT JOIN '. tablename('uni_account').' as a  ON a.uniacid = u.uniacid  WHERE u.uid = :uid AND u.role = :role ) AS c LEFT JOIN '.tablename('account').' as d ON c.default_acid = d.acid WHERE d.isdeleted = 0', array(':uid' => $uid, ':role' => $role));
		foreach ($all_account as $account) {
			if ($account['type'] == 1 || $account['type'] == 3) {
				$account_num++;
			}
			if ($account['type'] == 4) {
				$wxapp_num++;
			}
		}
	}
	$num = array(
		'account_num' => $account_num,
		'wxapp_num' =>$wxapp_num
	);
	return $num;
}
function uni_update_week_stat() {
	global $_W;
	$cachekey = "stat:todaylock:{$_W['uniacid']}";
	$cache = cache_load($cachekey);
	if(!empty($cache) && $cache['expire'] > TIMESTAMP) {
		return true;
	}
	$seven_days = array(
		date('Ymd', strtotime('-1 days')),
		date('Ymd', strtotime('-2 days')),
		date('Ymd', strtotime('-3 days')),
		date('Ymd', strtotime('-4 days')),
		date('Ymd', strtotime('-5 days')),
		date('Ymd', strtotime('-6 days')),
		date('Ymd', strtotime('-7 days')),
	);
	$week_stat_fans = pdo_getall('stat_fans', array('date' => $seven_days, 'uniacid' => $_W['uniacid']), '', 'date');
	$stat_update_yes = false;
	foreach ($seven_days as $sevens) {
		if (empty($week_stat_fans[$sevens]) || $week_stat_fans[$sevens]['cumulate'] <=0) {
			$stat_update_yes = true;
			break;
		}
	}
	if (empty($stat_update_yes)) {
		return true;
	}
	foreach($seven_days as $sevens) {
		if($_W['account']['level'] == ACCOUNT_SUBSCRIPTION_VERIFY || $_W['account']['level'] == ACCOUNT_SERVICE_VERIFY) {
			$account_obj = WeAccount::create();
			$weixin_stat = $account_obj->getFansStat();
			if(is_error($weixin_stat) || empty($weixin_stat)) {
				return error(-1, '调用微信接口错误');
			} else {
				$update_stat = array();
				$update_stat = array(
					'uniacid' => $_W['uniacid'],
					'new' => $weixin_stat[$sevens]['new'],
					'cancel' => $weixin_stat[$sevens]['cancel'],
					'cumulate' => $weixin_stat[$sevens]['cumulate'],
					'date' => $sevens,
				);
			}
		} else {
			$update_stat = array();
			$update_stat['cumulate'] = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('mc_mapping_fans') . " WHERE acid = :acid AND uniacid = :uniacid AND follow = :follow AND followtime < :endtime", array(':acid' => $_W['acid'], ':uniacid' => $_W['uniacid'], ':endtime' => strtotime($sevens)+86400, ':follow' => 1));
			$update_stat['date'] = $sevens;
			$update_stat['new'] = $week_stat_fans[$sevens]['new'];
			$update_stat['cancel'] = $week_stat_fans[$sevens]['cancel'];
			$update_stat['uniacid'] = $_W['uniacid'];
		}
		if(empty($week_stat_fans[$sevens])) {
			pdo_insert('stat_fans', $update_stat);
		} elseif (empty($week_stat_fans[$sevens]['cumulate']) || $week_stat_fans[$sevens]['cumulate'] < 0) {
			pdo_update('stat_fans', $update_stat, array('id' => $week_stat_fans[$sevens]['id']));
		}
	}
	cache_write($cachekey, array('expire' => TIMESTAMP + 7200));
	return true;
}


function uni_account_rank_top($uniacid) {
	global $_W;
	if (!empty($_W['isfounder'])) {
		$max_rank = pdo_getcolumn('uni_account', array(), 'max(rank)');
		pdo_update('uni_account', array('rank' => ($max_rank + 1)), array('uniacid' => $uniacid));
	}else {
		$max_rank = pdo_getcolumn('uni_account_users', array('uid' => $_W['uid']), 'max(rank)');
		pdo_update('uni_account_users', array('rank' => ($max_rank['maxrank'] + 1)), array('uniacid' => $uniacid, 'uid' => $_W['uid']));
	}
	return true;
}

function uni_account_last_switch() {
	global $_W, $_GPC;
	$cache_key = cache_system_key(CACHE_KEY_ACCOUNT_SWITCH, $_GPC['__switch']);
	$cache_lastaccount = (array)cache_load($cache_key);
	if (strexists($_W['siteurl'], 'c=wxapp')) {
		$uniacid = $cache_lastaccount['wxapp'];
	} else {
		$uniacid = $cache_lastaccount['account'];
	}
	if (!empty($uniacid)) {
		$account_info = uni_fetch($uniacid);
		$role = uni_permission($_W['uid'], $uniacid);
		if (!empty($account_info) && $account_info['isdeleted'] == 1 || empty($role)) {
			$uniacid = '';
		}
	}
	return $uniacid;
}

function uni_account_switch($uniacid, $redirect = '') {
	global $_W;
	isetcookie('__uniacid', $uniacid, 7 * 86400);
	isetcookie('__uid', $_W['uid'], 7 * 86400);
	if (!empty($redirect)) {
		header('Location: ' . $redirect);
		exit;
	}
	return true;
}


function uni_account_save_switch($uniacid) {
	global $_W, $_GPC;
	if (empty($_GPC['__switch'])) {
		$_GPC['__switch'] = random(5);
	}

	$cache_key = cache_system_key(CACHE_KEY_ACCOUNT_SWITCH, $_GPC['__switch']);
	$cache_lastaccount = cache_load($cache_key);
	if (empty($cache_lastaccount)) {
		$cache_lastaccount = array(
			'account' => $uniacid,
		);
	} else {
		$cache_lastaccount['account'] = $uniacid;
	}
	cache_write($cache_key, $cache_lastaccount);
	isetcookie('__switch', $_GPC['__switch'], 7 * 86400);
	return true;
}

function uni_account_list($condition, $pager) {
	global $_W;
	load()->model('wxapp');

	$sql = "SELECT %s FROM ". tablename('uni_account'). " as a LEFT JOIN " .
			tablename('account'). " as b ON a.uniacid = b.uniacid AND a.default_acid = b.acid ";

	if (!empty($pager)) {
		$limit = " LIMIT " . ($pager[0] - 1) * $pager[1] . ',' . $pager[1];
	}

		if (empty($_W['isfounder']) || user_is_vice_founder()) {
		$sql .= " LEFT JOIN ". tablename('uni_account_users')." as c ON a.uniacid = c.uniacid
		WHERE a.default_acid <> 0 AND c.uid = :uid";
		$params[':uid'] = $_W['uid'];

		$order_by = " ORDER BY c.`rank` DESC";
	} else {
		$sql .= " WHERE a.default_acid <> 0";

		$order_by = " ORDER BY a.`rank` DESC";
	}

	$sql .= " AND b.isdeleted <> 1 ";

	if (!empty($condition['keyword'])) {
		$sql .=" AND a.`name` LIKE :name";
		$params[':name'] = "%{$condition['keyword']}%";
	}

	if(isset($condition['letter'])) {
		if (!empty($condition['letter'])) {
			$sql .= " AND a.`title_initial` = :title_initial";
			$params[':title_initial'] = $condition['letter'];
		} else {
			$sql .= " AND a.`title_initial` = ''";
		}
	}

	if (!empty($condition['type'])) {
		$sql .= " AND b.type IN (" . implode(',', $condition['type']) . ")";
	}

	$sql .= $order_by;
	$sql .= ", a.`uniacid` DESC ";

	$list = pdo_fetchall(sprintf($sql, 'a.uniacid') . $limit, $params);
	$total = pdo_fetchcolumn(sprintf($sql, 'COUNT(*)'), $params);

	if (!empty($list)) {
		foreach($list as &$account) {
			$account = uni_fetch($account['uniacid']);
			$account['url'] = url('account/display/switch', array('uniacid' => $account['uniacid']));
			$account['role'] = uni_permission($_W['uid'], $account['uniacid']);
			$account['setmeal'] = uni_setmeal($account['uniacid']);

			if (!empty($settings['notify'])) {
				$account['sms'] = $account['setting']['notify']['sms']['balance'];
			} else {
				$account['sms'] = 0;
			}

			if (in_array(ACCOUNT_TYPE_APP_NORMAL, $condition['type'])) {
				$account['versions'] = wxapp_get_some_lastversions($account['uniacid']);
				$account['current_version'] = array();
				if (!empty($account['versions'])) {
					foreach ($account['versions'] as $version) {
						if (!empty($wxapp_cookie_uniacids) && !empty($wxappversionids[$version['uniacid']]) && in_array($version['id'], $wxappversionids[$version['uniacid']])) {
							$account['current_version'] = $version;
							break;
						}
					}
					if (empty($account['current_version'])) {
						$account['current_version'] = $account['versions'][0];
					}
				}
			}
		}
	}
	$result = array(
		'list' => $list,
		'total' => $total
	);
	return $result;
}


function account_create($uniacid, $account) {
	$accountdata = array('uniacid' => $uniacid, 'type' => $account['type'], 'hash' => random(8));
	pdo_insert('account', $accountdata);
	$acid = pdo_insertid();
	$account['acid'] = $acid;
	$account['token'] = random(32);
	$account['encodingaeskey'] = random(43);
	$account['uniacid'] = $uniacid;
	unset($account['type']);
	pdo_insert('account_wechats', $account);
	return $acid;
}


function account_fetch($acid) {
	$account_info = pdo_get('account', array('acid' => $acid));
	if (empty($account_info)) {
		return error(-1, '公众号不存在');
	}
	return uni_fetch($account_info['uniacid']);
}


function uni_setmeal($uniacid = 0) {
	global $_W;
	if(!$uniacid) {
		$uniacid = $_W['uniacid'];
	}
	$owneruid = pdo_fetchcolumn("SELECT uid FROM ".tablename('uni_account_users')." WHERE uniacid = :uniacid AND role = 'owner'", array(':uniacid' => $uniacid));
	if(empty($owneruid)) {
		$user = array(
			'uid' => -1,
			'username' => '创始人',
			'timelimit' => '未设置',
			'groupid' => '-1',
			'groupname' => '所有服务'
		);
		return $user;
	}
	load()->model('user');
	$groups = pdo_getall('users_group', array(), array('id', 'name'), 'id');
	$owner = user_single(array('uid' => $owneruid));
	$user = array(
		'uid' => $owner['uid'],
		'username' => $owner['username'],
		'groupid' => $owner['groupid'],
		'groupname' => $groups[$owner['groupid']]['name']
	);
	if(empty($owner['endtime'])) {
		$user['timelimit'] = date('Y-m-d', $owner['starttime']) . ' ~ 无限制' ;
	} else {
		if($owner['endtime'] <= TIMESTAMP) {
			$user['timelimit'] = '已到期';
		} else {
			$year = 0;
			$month = 0;
			$day = 0;
			$endtime = $owner['endtime'];
			$time = strtotime('+1 year');
			while ($endtime > $time)
			{
				$year = $year + 1;
				$time = strtotime("+1 year", $time);
			};
			$time = strtotime("-1 year", $time);
			$time = strtotime("+1 month", $time);
			while($endtime > $time)
			{
				$month = $month + 1;
				$time = strtotime("+1 month", $time);
			} ;
			$time = strtotime("-1 month", $time);
			$time = strtotime("+1 day", $time);
			while($endtime > $time)
			{
				$day = $day + 1;
				$time = strtotime("+1 day", $time);
			} ;
			if (empty($year)) {
				$timelimit = empty($month)? $day.'天' : date('Y-m-d', $owner['starttime']) . '~'. date('Y-m-d', $owner['endtime']);
			}else {
				$timelimit = date('Y-m-d', $owner['starttime']) . '~'. date('Y-m-d', $owner['endtime']);
			}
			$user['timelimit'] = $timelimit;
		}
	}
	return $user;
}


function uni_is_multi_acid($uniacid = 0) {
	global $_W;
	if(!$uniacid) {
		$uniacid = $_W['uniacid'];
	}
	$cachekey = "unicount:{$uniacid}";
	$nums = cache_load($cachekey);
	$nums = intval($nums);
	if(!$nums) {
		$nums = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('account_wechats') . ' WHERE uniacid = :uniacid', array(':uniacid' => $_W['uniacid']));
		cache_write($cachekey, $nums);
	}
	if($nums == 1) {
		return false;
	}
	return true;
}

function account_delete($acid) {
	global $_W;
	load()->func('file');
	load()->model('module');
		$account = pdo_get('uni_account', array('default_acid' => $acid));
	if ($account) {
		$uniacid = $account['uniacid'];
		$state = uni_permission($_W['uid'], $uniacid);
		if($state != ACCOUNT_MANAGE_NAME_FOUNDER && $state != ACCOUNT_MANAGE_NAME_OWNER) {
			itoast('没有该公众号操作权限！', url('account/recycle'), 'error');
		}
		if($uniacid == $_W['uniacid']) {
			isetcookie('__uniacid', '');
		}
		cache_delete("unicount:{$uniacid}");
		$modules = array();
				$rules = pdo_fetchall("SELECT id, module FROM ".tablename('rule')." WHERE uniacid = '{$uniacid}'");
		if (!empty($rules)) {
			foreach ($rules as $index => $rule) {
				$deleteid[] = $rule['id'];
			}
			pdo_delete('rule', "id IN ('".implode("','", $deleteid)."')");
		}

		$subaccount = pdo_fetchall("SELECT acid FROM ".tablename('account')." WHERE uniacid = :uniacid", array(':uniacid' => $uniacid));
		if (!empty($subaccount)) {
			foreach ($subaccount as $account) {
				@unlink(IA_ROOT . '/attachment/qrcode_'.$account['acid'].'.jpg');
				@unlink(IA_ROOT . '/attachment/headimg_'.$account['acid'].'.jpg');
				file_remote_delete('qrcode_'.$account['acid'].'.jpg');
				file_remote_delete('headimg_'.$account['acid'].'.jpg');
			}
			if (!empty($acid)) {
				rmdirs(IA_ROOT . '/attachment/images/' . $uniacid);
				@rmdir(IA_ROOT . '/attachment/images/' . $uniacid);
				rmdirs(IA_ROOT . '/attachment/audios/' . $uniacid);
				@rmdir(IA_ROOT . '/attachment/audios/' . $uniacid);
			}
		}

				$tables = array(
			'account','account_wechats', 'account_wxapp', 'wxapp_versions', 'core_attachment','core_paylog','core_queue','core_resource',
			'wechat_attachment', 'cover_reply', 'mc_chats_record','mc_credits_recharge','mc_credits_record',
			'mc_fans_groups','mc_groups','mc_handsel','mc_mapping_fans','mc_mapping_ucenter','mc_mass_record',
			'mc_member_address','mc_member_fields','mc_members','menu_event',
			'qrcode','qrcode_stat', 'rule','rule_keyword','site_article','site_category','site_multi','site_nav','site_slide',
			'site_styles','site_styles_vars','stat_keyword', 'stat_rule','uni_account','uni_account_modules','uni_account_users','uni_settings', 'uni_group', 'uni_verifycode','users_permission',
			'mc_member_fields',
		);
		if (!empty($tables)) {
			foreach ($tables as $table) {
				$tablename = str_replace($GLOBALS['_W']['config']['db']['tablepre'], '', $table);
				pdo_delete($tablename, array( 'uniacid'=> $uniacid));
			}
		}
	} else {
		$account = account_fetch($acid);
		if (empty($account)) {
			itoast('子公众号不存在或是已经被删除', '', '');
		}
		$uniacid = $account['uniacid'];
		$state = uni_permission($_W['uid'], $uniacid);
		if($state != ACCOUNT_MANAGE_NAME_FOUNDER && $state != ACCOUNT_MANAGE_NAME_OWNER) {
			itoast('没有该公众号操作权限！', url('account/recycle'), 'error');
		}
		$uniaccount = uni_fetch($account['uniacid']);
		if ($uniaccount['default_acid'] == $acid) {
			itoast('默认子公众号不能删除', '', '');
		}
		pdo_delete('account', array('acid' => $acid));
		pdo_delete('account_wechats', array('acid' => $acid, 'uniacid' => $uniacid));
		cache_delete("unicount:{$uniacid}");
		cache_delete("unisetting:{$uniacid}");
		cache_delete('account:auth:refreshtoken:'.$acid);
		$oauth = uni_setting($uniacid, array('oauth'));
		if($oauth['oauth']['account'] == $acid) {
			$acid = pdo_fetchcolumn('SELECT acid FROM ' . tablename('account_wechats') . " WHERE uniacid = :id AND level = 4 AND secret != '' AND `key` != ''", array(':id' => $uniacid));
			pdo_update('uni_settings', array('oauth' => iserializer(array('account' => $acid, 'host' => $oauth['oauth']['host']))), array('uniacid' => $uniacid));
		}
		@unlink(IA_ROOT . '/attachment/qrcode_'.$acid.'.jpg');
		@unlink(IA_ROOT . '/attachment/headimg_'.$acid.'.jpg');
		file_remote_delete('qrcode_'.$acid.'.jpg');
		file_remote_delete('headimg_'.$acid.'.jpg');
	}
	return true;
}


function account_wechatpay_proxy () {
	global $_W;
	$proxy_account = cache_load(cache_system_key('proxy_wechatpay_account:'));
	if (empty($proxy_account)) {
		$proxy_account = cache_build_proxy_wechatpay_account();
	}
	unset($proxy_account['borrow'][$_W['uniacid']]);
	unset($proxy_account['service'][$_W['uniacid']]);
	return $proxy_account;
}


function uni_account_module_shortcut_enabled($modulename, $uniacid = 0, $status = STATUS_ON) {
	global $_W;
	$module = module_fetch($modulename);
	if(empty($module)) {
		return error(1, '抱歉，你操作的模块不能被访问！');
	}
	$uniacid = intval($uniacid);
	$uniacid = !empty($uniacid) ? $uniacid : $_W['uniacid'];

	$module_status = pdo_get('uni_account_modules', array('module' => $modulename, 'uniacid' => $uniacid), array('id', 'shortcut'));
	if (empty($module_status)) {
		$data = array(
			'uniacid' => $uniacid,
			'module' => $modulename,
			'enabled' => STATUS_ON,
			'shortcut' => $status ? STATUS_ON : STATUS_OFF,
			'settings' => '',
		);
		pdo_insert('uni_account_modules', $data);
	} else {
		$data = array(
			'shortcut' => $status ? STATUS_ON : STATUS_OFF,
		);
		pdo_update('uni_account_modules', $data, array('id' => $module_status['id']));
		cache_build_module_info($modulename);
	}
	return true;
}


function uni_account_member_fields($uniacid) {
	if (empty($uniacid)) {
		return array();
	}
	$account_member_fields = pdo_getall('mc_member_fields', array('uniacid' => $uniacid), array(), 'fieldid');
	$system_member_fields = pdo_getall('profile_fields', array(), array(), 'id');
	$less_field_indexes = array_diff(array_keys($system_member_fields), array_keys($account_member_fields));
	if (empty($less_field_indexes)) {
		foreach ($account_member_fields as &$field) {
			$field['field'] = $system_member_fields[$field['fieldid']]['field'];
		}
		unset($field);
		return $account_member_fields;
	}

	$account_member_add_fields = array('uniacid' => $uniacid);
	foreach ($less_field_indexes as $field_index) {
		$account_member_add_fields['fieldid'] = $system_member_fields[$field_index]['id'];
		$account_member_add_fields['title'] = $system_member_fields[$field_index]['title'];
		$account_member_add_fields['available'] = $system_member_fields[$field_index]['available'];
		$account_member_add_fields['displayorder'] = $system_member_fields[$field_index]['displayorder'];
		pdo_insert('mc_member_fields', $account_member_add_fields);
		$insert_id = pdo_insertid();
		$account_member_fields[$insert_id]['id'] = $insert_id;
		$account_member_fields[$insert_id]['field'] = $system_member_fields[$field_index]['field'];
		$account_member_fields[$insert_id]['fid'] = $system_member_fields[$field_index]['id'];
		$account_member_fields[$insert_id] = array_merge($account_member_fields[$insert_id], $account_member_add_fields);
	}
	return $account_member_fields;
}
function getgroupmodules($groupid){
	$group   =pdo_get('users_group',array('id'=>$groupid));
	if(empty($group)){return '';}
	else{
		$package =iunserializer($group['package']);
		$taocmd  =array();
	    if(in_array(-1,$package)){
			$modules = pdo_fetchall("SELECT name FROM ". tablename('modules')."WHERE issystem=0");
	        foreach($modules as $value){
			    $taocmd[].=$value['name'];
		    }
        }
        else{
            foreach($package as $value){
		        $values=pdo_get('uni_group',array('id' =>$value),'modules');
			    $values=iunserializer($values['modules']);
			    $taocmd =array_merge($taocmd,$values);
		    }
	    }
		return $taocmd;
	}
	
}