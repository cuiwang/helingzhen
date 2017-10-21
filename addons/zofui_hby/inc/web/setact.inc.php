<?php 
	global $_W,$_GPC;
	
	
	$sql = "SELECT * FROM " . tablename('zofui_hby_activity') . "WHERE `uniacid` = :uniacid ORDER BY `id` DESC";
	
	$actinfo = pdo_fetch($sql,array(':uniacid'=>$_W['uniacid']));
	
	if(checksubmit()){
		if(empty($_GPC['money'])){
			message('请填入红包总计金额','referer',error);
		}
	
		$data = array(
			'uniacid' => $_W['uniacid'],
			'money' => trim($_GPC['money']),
			'time' => time(),
			'lastmoney' => trim($_GPC['money'])	
		);
		$res = pdo_insert('zofui_hby_activity',$data);
		if($res){
			message('初始化成功！',$this->createWebUrl('setact'),'success');
		}else{
			message('失败了','referer',error);
		}
	}
	
	
	
	
	
	
	
	include $this->template('web/setact');