<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
}

class Verify_EweiShopV2Page extends ComWebPage
{
	public function __construct($_com = 'verify')
	{
		parent::__construct($_com);
	}

	public function main()
	{
		global $_W;
		global $_GPC;

		if ($_W['ispost']) {
			$verifytype = intval($_GPC['verifytype']);
			$verifycode = trim($_GPC['verifycode']);

			if ($verifytype == 2) {
				if (empty($verifycode)) {
					show_json(0, '请填写核销码');
				}

				$verifygood = pdo_fetch('select *  from ' . tablename('ewei_shop_verifygoods') . ' where uniacid=:uniacid and  verifycode=:verifycode  limit 1 ', array(':uniacid' => $_W['uniacid'], ':verifycode' => $verifycode));

				if (empty($verifygood)) {
					show_json(0, '未查询到记次时商品或核销码已过期,请核对核销码');
				}

				if (intval($verifygood['codeinvalidtime']) < time()) {
					show_json(0, '核销码已过期,请用户重新获取核销码');
				}

				show_json(1, array('url' => webUrl('store/verify/verifygoods', array('verifycode' => $verifycode))));
			}

			show_json(0);
		}

		include $this->template();
	}

	public function verifygoods()
	{
		global $_W;
		global $_GPC;
		$verifycode = trim($_GPC['verifycode']);

		if (empty($verifycode)) {
			$this->message('未查询到记次时商品或核销码已失效,请核对核销码!', '', 'error');
		}

		$verifygood = pdo_fetch('select vg.*,g.id as goodsid ,g.title,g.subtitle,g.thumb  from ' . tablename('ewei_shop_verifygoods') . "   vg\r\n\t\t inner join " . tablename('ewei_shop_order_goods') . " og on vg.ordergoodsid = og.id\r\n\t\t inner join " . tablename('ewei_shop_goods') . " g on og.goodsid = g.id\r\n\t\t where   vg.verifycode=:verifycode and vg.uniacid=:uniacid  limit 1", array(':uniacid' => $_W['uniacid'], ':verifycode' => $verifycode));

		if (empty($verifygood)) {
			$this->message('未查询到记次时商品或核销码已失效,请核对核销码!', '', 'error');
		}

		if (intval($verifygood['codeinvalidtime']) < time()) {
			$this->message('核销码已过期,请用户重新获取核销码!', '', 'error');
		}

		if (!empty($verifygood['limitnum'])) {
			$verifygoodlogs = pdo_fetchall('select *  from ' . tablename('ewei_shop_verifygoods_log') . '    where verifygoodsid =:id  ', array(':id' => $verifygood['id']));
			$verifynum = 0;

			foreach ($verifygoodlogs as $verifygoodlog) {
				$verifynum += intval($verifygoodlog['verifynum']);
			}

			$lastverifys = intval($verifygood['limitnum']) - $verifynum;
		}

		$termofvalidity = date('Y-m-d', intval($verifygood['starttime']) + ($verifygood['limitdays'] * 86400));

		if (!empty($verifygood['storeid'])) {
			$store = pdo_fetch('select * from ' . tablename('ewei_shop_store') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $verifygood['storeid'], ':uniacid' => $_W['uniacid']));
		}

		include $this->template();
	}

	public function log()
	{
		global $_W;
		global $_GPC;
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$condition = ' vg.uniacid = :uniacid';
		$params = array(':uniacid' => $_W['uniacid']);
		$searchfield = strtolower(trim($_GPC['searchfield']));
		$keyword = trim($_GPC['keyword']);
		if (!empty($searchfield) && !empty($keyword)) {
			if ($searchfield == 'ordersn') {
				$condition .= ' and o.ordersn like :keyword';
			}
			else if ($searchfield == 'verifyid') {
				$condition .= ' and vg.id like :keyword';
			}
			else if ($searchfield == 'store') {
				$condition .= ' and s.storename like :keyword';
			}
			else {
				if ($searchfield == 'goodtitle') {
					$condition .= ' and g.title like :keyword';
				}
			}

			$params[':keyword'] = '%' . $keyword . '%';
		}

		if (!empty($_GPC['verifydate']['start']) && !empty($_GPC['verifydate']['end'])) {
			$verifystarttime = strtotime($_GPC['verifydate']['start']);
			$verifyendtime = strtotime($_GPC['verifydate']['end']);
			$condition .= ' AND vgl.verifydate >= :verifystarttime AND vgl.verifydate <= :verifyendtime ';
			$params[':verifystarttime'] = $verifystarttime;
			$params[':verifyendtime'] = $verifyendtime;
		}

		if (!empty($_GPC['buydate']['start']) && !empty($_GPC['buydate']['end'])) {
			$buystarttime = strtotime($_GPC['buydate']['start']);
			$buyendtime = strtotime($_GPC['buydate']['end']);
			$condition .= ' AND o.paytime >= :buystarttime AND o.paytime <= :buyendtime ';
			$params[':buystarttime'] = $buystarttime;
			$params[':buyendtime'] = $buyendtime;
		}

		$sql = 'select vg.*,g.id as goodsid ,g.title,g.thumb,o.ordersn,vgl.verifydate,o.paytime,s.storename,sa.salername,vgl.remarks,o.openid  from ' . tablename('ewei_shop_verifygoods_log') . "   vgl\r\n\t\t left join " . tablename('ewei_shop_verifygoods') . " vg on vg.id = vgl.verifygoodsid\r\n\t\t left join " . tablename('ewei_shop_store') . " s  on s.id = vgl.storeid\r\n\t\t left join " . tablename('ewei_shop_saler') . " sa  on sa.id = vgl.salerid\r\n\t\t left join " . tablename('ewei_shop_order_goods') . " og on vg.ordergoodsid = og.id\r\n\t\t left join " . tablename('ewei_shop_order') . " o on o.id = og.orderid\r\n\t\t left join " . tablename('ewei_shop_goods') . " g on og.goodsid = g.id\r\n\t\t where  1 and  " . $condition . ' ORDER BY vgl.verifydate DESC ';
		$sql .= ' LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize;
		$list = pdo_fetchall($sql, $params);

		foreach ($list as &$rom) {
			$member = m('member')->getMember($rom['openid']);
			$rom['username'] = $member['realname'];
			$rom['mobile'] = $member['mobile'];
		}

		unset($rom);
		$total = pdo_fetchcolumn('select  COUNT(*)   from ' . tablename('ewei_shop_verifygoods_log') . "   vgl\r\n\t\t left join " . tablename('ewei_shop_verifygoods') . " vg on vg.id = vgl.verifygoodsid\r\n\t\t left join " . tablename('ewei_shop_store') . " s  on s.id = vgl.storeid\r\n\t\t left join " . tablename('ewei_shop_saler') . " sa  on sa.id = vgl.salerid\r\n\t\t left join " . tablename('ewei_shop_order_goods') . " og on vg.ordergoodsid = og.id\r\n\t\t left join " . tablename('ewei_shop_order') . " o on o.id = og.orderid\r\n\t\t left join " . tablename('ewei_shop_goods') . " g on og.goodsid = g.id\r\n\t\t  where  1 and  " . $condition . ' ORDER BY vgl.verifydate DESC ', $params);
		$pager = pagination2($total, $pindex, $psize);
		include $this->template();
	}
}

?>
