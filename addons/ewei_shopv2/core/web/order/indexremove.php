<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
}

class Indexremove_EweiShopV2Page extends WebPage
{
	public function main()
	{
		global $_W;
		global $_GPC;

		if ($_W['ispost']) {
			$orderid = trim($_GPC['data']['orderid']);
			$recharge = trim($_GPC['data']['recharge']);
			$params = array('uniacid' => $_W['uniacid']);

			if (!empty($orderid)) {
				$params['ordersn'] = $orderid;
				$order = pdo_get('ewei_shop_order', $params);
				pdo_delete('ewei_shop_order', array('uniacid' => $_W['uniacid'], 'id' => $order['id']));
				pdo_delete('ewei_shop_order_goods', array('uniacid' => $_W['uniacid'], 'orderid' => $order['id']));
				pdo_delete('ewei_shop_order_comment', array('uniacid' => $_W['uniacid'], 'orderid' => $order['id']));
				pdo_delete('ewei_shop_order_refund', array('uniacid' => $_W['uniacid'], 'orderid' => $order['id']));
				show_json(1);
			}

			if (!empty($recharge)) {
				$params['logno'] = $recharge;
				pdo_delete('ewei_shop_member_log', $params);
				show_json(1);
			}
		}

		include $this->template();
	}
}

?>
