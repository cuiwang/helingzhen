<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
}

class Verifygoods_EweiShopV2Page extends MobilePage
{
	public function detail()
	{
		global $_W;
		global $_GPC;
		$openid = $_W['openid'];
		$verifycode = trim($_GPC['verifycode']);
		$id = trim($_GPC['id']);

		if (empty($verifycode)) {
			$this->message('未查询到记次时商品或核销码已失效,请核对核销码!', '', 'error');
		}

		$item = pdo_fetch('select vg.*,g.id as goodsid ,g.title,g.subtitle,g.thumb,vg.storeid  from ' . tablename('ewei_shop_verifygoods') . "   vg\r\n\t\t inner join " . tablename('ewei_shop_order_goods') . " og on vg.ordergoodsid = og.id\r\n\t\t inner join " . tablename('ewei_shop_goods') . " g on og.goodsid = g.id\r\n\t\t where  vg.id =:id and  vg.verifycode=:verifycode and vg.uniacid=:uniacid  limit 1", array(':id' => $id, ':uniacid' => $_W['uniacid'], ':verifycode' => $verifycode));

		if (empty($item)) {
			$this->message('未查询到记次时商品或核销码已失效,请核对核销码!', '', 'error');
		}

		if (intval($item['codeinvalidtime']) < time()) {
			$this->message('核销码已过期,请用户重新获取核销码!', '', 'error');
		}

		$saler = pdo_fetch('select * from ' . tablename('ewei_shop_saler') . ' where  status=1  and openid=:openid and uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid'], ':openid' => $openid));

		if (empty($saler)) {
			$this->message('您不是核销员,无权核销', '', 'error');
		}

		$store = pdo_fetch('select * from ' . tablename('ewei_shop_store') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $saler['storeid'], ':uniacid' => $_W['uniacid']));
		if (!empty($item['storeid']) && !empty($store) && ($item['storeid'] != $store['id'])) {
			$this->message('该商品无法在您所属门店核销!请重新确认!', '', 'error');
		}

		if (!empty($item['limitnum'])) {
			$verifygoodlogs = pdo_fetchall('select *  from ' . tablename('ewei_shop_verifygoods_log') . '    where verifygoodsid =:id  ', array(':id' => $item['id']));
			$verifynum = 0;

			foreach ($verifygoodlogs as $verifygoodlog) {
				$verifynum += intval($verifygoodlog['verifynum']);
			}

			$lastverifys = intval($item['limitnum']) - $verifynum;
		}

		if (empty($item['limittype'])) {
			$limitdate = intval($item['starttime']) + (intval($item['limitdays']) * 86400);
		}
		else {
			$limitdate = intval($item['limitdate']);
		}

		if ($limitdate < time()) {
			$this->message('该商品已过期!', '', 'error');
		}

		$termofvalidity = date('Y-m-d H:i', $limitdate);
		include $this->template();
	}

	public function main()
	{
		global $_W;
		global $_GPC;
		$openid = $_W['openid'];
		$saler = pdo_fetch('select * from ' . tablename('ewei_shop_saler') . ' where status=1  and openid=:openid and uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid'], ':openid' => $openid));

		if (empty($saler)) {
			$this->message('您无核销权限!');
		}

		$member = m('member')->getMember($saler['openid']);
		$store = false;

		if (!empty($saler['storeid'])) {
			$store = pdo_fetch('select * from ' . tablename('ewei_shop_store') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $saler['storeid'], ':uniacid' => $_W['uniacid']));
		}

		include $this->template();
	}

	public function search()
	{
		global $_W;
		global $_GPC;
		$openid = $_W['openid'];
		$verifycode = trim($_GPC['verifycode']);

		if (empty($verifycode)) {
			show_json(0, '请填写核销码');
		}

		$verifygood = pdo_fetch('select *  from ' . tablename('ewei_shop_verifygoods') . ' where uniacid=:uniacid and  verifycode=:verifycode  limit 1 ', array(':uniacid' => $_W['uniacid'], ':verifycode' => $verifycode));

		if (empty($verifygood)) {
			show_json(0, '未查询到记次时商品或核销码已失效,请核对核销码!');
		}

		if (intval($verifygood['codeinvalidtime']) < time()) {
			show_json(0, '核销码已过期,请用户重新获取核销码');
		}

		$saler = pdo_fetch('select * from ' . tablename('ewei_shop_saler') . ' where  status=1  and  openid=:openid and uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid'], ':openid' => $openid));

		if (empty($saler)) {
			show_json(0, '您不是核销员,无权核销');
		}

		$store = pdo_fetch('select * from ' . tablename('ewei_shop_store') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $saler['storeid'], ':uniacid' => $_W['uniacid']));
		if (!empty($verifygood['storeid']) && !empty($store) && ($verifygood['storeid'] != $store['id'])) {
			show_json(0, '该商品无法在您所属门店核销!请重新确认!');
		}

		show_json(1, array('verifygoodid' => $verifygood['id']));
	}

	public function complete()
	{
		global $_W;
		global $_GPC;
		$openid = $_W['openid'];
		$id = intval($_GPC['id']);
		$times = intval($_GPC['times']);
		$verifycode = trim($_GPC['verifycode']);
		$remarks = trim($_GPC['remarks']);
		$verifygood = pdo_fetch('select *  from ' . tablename('ewei_shop_verifygoods') . ' where uniacid=:uniacid and  verifycode=:verifycode  limit 1 ', array(':uniacid' => $_W['uniacid'], ':verifycode' => $verifycode));

		if (empty($verifygood)) {
			show_json(0, '核销码已过期,请重新输入核销码或扫取二维码');
		}

		if (intval($verifygood['codeinvalidtime']) < time()) {
			show_json(0, '核销码已过期,请重新输入核销码或扫取二维码');
		}

		$saler = pdo_fetch('select * from ' . tablename('ewei_shop_saler') . ' where status=1  and openid=:openid and uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid'], ':openid' => $openid));

		if (empty($saler)) {
			show_json(0, '您不是核销员,无权核销');
		}

		$store = pdo_fetch('select * from ' . tablename('ewei_shop_store') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $saler['storeid'], ':uniacid' => $_W['uniacid']));
		if (!empty($verifygood['storeid']) && !empty($store) && ($verifygood['storeid'] != $store['id'])) {
			show_json(0, '该商品无法在您所属门店核销!请重新确认!');
		}

		$used = 0;

		if (!empty($verifygood['limitnum'])) {
			$verifygoodlogs = pdo_fetchall('select *  from ' . tablename('ewei_shop_verifygoods_log') . '    where verifygoodsid =:id  ', array(':id' => $verifygood['id']));
			$verifynum = 0;

			foreach ($verifygoodlogs as $verifygoodlog) {
				$verifynum += intval($verifygoodlog['verifynum']);
			}

			$lastverifys = intval($verifygood['limitnum']) - $verifynum;

			if ($lastverifys < $times) {
				show_json(0, '商品可核销次数不足!');
			}

			if ($lastverifys == $times) {
				$used = 1;
			}
		}

		if (empty($verifygood['limittype'])) {
			$limitdate = intval($verifygood['starttime']) + (intval($verifygood['limitdays']) * 86400);
		}
		else {
			$limitdate = intval($verifygood['limitdate']);
		}

		if ($limitdate < time()) {
			$this->message('该商品已过期!', '', 'error');
		}

		$data = array('uniacid' => $_W['uniacid'], 'verifygoodsid' => $verifygood['id'], 'salerid' => $saler['id'], 'storeid' => $store['id'], 'verifynum' => $times, 'verifydate' => time(), 'remarks' => $remarks);
		pdo_insert('ewei_shop_verifygoods_log', $data);
		$logid = pdo_insertid();
		m('notice')->sendVerifygoodMessage($logid);
		pdo_query('update ' . tablename('ewei_shop_verifygoods') . ' set used=:used , verifycode=null ,codeinvalidtime=0 where id=:id', array(':id' => $verifygood['id'], ':used' => $used));

		if (!empty($verifygood['activecard'])) {
			com_run('wxcard::updateusercardbyvarifygoodid', $verifygood['id']);
		}

		$finishorderid = 0;
		$isonlyverifygood = m('order')->checkisonlyverifygoods($verifygood['orderid']);

		if ($isonlyverifygood) {
			$status = pdo_fetchcolumn('select status  from ' . tablename('ewei_shop_order') . ' where uniacid=:uniacid and id=:id  limit 1 ', array(':uniacid' => $_W['uniacid'], ':id' => $verifygood['orderid']));

			if ($status == 2) {
				$finishorderid = $verifygood['orderid'];
				$this->finishorder($finishorderid);
			}
		}

		show_json(1, array('verifygoodid' => $verifygood['id'], 'orderid' => $finishorderid));
	}

	public function success()
	{
		global $_W;
		global $_GPC;
		$this->message(array('title' => '操作完成', 'message' => '您可以退出浏览器了'), 'javascript:WeixinJSBridge.call("closeWindow");', 'success');
	}

	public function finishorder($id)
	{
		global $_W;
		$item = pdo_fetch('SELECT * FROM ' . tablename('ewei_shop_order') . ' WHERE id = :id and uniacid=:uniacid', array(':id' => $id, ':uniacid' => $_W['uniacid']));
		if (empty($item) || ($item['status'] != 2)) {
			return false;
		}

		pdo_update('ewei_shop_order', array('status' => 3, 'sendtime' => time(), 'finishtime' => time()), array('id' => $item['id'], 'uniacid' => $_W['uniacid']));
		m('order')->fullback($item['id']);
		if (p('ccard') && !empty($item['ccardid'])) {
			p('ccard')->setBegin($item['id'], $item['ccardid']);
		}

		m('member')->upgradeLevel($item['openid']);
		m('order')->setGiveBalance($item['id'], 1);
		m('notice')->sendOrderMessage($item['id']);
		com_run('printer::sendOrderMessage', $item['id']);

		if (com('coupon')) {
			com('coupon')->sendcouponsbytask($item['id']);
		}

		if (!empty($item['couponid'])) {
			com('coupon')->backConsumeCoupon($item['id']);
		}

		if (p('lineup')) {
			p('lineup')->checkOrder($item);
		}

		if (p('commission')) {
			p('commission')->checkOrderFinish($item['id']);
		}

		if (p('lottery')) {
			$res = p('lottery')->getLottery($item['openid'], 1, array('money' => $item['price'], 'paytype' => 2));

			if ($res) {
				p('lottery')->getLotteryList($item['openid'], array('lottery_id' => $res));
			}
		}

		plog('order.op.finish', '订单完成 ID: ' . $item['id'] . ' 订单号: ' . $item['ordersn']);
		return true;
	}
}

?>
