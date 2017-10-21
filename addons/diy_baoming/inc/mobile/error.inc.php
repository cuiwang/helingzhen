<?php

	global $_W,$_GPC;
    $weid=$_W['uniacid'];
    $userinfo=getFromUser($this->settings,$this->modulename);
    $userinfo=json_decode($userinfo,true); 
    $from_user=$userinfo['openid'];
    $id=intval($_GPC['id']);
	$op=$_GPC['op'];

	if(empty($id)){
		$msg="你访问的网站找不到了";
		$err_title="温馨提示";
		$label='warn';
		include $this->template('error');
	}
	
	if($op=='pay'){
		$msg="报名支付失败";
		$err_title="报名失败";
		$label='success';
		$redirect=$_W['siteroot'] . 'app/' . substr($this->createMobileUrl('share', array('id' => $id,'sign' => time())), 2);
		include $this->template('error');
	}

