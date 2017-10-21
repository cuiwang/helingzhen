<?php
function goodsReceive($order, $sysday = 0)
{
	$days = array();

	if (checkFetchOrder($order)) {
		return false;
	}

	$isonlyverifygoods = m('order')->checkisonlyverifygoods($order['id']);

	if ($isonlyverifygoods) {
		return false;
	}

	if ($order['merchid'] == 0) {
		$goods = pdo_fetchall('select og.goodsid, g.autoreceive from' . tablename('ewei_shop_order_goods') . ' og left join ' . tablename('ewei_shop_goods') . ' g on g.id=og.goodsid where og.orderid=' . $order['id']);

		foreach ($goods as $i => $g) {
			$days[] = $g['autoreceive'];
		}

		$day = max($days);
	}
	else {
		$day = 0;
	}

	if ($day < 0) {
		return false;
	}

	if ($day == 0) {
		if ($sysday <= 0) {
			return false;
		}

		$day = $sysday;
	}

	$daytimes = 86400 * $day;

	if (($order['sendtime'] + $daytimes) <= time()) {
		return true;
	}

	return false;
}

function checkFetchOrder($order)
{
	if (($order['isverify'] != 1) && empty($item['addressid']) && empty($item['isvirtualsend']) && empty($item['virtual']) && $item['dispatchtype']) {
		return true;
	}

	return false;
}

error_reporting(0);
require '../../../../../framework/bootstrap.inc.php';
require '../../../../../addons/ewei_shopv2/defines.php';
require '../../../../../addons/ewei_shopv2/core/inc/functions.php';
require '../../../../../addons/ewei_shopv2/core/inc/plugin_model.php';
global $_W;
global $_GPC;
ignore_user_abort();
set_time_limit(0);
$sets = pdo_fetchall('select uniacid from ' . tablename('ewei_shop_sysset'));

foreach ($sets as $set) {
	$_W['uniacid'] = $set['uniacid'];

	if (empty($_W['uniacid'])) {
		continue;
	}

	$trade = m('common')->getSysset('trade', $_W['uniacid']);
	$days = intval($trade['receive']);
	$p = p('commission');
	$pcoupon = com('coupon');
	$orders = pdo_fetchall('select id,couponid,openid,isparent,sendtime,price,merchid,isverify,addressid,isvirtualsend,virtual,dispatchtype from ' . tablename('ewei_shop_order') . ' where uniacid=' . $_W['uniacid'] . ' and status=2 ', array(), 'id');

	if (!empty($orders)) {
		foreach ($orders as $orderid => $order) {
			$result = goodsReceive($order, $days);

			if (!$result) {
				continue;
			}

			pdo_query('update ' . tablename('ewei_shop_order') . ' set status=3,finishtime=' . time() . ' where id=' . $orderid);

			if ($order['isparent'] == 1) {
				continue;
			}

			m('member')->upgradeLevel($order['openid'], $orderid);
			m('order')->setGiveBalance($orderid, 1);
			m('notice')->sendOrderMessage($orderid);
			m('order')->fullback($orderid);

			if ($pcoupon) {
				com('coupon')->sendcouponsbytask($item['id']);

				if (!empty($order['couponid'])) {
					$pcoupon->backConsumeCoupon($order['id']);
				}
			}

			if ($p) {
				$p->checkOrderFinish($orderid);
			}

			if (p('lottery') && ($order['merchid'] == 0)) {
				$res = p('lottery')->getLottery($order['openid'], 1, array('money' => $order['price'], 'paytype' => 2));

				if ($res) {
					p('lottery')->getLotteryList($order['openid'], array('lottery_id' => $res));
				}
			}
		}
	}
}

?>
