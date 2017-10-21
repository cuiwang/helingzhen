<?php
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
defined('IN_IA') or exit('Access Denied');

load()->model('module');
load()->model('wxapp');

$dos = array('design_method', 'post', 'get_wxapp_modules', 'getpackage');
$do = in_array($do, $dos) ? $do : 'post';
$_W['page']['title'] = '小程序 - 新建版本';
$account_info = uni_user_account_permission();

if ($do == 'design_method') {
	$uniacid = intval($_GPC['uniacid']);
	template('wxapp/design-method');
}

if($do == 'post') {
	$uniacid = intval($_GPC['uniacid']);
	$design_method = intval($_GPC['design_method']);
	
	if (empty($design_method)) {
		itoast('请先选择要添加小程序类型', referer(), 'error');
	}
	if ($design_method == WXAPP_TEMPLATE) {
		itoast('拼命开发中。。。', referer(), 'info');
	}
	
	if (checksubmit('submit')) {
		if ($account_info['wxapp_limit'] <= 0 && empty($uniacid) && !$_W['isfounder']) {
			iajax(-1, '创建的小程序已达上限！');
		}
		if ($design_method == WXAPP_TEMPLATE && empty($_GPC['select']['modules'])) {
			iajax(2, '请选择要打包的模块应用', url('wxapp/post'));
		}
				if (empty($uniacid)) {
			if (empty($_GPC['name'])) {
				iajax(1, '请填写小程序名称', url('wxapp/post'));
			}
			$account_wxapp_data = array(
				'name' => trim($_GPC['name']),
				'description' => trim($_GPC['description']),
				'account' => trim($_GPC['account']),
				'original' => trim($_GPC['original']),
				'level' => 1,
				'key' => trim($_GPC['appid']),
				'secret' => trim($_GPC['appsecret']),
				'type' => ACCOUNT_TYPE_APP_NORMAL,
				'plugin'=>$_GPC['select']['modules']['0']['module']
			);
			$uniacid = wxapp_account_create($account_wxapp_data);
			if(is_error($uniacid)) {
				iajax(3, '添加小程序信息失败', url('wxapp/post'));
			}
		} else {
			$wxapp_info = wxapp_fetch($uniacid);
			if (empty($wxapp_info)) {
				iajax(4, '小程序不存在或是已经被删除', url('wxapp/post'));
			}
		}
		
						$wxapp_version = array(
			'uniacid' => $uniacid,
			'multiid' => '0',
			'description' => trim($_GPC['description']),
			'version' => $_GPC['version'],
			'modules' => '',
			'design_method' => $design_method,
			'quickmenu' => '',
			'createtime' => TIMESTAMP,
			'template' => $design_method == WXAPP_TEMPLATE ? intval($_GPC['select']['template']) : 0,
		);
				if ($design_method == WXAPP_TEMPLATE) {
			$multi_data = array(
				'uniacid' => $uniacid,
				'title' => $account_wxapp_data['name'],
				'styleid' => 0,
			);
			pdo_insert('site_multi', $multi_data);
			$wxapp_version['multiid'] = pdo_insertid();
		}
				if (!empty($_GPC['select']['modules'])) {
			$select_modules = array();
			foreach ($_GPC['select']['modules'] as $module) {
				$module = module_fetch($module['module']);
				if (empty($module) || $module['wxapp_support'] != MODULE_SUPPORT_WXAPP) {
					continue;
				}
				$select_modules[$module['name']] = array('name' => $module['name'], 'version' => $module['version']);
			}
			$wxapp_version['modules'] = serialize($select_modules);
		}
				if (!empty($_GPC['quickmenu']) && $design_method == WXAPP_TEMPLATE) {
			$quickmenu = array(
				'color' => $_GPC['quickmenu']['bottom']['color'],
				'selected_color' => $_GPC['quickmenu']['bottom']['selectedColor'],
				'boundary' => $_GPC['quickmenu']['bottom']['boundary'],
				'bgcolor' => $_GPC['quickmenu']['bottom']['bgcolor'],
				'menus' => array(),
			);
			if (!empty($_GPC['quickmenu']['menus'])) {
				foreach ($_GPC['quickmenu']['menus'] as $row) {
					$quickmenu['menus'][] = array(
						'name' => $row['name'],
						'icon' => $row['defaultImage'],
						'selectedicon' => $row['selectedImage'],
						'url' => $row['module']['url'],
						'module' => $row['module']['module'],
					);
				}
			}
			$wxapp_version['quickmenu'] = serialize($quickmenu);
		}
		pdo_insert('wxapp_versions', $wxapp_version);
		iajax(0, '小程序创建成功！跳转后请自行下载打包程序', url('wxapp/display/switch', array('uniacid' => $uniacid)));
	}
	if (!empty($uniacid)) {
		$wxapp_info = wxapp_fetch($uniacid);
	}
	template('wxapp/post');
}

if($do == 'get_wxapp_modules') {
	$wxapp_modules = wxapp_support_wxapp_modules();
	iajax(0, $wxapp_modules, '');
}