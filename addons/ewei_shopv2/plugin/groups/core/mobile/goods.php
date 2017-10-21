<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
}

class Goods_EweiShopV2Page extends PluginMobileLoginPage
{
	public function main()
	{
		global $_W;
		global $_GPC;
		$openid = $_W['openid'];
		load()->model('mc');
		$uid = mc_openid2uid($openid);

		if (empty($uid)) {
			mc_oauth_userinfo($openid);
		}

		$uniacid = $_W['uniacid'];
		$id = intval($_GPC['id']);
		$groupsset = pdo_fetch('select description,groups_description,discount,headstype,headsmoney,headsdiscount from ' . tablename('ewei_shop_groups_set') . "\r\n\t\t\t\t\twhere uniacid = :uniacid ", array(':uniacid' => $uniacid));
		$groupsset['groups_description'] = m('ui')->lazy($groupsset['groups_description']);
		$goods = pdo_fetch('select * from ' . tablename('ewei_shop_groups_goods') . "\r\n\t\t\t\t\twhere id = :id and status = :status and uniacid = :uniacid and deleted = 0 order by displayorder desc", array(':id' => $id, ':uniacid' => $uniacid, ':status' => 1));
		if (empty($id) || empty($goods)) {
			$this->message('你访问的商品不存在或已删除!', mobileUrl('groups'), 'error');
		}

		if (!empty($goods['thumb_url'])) {
			$goods['thumb_url'] = array_merge(iunserializer($goods['thumb_url']));
		}

		$goods['fightnum'] = pdo_fetchcolumn('select count(1) from ' . tablename('ewei_shop_groups_order') . ' where goodid = :goodid and uniacid = :uniacid and deleted = 0 and is_team = 1 and status > 0 ', array(':goodid' => $goods['id'], ':uniacid' => $uniacid));
		$goods['fightnum'] = $goods['teamnum'] + $goods['fightnum'];
		$goods['content'] = m('ui')->lazy($goods['content']);

		if (empty($goods)) {
			$this->message('商品已下架或被删除!', mobileUrl('groups'), 'error');
		}

		if (!empty($groupsset['discount'])) {
			if (empty($goods['discount'])) {
				$goods['headstype'] = $groupsset['headstype'];
				$goods['headsmoney'] = $groupsset['headsmoney'];
				$goods['headsdiscount'] = $groupsset['headsdiscount'];
			}

			if ($goods['groupsprice'] < $goods['headsmoney']) {
				$goods['headsmoney'] = $goods['groupsprice'];
			}
		}

		$url = array('pay' => mobileUrl('groups/goods/pay'), 'lottery' => mobileUrl('groups/goods/lottery'));
		$set = $_W['shopset'];
		$_W['shopshare'] = array('title' => !empty($goods['share_title']) ? $goods['share_title'] : $goods['title'], 'imgUrl' => !empty($goods['share_icon']) ? tomedia($goods['share_icon']) : tomedia($goods['thumb']), 'desc' => !empty($goods['share_desc']) ? $goods['share_desc'] : $goods['description'], 'link' => mobileUrl('groups/goods', array('id' => $id), true));
		include $this->template();
	}

	public function openGroups()
	{
		global $_W;
		global $_GPC;
		$openid = $_W['openid'];
		$uniacid = $_W['uniacid'];
		$id = intval($_GPC['id']);
		load()->model('mc');
		$uid = mc_openid2uid($openid);

		if (empty($uid)) {
			mc_oauth_userinfo($openid);
		}

		if (empty($id)) {
			$this->message('你访问的商品不存在或已删除!', mobileUrl('groups'), 'error');
		}

		$goods = pdo_fetch('select * from ' . tablename('ewei_shop_groups_goods') . "\r\n\t\t\t\t\twhere id = :id and status = :status and uniacid = :uniacid and deleted = 0 order by displayorder desc", array(':id' => $id, ':uniacid' => $uniacid, ':status' => 1));
		$goods['fightnum'] = pdo_fetchcolumn('select count(1) from ' . tablename('ewei_shop_groups_order') . ' where goodid = :goodid and uniacid = :uniacid and deleted = 0 and is_team = 1 and status > 0 ', array(':goodid' => $goods['id'], ':uniacid' => $uniacid));
		$goods['fightnum'] = $goods['teamnum'] + $goods['fightnum'];
		$goods = set_medias($goods, 'thumb');
		$teams = pdo_fetchall('select * from ' . tablename('ewei_shop_groups_goods') . ' where deleted = 0 and status = 1 and uniacid = :uniacid order by sales desc limit 4', array(':uniacid' => $uniacid));

		foreach ($teams as $key => $value) {
			$value['fightnum'] = pdo_fetchcolumn('select count(1) from ' . tablename('ewei_shop_groups_order') . ' where goodid = :goodid and uniacid = :uniacid and deleted = 0 and is_team = 1 and status > 0 ', array(':goodid' => $value['id'], ':uniacid' => $uniacid));
			$value['fightnum'] = $value['teamnum'] + $value['fightnum'];
			$value = set_medias($value, 'thumb');
			$teams[$key] = $value;
		}

		if (empty($goods)) {
			$this->message('商品已下架或被删除!', mobileUrl('groups'), 'error');
		}

		include $this->template();
	}

	public function fightGroups()
	{
		global $_W;
		global $_GPC;
		$openid = $_W['openid'];
		$uniacid = $_W['uniacid'];
		$id = intval($_GPC['id']);
		load()->model('mc');
		$uid = mc_openid2uid($openid);

		if (empty($uid)) {
			mc_oauth_userinfo($openid);
		}

		if (empty($id)) {
			$this->message('你访问的商品不存在或已删除!', mobileUrl('groups'), 'error');
		}

		$goods = pdo_fetch('select * from ' . tablename('ewei_shop_groups_goods') . "\r\n\t\t\t\t\twhere id = :id and status = :status and uniacid = :uniacid and deleted = 0 order by displayorder desc", array(':id' => $id, ':uniacid' => $uniacid, ':status' => 1));
		$goods['fightnum'] = pdo_fetchcolumn('select count(1) from ' . tablename('ewei_shop_groups_order') . ' where goodid = :goodid and uniacid = :uniacid and deleted = 0 and is_team = 1 and status > 0 ', array(':goodid' => $goods['id'], ':uniacid' => $uniacid));
		$goods['fightnum'] = $goods['teamnum'] + $goods['fightnum'];
		$goods = set_medias($goods, 'thumb');
		$teams = pdo_fetchall('select o.paytime,o.id,o.goodid,o.teamid,m.nickname,m.realname,m.mobile,m.avatar,g.endtime,g.groupnum from ' . tablename('ewei_shop_groups_order') . " as o\r\n\t\t\t\tleft join " . tablename('ewei_shop_member') . " as m on m.openid=o.openid and m.uniacid =  o.uniacid\r\n\t\t\t\tleft join " . tablename('ewei_shop_groups_goods') . " as g on g.id = o.goodid\r\n\t\t\t\twhere o.goodid = :goodid and o.uniacid = :uniacid and o.openid != :openid and o.deleted = 0 and o.heads = 1 and o.paytime > 0 and o.success = 0 limit 10 ", array(':goodid' => $goods['id'], ':uniacid' => $uniacid, ':openid' => $openid));

		foreach ($teams as $key => $value) {
			$num = pdo_fetchcolumn('select count(1) from ' . tablename('ewei_shop_groups_order') . ' where uniacid = :uniacid and deleted = 0 and teamid = :teamid and status > 0 ', array(':teamid' => $value['teamid'], ':uniacid' => $uniacid));
			$value['num'] = $value['groupnum'] - $num;
			$value['residualtime'] = ($value['paytime'] + ($value['endtime'] * 60 * 60)) - time();
			$value['hour'] = intval($value['residualtime'] / 3600);
			$value['minite'] = intval(($value['residualtime'] / 60) % 60);
			$value['second'] = intval($value['residualtime'] % 60);
			$teams[$key] = $value;
		}

		include $this->template();
	}

	public function goodsCheck()
	{
		global $_W;
		global $_GPC;

		try {
			$uniacid = $_W['uniacid'];
			$id = intval($_GPC['id']);
			$type = $_GPC['type'];

			if (empty($id)) {
				show_json(0, array('message' => '商品不存在！'));
			}

			$goods = pdo_fetch('select * from ' . tablename('ewei_shop_groups_goods') . "\r\n\t\t\t\t\twhere id = :id and status = :status and uniacid = :uniacid and deleted = 0 order by displayorder desc", array(':id' => $id, ':uniacid' => $uniacid, ':status' => 1));

			if (empty($goods)) {
				show_json(0, array('message' => '商品不存在！'));
			}

			if ($type == 'single') {
				if (empty($goods['single'])) {
					show_json(0, array('message' => '商品不允许单购，请重新选择！'));
				}
			}

			if (empty($goods['stock'])) {
				show_json(0, array('message' => '商品库存为0，暂时无法购买，请浏览其他商品！'));
			}

			show_json(1);
		}
		catch (Exception $e) {
			$content = $e->getMessage();
			include $this->template('groups/error');
		}
	}
}

?>
