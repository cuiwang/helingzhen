<?php
require '../../../../framework/bootstrap.inc.php';
require '../../../../addons/ewei_shopv2/defines.php';
require '../../../../addons/ewei_shopv2/core/inc/functions.php';
$ordersn = str($_GET['out_trade_no']);
$attachs = explode(':', str($_GET['body']));
$get = json_encode($_GET);
$get = base64_encode($get);
if (empty($attachs) || !(is_array($attachs))) {
	exit();
}


$uniacid = intval($attachs[0]);
$paytype = intval($attachs[1]);
$url = $_W['siteroot'] . '../../app/index.php?i=' . $uniacid . '&c=entry&m=ewei_shopv2&do=mobile';

if (!(empty($ordersn))) {
	if (p('cashier') && strexists($ordersn, 'CS')) {
		$cashier = pdo_fetch('SELECT * FROM ' . tablename('ewei_shop_cashier_pay_log') . ' WHERE logno=:logno', array(':logno' => $ordersn));

		if (!(empty($cashier))) {
			$uniacid = $cashier['uniacid'];
			$cashierid = $cashier['cashierid'];
			$paytype = 2;
		}

	}


	if ($paytype == 0) {
		$url = $_W['siteroot'] . '../../app/index.php?i=' . $uniacid . '&c=entry&m=ewei_shopv2&do=mobile&r=order.pay_alipay.complete&alidata=' . $get;
	}
	 else if ($paytype == 1) {
		$url = $_W['siteroot'] . '../../app/index.php?i=' . $uniacid . '&c=entry&m=ewei_shopv2&do=mobile&r=order.pay_alipay.recharge_complete&alidata=' . $get;
	}
	 else if ($paytype == 2) {
		$url = $_W['siteroot'] . '../../app/index.php?i=' . $uniacid . '&c=entry&m=ewei_shopv2&do=mobile&r=cashier.pay.success&cashierid=' . $cashierid . '&orderid=' . $ordersn;
	}
	 else if ($paytype == 6) {
		$url = $_W['siteroot'] . '../../app/index.php?i=' . $uniacid . '&c=entry&m=ewei_shopv2&do=mobile&r=threen.register.threen_complete&alidata=' . $get . '&logno=' . $ordersn;
	}

}


header('location: ' . $url);
exit();
function str($str)
{
	$str = str_replace('"', '', $str);
	$str = str_replace('\'', '', $str);
	$str = str_replace('=', '', $str);
	return $str;
}


?>