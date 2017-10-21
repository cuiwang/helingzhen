<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.zheyitianshi.com/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
load()->func('communication');

function cloud_client_define() {
	return array(
		'/framework/function/communication.func.php',
		'/framework/model/cloud.mod.php',
		'/web/source/cloud/upgrade.ctrl.php',
		'/web/source/cloud/process.ctrl.php',
		'/web/source/cloud/dock.ctrl.php',
		'/web/themes/default/cloud/upgrade.html',
		'/web/themes/default/cloud/process.html'
	);
}

function cloud_prepare() {
	global $_W;
	setting_load('site');
	if(empty($_W['setting']['site']['key']) || empty($_W['setting']['site']['token'])) {
		return error('-1', "您的程序需要在微擎云服务平台注册你的站点资料, 来接入云平台服务后才能使用相应功能.");
	}
	return true;
}

function cloud_m_prepare($name) {
	$pars['method'] = 'module.check';
	$pars['module'] = $name;
	$dat = ihttp_post('http://v2.addons.we7.cc/gateway.php', $pars);
	if (is_error($dat)) {
		return $dat;
	}
	if ($dat['content'] == 'install-module-protect') {
		return error('-1', '此模块已设置版权保护，您只能通过云平台来安装。');
	}
	return true;
}

function _cloud_build_params() {
	global $_W;
	$pars = array();
	$pars['host'] = $_SERVER['HTTP_HOST'];
	$pars['family'] = IMS_FAMILY;
	$pars['version'] = IMS_VERSION;
	$pars['release'] = IMS_RELEASE_DATE;
	$pars['key'] = $_W['setting']['site']['key'];
	$pars['password'] = md5($_W['setting']['site']['key'] . $_W['setting']['site']['token']);
	$clients = cloud_client_define();
	$string = '';
	foreach($clients as $cli) {
		$string .= md5_file(IA_ROOT . $cli);
	}
	$pars['client'] = md5($string);
	return $pars;
}

function cloud_m_build($modulename) {
	$sql = 'SELECT * FROM ' . tablename('modules') . ' WHERE `name`=:name';
	$module = pdo_fetch($sql, array(':name' => $modulename));
	$pars = _cloud_build_params();
	$pars['method'] = 'module.build';
	$pars['module'] = $modulename;
	if(!empty($module)) {
		$pars['module_version'] = $module['version'];
	}
	$dat = ihttp_post('http://v2.addons.we7.cc/gateway.php', $pars);
	$file = IA_ROOT . '/data/module.build';
	$ret = _cloud_shipping_parse($dat, $file);
	if(!is_error($ret)) {
		$dir = IA_ROOT . '/addons/' . $modulename;
		$files = array();
		if(!empty($ret['files'])) {
			foreach($ret['files'] as $file) {
				$entry = $dir . $file['path'];
				if(!is_file($entry) || md5_file($entry) != $file['checksum']) {
					$files[] = '/'. $modulename . $file['path'];
				}
			}
		}
		$ret['files'] = $files;
		$schemas = array();
		if(!empty($ret['schemas'])) {
			load()->func('db');
			foreach($ret['schemas'] as $remote) {
				$name = substr($remote['tablename'], 4);
				$local = db_table_schema(pdo(), $name);
				unset($remote['increment']);
				unset($local['increment']);
				if(empty($local)) {
					$schemas[] = $remote;
				} else {
					$diffs = db_table_fix_sql($local, $remote);
					if(!empty($diffs)) {
						$schemas[] = $remote;
					}
				}
			}
		}
		$ret['upgrade'] = true;
		$ret['type'] = 'module';
		$ret['schemas'] = $schemas;
				if(empty($module)) {
			$ret['install'] = 1;
		}
	}
	return $ret;
}

function cloud_m_query() {
	$pars = _cloud_build_params();
	$pars['method'] = 'module.query';
	$dat = ihttp_post('http://v2.addons.we7.cc/gateway.php', $pars);
	$file = IA_ROOT . '/data/module.query';
	$ret = _cloud_shipping_parse($dat, $file);
	return $ret;
}

function cloud_m_info($name) {
	$pars = _cloud_build_params();
	$pars['method'] = 'module.info';
	$pars['module'] = $name;
	$dat = ihttp_post('http://v2.addons.we7.cc/gateway.php', $pars);
	$file = IA_ROOT . '/data/module.info';
	$ret = _cloud_shipping_parse($dat, $file);
	return $ret;
}

function cloud_m_upgradeinfo($name) {
	$module = pdo_fetch("SELECT name, version FROM ".tablename('modules')." WHERE name = '{$name}'");
	$pars = _cloud_build_params();
	$pars['method'] = 'module.info';
	$pars['module'] = $name;
	$pars['curversion'] = $module['version'];
	$pars['isupgrade'] = 1;
	$dat = ihttp_post('http://v2.addons.we7.cc/gateway.php', $pars);
	$file = IA_ROOT . '/data/module.info';
	$ret = _cloud_shipping_parse($dat, $file);
	return $ret;
}

function cloud_t_prepare($name) {
	$pars['method'] = 'theme.check';
	$pars['theme'] = $name;
	$dat = ihttp_post('http://v2.addons.we7.cc/gateway.php', $pars);
	if (is_error($dat)) {
		return $dat;
	}
	if ($dat['content'] == 'install-theme-protect') {
		return error('-1', '此模板已设置版权保护，您只能通过云平台来安装。');
	}
	return true;
}

function cloud_t_query() {
	$pars = _cloud_build_params();
	$pars['method'] = 'theme.query';
	$dat = ihttp_post('http://v2.addons.we7.cc/gateway.php', $pars);
	$file = IA_ROOT . '/data/theme.query';
	$ret = _cloud_shipping_parse($dat, $file);
	return $ret;
}

function cloud_t_info($name) {
	$pars = _cloud_build_params();
	$pars['method'] = 'theme.info';
	$pars['theme'] = $name;
	$dat = ihttp_post('http://v2.addons.we7.cc/gateway.php', $pars);
	$file = IA_ROOT . '/data/theme.info';
	$ret = _cloud_shipping_parse($dat, $file);
	return $ret;
}

function cloud_t_build($name) {
	$sql = 'SELECT * FROM ' . tablename('site_templates') . ' WHERE `name`=:name';
	$theme = pdo_fetch($sql, array(':name' => $name));
	
	$pars = _cloud_build_params();
	$pars['method'] = 'theme.build';
	$pars['theme'] = $name;
	if(!empty($theme)) {
		$pars['themeversion'] = $theme['version'];
	}
	$dat = ihttp_post('http://v2.addons.we7.cc/gateway.php', $pars);
	$file = IA_ROOT . '/data/theme.build';
	$ret = _cloud_shipping_parse($dat, $file);
	if(!is_error($ret)) {
		$dir = IA_ROOT . '/app/themes/' . $name;
		$files = array();
		if(!empty($ret['files'])) {
			foreach($ret['files'] as $file) {
				$entry = $dir . $file['path'];
				if(!is_file($entry) || md5_file($entry) != $file['checksum']) {
					$files[] = '/'. $name . $file['path'];
				}
			}
		}
		$ret['files'] = $files;
		$ret['upgrade'] = true;
		$ret['type'] = 'theme';
				if(empty($theme)) {
			$ret['install'] = 1;
		}
	}
	return $ret;
}
function cloud_sms_send($mobile, $content) {
	global $_W;
	$row = pdo_fetch("SELECT `notify` FROM ".tablename('uni_settings') . " WHERE uniacid = :uniacid", array(':uniacid' => $_W['uniacid']));
	$row['notify'] = @iunserializer($row['notify']);
	if(!empty($row['notify']) && !empty($row['notify']['sms'])) {
		$config = $row['notify']['sms'];
		$balance = intval($config['balance']);
		if($balance <= 0) {
			return error(-1, '发送短信失败, 请联系系统管理人员. 错误详情: 短信余额不足');
		}
		$sign = $config['signature'];
		if(empty($sign) && IMS_FAMILY == 'x') {
			$sign = $_W['setting']['copyright']['sitename'];
		}
		if(empty($sign)) {
			$sign = '微擎';
		}
		
		$pars = _cloud_build_params();
		$pars['method'] = 'sms.send';
		$pars['mobile'] = $mobile;
		$pars['content'] = $content . "【{$sign}】";
		$dat = ihttp_post('http://v2.addons.we7.cc/gateway.php', $pars);
		$file = IA_ROOT . '/data/sms.send';
		$ret = _cloud_shipping_parse($dat, $file);
		if (is_error($ret)) {
			return error($ret['errno'], $ret['message']);
		}
		if ($ret == 'success') {
			return true;
		} else {
			return error(-1, $ret);
		}
	}
	return error(-1, '发送短信失败, 请联系系统管理人员. 错误详情: 没有设置短信配额或参数');
}

function cloud_build() {
	$pars = _cloud_build_params();
	$pars['method'] = 'application.build';
	$dat = ihttp_post('http://v2.addons.we7.cc/gateway.php', $pars);
	$file = IA_ROOT . '/data/application.build';
	$ret = _cloud_shipping_parse($dat, $file);
	if(!is_error($ret)) {
		if($ret['state'] == 'warning') {
			$ret['files'] = cloud_client_define();
			unset($ret['schemas']);
			unset($ret['scripts']);
		} else {
			$files = array();
			if(!empty($ret['files'])) {
				foreach($ret['files'] as $file) {
					$entry = IA_ROOT . $file['path'];
					if(!is_file($entry) || md5_file($entry) != $file['checksum']) {
						$files[] = $file['path'];
					}
				}
			}
			$ret['files'] = $files;

			$schemas = array();
			if(!empty($ret['schemas'])) {
				load()->func('db');
				foreach($ret['schemas'] as $remote) {
					$name = substr($remote['tablename'], 4);
					$local = db_table_schema(pdo(), $name);
					unset($remote['increment']);
					unset($local['increment']);
					if(empty($local)) {
						$schemas[] = $remote;
					} else {
						$sqls = db_table_fix_sql($local, $remote);
						if(!empty($sqls)) {
							$schemas[] = $remote;
						}
					}
				}
			}
			$ret['schemas'] = $schemas;
		}

		if($ret['family'] == 'x' && IMS_FAMILY == 'v') {
			load()->model('setting');
			setting_upgrade_version('x', IMS_VERSION, IMS_RELEASE_DATE);
			message('您已经购买了商业授权版本, 系统将转换为商业版, 并重新运行自动更新程序.', 'refresh');
		}
		$ret['upgrade'] = false;
		if(!empty($ret['files']) || !empty($ret['schemas']) || !empty($ret['scripts'])) {
			$ret['upgrade'] = true;
		}
		$upgrade = array();
		$upgrade['upgrade'] = $ret['upgrade'];
		$upgrade['lastupdate'] = TIMESTAMP;
		cache_write('upgrade', $upgrade);
	}
	return $ret;
}

function cloud_schema() {
	$pars = _cloud_build_params();
	$pars['method'] = 'application.schema';
	$dat = ihttp_post('http://v2.addons.we7.cc/gateway.php', $pars);
	$file = IA_ROOT . '/data/application.schema';
	$ret = _cloud_shipping_parse($dat, $file);
	if(!is_error($ret)) {
		$schemas = array();
		if(!empty($ret['schemas'])) {
			load()->func('db');
			foreach($ret['schemas'] as $remote) {
				$name = substr($remote['tablename'], 4);
				$local = db_table_schema(pdo(), $name);
				unset($remote['increment']);
				unset($local['increment']);
				if(empty($local)) {
					$schemas[] = $remote;
				} else {
					$diffs = db_schema_compare($local, $remote);
					if(!empty($diffs)) {
						$schemas[] = $remote;
					}
				}
			}
		}
		$ret['schemas'] = $schemas;
	}
	return $ret;
}

function cloud_download($path, $type = '') {
	$pars = _cloud_build_params();
	$pars['method'] = 'application.shipping';
	$pars['path'] = $path;
	$pars['type'] = $type;
	$pars['gz'] = function_exists('gzcompress') && function_exists('gzuncompress') ? 'true' : 'false';
	$headers = array('content-type' => 'application/x-www-form-urlencoded');
	$dat = ihttp_request('http://v2.addons.we7.cc/gateway.php', $pars, $headers, 300);
	if(is_error($dat)) {
		return error(-1, '网络存在错误， 请稍后重试。' . $dat['message']);
	}
	if($dat['content'] == 'success') {
		return true;
	}
	$ret = @json_decode($dat['content'], true);
	if(is_error($ret)) {
		return $ret;
	} else {
		return error(-1, '不能下载文件， 请稍后重试。');
	}
}

function _cloud_shipping_parse($dat, $file) {
	if(is_error($dat)){
		return error(-1, '网络传输错误, 请检查您的cURL是否可用, 或者服务器网络是否正常. ' . $dat['message']);
	}
	$tmp = unserialize($dat['content']);
	if (is_array($tmp) && is_error($tmp)) {
		if($tmp['errno'] == '-2') {
			$data = file_get_contents(IA_ROOT . '/framework/version.inc.php');
			file_put_contents(IA_ROOT . '/framework/version.inc.php', str_replace("'x'", "'v'", $data));
		}
		return $tmp;
	}
	if ($dat['content'] == 'patching') {
		return error(-1, '补丁程序正在更新中，请稍后再试！');
	}
	if(strlen($dat['content']) != 32) {
		return error(-1, '云服务平台向您的服务器传输数据过程中出现错误, 这个错误可能是由于您的通信密钥和云服务不一致, 请尝试诊断云服务参数(重置站点ID和通信密钥). 传输原始数据:' . $dat['meta']);
	}
	$data = @file_get_contents($file);
	if(empty($data)) {
		return error(-1, '没有接收到服务器的传输的数据.');
	}
 //@unlink($file);
	$ret = @iunserializer($data);
	if(empty($data) || empty($ret) || $dat['content'] != $ret['secret']) {
		return error(-1, '云服务平台向您的服务器传输的数据校验失败, 可能是因为您的网络不稳定, 或网络不安全, 请稍后重试.');
	}
	$ret = iunserializer($ret['data']);
	if (is_array($ret) && is_error($ret)) {
		if($ret['errno'] == '-2') {
			$data = file_get_contents(IA_ROOT . '/framework/version.inc.php');
			file_put_contents(IA_ROOT . '/framework/version.inc.php', str_replace("'x'", "'v'", $data));
		}
	}
	if(!is_error($ret) && is_array($ret) && !empty($ret)) {
		if($ret['state'] == 'fatal') {
			return error($ret['errorno'], '发生错误: ' . $ret['message']);
		}
		return $ret;
	} else {
		return error(-1, "发生错误: {$ret['message']}");
	}
}
