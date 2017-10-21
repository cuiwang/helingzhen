<?php

if (!defined('IN_IA')) {
	exit('Access Denied');
}
 
require EWEI_SHOPV2_PLUGIN . 'app/core/page_mobile.php';
class Cart_EweiShopV2Page extends AppMobilePage
{
	public function get_cart()
	{
		global $_W;
		global $_GPC;
		$uniacid = $_W['uniacid'];
		$openid = $_W['openid'];
		$condition = ' and f.uniacid= :uniacid and f.openid=:openid and f.deleted=0';
		$params = array(':uniacid' => $uniacid, ':openid' => $openid);
		$list = array();
		$total = 0;
		$totalprice = 0;
		$ischeckall = true;
		$level = m('member')->getLevel($openid);
		$sql = 'SELECT f.id,f.total,f.goodsid,g.total as stock, o.stock as optionstock, g.maxbuy,g.title,g.thumb,ifnull(o.marketprice, g.marketprice) as marketprice,' . ' g.productprice,o.title as optiontitle,f.optionid,o.specs,g.minbuy,g.maxbuy,g.unit,f.merchid,g.merchsale' . ' ,f.selected FROM ' . tablename('ewei_shop_member_cart') . ' f ' . ' left join ' . tablename('ewei_shop_goods') . ' g on f.goodsid = g.id ' . ' left join ' . tablename('ewei_shop_goods_option') . ' o on f.optionid = o.id ' . ' where 1 ' . $condition . ' ORDER BY `id` DESC ';
		$list = pdo_fetchall($sql, $params);

		foreach ($list as &$g) {
			if (!empty($g['optionid'])) {
				$g['stock'] = $g['optionstock'];

				if (!empty($g['specs'])) {
					$thumb = m('goods')->getSpecThumb($g['specs']);

					if (!empty($thumb)) {
						$g['thumb'] = $thumb;
					}
				}
			}

			$g['marketprice'] = (double) $g['marketprice'];

			if ($g['selected']) {
				$prices = m('order')->getGoodsDiscountPrice($g, $level, 1);
				$g['marketprice'] = $prices['price'];
				$totalprice += $g['marketprice'] * $g['total'];
				$total += $g['total'];
			}

			$totalmaxbuy = $g['stock'];

			if (0 < $g['maxbuy']) {
				if ($totalmaxbuy != -1) {
					if ($g['maxbuy'] < $totalmaxbuy) {
						$totalmaxbuy = $g['maxbuy'];
					}
				}
				else {
					$totalmaxbuy = $g['maxbuy'];
				}
			}

			if (0 < $g['usermaxbuy']) {
				$order_goodscount = pdo_fetchcolumn('select ifnull(sum(og.total),0)  from ' . tablename('ewei_shop_order_goods') . ' og ' . ' left join ' . tablename('ewei_shop_order') . ' o on og.orderid=o.id ' . ' where og.goodsid=:goodsid and  o.status>=1 and o.openid=:openid  and og.uniacid=:uniacid ', array(':goodsid' => $g['goodsid'], ':uniacid' => $uniacid, ':openid' => $openid));
				$last = $g['usermaxbuy'] - $order_goodscount;

				if ($last <= 0) {
					$last = 0;
				}

				if ($totalmaxbuy != -1) {
					if ($last < $totalmaxbuy) {
						$totalmaxbuy = $last;
					}
				}
				else {
					$totalmaxbuy = $last;
				}
			}

			if (0 < $g['minbuy']) {
				if ($totalmaxbuy < $g['minbuy']) {
					$g['minbuy'] = $totalmaxbuy;
				}
			}

			$g['totalmaxbuy'] = $totalmaxbuy;
			$g['unit'] = empty($data['unit']) ? '件' : $data['unit'];

			if (empty($g['selected'])) {
				$ischeckall = false;
			}

			unset($g['maxbuy']);
		}

		unset($g);
		$list = set_medias($list, 'thumb');
		$result = array('ischeckall' => $ischeckall, 'total' => (int) $total, 'totalprice' => (double) $totalprice, 'empty' => empty($list));
		$merch_plugin = p('merch');
		$merch_data = m('common')->getPluginset('merch');
		if ($merch_plugin && $merch_data['is_openmerch']) {
			$getListUser = $merch_plugin->getListUser($list);
			$merch_user = $getListUser['merch_user'];
			$merch = $getListUser['merch'];
			if (is_array($list) && !empty($list)) {
				$newlist = array();

				foreach ($merch as $merchid => $merchlist) {
					$newlist[] = array('merchname' => $merch_user[$merchid]['merchname'], 'merchid' => $merchid, 'list' => $merchlist);
				}
			}

			$result['merch_list'] = $newlist;
		}
		else {
			$result['list'] = $list;
		}

		app_json($result);
	}

	public function add()
	{
		global $_W;
		global $_GPC;
		$id = intval($_GPC['id']);
		$total = intval($_GPC['total']);
		($total <= 0) && ($total = 1);

		if (empty($id)) {
			app_error(AppError::$ParamsError);
		}

		$optionid = intval($_GPC['optionid']);
		$goods = pdo_fetch('select id,marketprice,diyformid,diyformtype,diyfields, isverify, type,merchid from ' . tablename('ewei_shop_goods') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $id, ':uniacid' => $_W['uniacid']));

		if (empty($goods)) {
			app_error(AppError::$GoodsNotFound);
		}

		if (($goods['isverify'] == 2) || ($goods['type'] == 2) || ($goods['type'] == 3)) {
			app_error(AppError::$NotAddCart);
		}

		$diyform_plugin = p('diyform');
		$diyformid = 0;
		$diyformfields = iserializer(array());
		$diyformdata = iserializer(array());

		if ($diyform_plugin) {
			$diyformdata = $_GPC['diyformdata'];

			if (is_string($diyformdata)) {
				$diyformdatastring = htmlspecialchars_decode(str_replace('\\', '', $_GPC['diyformdata']));
				$diyformdata = @json_decode($diyformdatastring, true);
			}

			if (!empty($diyformdata) && is_array($diyformdata)) {
				$diyformfields = false;

				if ($goods['diyformtype'] == 1) {
					$diyformid = intval($goods['diyformid']);
					$formInfo = $diyform_plugin->getDiyformInfo($diyformid);

					if (!empty($formInfo)) {
						$diyformfields = $formInfo['fields'];
					}
				}
				else {
					if ($goods['diyformtype'] == 2) {
						$diyformfields = iunserializer($goods['diyfields']);
					}
				}

				if (!empty($diyformfields)) {
					$insert_data = $diyform_plugin->getInsertData($diyformfields, $diyformdata, true);
					$diyformdata = $insert_data['data'];
					$diyformfields = iserializer($diyformfields);
				}
			}
		}

		$data = pdo_fetch('select id,total,diyformid from ' . tablename('ewei_shop_member_cart') . ' where goodsid=:id and openid=:openid and   optionid=:optionid  and deleted=0 and  uniacid=:uniacid   limit 1', array(':uniacid' => $_W['uniacid'], ':openid' => $_W['openid'], ':optionid' => $optionid, ':id' => $id));

		if (empty($data)) {
			$data = array('uniacid' => $_W['uniacid'], 'merchid' => $goods['merchid'], 'openid' => $_W['openid'], 'goodsid' => $id, 'optionid' => $optionid, 'marketprice' => $goods['marketprice'], 'total' => $total, 'selected' => 1, 'diyformid' => $diyformid, 'diyformdata' => $diyformdata, 'diyformfields' => $diyformfields, 'createtime' => time());
			pdo_insert('ewei_shop_member_cart', $data);
		}
		else {
			$data['diyformid'] = $diyformid;
			$data['diyformdata'] = $diyformdata;
			$data['diyformfields'] = $diyformfields;
			$data['total'] += $total;
			pdo_update('ewei_shop_member_cart', $data, array('id' => $data['id']));
		}

		$cartcount = pdo_fetchcolumn('select sum(total) from ' . tablename('ewei_shop_member_cart') . ' where openid=:openid and deleted=0 and uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid'], ':openid' => $_W['openid']));
		app_json(array('isnew' => false, 'cartcount' => $cartcount));
	}

	public function update()
	{
		global $_W;
		global $_GPC;
		$id = intval($_GPC['id']);
		$goodstotal = intval($_GPC['total']);

		if (empty($id)) {
			app_error(AppError::$ParamsError);
		}

		$optionid = intval($_GPC['optionid']);
		empty($goodstotal) && ($goodstotal = 1);
		$data = pdo_fetch('select * from ' . tablename('ewei_shop_member_cart') . ' where id=:id and uniacid=:uniacid and openid=:openid limit 1 ', array(':id' => $id, ':uniacid' => $_W['uniacid'], ':openid' => $_W['openid']));

		if (empty($data)) {
			app_error(AppError::$NotInCart);
		}

		$goods = pdo_fetch('select id,maxbuy,minbuy,total,unit from ' . tablename('ewei_shop_goods') . ' where id=:id and uniacid=:uniacid and status=1 and deleted=0', array(':id' => $data['goodsid'], ':uniacid' => $_W['uniacid']));

		if (empty($goods)) {
			app_error(AppError::$GoodsNotFound);
		}

		$diyform_plugin = p('diyform');
		$diyformid = 0;
		$diyformfields = iserializer(array());
		$diyformdata = iserializer(array());

		if ($diyform_plugin) {
			$diyformdata = $_GPC['diyformdata'];
			if (!empty($diyformdata) && is_string($diyformdata)) {
				$diyformdatastring = htmlspecialchars_decode(str_replace('\\', '', $_GPC['diyformdata']));
				$diyformdata = @json_decode($diyformdatastring, true);
			}

			if (!empty($diyformdata) && is_array($diyformdata)) {
				$diyformfields = false;

				if ($goods['diyformtype'] == 1) {
					$diyformid = intval($goods['diyformid']);
					$formInfo = $diyform_plugin->getDiyformInfo($diyformid);

					if (!empty($formInfo)) {
						$diyformfields = $formInfo['fields'];
					}
				}
				else {
					if ($goods['diyformtype'] == 2) {
						$diyformfields = iunserializer($goods['diyfields']);
					}
				}

				if (!empty($diyformfields)) {
					$insert_data = $diyform_plugin->getInsertData($diyformfields, $diyformdata, true);
					$diyformdata = $insert_data['data'];
					$diyformfields = iserializer($diyformfields);
				}
			}
		}

		$arr = array('total' => $goodstotal, 'optionid' => $optionid, 'diyformid' => $diyformid, 'diyformdata' => $diyformdata, 'diyformfields' => $diyformfields);
		pdo_update('ewei_shop_member_cart', $arr, array('id' => $id, 'uniacid' => $_W['uniacid'], 'openid' => $_W['openid']));
		app_json();
	}

	public function remove()
	{
		global $_W;
		global $_GPC;
		$ids = $_GPC['ids'];

		if (empty($ids)) {
			app_error(AppError::$ParamsError);
		}

		if (!is_array($ids)) {
			$ids = htmlspecialchars_decode(str_replace('\\', '', $ids));
			$ids = @json_decode($ids, true);
		}

		if (empty($ids)) {
			app_error(AppError::$ParamsError);
		}

		$sql = 'update ' . tablename('ewei_shop_member_cart') . ' set deleted=1 where uniacid=:uniacid and openid=:openid and id in (' . implode(',', $ids) . ')';
		pdo_query($sql, array(':uniacid' => $_W['uniacid'], ':openid' => $_W['openid']));
		app_json();
	}

	public function tofavorite()
	{
		global $_W;
		global $_GPC;
		$uniacid = $_W['uniacid'];
		$openid = $_W['openid'];
		$ids = $_GPC['ids'];

		if (empty($ids)) {
			app_error(AppError::$ParamsError);
		}

		if (!is_array($ids)) {
			$ids = htmlspecialchars_decode(str_replace('\\', '', $ids));
			$ids = @json_decode($ids, true);
		}

		foreach ($ids as $id) {
			$goodsid = pdo_fetchcolumn('select goodsid from ' . tablename('ewei_shop_member_cart') . ' where id=:id and uniacid=:uniacid and openid=:openid limit 1 ', array(':id' => $id, ':uniacid' => $uniacid, ':openid' => $openid));

			if (!empty($goodsid)) {
				$fav = pdo_fetchcolumn('select count(*) from ' . tablename('ewei_shop_member_favorite') . ' where goodsid=:goodsid and uniacid=:uniacid and openid=:openid and deleted=0 limit 1 ', array(':goodsid' => $goodsid, ':uniacid' => $uniacid, ':openid' => $openid));

				if ($fav <= 0) {
					$fav = array('uniacid' => $uniacid, 'goodsid' => $goodsid, 'openid' => $openid, 'deleted' => 0, 'createtime' => time());
					pdo_insert('ewei_shop_member_favorite', $fav);
				}
			}
		}

		$sql = 'update ' . tablename('ewei_shop_member_cart') . ' set deleted=1 where uniacid=:uniacid and openid=:openid and id in (' . implode(',', $ids) . ')';
		pdo_query($sql, array(':uniacid' => $uniacid, ':openid' => $openid));
		app_json();
	}

	public function select()
	{
		global $_W;
		global $_GPC;
		$id = intval($_GPC['id']);
		$select = intval($_GPC['select']);

		if (!empty($id)) {
			$data = pdo_fetch('select id,goodsid,optionid, total from ' . tablename('ewei_shop_member_cart') . ' ' . ' where id=:id and uniacid=:uniacid and openid=:openid limit 1 ', array(':id' => $id, ':uniacid' => $_W['uniacid'], ':openid' => $_W['openid']));

			if (!empty($data)) {
				pdo_update('ewei_shop_member_cart', array('selected' => $select), array('id' => $id, 'uniacid' => $_W['uniacid']));
			}
		}
		else {
			pdo_update('ewei_shop_member_cart', array('selected' => $select), array('uniacid' => $_W['uniacid'], 'openid' => $_W['openid']));
		}

		app_json();
	}

	public function count()
	{
		global $_W;
		global $_GPC;
		$params = array(':uniacid' => $_W['uniacid'], ':openid' => $_W['openid']);
		$cartcount = (int) pdo_fetchcolumn('select ifnull(sum(total),0) from ' . tablename('ewei_shop_member_cart') . ' where uniacid=:uniacid and openid=:openid and deleted=0 and selected =1', $params);
		app_json(array('cartcount' => $cartcount));
	}
}

?>
