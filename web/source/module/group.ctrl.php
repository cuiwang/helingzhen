<?php
/**
 */
defined('IN_IA') or exit('Access Denied');
load()->model('module');
load()->model('user');
load()->model('module');

$dos = array('display', 'delete', 'post', 'save');
$do = !empty($_GPC['do']) ? $_GPC['do'] : 'display';
if (!in_array($_W['role'], array(ACCOUNT_MANAGE_NAME_OWNER, ACCOUNT_MANAGE_NAME_MANAGER, ACCOUNT_MANAGE_NAME_FOUNDER, ACCOUNT_MANAGE_NAME_VICE_FOUNDER))){
	itoast('无权限操作！', referer(), 'error');
}
if ($do != 'display' && !in_array($_W['role'], array(ACCOUNT_MANAGE_NAME_FOUNDER, ACCOUNT_MANAGE_NAME_VICE_FOUNDER))) {
	itoast('您只有查看权限！', url('module/group'), 'error');
}

if ($do == 'save') {
	$modules = empty($_GPC['modules']) ? array() : (array)$_GPC['modules'];
	$wxapp = empty($_GPC['wxapp']) ? array() : (array)array_keys($_GPC['wxapp']);

	$package_info = array(
		'id' => intval($_GPC['id']),
		'name' => $_GPC['name'],
		'modules' => array_merge($modules, $wxapp),
		'templates' => $_GPC['templates'],
	);

	$package_info = module_save_group_package($package_info);

	if (is_error($package_info)) {
		iajax(1, $package_info['message'], '');
	}
	iajax(0, '', url('module/group'));
}

if ($do == 'display') {
	$_W['page']['title'] = '应用套餐列表';
	$param = array('uniacid' => 0);
	if (!empty($_GPC['name'])) {
		$param['name like'] = "%". trim($_GPC['name']) ."%";
	}
	$modules = user_modules($_W['uid']);
	
	$modules_group_list = uni_groups();
	if (!empty($modules_group_list)) {
		foreach ($modules_group_list as $group_key => &$group) {
			if (empty($group['modules'])) {
				$group['modules'] = array();
			}
			if (!empty($group['wxapp'])) {
				$wxapp = $group['wxapp'];
				if (is_array($wxapp) && !empty($wxapp)) {
					if (!empty($group['wxapp'])) {
						foreach ($group['wxapp'] as &$wxapp) {
							if (file_exists(IA_ROOT.'/addons/'.$wxapp['name'].'/icon-custom.jpg')) {
								$wxapp['logo'] = tomedia(IA_ROOT.'/addons/'.$wxapp['name'].'/icon-custom.jpg');
							} else {
								$wxapp['logo'] = tomedia(IA_ROOT.'/addons/'.$wxapp['name'].'/icon.jpg');
							}
						}
						unset($wxapp);
					}
				} else {
					$group['wxapp'] = array();
				}
			}
			$group['templates'] = !empty($group['templates']) ? $group['templates'] : array();
		}
		unset($group);
	}
}

if ($do == 'delete') {
	$id = intval($_GPC['id']);
	if (!empty($id)) {
		pdo_delete('uni_group', array('id' => $id));
		cache_build_uni_group();
		cache_build_account_modules();
	}
	itoast('删除成功！', referer(), 'success');
}

if ($do == 'post') {
	$group_id = intval($_GPC['id']);
	$_W['page']['title'] = $group_id ? '编辑应用套餐' : '添加应用套餐';

	$group_have_module_app = array();
	$group_have_module_wxapp = array();
	$group_have_template = array();

	if (!empty($group_id)) {
		$uni_groups = uni_groups();
		$module_group = $uni_groups[$group_id];
		$group_have_module_app = empty($module_group['modules']) ? array() : $module_group['modules'];
		$group_have_module_wxapp = empty($module_group['wxapp']) ? array() : $module_group['wxapp'];
		$group_have_template = empty($module_group['templates']) ? array() : $module_group['templates'];
	}
	$module_list = user_uniacid_modules($_W['uid']);
	$group_not_have_module_app = array();
	$group_not_have_module_wxapp = array();
	if (!empty($module_list)) {
		foreach ($module_list as $name => $module_info) {
			$module_info = module_fetch($name);
			if ($module_info['app_support'] == 2 && !in_array($name, array_keys($group_have_module_app))) {
				if (!empty($module_info['main_module'])) {
					if (in_array($module_info['main_module'], array_keys($group_have_module_app))) {
						$group_not_have_module_app[$name] = $module_info;
					}
				} elseif (is_array($module_info['plugin_list']) && !empty($module_info['plugin_list'])) {
					$group_not_have_module_app[$name] = $module_info;
					foreach ($module_info['plugin_list'] as $plugin) {
						if (!in_array($plugin, array_keys($group_have_module_app))) {
							$plugin = module_fetch($plugin);
							if (!empty($plugin)) {
								$group_not_have_module_app[$plugin['name']] = $plugin;
							}
						}
					}
				} else {
					$group_not_have_module_app[$name] = $module_info;
				}
			}
			if ($module_info['wxapp_support'] == 2 && !in_array($name, array_keys($group_have_module_wxapp))) {
				$group_not_have_module_wxapp[$name] = $module_info;
			}
		}
	}

	$template_list = pdo_getall('site_templates', array(), array(), 'name');
	$group_not_have_template = array();	if (!empty($template_list)) {
		foreach ($template_list as $template) {
			if (!in_array($template['name'], array_keys($group_have_template))) {
				$group_not_have_template[$template['name']] =  $template;
			}
		}
	}
}

template('module/group');