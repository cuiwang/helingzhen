<?php
/**
 * [WeiZan System] Copyright (c) 2014 012wz.com
 * WeiZan is  a free software, it under the license terms, visited http://www.012wz.com/ for more details.
 */

error_reporting(1);
define('IN_MOBILE', true);
define("MON_WKJ", "mon_wkj");
require '../../framework/bootstrap.inc.php';
require_once  "WxPayPubHelper/WxPayPubHelper.php";
require_once "monUtil.class.php";
require_once "dbutil.class.php";
$input = file_get_contents('php://input');
WeUtility::logging('info',"微砍价异步通知数据".$input);

//WeUtility::logging('info',"商户key数据".$kjsetting);
$notify=new Notify_pub();
$notify->saveData($input);
$data=$notify->getData();
$kjsetting=DBUtil::findUnique(DBUtil::$TABLE_WKJ_SETTING,array(":appid"=>$data['appid']));
if(empty($data)){
	$notify->setReturnParameter("return_code","FAIL");
	$notify->setReturnParameter("return_msg","参数格式校验错误1");
	WeUtility::logging('info',"微砍价回复参数格式校验错误");
	exit($notify->createXml());
}

if($data['result_code'] !='SUCCESS' || $data['return_code'] !='SUCCESS') {
	$notify->setReturnParameter("return_code","FAIL");
	$notify->setReturnParameter("return_msg","参数格式校验错误2");
	WeUtility::logging('info',"微砍价回复参数格式校验错误");
	exit($notify->createXml());
}
//更新表订单信息

WeUtility::logging('info',"通知订单更新");
if($notify->checkSign($kjsetting['shkey'])) {
	$oid = substr($data['out_trade_no'], 15);
	WeUtility::logging('info',"oid". $oid);
	DBUtil::updateById(DBUtil::$TABLE_WJK_ORDER, array("status"=>4,'notifytime'=>TIMESTAMP,'wxnotify'=>'','wxorder_no'=>$data['transaction_id']), $oid);
	$notify->setReturnParameter("return_code","SUCCESS");
	$notify->setReturnParameter("return_msg","OK");
	exit($notify->createXml());
} else {
	$notify->setReturnParameter("return_code","FAIL");
	$notify->setReturnParameter("return_msg","签名校验错误");
	WeUtility::logging('info',"签名校验错误");
	exit($notify->createXml());
}

WeUtility::logging('info',"微砍价回复数据".$data);




