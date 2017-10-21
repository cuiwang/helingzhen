<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
}

class Index_EweiShopV2Page extends WebPage
{
	public function main()
	{
		global $_W;
		global $_GPC;
		$uniacid = intval($_W['uniacid']);
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$condition = ' and uniacid=:uniacid';
		$params = array(':uniacid' => $uniacid);
		$type = trim($_GPC['status']);

		if ($type == '-1') {
			$condition .= ' AND status = 0 ';
		}
		else {
			if ($type == '1') {
				$condition .= ' AND status = 1 ';
			}
		}

		if (!empty($_GPC['keyword'])) {
			$_GPC['keyword'] = trim($_GPC['keyword']);
			$condition .= ' AND titles LIKE :title';
			$params[':title'] = '%' . trim($_GPC['keyword']) . '%';
		}

		$gifts = pdo_fetchall('SELECT * FROM ' . tablename('ewei_shop_fullback_goods') . "\r\n                    WHERE 1 " . $condition . ' ORDER BY displayorder DESC,id DESC LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize, $params);
		$total = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename('ewei_shop_fullback_goods') . ' WHERE 1 ' . $condition . ' ', $params);
		$pager = pagination2($total, $pindex, $psize);
		include $this->template();
	}

	public function add()
	{
		$this->post();
	}

	public function edit()
	{
		$this->post();
	}

	protected function post()
	{
		global $_W;
		global $_GPC;
		$uniacid = intval($_W['uniacid']);
		$id = intval($_GPC['id']);

		if ($_W['ispost']) {
			$data = array('uniacid' => $uniacid, 'displayorder' => intval($_GPC['displayorder']), 'type' => intval($_GPC['type']), 'titles' => trim($_GPC['titles']), 'thumb' => save_media($_GPC['thumb']), 'marketprice' => floatval($_GPC['marketprice']), 'goodsid' => floatval($_GPC['goodsid']), 'startday' => intval($_GPC['startday']), 'refund' => intval($_GPC['refund']), 'status' => intval($_GPC['status']));

			if (empty($data['goodsid'])) {
				show_json(0, '指定商品不能为空！');
			}

			if ($data['startday'] < 0) {
				show_json(0, '全返时间不可以小于0！');
			}

			$option = $_GPC['fullbackgoods'];
			$good_data = pdo_fetch("select title,thumb,marketprice,goodssn,productsn,hasoption\r\n                            from " . tablename('ewei_shop_goods') . ' where id = ' . $data['goodsid'] . ' and uniacid = ' . $uniacid . ' ');

			if (empty($data['thumb'])) {
				$data['thumb'] = save_media($good_data['thumb']);
			}

			if (empty($good_data['option']) && !$good_data['hasoption']) {
				$fullbackgoodsStr = $_GPC['goods' . $data['goodsid'] . ''];
				$fullbackgoodsArray = explode(',', $fullbackgoodsStr);
				$data['minallfullbackallprice'] = $fullbackgoodsArray[0];
				$data['fullbackprice'] = $fullbackgoodsArray[1];
				$data['minallfullbackallratio'] = $fullbackgoodsArray[2];
				$data['fullbackratio'] = $fullbackgoodsArray[3];
				$data['day'] = $fullbackgoodsArray[4];
			}

			$good_data['option'] = $option[$data['goodsid']] ? $option[$data['goodsid']] : '';
			if ($good_data['hasoption'] && empty($good_data['option'])) {
				show_json(0, '请选择商品规格！');
			}

			if (!empty($good_data['option'])) {
				$data['hasoption'] = 1;
				$data['optionid'] = $option[$data['goodsid']];
				$fullbackOption = array_filter(explode(',', $good_data['option']));
				pdo_update('ewei_shop_goods_option', array('isfullback' => 0), array('uniacid' => $uniacid, 'goodsid' => $data['goodsid']));

				foreach ($fullbackOption as $val) {
					$fullbackgoodsoption = explode(',', $_GPC['fullbackgoodsoption' . $val . '']);
					$optionData = array('allfullbackprice' => floatval($fullbackgoodsoption[0]), 'fullbackprice' => floatval($fullbackgoodsoption[1]), 'allfullbackratio' => floatval($fullbackgoodsoption[2]), 'fullbackratio' => floatval($fullbackgoodsoption[3]), 'day' => intval($fullbackgoodsoption[4]), 'isfullback' => 1);
					pdo_update('ewei_shop_goods_option', $optionData, array('uniacid' => $uniacid, 'id' => intval($val)));
				}
			}

			if (!empty($id)) {
				pdo_update('ewei_shop_fullback_goods', $data, array('id' => $id));
				plog('sale.fullback.edit', '编辑全返 ID: ' . $id . ' <br/>全返名称: ' . $data['titles']);
			}
			else {
				pdo_insert('ewei_shop_fullback_goods', $data);
				$id = pdo_insertid();
				plog('sale.fullback.add', '添加全返 ID: ' . $id . '  <br/>全返名称: ' . $data['titles']);
			}

			$sql = 'update ' . tablename('ewei_shop_fullback_goods') . " g set\r\n                g.minallfullbackallprice = (select min(allfullbackprice) from " . tablename('ewei_shop_goods_option') . ' where goodsid = ' . $data['goodsid'] . "),\r\n                g.maxallfullbackallprice = (select max(allfullbackprice) from " . tablename('ewei_shop_goods_option') . ' where goodsid = ' . $data['goodsid'] . "),\r\n                g.minallfullbackallratio = (select min(allfullbackratio) from " . tablename('ewei_shop_goods_option') . ' where goodsid = ' . $data['goodsid'] . "),\r\n                g.maxallfullbackallratio = (select max(allfullbackratio) from " . tablename('ewei_shop_goods_option') . ' where goodsid = ' . $data['goodsid'] . ")\r\n                where g.goodsid = " . $data['goodsid'] . ' and g.hasoption=1 and g.uniacid = ' . $uniacid . ' and g.id = ' . $id . ' ';
			pdo_query($sql);

			if (0 < $data['status']) {
				pdo_update('ewei_shop_goods', array('isfullback' => $id), array('id' => $data['goodsid']));
			}
			else {
				pdo_update('ewei_shop_goods', array('isfullback' => 0), array('id' => $data['goodsid']));
			}

			show_json(1, array('url' => webUrl('sale/fullback/edit', array('id' => $id))));
		}

		if (!empty($id)) {
			$item = pdo_fetch('SELECT * FROM ' . tablename('ewei_shop_fullback_goods') . ' WHERE uniacid = ' . $uniacid . ' and id = ' . $id . ' ');
			$good_data = pdo_fetch("select title,thumb,hasoption\r\n                            from " . tablename('ewei_shop_goods') . ' where id = ' . $item['goodsid'] . ' and uniacid = ' . $uniacid . ' ');

			if (empty($good_data)) {
				$this->message('抱歉，商品不存在或是已经删除！', '', 'error');
			}

			$item['title'] = $good_data['title'];

			if (0 < $item['hasoption']) {
				$item['option'] = pdo_fetchall("SELECT id,title,marketprice,specs,allfullbackprice,fullbackprice,allfullbackratio,fullbackratio,isfullback,day \r\n                FROM " . tablename('ewei_shop_goods_option') . "\r\n                WHERE uniacid = :uniacid and goodsid = :goodsid  ORDER BY displayorder DESC,id DESC ", array(':uniacid' => $uniacid, 'goodsid' => $item['goodsid']));
			}

			if (!empty($item['thumb'])) {
				$item = set_medias($item, array('thumb'));
			}
		}

		include $this->template();
	}

	public function status()
	{
		global $_W;
		global $_GPC;
		$id = intval($_GPC['id']);

		if (empty($id)) {
			$id = (is_array($_GPC['ids']) ? implode(',', $_GPC['ids']) : 0);
		}

		$items = pdo_fetchall('SELECT id,titles FROM ' . tablename('ewei_shop_fullback_goods') . ' WHERE id in( ' . $id . ' ) AND uniacid=' . $_W['uniacid']);

		foreach ($items as $item) {
			pdo_update('ewei_shop_fullback_goods', array('status' => intval($_GPC['status'])), array('id' => $item['id']));
			pdo_update('ewei_shop_goods', array('isfullback' => intval($item['id'])), array('id' => $items['goodsid']));
			plog('sale.fullback.edit', ('修改全返商品状态<br/>ID: ' . $item['id'] . '<br/>商品名称: ' . $item['titles'] . '<br/>状态: ' . $_GPC['status']) == 1 ? '开启' : '关闭');
		}

		show_json(1, array('url' => referer()));
	}

	public function delete1()
	{
		global $_W;
		global $_GPC;
		$id = intval($_GPC['id']);

		if (empty($id)) {
			$id = (is_array($_GPC['ids']) ? implode(',', $_GPC['ids']) : 0);
		}

		$items = pdo_fetchall('SELECT id,titles,goodsid FROM ' . tablename('ewei_shop_fullback_goods') . ' WHERE id in( ' . $id . ' ) AND uniacid=' . $_W['uniacid']);

		foreach ($items as $item) {
			pdo_delete('ewei_shop_fullback_goods', array('id' => $item['id']));
			pdo_update('ewei_shop_goods', array('isfullback' => 0), array('id' => $item['goodsid']));
			plog('sale.fullback.edit', '彻底删除全返商品<br/>ID: ' . $item['id'] . '<br/>全返商品名称: ' . $item['titles']);
		}

		show_json(1, array('url' => referer()));
	}

	public function change()
	{
		global $_W;
		global $_GPC;
		$id = intval($_GPC['id']);

		if (empty($id)) {
			show_json(0, array('message' => '参数错误'));
		}

		$type = trim($_GPC['typechange']);
		$value = trim($_GPC['value']);

		if (!in_array($type, array('titles', 'displayorder'))) {
			show_json(0, array('message' => '参数错误'));
		}

		$gift = pdo_fetch('select id from ' . tablename('ewei_shop_fullback_goods') . ' where id=:id and uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid'], ':id' => $id));

		if (empty($gift)) {
			show_json(0, array('message' => '参数错误'));
		}

		pdo_update('ewei_shop_fullback_goods', array($type => $value), array('id' => $id));
		show_json(1);
	}

	public function query()
	{
		global $_W;
		global $_GPC;
		$uniacid = intval($_W['uniacid']);
		$kwd = trim($_GPC['keyword']);
		$pindex = max(1, intval($_GPC['page']));
		$psize = 8;
		$params = array();
		$params[':uniacid'] = $uniacid;
		$condition = ' and deleted=0 and uniacid=:uniacid and status = 1 and merchid = 0 and type != 30 ';

		if (!empty($kwd)) {
			$condition .= ' AND (`title` LIKE :keywords OR `keywords` LIKE :keywords)';
			$params[':keywords'] = '%' . $kwd . '%';
		}

		$goods = pdo_fetchall("SELECT id,title,thumb,marketprice,total\r\n            FROM " . tablename('ewei_shop_goods') . "\r\n            WHERE 1 " . $condition . ' ORDER BY displayorder DESC,id DESC LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize, $params);
		$total = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename('ewei_shop_goods') . ' WHERE 1 ' . $condition . ' ', $params);
		$pager = pagination($total, $pindex, $psize, '', array('before' => 5, 'after' => 4, 'ajaxcallback' => 'select_page', 'callbackfuncname' => 'select_page'));
		$goods = set_medias($goods, array('thumb'));
		include $this->template();
	}

	public function hasoption()
	{
		global $_W;
		global $_GPC;
		$uniacid = intval($_W['uniacid']);
		$goodsid = intval($_GPC['goodsid']);
		$id = intval($_GPC['id']);
		$hasoption = 0;
		$params = array(':uniacid' => $uniacid, ':goodsid' => $goodsid);
		$goods = pdo_fetch('select id,title,marketprice,hasoption,isfullback from ' . tablename('ewei_shop_goods') . ' where uniacid = :uniacid and id = :goodsid ', $params);

		if (!empty($id)) {
			$fullback = pdo_fetch('select * from ' . tablename('ewei_shop_fullback_goods') . "\r\n                        where id = " . $id . ' and uniacid = :uniacid and goodsid = :goodsid ', $params);
			$fullback['isfullback'] = $goods['isfullback'];
		}
		else {
			$fullback = array('titles' => $goods['title'], 'marketprice' => $goods['marketprice'], 'allfullbackprice' => 0, 'fullbackprice' => 0, 'allfullbackratio' => 0, 'fullbackratio' => 0, 'isfullback' => 0);
		}

		if ($goods['hasoption']) {
			$hasoption = 1;
			$option = array();
			$option = pdo_fetchall("SELECT id,title,marketprice,specs,allfullbackprice,fullbackprice,allfullbackratio,fullbackratio,isfullback \r\n                FROM " . tablename('ewei_shop_goods_option') . "\r\n                WHERE uniacid = :uniacid and goodsid = :goodsid  ORDER BY displayorder DESC,id DESC ", $params);
		}
		else {
			$packgoods['marketprice'] = $goods['marketprice'];
		}

		include $this->template();
	}

	public function option()
	{
		global $_W;
		global $_GPC;
		$uniacid = intval($_W['uniacid']);
		$options = (is_array($_GPC['option']) ? implode(',', array_filter($_GPC['option'])) : 0);
		$options = intval($options);
		$option = pdo_fetch('SELECT id,title FROM ' . tablename('ewei_shop_goods_option') . "\r\n            WHERE uniacid = " . $uniacid . ' and id = ' . $options . '  ORDER BY displayorder DESC,id DESC LIMIT 1');
		show_json(1, $option);
	}
}

?>
