<?php
/**
 * Created by PhpStorm.
 * User: DoubleWei
 * Date: 2016/12/28
 * Time: 9:26
 */
require_once(dirname(__FILE__) . "/../../model/user.php");
require_once(dirname(__FILE__) . "/../../model/order.php");
require_once(dirname(__FILE__) . "/../../model/parConfig.php");
global $_W,$_GPC;
$abc_openid_abc=$_W['openid'];//获取用户openid
$abc_weid_abc=$_W['uniacid'];//获取当前公众号ID
$abc_timestamp_abc=$_W['timestamp'];//获得当前时间戳
$abc_time_abc=date('Y-m-d H:i:s', $abc_timestamp_abc);//将当前的时间戳转化为时间格式

$abc_uid_abc=$_GPC['abc_uid_abc'];//获取传过来的用户ID
$abc_order_type_abc=$_GPC['abc_order_type_abc']; /*订单类型*/
$abc_place_of_departure_abc=$_GPC['abc_place_of_departure_abc']; /*出发地*/
$abc_destination_abc =$_GPC['abc_destination_abc']; /*目的地*/
$abc_pathway_abc = $_GPC['abc_pathway_abc'];  /*途径地*/
$abc_departure_time_abc = $_GPC['abc_departure_time_abc']; /*出发时间*/
$abc_replenishment_time_abc = $_GPC['abc_replenishment_time_abc'];/*补充时间*/
$abc_phone_abc= $_GPC['abc_phone_abc']; /*手机号*/
$abc_name_abc= $_GPC['abc_name_abc'];/*联系人*/
$abc_number_abc= $_GPC['abc_number_abc'];  /*空位或乘客数量*/
$abc_isTop_abc = $_GPC['abc_isTop_abc']; /*是否置顶*/
$abc_describe_abc = $_GPC['abc_describe_abc']; /*描述*/



$abc_data_abc=array(
  'abc_openid_abc'=>$abc_openid_abc,
    'abc_weid_abc'=>$abc_weid_abc,
    'abc_order_type_abc'=>$abc_order_type_abc,
    'abc_uid_abc'=>$abc_uid_abc,
    'abc_place_of_departure_abc'=>$abc_place_of_departure_abc,
    'abc_destination_abc'=>$abc_destination_abc,
    'abc_pathway_abc'=>$abc_pathway_abc,
    'abc_departure_time_abc'=>$abc_departure_time_abc,
    'abc_replenishment_time_abc'=>$abc_replenishment_time_abc,
    'abc_phone_abc'=>$abc_phone_abc,
    'abc_name_abc'=>$abc_name_abc,
    'abc_number_abc'=>$abc_number_abc,
    'abc_isTop_abc'=>$abc_isTop_abc,
    'abc_describe_abc'=>$abc_describe_abc,
    'abc_order_create_time_abc'=>$abc_time_abc,
);




$abc_user_abc=userModel::getUserByOpenId($abc_openid_abc);//获取用户信息

if($abc_order_type_abc==0){         //人找车

    $abc_price_abc=ParConfigModel::getPrice2();//获取人找车价格
    $abc_new_balance_abc=$abc_user_abc['abc_balance_abc']-$abc_price_abc;//计算余额

    if ($abc_new_balance_abc<0){
        echo json_encode(array("success"=>false,"data"=>"余额不足"));  //返回false
        exit;
    }
    if ($abc_isTop_abc==0&&$abc_new_balance_abc>=0){  //不置顶

        userModel::updateBalance($abc_new_balance_abc);             //更新余额
        orderModel::createOrder($abc_data_abc);                     //插入新订单
        echo json_encode(array("success"=>true));  //返回true


    }elseif($abc_isTop_abc==1&&$abc_new_balance_abc>=0){  //置顶一天

        $abc_isTop_price3_abc=ParConfigModel::getIsTopPrice3();//获取人找车获取一天置顶价格
        $abc_new_balance_abc-=$abc_isTop_price3_abc;
        if ($abc_new_balance_abc>=0){

            userModel::updateBalance($abc_new_balance_abc);             //更新余额
            orderModel::createOrder($abc_data_abc);                     //插入新订单
            echo json_encode(array("success"=>true));  //返回true

        }else{
            echo json_encode(array("success"=>false,"data"=>"余额不足"));  //返回false
        }


    }elseif ($abc_isTop_abc==2&&$abc_new_balance_abc>=0){ //置顶3天

        $abc_isTop_price4_abc=ParConfigModel::getIsTopPrice4();//获取人找车获取三天置顶价格
        $abc_new_balance_abc-=$abc_isTop_price4_abc;
        if ($abc_new_balance_abc>=0){

            userModel::updateBalance($abc_new_balance_abc);             //更新余额
            orderModel::createOrder($abc_data_abc);                     //插入新订单
            echo json_encode(array("success"=>true));  //返回true

        }else{
            echo json_encode(array("success"=>false,"data"=>"余额不足"));  //返回false
        }

    }

}elseif($abc_order_type_abc==1){    //车找人

    $abc_price_abc=ParConfigModel::getPrice1();//获取车找人价格
    $abc_new_balance_abc=$abc_user_abc['abc_balance_abc']-$abc_price_abc;//计算余额

    if ($abc_new_balance_abc<0){
        echo json_encode(array("success"=>false,"data"=>"余额不足"));  //返回false
        exit;
    }
    if ($abc_isTop_abc==0&&$abc_new_balance_abc>=0){  //不置顶

        userModel::updateBalance($abc_new_balance_abc);             //更新余额
        orderModel::createOrder($abc_data_abc);                     //插入新订单
        echo json_encode(array("success"=>true));  //返回true

    }elseif($abc_isTop_abc==1&&$abc_new_balance_abc>=0){  //置顶一天

        $abc_isTop_price1_abc=ParConfigModel::getIsTopPrice1();//获取车找人一天置顶价格
        $abc_new_balance_abc-=$abc_isTop_price1_abc;
        if ($abc_new_balance_abc>=0){

            userModel::updateBalance($abc_new_balance_abc);             //更新余额
            orderModel::createOrder($abc_data_abc);                     //插入新订单
            echo json_encode(array("success"=>true));  //返回true

        }else{
            echo json_encode(array("success"=>false,"data"=>"余额不足"));  //返回false
        }


    }elseif ($abc_isTop_abc==2&&$abc_new_balance_abc>=0){ //置顶3天

        $abc_isTop_price2_abc=ParConfigModel::getIsTopPrice2();//获取车找人三天置顶价格
        $abc_new_balance_abc-=$abc_isTop_price2_abc;
        if ($abc_new_balance_abc>=0){

            userModel::updateBalance($abc_new_balance_abc);             //更新余额
            orderModel::createOrder($abc_data_abc);                     //插入新订单
            echo json_encode(array("success"=>true));  //返回true

        }else{
            echo json_encode(array("success"=>false,"data"=>"余额不足"));  //返回false
        }

    }


}

