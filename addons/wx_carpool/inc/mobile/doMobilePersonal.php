<?php
/**
 * Created by PhpStorm.
 * User: DoubleWei
 * Date: 2016/12/25
 * Time: 13:38
 */
require_once(dirname(__FILE__) . "/../../model/parConfig.php");
require_once(dirname(__FILE__) . "/../../model/user.php");
require_once(dirname(__FILE__) . "/../../model/order.php");
global $_GPC,$_W;
$img=2;
$abc_op_abc=$_GPC['abc_op_abc'];//获取操作类型
$abc_id_abc=$_GPC['abc_id_abc'];//获取订单ID
$level = $_W['account']['level'];//获取公众号类型
$abc_openid_abc=$_W['openid'];//获取用户openid
$abc_information_abc = $_W['fans'];//获取用户信息
$img = isset($abc_information_abc['tag']['avatar'])?$abc_information_abc['tag']['avatar']:"";

if(empty($abc_openid_abc)||$abc_information_abc['follow']=='0'){
    $this->doMobilePrompt();
    exit;
}
$abc_timestamp_abc=$_W['timestamp'];//获得当前时间戳
$abc_time_abc=date('Y-m-d H:i:s', $abc_timestamp_abc);//将当前时间戳转化为时间格式
$abc_user_abc=userModel::getUserByOpenId($abc_openid_abc);//根绝openid获取用户信息
$abc_datas_abc=orderModel::getUserOrder();//获取当前用户发布的信息



if ($abc_op_abc=='del'){
    $abc_judge_openid_abc=orderModel::getOpenidByOid($abc_id_abc);//根绝订单ID 获取OPENID

    if ($abc_openid_abc===$abc_judge_openid_abc) {
        $type = 0;
        if (orderModel::delUserOrder($abc_id_abc, $type)){
            message("删除成功");
        }else{
            message("删除失败");
        }
    }else{
        message("操作错误");
    }
    exit;
}

$abc_wx_name_abc=ParConfigModel::getWxName();//获取当前配置的平台名称
$nickname  = $_W['fans']['nickname'];

$isOpenRecharge= ParConfigModel::getIsOpenRecharge(); //获得是否打开充值提示




include $this->template('personal');