<?php
//检查安装
function init(){
	global $_W;
	if(!pdo_tableexists('meepo_module')){
		$sql = "CREATE TABLE `ims_meepo_module` (
	`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`module` varchar(32) NOT NULL DEFAULT '',
	`set` text NOT NULL,
	`time` int(11) UNSIGNED NOT NULL DEFAULT 0,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM
CHECKSUM=0
DELAY_KEY_WRITE=0;";
		pdo_query($sql);
	}
	
	if(!pdo_tableexists('meepo_message_replace')){
		$sql = "CREATE TABLE `ims_meepo_message_replace` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `replace` varchar(40) DEFAULT NULL,
  `name` varchar(40) DEFAULT NULL,
  `pro` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8";
		pdo_query($sql);
	}
}
//反序列化
function munserializer($value) {
	if (empty($value)) {
		return '';
	}
	if (!is_serialized($value)) {
		if(is_array($value)){
			foreach ($value as $key=>$v){
				$v = munserializer($v);
				$data[$key]=$v;
			}
			return $data;
		}
		return $value;
	}
	$result = iunserializer($value);

	return munserializer($result);
}
//获取验证
function getAuthSet($module){
	global $_W;
	$set =pdo_fetch("SELECT * FROM " . tablename('meepo_module'). " WHERE `module` = '{$module}' limit 1");
	$sets =iunserializer($set['set']);
	if (is_array($sets)){
		return is_array($sets['auth'])? $sets['auth'] : array();
	}
	return array();
}
//便利文件夹
function my_scandir($dir) {
	global $my_scenfiles;
	if ($handle = opendir($dir)) {
		while (($file = readdir($handle)) !== false) {
			if ($file != ".." && $file != ".") {
				if (is_dir($dir . "/" . $file)) {
					my_scandir($dir . "/" . $file);
				} else {
					$my_scenfiles[] = $dir . "/" . $file;
				}
			}
		}
		closedir($handle);
	}
}

function getset($module){
	global $_W;
	$sql = "SELECT * FROM ".tablename('meepo_common_setting')." WHERE uniacid = :uniacid AND module = :module";
	$params = array(':uniacid'=>$_W['uniacid'],':module'=>$module);
	$set = pdo_fetch($sql,$params);
	$setting = munserializer($set['set']);
	return $setting;
}

function updateset($set,$module){
	global $_W;
	$data = serialize($set);
	pdo_update('meepo_common_setting',array('set'=>$data),array('uniacid'=>$_W['uniacid'],'module'=>$module));
	return true;
}

