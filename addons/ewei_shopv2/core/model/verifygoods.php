<?php
class Verifygoods_EweiShopV2Model
{
	/**
     *
     * @param type $orderid
     */
	public function createverifygoods($orderid)
	{
		global $_W;
		$verifygoods = pdo_fetchall('select  *   from  ' . tablename('ewei_shop_verifygoods') . ' where  orderid=:orderid ', array(':orderid' => $orderid));

		if (!empty($verifygoods)) {
			return false;
		}

		if (p('newstore')) {
			$sql2 = ',o.storeid,o.isnewstore';
		}

		$ordergoods = pdo_fetchall('select o.openid,o.uniacid,o.id as orderid , og.id as ordergoodsid,g.verifygoodsdays,g.verifygoodsnum,g.verifygoodslimittype,g.verifygoodslimitdate,og.total ' . $sql2 . ' from ' . tablename('ewei_shop_order_goods') . '   og inner join ' . tablename('ewei_shop_goods') . " g on og.goodsid = g.id\r\n          inner join " . tablename('ewei_shop_order') . " o on og.orderid = o.id\r\n          where   og.orderid =:orderid and g.type = 5", array(':orderid' => $orderid));
		$time = time();

		foreach ($ordergoods as $ordergood) {
			$total = intval($ordergood['total']);
			$i = 0;

			while ($i < $total) {
				$data = array('uniacid' => $ordergood['uniacid'], 'openid' => $ordergood['openid'], 'orderid' => $ordergood['orderid'], 'ordergoodsid' => $ordergood['ordergoodsid'], 'starttime' => $time, 'limittype' => intval($ordergood['verifygoodslimittype']), 'limitdate' => intval($ordergood['verifygoodslimitdate']), 'limitdays' => intval($ordergood['verifygoodsdays']), 'limitnum' => intval($ordergood['verifygoodsnum']), 'used' => 0, 'invalid' => 0);

				if (p('newstore')) {
					if (!empty($ordergoods['storeid']) && !empty($ordergoods['isnewstore'])) {
						$data['storeid'] = intval($ordergoods['storeid']);
					}
				}

				pdo_insert('ewei_shop_verifygoods', $data);
				++$i;
			}
		}

		return true;
	}

	/**
     *
     * @param type $openid
     */
	public function getCanUseVerifygoods($openid)
	{
		global $_W;
		$sql = 'select vg.*,g.title,g.subtitle,g.thumb,c.card_id  from ' . tablename('ewei_shop_verifygoods') . "   vg\r\n                 inner join " . tablename('ewei_shop_order_goods') . " og on vg.ordergoodsid = og.id\r\n                 left  join " . tablename('ewei_shop_order') . " o on vg.orderid = o.id\r\n                 left  join " . tablename('ewei_shop_order_refund') . " orf on o.refundid = orf.id\r\n                 inner join " . tablename('ewei_shop_goods') . " g on og.goodsid = g.id\r\n                 left  join " . tablename('ewei_shop_goods_cards') . " c on c.id = g.cardid\r\n                 where   vg.uniacid=:uniacid and vg.openid=:openid and vg.invalid =0 and (o.refundid=0 or orf.status<0) and o.status>0\r\n                 and    ((vg.limittype=0   and vg.limitdays * 86400 + vg.starttime >=unix_timestamp() )or ( vg.limittype=1   and vg.limitdate >=unix_timestamp() ))  and  vg.used =0 order by vg.starttime desc";
		$verifygoods = set_medias(pdo_fetchall($sql, array(':uniacid' => $_W['uniacid'], ':openid' => $openid)), 'thumb');

		if (empty($verifygoods)) {
			$verifygoods = array();
		}

		foreach ($verifygoods as $i => &$row) {
			$row['numlimit'] = 0;

			if (!empty($row['limitnum'])) {
				$row['numlimit'] = 1;
			}

			if (is_weixin()) {
				if (!empty($row['card_id']) && empty($row['activecard'])) {
					$row['cangetcard'] = 1;
				}
			}
		}

		unset($row);
		return $verifygoods;
	}

	/**
     *
     * @param type $openid
     */
	public function checkhaveverifygoods($openid)
	{
		global $_W;
		$sql = 'select  COUNT(1)  from ' . tablename('ewei_shop_verifygoods') . "   vg\r\n                 inner join " . tablename('ewei_shop_order_goods') . " og on vg.ordergoodsid = og.id\r\n                 left  join " . tablename('ewei_shop_order') . " o on vg.orderid = o.id\r\n                 left  join " . tablename('ewei_shop_order_refund') . " orf on o.refundid = orf.id\r\n                 inner join " . tablename('ewei_shop_goods') . " g on og.goodsid = g.id\r\n                 left  join " . tablename('ewei_shop_goods_cards') . " c on c.id = g.cardid\r\n                 where   vg.uniacid=:uniacid and vg.openid=:openid and vg.invalid =0   order by vg.starttime desc";
		$verifygoods = pdo_fetchcolumn($sql, array(':uniacid' => $_W['uniacid'], ':openid' => $openid));

		if (!empty($verifygoods)) {
			return 1;
		}

		return 0;
	}
}

if (!defined('IN_IA')) {
	exit('Access Denied');
}

?>
