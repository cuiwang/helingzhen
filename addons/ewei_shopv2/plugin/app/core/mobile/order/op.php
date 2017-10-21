<?php

if (!defined('IN_IA')) {
	exit('Access Denied');
}
 
require EWEI_SHOPV2_PLUGIN . 'app/core/page_mobile.php';
class Op_EweiShopV2Page extends AppMobilePage
{
	/**
     * 取消订单
     * @global type $_W
     * @global type $_GPC
     */
	public function cancel()
	{
		global $_W;
		global $_GPC;
		$orderid = intval($_GPC['id']);

		if (empty($orderid)) {
			app_error(AppError::$ParamsError);
		}

		$order = pdo_fetch('select id,ordersn,openid,status,deductcredit,deductcredit2,deductprice,couponid from ' . tablename('ewei_shop_order') . ' where id=:id and uniacid=:uniacid and openid=:openid limit 1', array(':id' => $orderid, ':uniacid' => $_W['uniacid'], ':openid' => $_W['openid']));

		if (empty($order)) {
			app_error(AppError::$OrderNotFound);
		}

		if (0 < $order['status']) {
			app_error(AppError::$OrderCannotCancel);
		}

		if ($order['status'] < 0) {
			app_error(AppError::$OrderCannotCancel);
		}

		m('order')->setStocksAndCredits($orderid, 2);

		if (0 < $order['deductprice']) {
			m('member')->setCredit($order['openid'], 'credit1', $order['deductcredit'], array('0', $_W['shopset']['shop']['name'] . '购物返还抵扣积分 积分: ' . $order['deductcredit'] . ' 抵扣金额: ' . $order['deductprice'] . ' 订单号: ' . $order['ordersn']));
		}

		m('order')->setDeductCredit2($order);
		if (com('coupon') && !empty($order['couponid'])) {
			com('coupon')->returnConsumeCoupon($orderid);
		}

		pdo_update('ewei_shop_order', array('status' => -1, 'canceltime' => time(), 'closereason' => trim($_GPC['remark'])), array('id' => $order['id'], 'uniacid' => $_W['uniacid']));
		m('notice')->sendOrderMessage($orderid);
		app_json();
	}

	/**
     * 确认收货
     * @global type $_W
     * @global type $_GPC
     */
	public function finish()
	{
		global $_W;
		global $_GPC;
		$orderid = intval($_GPC['id']);

		if (empty($orderid)) {
			app_error(AppError::$ParamsError);
		}

		$order = pdo_fetch('select id,status,openid,couponid,refundstate,refundid from ' . tablename('ewei_shop_order') . ' where id=:id and uniacid=:uniacid and openid=:openid limit 1', array(':id' => $orderid, ':uniacid' => $_W['uniacid'], ':openid' => $_W['openid']));

		if (empty($order)) {
			app_error(AppError::$OrderNotFound);
		}

		if ($order['status'] != 2) {
			app_error(AppError::$OrderCannotFinish);
		}

		if ((0 < $order['refundstate']) && !empty($order['refundid'])) {
			$change_refund = array();
			$change_refund['status'] = -2;
			$change_refund['refundtime'] = time();
			pdo_update('ewei_shop_order_refund', $change_refund, array('id' => $order['refundid'], 'uniacid' => $_W['uniacid']));
		}

		pdo_update('ewei_shop_order', array('status' => 3, 'finishtime' => time(), 'refundstate' => 0), array('id' => $order['id'], 'uniacid' => $_W['uniacid']));
		m('member')->upgradeLevel($order['openid']);
		m('order')->setGiveBalance($orderid, 1);
		if (com('coupon') && !empty($order['couponid'])) {
			com('coupon')->backConsumeCoupon($orderid);
		}

		m('notice')->sendOrderMessage($orderid);

		if (p('commission')) {
			p('commission')->checkOrderFinish($orderid);
		}

		app_json();
	}

	/**
     * 删除或恢复订单
     * @global type $_W
     * @global type $_GPC
     */
	public function delete()
	{
		global $_W;
		global $_GPC;
		$orderid = intval($_GPC['id']);
		$userdeleted = intval($_GPC['userdeleted']);

		if (empty($orderid)) {
			app_error(AppError::$ParamsError);
		}

		$order = pdo_fetch('select id,status,refundstate,refundid from ' . tablename('ewei_shop_order') . ' where id=:id and uniacid=:uniacid and openid=:openid limit 1', array(':id' => $orderid, ':uniacid' => $_W['uniacid'], ':openid' => $_W['openid']));

		if (empty($order)) {
			app_error(AppError::$OrderNotFound);
		}

		if ($userdeleted == 0) {
			if ($order['status'] != 3) {
				app_error(AppError::$OrderCannotRestore);
			}
		}
		else {
			if (($order['status'] != 3) && ($order['status'] != -1)) {
				app_error(AppError::$OrderCannotDelete);
			}

			if ((0 < $order['refundstate']) && !empty($order['refundid'])) {
				$change_refund = array();
				$change_refund['status'] = -2;
				$change_refund['refundtime'] = time();
				pdo_update('ewei_shop_order_refund', $change_refund, array('id' => $order['refundid'], 'uniacid' => $_W['uniacid']));
			}
		}

		pdo_update('ewei_shop_order', array('userdeleted' => $userdeleted, 'refundstate' => 0), array('id' => $order['id'], 'uniacid' => $_W['uniacid']));
		app_json();
	}
}

?>
