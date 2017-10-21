<?php
global $_W,$_GPC;
mload()->func('tpl');
$act = $_GPC['act'];
//导航栏目
$acts = array(
	array('title'=>'已装插件','act'=>'','id'=>false),
	array('title'=>'更多插件','act'=>'more','id'=>false),
	array('title'=>'设计插件','act'=>'post','id'=>false),
	array('title'=>'插件权限','act'=>'privite','id'=>true),
	array('title'=>'使用记录','act'=>'log','id'=>true),
);

$navs = array();
foreach ($acts as $ac){
	if($act == $ac['act']){
		if($ac['id'] && $_GPC['id']>0){
			$navs[] = array('href'=>$this->createWebUrl('plugin',array('act'=>$ac['act'],'id'=>$_GPC['id'])),'title'=>$ac['title'],'active'=>true);
		}else if(!$ac['id'] && empty($_GPC['id'])){
			$navs[] = array('href'=>$this->createWebUrl('plugin',array('act'=>$ac['act'])),'title'=>$ac['title'],'active'=>true);
		}else if(!$ac['id'] && !empty($_GPC['id'])){
			$navs[] = array('href'=>$this->createWebUrl('plugin',array('act'=>$ac['act'])),'title'=>$ac['title'],'active'=>false);
		}
	}else{
		if($ac['id'] && $_GPC['id']>0){
			$navs[] = array('href'=>$this->createWebUrl('plugin',array('act'=>$ac['act'],'id'=>$_GPC['id'])),'title'=>$ac['title'],'active'=>false);
		}else if(!$ac['id']){
			$navs[] = array('href'=>$this->createWebUrl('plugin',array('act'=>$ac['act'])),'title'=>$ac['title'],'active'=>false);
		}
	}
}

if(empty($act)){
	$lists = $this->getpluginlist();
	foreach ($lists as $li){
		$li['log'] = $this->createWebUrl('plugin',array('act'=>'log','id'=>$li['id']));
		$li['privite'] = $this->createWebUrl('plugin',array('act'=>'privite','id'=>$li['id']));
		$li['uninstall'] = $this->createWebUrl('plugin',array('act'=>'uninstall','code'=>$li['code']));
		$list[] = $li;

	}
}

if($act == 'post'){
	$plugin = get_default($this->modulename);
	$tpl = get_tpl($plugin);
	if($_W['ispost']){
		$data = array();
		$data['title'] = $_GPC['title'];
		$data['code'] = $_GPC['code'];
		$data['version'] = $_GPC['version'];
		$data['author'] = $_GPC['author'];
		$data['module'] = $_GPC['module'];
		$data['fee'] = $_GPC['fee'];
		$data['desc'] = $_GPC['desc'];
	}
}

if($act == 'more'){
	//未安装 插件列表
	$list = array();
	$path = MODULE_ROOT . '/plugin/';
	if (is_dir($path)) {
		if ($handle = opendir($path)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != ".." ) {
					$path2 = $path .'/'.$file;
					if(is_dir($path2)){
						//检查是否安装，是否有更新
						//$plugin = $this->getPluginByCode($file);
						if(empty($plugin)){
							require MODULE_ROOT.'/plugin/'.$file.'/config.php';
							$config['install'] = $this->createWebUrl('plugin',array('act'=>'install','code'=>$file));
							$list[] = $config;
						}
					}
				}
			}
		}
	}
}

if($act == 'install'){
	//安装插件
	$code = trim($_GPC['code']);
	$plugin = $this->getPluginByCode($code);
	if(empty($plugin)){
		$configfile = MODULE_ROOT . '/plugin/'.$code.'/install.php';
		if(file_exists($configfile)){
			require $configfile;
		}
		message('安装完成！',$this->createWebUrl('plugin'),'success');
	}else{
		message('模块已安装！！',$this->createWebUrl('plugin'),'error');
	}
}

if($act == 'uninstall'){
	//卸载插件
	$code = trim($_GPC['code']);
	$plugin = $this->getPluginByCode($code);
	if(!empty($plugin)){
		pdo_delete('meepo_common_plugin',array('module'=>$plugin['module'],'code'=>$plugin['code']));
		pdo_delete('meepo_common_menu',array('module'=>$plugin['module'],'code'=>$plugin['code']));
		message('卸载成功',$this->createWebUrl('plugin'),'success');
	}
}

if($act == 'update'){
	//更新插件
}


include $this->template('plugin');

function get_default($module){
	global $_W,$_GPC;
	$data = array();
	$data['module'] = $module;
	return $data;
}

function get_tpl($project){
	$data = array();
	$data[] = array('type'=>'tpl_text','label'=>'插件标题','display'=>true,'name'=>'title','value'=>$project['title'],'help'=>'模块的名称, 由于显示在用户的模块列表中. 不要超过10个字符');
	$data[] = array('type'=>'tpl_text','label'=>'插件标示','display'=>true,'name'=>'code','value'=>$project['code'],'help'=>'模块标识符, 应对应模块文件夹的名称, 系统系统按照此标识符查找模块定义, 只能由字母数字下划线组成');
	$data[] = array('type'=>'tpl_text','label'=>'插件版本','display'=>true,'name'=>'version','value'=>$project['version'],'help'=>'模块当前版本, 此版本号用于模块的版本更新');

	$data[] = array('type'=>'tpl_text','label'=>'插件作者','display'=>true,'name'=>'author','value'=>$project['author'],'help'=>'模块的作者, 留下你的大名吧');
	$data[] = array('type'=>'tpl_text','label'=>'所属模块','display'=>false,'name'=>'module','value'=>$project['module'],'help'=>'所属模块标示');
	$data[] = array('type'=>'tpl_text','label'=>'插件费用','display'=>true,'name'=>'fee','value'=>$project['fee'],'help'=>'预设价格');
	$data[] = array('type'=>'tpl_edit','label'=>'插件简介','display'=>true,'name'=>'desc','value'=>$project['desc'],'help'=>'模块详细描述, 详细介绍模块的功能和使用方法');
	return $data;
}
