<?php

header("Content-type:text/csv;");   
header("Content-Disposition:attachment;filename=orders.csv");   
header('Cache-Control:must-revalidate,post-check=0,pre-check=0');   
header('Expires:0');   
header('Pragma:public');

$weid=$_W['uniacid'];
$id= intval($_GPC['id']);

$value = pdo_fetch("SELECT * FROM ".tablename('heixiu_car_order')." WHERE weid=:weid AND id=:id ORDER BY id DESC ", array(':weid' => $weid,':id'=>$id));

$meals= pdo_fetchall('SELECT * FROM '.tablename('heixiu_car_meal')." ORDER BY id DESC");

$brandtitle = pdo_fetchcolumn('SELECT title FROM '.tablename('heixiu_car_brand')." WHERE  id=:id ", array(':id' =>$value['brandid']));

$series = pdo_fetchcolumn('SELECT title FROM '.tablename('heixiu_car_series')." WHERE id=:id ", array(':id' =>$value['seriesid']));

$style= pdo_fetchcolumn('SELECT title FROM '.tablename('heixiu_car_style')." WHERE id=:id ", array(':id' =>$value['styleid']));

$uid = pdo_fetchcolumn('SELECT uid FROM ' .tablename('mc_mapping_fans') . 'WHERE openid = :openid limit 1',array(':openid'=>$value['openid']));

$nickname = pdo_fetch("SELECT nickname FROM ".tablename('mc_members')." WHERE `uid` = :uid limit 1", array(':uid' => $uid));

$title= "订单号".","."姓名".","."电话".","."预约时间".","."状态".","."车牌号".","."品牌".","."车系".","."年款".","."地址".","."套餐".","."微信昵称".","."技师名称".","."时间"."\n";
$mealArr= array();
foreach($meals as $v) {
	$mealArr[$v['id']]= $v['title'];
}
			
if(isWin()){
	echo iconv('utf-8', 'gb2312', $title);
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
    .",".$txt.",".$value['carprefix'].$value['carNo']
    .",".$brandtitle
    .",".$series
    .",".$style
    .",".$value['address']
    .",".$mealArr[$value['mealid']].'/'.$value['price']
    .",".$nickname['nickname']
    .",".$value['jsname']
    .",".date("Y-m-d G:i",$value['createtime'])."\n";
    echo iconv('utf-8', 'gb2312', $temp);

}else{
	echo $title;
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
    echo $brandtitle ;
    echo $series ;
    echo $style ;
    echo $value['address'].",";
    echo $mealArr[$value['mealid']].'/'.$value['price'].",";
    echo $nickname['nickname'].",";
    echo $value['jsname'].",";
    echo date("Y-m-d G:i",$value['createtime'])."\n";
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