<?php 
	global $_W,$_GPC;
	//$_W['openid'] = 111111;
	if(empty($_W['openid'])){
		die('您访问的方式不正确,请在微信中打开');
	}
	//活动信息
	$asql = "SELECT * FROM " . tablename('zofui_hby_activity') . " WHERE `uniacid` = :uniacid ORDER BY `id` DESC";
	$actinfo = pdo_fetch($asql , array(
		':uniacid' => $_W['uniacid']			
	));

	if(empty($actinfo)){
		die('活动不存在,请先初始化活动');
	}
	$userinfo = $this->getUserInfo();
	setcookie('flag',999,time()+3600,'/');
	
	$times = ($userinfo['times'] < 0)?$this->module['config']['times']:$userinfo['times'];
	
	$flag = 0;	
	//必须关注才能参与 $flag = 1;
	if($userinfo['subscribe'] == 0 && $this->module['config']['guanzhu'] == 1){
		$flag = 1;
	}
	//已没有奖金了 $flag = 2;
	if($actinfo['lastmoney'] <= 1){
		$flag = 2;		
	}
	$ip = $this->getClientIp();
	$area = $this->getarea($ip);

	if(empty($ip)){
		die('访问出错了，请刷新');
	}
	if(!empty($this->module['config']['limitarea']) && !strpos('flag'.$this->module['config']['limitarea'],$area['city'])){
		$flag = 3;
	}
	
	include $this->template('index');