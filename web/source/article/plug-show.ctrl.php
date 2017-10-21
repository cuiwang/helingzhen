<?php
/**
 * [WeEngine System] Copyright (c) 2015 012WZ.COM
 
 */
defined('IN_IA') or exit('Access Denied');
$dos = array( 'detail', 'list');
$do = in_array($do, $dos) ? $do : 'list';
load()->model('article');
$links = article_link_home();
if($do == 'detail') {
	$id = intval($_GPC['id']);
	$case = article_case_info($id);
	$catecases = article_catecase('case');
	if(is_error($case)) {
		message('案例不存在或已删除', referer(), 'error');
	}
	$_W['page']['title'] = $case['title'] . '-案例列表';
}

if($do == 'list') {
$_W['page']['title'] = '功能模块展示';
	load()->model('module');
	load()->model('extension');
	load()->model('cloud');
	load()->model('cache');
	load()->func('file');
	$modtypes = module_types();
	$modules = pdo_fetchall("SELECT * FROM " . tablename('modules') .'WHERE issystem = 0 ORDER BY `price` DESC, `mid` ASC', array(), 'mid');
	if (!empty($modules)) {
		foreach ($modules as $mid => $module) {
			$manifest = ext_module_manifest($module['name']);
			$modules[$mid]['official'] = empty($module['issystem']) && (strexists($module['author'], 'WeiZan Team') || strexists($module['author'], '微信团队'));
			$modules[$mid]['description'] = strip_tags($module['description']);
			if(is_array($manifest) && ver_compare($module['version'], $manifest['application']['version']) == '-1') {
				$modules[$mid]['upgrade'] = true;
			}
			if (empty($sysmodules)){
				$sysmodules = array();
			}
			if(in_array($module['name'], $sysmodules)) {
				$modules[$mid]['imgsrc'] = '../framework/builtin/' . $module['name'] . '/icon-custom.jpg';
				if(!file_exists($modules[$mid]['imgsrc'])) {
					$modules[$mid]['imgsrc'] = '../framework/builtin/' . $module['name'] . '/icon.jpg';
				}
			} else {
				$modules[$mid]['imgsrc'] = '../addons/' . $module['name'] . '/icon-custom.jpg';
				if(!file_exists($modules[$mid]['imgsrc'])) {
					$modules[$mid]['imgsrc'] = '../addons/' . $module['name'] . '/icon.jpg';
				}
			}
		}
	}
	$sysmodules = implode("', '", $sysmodules);
}

template('article/plug-show');
