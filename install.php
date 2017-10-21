<?php
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
define('IN_IA', true);
error_reporting(E_ALL ^ E_NOTICE);
@set_time_limit(0);
@set_magic_quotes_runtime(0);
ob_start();
define('IA_ROOT', str_replace("\\",'/', dirname(__FILE__)));
if($_GET['res']) {
	$res = $_GET['res'];
	$reses = tpl_resources();
	if(array_key_exists($res, $reses)) {
		if($res == 'css') {
			header('content-type:text/css');
		} else {
			header('content-type:image/png');
		}
		echo base64_decode($reses[$res]);
		exit();
	}
}
$actions = array('license', 'env', 'db', 'finish');
$action = $_COOKIE['action'];
$action = in_array($action, $actions) ? $action : 'license';
$ispost = strtolower($_SERVER['REQUEST_METHOD']) == 'post';

if(file_exists(IA_ROOT . '/data/install.lock') && $action != 'finish') {
	header('location: ./index.php');
	exit;
}
header('content-type: text/html; charset=utf-8');
if($action == 'license') {
	if($ispost) {
		setcookie('action', 'env');
		header('location: ?refresh');
		exit;
	}
	tpl_install_license();
}
if($action == 'env') {
	if($ispost) {
		setcookie('action', $_POST['do'] == 'continue' ? 'db' : 'license');
		header('location: ?refresh');
		exit;
	}
	$ret = array();
	$ret['server']['os']['value'] = php_uname();
	if(PHP_SHLIB_SUFFIX == 'dll') {
		$ret['server']['os']['remark'] = '建议使用 Linux 系统以提升程序性能';
		$ret['server']['os']['class'] = 'warning';
	}
	$ret['server']['sapi']['value'] = $_SERVER['SERVER_SOFTWARE'];
	if(PHP_SAPI == 'isapi') {
		$ret['server']['sapi']['remark'] = '建议使用 Apache 或 Nginx 以提升程序性能';
		$ret['server']['sapi']['class'] = 'warning';
	}
	$ret['server']['php']['value'] = PHP_VERSION;
	$ret['server']['dir']['value'] = IA_ROOT;
	if(function_exists('disk_free_space')) {
		$ret['server']['disk']['value'] = floor(disk_free_space(IA_ROOT) / (1024*1024)).'M';
	} else {
		$ret['server']['disk']['value'] = 'unknow';
	}
	$ret['server']['upload']['value'] = @ini_get('file_uploads') ? ini_get('upload_max_filesize') : 'unknow';

	$ret['php']['version']['value'] = PHP_VERSION;
	$ret['php']['version']['class'] = 'success';
	if(version_compare(PHP_VERSION, '5.3.0') == -1) {
		$ret['php']['version']['class'] = 'danger';
		$ret['php']['version']['failed'] = true;
		$ret['php']['version']['remark'] = 'PHP版本必须为 5.3.0 以上. <a href="http://help.012wz.com/">详情</a>';
	}

	$ret['php']['pdo']['ok'] = extension_loaded('pdo') && extension_loaded('pdo_mysql');
	if($ret['php']['pdo']['ok']) {
		$ret['php']['pdo']['value'] = '<span class="glyphicon glyphicon-ok text-success"></span>';
		$ret['php']['pdo']['class'] = 'success';
		$ret['php']['pdo']['remark'] = '';
	} else {
		$ret['php']['pdo']['failed'] = true;
		$ret['php']['pdo']['value'] = '<span class="glyphicon glyphicon-remove text-danger"></span>';
		$ret['php']['pdo']['class'] = 'danger';
			$ret['php']['pdo']['remark'] = '您的PHP环境不支持PDO, 系统无法正常运行. <a target="_blank" href="http://bbs.2jin1.com/forum.php?mod=redirect&goto=findpost&ptid=3564&pid=58074">详情</a>';
	}

	$ret['php']['curl']['ok'] = extension_loaded('curl') && function_exists('curl_init');
	if($ret['php']['curl']['ok']) {
		$ret['php']['curl']['value'] = '<span class="glyphicon glyphicon-ok text-success"></span>';
		$ret['php']['curl']['class'] = 'success';
		$ret['php']['curl']['remark'] = '';
	} else {
		$ret['php']['curl']['value'] = '<span class="glyphicon glyphicon-remove text-danger"></span>';
		$ret['php']['curl']['class'] = 'danger';
			$ret['php']['curl']['remark'] = '您的PHP环境不支持cURL,  系统无法正常运行. <a target="_blank" href="http://help.012wz.com/">详情</a>';
		$ret['php']['curl']['failed'] = true;
	}

	$ret['php']['ssl']['ok'] = extension_loaded('openssl');
	if($ret['php']['ssl']['ok']) {
		$ret['php']['ssl']['value'] = '<span class="glyphicon glyphicon-ok text-success"></span>';
		$ret['php']['ssl']['class'] = 'success';
	} else {
		$ret['php']['ssl']['value'] = '<span class="glyphicon glyphicon-remove text-danger"></span>';
		$ret['php']['ssl']['class'] = 'danger';
		$ret['php']['ssl']['failed'] = true;
		$ret['php']['ssl']['remark'] = '没有启用OpenSSL, 将无法访问公众平台的接口, 系统无法正常运行. <a target="_blank" href="http://help.012wz.com/">详情</a>';
	}

	$ret['php']['gd']['ok'] = extension_loaded('gd');
	if($ret['php']['gd']['ok']) {
		$ret['php']['gd']['value'] = '<span class="glyphicon glyphicon-ok text-success"></span>';
		$ret['php']['gd']['class'] = 'success';
	} else {
		$ret['php']['gd']['value'] = '<span class="glyphicon glyphicon-remove text-danger"></span>';
		$ret['php']['gd']['class'] = 'danger';
		$ret['php']['gd']['failed'] = true;
		$ret['php']['gd']['remark'] = '没有启用GD, 将无法正常上传和压缩图片, 系统无法正常运行. <a target="_blank" href="http://help.012wz.com/">详情</a>';
	}

	$ret['php']['dom']['ok'] = class_exists('DOMDocument');
	if($ret['php']['dom']['ok']) {
		$ret['php']['dom']['value'] = '<span class="glyphicon glyphicon-ok text-success"></span>';
		$ret['php']['dom']['class'] = 'success';
	} else {
		$ret['php']['dom']['value'] = '<span class="glyphicon glyphicon-remove text-danger"></span>';
		$ret['php']['dom']['class'] = 'danger';
		$ret['php']['dom']['failed'] = true;
		$ret['php']['dom']['remark'] = '没有启用DOMDocument, 将无法正常安装使用模块, 系统无法正常运行. <a target="_blank" href="http://help.012wz.com/">详情</a>';
	}

	$ret['php']['session']['ok'] = ini_get('session.auto_start');
	if($ret['php']['session']['ok'] == 0 || strtolower($ret['php']['session']['ok']) == 'off') {
		$ret['php']['session']['value'] = '<span class="glyphicon glyphicon-ok text-success"></span>';
		$ret['php']['session']['class'] = 'success';
	} else {
		$ret['php']['session']['value'] = '<span class="glyphicon glyphicon-remove text-danger"></span>';
		$ret['php']['session']['class'] = 'danger';
		$ret['php']['session']['failed'] = true;
		$ret['php']['session']['remark'] = '系统session.auto_start开启, 将无法正常注册会员, 系统无法正常运行. <a target="_blank" href="http://bbs.weizancms.com/forum.php?mod=redirect&goto=findpost&ptid=3564&pid=58111">详情</a>';
	}

	$ret['php']['asp_tags']['ok'] = ini_get('asp_tags');
	if(empty($ret['php']['asp_tags']['ok']) || strtolower($ret['php']['asp_tags']['ok']) == 'off') {
		$ret['php']['asp_tags']['value'] = '<span class="glyphicon glyphicon-ok text-success"></span>';
		$ret['php']['asp_tags']['class'] = 'success';
	} else {
		$ret['php']['asp_tags']['value'] = '<span class="glyphicon glyphicon-remove text-danger"></span>';
		$ret['php']['asp_tags']['class'] = 'danger';
		$ret['php']['asp_tags']['failed'] = true;
		$ret['php']['asp_tags']['remark'] = '请禁用可以使用ASP 风格的标志，配置php.ini中asp_tags = Off';
	}

	$ret['write']['root']['ok'] = local_writeable(IA_ROOT . '/');
	if($ret['write']['root']['ok']) {
		$ret['write']['root']['value'] = '<span class="glyphicon glyphicon-ok text-success"></span>';
		$ret['write']['root']['class'] = 'success';
	} else {
		$ret['write']['root']['value'] = '<span class="glyphicon glyphicon-remove text-danger"></span>';
		$ret['write']['root']['class'] = 'danger';
		$ret['write']['root']['failed'] = true;
		$ret['write']['root']['remark'] = '本地目录无法写入, 将无法使用自动更新功能, 系统无法正常运行.  <a href="http://help.012wz.com/">详情</a>';
	}
	$ret['write']['data']['ok'] = local_writeable(IA_ROOT . '/data');
	if($ret['write']['data']['ok']) {
		$ret['write']['data']['value'] = '<span class="glyphicon glyphicon-ok text-success"></span>';
		$ret['write']['data']['class'] = 'success';
	} else {
		$ret['write']['data']['value'] = '<span class="glyphicon glyphicon-remove text-danger"></span>';
		$ret['write']['data']['class'] = 'danger';
		$ret['write']['data']['failed'] = true;
		$ret['write']['data']['remark'] = 'data目录无法写入, 将无法写入配置文件, 系统无法正常安装. ';
	}

	$ret['continue'] = true;
	foreach($ret['php'] as $opt) {
		if($opt['failed']) {
			$ret['continue'] = false;
			break;
		}
	}
	if($ret['write']['failed']) {
		$ret['continue'] = false;
	}
	tpl_install_env($ret);
}
if($action == 'db') {
	if($ispost) {
		if($_POST['do'] != 'continue') {
			setcookie('action', 'env');
			header('location: ?refresh');
			exit();
		}
		$family = $_POST['family'] == 'x' ? 'x' : 'v';
		$db = $_POST['db'];
		$user = $_POST['user'];
		list($host, $port) = explode(':', $db['server']);
		if (empty($port)) {
			$port = '3306';
		}
		try {
			$link = new PDO("mysql:host={$host};port={$port}", $db['username'], $db['password']);
		} catch (Exception $error) {
			$error = $error->getMessage();
			if (strpos($error, 'Access denied for user') !== false) {
				$error = '您的数据库访问用户名或是密码错误. <br />';
			} else {
				$error = iconv('gbk', 'utf-8', $error);
			}
		}
		if(!empty($link)) {
			$link->query("SET character_set_connection=utf8, character_set_results=utf8, character_set_client=binary");
			$link->query("SET sql_mode=''");

			if($link->errorCode() != '00000') {
				$error = $link->errorInfo();
				$error = $error[2];
			} else {
				$db_found = $link->query("SHOW DATABASES LIKE '{$db['name']}';")->fetchColumn();
				if (empty($db_found)) {
					if(version_compare($link->query('select version()')->fetchColumn(), '4.1') == '1') {
						$link->query("CREATE DATABASE IF NOT EXISTS `{$db['name']}` DEFAULT CHARACTER SET utf8");
					} else {
						$link->query("CREATE DATABASE IF NOT EXISTS `{$db['name']}`");
					}
				}
				$db_found = $link->query("SHOW DATABASES LIKE '{$db['name']}';")->fetchColumn();
				if (empty($db_found)) {
					$error .= "数据库不存在且创建数据库失败. <br />";
				}
				if($link->errorCode() != '00000') {
					$errorinfo = $link->errorInfo();
					$error .= $errorinfo[2];
				}
			}
		}
		if(empty($error)) {
			$link->query("USE `{$db['name']}`");
			$table_found = $link->query("SHOW TABLES LIKE '{$db['prefix']}%';")->fetchColumn();
			if (!empty($table_found)) {
				$error = '您的数据库不为空，请重新建立数据库或是清空该数据库或更改表前缀！';
			}
		}
		if(empty($error)) {
			$config = local_config();
			$cookiepre = local_salt(4) . '_';
			$authkey = local_salt(8);
			$config = str_replace(array(
				'{db-server}', '{db-username}', '{db-password}', '{db-port}', '{db-name}', '{db-tablepre}', '{cookiepre}', '{authkey}', '{attachdir}'
			), array(
				$host, $db['username'], $db['password'], $port, $db['name'], $db['prefix'], $cookiepre, $authkey, 'attachment'
			), $config);
			$verfile = IA_ROOT . '/framework/version.inc.php';
			$dbfile = IA_ROOT . '/data/db.php';

			if($_POST['type'] == 'remote') {
				$link = null;
				$ins = remote_install();
				if(empty($ins) || !is_array($ins)) {
					die('<script type="text/javascript">alert("连接不到服务器, 请稍后重试！");history.back();</script>');
				}
				if($ins['error']) {
					die('<script type="text/javascript">alert("链接微信更新服务器失败, 错误为: ' . $ins['error'] . '！");history.back();</script>');
				}
				$archive = $ins['files'];
				if(!$archive) {
					die('<script type="text/javascript">alert("未能下载程序包, 请确认你的安装程序目录有写入权限. 多次安装失败, 请访问论坛获取解决方案！");history.back();</script>');
				}
				$link = new PDO("mysql:dbname={$db['name']};host={$host};port={$port}", $db['username'], $db['password']);
				$link->query("SET character_set_connection=utf8, character_set_results=utf8, character_set_client=binary");
				$link->query("SET sql_mode=''");

				$version = $ins['version'];
				$release = $ins['release'];
				$family = $ins['family'];
				//写入临时目录
				$tmpfile = IA_ROOT . '/we7source.tmp';
				file_put_contents($tmpfile, $archive);
				local_mkdirs(IA_ROOT . '/data');
				file_put_contents(IA_ROOT . '/data/db.php', base64_decode($ins['schemas']));

				$fp = fopen($tmpfile, 'r');
				if ($fp) {
					$buffer = '';
					while (!feof($fp)) {
						$buffer .= fgets($fp, 4096);
						if($buffer[strlen($buffer) - 1] == "\n") {
							$pieces = explode(':', $buffer);
							$path = base64_decode($pieces[0]);
							$dat = base64_decode($pieces[1]);
							$fname = IA_ROOT . $path;
							local_mkdirs(dirname($fname));
							file_put_contents($fname, $dat);
							$buffer = '';
						}
					}
					fclose($fp);
				}
				unlink($tmpfile);
			} else {
				if (file_exists($verfile)) {
					include $verfile;
					$version = IMS_VERSION;
					$release = IMS_RELEASE_DATE;
				} else {
					$version = '';
					$release = date('YmdHis');
				}
			}
$verdat = <<<VER
<?php
/**
 * 版本号
 *
 * [WeEngine System] Copyright (c) 2013 weizancms.com
 */

defined('IN_IA') or exit('Access Denied');

define('IMS_FAMILY', '{$family}');
define('IMS_VERSION', '{$version}');
define('IMS_RELEASE_DATE', '{$release}');
VER;
			$is_ok = file_put_contents($verfile, $verdat);
			if(!$is_ok) {
				die('<script type="text/javascript">alert("生成版本文件失败");history.back();</script>');
			}
			if(file_exists(IA_ROOT . '/index.php') && is_dir(IA_ROOT . '/web') && file_exists($verfile) && file_exists($dbfile)) {
				$dat = require $dbfile;
				if(empty($dat) || !is_array($dat)) {
					die('<script type="text/javascript">alert("安装包不正确, 数据安装脚本缺失.");history.back();</script>');
				}
				foreach($dat['schemas'] as $schema) {
					$sql = local_create_sql($schema);
					local_run($sql);
				}
				foreach($dat['datas'] as $data) {
					local_run($data);
				}
			} else {
				die('<script type="text/javascript">alert("你正在使用本地安装, 但未下载完整安装包, 请从微信CMS下载完整安装包后重试.");history.back();</script>');
			}

			$salt = local_salt(8);
			$password = sha1("{$user['password']}-{$salt}-{$authkey}");
			$link->query("INSERT INTO {$db['prefix']}users (username, password, salt, joindate) VALUES('{$user['username']}', '{$password}', '{$salt}', '" . time() . "')");
			local_mkdirs(IA_ROOT . '/data');
			file_put_contents(IA_ROOT . '/data/config.php', $config);
			touch(IA_ROOT . '/data/install.lock');
			setcookie('action', 'finish');
			header('location: ?refresh');
			exit();
		}
	}
	tpl_install_db($error);

}
if($action == 'finish') {
	setcookie('action', '', -10);
	$dbfile = IA_ROOT . '/data/db.php';
	//@unlink($dbfile);
	define('IN_SYS', true);
	require IA_ROOT . '/framework/bootstrap.inc.php';
	require IA_ROOT . '/web/common/bootstrap.sys.inc.php';
	$_W['uid'] = $_W['isfounder'] = 1;
	load()->web('common');
	load()->web('template');
	load()->model('setting');
	load()->model('cache');

	cache_build_frame_menu();
	cache_build_setting();
	cache_build_users_struct();
	cache_build_module_subscribe_type();
	tpl_install_finish();
}

function local_writeable($dir) {
	$writeable = 0;
	if(!is_dir($dir)) {
		@mkdir($dir, 0777);
	}
	if(is_dir($dir)) {
		if($fp = fopen("$dir/test.txt", 'w')) {
			fclose($fp);
			unlink("$dir/test.txt");
			$writeable = 1;
		} else {
			$writeable = 0;
		}
	}
	return $writeable;
}

function local_salt($length = 8) {
	$result = '';
	while(strlen($result) < $length) {
		$result .= sha1(uniqid('', true));
	}
	return substr($result, 0, $length);
}

function local_config() {
	$cfg = <<<EOF
<?php
defined('IN_IA') or exit('Access Denied');

\$config = array();

\$config['db']['master']['host'] = '{db-server}';
\$config['db']['master']['username'] = '{db-username}';
\$config['db']['master']['password'] = '{db-password}';
\$config['db']['master']['port'] = '{db-port}';
\$config['db']['master']['database'] = '{db-name}';
\$config['db']['master']['charset'] = 'utf8';
\$config['db']['master']['pconnect'] = 0;
\$config['db']['master']['tablepre'] = '{db-tablepre}';

\$config['db']['slave_status'] = false;
\$config['db']['slave']['1']['host'] = '';
\$config['db']['slave']['1']['username'] = '';
\$config['db']['slave']['1']['password'] = '';
\$config['db']['slave']['1']['port'] = '3307';
\$config['db']['slave']['1']['database'] = '';
\$config['db']['slave']['1']['charset'] = 'utf8';
\$config['db']['slave']['1']['pconnect'] = 0;
\$config['db']['slave']['1']['tablepre'] = 'ims_';
\$config['db']['slave']['1']['weight'] = 0;

\$config['db']['common']['slave_except_table'] = array('core_sessions');

// --------------------------  CONFIG COOKIE  --------------------------- //
\$config['cookie']['pre'] = '{cookiepre}';
\$config['cookie']['domain'] = '';
\$config['cookie']['path'] = '/';

// --------------------------  CONFIG SETTING  --------------------------- //
\$config['setting']['charset'] = 'utf-8';
\$config['setting']['cache'] = 'mysql';
\$config['setting']['timezone'] = 'Asia/Shanghai';
\$config['setting']['memory_limit'] = '256M';
\$config['setting']['filemode'] = 0644;
\$config['setting']['authkey'] = '{authkey}';
\$config['setting']['founder'] = '1';
\$config['setting']['development'] = 0;
\$config['setting']['referrer'] = 0;
\$config['setting']['https'] = 0;

// --------------------------  CONFIG UPLOAD  --------------------------- //
\$config['upload']['image']['extentions'] = array('gif', 'jpg', 'jpeg', 'png');
\$config['upload']['image']['limit'] = 5000;
\$config['upload']['attachdir'] = '{attachdir}';
\$config['upload']['audio']['extentions'] = array('mp3');
\$config['upload']['audio']['limit'] = 5000;

// --------------------------  CONFIG MEMCACHE  --------------------------- //
\$config['setting']['memcache']['server'] = '';
\$config['setting']['memcache']['port'] = 11211;
\$config['setting']['memcache']['pconnect'] = 1;
\$config['setting']['memcache']['timeout'] = 30;
\$config['setting']['memcache']['session'] = 1;

// --------------------------  CONFIG PROXY  --------------------------- //
\$config['setting']['proxy']['host'] = '';
\$config['setting']['proxy']['auth'] = '';
EOF;
	return trim($cfg);
}

function local_mkdirs($path) {
	if(!is_dir($path)) {
		local_mkdirs(dirname($path));
		mkdir($path);
	}
	return is_dir($path);
}

function local_run($sql) {
	global $link, $db;

	if(!isset($sql) || empty($sql)) return;

	$sql = str_replace("\r", "\n", str_replace(' ims_', ' '.$db['prefix'], $sql));
	$sql = str_replace("\r", "\n", str_replace(' `ims_', ' `'.$db['prefix'], $sql));
	$ret = array();
	$num = 0;
	foreach(explode(";\n", trim($sql)) as $query) {
		$ret[$num] = '';
		$queries = explode("\n", trim($query));
		foreach($queries as $query) {
			$ret[$num] .= (isset($query[0]) && $query[0] == '#') || (isset($query[1]) && isset($query[1]) && $query[0].$query[1] == '--') ? '' : $query;
		}
		$num++;
	}
	unset($sql);
	foreach($ret as $query) {
		$query = trim($query);
		if($query) {
			if(!$link->query($query)) {
				$errorinfo = $link->errorInfo();
				echo $errorinfo[2] . ": " . $link->errorCode() . "<br />";
				exit($query);
			}
		}
	}
}

function local_create_sql($schema) {
	$pieces = explode('_', $schema['charset']);
	$charset = $pieces[0];
	$engine = $schema['engine'];
	$sql = "CREATE TABLE IF NOT EXISTS `{$schema['tablename']}` (\n";
	foreach ($schema['fields'] as $value) {
		if(!empty($value['length'])) {
			$length = "({$value['length']})";
		} else {
			$length = '';
		}

		$signed  = empty($value['signed']) ? ' unsigned' : '';
		if(empty($value['null'])) {
			$null = ' NOT NULL';
		} else {
			$null = '';
		}
		if(isset($value['default'])) {
			$default = " DEFAULT '" . $value['default'] . "'";
		} else {
			$default = '';
		}
		if($value['increment']) {
			$increment = ' AUTO_INCREMENT';
		} else {
			$increment = '';
		}

		$sql .= "`{$value['name']}` {$value['type']}{$length}{$signed}{$null}{$default}{$increment},\n";
	}
	foreach ($schema['indexes'] as $value) {
		$fields = implode('`,`', $value['fields']);
		if($value['type'] == 'index') {
			$sql .= "KEY `{$value['name']}` (`{$fields}`),\n";
		}
		if($value['type'] == 'unique') {
			$sql .= "UNIQUE KEY `{$value['name']}` (`{$fields}`),\n";
		}
		if($value['type'] == 'primary') {
			$sql .= "PRIMARY KEY (`{$fields}`),\n";
		}
	}
	$sql = rtrim($sql);
	$sql = rtrim($sql, ',');

	$sql .= "\n) ENGINE=$engine DEFAULT CHARSET=$charset;\n\n";
	return $sql;
}

function __remote_install_headers($ch = '', $header = '') {
	static $hash;
	if(!empty($header)) {
		$pieces = explode(':', $header);
		if(trim($pieces[0]) == 'hash') {
			$hash = trim($pieces[1]);
		}
	}
	if($ch == '' && $header == '') {
		return $hash;
	}
	return strlen($header);
}

function remote_install() {
	global $family;
	$token = '';
	$pars = array();
	$pars['host'] = $_SERVER['HTTP_HOST'];
	$pars['version'] = '1.0';
	$pars['release'] = '';
	$pars['type'] = 'install';
	$pars['product'] = '';
	$url = 'http://addons.weizancms.com/gateway.php';
	$urlset = parse_url($url);
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $pars);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADERFUNCTION, '__remote_install_headers');
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$content = curl_exec($ch);
	curl_close($ch);
	$sign = __remote_install_headers();
	$ret = array();
	if(empty($content)) {
		return showerror(-1, '获取安装信息失败，可能是由于网络不稳定，请重试。');
	}
	$ret = unserialize($content);
	if($sign != md5($ret['data'] . $token)) {
		return showerror(-1, '发生错误: 数据校验失败，可能是传输过程中网络不稳定导致，请重试。');
	}
	$ret['data'] = unserialize($ret['data']);
	return $ret['data'];
}

function __remote_download_headers($ch = '', $header = '') {
	static $hash;
	if(!empty($header)) {
		$pieces = explode(':', $header);
		if(trim($pieces[0]) == 'hash') {
			$hash = trim($pieces[1]);
		}
	}
	if($ch == '' && $header == '') {
		return $hash;
	}
	return strlen($header);
}

function remote_download($archive) {
	$pars = array();
	$pars['host'] = $_SERVER['HTTP_HOST'];
	$pars['version'] = '';
	$pars['release'] = '';
	$pars['archive'] = base64_encode(json_encode($archive));
	$url = 'http://addons.weizancms.com/gateway.php';
	$urlset = parse_url($url);
	$headers[] = "Host: {$urlset['host']}";
	$tmpfile = IA_ROOT . '/weizan.zip';
	$fp = fopen($tmpfile, 'w+');
	if(!$fp) {
		return false;
	}
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $pars);
	curl_setopt($ch, CURLOPT_FILE, $fp);
	curl_setopt($ch, CURLOPT_HEADERFUNCTION, '__remote_download_headers');
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	if(!curl_exec($ch)) {
		return false;
	}
	curl_close($ch);
	fclose($fp);
	$sign = __remote_download_headers();
	if(md5_file($tmpfile) == $sign) {
		return $tmpfile;
	}
	return false;
}

function tpl_frame() {
	global $action, $actions;
	$action = $_COOKIE['action'];
	$step = array_search($action, $actions);
	$steps = array();
	for($i = 0; $i <= $step; $i++) {
		if($i == $step) {
			$steps[$i] = ' list-group-item-info';
		} else {
			$steps[$i] = ' list-group-item-success';
		}
	}
	$progress = $step * 25 + 25;
	$content = ob_get_contents();
	ob_clean();
	$tpl = <<<EOF
<!DOCTYPE html>
<html lang="zh-cn">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>安装系统 - 微信插件框架</title>
		<link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.2.0/css/bootstrap.min.css">
		<style>
			html,body{font-size:13px;font-family:"Microsoft YaHei UI", "微软雅黑", "宋体";}
			.pager li.previous a{margin-right:10px;}
			.header a{color:#FFF;}
			.header a:hover{color:#428bca;}
			.footer{padding:10px;}
			.footer a,.footer{color:#eee;font-size:14px;line-height:25px;}
		</style>
		<!--[if lt IE 9]>
		  <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		  <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body style="background-color:#28b0e4;">
		<div class="container">
			<div class="header" style="margin:15px auto;">
				<ul class="nav nav-pills pull-right" role="tablist">
					<li role="presentation" class="active"><a href="javascript:;">安装微信框架</a></li>
					<li role="presentation"><a href="http://www.weizancms.com">插件商城</a></li>
				</ul>
				<img src="http://bbs.2jin1.com/static/image/common/top-logo.png" />
			</div>
			<div class="row well" style="margin:auto 0;">
				<div class="col-xs-3">
					<div class="progress" title="安装进度">
						<div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="{$progress}" aria-valuemin="0" aria-valuemax="100" style="width: {$progress}%;">
							{$progress}%
						</div>
					</div>
					<div class="panel panel-default">
						<div class="panel-heading">
							安装步骤
						</div>
						<ul class="list-group">
							<a href="javascript:;" class="list-group-item{$steps[0]}"><span class="glyphicon glyphicon-copyright-mark"></span> &nbsp; 许可协议</a>
							<a href="javascript:;" class="list-group-item{$steps[1]}"><span class="glyphicon glyphicon-eye-open"></span> &nbsp; 环境监测</a>
							<a href="javascript:;" class="list-group-item{$steps[2]}"><span class="glyphicon glyphicon-cog"></span> &nbsp; 参数配置</a>
							<a href="javascript:;" class="list-group-item{$steps[3]}"><span class="glyphicon glyphicon-ok"></span> &nbsp; 成功</a>
						</ul>
					</div>
				</div>
				<div class="col-xs-9">
					{$content}
				</div>
			</div>
			<div class="footer" style="margin:15px auto;">
				<div class="text-center">
					<a href="http://www.weizancms.com">关于插件商城</a> &nbsp; &nbsp; 
				</div>
				<div class="text-center">
					Powered by <a href="/"><b>微信CMS</b></a> v0.9
				</div>
			</div>
		</div>
		<script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
		<script src="http://cdn.bootcss.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	</body>
</html>
EOF;
	echo trim($tpl);
}

function tpl_install_license() {
	echo <<<EOF
		<div class="panel panel-default">
			<div class="panel-heading">阅读许可协议</div>
			<div class="panel-body" style="overflow-y:scroll;max-height:400px;line-height:20px;">
				<h3>版权所有 (c)2014-2015，微信CMS团队保留部分权利。 </h3>
				<p>
					感谢您选择微信CMS - 微信公众平台插件开源免费框架（以下简称WEIZANCMS，WEIZANCMS基于 PHP + MySQL的技术开发） <br />
					为了使你正确并合法的使用本软件，请你在使用前务必阅读清楚下面的协议条款：
				</p>
				<p>
					<strong>一、本授权协议适用且仅适用于微信CMS系统(WEIZANCMS. 以下简称微信CMS)任何版本，微信CMS官方对本授权协议的最终解释权。</strong>
				</p>
				<p>
					<strong>二、协议许可的权利 </strong>
					<ol>
						<li>您可以在协议规定的约束和限制范围内修改微信CMS源代码或界面风格以适应您的网站要求。</li>
						<li>您拥有使用本软件构建的网站全部内容所有权，并独立承担与这些内容的相关法律义务。</li>
					</ol>
				</p>
				<p>
					<strong>三、协议规定的约束和限制 </strong>
					<ol>
						<li>未经官方许可，不得对本软件或与之关联的代码进行出租、出售、抵押或发放子许可证。</li>
						<li>未经官方许可，禁止在微信的整体或任何部分基础上以发展任何派生版本、修改版本或第三方版本用于重新分发。</li>
						<li>如果您未能遵守本协议的条款，您的授权将被终止，所被许可的权利将被收回，并承担相应法律责任。</li>
					</ol>
				</p>
				<p>
					<strong>四、有限担保和免责声明 </strong>
					<ol>
						<li>本软件及所附带的文件是作为不提供任何明确的或隐含的赔偿或担保的形式提供的。</li>
						<li>用户出于自愿而使用本软件，您必须了解使用本软件的风险，在尚未购买产品技术服务之前，我们不承诺对免费用户提供任何形式的技术支持、使用担保，也不承担任何因使用本软件而产生问题的相关责任。</li>
						<li>电子文本形式的授权协议如同双方书面签署的协议一样，具有完全的和等同的法律效力。您一旦开始确认本协议并安装  WEIZANCMS，即被视为完全理解并接受本协议的各项条款，在享有上述条款授予的权力的同时，受到相关的约束和限制。协议许可范围以外的行为，将直接违反本授权协议并构成侵权，我们有权随时终止授权，责令停止损害，并保留追究相关责任的权力。</li>
						<li>本软件源自网上第三方免费源码，并且这些文件是没经过授权发布的，请参考软件的使用许可合法的使用。</li>
					</ol>
				</p>
			</div>
		</div>
		<form class="form-inline" role="form" method="post">
			<ul class="pager">
				<li class="pull-left" style="display:block;padding:5px 10px 5px 0;">
					<div class="checkbox">
						<label>
							<input type="checkbox"> 我已经阅读并同意此协议
						</label>
					</div>
				</li>
				<li class="previous"><a href="javascript:;" onclick="if(jQuery(':checkbox:checked').length == 1){jQuery('form')[0].submit();}else{alert('您必须同意软件许可协议才能安装！')};">继续 <span class="glyphicon glyphicon-chevron-right"></span></a></li>
			</ul>
		</form>
EOF;
	tpl_frame();
}

function tpl_install_env($ret = array()) {
	if(empty($ret['continue'])) {
		$continue = '<li class="previous disabled"><a href="javascript:;">请先解决环境问题后继续</a></li>';
	} else {
		$continue = '<li class="previous"><a href="javascript:;" onclick="$(\'#do\').val(\'continue\');$(\'form\')[0].submit();">继续 <span class="glyphicon glyphicon-chevron-right"></span></a></li>';
	}
	echo <<<EOF
		<div class="panel panel-default">
			<div class="panel-heading">服务器信息</div>
			<table class="table table-striped">
				<tr>
					<th style="width:150px;">参数</th>
					<th>值</th>
					<th></th>
				</tr>
				<tr class="{$ret['server']['os']['class']}">
					<td>服务器操作系统</td>
					<td>{$ret['server']['os']['value']}</td>
					<td>{$ret['server']['os']['remark']}</td>
				</tr>
				<tr class="{$ret['server']['sapi']['class']}">
					<td>Web服务器环境</td>
					<td>{$ret['server']['sapi']['value']}</td>
					<td>{$ret['server']['sapi']['remark']}</td>
				</tr>
				<tr class="{$ret['server']['php']['class']}">
					<td>PHP版本</td>
					<td>{$ret['server']['php']['value']}</td>
					<td>{$ret['server']['php']['remark']}</td>
				</tr>
				<tr class="{$ret['server']['dir']['class']}">
					<td>程序安装目录</td>
					<td>{$ret['server']['dir']['value']}</td>
					<td>{$ret['server']['dir']['remark']}</td>
				</tr>
				<tr class="{$ret['server']['disk']['class']}">
					<td>磁盘空间</td>
					<td>{$ret['server']['disk']['value']}</td>
					<td>{$ret['server']['disk']['remark']}</td>
				</tr>
				<tr class="{$ret['server']['upload']['class']}">
					<td>上传限制</td>
					<td>{$ret['server']['upload']['value']}</td>
					<td>{$ret['server']['upload']['remark']}</td>
				</tr>
			</table>
		</div>

		<div class="alert alert-info">PHP环境要求必须满足下列所有条件，否则系统或系统部份功能将无法使用。</div>
		<div class="panel panel-default">
			<div class="panel-heading">PHP环境要求</div>
			<table class="table table-striped">
				<tr>
					<th style="width:150px;">选项</th>
					<th style="width:180px;">要求</th>
					<th style="width:50px;">状态</th>
					<th>说明及帮助</th>
				</tr>
				<tr class="{$ret['php']['version']['class']}">
					<td>PHP版本</td>
					<td>5.3或者5.3以上，建议使用php7</td>
					<td>{$ret['php']['version']['value']}</td>
					<td>{$ret['php']['version']['remark']}</td>
				</tr>
				<tr class="{$ret['php']['pdo']['class']}">
					<td>PDO_MYSQL</td>
					<td>支持</td>
					<td>{$ret['php']['pdo']['value']}</td>
					<td>{$ret['php']['pdo']['remark']}</td>
				</tr>
				<tr class="{$ret['php']['curl']['class']}">
					<td>cURL</td>
					<td>支持</td>
					<td>{$ret['php']['curl']['value']}</td>
					<td>{$ret['php']['curl']['remark']}</td>
				</tr>
				<tr class="{$ret['php']['ssl']['class']}">
					<td>openSSL</td>
					<td>支持</td>
					<td>{$ret['php']['ssl']['value']}</td>
					<td>{$ret['php']['ssl']['remark']}</td>
				</tr>
				<tr class="{$ret['php']['gd']['class']}">
					<td>GD2</td>
					<td>支持</td>
					<td>{$ret['php']['gd']['value']}</td>
					<td>{$ret['php']['gd']['remark']}</td>
				</tr>
				<tr class="{$ret['php']['dom']['class']}">
					<td>DOM</td>
					<td>支持</td>
					<td>{$ret['php']['dom']['value']}</td>
					<td>{$ret['php']['dom']['remark']}</td>
				</tr>
				<tr class="{$ret['php']['session']['class']}">
					<td>session.auto_start</td>
					<td>关闭</td>
					<td>{$ret['php']['session']['value']}</td>
					<td>{$ret['php']['session']['remark']}</td>
				</tr>
				<tr class="{$ret['php']['asp_tags']['class']}">
					<td>asp_tags</td>
					<td>关闭</td>
					<td>{$ret['php']['asp_tags']['value']}</td>
					<td>{$ret['php']['asp_tags']['remark']}</td>
				</tr>
			</table>
		</div>

		<div class="alert alert-info">系统要求微信整个安装目录必须可写, 才能使用微信所有功能。</div>
		<div class="panel panel-default">
			<div class="panel-heading">目录权限监测</div>
			<table class="table table-striped">
				<tr>
					<th style="width:150px;">目录</th>
					<th style="width:180px;">要求</th>
					<th style="width:50px;">状态</th>
					<th>说明及帮助</th>
				</tr>
				<tr class="{$ret['write']['root']['class']}">
					<td>/</td>
					<td>整目录可写</td>
					<td>{$ret['write']['root']['value']}</td>
					<td>{$ret['write']['root']['remark']}</td>
				</tr>
				<tr class="{$ret['write']['data']['class']}">
					<td>/</td>
					<td>data目录可写</td>
					<td>{$ret['write']['data']['value']}</td>
					<td>{$ret['write']['data']['remark']}</td>
				</tr>
			</table>
		</div>
		<form class="form-inline" role="form" method="post">
			<input type="hidden" name="do" id="do" />
			<ul class="pager">
				<li class="previous"><a href="javascript:;" onclick="$('#do').val('back');$('form')[0].submit();"><span class="glyphicon glyphicon-chevron-left"></span> 返回</a></li>
				{$continue}
			</ul>
		</form>
EOF;
	tpl_frame();
}

function tpl_install_db($error = '') {
	if(!empty($error)) {
		$message = '<div class="alert alert-danger">发生错误: ' . $error . '</div>';
	}
	$insTypes = array();
	if(file_exists(IA_ROOT . '/index.php') && is_dir(IA_ROOT . '/app') && is_dir(IA_ROOT . '/web')) {
		$insTypes['local'] = ' checked="checked"';
	} else {
		$insTypes['remote'] = ' checked="checked"';
	}
	if (!empty($_POST['type'])) {
		$insTypes = array();
		$insTypes[$_POST['type']] = ' checked="checked"';
	}
	$disabled = empty($insTypes['local']) ? ' disabled="disabled"' : '';
	echo <<<EOF
	{$message}
	<form class="form-horizontal" method="post" role="form">
		<div class="panel panel-default">
			<div class="panel-heading">安装选项</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-sm-2 control-label">安装方式</label>
					<div class="col-sm-10">
						
						<label class="radio-inline">
							<input type="radio" name="type" value="local"{$insTypes['local']}{$disabled}> 离线安装
						</label>
						<div class="help-block">
							在线安装能够直接安装最新版本微信系统, 如果在线安装困难, 请下载离线安装包后使用本地安装. <br/>
							离线安装包可能不是最新程序, 如果你不确定, 可以现在访问官网重新下载一份最新的.
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">数据库选项</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-sm-2 control-label">数据库主机</label>
					<div class="col-sm-4">
						<input class="form-control" type="text" name="db[server]" value="127.0.0.1">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">数据库用户</label>
					<div class="col-sm-4">
						<input class="form-control" type="text" name="db[username]" value="root">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">数据库密码</label>
					<div class="col-sm-4">
						<input class="form-control" type="text" name="db[password]">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">表前缀</label>
					<div class="col-sm-4">
						<input class="form-control" type="text" name="db[prefix]" value="ims_">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">数据库名称</label>
					<div class="col-sm-4">
						<input class="form-control" type="text" name="db[name]" value="weizan">
					</div>
				</div>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">管理选项</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-sm-2 control-label">管理员账号</label>
					<div class="col-sm-4">
						<input class="form-control" type="username" name="user[username]">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">管理员密码</label>
					<div class="col-sm-4">
						<input class="form-control" type="password" name="user[password]">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">确认密码</label>
					<div class="col-sm-4">
						<input class="form-control" type="password"">
					</div>
				</div>
			</div>
		</div>
		<input type="hidden" name="do" id="do" />
		<ul class="pager">
			<li class="previous"><a href="javascript:;" onclick="$('#do').val('back');$('form')[0].submit();"><span class="glyphicon glyphicon-chevron-left"></span> 返回</a></li>
			<li class="previous"><a href="javascript:;" onclick="if(check(this)){jQuery('#do').val('continue');$('form')[0].submit();}">继续 <span class="glyphicon glyphicon-chevron-right"></span></a></li>
		</ul>
	</form>
	<script>
		var lock = false;
		function check(obj) {
			if(lock) {
				return;
			}
			$('.form-control').parent().parent().removeClass('has-error');
			var error = false;
			$('.form-control').each(function(){
				if($(this).val() == '') {
					$(this).parent().parent().addClass('has-error');
					this.focus();
					error = true;
				}
			});
			if(error) {
				alert('请检查未填项');
				return false;
			}
			if($(':password').eq(0).val() != $(':password').eq(1).val()) {
				$(':password').parent().parent().addClass('has-error');
				alert('确认密码不正确.');
				return false;
			}
			lock = true;
			$(obj).parent().addClass('disabled');
			$(obj).html('正在执行安装');
			return true;
		}
	</script>
EOF;
	tpl_frame();
}

function tpl_install_finish() {
	echo <<<EOF
	<div class="page-header"><h3>安装完成</h3></div>
	<div class="alert alert-success">
		恭喜您!已成功安装“微信 - 公众平台自助开源引擎”系统，您现在可以: <a target="_blank" class="btn btn-success" href="./web/index.php">访问网站首页</a>
		<br />
	</div>
EOF;
	tpl_frame();
}

function tpl_resources() {
	static $res = array(
		'logo' => 'http://bbs.2jin1.com/static/image/common/top-logo.png',
	);
	return $res;
}

function showerror($errno, $message = '') {
	return array(
		'errno' => $errno,
		'error' => $message,
	);
}
