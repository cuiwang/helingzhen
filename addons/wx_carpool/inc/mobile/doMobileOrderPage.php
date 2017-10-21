<?php
/*带条件的订单分页查询*/
require_once(dirname(__FILE__) . "/../../model/order.php");

global $_W,$_GPC;
$weid=$_W['uniacid'];//获取当前公众号ID
$abc_op_abc=$_GPC['op'];//获取操作类型
$abc_timestamp_abc=$_W['timestamp'];//获得当前时间戳
if ($abc_op_abc=="search"){
    $abc_data_abc= orderModel::getSearch();
    $abc_orderList_abc=$abc_data_abc[0];
    $count=$abc_data_abc[1]['count'];
}elseif ($abc_op_abc=="search_passenger"){ //找乘客
    $abc_orderList_abc=orderModel::getDriver(); //获取人找车信息
    $count=orderModel::getDriverCount(); //获取车找人信息的总数
}elseif ($abc_op_abc=="search_drive"){      //找司机
    $abc_orderList_abc=orderModel::getPassenger();  //获取车找人信息
    $count=orderModel::getPassengerCount(); //获取人找车信息的总数
}



include $this->template("orderpage");





