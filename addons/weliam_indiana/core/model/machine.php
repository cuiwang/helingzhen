<?php 
	// 
	//  machine.php
	//  <project>
	//  机器人自动设置时间执行
	//  Created by Administrator on 2016-03-17.
	//  Copyright 2016 Administrator. All rights reserved.
	// 
	if (!defined('IN_IA')) {
		exit('Access Denied');
	} 
	class Welian_Indiana_Machine {
		
		public function get_Machines($num = 0){
			//查出member表中多少条数据
			global $_W;
			$info = pdo_fetchall("select * from".tablename('weliam_indiana_member')."where uniacid = '{$_W['uniacid']}' and type = '-1' and ip != '' order by rand() limit  ".$num);
			return $info;
		}
		
		public function get_MachinesInfoByPeriodnNumber($period_number = ''){
			//通过期号获取机器人运行情况
			global $_W;
			$machine_info = pdo_fetch("select * from".tablename('weliam_indiana_machineset')."where uniacid ='{$_W['uniacid']}' and period_number = '{$period_number}'");
			return $machine_info;
		}
				
		public function get_machine_name($num = 0){
			//随机获取机器人名称
			global $_W;
			
			$sql = "select data from".tablename('weliam_indiana_in')."where uniacid=:uniacid and type=:type order by rand() limit ".$num;
			$data = array(
				':uniacid'=>$_W['uniacid'],
				':type'=>1
			);
			$result = pdo_fetchall($sql,$data);
			$arr = array();
			foreach($result as $key=>$value){
				$arr[$key] = $value['data'];
			}
			return $arr;
		}
		
		public function get_machine_IP($num = 0){
			//随机获取ip地址
			global $_W;
			
			$arr = array();
			for($i=0;$i<$num;$i++){
				$sql = "select data from".tablename('weliam_indiana_in')."where uniacid=:uniacid and type=:type order by rand() limit 1";
				$data = array(
					':uniacid'=>$_W['uniacid'],
					':type'=>2
				);
				$result = pdo_fetchcolumn($sql,$data);
				$all_ip = explode('-', $result);
				$ip_A = explode('.', $all_ip[0]);
				$ip_B = explode('.', $all_ip[1]);
				$arr[$i] = rand($ip_A[0], $ip_B[0]).'.'.rand($ip_A[1], $ip_B[1]).'.'.rand($ip_A[2], $ip_B[2]).'.'.rand($ip_A[3], $ip_B[3]);
			}
			
			return $arr;
		}
		
		public function get_new_period_number($goodsid=''){
			//通过goodsid获取该商品进行期的期号
			global $_W;
			
			$sql = "select period_number from".tablename('weliam_indiana_period')."where uniacid=:uniacid and goodsid=:goodsid and status=:status";
			$data = array(
				':uniacid'=>$_W['uniacid'],
				':goodsid'=>$goodsid,
				':status'=>1
			);
			$result = pdo_fetchcolumn($sql,$data);
			if(empty($result)){
				return FALSE;
			}else{
				return $result;
			}
		}
		
		public function get_next_time($start_time,$end_time,$timebucket){
			//获取下次购买时间
			global $_W;
			$diff_time = rand(1, $timebucket);				//计算时间差
			$next_time = time() + $diff_time;
			return $next_time;
		}
	}
	
	?>