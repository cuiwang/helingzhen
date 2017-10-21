<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
}

class Fullback_EweiShopV2Page extends MobileLoginPage
{
	public function main()
	{
		global $_W;
		global $_GPC;
		$_GPC['type'] = intval($_GPC['type']);
		include $this->template();
	}

	public function get_list()
	{
		global $_W;
		global $_GPC;
		$isfullback = intval($_GPC['type']);
		$pindex = max(1, intval($_GPC['page']));
		$psize = 10;
		$condition = ' and fl.openid=:openid and fl.uniacid=:uniacid and fl.isfullback=:isfullback';
		$params = array(':uniacid' => $_W['uniacid'], ':openid' => $_W['openid'], ':isfullback' => $isfullback);
		$list = array();
		$list = pdo_fetchall('select fl.*,g.thumb,g.title,og.optionname from ' . tablename('ewei_shop_fullback_log') . " as fl\r\n            left join " . tablename('ewei_shop_goods') . " as g on g.id = fl.goodsid\r\n            left join " . tablename('ewei_shop_order_goods') . " as og on og.orderid = fl.orderid and og.goodsid = fl.goodsid \r\n            where 1 " . $condition . ' group by fl.id order by fl.createtime desc LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize, $params);
		$total = pdo_fetchcolumn('select count(1) from ' . tablename('ewei_shop_fullback_log') . " as fl\r\n            where 1 " . $condition . ' order by fl.createtime desc ', $params);

		foreach ($list as &$row) {
			$row['createtime'] = date('Y/m/d H:i:s', $row['createtime']);
			$row['price'] = price_format($row['price'], 2);
			$row['priceevery'] = price_format($row['priceevery'], 2);

			if ($row['fullbackday'] < $row['day']) {
				$row['surplusday'] = $row['day'] - $row['fullbackday'];
				$row['surplusprice'] = $row['priceevery'] * $row['fullbackday'];
			}
			else {
				$row['surplusday'] = 0;
				$row['surplusprice'] = $row['price'];
			}

			$row = set_medias($row, array('thumb'));
		}

		unset($row);
		show_json(1, array('list' => $list, 'total' => $total, 'pagesize' => $psize));
	}
}

?>
