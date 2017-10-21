<?php
/**
 * [WeiZan System] Copyright (c) 2014 
 * WeiZan is  a free software, it under the license terms, visited http://www./ for more details.
 */
if (!defined('IN_IA')) {exit('Access Denied');}
global $_W, $_GPC;
load()->classs('account');
$result = array(
		'error' => 'error',
		'message' => '',
		'data' => ''
);

if(empty($_W['acid'])){
	$sql = "SELECT acid FROM ".tablename('mc_mapping_fans')." WHERE openid = :openid AND uniacid = :uniacid limit 1";
	$params = array(':openid'=>$_W['openid'],':uniacid'=>$_W['uniacid']);
	$_W['acid'] = pdo_fetchcolumn($sql,$params);
}

if(empty($_W['acid'])){
	$result['message'] = '没有找到相关公众账号';
	die(json_encode($result));
}

$acid = $_W['acid'];
$acc = WeAccount::create($acid);

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'upload';
$type = !empty($_GPC['type'])?$_GPC['type']:'image';
if ($operation == 'upload') {
	if($type == 'image'){
		$serverId = trim($_GPC['serverId']);
		$localId = trim($_GPC['localId']);
		$media = array();
		$media['media_id'] = $serverId;
		$media['type'] = $type;
		
		$result['serverId'] = $serverId;
		$result['localId'] = $localId;
		
		$filename = $acc->downloadMedia($media);
		if(is_error($filename)){
			$result['message'] = '上传失败';
			die(json_encode($result));
		}
		$result['error'] = 'success';
		$result['filename'] = $filename;
		
		$result['path'] = $_W['attachurl'].$filename;
		die(json_encode($result));
	}
} elseif ($operation == 'remove') {
	$file = $_GPC['file'];
	file_delete($file);
	exit(json_encode(array('status'=>true)));
}