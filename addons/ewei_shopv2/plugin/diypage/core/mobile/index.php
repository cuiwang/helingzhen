<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
}

class Index_EweiShopV2Page extends PluginMobilePage
{
	public function main()
	{
		global $_W;
		global $_GPC;
		$id = intval($_GPC['id']);

		if (empty($id)) {
			$this->message('请求参数错误！', mobileUrl());
		}

		$page = $this->model->getPage($id, true);

		if (empty($page)) {
			$this->message('页面不存在！', mobileUrl());
		}

		if (empty($_W['openid']) && (($page['type'] == 3) || ($page['type'] == 4))) {
			$_W['openid'] = m('account')->checkLogin();
		}

		$member = m('member')->getMember($_W['openid']);

		if ($page['type'] == 4) {
			$comset = $_W['shopset']['commission'];

			if (empty($comset['level'])) {
				$this->message('未开启分销', mobileUrl());
			}

			if (($member['isagent'] != 1) || ($member['status'] != 1)) {
				$jumpurl = (!empty($comset['no_commission_url']) ? trim($comset['no_commission_url']) : mobileUrl('commission/register'));
				header('location:' . $jumpurl);
				exit();
			}
		}
		else {
			if ($page['type'] == 5) {
				header('location:' . mobileUrl('goods'));
				exit();
			}
		}

		if (!empty($page['data']['page']['visit']) && ($page['data']['page']['type'] == 1)) {
			if (empty($_W['openid'])) {
				$_W['openid'] = m('account')->checkLogin();
				exit();
			}

			$title = (!empty($page['data']['page']['novisit']['title']) ? $page['data']['page']['novisit']['title'] : '您没有权限访问!');
			$link = (!empty($page['data']['page']['novisit']['link']) ? $page['data']['page']['novisit']['link'] : mobileUrl());
			$visit_m = $page['data']['page']['visitlevel']['member'];
			$visit_c = $page['data']['page']['visitlevel']['commission'];
			$visit_c = (isset($visit_c) ? explode(',', $visit_c) : array());
			$visit_m = (isset($visit_m) ? explode(',', $visit_m) : array());
			if (!in_array(empty($member['level']) ? 'default' : $member['level'], $visit_m) && (!in_array($member['agentlevel'], $visit_c) || empty($member['isagent']) || empty($member['status']))) {
				$this->message($title, $link);
			}
		}

		$diyitems = $page['data']['items'];
		$diyitem_search = array();
		if (!empty($diyitems) && is_array($diyitems)) {
			$jsondiyitems = json_encode($diyitems);

			if (strexists($jsondiyitems, 'fixedsearch')) {
				foreach ($diyitems as $diyitemid => $diyitem) {
					if ($diyitem['id'] == 'fixedsearch') {
						$diyitem_search = $diyitem;
						unset($diyitems[$diyitemid]);
					}
				}

				unset($diyitem);
			}
		}

		$this->page = $page;
		$startadv = $this->model->getStartAdv($page['diyadv']);
		$this->model->setShare($page);
		include $this->template();
	}

	public function getmerch()
	{
		global $_W;
		global $_GPC;

		if ($_W['ispost']) {
			$lat = floatval($_GPC['lat']);
			$lng = floatval($_GPC['lng']);
			$item = $_GPC['item'];
			if (empty($item) || !p('merch')) {
				show_json(0, '参数错误或未启用多商户');
			}

			$condition = ' and status=1 and uniacid=:uniacid ';
			$params = array(':uniacid' => $_W['uniacid']);
			$orderby = ' isrecommand desc, id asc ';

			if ($item['params']['merchdata'] == 0) {
				$merchids = array();

				foreach ($item['data'] as $index => $data) {
					if (!empty($data['merchid'])) {
						$merchids[] = $data['merchid'];
					}
				}

				$newmerchids = implode(',', $merchids);

				if (empty($newmerchids)) {
					show_json(0, '商户组数据为空');
				}

				$condition .= ' and id in( ' . $newmerchids . ' ) ';
			}
			else if ($item['params']['merchdata'] == 1) {
				if (empty($item['params']['cateid'])) {
					show_json(0, '商户组cateid为空');
				}

				$condition .= ' and cateid=:cateid ';
				$params['cateid'] = $item['params']['cateid'];
			}
			else if ($item['params']['merchdata'] == 2) {
				if (empty($item['params']['groupid'])) {
					show_json(0, '商户组groupid为空');
				}

				$condition .= ' and groupid=:groupid ';
				$params['groupid'] = $item['params']['groupid'];
			}
			else {
				if ($item['params']['merchdata'] == 3) {
					$condition .= ' and isrecommand=1 ';
				}
			}

			$limit = 0;
			if (!empty($item['params']['merchdata']) && !empty($item['params']['merchnum'])) {
				$limit = $item['params']['merchnum'];
			}

			$limitsql = '';
			if (empty($item['params']['merchsort']) && !empty($limit)) {
				$limitsql = ' limit ' . $limit;
			}

			$merchs = pdo_fetchall('select id, merchname as `name`, logo as thumb, status, `desc`, address, tel, lng, lat from ' . tablename('ewei_shop_merch_user') . ' where 1 ' . $condition . ' order by ' . $orderby . $limitsql, $params);

			if (empty($merchs)) {
				show_json(0, '未查询到数据');
			}

			$merchs = set_medias($merchs, array('thumb'));

			foreach ($merchs as $index => $merch) {
				if (!empty($merch['lat']) && !empty($merch['lng'])) {
					$distance = m('util')->GetDistance($lat, $lng, $merch['lat'], $merch['lng'], 2);
					$merchs[$index]['distance'] = $distance;
				}
			}

			if (empty($lat) || empty($lng) || empty($item['params']['merchsort'])) {
				show_json(1, array('list' => $merchs));
			}

			if (!empty($item['params']['openlocation'])) {
				$sort = SORT_DESC;

				if (1 < $item['params']['merchsort']) {
					$sort = SORT_ASC;
				}

				$merchs = m('util')->multi_array_sort($merchs, 'distance', $sort);
				if (!empty($limit) && !empty($merchs)) {
					$newmerchs = array();

					foreach ($merchs as $index => $merch) {
						if (($index + 1) <= $limit) {
							$newmerchs[$index] = $merch;
						}
						else {
							continue;
						}
					}

					$merchs = $newmerchs;
				}
			}

			show_json(1, array('list' => $merchs));
		}

		show_json(0, '错误的请求');
	}

	public function uECt2c4xuD5oQ6ZGgym2()
	{
		require __DIR__ . '/menu.php';
	}
}

?>
