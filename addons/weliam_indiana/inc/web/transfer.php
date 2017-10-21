<?php 
	// 
	//  transfer.php
	//  <project>
	//  商家终止商品交易，并且退款到余额
	//  Created by haoran on 2016-01-21.
	//  Copyright 2016 haoran. All rights reserved.
	// 
	
	global $_W,$_GPC;
	$period_number = $_GPC['period_number'];
	$result = pdo_fetch("select goodsid,periods,endtime,status from".tablename('weliam_indiana_period')."where period_number = '{$period_number}'");
	if(!empty($result['endtime'])){
		message("该期已经结束不能终止",$this->createWebUrl('merchant'));
		exit;
	}
	//修改该期商品终止
	pdo_update('weliam_indiana_period',array('status' => 8) , array('period_number' => $period_number,'uniacid'=>$_W['uniacid']));
	//修改整个商品终止
	pdo_update('weliam_indiana_goodslist',array('status' => 3) , array('id' => $result['goodsid'],'uniacid'=>$_W['uniacid']));
	//修改购买商品记录信息
	$record = pdo_fetchall("select id,openid,unicaid,goodsid,period_number,count  from".tablename('weliam_indiana_record')."where period_number= '{$period_number}'");
	pdo_update('weliam_indiana_record',array('status' => '-1'),array('period_number' => $period_number,'uniacid'=>$_W['uniacid']));
	 foreach($record as $key=>$value){
	 	m('credit')->updateCredit2($record['openid'],$_W['uniacid'],$record['count']);
		message("终止成功",$this->createWebUrl('merchant'));
	 }
	?>