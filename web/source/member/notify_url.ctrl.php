<?php
/**
 * [Weizan System] Copyright (c) 2014 WEIZANCMS.COM
 
 */

define('IN_SYS', true);

require '../../../framework/bootstrap.inc.php';
$_W['siteroot'] = htmlspecialchars('http://' . $_SERVER['HTTP_HOST']);
if(substr($_W['siteroot'], -1) != '/') {
    $_W['siteroot'] .= '/';
}

require IA_ROOT . '/web/common/bootstrap.sys.inc.php';
require IA_ROOT . '/web/source/member/common/common.inc.php';
load()->web('common');
load()->web('template');

define ('ALIPAY_ROOT', "payment" . DIRECTORY_SEPARATOR . "alipay");
if (!defined("ALIPAY_SDK_ROOT"))
{
    define("ALIPAY_SDK_ROOT", dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . ALIPAY_ROOT . DIRECTORY_SEPARATOR);
}
global $_GPC;
require_once("../../payment/alipay/alipay.config.php");
require_once("../../payment/alipay/lib/alipay_notify.class.php");
$alipay_config['cacert']= ALIPAY_SDK_ROOT."cacert.pem";

//计算得出通知验证结果
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyReturn();
$verify_result2 = $alipayNotify->verifyNotify();

if($verify_result || $verify_result2) {//验证成功
    echo "success";		//请不要修改或删除
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //请在这里加上商户的业务逻辑程序代
    //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
    //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
    //商户订单号
    $out_trade_no = $_GPC['out_trade_no'];
    //支付宝交易号
    $trade_no = $_GPC['trade_no'];
    //交易状态
    $trade_status = $_GPC['trade_status'];
    try{
        pdo_begin();
        $order = pdo_fetch("SELECT * FROM ".tablename("uni_payorder")." WHERE orderid = :orderid", array(":orderid"=>$out_trade_no));
		
        if(empty($order) || $order["status"] == 1){
            header("location:".$_W["siteroot"]."web/index.php?c=member&a=member");
            pdo_rollback();
            return;
        }
        if(pdo_update("uni_payorder",array("status"=>1,"pay_time"=>TIMESTAMP, "order_no"=>$trade_no),array("orderid"=>$out_trade_no)) > 0 ){
            if(user_credits_update($order["uid"],$order["credittype"],$order["money"],array(2,"充值"))){
					
                pdo_commit();
                header("location:".$_W["siteroot"]."web/index.php?c=member&a=member");
                return;
            }
            pdo_rollback();
        }
        header("location:".$_W["siteroot"]."web/index.php?c=member&a=member");
    }catch (Exception $e) {
        pdo_rollback();
    }
    //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
} else {
    //验证失败
    echo "fail";
    header("location:".$_W["siteroot"]."web/index.php?c=member&a=member");
    return;
    //调试用，写文本函数记录程序运行情况是否正常
    //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
}