<?php
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
 
defined('IN_IA') or exit('Access Denied');

$dos = array('display', 'post', 'display_status', 'delete');
$do = in_array($do, $dos) ? $do : 'display';
$_W['page']['title'] = '系统管理 - 菜单设置';

$system_menu = cache_load('system_frame');
$system_top_menu = array('account', 'wxapp', 'module');
if(empty($system_menu)) {
	cache_build_frame_menu();
	$system_menu = cache_load('system_frame');
}

$system_menu_permission = array();
if (!empty($system_menu)) {
	foreach ($system_menu as $menu_name => $menu) {
		if (in_array($menu_name, $system_top_menu)) {
			$system_menu_permission[] = $menu_name;
		}
		if (!empty($menu['section'])) {
			foreach ($menu['section'] as $section_name => $section) {
				if (!empty($section['menu'])) {
					foreach ($section['menu']  as $permission_name => $sub_menu) {
						if ($sub_menu['is_system']) {
							$system_menu_permission[] = $sub_menu['permission_name'];
						}
					}
				}
			}
		}
	}
}

if ($do == 'display') {
	$add_top_nav = pdo_getall('core_menu', array('group_name' => 'frame', 'is_system <>' => 1), array('title', 'url', 'permission_name'));
	if (!empty($add_top_nav)) {
		foreach ($add_top_nav as $menu) {
			$system_menu[$menu['permission_name']] = array(
				'title' => $menu['title'],
				'is_system' => 0,
				'permission_name' => $menu['permission_name'],
				'url' => $menu['url'],
			);
		}
	}
	template('system/menu');
} elseif ($do == 'post') {
	$id = intval($_GPC['id']);
	if ($_GPC['group'] == 'platform_module') {
		iajax(-1, '应用模块下不可添加下级分类！', referer());
	}
	$menu = array(
		'title' => $_GPC['title'],
		'url' => $_GPC['url'],
		'permission_name' => $_GPC['permissionName'],
		'is_system' => $_GPC['isSystem'],
		'displayorder' => $_GPC['displayorder'],
		'type' => 'url',
		'icon' => $_GPC['icon'],
	);
	if (empty($menu['title']) || empty($menu['url']) || empty($menu['permission_name'])) {
		iajax(-1, '请完善菜单信息', referer());
	}
	if (!preg_match('/^[a-zA-Z0-9_]+$/', $menu['permission_name'], $match)) {
		iajax(-1, '菜单标识只能是数字、字母、下划线', referer());
	}
	if (empty($menu['is_system']) && substr($menu['url'], 0, 4) != 'http' && substr($menu['url'], 0, 2) != '//') {
		iajax(-1, '请输入完整的链接', referer());
	}
	if (in_array($menu['permission_name'], $system_menu_permission)) {
		$menu['is_system'] = 1;
		unset($menu['url']);
	} else {
		$menu['group_name'] = $_GPC['group'];
		$menu['is_system'] = 0;
		
		$menu_db = pdo_get('core_menu', array('permission_name' => $menu['permission_name']));
		if (!empty($menu_db) && $menu_db['id'] != $id) {
			iajax(-1, '菜单标识不得重复请更换', referer());
		}
		
	}
	$permission_name = $menu['permission_name'];
	$menu_db = pdo_get('core_menu', array('permission_name' => $permission_name));
	
	if (!empty($menu_db)) {
		unset($menu['permission_name']);
		$menu['group_name'] = $menu_db['group_name'];
		pdo_update('core_menu', $menu, array('permission_name' => $permission_name));
	} else {
		$menu['is_display'] = 1;
		pdo_insert('core_menu', $menu);
	}
	cache_build_frame_menu();
	iajax(0, '更新成功', referer());
} elseif ($do == 'display_status') {
	$permission_name = $_GPC['permission_name'];
	$status = intval($_GPC['status']);
	$menu_db = pdo_get('core_menu', array('permission_name' => $permission_name));
	
	if (!empty($menu_db)) {
		pdo_update('core_menu', array('is_display' => $status), array('permission_name' => $permission_name));
	} else {
		$menu_data = array('is_display' => $status, 'permission_name' => $permission_name);
		if (in_array($permission_name, $system_top_menu)) {
			$menu_data['is_system'] = 1;
			$menu_data['group_name'] = 'frame';
		}
		pdo_insert('core_menu',  $menu_data);
	}
	cache_build_frame_menu();
	iajax(0, '更新成功', referer());
} elseif ($do == 'delete') {
	$permission_name = $_GPC['permission_name'];
	$menu_db = pdo_get('core_menu', array('permission_name' => $permission_name));
	
	if (!empty($menu_db['is_system'])) {
		iajax(-1, '系统菜单不能删除', referer());
	}
	if (!empty($menu_db)) {
		pdo_delete('core_menu', array('id' => $menu_db['id']));
		cache_build_frame_menu();
	}
	iajax(0, '更新成功', referer());
}