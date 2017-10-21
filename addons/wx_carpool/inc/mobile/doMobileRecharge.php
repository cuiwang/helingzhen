<?php
/**
 * Created by PhpStorm.
 * User: DoubleWei
 * Date: 2016/12/25
 * Time: 13:41
 */
require_once(dirname(__FILE__) . "/../../model/parConfig.php");
require_once(dirname(__FILE__) . "/../../model/picConfig.php");
require_once(dirname(__FILE__) . "/../../model/user.php");
require_once(dirname(__FILE__) . "/../../model/order.php");
require_once(dirname(__FILE__) . "/../../model/pay.php");

global $_GPC,$_W;
$abc_weid_abc = $_W['uniacid'];               //获取当前公众号ID
$level = $_W['account']['level'];//获取公众号类型
$abc_openid_abc=$_W['openid'];//获取用户openid
$abc_information_abc = $_W['fans'];//获取用户信息
$abc_op_abc=$_GPC['abc_op_abc'];//获取操作类型
if ($level != 4) $abc_information_abc = mc_fansinfo($openid);
if(empty($abc_openid_abc)||$abc_information_abc['follow']=='0'){
    $this->doMobilePrompt();
    exit;
}
$abc_wx_name_abc=ParConfigModel::getWxName();//获取当前配置的平台名称

$abc_user_abc=userModel::getUserByOpenId($abc_openid_abc);//获取当前用户信息

//操作类型为支付执行支付
if($abc_op_abc=="abc_pay_abc"){



    $abc_pay_price_abc=$_GPC['abc_pay_price_abc'];//获取要重置的价格
    $abc_order_id_abc=$_W['timestamp'];//将时间戳赋值给订单ID
    $abc_time_abc=date('Y-m-d H:i:s', $abc_order_id_abc);//将当前时间戳转换为时间格式

    //创建支付表要插入数据
    $abc_data_abc=array(
        'abc_weid_abc'=>$abc_weid_abc,
        'abc_uid_abc'=>$abc_user_abc['abc_id_abc'],
        'abc_order_id_abc'=>$abc_order_id_abc,
        'abc_num_abc'=>$abc_pay_price_abc,
        'abc_create_time_abc'=>$abc_time_abc,
        'abc_update_time_abc'=>$abc_time_abc,
    );


    //创建收银台要插入数据
    $params = array(
        'tid' => $abc_order_id_abc,      //充值模块中的订单号，此号码用于业务模块中区分订单，交易的识别码
        'ordersn' => $abc_order_id_abc,  //收银台中显示的订单号
        'title' => "余额充值",          //收银台中显示的标题
        'fee' => $abc_pay_price_abc,      //收银台中显示需要支付的金额,只能大于 0
        'user' => $abc_user_abc['abc_id_abc'],     //付款用户, 付款的用户名(选填项)
    );


    payModel::createPay($abc_data_abc);//插入一条新支付记录
    $this->pay($params);
    exit;

}
include $this->template('recharge');