<?php 
	global $_W,$_GPC;
	//$_W['openid'] = 111111;	
	if(empty($_W['openid'])){
		die('您访问的方式不正确');
	}	
	//活动信息
	$asql = "SELECT * FROM " . tablename('zofui_hby_activity') . " WHERE `uniacid` = :uniacid ORDER BY `id` DESC";
	$actinfo = pdo_fetch($asql , array(
		':uniacid' => $_W['uniacid']			
	));
	if(empty($actinfo)){
		die('活动不存在,请先初始化活动');
	}	
	$uid = empty($_GPC['uid'])?9999999:$_GPC['uid'];

	$usql = "SELECT * FROM " . tablename('zofui_hby_user') . " WHERE `id` = :id LIMIT 1";
	$userinfo = pdo_fetch($usql,array(
		':id' => $_GPC['uid']
	));
	
	$sql = "SELECT * FROM " . tablename('zofui_hby_hblog') . " WHERE `uniacid` = :uniacid ORDER BY `id` DESC LIMIT 10";
	$prizeinfo = pdo_fetchall($sql,array(
		':uniacid' => $_W['uniacid']
	));
	

 	foreach((array)$prizeinfo as $k=>$v){
		$sql = "SELECT * FROM " . tablename('zofui_hby_user') . " WHERE `openid` = :openid ORDER BY `id` DESC LIMIT 1";
		$headinfo = pdo_fetch($sql,array(
			':openid' => $v['openid']
		));
		$prizeinfo[$k]['headimg'] = $headinfo['headimgurl'];
		$prizeinfo[$k]['nickname'] = $headinfo['nickname'];	
		$prizeinfo[$k]['time'] = time() - $k*11 - rand(1,22);
	}
	
	

	
	include $this->template('invite');