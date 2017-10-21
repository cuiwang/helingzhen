<?php 
	global $_W,$_GPC;
	
	if(!$_W['isajax']){
		die('页面不存在');
	}
	
	$res = pdo_update('zofui_hby_user',array('times' => $_GPC['times']-1),array('id'=>$_GPC['id']));
	