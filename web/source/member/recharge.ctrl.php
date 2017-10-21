<?php
/**
 * [Weizan System] Copyright (c) 2014 WEIZANCMS.COM
 
 */
defined('IN_IA') or exit('Access Denied');
include_once 'common/common.inc.php';
global $_W,$_GPC;
checklogin();
if (!defined("BFB_SDK_ROOT"))
{
    define("BFB_SDK_ROOT", dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . BAIFUBAO_ROOT . DIRECTORY_SEPARATOR);
}
define ('ALIPAY_ROOT', "payment" . DIRECTORY_SEPARATOR . "alipay");
if (!defined("ALIPAY_SDK_ROOT"))
{
    define("ALIPAY_SDK_ROOT", dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . ALIPAY_ROOT . DIRECTORY_SEPARATOR);
}
require_once(BFB_SDK_ROOT . 'bfb_pay.cfg.php');
require_once(BFB_SDK_ROOT . 'bfb_sdk.php');
require_once(ALIPAY_SDK_ROOT."alipay.config.php");
require_once(ALIPAY_SDK_ROOT."lib/alipay_submit.class.php");
if($_W['ispost']) {
    $pay_type = $_GPC["pay_type"];
    $order_create_time = date("YmdHis");
    $order_no = $order_create_time . sprintf ( '%06d', rand(0, 999999));
    if($pay_type == "baifubao") {
        $bfb_sdk = new bfb_sdk();
        $expire_time = date('YmdHis', strtotime('+2 day'));
        $goods_category = "";//商品分类号
        $good_name = $_W["username"].iconv("UTF-8", "GBK", urldecode("用户充值"));
        $good_desc = "用户充值描述";
        $goods_url = "";//商品在商户网站上的URL
        $unit_amount = "";//单价
        $unit_count = "";//数量
        $transport_amount = "";//运费
        $total_amount = $_POST['recharge_number'] * 100;//总金额
        $buyer_sp_username = "aaaa";//买家在商户网站的用户名
        $return_url = $_W['siteroot']."web/source/member/common/return_url.inc.php";//后台通知地址
        $page_url = $_W['siteroot']."web/source/member/common/return_url.inc.php";//前台通知地址
        $pay_type = "2";//支付方式(1:余额支付（必须登录百付宝）2:网银支付（在百付宝页面上选择银行，可以不登录百付宝）3:银行网关支付（直接跳到银行的支付页面，无需登录百付宝）)
        $bank_no = "";//默认银行的编码：如果支付方式是银行网关支付，则必须有值。取值范围参见接入指南附录
        $sp_uno = "";//用户在商户端的用户ID(必须唯一)
        $extra = "";//商户自定义数据

        $params = array (
            'service_code' => sp_conf::BFB_PAY_INTERFACE_SERVICE_ID,
            'sp_no' => sp_conf::$SP_NO,
            'order_create_time' => $order_create_time,
            'order_no' => $order_no,
            'goods_category' => $goods_category,
            'goods_name' => $good_name,
            'goods_desc' => $good_desc,
            'goods_url' => $goods_url,
            'unit_amount' => $unit_amount,
            'unit_count' => $unit_count,
            'transport_amount' => $transport_amount,
            'total_amount' => $total_amount,
            'currency' => sp_conf::BFB_INTERFACE_CURRENTCY,
            'buyer_sp_username' => $buyer_sp_username,
            'return_url' => $return_url,
            'page_url' => $page_url,
            'pay_type' => $pay_type,
            'bank_no' => $bank_no,
            'expire_time' => $expire_time,
            'input_charset' => sp_conf::BFB_INTERFACE_ENCODING,
            'version' => sp_conf::BFB_INTERFACE_VERSION,
            'sign_method' => sp_conf::SIGN_METHOD_MD5,
            'extra' =>$extra
        );

        $order_url = $bfb_sdk->create_baifubao_pay_order_url($params,sp_conf::BFB_PAY_DIRECT_NO_LOGIN_URL);

        if(false === $order_url){
            $bfb_sdk->log('create the url for baifubao pay interface failed');
        }else {
            $bfb_sdk->log(sprintf('create the url for baifubao pay interface success, [URL: %s]', $order_url));

            pdo_insert("uni_payorder",array("orderid"=>$order_no,"money"=>doubleval($_POST['recharge_number']),"order_time"=>TIMESTAMP,"uid"=>$_W['uid'],"credittype"=>$_POST["recharge_type"],"pay_type"=>0));
            if(pdo_insertid() > 0) {
                //die(json_encode(array("code"=>1,"message"=>"创建订单成功.","url"=>$order_url)));
                message("正在使用百付宝支付,马上跳转到支付页面,请稍后....",$order_url);
            }
        }
        //die(json_encode(array("code"=>0,"message"=>"创建订单失败.")));
        message("创建订单失败，请选择其他支付方式",url('member/recharge'));
    }else{
        /**************************请求参数**************************/
        //支付类型
        $payment_type = "1";
        //必填，不能修改
        //服务器异步通知页面路径
        $notify_url = $_W['siteroot']."web/source/member/notify_url.ctrl.php";
        //需http://格式的完整路径，不能加?id=123这类自定义参数

        //页面跳转同步通知页面路径
        $return_url =  $_W['siteroot']."web/source/member/notify_url.ctrl.php";
        //需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/

        //卖家支付宝帐户
        $seller_email = $alipay_config['seller_email'];
        //必填

        //商户订单号
        $out_trade_no = $order_no;
        //商户网站订单系统中唯一订单号，必填

        //订单名称
        $subject = "用户充值";
        //必填

        //付款金额
        $total_fee = $_POST['recharge_number'];
        //必填

        //订单描述
        $body = "用户充值";
        //商品展示地址
        $show_url = $_W['siteroot'];
        //需以http://开头的完整路径，例如：http://www.xxx.com/myorder.html

        //防钓鱼时间戳
        $anti_phishing_key = "";
        //若要使用请调用类文件submit中的query_timestamp函数

        //客户端的IP地址
        $exter_invoke_ip = "";
        //非局域网的外网IP地址，如：221.0.0.1

        /************************************************************/
        //构造要请求的参数数组，无需改动
        $parameter = array(
            "service" => "create_direct_pay_by_user",
            "partner" => trim($alipay_config['partner']),
            "payment_type"	=> $payment_type,
            "notify_url"	=> $notify_url,
            "return_url"	=> $return_url,
            "seller_email"	=> $seller_email,
            "out_trade_no"	=> $out_trade_no,
            "subject"	=> $subject,
            "total_fee"	=> $total_fee,
            "body"	=> $body,
            "show_url"	=> $show_url,
            "anti_phishing_key"	=> $anti_phishing_key,
            "exter_invoke_ip"	=> $exter_invoke_ip,
            "_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
        );
        //exit(json_encode($parameter));
        //建立请求
        $alipaySubmit = new AlipaySubmit($alipay_config);
        $html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
        if(empty($html_text)){
            logResult("创建订单失败.");
        }else {
            logResult("创建订单成功.".$out_trade_no);
            pdo_insert("uni_payorder",array("orderid"=>$order_no,"money"=>doubleval($_POST['recharge_number']),"order_time"=>TIMESTAMP,"uid"=>$_W['uid'],"credittype"=>$_POST["recharge_type"],"pay_type"=>1));
            if(pdo_insertid() > 0) {
                //die(json_encode(array("code"=>1,"message"=>"创建订单成功.","url"=>$order_url)));
                //message("正在使用百付宝支付,马上跳转到支付页面,请稍后....",$order_url);
                echo($html_text);
                exit;
            }
        }
    }
}
$settings = get_settings();
$service = explode("|",$settings["service_qqs"]);
$qqs = array();
foreach($service as $ser) {
    list($name,$qq) = explode("-",$ser);
    $qqs[] = array(
        "name"=>$name,
        "qq"=>$qq
    );
}
template('member/recharge');