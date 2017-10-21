<?php
/* *
 * 功能：服务器同步通知页面
 */

require_once '../../../framework/bootstrap.inc.php';
require '../../../app/common/bootstrap.app.inc.php';
load()->app('common');
load()->app('template');


$tid = $_REQUEST['i2'];
$module = 'cgc_ad';

$sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE  `module`=:module AND `tid`=:tid';
$pars = array();
$pars[':module'] = $module;
$pars[':tid'] = $tid;
$record = pdo_fetch($sql, $pars);
if (empty($record)){
	WeUtility::logging($module.' no_url', "record error");
	exit('fail');
}


$uniacid = $record['uniacid'];

require_once("yun.config.php");

require_once("lib/yun_md5.function.php");

//计算得出通知验证结果
$yunNotify = md5Verify($_REQUEST['i1'],$_REQUEST['i2'],$_REQUEST['i3'],$yun_config['key'],$yun_config['partner']);
if($yunNotify) {//验证成功
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//商户订单号
	$out_trade_no = $_REQUEST['i2'];
	//云支付交易号
	$trade_no = $_REQUEST['i4'];
	//价格
	$yunprice=$_REQUEST['i1'];
	
	$_W['uniacid'] = $uniacid;
	
	$site = WeUtility::createModuleSite($module);
	
	if(is_error($site)) {
		WeUtility::logging($module.' no_url', "site error");
		exit('fail');
	}
	
	$method = 'payResult';
	if (method_exists($site, $method)) {
	  $ret = array();			
	  $ret['uniacid'] = $_W['uniacid'];
	  $ret['module'] = $record['module'];
	  $ret['result'] = 'success';
	  $ret['type'] = "yun_pay";
	  $ret['from'] = 'notify';
	  //异步请求
	  $ret['sync'] = false;
	  $ret['tid'] = $tid;
	  $ret['user'] = $record['openid'];
	  $ret['fee'] = $record['fee'];					
	  $site->$method($ret);
	  exit('success');
	} else {
	   WeUtility::logging($module.' callback', "settings error");
	   exit('fail');	
	} 
} else {
  exit('fail');
}
      