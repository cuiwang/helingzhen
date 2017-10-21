<?php
global $_W, $_GPC;
if(!$_W['isfounder']){

    message('非超级管理员无权访问!');

}
$op =empty($_GPC['op'])? 'display' : $_GPC['op'];

load()->func('communication');
load()->func('file');

load()->func('db');
load()->model('setting');
load()->func('communication');


message('暂不支持在线更新，客服QQ151619143','','error');

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

function getAuthSet(){
	global $_W;
	$sql = "SELECT * FROM ".tablename('jy_qingsongchou_mitu_setting')." WHERE code = :code";
	$params = array(':code'=>'auth');
	$setting = pdo_fetch($sql,$params);
	$item = iunserializer($item['value']);
	return $item['code'];
}
/*
 * 结构转数组
 * */
function cloud_object_array($array) {
	if(is_object($array)) {
		$array = (array)$array;
	} if(is_array($array)) {
		foreach($array as $key=>$value) {
			$array[$key] = cloud_object_array($value);
		}
	}
	return $array;
}