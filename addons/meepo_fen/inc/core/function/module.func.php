<?php
/**
* @安装插件
*/
function insert_plugin($config){
	pdo_insert('meepo_common_plugin',$config);
	$id = pdo_insertid();
	return $id;
}
/**
* @安装插件菜单
*/
function insert_plugin_menu($menu){
	pdo_insert('meepo_common_menu',$menu);
	$id = pdo_insertid();
	return $id;
}
/**
* @安装插件项目
*/
function insert_plugin_items($item){
	pdo_insert('meepo_common_menu_items',$item);
	$id = pdo_insertid();
	return $id;
}
/**
* @插件导航
*/
function insert_plugin_navs($nav){
  pdo_insert('meepo_common_plugin_navs',$nav);
  return pdo_insertid();
}
/**
* @插件表单
*/
function insert_plugin_tpl($tpl){
  pdo_insert('meepo_common_plugin_tpl',$tpl);
  return pdo_insertid();
}
/**
* @插件表头
*/
function insert_plugin_ths($th){
  pdo_insert('meepo_common_plugin_ths',$th);
  return pdo_insertid();
}
/**
* @插件搜索
*/
function insert_plugin_search($search){
  pdo_insert('meepo_common_plugin_search',$search);
  return pdo_insertid();
}

/**
* @添加模块menu
*/
function insert_menu($module,$do,$title){
	$sql = "SELECT * FROM ".tablename('modules_bindings')." WHERE module = :module AND do = :do AND entry = :entry";
	$params = array(':module'=>$module,':do'=>$do,':entry'=>'menu');
	$is = pdo_fetch($sql,$params);
	if(empty($is)){
		pdo_insert('modules_bindings',array('module'=>$module,'do'=>$do,'title'=>$title,'entry'=>'menu'));
	}
}
/**
* @添加模块cover
*/
function insert_cover($module,$do,$title){
	$sql = "SELECT * FROM ".tablename('modules_bindings')." WHERE module = :module AND do = :do AND entry = :entry";
	$params = array(':module'=>$module,':do'=>$do,':entry'=>'cover');
	$is = pdo_fetch($sql,$params);
	if(empty($is)){
		pdo_insert('modules_bindings',array('module'=>$module,'do'=>$do,'title'=>$title,'entry'=>'cover'));
	}
}
/**
* @删除模块menu
*/
function delete_menu($module,$do){
	$sql = "SELECT * FROM ".tablename('modules_bindings')." WHERE module = :module AND do = :do AND entry = :entry";
	$params = array(':module'=>$module,':do'=>$do,':entry'=>'menu');
	$is = pdo_fetch($sql,$params);
	if(!empty($is)){
		pdo_delete('modules_bindings',array('module'=>$module,'do'=>$do,'entry'=>'menu'));
	}
}
/**
* @删除模块cover
*/
function delete_cover($module,$do){
	$sql = "SELECT * FROM ".tablename('modules_bindings')." WHERE module = :module AND do = :do AND entry = :entry";
	$params = array(':module'=>$module,':do'=>$do,':entry'=>'cover');
	$is = pdo_fetch($sql,$params);
	if(!empty($is)){
		pdo_delete('modules_bindings',array('module'=>$module,'do'=>$do,'entry'=>'cover'));
	}
}
/**
* @更新模块配置
*/
function update_modules($module,$data,$fields){
	$handles = serialize($data);
	return pdo_update('modules',array($fields=>$handles),array('name'=>$module));
}
