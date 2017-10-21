<?php 
require '../../../../framework/bootstrap.inc.php';
require IA_ROOT. '/addons/weliam_indiana/defines.php';
require WELIAM_INDIANA_INC.'function.php'; 

$_W['uniacid'] = $_GPC['uniacid'];
$flag = $_GPC['flag'];
$ip_a = $_SERVER["REMOTE_ADDR"];
$ip_b = $_SERVER["HTTP_X_FORWARDED_FOR"];
if(empty($_W['uniacid']) && $ip_a != '114.215.91.124' && $ip_b != '114.215.91.124' &&  $flag != 'bendi'){
	exit('false');
}

$now_time = time();							//当前时间
$flag_time = (time()+28800) % 86400;		//得到当前时间小时时间戳	
$buy_num = intval($_GPC['buy_num']);
if($buy_num == '' || $buy_num == 0){
	$buy_num = 2;
}

$sql = "select id,period_number,goodsid,next_time,machine_num,is_followed,timebucket,start_time,end_time,max_num,all_buy from".tablename('weliam_indiana_machineset')."where uniacid = {$_W['uniacid']} and status = 1 and next_time < {$now_time} and max_num > 0 and start_time < {$flag_time} and end_time > {$flag_time} order by rand() limit {$buy_num}";
$result = pdo_fetchall($sql);

/*************日志文件位置开始************/
m('log')->WL_log('machine_api','机器人购买进程',sizeof($result),$_W['uniacid']);
/*************日志文件位置结束************/ 

if(!empty($result)){
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
					pdo_update('weliam_indiana_machineset',array('next_time'=>$next_time,'period_number'=>$new_period_number),array('id'=>$value['id']));
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
		}
	}
	exit('true');
}
exit('false');
