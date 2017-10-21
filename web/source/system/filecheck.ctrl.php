<?php

load()->func('file');
load()->model('cloud');
load()->func('communication');
$pars = array();
$pars['host'] = $_SERVER['HTTP_HOST'];
$pars['family'] = IMS_FAMILY;
$pars['version'] = IMS_VERSION;
$pars['release'] = IMS_RELEASE_DATE;
$pars['key'] = $_W['setting']['site']['key'];
$pars['password'] = md5($_W['setting']['site']['key'] . $_W['setting']['site']['token']);
if ($do == 'check') {
	$filetree = file_tree(IA_ROOT,array('/addons','/data','/attachment','/app/themes'),array('.php','.js','.html'));
	$extra1=file_tree(IA_ROOT.'/app/themes/default',array(),array('.php','.js','.html'),IA_ROOT);
	$extra2=file_tree(IA_ROOT.'/app/themes/quick',array(),array('.php','.js','.html'),IA_ROOT);
	$filetree = array_merge($filetree,$extra1,$extra2);
	$modify = array();
	$unknown = array();
	$lose = array();
	$pars['method'] = 'application1.build';
    $dat1 = cloud_request(ADDONS_URL.'/gateway.php', $pars);
    $dat = unserialize(base64_decode($dat1['content']));
    if($dat['state']){
	    message($dat['content']);
    }
	if(empty($dat) || empty($dat1['content']) || empty($dat['files']) || empty($dat['schemas'])){
		message('没有收到服务器传输的数据！无法进行文件校验！');
	}
	unset($dat1);
	foreach ($dat['files'] as $value) {
		$clouds[$value['path']]['path'] = $value['path'];
		$clouds[$value['path']]['checksum'] = $value['checksum'];
	}
	foreach ($filetree as $filename) {
		if (!empty($clouds[$filename])) {
			if (md5_file(IA_ROOT.$filename) != $clouds[$filename]['checksum']) {
				$modify[] = $filename;
			}
		} else {
			$unknown[] = $filename;
		}
	}
	$count_unknown = count($unknown);
	$count_modify = count($modify);
}
//以下为模块文件校验，暂不使用
if ($do == 'checkmodule') {
	$module=trim($_GPC['name']);
	$pars['module'] = $module;
	$pars['method'] = 'module1.build';
	$dat1 = cloud_request(ADDONS_URL.'/gateway.php', $pars);
	$dat = unserialize(base64_decode($dat1['content']));
    if(empty($dat['files']) || empty($dat1['content'])){
		message('没有收到服务器传输的数据！无法进行校验');
	}
	if($dat['state']){
	    message($dat['content']);
    }
	$clouds=array();
	foreach($dat['files'] as $file){
		$clouds[$file['path']]=$file['checksum'];
	}
	$locals=file_tree(IA_ROOT.'/addons/'.$module,array(),array('.php'));
	$unknown=$modify=array();
	foreach($locals as $local){
		if(empty($clouds[$local])){
			$unknown[]=$local;
		}elseif(md5_file(IA_ROOT.'/addons/'.$module.$local) != $clouds[$local]){
			$modify[]=$local;
		}
	}
	$count_unknown = count($unknown);
	$count_modify = count($modify);
}
template('system/filecheck');