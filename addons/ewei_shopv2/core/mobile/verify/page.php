<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
}

class Page_EweiShopV2Page extends MobilePage
{
	public function main()
	{
		global $_W;
		global $_GPC;
		$openid = $_W['openid'];
		$uniacd = $_W['uniacid'];
		$merchid = 0;
		$merch_plugin = p('merch');
		$saler = pdo_fetch('select * from ' . tablename('ewei_shop_saler') . ' where openid=:openid and uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid'], ':openid' => $openid));
		if (empty($saler) && $merch_plugin) {
			$saler = pdo_fetch('select * from ' . tablename('ewei_shop_merch_saler') . ' where openid=:openid and uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid'], ':openid' => $openid));
		}

		if (empty($saler)) {
			$this->message('您无核销权限!');
		}
		else {
			$merchid = $saler['merchid'];
		}

		$member = m('member')->getMember($saler['openid']);
		$store = false;

		if (!empty($saler['storeid'])) {
			if (0 < $merchid) {
				$store = pdo_fetch('select * from ' . tablename('ewei_shop_merch_store') . ' where id=:id and uniacid=:uniacid and merchid = :merchid limit 1', array(':id' => $saler['storeid'], ':uniacid' => $_W['uniacid'], ':merchid' => $merchid));
			}
			else {
				$store = pdo_fetch('select * from ' . tablename('ewei_shop_store') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $saler['storeid'], ':uniacid' => $_W['uniacid']));
			}
		}

		include $this->template();
	}

	public function search()
	{
		global $_W;
		global $_GPC;
		$verifycode = trim($_GPC['verifycode']);

		if (empty($verifycode)) {
			show_json(0, '请填写消费码或自提码');
		}

		$orderid = pdo_fetchcolumn('select id from ' . tablename('ewei_shop_order') . ' where uniacid=:uniacid and ( verifycode=:verifycode or verifycodes like :verifycodes ) limit 1 ', array(':uniacid' => $_W['uniacid'], ':verifycode' => $verifycode, ':verifycodes' => '%|' . $verifycode . '|%'));

		if (empty($orderid)) {
			show_json(0, '未查询到订单,请核对');
		}

		$allow = com('verify')->allow($orderid);

		if (is_error($allow)) {
			show_json(0, $allow['message']);
		}

		extract($allow);
		$verifyinfo = iunserializer($order['verifyinfo']);

		if ($order['verifytype'] == 2) {
			foreach ($verifyinfo as &$v) {
				unset($v['select']);

				if ($v['verifycode'] == $verifycode) {
					if ($v['verified']) {
						show_json(0, '此消费码已经使用!');
					}

					$v['select'] = 1;
				}
			}

			unset($v);
			pdo_update('ewei_shop_order', array('verifyinfo' => iserializer($verifyinfo)), array('id' => $orderid));
		}

		show_json(1, array('orderid' => $orderid, 'istrade' => intval($order['istrade'])));
	}

	public function complete()
	{
		global $_W;
		global $_GPC;
		$openid = $_W['openid'];
		$uniacid = $_W['uniacid'];
		$orderid = intval($_GPC['id']);
		$times = intval($_GPC['times']);
		com('verify')->verify($orderid, $times);
		show_json(1);
	}
}

?>
