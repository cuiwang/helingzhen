<?php
error_reporting(0);
define('IN_MOBILE', true);
require_once("lib/yun_md5.function.php");
require '../../../framework/bootstrap.inc.php';
$sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `tid`=:tid';
$params = array();
$params[':tid'] = trim($_REQUEST['i2']);
$get = pdo_fetch($sql, $params);
$_W['uniacid'] = $_W['weid'] = $get['uniacid'];
$setting = uni_setting($_W['uniacid'], array('payment'));
/*********判断是否支持uni_setting开始********/
if(empty($setting['payment']['yunpay']['partner'])){
	$sql = 'SELECT `settings` FROM ' . tablename('uni_account_modules') . ' WHERE `uniacid` = :uniacid AND `module` = :module';
	$settings = pdo_fetchcolumn($sql, array(':uniacid' => $_W['uniacid'], ':module' => 'weliam_indiana'));
	$yunpays = iunserializer($settings);
	$setting['payment']['yunpay']['partner'] = $yunpays['yunpay_partner'];
	$setting['payment']['yunpay']['key'] = $yunpays['yunpay_key'];
	$setting['payment']['yunpay']['seller_email'] = $yunpays['yunpay_seller_email'];
}
/*********判断是否支持uni_setting结束********/

if(!is_array($setting['payment'])) {
	exit('没有设定支付参数.');
}
if($get['status']==1) {
	$log = $get;
	if(!empty($log)) {
		if (!empty($log['tag'])) {
			$tag = iunserializer($log['tag']);
			$log['uid'] = $tag['uid'];
		}
		$site = WeUtility::createModuleSite($log['module']);
		if(!is_error($site)) {
			$method = 'payResult';
			if (method_exists($site, $method)) {
				$ret = array();
				$ret['weid'] = $log['uniacid'];
				$ret['uniacid'] = $log['uniacid'];
				$ret['result'] = 'success';
				$ret['type'] = $log['type'];
				$ret['from'] = 'return';
				$ret['tid'] = $log['tid'];
				$ret['uniontid'] = $log['uniontid'];
				$ret['user'] = $log['openid'];
				$ret['fee'] = $log['fee'];
				$ret['tag'] = $tag;
				$ret['is_usecard'] = $log['is_usecard'];
				$ret['card_type'] = $log['card_type'];
				$ret['card_fee'] = $log['card_fee'];
				$ret['card_id'] = $log['card_id'];
				exit($site->$method($ret));
			}
		}
	}
}
if(is_array($setting['payment'])) {
	$yunpay = $setting['payment']['yunpay'];
	if(!empty($yunpay)) {
		////////////////////////////////////////////////////////////////
		//计算得出通知验证结果
	$yunNotify = md5Verify($_REQUEST['i1'],$_REQUEST['i2'],$_REQUEST['i3'],$yunpay['key'],$yunpay['partner']);
	//echo $yunNotify;exit;
	if($yunNotify) {//验证成功
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//商户订单号
	$out_trade_no = $_REQUEST['i2'];
	//云支付交易号
	$trade_no = $_REQUEST['i4'];
	//价格
	$yunprice=$_REQUEST['i1'];
	/*********************检测支付是否异常开始**********************/
	/*$sql_y = "select * from".tablename('weliam_indiana_rechargerecord')."where uniacid = :uniacid and ordersn = :ordersn";
	$recharge = pdo_fetch($sql_y,array(':uniacid'=>$_W['uniacid'],':ordersn'=>$out_trade_no));
	if($recharge['num'] != $yunprice || $yunprice<1){
		echo "存在非法操作，如果此操作造成我方严重影响，将追究你的责任！！！";
		exit;
	}*/
	/*********************检测支付是否异常结束**********************/
		/////////////////////////////////////////////////////////////////
			if(!empty($get)){
				$log = $get;
				}else{
				$sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `tid`=:tid';
				$params = array();
				$params[':tid'] = $_REQUEST['i2'];
				$log = pdo_fetch($sql, $params);
				}
			if(!empty($log)) {
				if($log['status'] == '0'){
					$log['tag'] = iunserializer($log['tag']);
					$log['tag']['transaction_id'] = $trade_no;//云支付交易号
					$log['uid'] = $log['tag']['uid'];
					$record = array();
					$record['status'] = '1';
					$record['tag'] = iserializer($log['tag']);		
					pdo_update('core_paylog', $record, array('plid' => $log['plid']));
				}
				$site = WeUtility::createModuleSite($log['module']);
				if(!is_error($site)) {
					$method = 'payResult';
					if (method_exists($site, $method)) {
						$ret = array();
						$ret['weid'] = $log['weid'];
						$ret['uniacid'] = $log['uniacid'];
						$ret['acid'] = $log['acid'];
						$ret['result'] = 'success';
						$ret['type'] = $log['type'];
						$ret['from'] = 'notify';
						$ret['tid'] = $log['tid'];
						$ret['uniontid'] = $log['uniontid'];
						$ret['user'] = empty($get['openid']) ? $log['openid'] : $get['openid'];
						$ret['fee'] = $log['fee'] = $yunprice;
						$ret['tag'] = $log['tag'];
						$ret['is_usecard'] = $log['is_usecard'];
						$ret['card_type'] = $log['card_type'];
						$ret['card_fee'] = $log['card_fee'];
						$ret['card_id'] = $log['card_id'];
						$site->$method($ret);
						exit('success');
					}
				}
			}
		}
	}
}
exit('fail');