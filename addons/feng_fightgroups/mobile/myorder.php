<?php
	$this->checkpay();
	$op = intval($_GPC['op']); //op=0对应获取全部订单,op=1对应获取待付款订单,op=2对应获取待收货订单
	$openid = $_W['openid'];//用户的openid
	//获取当前用户全部订单信息
	if($op==0){
		$sql = 'SELECT * FROM '.tablename('tg_order').' WHERE openid = :openid  and uniacid = :uniacid ORDER BY id desc'; //从订单信息表里面取得数据的sql语句
		$params = array(':openid'=>$openid , ':uniacid'=>$weid);
		$orders = pdo_fetchall($sql, $params); 
	}elseif ($op==1) {					//获取当前用户待付款订单信息
		$sql = 'SELECT * FROM '.tablename('tg_order').' WHERE openid = :openid  and uniacid = :uniacid  and status = :status ORDER BY id desc'; //从订单信息表里面取得数据的sql语句
		$params = array(':openid'=>$openid , ':uniacid'=>$weid,':status'=>0);
		$orders = pdo_fetchall($sql, $params); 
	}elseif ($op==2) {					//获取当前用户待收货订单信息 在数据表里status = 2代表待收货
		$sql = 'SELECT * FROM '.tablename('tg_order').' WHERE openid = :openid  and uniacid = :uniacid  and status = :status ORDER BY id desc'; //从订单信息表里面取得数据的sql语句
		$params = array(':openid'=>$openid , ':uniacid'=>$weid,':status'=>2);
		$orders = pdo_fetchall($sql, $params); 
	}else{
		message('获取订单信息失败.', $this->createMobileUrl('myorder',array('op' => '0')));
	}
	//获取每一个订单中的商品的信息
	$sql_order = 'SELECT g_id FROM '.tablename('tg_order').' WHERE openid = :openid  and uniacid = :uniacid';
	$sql_order = 'SELECT gname, gimg FROM '.tablename('tg_goods').' WHERE uniacid = :uniacid  and id in ('.$sql_order.')';
	//获取全部订单的订单数量
	$sql_0 = 'SELECT count(*) as "num" FROM '.tablename('tg_order').' a,'.tablename('tg_goods').' b'.' WHERE a.openid = :openid  and a.uniacid = :uniacid and b.id = a.g_id'; //从订单信息表里面取得数据的sql语句
	$params_0 = array(':openid'=>$openid , ':uniacid'=>$weid);
	$orders_num_0 = pdo_fetch($sql_0, $params_0); 
	if(empty($orders_num_0)){
		$orders_num_0 = 0;								
	}
	//获取待付款订单的订单数量
	$sql_1 = 'SELECT count(*) as "num" FROM '.tablename('tg_order').' a,'.tablename('tg_goods').' b'.' WHERE a.openid = :openid  and a.uniacid = :uniacid and b.id = a.g_id and status = 0'; //从订单信息表里面取得数据的sql语句
	$params_1 = array(':openid'=>$openid , ':uniacid'=>$weid);
	$orders_num_1 = pdo_fetch($sql_1, $params_1); 
	if(empty($orders_num_1)){
		$orders_num_1 = 0;								
	}
	//获取待收货订单的订单数量
	$sql_2 = 'SELECT count(*) as "num" FROM '.tablename('tg_order').' a,'.tablename('tg_goods').' b'.' WHERE a.openid = :openid  and a.uniacid = :uniacid and b.id = a.g_id and status = 2'; //从订单信息表里面取得数据的sql语句
	$params_2 = array(':openid'=>$openid , ':uniacid'=>$weid);
	$orders_num_2 = pdo_fetch($sql_2, $params_2); 
	if(empty($orders_num_2)){
		$orders_num_2 = 0;								
	}
	include $this->template('myorder');
?>