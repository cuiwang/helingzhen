<?php

if (!defined('IN_IA')) {
	exit('Access Denied');
}
 
require EWEI_SHOPV2_PLUGIN . 'app/core/page_mobile.php';
class Shop_EweiShopV2Page extends AppMobilePage
{
	public function get_shopindex()
	{
		global $_W;
		global $_GPC;
		$uniacid = $_W['uniacid'];
		$defaults = array(
			'adv'       => array('text' => '幻灯片', 'visible' => 1),
			'search'    => array('text' => '搜索栏', 'visible' => 1),
			'nav'       => array('text' => '导航栏', 'visible' => 1),
			'notice'    => array('text' => '公告栏', 'visible' => 1),
			'cube'      => array('text' => '魔方栏', 'visible' => 1),
			'banner'    => array('text' => '广告栏', 'visible' => 1),
			'recommand' => array('text' => '推荐栏', 'visible' => 1)
			);
		$appsql = '';

		if ($this->iswxapp) {
			$appsql = ' and iswxapp = 1';
		}

		$sorts = ($this->iswxapp ? $_W['shopset']['shop']['indexsort_wxapp'] : $_W['shopset']['shop']['indexsort']);
		$sorts = (isset($sorts) ? $sorts : $defaults);
		$sorts['recommand'] = array('text' => '系统推荐', 'visible' => 1);
		$advs = pdo_fetchall('select id,advname,link,thumb from ' . tablename('ewei_shop_adv') . ' where uniacid=:uniacid' . $appsql . ' and enabled=1 order by displayorder desc', array(':uniacid' => $uniacid));
		$advs = set_medias($advs, 'thumb');
		$navs = pdo_fetchall('select id,navname,url,icon from ' . tablename('ewei_shop_nav') . ' where uniacid=:uniacid' . $appsql . ' and status=1 order by displayorder desc', array(':uniacid' => $uniacid));
		$navs = set_medias($navs, 'icon');
		$cubes = ($this->iswxapp ? $_W['shopset']['shop']['cubes_wxapp'] : $_W['shopset']['shop']['cubes']);
		$cubes = set_medias($cubes, 'img');
		$banners = pdo_fetchall('select id,bannername,link,thumb from ' . tablename('ewei_shop_banner') . ' where uniacid=:uniacid' . $appsql . ' and enabled=1 order by displayorder desc', array(':uniacid' => $uniacid));
		$banners = set_medias($banners, 'thumb');
		$bannerswipe = ($this->iswxapp ? intval($_W['shopset']['shop']['bannerswipe_wxapp']) : intval($_W['shopset']['shop']['bannerswipe']));
		$_W['shopset']['shop']['indexrecommands'] = $this->iswxapp ? $_W['shopset']['shop']['indexrecommands_wxapp'] : $_W['shopset']['shop']['indexrecommands'];

		if (!empty($_W['shopset']['shop']['indexrecommands'])) {
			$goodids = implode(',', $_W['shopset']['shop']['indexrecommands']);

			if (!empty($goodids)) {
				$indexrecommands = pdo_fetchall('select id, title, thumb, marketprice,ispresell,presellprice, productprice, minprice, total from ' . tablename('ewei_shop_goods') . ' where id in( ' . $goodids . ' ) and uniacid=:uniacid and status=1 order by instr(\'' . $goodids . '\',id),displayorder desc', array(':uniacid' => $uniacid));
				$indexrecommands = set_medias($indexrecommands, 'thumb');

				foreach ($indexrecommands as $key => $value) {
					$indexrecommands[$key]['marketprice'] = (double) $indexrecommands[$key]['marketprice'];
					$indexrecommands[$key]['minprice'] = (double) $indexrecommands[$key]['minprice'];
					$indexrecommands[$key]['presellprice'] = (double) $indexrecommands[$key]['presellprice'];
					$indexrecommands[$key]['productprice'] = (double) $indexrecommands[$key]['productprice'];

					if (0 < $value['ispresell']) {
						$indexrecommands[$key]['minprice'] = $value['presellprice'];
					}
				}
			}
		}

		$goodsstyle = ($this->iswxapp ? $_W['shopset']['shop']['goodsstyle_wxapp'] : $_W['shopset']['shop']['goodsstyle']);
		$notices = pdo_fetchall('select id, title, link, thumb from ' . tablename('ewei_shop_notice') . ' where uniacid=:uniacid' . $appsql . ' and status=1 order by displayorder desc limit 5', array(':uniacid' => $uniacid));
		$notices = set_medias($notices, 'thumb');
		$seckillinfo = plugin_run('seckill::getTaskSeckillInfo');
		$copyright = m('common')->getCopyright();
		$newsorts = array();

		foreach ($sorts as $key => $old) {
			$old['type'] = $key;

			if ($key == 'adv') {
				$old['data'] = !empty($advs) ? $advs : array();
			}
			else if ($key == 'nav') {
				$old['data'] = !empty($navs) ? $navs : array();
			}
			else if ($key == 'cube') {
				$old['data'] = !empty($cubes) ? $cubes : array();
			}
			else if ($key == 'banner') {
				$old['data'] = !empty($banners) ? $banners : array();
				$old['bannerswipe'] = !empty($bannerswipe) ? $bannerswipe : array();
			}
			else if ($key == 'notice') {
				$old['data'] = !empty($notices) ? $notices : array();
			}
			else if ($key == 'seckillinfo') {
				$old['data'] = !empty($seckillinfo) ? $seckillinfo : array();
			}
			else {
				if ($key == 'recommand') {
					$old['data'] = !empty($indexrecommands) ? $indexrecommands : array();
				}
			}

			$newsorts[] = $old;
			if (($key == 'notice') && !isset($sorts['seckill'])) {
				$newsorts[] = array('text' => '秒杀栏', 'visible' => 0);
			}
		}

		app_json(array('uniacid' => $uniacid, 'sorts' => $newsorts, 'goodsstyle' => $goodsstyle, 'copyright' => !empty($copyright) && !empty($copyright['copyright']) ? $copyright['copyright'] : ''));
	}

	public function get_recommand()
	{
		global $_W;
		global $_GPC;
		$args = array('page' => $_GPC['page'], 'pagesize' => 10, 'isrecommand' => 1, 'order' => 'displayorder desc,createtime desc', 'by' => '');
		$recommand = m('goods')->getList($args);

		if (!empty($recommand['list'])) {
			foreach ($recommand['list'] as &$item) {
				$item['marketprice'] = (double) $item['marketprice'];
				$item['minprice'] = (double) $item['minprice'];
				$item['presellprice'] = (double) $item['presellprice'];
				$item['productprice'] = (double) $item['productprice'];
			}

			unset($item);
		}

		app_json(array('list' => $recommand['list'], 'pagesize' => $args['pagesize'], 'total' => $recommand['total'], 'page' => intval($_GPC['page'])));
	}

	/**
     * 检测是否关闭
     */
	public function check_close()
	{
		global $_W;
		$close = (isset($_W['shopset']['close']) ? $_W['shopset']['close'] : array('flag' => 0, 'url' => '', 'detail' => ''));
		$close['detail'] = base64_encode($close['detail']);
		app_json(array('close' => $close));
	}

	/**
     * 获取分类
     */
	public function get_category()
	{
		global $_W;
		global $_GPC;
		$refresh = intval($_GPC['refresh']);
		$category_set = $_W['shopset']['category'];
		$category_set['advimg'] = tomedia($category_set['advimg']);
		$level = intval($category_set['level']);
		$category = m('shop')->getCategory();
		$recommands = array();

		foreach ($category['children'] as $k => $v) {
			foreach ($v as $r) {
				if ($r['isrecommand'] == 1) {
					$r['thumb'] = tomedia($r['thumb']);
					$rec = array(
						'id'     => $r['id'],
						'name'   => $r['name'],
						'thumb'  => $r['thumb'],
						'advurl' => $r['advurl'],
						'advimg' => $r['advimg'],
						'child'  => array(),
						'level'  => $r['level']
						);

					if (isset($category['children'][$r['id']])) {
						foreach ($category['children'][$r['id']] as $c) {
							$c['thumb'] = tomedia($c['thumb']);
							$child = array(
								'id'     => $c['id'],
								'name'   => $c['name'],
								'thumb'  => $c['thumb'],
								'advurl' => $c['advurl'],
								'advimg' => $c['advimg'],
								'child'  => array()
								);
							$rec['child'][] = $child;
						}
					}

					$recommands[] = $rec;
				}
			}
		}

		$allcategory = array();

		foreach ($category['parent'] as $p) {
			$p['thumb'] = tomedia($p['thumb']);
			$p['advimg'] = tomedia($p['advimg']);
			$parent = array(
				'id'     => $p['id'],
				'name'   => $p['name'],
				'thumb'  => $p['thumb'],
				'advurl' => $p['advurl'],
				'advimg' => $p['advimg'],
				'child'  => array()
				);

			if (is_array($category['children'][$p['id']])) {
				foreach ($category['children'][$p['id']] as $c) {
					if (!empty($c['thumb'])) {
						$c['thumb'] = tomedia($c['thumb']);
					}

					if (!empty($c['thumb'])) {
						$c['advimg'] = tomedia($c['advimg']);
					}

					if (!empty($c['id'])) {
						$child = array(
							'id'     => $c['id'],
							'name'   => $c['name'],
							'thumb'  => $c['thumb'],
							'advurl' => $c['advurl'],
							'advimg' => $c['advimg'],
							'child'  => array(),
							'level'  => $c['level']
							);
					}

					if (is_array($category['children'][$c['id']])) {
						foreach ($category['children'][$c['id']] as $t) {
							if (!empty($t['thumb'])) {
								$t['thumb'] = tomedia($t['thumb']);
							}

							if (!empty($t['id'])) {
								$child['child'][] = array('id' => $t['id'], 'name' => $t['name'], 'thumb' => $t['thumb'], 'advurl' => $t['advurl'], 'advimg' => $t['advimg']);
							}
						}
					}

					$parent['child'][] = $child;
				}
			}

			$allcategory[] = $parent;
		}

		app_json(array('set' => $category_set, 'recommands' => $recommands, 'category' => $allcategory));
	}

	/**
     * 获取设置
     */
	public function get_set()
	{
		global $_W;
		global $_GPC;
		$sets = array();
		$global_set = m('cache')->getArray('globalset', 'global');

		if (empty($global_set)) {
			$global_set = m('common')->setGlobalSet();
		}

		empty($global_set['trade']['credittext']) && $global_set['trade']['credittext'] = '积分';
		empty($global_set['trade']['moneytext']) && $global_set['trade']['moneytext'] = '余额';
		$merch_plugin = p('merch');
		$merch_data = m('common')->getPluginset('merch');
		$openmerch = $merch_plugin && $merch_data['is_openmerch'];
		$sets = array(
			'shop'               => array('name' => $global_set['shop']['name'], 'logo' => tomedia($global_set['shop']['logo']), 'description' => $global_set['shop']['description'], 'img' => tomedia($global_set['shop']['img'])),
			'share'              => array('title' => empty($global_set['share']['title']) ? $global_set['shop']['name'] : $global_set['share']['title'], 'img' => empty($global_set['share']['icon']) ? tomedia($global_set['shop']['logo']) : tomedia($global_set['share']['icon']), 'desc' => empty($global_set['share']['desc']) ? $global_set['shop']['description'] : $global_set['share']['desc'], 'link' => empty($global_set['share']['url']) ? mobileUrl('', array('appfrom' => 1), true) : $global_set['share']['url']),
			'trade'              => array('closerecharge' => intval($global_set['trade']['closerecharge']), 'minimumcharge' => floatval($global_set['trade']['minimumcharge']), 'withdraw' => intval($global_set['trade']['withdraw']), 'withdrawmoney' => floatval($global_set['trade']['withdrawmoney']), 'closecomment' => intval($global_set['trade']['withdraw']), 'closecommentshow' => intval($global_set['trade']['closecommentshow'])),
			'payset'             => array('weixin' => intval($global_set['pay']['weixin']), 'alipay' => intval($global_set['pay']['alipay']), 'credit' => intval($global_set['pay']['credit']), 'cash' => intval($global_set['pay']['cash'])),
			'contact'            => array('phone' => isset($global_set['contact']['phone']) ? $global_set['contact']['phone'] : '', 'province' => isset($global_set['contact']['phone']) ? $global_set['contact']['province'] : '', 'city' => isset($global_set['contact']['phone']) ? $global_set['contact']['city'] : '', 'address' => isset($global_set['contact']['phone']) ? $global_set['contact']['address'] : ''),
			'menu'               => $this->model->diyMenu('shop'),
			'cancelorderreasons' => array('不取消了', '我不想买了', '信息填写错误，重新拍', '同城见面交易', '其他原因'),
			'openmerch'          => $openmerch,
			'texts'              => array('credittext' => $global_set['trade']['credittext'], 'moneytext' => $global_set['trade']['moneytext'])
			);
		app_json(array('sets' => $sets));
	}

	public function get_areas()
	{
		$areas = m('common')->getAreas();
		app_json(array('areas' => $areas));
	}
}

?>
