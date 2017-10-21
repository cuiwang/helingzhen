<?php 
	// 
	//  machine_async.inc.php
	//  <project>
	//  异步执行机器人
	//  Created by Administrator on 2016-03-24.
	//  Copyright 2016 Administrator. All rights reserved.
	// 
	global $_W;
	$flag_time = time()%86400;		//得到当前时间小时时间戳	
	$sql = "select id,period_number,goodsid,next_time,machine_num,is_followed,timebucket,start_time,end_time,max_num,all_buy from".tablename('weliam_indiana_machineset')."where uniacid=:uniacid and status=:status and next_time<{$flag_time} and max_num>:max_num and start_time<{$flag_time} and end_time>{$flag_time}";
	$data = array(
		':uniacid'=>$_W['uniacid'],
		':status'=>1,
		':max_num'=>0,
	);
	$result = pdo_fetchall($sql,$data);
	$this->WL_log('machine','buy_info',$result);
		
	foreach($result as $key=>$value){
		//查询该期商品是否完成
		$sql_period = "select id,shengyu_codes,status,goodsid from".tablename('weliam_indiana_period')."where uniacid=:uniacid and period_number=:period_number";
		$data_period = array(
			':uniacid'=>$_W['uniacid'],
			':period_number'=>$value['period_number']
		);
		$result_period = pdo_fetch($sql_period,$data_period);
	
		if($result_period['status'] != 1){
			//本期已经结束
			$new_period_number = m('machine')->get_new_period_number($value['goodsid']);
			if($new_period_number == FALSE){
				//商品已经进行完成
				pdo_update('weliam_indiana_machineset',array('status'=>-1),array('id'=>$value['id']));	//将该机器人暂停
			}else{
				if($value['is_followed'] == 1){
					//有最新期(开启连期)
					$next_time = m('machine')->get_next_time($value['start_time'],$value['end_time'],$value['timebucket']);	//获取下期时间
					pdo_update('weliam_indiana_machineset',array('next_time'=>$next_time,'period_number'),array('id'=>$value['id']));
				}else{
					//未开启连期商品停止
					pdo_update('weliam_indiana_machineset',array('status'=>-1),array('id'=>$value['id']));	//将该机器人暂停
				}
			}
		}else{
			//正常购买
			$buy_number = rand(1,$value['machine_num']);
			if($buy_number > $value['max_num']){
				//购买数量大于剩余最大购买数
				$buy_number = $value['max_num'];
			}
			if($buy_number > $result_period['shengyu_codes']){
				//如果购买数量大于商品剩余数量
				$buy_number = $result_period['shengyu_codes'];
			}
	
			$machine = m('machine')->get_Machines(1);			//获取一个机器人
			$api = "http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip={$machine[0]['ip']}";
			$json = @file_get_contents($api);//调用新浪IP地址库 
			$arr = json_decode($json,true);
			pdo_insert('weliam_indiana_cart',array('num'=>$buy_number,'uniacid'=>$_W['uniacid'],'period_number'=>$value['period_number'],'openid'=>$machine[0]['openid'],'ip'=>$machine[0]['ip'],'ipaddress'=>$arr['province'].$arr['city']));
	
			m('codes')->code($machine[0]['openid'],'machine',$_W['uniacid'],time());			//执行购买程序
	
			$left_number = $value['max_num']-$buy_number;
			if($left_number == 0){
				$new_status = -1;
			}else{
				$new_status = 1;
			}
			$next_time = m('machine')->get_next_time($value['start_time'],$value['end_time'],$value['timebucket']);	
			$all_buy = $value['all_buy'] + $buy_number;
	
			pdo_update('weliam_indiana_machineset',array('max_num'=>$left_number,'status'=>$new_status,'next_time'=>$next_time,'all_buy'=>$all_buy),array('id'=>$value['id']));
			$this->WL_log('machine','all_buy',$all_buy);
		}
		
	}
	message('购买成功',referer(),'success');
	?>