<?php
	defined('IN_IA') or exit('Access Denied');
	global $_W,$_GPC;
	load()->model('account');
	load()->func('communication');
	
	$uniacccount = WeAccount::create($_W['uniacid']);
	$barcode =array('expire_seconds' => 2292000,'action_name' => 'QR_SCENE','action_info' => array('scene' => array('scene_id'=>intval($_GPC['uid']))));
	
	$res = $uniacccount->barCodeCreateDisposable($barcode);
	if($res['errno'] == -1){
		$uniacccount = WeAccount::create($_W['acid']);
		$barcode =array('expire_seconds' => 2292000,'action_name' => 'QR_SCENE','action_info' => array('scene' => array('scene_id'=>intval($_GPC['uid']))));
		
		$res = $uniacccount->barCodeCreateDisposable($barcode);		
	}
	
	
	$url = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".UrlEncode($res['ticket']);

	$result = ihttp_get($url);
	header("content-type: image/jpeg"); 
	echo $result['content'];