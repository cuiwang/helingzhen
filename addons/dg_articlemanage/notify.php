<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */

error_reporting(1);
define('IN_MOBILE', true);
require '../../framework/bootstrap.inc.php';
require_once  "../../addons/dg_articlemanage/WxPayPubHelper/WxPayPubHelper.php";

$input = file_get_contents('php://input');
WeUtility::logging('info',"付费文章异步通知数据".$input);
global $_W;
//WeUtility::logging('info',"商户key数据".$kjsetting);
$notify=new Notify_pub();
$notify->saveData($input);
$data=$notify->getData();
//$kjsetting=DBUtil::findUnique(DBUtil::$TABLE_WKJ_SETTING,array(":appid"=>$data['appid']));
if(empty($data)){
	$notify->setReturnParameter("return_code","FAIL");
	$notify->setReturnParameter("return_msg","参数格式校验错误");
	WeUtility::logging('info',"付费文章回复参数格式校验错误");
	exit($notify->createXml());
}

if($data['result_code'] !='SUCCESS' || $data['return_code'] !='SUCCESS') {
	$notify->setReturnParameter("return_code","FAIL");
	$notify->setReturnParameter("return_msg","参数格式校验错误");
	WeUtility::logging('info',"付费文章回复参数格式校验错误");
	exit($notify->createXml());
}
//更新表订单信息
WeUtility::logging('info',"更新表订单信息".$data);
	//DBUtil::update(DBUtil::$TABLE_WJK_ORDER,array("status"=>4,'notifytime'=>TIMESTAMP,'wxnotify'=>$data,'wxorder_no'=>$data['transaction_id']),array("order_no"=>$data['out_trade_no']));
    $order_data=array(
    		"order_status"=>1,
    		"transaction_id"=>$data['transaction_id'],
    		"pay_money"=>floatval($data['cash_fee'])/100,
    		"pay_time"=>time()
    );
load()->func('logging');
	pdo_update('dg_article_payment',$order_data,array("out_trade_no"=>$data["out_trade_no"]));
logging_run($data);
logging_run(1230);
logging_run($order_data);
	$notify->setReturnParameter("return_code","SUCCESS");
	$notify->setReturnParameter("return_msg","OK");
	exit($notify->createXml());


WeUtility::logging('info',"付费文章查询回复数据".$data);




