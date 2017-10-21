<?php

header("Content-type:text/csv;");   
header("Content-Disposition:attachment;filename=orders.csv");   
header('Cache-Control:must-revalidate,post-check=0,pre-check=0');   
header('Expires:0');   
header('Pragma:public');

$weid=$_W['uniacid'];
$result = pdo_fetchAll("SELECT * FROM ".tablename('heixiu_car_order')." WHERE  weid=".$weid." ORDER BY id DESC ");
$meals= pdo_fetchall('SELECT * FROM '.tablename('heixiu_car_meal')."  ORDER BY id DESC");
$activitys = pdo_fetchAll("SELECT * FROM ".tablename('heixiu_car_activity')." WHERE  weid=".$weid." ORDER BY id DESC ");

$title= "订单号".","."姓名".","."电话".","."预约时间".","."状态".","."车牌号".","."区域".","."套餐".","."价格".","."技师名称".","."时间"."\n";
$mealArr= array();
foreach($meals as $v) {
	$mealArr[$v['id']]= $v['title'];
}
			
if(isWin()){
	echo iconv('utf-8', 'gb2312', $title);
	foreach ($result as $key => $value) {
		$txt="";
		if ($value['status'] == 0){
			$txt='待确认 ';
		}elseif($value['status'] == 1){
			$txt='已确认'; 
		}elseif($value['status'] == -1){
			$txt='已关闭';  
		}  
		$temp = $value['ordersn'].",".$value['username']
		.",".$value['mobile'].",".$value['appointmenttime'].$value['timezone']
		.",".$txt.",".$value['carprefix'].$value['carNo'].",".$value['address']
		.",".$mealArr[$value['mealid']].",".$value['price']
		.",".$value['jsname']
		.",".date("Y-m-d G:i",$value['createtime'])."\n";
		echo iconv('utf-8', 'gb2312', $temp);
	}
}else{
	echo $title;
	foreach ($result as $key => $value) {
		$txt="";
		if ($value['status'] == 0){
			$txt='待确认 ';
		}elseif($value['status'] == 1){
			$txt='已确认'; 
		}elseif($value['status'] == -1){
			$txt='已关闭';  
		} 
		echo $value['ordersn'].",";
		echo $value['username'].",";
		echo $value['mobile'].",";
		echo $value['appointmenttime'].$value['timezone'].",";
		echo $txt.",";
		echo $value['carprefix'].$value['carNo'].",";
		echo $value['address'].",";
		echo $mealArr[$value['mealid']].",";
		echo $value['price'].",";
		echo $value['jsname'].","; 
		echo date("Y-m-d G:i",$value['createtime'])."\n";
	}
}

function isWin(){
     global $_SERVER;
     $agent = $_SERVER['HTTP_USER_AGENT'];
     $os = false;
     if (eregi('win', $agent)){ 
         $os = "win";
     }else if (eregi('teleport', $agent)){
         $os = 'teleport';
     }else if (eregi('flashget', $agent)){ 
         $os = 'flashget';
     }else if (eregi('webzip', $agent)){
         $os = 'webzip';
     }else if (eregi('offline', $agent)){ 
         $os = 'offline';
     }else {
     }
     return $os;
}

?>