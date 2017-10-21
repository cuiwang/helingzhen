<?php
/**
 * [WECHAT 2017]
 
 */
defined('IN_IA') or exit('Access Denied');
$dos = array('installed', 'prepared', 'install', 'refresh', 'uninstall', 'web', 'batch-install', 'designer', 'check', 'upgrade');
$do = in_array($do, $dos) ? $do : 'installed';
load()->model('extension');
load()->model('cloud');
load()->model('cache');
load()->func('file');
if($do == 'installed') {
	$_W['page']['title'] = '已安装的微站风格 - 风格主题 - 扩展';
	$templateids = array();
	$where = (empty($_GPC['type']) || $_GPC['type'] == 'all') ? '' : " WHERE `type` = '{$_GPC['type']}'";
	$templates = pdo_fetchall("SELECT * FROM ".tablename('site_templates') . $where);
	foreach($templates as $tpl) {
		$templateids[] = $tpl['name'];
	}
	
	$temtypes = ext_template_type();
	template('system/theme1');
}

if($do == 'prepared') {
	$_W['page']['title'] = '安装微站风格 - 风格主题 - 扩展';
	$templateids = array();
	$templates = pdo_fetchall("SELECT * FROM ".tablename('site_templates'));
	$ip=file_get_contents(ADDONS_URL . '/getip.php');
	foreach($templates as $tpl) {
		$templateids[] = $tpl['name'];
	}
	$uninstallTemplates = array();
	$path = IA_ROOT . '/app/themes/';
	if (is_dir($path)) {
		if ($handle = opendir($path)) {
			while (false !== ($modulepath = readdir($handle))) {
				$manifest = ext_template_manifest($modulepath, false);
				if(!empty($manifest) && !in_array($manifest['name'], $templateids)) {
					$uninstallTemplates[$manifest['name']] = $manifest;
					$uninstallTemplates_title[$manifest['name']] = $manifest['title'];
					$templateids[] = $manifest['name'];
				}
			}
		}
	}
	$prepare_templates = json_encode(array_keys($uninstallTemplates));
	$prepare_templates_title = json_encode($uninstallTemplates_title);
	template('system/theme1');
}

if($do == 'batch-install') {
	if($_W['ispost']) {
		$id = $_GPC['templateid'];
		$m = ext_template_manifest($id);
		if (empty($m)) {
			exit('error');
		}
		if (pdo_fetchcolumn("SELECT id FROM ".tablename('site_templates')." WHERE name = '{$m['name']}'")) {
			exit('error');
		}
				unset($m['settings']);
		
		if (pdo_insert('site_templates', $m)) {
			exit('success');
		} else {
			exit('error');
		}
	} else {
		exit('error');
	}
}
$pars = array();
$pars['host'] = $_SERVER['HTTP_HOST'];
$pars['family'] = IMS_FAMILY;
$pars['version'] = IMS_VERSION;
$pars['release'] = IMS_RELEASE_DATE;
$pars['key'] = $_W['setting']['site']['key'];
$pars['password'] = md5($_W['setting']['site']['key'] . $_W['setting']['site']['token']);
if($do == 'install') {
	if(empty($_W['isfounder'])) {
		message('您没有安装模版的权限', '', 'error');
	}
	$id = $_GPC['templateid'];
	if (pdo_fetchcolumn("SELECT id FROM ".tablename('site_templates')." WHERE name = :name", array(':name' => $id))) {
		message('模板已经安装或是唯一标识已存在！', '', 'error');
	}
	$manifest = ext_template_manifest($id, false);

	if (!empty($manifest)) {
		$r = cloud_t_prepare($id);
		if(is_error($r)) {
			message($r['message'], url('system/theme1/prepared'), 'error');
		}
	}

	if (empty($manifest)) {
		$pars['method'] = 'theme1.build';
	    $pars['theme'] = $id;
		$pars['install'] = 1;
	    $dat = cloud_request(ADDONS_URL.'/gateway.php', $pars);
	    $dat = unserialize(base64_decode($dat['content']));
        if($dat['state']){
	        message($dat['content']);
        }
	    $dir = IA_ROOT . '/app/themes/' . $id;
		if(!is_dir($dir)){
			mkdir($dir);
		}
		$temp=IA_ROOT . '/data/'.random(8).'.zip';
		$files=$dat['files'];
		if(empty($files)){
			message('没有收到服务器传输的数据！');
		}
		file_put_contents($temp,$files);
		$zip = new ZipArchive();
		$zip->open($temp);
		$zip->extractTo($dir);
		$zip->close();
		@unlink($temp);
		$manifest = ext_template_manifest_parse($dat['manifest']);
	}
	if (empty($manifest)) {
		message('模板安装配置文件不存在或是格式不正确！', '', 'error');
	}
	if ($manifest['name'] != $id) {
		message('安装模板与文件标识不符，请重新安装', '', 'error');
	}
	if (pdo_fetchcolumn("SELECT id FROM ".tablename('site_templates')." WHERE name = '{$manifest['name']}'")) {
		message('模板已经安装或是唯一标识已存在！', '', 'error');
	}
	if (pdo_insert('site_templates', $manifest)) {
		$tid = pdo_insertid();
	} else {
		message('模板安装失败, 请联系模板开发者！');
	}
	message('模板安装成功, 请按照【公众号服务套餐】【用户组】来分配权限！', url('system/theme'), 'success');
}

if($do == 'uninstall') {
	$name = pdo_fetchcolumn('SELECT name FROM ' . tablename('site_templates') . ' WHERE id = :id', array(':id' => intval($_GPC['id'])));
	if($name == 'default') {
		message('默认模板不能卸载', '', 'error');
	}
	if (pdo_delete('site_templates', array('id' => intval($_GPC['id'])))) {
		
		pdo_delete('site_styles',array('templateid' => intval($_GPC['id'])));
				pdo_delete('site_styles_vars',array('templateid' => intval($_GPC['id'])));
		message('模板移除成功, 你可以重新安装, 或者直接移除文件来安全删除！', referer(), 'success');
	} else {
		message('模板移除失败, 请联系模板开发者！');
	}
}

if($do == 'upgrade') {
	$id = $_GPC['templateid'];
	$theme = pdo_fetch("SELECT id, name, title FROM " . tablename('site_templates') . " WHERE name = :name", array(':name' => $id));
	if(empty($theme)){
		message('模版不存在或已经被卸载！');
	}
	$pars['method'] = 'theme1.build';
	$pars['theme'] = $id;
	$dat1 = cloud_request(ADDONS_URL.'/gateway.php', $pars);
	$dat = unserialize(base64_decode($dat1['content']));
    if(empty($dat) || empty($dat1['content'])){
		message('没有收到服务器传输的数据！');
	}
	if($dat['state']){
	    message($dat['content']);
    }
	$dir = IA_ROOT . '/app/themes/' . $id;
	$upfiles=$files = array();
	if(!empty($dat['files'])) {
		
		foreach($dat['files'] as $file) {
			$entry = $dir . $file['path'];
			if(!is_file($entry) || md5_file($entry) != $file['checksum']) {
				$files[] =$file['path'];
				$upfiles[]=$file;
			}			
		}
	}
	if(!empty($files)){
		$pars['method'] = 'theme1.get';
	    $pars['files'] = base64_encode(json_encode($files));
	    $data = cloud_request(ADDONS_URL.'/gateway.php', $pars);
	    if(empty($data)){
		    message('网络传输错误！');
	    }
	    $data=unserialize(base64_decode($data['content']));
	    if($data['state']){
		    message($data['content']);
	    }
	    elseif(!empty($data['content'])){
		    $files=$data['content'];
		    $temp=IA_ROOT . '/data/temp.'.random(5);
		    file_put_contents($temp,$files);
		    $zip = new ZipArchive();
		    $zip->open($temp);
		    $zip->extractTo($dir);
		    $zip->close();
		    @unlink($temp);
	    }
	}
	foreach($upfiles as $file) {
		$entry = $dir . $file['path'];
		if(!is_file($entry) || md5_file($entry) != $file['checksum']) {
			message("更新失败，请设置好模块根目录（".$dir."）的权限再更新！");
		}
	}
	$packet = $dat;
	$manifest = ext_template_manifest_parse($packet['manifest']);
	if (empty($manifest)) {
		message('模板安装配置文件不存在或是格式不正确！', '', 'error');
	}
	pdo_update('site_templates', array('version' => $packet['version']), array('id' => $theme['id']));
	message('模板更新成功！', url('system/theme1'), 'success');
	
	
}

if($do == 'web') {
	$_W['page']['title'] = '管理后台风格 - 风格主题 - 扩展';
	load()->model('setting');
	if(checksubmit('submit')) {
		$data = array(
			'template' => $_GPC['template'],
		);
		setting_save($data, 'basic');
		message('更新设置成功！', 'refresh');
	}
	$path = IA_ROOT . '/web/themes/';
	if(is_dir($path)) {
		if ($handle = opendir($path)) {
			while (false !== ($templatepath = readdir($handle))) {
				if ($templatepath != '.' && $templatepath != '..') {
					if(is_dir($path.$templatepath)){
						$template[] = $templatepath;
					}
				}
			}
		}
	}
	template('system/web');
}


if ($do == 'designer') {
	if (empty($_W['isfounder'])) {
		message('您没有设计新模板的权限', '', 'error');
	}
	$_W['page']['title'] = '设计微站风格 - 风格主题 - 扩展';
	
	$available['download'] = class_exists('ZipArchive');
	
	$available['create'] = is_writable(IA_ROOT . '/app/themes');
	$versions = array('0.52', '0.6');
	
	$temtypes = ext_template_type();
	
	if (checksubmit('submit') && $available[$_GPC['method']]) {
		$t['template']['name'] = trim($_GPC['template']['name']);
		if(empty($t['template']['name']) || preg_match('/\*\/|\/\*|eval|\$\_/i', $t['template']['name'])) {
			message('请输入有效的模板名称. ');
		}
		$t['template']['identifie'] = trim($_GPC['template']['identifie']);
		if(empty($t['template']['identifie']) || !preg_match('/^[a-z][a-z\d_]+$/i', $t['template']['identifie'])) {
			message('必须输入模板标识符(仅支持字母和数字, 且只能以字母开头). ');
		}
		$t['template']['type'] = array_key_exists($_GPC['template']['type'], $temtypes) ? $_GPC['template']['type'] : 'other';
		$t['template']['description'] = trim($_GPC['template']['description']);
		if(empty($t['template']['description']) || preg_match('/\*\/|\/\*|eval|\$\_/i', $t['template']['description'])) {
			message('请输入有效的模板介绍. ');
		}
		$t['template']['author'] = trim($_GPC['template']['author']);
		if(empty($t['template']['author']) || preg_match('/\*\/|\/\*|eval|\$\_/i', $t['template']['author'])) {
			message('请输入有效的模板作者');
		}
		$t['template']['url'] = trim($_GPC['template']['url']);
		if(empty($t['template']['url']) || preg_match('/\*\/|\/\*|eval|\$\_/i', $t['template']['url'])) {
			message('请输入有效的模板发布页');
		}
		$t['template']['sections'] = trim($_GPC['template']['sections']);
		if (is_array($_GPC['versions'])) {
			foreach ($_GPC['versions'] as $value) {
				if (in_array($value, $versions)) {
					$t['versions'][] = $value;
				}
			}
		} else {
			message('请设置版本的兼容性');
		}

		$t['settings'] = array();
		if(!empty($_GPC['settings']['variables'])) {
			foreach($_GPC['settings']['variables'] as $key => $value) {
				$temp = array();
				if(!empty($_GPC['settings']['variables'][$key]) && preg_match('/^[a-z\d]+$/i', $_GPC['settings']['variables'][$key])) {
					if (!empty($_GPC['settings']['description'][$key])) {
						$temp['variable'] = $_GPC['settings']['variables'][$key];
						$temp['value'] = $_GPC['settings']['values'][$key];
						$temp['desc'] = $_GPC['settings']['description'][$key];
						$t['settings'][] = $temp;
					}
				}
			}
		}
		if($_FILES['preview'] && $_FILES['preview']['error'] == '0' && !empty($_FILES['preview']['tmp_name'])) {
			$t['preview'] = $_FILES['preview']['tmp_name'];
		}
		
		$manifest = manifest($t);
		load()->func('file');

		
		if ($_GPC['method'] == 'create') {
			$tpldir = IA_ROOT . '/app/themes/' . strtolower($t['template']['identifie']);
			if (is_dir($tpldir)) {
				message('模板目录' . $tpldir . '已存在，请更换模板标识还删除已存在模板');
			}
			mkdirs($tpldir);
			file_put_contents("{$tpldir}/manifest.xml", $manifest);
			if (!empty($t['preview'])) {
				file_move($t['preview'], "{$tpldir}/preview.jpg");
			}
			message('模板生成成功，请访问' . $tpldir . '目录进行查看', referer(), 'success');
			exit();
		}

		
		if ($_GPC['method'] == 'download') {
			$zipfile = IA_ROOT . '/data/temp.zip';
			$zip = new ZipArchive();
			$zip->open($zipfile, ZipArchive::CREATE);
			$zip->addFromString('manifest.xml', $manifest);
			if (!empty($t['preview'])) {
				$zip->addFile($t['preview'], "preview.jpg");
				
			}
			$zip->close();
			header('content-type: application/zip');
			header('content-disposition: attachment; filename="' . $t['template']['identifie'] . '.zip"');
			readfile($zipfile);
			@unlink($t['preview']);
			@unlink($zipfile);
		}
	}

	template('system/desitemp');
}

if($do == 'check') {
	if($_W['isajax']) {
		$foo = $_GPC['foo'];
		
		$r = cloud_prepare();
		if(is_error($r)) {
			exit('cloud service is unavailable');
		}

		if ($foo == 'upgrade') {
			$mods = array();

			$ret = cloud_t_query();

			if (!is_error($ret)) {
				foreach($ret as $k => $v) {
					$mods[$k] = array(
						'from' => 'cloud',
						'version' => $v['version'],
						'branches' => $v['branches'],
						'site_branch' => $v['branches'][$v['branch']],
					);
				}

				$mods['pirate_apps'] = array_values($v['pirate_apps']);
			}

			if(!empty($mods)) {
				exit(json_encode($mods));
			}
		} else {
			$templateids = array();
			$templates = pdo_fetchall("SELECT `name` FROM " . tablename('site_templates') . ' ORDER BY `id` ASC');
			if(!empty($templates)) {
				foreach($templates as $m) {
					$templateids[] = $m['name'];
				}
			}
			$ret = cloud_t_query();
			if(!is_error($ret)) {
				$cloudUninstallThemes = array();
				foreach($ret as $k => $v) {
					if(!in_array(strtolower($k), $templateids)) {
						$v['name'] = $k;
						$cloudUninstallThemes[] = $v;
						$templateids[] = $k;
					}
				}
				exit(json_encode($cloudUninstallThemes));
			}
		}
	}
	exit();
}


function manifest($t) {
	$versions = implode(',', $t['versions']);
	$item = '';
	if(!empty($t['settings'])) {
		foreach($t['settings'] as $key => $value) {
			$item .= "\r\n\t\t<item variable=\"{$value['variable']}\" content=\"{$value['value']}\" description=\"{$value['desc']}\"/>";
		}
	}
	$tpl = <<<TPL
<?xml version="1.0" encoding="utf-8"?>
<manifest versionCode="{$versions}">
	<identifie><![CDATA[{$t['template']['identifie']}]]></identifie>
	<title><![CDATA[{$t['template']['name']}]]></title>
	<type><![CDATA[{$t['template']['type']}]]></type>
	<description><![CDATA[{$t['template']['description']}]]></description>
	<author><![CDATA[{$t['template']['author']}]]></author>
	<url><![CDATA[{$t['template']['url']}]]></url>
	<sections><![CDATA[{$t['template']['sections']}]]></sections>
	<settings>{$item}
	</settings>
</manifest>
TPL;
	return ltrim($tpl);
}