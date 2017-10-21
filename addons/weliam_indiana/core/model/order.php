<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
} 
class Welian_Indiana_Order {
	public function getList($args = array()) {
		global $_W;
		$fetch = !empty($args['fetch'])? intval($args['fetch']): 0;
		$page = !empty($args['page'])? intval($args['page']): 1;
		$pagesize = !empty($args['pagesize'])? intval($args['pagesize']): 10;
		$random = !empty($args['random'])? $args['random'] : false;
		$order = !empty($args['order'])? $args['order'] : '';
		$orderby = !empty($args['by'])? $args['by'] : '';
		$condition =  "and uniacid = :uniacid ";
		$params = array(':uniacid' => $_W['uniacid']);
		$id = !empty($args['id'])? $args['id']:'';
		if (!empty($id)) {
			$condition .=  " and id = :id ";
			$params[':id'] = $id;
		}
		$status = !empty($args['status'])? $args['status']:'';
		if (!empty($status)) {
			$condition .=  " and status = :status ";
			$params[':status'] = $status;
		}
		$isnew = !empty($args['isnew'])? 1 : 0;
		if (!empty($isnew)) {
			$condition .= " and isnew=1 ";
		} 
		$ishot = !empty($args['ishot'])? 1 : 0;
		if (!empty($ishot)) {
			$condition .= " and ishot=1 ";
		} 
		$keywords = !empty($args['keywords'])? $args['keywords'] : '';
		if (!empty($keywords)) {
			$condition .= " AND 'title' LIKE :title ";
			$params[':title'] = '%' . trim($keywords) . '%';
		} 
		if($one){
			$sql = "SELECT * FROM " . tablename('weliam_indiana_goodslist') . " where 1 {$condition}";
			$list = pdo_fetch($sql, $params);
		}else{
			if (!$random) {
			$sql = "SELECT * FROM " . tablename('weliam_indiana_record') . " where 1 {$condition} ORDER BY {$order} {$orderby} LIMIT " . ($page - 1) * $pagesize . ',' . $pagesize;
			} else{
				$sql = "SELECT * FROM " . tablename('weliam_indiana_record') . " where 1 {$condition}";
			}
			$list = pdo_fetchall($sql, $params);
		}
		return $list;
	} 
	public function get_head_img($url,$num){
			$imgs_array = array();
			$random_array = array();
			$files=array(); 
			if ($handle=opendir($url)) { 
				while(false !== ($file = readdir($handle))) { 
				    if ($file != "." && $file != "..") { 
					    if(substr($file,-3)=='gif' || substr($file,-3)=='jpg') $files[count($files)] = $file; 
					    } 
				} 
			} 
			closedir($handle); 
			for($i=0;$i<$num;$i++){
				$random=rand(0,count($files)-1);
				while(in_array($random, $random_array)){
					$random=rand(0,count($files)-1);
				} 
				$random_array[$i] = $random;
				$imgs_url = $url."/".$files[$random];
				$imgs_array[$i] = $imgs_url;
				
			}
			return $imgs_array;
		}
	public function randnum($total , $grounds){
		//计算每个人分到的夺宝码
		$total = $total;
		$grounds = $grounds;
		$left = $total - $grounds;
		$arr = array();
		for( $i = 0; $i < $grounds; $i++ ){
		    $arr[$i] = 1;
	    }
		for( $j = 0; $j < $left ; $j++){
			$num = rand(0,$grounds-1);
			$arr[$num]++;
		}
		return $arr;
	}
	public function get_nickname($filename,$num){
			$nickname_array = array();
			$random_array = array();
			$file_new=fopen($filename,"r");
			$file_read = fread($file_new, filesize($filename)); 
			fclose($file_new);
			$arr_new = unserialize($file_read);
			for($i=0;$i<$num;$i++){
				$random=rand(0,count($arr_new)-1);
				while(in_array($random, $random_array)){
					$random=rand(0,count($arr_new)-1);
				} 
				$random_array[$i] = $random;
				$nickname = $arr_new[$random];
				$nickname_array[$i] = $nickname;
				
			}
			return $arr_new;
		}
/*	public function get_ip($filename,$num){
			$nickname_array = array();
			$random_array = array();
			$file_new=fopen($filename,"r");
			$file_read = fread($file_new, filesize($filename)); 
			fclose($file_new);
			$arr_new = unserialize($file_read);
			for($i=0;$i<$num;$i++){
				$random=rand(0,count($arr_new)-1);
				$random_array[$i] = $random;
				$nickname = $arr_new[$random];
				$nickname_array[$i] = $nickname;
				
			}
			return $nickname_array;
		}*/
	public function get_ip($filename,$num){
		//随机取IP地址
		$nickname_array = array();
		$random_array = array();
		$file_new=fopen($filename,"r");
		$file_read = fread($file_new, filesize($filename)); 
		fclose($file_new);
		$arr_new = unserialize($file_read);
		for($i=0;$i<$num;$i++){
			$random=rand(0,count($arr_new)-1);
			$random_array[$i] = $random;
			$nickname = $arr_new[$random];
			$all_ip = explode('-', $nickname);
			$ip_A = explode('.', $all_ip[0]);
			$ip_B = explode('.', $all_ip[1]);
			$nickname_array[$i] = rand($ip_A[0], $ip_B[0]).'.'.rand($ip_A[1], $ip_B[1]).'.'.rand($ip_A[2], $ip_B[2]).'.'.rand($ip_A[3], $ip_B[3]);
		}
		return $nickname_array;
	}
		public function get_randtime($inittime=0,$now=0,$num){
			//机器人时间计算
			$inittime = $inittime;
			$now = $now;
			$num = $num;
			$arr = array();
			for($i = 0 ; $i < $num ; $i++){
				$arr[$i] = rand($inittime,$now);
			}
			for($i = 1 ; $i < sizeof($arr) ; $i++){
				for($j = 1 ; $j < sizeof($arr); $j++){
					if($arr[$j-1] > $arr[$j]){
						$flag = $arr[$j-1];
						$arr[$j-1] = $arr[$j];
						$arr[$j] = $flag;
					}
				}
			}
			return $arr;
		}
		
		public function set_randtime($nowtime = '',$timebucket = ''){
			//根据时间段取一个机器人购买时间
			global $_W;
			if(!empty($nowtime) && !empty($timebucket)){
				$timebucket = rand(1, $timebucket);
				$next_time = $nowtime + $timebucket;
				return $next_time;
			}
			
		}
		
	public function get_Machines($num = 0){
		//查出member表中多少条数据
		global $_W;
		$info = pdo_fetchall("select * from".tablename('weliam_indiana_member')."where uniacid = '{$_W['uniacid']}' and type = '-1' and ip != '' order by rand() limit  ".$num);
		return $info;
	}
	
} 
