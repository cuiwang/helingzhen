<?php
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
defined('IN_IA') or exit('Access Denied');


function ext_module_convert($manifest) {
	if (!empty($manifest['platform']['supports'])) {
		$app_support = in_array('app', $manifest['platform']['supports']) ? 2 : 1;
		$wxapp_support = in_array('wxapp', $manifest['platform']['supports']) ? 2 : 1;
		if ($app_support == 1 && $wxapp_support == 1) {
			$app_support = 2;
		}
	} else {
		$app_support = 2;
		$wxapp_support = 1;
	}

	return array(
		'name' => $manifest['application']['identifie'],
		'title' => $manifest['application']['name'],
		'version' => $manifest['application']['version'],
		'type' => $manifest['application']['type'],
		'ability' => $manifest['application']['ability'],
		'description' => $manifest['application']['description'],
		'author' => $manifest['application']['author'],
		'url' => $manifest['application']['url'],
		'settings'  => intval($manifest['application']['setting']),
		'subscribes' => iserializer(is_array($manifest['platform']['subscribes']) ? $manifest['platform']['subscribes'] : array()),
		'handles' => iserializer(is_array($manifest['platform']['handles']) ? $manifest['platform']['handles'] : array()),
		'isrulefields' => intval($manifest['platform']['isrulefields']),
		'iscard' => intval($manifest['platform']['iscard']),
		'page' => $manifest['bindings']['page'],
		'cover' => $manifest['bindings']['cover'],
		'rule' => $manifest['bindings']['rule'],
		'menu' => $manifest['bindings']['menu'],
		'home' => $manifest['bindings']['home'],
		'profile' => $manifest['bindings']['profile'],
		'app_support' => $app_support,
		'wxapp_support' => $wxapp_support,
		'shortcut' => $manifest['bindings']['shortcut'],
		'function' => $manifest['bindings']['function'],
		'permissions' => $manifest['permissions'],
		'issystem' => 0,
	);
}


function ext_module_manifest_parse($xml) {
	if (!strexists($xml, '<manifest')) {
		$xml = base64_decode($xml);
	}
	if (empty($xml)) {
		return array();
	}
	$dom = new DOMDocument();
	$dom->loadXML($xml);
		$root = $dom->getElementsByTagName('manifest')->item(0);
	if (empty($root)) {
		return array();
	}
	$vcode = explode(',', $root->getAttribute('versionCode'));
	$manifest['versions'] = array();
	if (is_array($vcode)) {
		foreach ($vcode as $v) {
			$v = trim($v);
			if (!empty($v)) {
				$manifest['versions'][] = $v;
			}
		}
		$manifest['versions'][] = '0.52';
		$manifest['versions'][] = '0.6';
		$manifest['versions'] = array_unique($manifest['versions']);
	}
	$manifest['install'] = $root->getElementsByTagName('install')->item(0)->textContent;
	$manifest['uninstall'] = $root->getElementsByTagName('uninstall')->item(0)->textContent;
	$manifest['upgrade'] = $root->getElementsByTagName('upgrade')->item(0)->textContent;
	$application = $root->getElementsByTagName('application')->item(0);
	if (empty($application)) {
		return array();
	}
	$manifest['application'] = array(
		'name' => trim($application->getElementsByTagName('name')->item(0)->textContent),
		'identifie' => trim($application->getElementsByTagName('identifie')->item(0)->textContent),
		'version' => trim($application->getElementsByTagName('version')->item(0)->textContent),
		'type' => trim($application->getElementsByTagName('type')->item(0)->textContent),
		'ability' => trim($application->getElementsByTagName('ability')->item(0)->textContent),
		'description' => trim($application->getElementsByTagName('description')->item(0)->textContent),
		'author' => trim($application->getElementsByTagName('author')->item(0)->textContent),
		'url' => trim($application->getElementsByTagName('url')->item(0)->textContent),
		'setting' => trim($application->getAttribute('setting')) == 'true',
	);
	$platform = $root->getElementsByTagName('platform')->item(0);
	if (!empty($platform)) {
		$manifest['platform'] = array(
			'subscribes' => array(),
			'handles' => array(),
			'isrulefields' => false,
			'iscard' => false,
			'supports' => array(),
		);
				$subscribes = $platform->getElementsByTagName('subscribes')->item(0);
		if (!empty($subscribes)) {
			$messages = $subscribes->getElementsByTagName('message');
			for ($i = 0; $i < $messages->length; $i++) {
				$t = $messages->item($i)->getAttribute('type');
				if (!empty($t)) {
					$manifest['platform']['subscribes'][] = $t;
				}
			}
		}
				$handles = $platform->getElementsByTagName('handles')->item(0);
		if (!empty($handles)) {
			$messages = $handles->getElementsByTagName('message');
			for ($i = 0; $i < $messages->length; $i++) {
				$t = $messages->item($i)->getAttribute('type');
				if (!empty($t)) {
					$manifest['platform']['handles'][] = $t;
				}
			}
		}
				$rule = $platform->getElementsByTagName('rule')->item(0);
		if (!empty($rule) && $rule->getAttribute('embed') == 'true') {
			$manifest['platform']['isrulefields'] = true;
		}
				$card = $platform->getElementsByTagName('card')->item(0);
		if (!empty($card) && $card->getAttribute('embed') == 'true') {
			$manifest['platform']['iscard'] = true;
		}
		$supports = $platform->getElementsByTagName('supports')->item(0);
		if (!empty($supports)) {
			$support_type = $supports->getElementsByTagName('item');
			for ($i = 0; $i < $support_type->length; $i++) {
				$t = $support_type->item($i)->getAttribute('type');
				if (!empty($t)) {
					$manifest['platform']['supports'][] = $t;
				}
			}
		}
				$plugins = $platform->getElementsByTagName('plugins')->item(0);
		if (!empty($plugins)) {
			$plugin_list = $plugins->getElementsByTagName('item');
			for ($i = 0; $i < $plugin_list->length; $i++) {
				$plugin = $plugin_list->item($i)->getAttribute('name');
				if (!empty($plugin)) {
					$manifest['platform']['plugin_list'][] = $plugin;
				}
			}
		}
		$plugin_main = $platform->getElementsByTagName('plugin-main')->item(0);
		if (!empty($plugin_main)) {
			$plugin_main = $plugin_main->getAttribute('name');
			if (!empty($plugin_main)) {
				$manifest['platform']['main_module'] = $plugin_main;
			}
		}
	}
		$bindings = $root->getElementsByTagName('bindings')->item(0);
	if (!empty($bindings)) {
		global $points;
		if (!empty($points)) {
			$ps = array_keys($points);
			$manifest['bindings'] = array();
			foreach ($ps as $p) {
				$define = $bindings->getElementsByTagName($p)->item(0);
				$manifest['bindings'][$p] = _ext_module_manifest_entries($define);
			}
		}
	}
		$permissions = $root->getElementsByTagName('permissions')->item(0);
	if (!empty($permissions)) {
		$manifest['permissions'] = array();
		$items = $permissions->getElementsByTagName('entry');
		for ($i = 0; $i < $items->length; $i++) {
			$item = $items->item($i);
			$row = array(
				'title' => $item->getAttribute('title'),
				'permission' => $item->getAttribute('do'),
			);
			if (!empty($row['title']) && !empty($row['permission'])) {
				$manifest['permissions'][] = $row;
			}
		}
	}
	return $manifest;
}


function ext_module_manifest($modulename) {
	$filename = IA_ROOT . '/addons/' . $modulename . '/manifest.xml';
	if (!file_exists($filename)) {
		return array();
	}
	$xml = file_get_contents($filename);
	return ext_module_manifest_parse($xml);
}


function _ext_module_manifest_entries($elm) {
	$ret = array();
	if (!empty($elm)) {
		$call = $elm->getAttribute('call');
		if (!empty($call)) {
			$ret[] = array('call' => $call);
		}
		$entries = $elm->getElementsByTagName('entry');
		for ($i = 0; $i < $entries->length; $i++) {
			$entry = $entries->item($i);
			$direct = $entry->getAttribute('direct');
			$row = array(
				'title' => $entry->getAttribute('title'),
				'do' => $entry->getAttribute('do'),
				'direct' => !empty($direct) && $direct != 'false' ? true : false,
				'state' => $entry->getAttribute('state')
			);
			if (!empty($row['title']) && !empty($row['do'])) {
				$ret[] = $row;
			}
		}
	}
	return $ret;
}


function ext_module_checkupdate($modulename) {
	$manifest = ext_module_manifest($modulename);
	if (!empty($manifest) && is_array($manifest)) {
		$version = $manifest['application']['version'];
		load()->model('module');
		$module = module_fetch($modulename);
		if (version_compare($version, $module['version']) == '1') {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}


function ext_module_bindings() {
	static $bindings = array(
		'cover' => array(
			'name' => 'cover',
			'title' => '功能封面',
			'desc' => '功能封面是定义微站里一个独立功能的入口(手机端操作), 将呈现为一个图文消息, 点击后进入微站系统中对应的功能.'
		),
		'rule' => array(
			'name' => 'rule',
			'title' => '规则列表',
			'desc' => '规则列表是定义可重复使用或者可创建多次的活动的功能入口(管理后台Web操作), 每个活动对应一条规则. 一般呈现为图文消息, 点击后进入定义好的某次活动中.'
		),
		'menu' => array(
			'name' => 'menu',
			'title' => '管理中心导航菜单',
			'desc' => '管理中心导航菜单将会在管理中心生成一个导航入口(管理后台Web操作), 用于对模块定义的内容进行管理.'
		),
		'home' => array(
			'name' => 'home',
			'title' => '微站首页导航图标',
			'desc' => '在微站的首页上显示相关功能的链接入口(手机端操作), 一般用于通用功能的展示.'
		),
		'profile'=> array(
			'name' => 'profile',
			'title' => '微站个人中心导航',
			'desc' => '在微站的个人中心上显示相关功能的链接入口(手机端操作), 一般用于个人信息, 或针对个人的数据的展示.'
		),
		'shortcut'=> array(
			'name' => 'shortcut',
			'title' => '微站快捷功能导航',
			'desc' => '在微站的快捷菜单上展示相关功能的链接入口(手机端操作), 仅在支持快捷菜单的微站模块上有效.'
		),
		'function'=> array(
			'name' => 'function',
			'title' => '微站独立功能',
			'desc' => '需要特殊定义的操作, 一般用于将指定的操作指定为(direct). 如果一个操作没有在具体位置绑定, 但是需要定义为(direct: 直接访问), 可以使用这个嵌入点'
		),
		'page'=> array(
			'name' => 'page',
			'title' => '小程序入口',
			'desc' => '用于小程序入口的链接'
		)
	);
	return $bindings;
}


function ext_module_clean($modulename, $isCleanRule = false) {
	$pars = array();
	$pars[':module'] = $modulename;
	$sql = 'DELETE FROM ' . tablename('core_queue') . ' WHERE `module`=:module';
	pdo_query($sql, $pars);

	$sql = 'DELETE FROM ' . tablename('modules') . ' WHERE `name`=:module';
	pdo_query($sql, $pars);

	$sql = 'DELETE FROM ' . tablename('modules_bindings') . ' WHERE `module`=:module';
	pdo_query($sql, $pars);

	if ($isCleanRule) {

		$sql = 'DELETE FROM ' . tablename('rule') . ' WHERE `module`=:module';
		pdo_query($sql, $pars);

		$sql = 'DELETE FROM ' . tablename('rule_keyword') . ' WHERE `module`=:module';
		pdo_query($sql, $pars);

		$sql = 'SELECT rid FROM ' . tablename('cover_reply') . ' WHERE `module`=:module';
		$data = pdo_fetchall($sql, $pars, 'rid');
		if (!empty($data)) {
			$rids = array_keys($data);
			$ridstr = implode(',', $rids);
			pdo_query('DELETE FROM ' . tablename('rule_keyword') . " WHERE module = 'cover' AND rid IN ({$ridstr})");
			pdo_query('DELETE FROM ' . tablename('rule') . " WHERE module = 'cover' AND id IN ({$ridstr})");

			$sql = 'DELETE FROM ' . tablename('cover_reply') . ' WHERE `module`=:module';
			pdo_query($sql, $pars);
		}
	}

	$sql = 'DELETE FROM ' . tablename('site_nav') . ' WHERE `module`=:module';
	pdo_query($sql, $pars);

	$sql = 'DELETE FROM ' . tablename('uni_account_modules') . ' WHERE `module`=:module';
	pdo_query($sql, $pars);

}


function ext_module_manifest_validate() {
	if(strcasecmp(ADDONS_URL,'http://addons.wdlcms.com')==0) {
			$xsd = <<<TPL
<?xml version="1.0" encoding="utf-8"?>
<xs:schema xmlns='http://www.wdlcms.com' targetNamespace='http://www.wdlcms.com' xmlns:xs="http://www.w3.org/2001/XMLSchema" elementFormDefault="qualified">
	<xs:element name="entry">
		<xs:complexType>
			<xs:attribute name="title" type="xs:string" />
			<xs:attribute name="do" type="xs:string" />
			<xs:attribute name="direct" type="xs:boolean" />
			<xs:attribute name="state" type="xs:string" />
		</xs:complexType>
	</xs:element>
	<xs:element name="dl">
		<xs:complexType>
			<xs:attribute name="name" type="xs:string" />
			<xs:attribute name="value" type="xs:string" />
		</xs:complexType>
	</xs:element>
	<xs:element name="message">
		<xs:complexType>
			<xs:attribute name="type" type="xs:string" />
		</xs:complexType>
	</xs:element>
	<xs:element name="manifest">
		<xs:complexType>
			<xs:all>
				<xs:element name="application" minOccurs="1" maxOccurs="1">
					<xs:complexType>
						<xs:all>
							<xs:element name="name" type="xs:string" minOccurs="1" maxOccurs="1" />
							<xs:element name="identifie" type="xs:string"  minOccurs="1" maxOccurs="1" />
							<xs:element name="version" type="xs:string"  minOccurs="1" maxOccurs="1" />
							<xs:element name="type" type="xs:string"  minOccurs="1" maxOccurs="1" />
							<xs:element name="ability" type="xs:string"  minOccurs="1" maxOccurs="1" />
							<xs:element name="description" type="xs:string"  minOccurs="1" maxOccurs="1" />
							<xs:element name="author" type="xs:string"  minOccurs="1" maxOccurs="1" />
							<xs:element name="url" type="xs:string"  minOccurs="1" maxOccurs="1" />
						</xs:all>
						<xs:attribute name="setting" type="xs:boolean" />
					</xs:complexType>
				</xs:element>
				<xs:element name="platform" minOccurs="0" maxOccurs="1">
					<xs:complexType>
						<xs:all>
							<xs:element name="subscribes" minOccurs="0" maxOccurs="1">
								<xs:complexType>
									<xs:sequence>
										<xs:element ref="message" minOccurs="0" maxOccurs="unbounded" />
									</xs:sequence>
								</xs:complexType>
							</xs:element>
							<xs:element name="handles" minOccurs="0" maxOccurs="1">
								<xs:complexType>
									<xs:sequence>
										<xs:element ref="message" minOccurs="0" maxOccurs="unbounded" />
									</xs:sequence>
								</xs:complexType>
							</xs:element>
							<xs:element name="rule" minOccurs="0" maxOccurs="1">
								<xs:complexType>
									<xs:attribute name="embed" type="xs:boolean" />
								</xs:complexType>
							</xs:element>
							<xs:element name="card" minOccurs="0" maxOccurs="1">
								<xs:complexType>
									<xs:attribute name="embed" type="xs:boolean" />
								</xs:complexType>
							</xs:element>
						</xs:all>
					</xs:complexType>
				</xs:element>
				<xs:element name="bindings" minOccurs="0" maxOccurs="1">
					<xs:complexType>
						<xs:all>
							<xs:element name="cover" minOccurs="0" maxOccurs="1">
								<xs:complexType>
									<xs:sequence>
										<xs:element ref="entry" minOccurs="0" maxOccurs="unbounded" />
									</xs:sequence>
									<xs:attribute name="call" type="xs:string" />
								</xs:complexType>
							</xs:element>
							<xs:element name="rule" minOccurs="0" maxOccurs="1">
								<xs:complexType>
									<xs:sequence>
										<xs:element ref="entry" minOccurs="0" maxOccurs="unbounded" />
									</xs:sequence>
									<xs:attribute name="call" type="xs:string" />
								</xs:complexType>
							</xs:element>
							<xs:element name="menu" minOccurs="0" maxOccurs="1">
								<xs:complexType>
									<xs:sequence>
										<xs:element ref="entry" minOccurs="0" maxOccurs="unbounded" />
									</xs:sequence>
									<xs:attribute name="call" type="xs:string" />
								</xs:complexType>
							</xs:element>
							<xs:element name="home" minOccurs="0" maxOccurs="1">
								<xs:complexType>
									<xs:sequence>
										<xs:element ref="entry" minOccurs="0" maxOccurs="unbounded" />
									</xs:sequence>
									<xs:attribute name="call" type="xs:string" />
								</xs:complexType>
							</xs:element>
							<xs:element name="profile" minOccurs="0" maxOccurs="1">
								<xs:complexType>
									<xs:sequence>
										<xs:element ref="entry" minOccurs="0" maxOccurs="unbounded" />
									</xs:sequence>
									<xs:attribute name="call" type="xs:string" />
								</xs:complexType>
							</xs:element>
							<xs:element name="shortcut" minOccurs="0" maxOccurs="1">
								<xs:complexType>
									<xs:sequence>
										<xs:element ref="entry" minOccurs="0" maxOccurs="unbounded" />
									</xs:sequence>
									<xs:attribute name="call" type="xs:string" />
								</xs:complexType>
							</xs:element>
							<xs:element name="function" minOccurs="0" maxOccurs="1">
								<xs:complexType>
									<xs:sequence>
										<xs:element ref="entry" minOccurs="0" maxOccurs="unbounded" />
									</xs:sequence>
									<xs:attribute name="call" type="xs:string" />
								</xs:complexType>
							</xs:element>
						</xs:all>
					</xs:complexType>
				</xs:element>
				<xs:element name="permissions" minOccurs="0" maxOccurs="1">
					<xs:complexType>
						<xs:sequence>
							<xs:element ref="entry" minOccurs="0" maxOccurs="unbounded" />
						</xs:sequence>
					</xs:complexType>
				</xs:element>
				<xs:element name="crons" minOccurs="0" maxOccurs="1">
					<xs:complexType>
						<xs:sequence>
							<xs:element name="item" minOccurs="0" maxOccurs="unbounded">
								<xs:complexType>
									<xs:sequence>
										<xs:element ref="dl" minOccurs="0" maxOccurs="unbounded" />
									</xs:sequence>
								</xs:complexType>
							</xs:element>
						</xs:sequence>
					</xs:complexType>
				</xs:element>
				<xs:element name="install" type="xs:string" minOccurs="0" maxOccurs="1" />
				<xs:element name="uninstall" type="xs:string" minOccurs="0" maxOccurs="1" />
				<xs:element name="upgrade" type="xs:string" minOccurs="0" maxOccurs="1" />
			</xs:all>
			<xs:attribute name="versionCode" type="xs:string" />
		</xs:complexType>
	</xs:element>
</xs:schema>
TPL;
	}else if(strcasecmp(ADDONS_URL,'http://v2.addons.weizancms.com')==0) {
			$xsd = <<<TPL
<?xml version="1.0" encoding="utf-8"?>
<xs:schema xmlns='http://www.weizancms.com' targetNamespace='http://www.weizancms.com' xmlns:xs="http://www.w3.org/2001/XMLSchema" elementFormDefault="qualified">
	<xs:element name="entry">
		<xs:complexType>
			<xs:attribute name="title" type="xs:string" />
			<xs:attribute name="do" type="xs:string" />
			<xs:attribute name="direct" type="xs:boolean" />
			<xs:attribute name="state" type="xs:string" />
		</xs:complexType>
	</xs:element>
	<xs:element name="dl">
		<xs:complexType>
			<xs:attribute name="name" type="xs:string" />
			<xs:attribute name="value" type="xs:string" />
		</xs:complexType>
	</xs:element>
	<xs:element name="message">
		<xs:complexType>
			<xs:attribute name="type" type="xs:string" />
		</xs:complexType>
	</xs:element>
	<xs:element name="manifest">
		<xs:complexType>
			<xs:all>
				<xs:element name="application" minOccurs="1" maxOccurs="1">
					<xs:complexType>
						<xs:all>
							<xs:element name="name" type="xs:string" minOccurs="1" maxOccurs="1" />
							<xs:element name="identifie" type="xs:string"  minOccurs="1" maxOccurs="1" />
							<xs:element name="version" type="xs:string"  minOccurs="1" maxOccurs="1" />
							<xs:element name="type" type="xs:string"  minOccurs="1" maxOccurs="1" />
							<xs:element name="ability" type="xs:string"  minOccurs="1" maxOccurs="1" />
							<xs:element name="description" type="xs:string"  minOccurs="1" maxOccurs="1" />
							<xs:element name="author" type="xs:string"  minOccurs="1" maxOccurs="1" />
							<xs:element name="url" type="xs:string"  minOccurs="1" maxOccurs="1" />
						</xs:all>
						<xs:attribute name="setting" type="xs:boolean" />
					</xs:complexType>
				</xs:element>
				<xs:element name="platform" minOccurs="0" maxOccurs="1">
					<xs:complexType>
						<xs:all>
							<xs:element name="subscribes" minOccurs="0" maxOccurs="1">
								<xs:complexType>
									<xs:sequence>
										<xs:element ref="message" minOccurs="0" maxOccurs="unbounded" />
									</xs:sequence>
								</xs:complexType>
							</xs:element>
							<xs:element name="handles" minOccurs="0" maxOccurs="1">
								<xs:complexType>
									<xs:sequence>
										<xs:element ref="message" minOccurs="0" maxOccurs="unbounded" />
									</xs:sequence>
								</xs:complexType>
							</xs:element>
							<xs:element name="rule" minOccurs="0" maxOccurs="1">
								<xs:complexType>
									<xs:attribute name="embed" type="xs:boolean" />
								</xs:complexType>
							</xs:element>
							<xs:element name="card" minOccurs="0" maxOccurs="1">
								<xs:complexType>
									<xs:attribute name="embed" type="xs:boolean" />
								</xs:complexType>
							</xs:element>
						</xs:all>
					</xs:complexType>
				</xs:element>
				<xs:element name="bindings" minOccurs="0" maxOccurs="1">
					<xs:complexType>
						<xs:all>
							<xs:element name="cover" minOccurs="0" maxOccurs="1">
								<xs:complexType>
									<xs:sequence>
										<xs:element ref="entry" minOccurs="0" maxOccurs="unbounded" />
									</xs:sequence>
									<xs:attribute name="call" type="xs:string" />
								</xs:complexType>
							</xs:element>
							<xs:element name="rule" minOccurs="0" maxOccurs="1">
								<xs:complexType>
									<xs:sequence>
										<xs:element ref="entry" minOccurs="0" maxOccurs="unbounded" />
									</xs:sequence>
									<xs:attribute name="call" type="xs:string" />
								</xs:complexType>
							</xs:element>
							<xs:element name="menu" minOccurs="0" maxOccurs="1">
								<xs:complexType>
									<xs:sequence>
										<xs:element ref="entry" minOccurs="0" maxOccurs="unbounded" />
									</xs:sequence>
									<xs:attribute name="call" type="xs:string" />
								</xs:complexType>
							</xs:element>
							<xs:element name="home" minOccurs="0" maxOccurs="1">
								<xs:complexType>
									<xs:sequence>
										<xs:element ref="entry" minOccurs="0" maxOccurs="unbounded" />
									</xs:sequence>
									<xs:attribute name="call" type="xs:string" />
								</xs:complexType>
							</xs:element>
							<xs:element name="profile" minOccurs="0" maxOccurs="1">
								<xs:complexType>
									<xs:sequence>
										<xs:element ref="entry" minOccurs="0" maxOccurs="unbounded" />
									</xs:sequence>
									<xs:attribute name="call" type="xs:string" />
								</xs:complexType>
							</xs:element>
							<xs:element name="shortcut" minOccurs="0" maxOccurs="1">
								<xs:complexType>
									<xs:sequence>
										<xs:element ref="entry" minOccurs="0" maxOccurs="unbounded" />
									</xs:sequence>
									<xs:attribute name="call" type="xs:string" />
								</xs:complexType>
							</xs:element>
							<xs:element name="function" minOccurs="0" maxOccurs="1">
								<xs:complexType>
									<xs:sequence>
										<xs:element ref="entry" minOccurs="0" maxOccurs="unbounded" />
									</xs:sequence>
									<xs:attribute name="call" type="xs:string" />
								</xs:complexType>
							</xs:element>
						</xs:all>
					</xs:complexType>
				</xs:element>
				<xs:element name="permissions" minOccurs="0" maxOccurs="1">
					<xs:complexType>
						<xs:sequence>
							<xs:element ref="entry" minOccurs="0" maxOccurs="unbounded" />
						</xs:sequence>
					</xs:complexType>
				</xs:element>
				<xs:element name="crons" minOccurs="0" maxOccurs="1">
					<xs:complexType>
						<xs:sequence>
							<xs:element name="item" minOccurs="0" maxOccurs="unbounded">
								<xs:complexType>
									<xs:sequence>
										<xs:element ref="dl" minOccurs="0" maxOccurs="unbounded" />
									</xs:sequence>
								</xs:complexType>
							</xs:element>
						</xs:sequence>
					</xs:complexType>
				</xs:element>
				<xs:element name="install" type="xs:string" minOccurs="0" maxOccurs="1" />
				<xs:element name="uninstall" type="xs:string" minOccurs="0" maxOccurs="1" />
				<xs:element name="upgrade" type="xs:string" minOccurs="0" maxOccurs="1" />
			</xs:all>
			<xs:attribute name="versionCode" type="xs:string" />
		</xs:complexType>
	</xs:element>
</xs:schema>
TPL;
	}else{
	$xsd = <<<TPL
<?xml version="1.0" encoding="utf-8"?>
<xs:schema xmlns='http://www.012wz.com' targetNamespace='http://www.012wz.com' xmlns:xs="http://www.w3.org/2001/XMLSchema" elementFormDefault="qualified">
	<xs:element name="entry">
		<xs:complexType>
			<xs:attribute name="title" type="xs:string" />
			<xs:attribute name="do" type="xs:string" />
			<xs:attribute name="direct" type="xs:boolean" />
			<xs:attribute name="state" type="xs:string" />
		</xs:complexType>
	</xs:element>
	<xs:element name="dl">
		<xs:complexType>
			<xs:attribute name="name" type="xs:string" />
			<xs:attribute name="value" type="xs:string" />
		</xs:complexType>
	</xs:element>
	<xs:element name="message">
		<xs:complexType>
			<xs:attribute name="type" type="xs:string" />
		</xs:complexType>
	</xs:element>
	<xs:element name="manifest">
		<xs:complexType>
			<xs:all>
				<xs:element name="application" minOccurs="1" maxOccurs="1">
					<xs:complexType>
						<xs:all>
							<xs:element name="name" type="xs:string" minOccurs="1" maxOccurs="1" />
							<xs:element name="identifie" type="xs:string"  minOccurs="1" maxOccurs="1" />
							<xs:element name="version" type="xs:string"  minOccurs="1" maxOccurs="1" />
							<xs:element name="type" type="xs:string"  minOccurs="1" maxOccurs="1" />
							<xs:element name="ability" type="xs:string"  minOccurs="1" maxOccurs="1" />
							<xs:element name="description" type="xs:string"  minOccurs="1" maxOccurs="1" />
							<xs:element name="author" type="xs:string"  minOccurs="1" maxOccurs="1" />
							<xs:element name="url" type="xs:string"  minOccurs="1" maxOccurs="1" />
						</xs:all>
						<xs:attribute name="setting" type="xs:boolean" />
					</xs:complexType>
				</xs:element>
				<xs:element name="platform" minOccurs="0" maxOccurs="1">
					<xs:complexType>
						<xs:all>
							<xs:element name="subscribes" minOccurs="0" maxOccurs="1">
								<xs:complexType>
									<xs:sequence>
										<xs:element ref="message" minOccurs="0" maxOccurs="unbounded" />
									</xs:sequence>
								</xs:complexType>
							</xs:element>
							<xs:element name="handles" minOccurs="0" maxOccurs="1">
								<xs:complexType>
									<xs:sequence>
										<xs:element ref="message" minOccurs="0" maxOccurs="unbounded" />
									</xs:sequence>
								</xs:complexType>
							</xs:element>
							<xs:element name="rule" minOccurs="0" maxOccurs="1">
								<xs:complexType>
									<xs:attribute name="embed" type="xs:boolean" />
								</xs:complexType>
							</xs:element>
							<xs:element name="card" minOccurs="0" maxOccurs="1">
								<xs:complexType>
									<xs:attribute name="embed" type="xs:boolean" />
								</xs:complexType>
							</xs:element>
						</xs:all>
					</xs:complexType>
				</xs:element>
				<xs:element name="bindings" minOccurs="0" maxOccurs="1">
					<xs:complexType>
						<xs:all>
							<xs:element name="cover" minOccurs="0" maxOccurs="1">
								<xs:complexType>
									<xs:sequence>
										<xs:element ref="entry" minOccurs="0" maxOccurs="unbounded" />
									</xs:sequence>
									<xs:attribute name="call" type="xs:string" />
								</xs:complexType>
							</xs:element>
							<xs:element name="rule" minOccurs="0" maxOccurs="1">
								<xs:complexType>
									<xs:sequence>
										<xs:element ref="entry" minOccurs="0" maxOccurs="unbounded" />
									</xs:sequence>
									<xs:attribute name="call" type="xs:string" />
								</xs:complexType>
							</xs:element>
							<xs:element name="menu" minOccurs="0" maxOccurs="1">
								<xs:complexType>
									<xs:sequence>
										<xs:element ref="entry" minOccurs="0" maxOccurs="unbounded" />
									</xs:sequence>
									<xs:attribute name="call" type="xs:string" />
								</xs:complexType>
							</xs:element>
							<xs:element name="home" minOccurs="0" maxOccurs="1">
								<xs:complexType>
									<xs:sequence>
										<xs:element ref="entry" minOccurs="0" maxOccurs="unbounded" />
									</xs:sequence>
									<xs:attribute name="call" type="xs:string" />
								</xs:complexType>
							</xs:element>
							<xs:element name="profile" minOccurs="0" maxOccurs="1">
								<xs:complexType>
									<xs:sequence>
										<xs:element ref="entry" minOccurs="0" maxOccurs="unbounded" />
									</xs:sequence>
									<xs:attribute name="call" type="xs:string" />
								</xs:complexType>
							</xs:element>
							<xs:element name="shortcut" minOccurs="0" maxOccurs="1">
								<xs:complexType>
									<xs:sequence>
										<xs:element ref="entry" minOccurs="0" maxOccurs="unbounded" />
									</xs:sequence>
									<xs:attribute name="call" type="xs:string" />
								</xs:complexType>
							</xs:element>
							<xs:element name="function" minOccurs="0" maxOccurs="1">
								<xs:complexType>
									<xs:sequence>
										<xs:element ref="entry" minOccurs="0" maxOccurs="unbounded" />
									</xs:sequence>
									<xs:attribute name="call" type="xs:string" />
								</xs:complexType>
							</xs:element>
						</xs:all>
					</xs:complexType>
				</xs:element>
				<xs:element name="permissions" minOccurs="0" maxOccurs="1">
					<xs:complexType>
						<xs:sequence>
							<xs:element ref="entry" minOccurs="0" maxOccurs="unbounded" />
						</xs:sequence>
					</xs:complexType>
				</xs:element>
				<xs:element name="crons" minOccurs="0" maxOccurs="1">
					<xs:complexType>
						<xs:sequence>
							<xs:element name="item" minOccurs="0" maxOccurs="unbounded">
								<xs:complexType>
									<xs:sequence>
										<xs:element ref="dl" minOccurs="0" maxOccurs="unbounded" />
									</xs:sequence>
								</xs:complexType>
							</xs:element>
						</xs:sequence>
					</xs:complexType>
				</xs:element>
				<xs:element name="install" type="xs:string" minOccurs="0" maxOccurs="1" />
				<xs:element name="uninstall" type="xs:string" minOccurs="0" maxOccurs="1" />
				<xs:element name="upgrade" type="xs:string" minOccurs="0" maxOccurs="1" />
			</xs:all>
			<xs:attribute name="versionCode" type="xs:string" />
		</xs:complexType>
	</xs:element>
</xs:schema>
TPL;
	}
	return trim($xsd);
}


function ext_template_manifest($tpl, $cloud = true) {
	$filename = IA_ROOT . '/app/themes/' . $tpl . '/manifest.xml';
	if (!file_exists($filename)) {
		if ($cloud) {
			load()->model('cloud');
			$manifest = cloud_t_info($tpl);
		}
		return is_error($manifest) ? array() : $manifest;
	}
	$manifest = ext_template_manifest_parse(file_get_contents($filename));
	if (empty($manifest['name']) || $manifest['name'] != $tpl) {
		return array();
	}
	return $manifest;
}


function ext_template_manifest_parse($xml) {
	$xml = str_replace(array('&'), array('&amp;'), $xml);
	$xml = @isimplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
	if (empty($xml)) {
		return array();
	}
	$manifest['name'] = strval($xml->identifie);
	$manifest['title'] = strval($xml->title);
	if (empty($manifest['title'])) {
		return array();
	}
	$manifest['type'] = !empty($xml->type) ? strval($xml->type) : 'other';
	$manifest['description'] = strval($xml->description);
	$manifest['author'] = strval($xml->author);
	$manifest['url'] = strval($xml->url);
	if (isset($xml->sections)) {
		$manifest['sections'] = strval($xml->sections);
	}
	if ($xml->settings->item) {
		foreach ($xml->settings->item as $msg) {
			$attrs = $msg->attributes();
			$manifest['settings'][] = array('key' => trim(strval($attrs['variable'])), 'value' => trim(strval($attrs['content'])), 'desc' => trim(strval($attrs['description'])));
		}
	}
	return $manifest;
}


function ext_webtheme_manifest($tpl, $cloud = true) {
	$filename = IA_ROOT . '/web/themes/' . $tpl . '/manifest.xml';
	if (!file_exists($filename)) {
		if ($cloud) {
			load()->model('cloud');
			$manifest = cloud_w_info($tpl);
		}
		return is_error($manifest) ? array() : $manifest;
	}
	$manifest = ext_template_manifest_parse(file_get_contents($filename));
	if (empty($manifest['name']) || $manifest['name'] != $tpl) {
		return array();
	}
	return $manifest;
}


function ext_webtheme_manifest_parse($xml) {
	$xml = str_replace(array('&'), array('&amp;'), $xml);
	$xml = @isimplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
	if (empty($xml)) {
		return array();
	}
	$manifest['name'] = strval($xml->identifie);
	$manifest['title'] = strval($xml->title);
	if (empty($manifest['title'])) {
		return array();
	}
	$manifest['type'] = !empty($xml->type) ? strval($xml->type) : 'other';
	$manifest['description'] = strval($xml->description);
	$manifest['author'] = strval($xml->author);
	$manifest['url'] = strval($xml->url);
	return $manifest;
}
function ext_template_type() {
	static $types = array(
		'often' => array(
			'name' => 'often',
			'title' => '常用模板',
		),
		'rummery' => array(
			'name' => 'rummery',
			'title' => '酒店',
		),
		'car' => array(
			'name' => 'car',
			'title' => '汽车',
		),
		'tourism' => array(
			'name' => 'tourism',
			'title' => '旅游',
		),
		'drink' => array(
			'name' => 'drink',
			'title' => '餐饮',
		),
		'realty' => array(
			'name' => 'realty',
			'title' => '房地产',
		),
		'medical' => array(
			'name' => 'medical',
			'title' => '医疗保健'
		),
		'education' => array(
			'name' => 'education',
			'title' => '教育'
		),
		'cosmetology' => array(
			'name' => 'cosmetology',
			'title' => '健身美容'
		),
		'shoot' => array(
			'name' => 'shoot',
			'title' => '婚纱摄影'
		),
		'other' => array(
			'name' => 'other',
			'title' => '其它行业'
		)
	);
	return $types;
}


function ext_webtheme_type() {
	static $types = array(
		'often' => array(
			'name' => 'often',
			'title' => '常用模板',
		),
		'rummery' => array(
			'name' => 'rummery',
			'title' => '酒店',
		),
		'car' => array(
			'name' => 'car',
			'title' => '汽车',
		),
		'tourism' => array(
			'name' => 'tourism',
			'title' => '旅游',
		),
		'drink' => array(
			'name' => 'drink',
			'title' => '餐饮',
		),
		'realty' => array(
			'name' => 'realty',
			'title' => '房地产',
		),
		'medical' => array(
			'name' => 'medical',
			'title' => '医疗保健'
		),
		'education' => array(
			'name' => 'education',
			'title' => '教育'
		),
		'cosmetology' => array(
			'name' => 'cosmetology',
			'title' => '健身美容'
		),
		'shoot' => array(
			'name' => 'shoot',
			'title' => '婚纱摄影'
		),
		'other' => array(
			'name' => 'other',
			'title' => '其它行业'
		)
	);
	return $types;
}

function ext_module_script_clean($modulename, $manifest) {
	$moduleDir = IA_ROOT . '/addons/' . $modulename . '/';
	$manifest['install'] = trim($manifest['install']);
	$manifest['uninstall'] = trim($manifest['uninstall']);
	$manifest['upgrade'] = trim($manifest['upgrade']);
	if (strexists($manifest['install'], '.php')) {
		if (file_exists($moduleDir . $manifest['install'])) {
			unlink($moduleDir . $manifest['install']);
		}
	}
	if (strexists($manifest['uninstall'], '.php')) {
		if (file_exists($moduleDir . $manifest['uninstall'])) {
			unlink($moduleDir . $manifest['uninstall']);
		}
	}
	if (strexists($manifest['upgrade'], '.php')) {
		if (file_exists($moduleDir . $manifest['upgrade'])) {
			unlink($moduleDir . $manifest['upgrade']);
		}
	}
	if (file_exists($moduleDir . 'manifest.xml')) {
		unlink($moduleDir . 'manifest.xml');
	}
}


function ext_module_msg_types() {
	$mtypes = array();
	$mtypes['text'] = '文本消息(重要)';
	$mtypes['image'] = '图片消息';
	$mtypes['voice'] = '语音消息';
	$mtypes['video'] = '视频消息';
	$mtypes['shortvideo'] = '小视频消息';
	$mtypes['location'] = '位置消息';
	$mtypes['link'] = '链接消息';
	$mtypes['subscribe'] = '粉丝开始关注';
	$mtypes['unsubscribe'] = '粉丝取消关注';
	$mtypes['qr'] = '扫描二维码';
	$mtypes['trace'] = '追踪地理位置';
	$mtypes['click'] = '点击菜单(模拟关键字)';
	$mtypes['view'] = '点击菜单(链接)';
	$mtypes['merchant_order'] = '微小店消息';
	$mtypes['user_get_card'] = '用户领取卡券事件';
	$mtypes['user_del_card'] = '用户删除卡券事件';
	$mtypes['user_consume_card'] = '用户核销卡券事件';
	return $mtypes;
}

function ext_check_module_subscribe($modulename) {
	global $_W, $_GPC;
	if (empty($modulename)) {
		return true;
	}
	if (!is_array($_W['setting']['module_receive_ban'])) {
		$_W['setting']['module_receive_ban'] = array();
	}
	load()->func('communication');
	$response = ihttp_request($_W['siteroot'] . 'web/' .  url('utility/modules/check_receive', array('module_name' => $modulename)));
	$response['content'] = json_decode($response['content'], true);
	if (empty($response['content']['message']['errno'])) {
		unset($_W['setting']['module_receive_ban'][$modulename]);
		$module_subscribe_success = true;
	} else {
		$_W['setting']['module_receive_ban'][$modulename] = $modulename;
		$module_subscribe_success = false;
	}
	setting_save($_W['setting']['module_receive_ban'], 'module_receive_ban');
	return $module_subscribe_success;
}


function manifest_check($module_name, $manifest) {
	if(is_string($manifest)) {
		return error(1, '模块配置项定义错误, 具体错误内容为: <br />' . $manifest);
	}
	if(empty($manifest['application']['name'])) {
		return error(1, '模块名称未定义. ');
	}
	if(empty($manifest['application']['identifie']) || !preg_match('/^[a-z][a-z\d_]+$/i', $manifest['application']['identifie'])) {
		return error(1, '模块标识符未定义或格式错误(仅支持字母和数字, 且只能以字母开头).');
	}
	if(strtolower($module_name) != strtolower($manifest['application']['identifie'])) {
		return error(1, '模块名称定义与模块路径名称定义不匹配. ');
	}
	if(empty($manifest['application']['version']) || !preg_match('/^[\d\.]+$/i', $manifest['application']['version'])) {
		return error(1, '模块版本号未定义(仅支持数字和句点). ');
	}
	if(empty($manifest['application']['ability'])) {
		return error(1, '模块功能简述未定义. ');
	}
	if($manifest['platform']['isrulefields'] && !in_array('text', $manifest['platform']['handles'])) {
		return error(1, '模块功能定义错误, 嵌入规则必须要能够处理文本类型消息.. ');
	}
	if((!empty($manifest['cover']) || !empty($manifest['rule'])) && !$manifest['platform']['isrulefields']) {
		return error(1, '模块功能定义错误, 存在封面或规则功能入口绑定时, 必须要嵌入规则. ');
	}
	global $points;
	if (!empty($points)) {
		foreach($points as $name => $point) {
			if(is_array($manifest[$name])) {
				foreach($manifest[$name] as $menu) {
					if(trim($menu['title']) == ''  || !preg_match('/^[a-z\d]+$/i', $menu['do']) && empty($menu['call'])) {
						return error(1, $point['title'] . ' 扩展项功能入口定义错误, (操作标题[title], 入口方法[do])格式不正确.');
					}
				}
			}
		}
	}
		if(is_array($manifest['permissions']) && !empty($manifest['permissions'])) {
		foreach($manifest['permissions'] as $permission) {
			if(trim($permission['title']) == ''  || !preg_match('/^[a-z\d_]+$/i', $permission['permission'])) {
				return error(1, "名称为： {$permission['title']} 的权限标识格式不正确,请检查标识名称或标识格式是否正确");
			}
		}
	}
	if(!is_array($manifest['versions'])) {
		return error(1, '兼容版本格式错误');
	}
	return error(0);
}