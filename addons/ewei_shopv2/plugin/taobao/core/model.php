<?php
set_time_limit(0);

if (!defined('IN_IA')) {
	exit('Access Denied');
}

class TaobaoModel extends PluginModel
{
	public function get_item_taobao($itemid = '', $taobaourl = '', $cates, $merchid = 0)
	{
		global $_W;
		$g = pdo_fetch('select * from ' . tablename('ewei_shop_goods') . ' where uniacid=:uniacid and merchid=:merchid and catch_id=:catch_id and catch_source=\'taobao\' limit 1', array(':uniacid' => $_W['uniacid'], ':merchid' => $merchid, ':catch_id' => $itemid));

		if ($g) {
		}

		$url = $this->get_taobao_info_url($itemid);
		load()->func('communication');
		$response = ihttp_get($url);

		if (!isset($response['content'])) {
			return array('result' => '0', 'error' => '未从淘宝获取到商品信息!');
		}

		$content = $response['content'];

		if (strexists($response['content'], 'ERRCODE_QUERY_DETAIL_FAIL')) {
			return array('result' => '0', 'error' => '宝贝不存在!');
		}

		$arr = json_decode($content, true);
		$data = $arr['data'];
		$itemInfoModel = $data['itemInfoModel'];
		$item = array();
		$item['id'] = $g['id'];
		$item['merchid'] = $merchid;

		if (!empty($merchid)) {
			if (empty($_W['merch_user']['goodschecked'])) {
				$item['checked'] = 1;
			}
			else {
				$item['checked'] = 0;
			}
		}

		$pcates = array();
		$ccates = array();
		$tcates = array();
		$pcateid = 0;
		$ccateid = 0;
		$tcateid = 0;

		if (is_array($cates)) {
			foreach ($cates as $key => $cid) {
				$c = pdo_fetch('select level from ' . tablename('ewei_shop_category') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $cid, ':uniacid' => $_W['uniacid']));

				if ($c['level'] == 1) {
					$pcates[] = $cid;
				}
				else if ($c['level'] == 2) {
					$ccates[] = $cid;
				}
				else {
					if ($c['level'] == 3) {
						$tcates[] = $cid;
					}
				}

				if ($key == 0) {
					if ($c['level'] == 1) {
						$pcateid = $cid;
					}
					else if ($c['level'] == 2) {
						$crow = pdo_fetch('select parentid from ' . tablename('ewei_shop_category') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $cid, ':uniacid' => $_W['uniacid']));
						$pcateid = $crow['parentid'];
						$ccateid = $cid;
					}
					else {
						if ($c['level'] == 3) {
							$tcateid = $cid;
							$tcate = pdo_fetch('select id,parentid from ' . tablename('ewei_shop_category') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $cid, ':uniacid' => $_W['uniacid']));
							$ccateid = $tcate['parentid'];
							$ccate = pdo_fetch('select id,parentid from ' . tablename('ewei_shop_category') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $ccateid, ':uniacid' => $_W['uniacid']));
							$pcateid = $ccate['parentid'];
						}
					}
				}
			}
		}

		$item['pcate'] = $pcateid;
		$item['ccate'] = $ccateid;
		$item['tcate'] = $tcateid;

		if (!empty($cates)) {
			$item['cates'] = implode(',', $cates);
		}

		$item['pcates'] = implode(',', $pcates);
		$item['ccates'] = implode(',', $ccates);
		$item['tcates'] = implode(',', $tcates);
		$item['itemId'] = $itemInfoModel['itemId'];
		$item['title'] = $itemInfoModel['title'];
		$item['pics'] = $itemInfoModel['picsPath'];
		$params = array();

		if (isset($data['props'])) {
			$props = $data['props'];

			foreach ($props as $pp) {
				$params[] = array('title' => $pp['name'], 'value' => $pp['value']);
			}
		}

		$item['params'] = $params;
		$specs = array();
		$options = array();

		if (isset($data['skuModel'])) {
			$skuModel = $data['skuModel'];

			if (isset($skuModel['skuProps'])) {
				$skuProps = $skuModel['skuProps'];

				foreach ($skuProps as $prop) {
					$spec_items = array();

					foreach ($prop['values'] as $spec_item) {
						$spec_items[] = array('valueId' => $spec_item['valueId'], 'title' => $spec_item['name'], 'thumb' => $spec_item['imgUrl']);
					}

					$spec = array('propId' => $prop['propId'], 'title' => $prop['propName'], 'items' => $spec_items);
					$specs[] = $spec;
				}
			}

			if (isset($skuModel['ppathIdmap'])) {
				$ppathIdmap = $skuModel['ppathIdmap'];

				foreach ($ppathIdmap as $key => $skuId) {
					$option_specs = array();
					$m = explode(';', $key);

					foreach ($m as $v) {
						$mm = explode(':', $v);
						$option_specs[] = array('propId' => $mm[0], 'valueId' => $mm[1]);
					}

					$options[] = array('option_specs' => $option_specs, 'skuId' => $skuId, 'stock' => 0, 'marketprice' => 0, 'specs' => '');
				}
			}
		}

		$item['specs'] = $specs;
		$stack = $data['apiStack'][0]['value'];
		$value = json_decode($stack, true);
		$item1 = array();
		$data1 = $value['data'];
		$itemInfoModel1 = $data1['itemInfoModel'];
		$item['total'] = $itemInfoModel1['quantity'];
		$item['sales'] = $itemInfoModel1['totalSoldQuantity'];

		if (isset($data1['skuModel'])) {
			$skuModel1 = $data1['skuModel'];

			if (isset($skuModel1['skus'])) {
				$skus = $skuModel1['skus'];

				foreach ($skus as $key => $val) {
					$sku_id = $key;

					foreach ($options as &$o) {
						if ($o['skuId'] == $sku_id) {
							$o['stock'] = $val['quantity'];

							foreach ($val['priceUnits'] as $p) {
								$o['marketprice'] = $p['price'];
							}

							$titles = array();

							foreach ($o['option_specs'] as $osp) {
								foreach ($specs as $sp) {
									if ($sp['propId'] == $osp['propId']) {
										foreach ($sp['items'] as $spitem) {
											if ($spitem['valueId'] == $osp['valueId']) {
												$titles[] = $spitem['title'];
											}
										}
									}
								}
							}

							$o['title'] = $titles;
						}
					}

					unset($o);
				}
			}
			else {
				$mprice = 0;

				foreach ($itemInfoModel1['priceUnits'] as $p) {
					$mprice = $p['price'];
				}

				$item['marketprice'] = $mprice;
			}
		}
		else {
			$mprice = 0;

			foreach ($itemInfoModel1['priceUnits'] as $p) {
				$mprice = $p['price'];
			}

			$item['marketprice'] = $mprice;
		}

		$item['options'] = $options;
		$item['content'] = array();
		$url = $this->get_taobao_detail_url($itemid);
		load()->func('communication');
		$response = ihttp_get($url);
		$response = $this->contentpasswh($response);
		$item['content'] = $response;
		return $this->save_taobao_goods($item, $taobaourl);
	}

	public function get_item_jingdong($itemid = '', $jingdongurl = '', $cates, $merchid = 0)
	{
		error_reporting(0);
		global $_W;
		$g = pdo_fetch('select * from ' . tablename('ewei_shop_goods') . ' where uniacid=:uniacid and merchid=:merchid and catch_id=:catch_id and catch_source=\'jingdong\' limit 1', array(':uniacid' => $_W['uniacid'], ':catch_id' => $itemid, ':merchid' => $merchid));
		$url = $this->get_jingdong_info_url($itemid);
		load()->func('communication');
		$response = ihttp_get($url);
		$length = strval($response['headers']['Content-Length']);

		if ($length != NULL) {
			return array('result' => '0', 'error' => '未从京东获取到商品信息!');
		}
/* [31m * TODO SEPARATE[0m */

		$content = $response['content'];
		$dom = new DOMDocument();
		$dom->loadHTML('<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>' . $content);
		$xml = simplexml_import_dom($dom);
		$prodectNameContent = $xml->xpath('//*[@id="goodName"]');
		$prodectName = strval($prodectNameContent[0]->attributes()->value);

		if ($prodectName == NULL) {
			return array('result' => '0', 'error' => '宝贝不存在!');
		}

		$item = array();
		$item['id'] = $g['id'];
		$item['merchid'] = $merchid;

		if (!empty($merchid)) {
			if (empty($_W['merch_user']['goodschecked'])) {
				$item['checked'] = 1;
			}
			else {
				$item['checked'] = 0;
			}
		}

		$pcates = array();
		$ccates = array();
		$tcates = array();
		$pcateid = 0;
		$ccateid = 0;
		$tcateid = 0;

		if (is_array($cates)) {
			foreach ($cates as $key => $cid) {
				$c = pdo_fetch('select level from ' . tablename('ewei_shop_category') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $cid, ':uniacid' => $_W['uniacid']));

				if ($c['level'] == 1) {
					$pcates[] = $cid;
				}
				else if ($c['level'] == 2) {
					$ccates[] = $cid;
				}
				else {
					if ($c['level'] == 3) {
						$tcates[] = $cid;
					}
				}

				if ($key == 0) {
					if ($c['level'] == 1) {
						$pcateid = $cid;
					}
					else if ($c['level'] == 2) {
						$crow = pdo_fetch('select parentid from ' . tablename('ewei_shop_category') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $cid, ':uniacid' => $_W['uniacid']));
						$pcateid = $crow['parentid'];
						$ccateid = $cid;
					}
					else {
						if ($c['level'] == 3) {
							$tcateid = $cid;
							$tcate = pdo_fetch('select id,parentid from ' . tablename('ewei_shop_category') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $cid, ':uniacid' => $_W['uniacid']));
							$ccateid = $tcate['parentid'];
							$ccate = pdo_fetch('select id,parentid from ' . tablename('ewei_shop_category') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $ccateid, ':uniacid' => $_W['uniacid']));
							$pcateid = $ccate['parentid'];
						}
					}
				}
			}
		}

		$item['pcate'] = $pcateid;
		$item['ccate'] = $ccateid;
		$item['tcate'] = $tcateid;

		if (!empty($cates)) {
			$item['cates'] = implode(',', $cates);
		}

		$item['pcates'] = implode(',', $pcates);
		$item['ccates'] = implode(',', $ccates);
		$item['tcates'] = implode(',', $tcates);
		$item['itemId'] = $itemid;
		$item['title'] = $prodectName;
		$pics = array();
		$imgurls = $xml->xpath('//*[@id="slide"]/ul');

		foreach ($imgurls[0]->li as $imgurl) {
			if (count($pics) < 4) {
				if (count($pics) == 0) {
					$iurl = $imgurl->img->attributes()->src;

					if (stripos($iurl, '//') == 0) {
						$iurl .= 'http:' . $iurl;
					}

					$pics[] = $iurl;
				}
				else {
					$iurl = $imgurl->img->attributes()->imgsrc;

					if (stripos($iurl, '//') == 0) {
						$iurl .= 'http:' . $iurl;
					}

					$pics[] = $iurl;
				}
			}
		}
/* [31m * TODO SEPARATE[0m */

		$item['pics'] = $pics;
		$specs = array();
		$item['total'] = 10;
		$item['sales'] = 0;
		$prodectPriceContent = $xml->xpath('//*[@id="jdPrice"]');
		$prodectPrices = strval($prodectPriceContent[0]->attributes()->value);
		$item['marketprice'] = $prodectPrices;
		$url = $this->get_jingdong_detail_url($itemid);
		$responseDetail = ihttp_get($url);
		$contenteDetail = $responseDetail['content'];
		$details = json_decode($contenteDetail, true);
		$prodectContent = $details['wdis'];
		$prodectContent = strval($prodectContent);
		$prodectContent = $this->contentpasswh($prodectContent);
		$item['content'] = $prodectContent;
		$params = array();
		$pr = $details['ware']['wi']['code'];
		preg_match_all('/<td class="tdTitle">(.*?)<\\/td>/i', $pr, $params1);
		preg_match_all('/<td>(.*?)<\\/td>/i', $pr, $params2);
		$paramsTitle = $params1[1];
		$paramsValue = $params2[1];

		if (count($paramsTitle) == count($paramsValue)) {
			$i = 0;

			while ($i < count($paramsTitle)) {
				$params[] = array('title' => $paramsTitle[$i], 'value' => $paramsValue[$i]);
				++$i;
			}
		}

		$item['params'] = $params;
		return $this->save_jingdong_goods($item, $jingdongurl);
	}

	public function save_taobao_goods($item = array(), $catch_url = '')
	{
		global $_W;
		$data = array('uniacid' => $_W['uniacid'], 'merchid' => $item['merchid'], 'checked' => $item['checked'], 'catch_source' => 'taobao', 'catch_id' => $item['itemId'], 'catch_url' => $catch_url, 'title' => $item['title'], 'total' => $item['total'], 'marketprice' => $item['marketprice'], 'pcate' => $item['pcate'], 'ccate' => $item['ccate'], 'tcate' => $item['tcate'], 'cates' => $item['cates'], 'sales' => $item['sales'], 'createtime' => time(), 'updatetime' => time(), 'hasoption' => 0 < count($item['options']) ? 1 : 0, 'status' => 0, 'deleted' => 0, 'buylevels' => '', 'showlevels' => '', 'buygroups' => '', 'showgroups' => '', 'noticeopenid' => '', 'storeids' => '', 'merchsale' => 1);

		if (empty($item['merchid'])) {
			$data['discounts'] = '{"type":"0","default":"","default_pay":""}';
		}

		$thumb_url = array();
		$pics = $item['pics'];
		$piclen = count($pics);

		if (0 < $piclen) {
			$data['thumb'] = $this->save_image($pics[0], false);

			if (1 < $piclen) {
				$i = 1;

				while ($i < $piclen) {
					$img = $this->save_image($pics[$i], false);
					$thumb_url[] = $img;
					++$i;
				}
			}
		}

		$data['thumb_url'] = serialize($thumb_url);
		$goods = pdo_fetch('select * from ' . tablename('ewei_shop_goods') . ' where  catch_id=:catch_id and catch_source=\'taobao\' and uniacid=:uniacid and merchid=:merchid', array(':catch_id' => $item['itemId'], ':uniacid' => $_W['uniacid'], ':merchid' => $item['merchid']));

		if (empty($goods)) {
			pdo_insert('ewei_shop_goods', $data);
			$goodsid = pdo_insertid();
		}
		else {
			$goodsid = $goods['id'];
			unset($data['createtime']);
			pdo_update('ewei_shop_goods', $data, array('id' => $goodsid));
		}

		$goods_params = pdo_fetchall('select * from ' . tablename('ewei_shop_goods_param') . ' where goodsid=:goodsid ', array(':goodsid' => $goodsid));
		$params = $item['params'];
		$paramids = array();
		$displayorder = 0;

		foreach ($params as $p) {
			$oldp = pdo_fetch('select * from ' . tablename('ewei_shop_goods_param') . ' where goodsid=:goodsid and title=:title limit 1', array(':goodsid' => $goodsid, ':title' => $p['title']));
			$paramid = 0;
			$d = array('uniacid' => $_W['uniacid'], 'goodsid' => $goodsid, 'title' => $p['title'], 'value' => $p['value'], 'displayorder' => $displayorder);

			if (empty($oldp)) {
				pdo_insert('ewei_shop_goods_param', $d);
				$paramid = pdo_insertid();
			}
			else {
				pdo_update('ewei_shop_goods_param', $d, array('id' => $oldp['id']));
				$paramid = $oldp['id'];
			}

			$paramids[] = $paramid;
			++$displayorder;
		}

		if (0 < count($paramids)) {
			pdo_query('delete from ' . tablename('ewei_shop_goods_param') . ' where goodsid=:goodsid and id not in (' . implode(',', $paramids) . ')', array(':goodsid' => $goodsid));
		}
		else {
			pdo_query('delete from ' . tablename('ewei_shop_goods_param') . ' where goodsid=:goodsid ', array(':goodsid' => $goodsid));
		}

		$specs = $item['specs'];
		$specids = array();
		$displayorder = 0;
		$newspecs = array();

		foreach ($specs as $spec) {
			$oldspec = pdo_fetch('select * from ' . tablename('ewei_shop_goods_spec') . ' where goodsid=:goodsid and propId=:propId limit 1', array(':goodsid' => $goodsid, ':propId' => $spec['propId']));
			$specid = 0;
			$d_spec = array('uniacid' => $_W['uniacid'], 'goodsid' => $goodsid, 'title' => $spec['title'], 'displayorder' => $displayorder, 'propId' => $spec['propId']);

			if (empty($oldspec)) {
				pdo_insert('ewei_shop_goods_spec', $d_spec);
				$specid = pdo_insertid();
			}
			else {
				pdo_update('ewei_shop_goods_spec', $d_spec, array('id' => $oldspec['id']));
				$specid = $oldspec['id'];
			}

			$d_spec['id'] = $specid;
			$specids[] = $specid;
			++$displayorder;
			$spec_items = $spec['items'];
			$spec_itemids = array();
			$displayorder_item = 0;
			$newspecitems = array();

			foreach ($spec_items as $spec_item) {
				$d = array('uniacid' => $_W['uniacid'], 'specid' => $specid, 'title' => $spec_item['title'], 'thumb' => $this->save_image($spec_item['thumb'], false), 'valueId' => $spec_item['valueId'], 'show' => 1, 'displayorder' => $displayorder_item);
				$oldspecitem = pdo_fetch('select * from ' . tablename('ewei_shop_goods_spec_item') . ' where specid=:specid and valueId=:valueId limit 1', array(':specid' => $specid, ':valueId' => $spec_item['valueId']));
				$spec_item_id = 0;

				if (empty($oldspecitem)) {
					pdo_insert('ewei_shop_goods_spec_item', $d);
					$spec_item_id = pdo_insertid();
				}
				else {
					pdo_update('ewei_shop_goods_spec_item', $d, array('id' => $oldspecitem['id']));
					$spec_item_id = $oldspecitem['id'];
				}

				++$displayorder_item;
				$spec_itemids[] = $spec_item_id;
				$d['id'] = $spec_item_id;
				$newspecitems[] = $d;
			}

			$d_spec['items'] = $newspecitems;
			$newspecs[] = $d_spec;

			if (0 < count($spec_itemids)) {
				pdo_query('delete from ' . tablename('ewei_shop_goods_spec_item') . ' where specid=:specid and id not in (' . implode(',', $spec_itemids) . ')', array(':specid' => $specid));
			}
			else {
				pdo_query('delete from ' . tablename('ewei_shop_goods_spec_item') . ' where specid=:specid ', array(':specid' => $specid));
			}

			pdo_update('ewei_shop_goods_spec', array('content' => serialize($spec_itemids)), array('id' => $oldspec['id']));
		}

		if (0 < count($specids)) {
			pdo_query('delete from ' . tablename('ewei_shop_goods_spec') . ' where goodsid=:goodsid and id not in (' . implode(',', $specids) . ')', array(':goodsid' => $goodsid));
		}
		else {
			pdo_query('delete from ' . tablename('ewei_shop_goods_spec') . ' where goodsid=:goodsid ', array(':goodsid' => $goodsid));
		}

		$minprice = 0;
		$options = $item['options'];

		if (0 < count($options)) {
			$minprice = $options[0]['marketprice'];
		}

		$optionids = array();
		$displayorder = 0;

		foreach ($options as $o) {
			$option_specs = $o['option_specs'];
			$ids = array();
			$valueIds = array();

			foreach ($option_specs as $os) {
				foreach ($newspecs as $nsp) {
					foreach ($nsp['items'] as $nspitem) {
						if ($nspitem['valueId'] == $os['valueId']) {
							$ids[] = $nspitem['id'];
							$valueIds[] = $nspitem['valueId'];
						}
					}
				}
			}

			$ids = implode('_', $ids);
			$valueIds = implode('_', $valueIds);
			$do = array('uniacid' => $_W['uniacid'], 'displayorder' => $displayorder, 'goodsid' => $goodsid, 'title' => implode('+', $o['title']), 'specs' => $ids, 'stock' => $o['stock'], 'marketprice' => $o['marketprice'], 'skuId' => $o['skuId']);

			if ($o['marketprice'] < $minprice) {
				$minprice = $o['marketprice'];
			}

			$oldoption = pdo_fetch('select * from ' . tablename('ewei_shop_goods_option') . ' where goodsid=:goodsid and skuId=:skuId limit 1', array(':goodsid' => $goodsid, ':skuId' => $o['skuId']));
			$option_id = 0;

			if (empty($oldoption)) {
				pdo_insert('ewei_shop_goods_option', $do);
				$option_id = pdo_insertid();
			}
			else {
				pdo_update('ewei_shop_goods_option', $do, array('id' => $oldoption['id']));
				$option_id = $oldoption['id'];
			}

			++$displayorder;
			$optionids[] = $option_id;
		}

		if (0 < count($optionids)) {
			pdo_query('delete from ' . tablename('ewei_shop_goods_option') . ' where goodsid=:goodsid and id not in (' . implode(',', $optionids) . ')', array(':goodsid' => $goodsid));
		}
		else {
			pdo_query('delete from ' . tablename('ewei_shop_goods_option') . ' where goodsid=:goodsid ', array(':goodsid' => $goodsid));
		}

		$response = $item['content'];
		$content = $response['content'];
		preg_match_all('/<img.*?src=[\\\\\'| \\"](.*?(?:[\\.gif|\\.jpg]?))[\\\\\'|\\"].*?[\\/]?>/', $content, $imgs);

		if (isset($imgs[1])) {
			foreach ($imgs[1] as $img) {
				$catchimg = $img;

				if (substr($catchimg, 0, 2) == '//') {
					$img = 'http://' . substr($img, 2);
				}

				$im = array('catchimg' => $catchimg, 'system' => $this->save_image($img, true));
				$images[] = $im;
			}
		}

		preg_match('/tfsContent : \\\'(.*)\\\'/', $content, $html);
		$html = iconv('GBK', 'UTF-8', $html[1]);

		if (isset($images)) {
			foreach ($images as $img) {
				$html = str_replace($img['catchimg'], $img['system'], $html);
			}
		}

		$html = m('common')->html_to_images($html);
		$hasoption = 0;

		if (0 < count($options)) {
			$hasoption = 1;
		}

		$d = array('content' => $html, 'hasoption' => $hasoption);

		if (0 < $minprice) {
			$d['marketprice'] = $minprice;
		}

		pdo_update('ewei_shop_goods', $d, array('id' => $goodsid));

		if ($d['hasoption']) {
			$sql = 'update ' . tablename('ewei_shop_goods') . " g set\r\n            g.minprice = (select min(marketprice) from " . tablename('ewei_shop_goods_option') . ' where goodsid = ' . $goodsid . " and marketprice > 0),\r\n            g.maxprice = (select max(marketprice) from " . tablename('ewei_shop_goods_option') . ' where goodsid = ' . $goodsid . ")\r\n            where g.id = " . $goodsid . ' and g.hasoption=1';
		}
		else {
			$sql = 'update ' . tablename('ewei_shop_goods') . ' set minprice = marketprice,maxprice = marketprice where id = ' . $goodsid . ' and hasoption=0;';
		}

		pdo_query($sql);
		return array('result' => '1', 'goodsid' => $goodsid);
	}

	public function save_jingdong_goods($item = array(), $catch_url = '')
	{
		global $_W;
		$data = array('uniacid' => $_W['uniacid'], 'merchid' => $item['merchid'], 'checked' => $item['checked'], 'catch_source' => 'jingdong', 'catch_id' => $item['itemId'], 'catch_url' => $catch_url, 'title' => $item['title'], 'total' => $item['total'], 'marketprice' => $item['marketprice'], 'pcate' => $item['pcate'], 'ccate' => $item['ccate'], 'tcate' => $item['tcate'], 'cates' => $item['cates'], 'sales' => $item['sales'], 'createtime' => time(), 'updatetime' => time(), 'hasoption' => 0, 'status' => 0, 'deleted' => 0, 'buylevels' => '', 'showlevels' => '', 'buygroups' => '', 'showgroups' => '', 'noticeopenid' => '', 'storeids' => '', 'minprice' => $item['marketprice'], 'maxprice' => $item['marketprice'], 'merchsale' => 1);

		if (empty($item['merchid'])) {
			$data['discounts'] = '{"type":"0","default":"","default_pay":""}';
		}

		$thumb_url = array();
		$pics = $item['pics'];
		$piclen = count($pics);

		if (0 < $piclen) {
			$data['thumb'] = $this->save_image($pics[0], false);

			if (1 < $piclen) {
				$i = 1;

				while ($i < $piclen) {
					$img = $this->save_image($pics[$i], false);
					$thumb_url[] = $img;
					++$i;
				}
			}
		}

		$data['thumb_url'] = serialize($thumb_url);
		$goods = pdo_fetch('select * from ' . tablename('ewei_shop_goods') . ' where  catch_id=:catch_id and catch_source=\'jingdong\' and uniacid=:uniacid and merchid=:merchid', array(':catch_id' => $item['itemId'], ':uniacid' => $_W['uniacid'], ':merchid' => $item['merchid']));

		if (empty($goods)) {
			pdo_insert('ewei_shop_goods', $data);
			$goodsid = pdo_insertid();
		}
		else {
			$goodsid = $goods['id'];
			unset($data['createtime']);
			pdo_update('ewei_shop_goods', $data, array('id' => $goodsid));
		}

		$goods_params = pdo_fetchall('select * from ' . tablename('ewei_shop_goods_param') . ' where goodsid=:goodsid ', array(':goodsid' => $goodsid));
		$params = $item['params'];
		$paramids = array();
		$displayorder = 0;

		foreach ($params as $p) {
			$oldp = pdo_fetch('select * from ' . tablename('ewei_shop_goods_param') . ' where goodsid=:goodsid and title=:title limit 1', array(':goodsid' => $goodsid, ':title' => $p['title']));
			$paramid = 0;
			$d = array('uniacid' => $_W['uniacid'], 'goodsid' => $goodsid, 'title' => $p['title'], 'value' => $p['value'], 'displayorder' => $displayorder);

			if (empty($oldp)) {
				pdo_insert('ewei_shop_goods_param', $d);
				$paramid = pdo_insertid();
			}
			else {
				pdo_update('ewei_shop_goods_param', $d, array('id' => $oldp['id']));
				$paramid = $oldp['id'];
			}

			$paramids[] = $paramid;
			++$displayorder;
		}

		if (0 < count($paramids)) {
			pdo_query('delete from ' . tablename('ewei_shop_goods_param') . ' where goodsid=:goodsid and id not in (' . implode(',', $paramids) . ')', array(':goodsid' => $goodsid));
		}
		else {
			pdo_query('delete from ' . tablename('ewei_shop_goods_param') . ' where goodsid=:goodsid ', array(':goodsid' => $goodsid));
		}

		$content = $item['content'];
		preg_match_all('/<img.*?src=[\\\\\'| \\"](.*?(?:[\\.gif|\\.jpg]?))[\\\\\'|\\"].*?[\\/]?>/', $content, $imgs);

		if (isset($imgs[1])) {
			foreach ($imgs[1] as $img) {
				$catchimg = $img;

				if (substr($catchimg, 0, 2) == '//') {
					$img = 'http://' . substr($img, 2);
				}

				$im = array('catchimg' => $catchimg, 'system' => $this->save_image($img, true));
				$images[] = $im;
			}
		}

		$html = $content;

		if (isset($images)) {
			foreach ($images as $img) {
				$html = str_replace($img['catchimg'], $img['system'], $html);
			}
		}

		$html = m('common')->html_to_images($html);
		$d = array('content' => $html);
		pdo_update('ewei_shop_goods', $d, array('id' => $goodsid));
		return array('result' => '1', 'goodsid' => $goodsid);
	}

	public function save_1688_goods($item = array(), $catch_url = '')
	{
		global $_W;
		$data = array('uniacid' => $_W['uniacid'], 'merchid' => $item['merchid'], 'checked' => $item['checked'], 'catch_source' => '1688', 'catch_id' => $item['itemId'], 'catch_url' => $catch_url, 'title' => $item['title'], 'total' => $item['total'], 'marketprice' => $item['marketprice'], 'pcate' => $item['pcate'], 'ccate' => $item['ccate'], 'tcate' => $item['tcate'], 'cates' => $item['cates'], 'sales' => $item['sales'], 'createtime' => time(), 'updatetime' => time(), 'hasoption' => 0, 'status' => 0, 'deleted' => 0, 'buylevels' => '', 'showlevels' => '', 'buygroups' => '', 'showgroups' => '', 'noticeopenid' => '', 'storeids' => '', 'minprice' => $item['marketprice'], 'maxprice' => $item['marketprice'], 'merchsale' => 1);

		if (empty($item['merchid'])) {
			$data['discounts'] = '{"type":"0","default":"","default_pay":""}';
		}

		$thumb_url = array();
		$pics = $item['pics'];
		$piclen = count($pics);

		if (0 < $piclen) {
			$data['thumb'] = $this->save_image($pics[0], false);

			if (1 < $piclen) {
				$i = 1;

				while ($i < $piclen) {
					$img = $this->save_image($pics[$i], false);
					$thumb_url[] = $img;
					++$i;
				}
			}
		}

		$data['thumb_url'] = serialize($thumb_url);
		$goods = pdo_fetch('select * from ' . tablename('ewei_shop_goods') . ' where  catch_id=:catch_id and catch_source=\'1688\' and uniacid=:uniacid and merchid=:merchid', array(':catch_id' => $item['itemId'], ':uniacid' => $_W['uniacid'], ':merchid' => $item['merchid']));

		if (empty($goods)) {
			pdo_insert('ewei_shop_goods', $data);
			$goodsid = pdo_insertid();
		}
		else {
			$goodsid = $goods['id'];
			unset($data['createtime']);
			pdo_update('ewei_shop_goods', $data, array('id' => $goodsid));
		}

		$goods_params = pdo_fetchall('select * from ' . tablename('ewei_shop_goods_param') . ' where goodsid=:goodsid ', array(':goodsid' => $goodsid));
		$params = $item['params'];
		$paramids = array();
		$displayorder = 0;

		foreach ($params as $p) {
			$oldp = pdo_fetch('select * from ' . tablename('ewei_shop_goods_param') . ' where goodsid=:goodsid and title=:title limit 1', array(':goodsid' => $goodsid, ':title' => $p['title']));
			$paramid = 0;
			$d = array('uniacid' => $_W['uniacid'], 'goodsid' => $goodsid, 'title' => $p['title'], 'value' => $p['value'], 'displayorder' => $displayorder);

			if (empty($oldp)) {
				pdo_insert('ewei_shop_goods_param', $d);
				$paramid = pdo_insertid();
			}
			else {
				pdo_update('ewei_shop_goods_param', $d, array('id' => $oldp['id']));
				$paramid = $oldp['id'];
			}

			$paramids[] = $paramid;
			++$displayorder;
		}

		if (0 < count($paramids)) {
			pdo_query('delete from ' . tablename('ewei_shop_goods_param') . ' where goodsid=:goodsid and id not in (' . implode(',', $paramids) . ')', array(':goodsid' => $goodsid));
		}
		else {
			pdo_query('delete from ' . tablename('ewei_shop_goods_param') . ' where goodsid=:goodsid ', array(':goodsid' => $goodsid));
		}

		$specs = $item['specs'];
		$specids = array();
		$displayorder = 0;
		$newspecs = array();

		foreach ($specs as $spec) {
			$oldspec = pdo_fetch('select * from ' . tablename('ewei_shop_goods_spec') . ' where goodsid=:goodsid and propId=:propId limit 1', array(':goodsid' => $goodsid, ':propId' => $spec['propId']));
			$specid = 0;
			$d_spec = array('uniacid' => $_W['uniacid'], 'goodsid' => $goodsid, 'title' => $spec['title'], 'displayorder' => $displayorder, 'propId' => $spec['propId']);

			if (empty($oldspec)) {
				pdo_insert('ewei_shop_goods_spec', $d_spec);
				$specid = pdo_insertid();
			}
			else {
				pdo_update('ewei_shop_goods_spec', $d_spec, array('id' => $oldspec['id']));
				$specid = $oldspec['id'];
			}

			$d_spec['id'] = $specid;
			$specids[] = $specid;
			++$displayorder;
			$spec_items = $spec['items'];
			$spec_itemids = array();
			$displayorder_item = 0;
			$newspecitems = array();

			foreach ($spec_items as $spec_item) {
				$d = array('uniacid' => $_W['uniacid'], 'specid' => $specid, 'title' => $spec_item['title'], 'thumb' => $this->save_image($spec_item['thumb'], false), 'valueId' => $spec_item['valueId'], 'show' => 1, 'displayorder' => $displayorder_item);
				$oldspecitem = pdo_fetch('select * from ' . tablename('ewei_shop_goods_spec_item') . ' where specid=:specid and valueId=:valueId limit 1', array(':specid' => $specid, ':valueId' => $spec_item['valueId']));
				$spec_item_id = 0;

				if (empty($oldspecitem)) {
					pdo_insert('ewei_shop_goods_spec_item', $d);
					$spec_item_id = pdo_insertid();
				}
				else {
					pdo_update('ewei_shop_goods_spec_item', $d, array('id' => $oldspecitem['id']));
					$spec_item_id = $oldspecitem['id'];
				}

				++$displayorder_item;
				$spec_itemids[] = $spec_item_id;
				$d['id'] = $spec_item_id;
				$newspecitems[] = $d;
			}

			$d_spec['items'] = $newspecitems;
			$newspecs[] = $d_spec;

			if (0 < count($spec_itemids)) {
				pdo_query('delete from ' . tablename('ewei_shop_goods_spec_item') . ' where specid=:specid and id not in (' . implode(',', $spec_itemids) . ')', array(':specid' => $specid));
			}
			else {
				pdo_query('delete from ' . tablename('ewei_shop_goods_spec_item') . ' where specid=:specid ', array(':specid' => $specid));
			}

			pdo_update('ewei_shop_goods_spec', array('content' => serialize($spec_itemids)), array('id' => $oldspec['id']));
		}

		if (0 < count($specids)) {
			pdo_query('delete from ' . tablename('ewei_shop_goods_spec') . ' where goodsid=:goodsid and id not in (' . implode(',', $specids) . ')', array(':goodsid' => $goodsid));
		}
		else {
			pdo_query('delete from ' . tablename('ewei_shop_goods_spec') . ' where goodsid=:goodsid ', array(':goodsid' => $goodsid));
		}

		$content = $item['content'];
		preg_match_all('/<img.*?src=[\\\\\'| \\"](.*?(?:[\\.gif|\\.jpg]?))[\\\\\'|\\"].*?[\\/]?>/', $content, $imgs);

		if (isset($imgs[1])) {
			foreach ($imgs[1] as $img) {
				$catchimg = $img;

				if (substr($catchimg, 0, 2) == '//') {
					$img = 'http://' . substr($img, 2);
				}

				$im = array('catchimg' => $catchimg, 'system' => $this->save_image($img, true));
				$images[] = $im;
			}
		}

		$html = $content;

		if (isset($images)) {
			foreach ($images as $img) {
				$html = str_replace($img['catchimg'], $img['system'], $html);
			}
		}

		$html = m('common')->html_to_images($html);
		$d = array('content' => $html);
		pdo_update('ewei_shop_goods', $d, array('id' => $goodsid));
		return array('result' => '1', 'goodsid' => $goodsid);
	}

	public function save_taobaocsv_goods($item = array(), $merchid = 0)
	{
		global $_W;
		$data = array('uniacid' => $_W['uniacid'], 'merchid' => $merchid, 'catch_source' => 'taobaocsv', 'catch_id' => '', 'catch_url' => '', 'title' => $item['title'], 'total' => $item['total'], 'marketprice' => $item['marketprice'], 'pcate' => '', 'ccate' => '', 'tcate' => '', 'cates' => '', 'sales' => 0, 'createtime' => time(), 'updatetime' => time(), 'hasoption' => 0, 'status' => 0, 'deleted' => 0, 'buylevels' => '', 'showlevels' => '', 'buygroups' => '', 'showgroups' => '', 'noticeopenid' => '', 'storeids' => '', 'minprice' => $item['marketprice'], 'maxprice' => $item['marketprice'], 'merchsale' => 1);

		if (empty($item['merchid'])) {
			$data['discounts'] = '{"type":"0","default":"","default_pay":""}';
		}

		if (!empty($merchid)) {
			if (empty($_W['merch_user']['goodschecked'])) {
				$data['checked'] = 1;
			}
			else {
				$data['checked'] = 0;
			}
		}

		$thumb_url = array();
		$pics = $item['pics'];
		$piclen = count($pics);

		if (0 < $piclen) {
			$data['thumb'] = $this->save_image($pics[0], false);

			if (1 < $piclen) {
				$i = 1;

				while ($i < $piclen) {
					$img = $this->save_image($pics[$i], false);
					$thumb_url[] = $img;
					++$i;
				}
			}
		}

		$data['thumb_url'] = serialize($thumb_url);
		pdo_insert('ewei_shop_goods', $data);
		$goodsid = pdo_insertid();
		$content = $item['content'];
		preg_match_all('/<img.*?src=[\\\\\'| \\"](.*?(?:[\\.gif|\\.jpg]?))[\\\\\'|\\"].*?[\\/]?>/', $content, $imgs);

		if (isset($imgs[1])) {
			foreach ($imgs[1] as $img) {
				$catchimg = $img;

				if (substr($catchimg, 0, 2) == '//') {
					$img = 'http://' . substr($img, 2);
				}

				$im = array('catchimg' => $catchimg, 'system' => $this->save_image($img, true));
				$images[] = $im;
			}
		}

		$html = $content;

		if (isset($images)) {
			foreach ($images as $img) {
				$html = str_replace($img['catchimg'], $img['system'], $html);
			}
		}

		$html = m('common')->html_to_images($html);
		$d = array('content' => $html);
		pdo_update('ewei_shop_goods', $d, array('id' => $goodsid));
		return array('result' => '1', 'goodsid' => $goodsid);
	}

	public function get_taobao_info_url($itemid)
	{
		$url = 'http://hws.m.taobao.com/cache/wdetail/5.0/?id=' . $itemid;
		$url = $this->getRealURL($url);
		return $url;
	}

	public function get_taobao_detail_url($itemid)
	{
		$url = 'http://hws.m.taobao.com/cache/wdesc/5.0/?id=' . $itemid;
		$url = $this->getRealURL($url);
		return $url;
	}

	public function get_jingdong_info_url($itemid)
	{
		$url = 'http://item.m.jd.com/ware/view.action?wareId=' . $itemid;
		$url = $this->getRealURL($url);
		return $url;
	}

	public function get_jingdong_detail_url($itemid)
	{
		$url = 'http://item.m.jd.com/ware/detail.json?wareId=' . $itemid;
		$url = $this->getRealURL($url);
		return $url;
	}

	public function get_1688_info_url($itemid)
	{
		$url = 'https://m.1688.com/offer/' . $itemid . '.html';
		return $url;
	}

	public function save_image($url, $iscontent)
	{
		global $_W;
		$ext = strrchr($url, '.');
		if (($ext != '.jpeg') && ($ext != '.gif') && ($ext != '.jpg') && ($ext != '.png')) {
			return $url;
		}

		if (trim($url) == '') {
			return $url;
		}

		$filename = random(32) . $ext;
		$save_dir = ATTACHMENT_ROOT . 'images/' . $_W['uniacid'] . '/' . date('Y') . '/' . date('m') . '/';
		if (!file_exists($save_dir) && !mkdir($save_dir, 511, true)) {
			return $url;
		}

		$img = ihttp_get($url);

		if (is_error($img)) {
			return '';
		}

		$img = $img['content'];

		if (strlen($img) != 0) {
			file_put_contents($save_dir . $filename, $img);
			$imgdir = 'images/' . $_W['uniacid'] . '/' . date('Y') . '/' . date('m') . '/';
			$saveurl = save_media($imgdir . $filename, true);
			return $saveurl;
		}

		return '';
	}

	public function getRealURL($url)
	{
		if (function_exists('stream_context_set_default')) {
			stream_context_set_default(array(
	'http' => array('method' => 'HEAD')
	));
		}

		$header = get_headers($url, 1);
		if (strpos($header[0], '301') || strpos($header[0], '302')) {
			if (is_array($header['Location'])) {
				return $header['Location'][count($header['Location']) - 1];
			}

			return $header['Location'];
		}

		return $url;
	}

	public function check_remote_file_exists($url)
	{
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_NOBODY, true);
		$result = curl_exec($curl);
		$found = false;

		if ($result !== false) {
			$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

			if ($statusCode == 200) {
				$found = true;
			}
		}

		curl_close($curl);
		return $found;
	}

	public function get_pageno_url($url = '', $pageNo = 1)
	{
		$url .= '/search.htm?pageNo=' . $pageNo;
		return $url;
	}

	public function get_total_page($url = '', $taobao = false)
	{
		if (empty($url)) {
			return array('totalpage' => 0);
		}

		$content = $this->get_page_content($url);
		exit($content);
		$str = '';

		if ($taobao) {
			$str = '/<span class="page-info">(.*)<\\/span>/';
		}
		else {
			$str = '/<b class="ui-page-s-len">(.*)<\\/b>/';
		}

		preg_match($str, $content, $p);

		if (is_array($p)) {
			$pages = explode('/', $p[1]);
			return array('totalpage' => $pages[1]);
		}

		return array('totalpage' => 0);
	}

	public function httpGet($url)
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, 500);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_URL, $url);
		$res = curl_exec($curl);
		curl_close($curl);
		return $res;
	}

	public function get_page_content($url = '', $pageNo = 1)
	{
		if (empty($url)) {
			return array('totalpage' => 0);
		}

		$url = $this->get_pageno_url($url, $pageNo);
		load()->func('communication');
		$response = ihttp_get($url);

		if (!isset($response['content'])) {
			return array('result' => 0);
		}

		return $response['content'];
	}

	public function get_pag_items($pageContent = '')
	{
		$str = '/data-id="(.*)"/U';
		preg_match_all($str, $pageContent, $items);

		if (isset($items[1])) {
			return $items[1];
		}

		return array();
	}

	public function contentpasswh($content)
	{
		$content = preg_replace('/(?:width)=(\'|").*?\\1/', ' width="100%"', $content);
		$content = preg_replace('/(?:height)=(\'|").*?\\1/', ' ', $content);
		$content = preg_replace('/(?:max-width:\\d*\\.?\\d*(px|rem|em))/', '', $content);
		$content = preg_replace('/(?:max-height:\\d*\\.?\\d*(px|rem|em))/', '', $content);
		$content = preg_replace('/(?:min-width:\\d*\\.?\\d*(px|rem|em))/', ' ', $content);
		$content = preg_replace('/(?:min-height:\\d*\\.?\\d*(px|rem|em))/', ' ', $content);
		return $content;
	}

	public function get_item_one688($itemid = '', $one688url = '', $cates, $merchid = 0)
	{
		$this->alibaba($itemid, $cates, $merchid);
	}

	public function alibaba($itemid, $cates, $merchid = 0)
	{
		global $_W;
		global $_GPC;
		error_reporting(0);

		if (true) {
			$id = $itemid;
			$itemUrl = 'http://m.1688.com/offer/' . $id . '.html';
			$html = file_get_contents($itemUrl);
			preg_match('/window\\.wingxViewData=window\\.wingxViewData\\|\\|{};window\\.wingxViewData\\[0\\]=(.+)<\\/script>/', $html, $res);
			$json1 = $res[1];
			$json1 = json_decode($json1, true);

			if (empty($json1['detailUrl'])) {
				show_json(-1, '商品获取失败');
			}

			$detailUrl = $json1['detailUrl'];
			load()->func('communication');
			$detail = ihttp_get($detailUrl);
			$detail = iconv('GBK', 'UTF-8', $detail['content']);
			preg_match('/var offer_details=(.+);$/', $detail, $detailStr);
			$detail_temp = json_decode($detailStr[1], true);

			if (empty($detail_temp)) {
				preg_match('/var desc=\'(.+)\';$/', $detail, $detailStr);
				unset($detail);
				$detail['content'] = $detailStr[1];
			}
			else {
				$detail = $detail_temp;
			}

			$thumb_url = array();

			foreach ($json1['imageList'] as $k => $v) {
				$thumb_url[] = $this->save_image($v['originalImageURI'], 1);
			}

			$thumb = $thumb_url[0];
			unset($thumb_url[0]);
			$priceRange = explode('-', $json1['priceDisplay']);
			$minprice = floatval($priceRange[0]);
			$maxprice = (empty($priceRange[1]) ? floatval($priceRange[0]) : floatval($priceRange[1]));
			$hasoption = (empty($json1['skuProps']) ? 0 : 1);
			$param = $json1['productFeatureList'];
			$detail['content'] = $this->contentpasswh($detail['content']);
			preg_match_all('/<img.*?src=[\\\\\'| \\"](.*?(?:[\\.gif|\\.jpg]?))[\\\\\'|\\"].*?[\\/]?>/', $detail['content'], $imgs);

			if (isset($imgs[1])) {
				foreach ($imgs[1] as $img) {
					$catchimg = $img;

					if (substr($catchimg, 0, 2) == '//') {
						$img = 'http://' . substr($img, 2);
					}

					$im = array('catchimg' => $catchimg, 'system' => $this->save_image($img, true));
					$images[] = $im;
				}
			}

			if (isset($images)) {
				foreach ($images as $img) {
					$detail['content'] = str_replace($img['catchimg'], $img['system'], $detail['content']);
				}
			}

			$detail['content'] = m('common')->html_to_images($detail['content']);
			$data = array('uniacid' => $_W['uniacid'], 'thumb' => $thumb, 'thumb_url' => serialize($thumb_url), 'title' => $json1['subject'], 'status' => 0, 'marketprice' => $maxprice, 'originalprice' => $minprice, 'minprice' => $minprice, 'maxprice' => $maxprice, 'hasoption' => $hasoption, 'createtime' => time(), 'total' => $json1['canBookedAmount'], 'content' => $detail['content'], 'merchid' => $merchid, 'cates' => $cates, 'checked' => empty($merchid) ? 0 : 1);
			$pcates = array();
			$ccates = array();
			$tcates = array();
			$pcateid = 0;
			$ccateid = 0;
			$tcateid = 0;

			if (is_array($cates)) {
				foreach ($cates as $key => $cid) {
					$c = pdo_fetch('select level from ' . tablename('ewei_shop_category') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $cid, ':uniacid' => $_W['uniacid']));

					if ($c['level'] == 1) {
						$pcates[] = $cid;
					}
					else if ($c['level'] == 2) {
						$ccates[] = $cid;
					}
					else {
						if ($c['level'] == 3) {
							$tcates[] = $cid;
						}
					}

					if ($key == 0) {
						if ($c['level'] == 1) {
							$pcateid = $cid;
						}
						else if ($c['level'] == 2) {
							$crow = pdo_fetch('select parentid from ' . tablename('ewei_shop_category') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $cid, ':uniacid' => $_W['uniacid']));
							$pcateid = $crow['parentid'];
							$ccateid = $cid;
						}
						else {
							if ($c['level'] == 3) {
								$tcateid = $cid;
								$tcate = pdo_fetch('select id,parentid from ' . tablename('ewei_shop_category') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $cid, ':uniacid' => $_W['uniacid']));
								$ccateid = $tcate['parentid'];
								$ccate = pdo_fetch('select id,parentid from ' . tablename('ewei_shop_category') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $ccateid, ':uniacid' => $_W['uniacid']));
								$pcateid = $ccate['parentid'];
							}
						}
					}
				}
			}

			$data['pcate'] = $pcateid;
			$data['ccate'] = $ccateid;
			$data['tcate'] = $tcateid;

			if (!empty($cates)) {
				$data['cates'] = implode(',', $cates);
			}

			$data['pcates'] = implode(',', $pcates);
			$data['ccates'] = implode(',', $ccates);
			$data['tcates'] = implode(',', $tcates);
			pdo_insert('ewei_shop_goods', $data);
			$goodsid = pdo_insertid();

			if (empty($goodsid)) {
				show_json(0, '抓取失败');
			}

			$param = $this->paramFormat($param, $goodsid);

			if ($hasoption) {
				foreach ($json1['skuProps'] as $k => $v) {
					$spec = $v['prop'];
					pdo_insert('ewei_shop_goods_spec', array('uniacid' => $_W['uniacid'], 'goodsid' => $goodsid, 'title' => $spec));
					$specId = pdo_insertid();

					foreach ($v['value'] as $key => $val) {
						$thumb = $val['imageUrl'];
						$title = $val['name'];
						pdo_insert('ewei_shop_goods_spec_item', array('specid' => $specId, 'title' => $val['name'], 'thumb' => empty($thumb) ? '' : $thumb, 'show' => 1));
						$specs = pdo_insertid();
						$specsid[$k][$key][$title] = $specs;
					}
				}

				$map = $json1['skuMap'];

				foreach ($map as $k => $v) {
					$specArr = explode('&gt;', $k);

					foreach ($specsid as $key => $item) {
						foreach ($item as $v1) {
							if (!empty($v1[$specArr[$key]])) {
								$sss[] = $v1[$specArr[$key]];
							}
						}
					}

					$option['specs'] = implode('_', $sss);
					unset($sss);
					$option['title'] = str_replace('&gt;', '+', $k);

					if (!empty($v['price'])) {
						$option['marketprice'] = $v['price'];
					}
					else if (!empty($v['discountPrice'])) {
						$option['marketprice'] = $v['discountPrice'];
					}
					else if (!empty($json1['discountPriceRanges'])) {
						$option['marketprice'] = $json1['discountPriceRanges'][0]['price'];
					}
					else {
						$option['marketprice'] = 0;
					}

					$option['stock'] = $v['canBookCount'];
					$option['goodsid'] = $goodsid;

					if (!empty($v['price'])) {
						$option['productprice'] = $v['price'];
					}
					else if (!empty($v['discountPrice'])) {
						$option['productprice'] = $v['discountPrice'];
					}
					else if (!empty($json1['discountPriceRanges'])) {
						$option['productprice'] = $json1['discountPriceRanges'][0]['price'];
					}
					else {
						$option['productprice'] = 0;
					}

					pdo_insert('ewei_shop_goods_option', $option);
				}
			}

			return array(1, $goodsid);
		}
	}

	private function paramFormat($param, $id)
	{
		global $_W;
		$value = array();

		foreach ($param as $k => $v) {
			if (!empty($v['name']) && !empty($v['value'])) {
				$unit = (empty($v['unit']) ? '' : $v['unit']);
				$value[$v['name']] = $v['value'] . $unit;
			}
		}

		foreach ($value as $key => $val) {
			pdo_insert('ewei_shop_goods_param', array('goodsid' => $id, 'uniacid' => $_W['uniacid'], 'title' => $key, 'value' => $val));
		}
	}
}

?>
