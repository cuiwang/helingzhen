<?php
/**
 * Created by PhpStorm.
 * User: DoubleWei
 * Date: 2016/12/31
 * Time: 14:12
 */

require_once(dirname(__FILE__) . "/../../model/pay.php");
require_once(dirname(__FILE__) . "/../../model/user.php");
global $_W,$_GPC;
$weid=$_W['uniacid'];//获取当前公众号ID
$openid=$_W['openid'];//获得当前用户ID

$_GPC['abc_pid_abc']=$params['tid'];//获取支付编号
$result=payModel::getPay();//获取该支付应支付价格
/*判断输入金额与应付金额是否相同*/
if($params['result'] == 'success' && $params['from'] == 'notify'){
    if($params['fee']==$result['abc_num_abc']){
        exit('用户支付的金额与订单金额不符合');
    }

}

/*调微擎支付页面，并跟新未支付订单为已支付*/
if ($params['from'] == 'return') {
    if ($params['result'] == 'success') {


        $abc_timestamp_abc=$_W['timestamp'];//获得当前时间戳
        $abc_time_abc=date('Y-m-d H:i:s', $abc_timestamp_abc);//将当前时间戳转换为时间格式

        //创建更新支付状态的数据
        $abc_pay_data_abc=array(
            'abc_status_abc'=>1,       //支付状态为1=以支付
            'abc_update_time_abc'=>$abc_time_abc,
            );

        //创建更新用户的数据
        $abc_user_data_abc=array(
            'abc_add_price_abc'=>$params['fee'],
            'abc_last_time_to_recharge_abc'=>$abc_time_abc,
        );

        userModel::updateBalance($abc_user_data_abc,2);//更新用户信息
        payModel::updatePay($abc_pay_data_abc);//更新支付表支付状态

        message('支付成功！', '../../app/' . $this->createMobileUrl('personal'));
    } else {

        message('支付失败！', '../../app/' .  $this->createMobileUrl('personal'));
    }
}