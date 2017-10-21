<?php
load()->func('communication');

//检查安装
function cloud_init(){
	global $_W;
	$filename = CORE_PATH.'inc/core/data/install.php';
	if(file_exists($filename)){
		include $filename;
	}
}
//检查验证
function checkAuthSet(){
  $ip = gethostbyname($_SERVER['SERVER_ADDR']);
  $domain = $_SERVER['HTTP_HOST'];
  $setting = setting_load('site');
  $id =isset($setting['site']['key'])? $setting['site']['key'] : '1';
  $resp =ihttp_post(IMEEPOS_URL.'oauth.php',array('ip'=>$ip,'id'=>$id,'domain'=>$domain,'module'=>$this->modulename));
}
//设置验证
function setAuthSet($set,$module){
	global $_W;
	if(empty($set)){
		$set = array();
	}
	$set =pdo_fetch('SELECT * FROM ' . tablename('meepo_module'). ' WHERE module = :module limit 1', array(':module' => $module));
	if (empty($set)){
		pdo_insert('meepo_module', array('set' => iserializer($set), 'module' => $module,'time'=>time()));
	}else{
		pdo_update('meepo_module', array('set' => iserializer($set),'time'=>time()),array('module'=>$module));
	}
	message('系统授权成功！', referer(), 'success');
	return array();
}
