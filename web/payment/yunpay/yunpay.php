<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>支付跳转中...</title>
</head>
<?php
 //↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
require '../../../../framework/bootstrap.inc.php';
global $_GPC;
 $yunpay = pdo_fetch("SELECT * FROM ".tablename('buymod_payset')." where weid=:weid",array(':weid' => 0));
 $order = pdo_fetch("SELECT * FROM ".tablename("buymod_payrecords")." WHERE orderid = :orderid", array(":orderid"=>$_GPC['order_no']));
//合作身份者id
$yun_config['partner']		= $yunpay['yunpid'];
//安全检验码
$yun_config['key']			= $yunpay['yunkey'];
//云会员账户（邮箱）
$seller_email = $yunpay['yunaccount'];

//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
require_once("lib/yun_md5.function.php");
	

/**************************请求参数**************************/

        //商户订单号
        $out_trade_no = $order['orderid'];//商户网站订单系统中唯一订单号，必填

        //订单名称
        $subject ="用户充值";//必填

        //付款金额
        $total_fee = $order['credit'];//必填 需为整数

        //订单描述

        $body = "用户充值";;
		
		
		//服务器异步通知页面路径
        $nourl = $_W['siteroot']."no_url.php";
        //需http://格式的完整路径，不能加?id=123这类自定义参数

        //页面跳转同步通知页面路径
        $reurl = $_W['siteroot']."re_url.php";
        //需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/
       
		//商品展示地址
        $orurl = "";
        //需http://格式的完整路径，不能加?id=123这类自定义参数，如原网站带有 参数请彩用伪静态或短网址解决

        //商品形象图片地址
        $orimg = "";
        //需http://格式的完整路径，必须为图片完整地址

/************************************************************/

//构造要请求的参数数组，无需改动
$parameter = array(
		"partner" => trim($yun_config['partner']),
		"seller_email"	=> $seller_email,
		"out_trade_no"	=> $out_trade_no,
		"subject"	=> $subject,
		"total_fee"	=> $total_fee,
		"body"	=> $body,
		"nourl"	=> $nourl,
		"reurl"	=> $reurl,
		"orurl"	=> $orurl,
		"orimg"	=> $orimg
);

//建立请求

$html_text = i2e($parameter, "支付进行中...");
echo $html_text;


?>
</body>
</html>