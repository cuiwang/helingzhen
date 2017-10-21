<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
}

class Selecturl_EweiShopV2Page extends WebPage
{
	protected $full = false;
	protected $platform = false;

	public function __construct()
	{
		global $_W;
		global $_GPC;
		$this->full = intval($_GPC['full']);
		$this->platform = trim($_GPC['platform']);
	}

	public function main()
	{
		global $_W;
		global $_GPC;
		$syscate = m('common')->getSysset('category');

		if (0 < $syscate['level']) {
			$categorys = pdo_fetchall('SELECT id,name,parentid FROM ' . tablename('ewei_shop_category') . ' WHERE enabled=:enabled and uniacid= :uniacid  ', array(':uniacid' => $_W['uniacid'], ':enabled' => '1'));
		}

		if (p('diypage')) {
			$diypage = p('diypage')->getPageList();

			if (!empty($diypage)) {
				$allpagetype = p('diypage')->getPageType();
			}
		}

		if (p('quick')) {
			$quickList = p('quick')->getPageList();
		}

		$platform = $this->platform;
		$full = $this->full;
		$allUrls = array(
			array(
				'name' => '商城页面',
				'list' => array(
					array('name' => '商城首页', 'url' => mobileUrl(NULL, NULL, $full), 'url_wxapp' => '/pages/index/index'),
					array('name' => '分类导航', 'url' => mobileUrl('shop/category', NULL, $full), 'url_wxapp' => '/pages/shop/caregory/index'),
					array('name' => '全部商品', 'url' => mobileUrl('goods', NULL, $full), 'url_wxapp' => '/pages/goods/index/index'),
					array('name' => '公告页面', 'url' => mobileUrl('shop/notice', NULL, $full), 'url_wxapp' => '/pages/shop/notice/index/index'),
					array('name' => '购物车', 'url' => mobileUrl('member/cart', NULL, $full), 'url_wxapp' => '/pages/member/index/index')
					)
				),
			array(
				'name' => '商品属性',
				'list' => array(
					array('name' => '推荐商品', 'url' => mobileUrl('goods', array('isrecommand' => 1), $full), 'url_wxapp' => '/pages/goods/index/index?isrecommand=1'),
					array('name' => '新品上市', 'url' => mobileUrl('goods', array('isnew' => 1), $full), 'url_wxapp' => '/pages/goods/index/index?isnew=1'),
					array('name' => '热卖商品', 'url' => mobileUrl('goods', array('ishot' => 1), $full), 'url_wxapp' => '/pages/goods/index/index?ishot=1'),
					array('name' => '促销商品', 'url' => mobileUrl('goods', array('isdiscount' => 1), $full), 'url_wxapp' => '/pages/goods/index/index?isdiscount=1'),
					array('name' => '卖家包邮', 'url' => mobileUrl('goods', array('issendfree' => 1), $full), 'url_wxapp' => '/pages/goods/index/index?issendfree=1'),
					array('name' => '限时抢购', 'url' => mobileUrl('goods', array('istime' => 1), $full), 'url_wxapp' => '/pages/goods/index/index?istime=1')
					)
				),
			array(
				'name' => '会员中心',
				'list' => array(
					0  => array('name' => '会员中心', 'url' => mobileUrl('member', NULL, $full), 'url_wxapp' => '/pages/member/index/index'),
					1  => array('name' => '我的订单(全部)', 'url' => mobileUrl('order', NULL, $full), 'url_wxapp' => '/pages/order/index'),
					2  => array('name' => '待付款订单', 'url' => mobileUrl('order', array('status' => 0), $full), 'url_wxapp' => '/pages/order/index?status=0'),
					3  => array('name' => '待发货订单', 'url' => mobileUrl('order', array('status' => 1), $full), 'url_wxapp' => '/pages/order/index?status=1'),
					4  => array('name' => '待收货订单', 'url' => mobileUrl('order', array('status' => 2), $full), 'url_wxapp' => '/pages/order/index?status=2'),
					5  => array('name' => '退换货订单', 'url' => mobileUrl('order', array('status' => 4), $full), 'url_wxapp' => '/pages/order/index?status=4'),
					6  => array('name' => '已完成订单', 'url' => mobileUrl('order', array('status' => 3), $full), 'url_wxapp' => '/pages/order/index?status=3'),
					7  => array('name' => '我的收藏', 'url' => mobileUrl('member/favorite', array(), $full), 'url_wxapp' => '/pages/member/favorite/index'),
					8  => array('name' => '我的足迹', 'url' => mobileUrl('member/history', array(), $full), 'url_wxapp' => '/pages/member/history/index'),
					9  => array('name' => '会员充值', 'url' => mobileUrl('member/recharge', array(), $full), 'url_wxapp' => '/pages/member/recharge/index'),
					10 => array('name' => '余额明细', 'url' => mobileUrl('member/log', array(), $full), 'url_wxapp' => '/pages/member/log/index'),
					11 => array('name' => '余额提现', 'url' => mobileUrl('member/withdraw', array(), $full), 'url_wxapp' => '/pages/member/withdraw/index'),
					12 => array('name' => '我的资料', 'url' => mobileUrl('member/info', array(), $full), 'url_wxapp' => '/pages/member/info/index'),
					13 => array('name' => '积分排行', 'url' => mobileUrl('member/rank', array(), $full), 'url_wxapp' => ''),
					14 => array('name' => '消费排行', 'url' => mobileUrl('member/rank/order_rank', array(), $full), 'url_wxapp' => ''),
					16 => array('name' => '收货地址管理', 'url' => mobileUrl('member/address', array(), $full), 'url_wxapp' => '/pages/member/address/index'),
					18 => array('name' => '我的全返', 'url' => mobileUrl('member/fullback', array(), $full), 'url_wxapp' => ''),
					19 => array('name' => '记次时商品', 'url' => mobileUrl('verifygoods', array(), $full), 'url_wxapp' => '')
					)
				)
			);

		if ($platform) {
			unset($allUrls[0]['list'][0]);
			unset($allUrls[0]['list'][1]);
			unset($allUrls[0]['list'][4]);
			unset($allUrls[2]['list'][0]);
			unset($allUrls[2]['list'][13]);
			unset($allUrls[2]['list'][14]);
			unset($allUrls[2]['list'][15]);
			unset($allUrls[2]['list'][18]);
		}

		if (p('commission') && !$platform) {
			$allUrls[] = array(
	'name' => m('plugin')->getName('commission'),
	'list' => array(
		array('name' => '分销中心', 'url' => mobileUrl('commission', NULL, $full), 'url_wxapp' => ''),
		array('name' => '成为分销商', 'url' => mobileUrl('commission/register', NULL, $full), 'url_wxapp' => ''),
		array('name' => '我的小店', 'url' => mobileUrl('commission/myshop', NULL, $full), 'url_wxapp' => ''),
		array('name' => '分销佣金/佣金提现', 'url' => mobileUrl('commission/withdraw', NULL, $full), 'url_wxapp' => ''),
		array('name' => '分销订单', 'url' => mobileUrl('commission/order', NULL, $full), 'url_wxapp' => ''),
		array('name' => '我的下线', 'url' => mobileUrl('commission/down', NULL, $full), 'url_wxapp' => ''),
		array('name' => '提现明细', 'url' => mobileUrl('commission/log', NULL, $full), 'url_wxapp' => ''),
		array('name' => '推广二维码', 'url' => mobileUrl('commission/qrcode', NULL, $full), 'url_wxapp' => ''),
		array('name' => '小店设置', 'url' => mobileUrl('commission/myshop/set', NULL, $full), 'url_wxapp' => ''),
		array('name' => '佣金排名', 'url' => mobileUrl('commission/rank', NULL, $full), 'url_wxapp' => ''),
		array('name' => '自选商品', 'url' => mobileUrl('commission/myshop/select', NULL, $full), 'url_wxapp' => '')
		)
	);
		}

		if (p('article') && !$platform) {
			$allUrls[] = array(
	'name' => m('plugin')->getName('article'),
	'list' => array(
		array('name' => '文章列表页面', 'url' => mobileUrl('article/list', NULL, $full), 'url_wxapp' => '')
		)
	);
		}

		if (!$platform) {
			$allUrls[] = array(
	'name' => '超级券',
	'list' => array(
		array('name' => '领取优惠券', 'url' => mobileUrl('sale/coupon', NULL, $full), 'url_wxapp' => ''),
		array('name' => '我的优惠券', 'url' => mobileUrl('sale/coupon/my', NULL, $full), 'url_wxapp' => '')
		)
	);
		}

		if (p('groups') && !$platform) {
			$allUrls[] = array(
	'name' => m('plugin')->getName('groups'),
	'list' => array(
		array('name' => '拼团首页', 'url' => mobileUrl('groups', NULL, $full), 'url_wxapp' => ''),
		array('name' => '活动列表', 'url' => mobileUrl('groups/category', NULL, $full), 'url_wxapp' => ''),
		array('name' => '我的订单', 'url' => mobileUrl('groups/orders', NULL, $full), 'url_wxapp' => ''),
		array('name' => '我的团', 'url' => mobileUrl('groups/team', NULL, $full), 'url_wxapp' => '')
		)
	);
		}

		if (p('mr') && !$platform) {
			$allUrls[] = array(
	'name' => m('plugin')->getName('mr'),
	'list' => array(
		array('name' => '充值页面', 'url' => mobileUrl('mr', NULL, $full), 'url_wxapp' => ''),
		array('name' => '充值记录', 'url' => mobileUrl('mr/order', NULL, $full), 'url_wxapp' => '')
		)
	);
		}

		if (p('sns') && !$platform) {
			$allUrls[] = array(
	'name' => m('plugin')->getName('sns'),
	'list' => array(
		array('name' => '社区首页', 'url' => mobileUrl('sns', NULL, $full), 'url_wxapp' => ''),
		array('name' => '全部板块', 'url' => mobileUrl('sns/board/lists', NULL, $full), 'url_wxapp' => ''),
		array('name' => '我的社区', 'url' => mobileUrl('sns/user', NULL, $full), 'url_wxapp' => '')
		)
	);
		}

		if (p('sign') && !$platform) {
			$allUrls[] = array(
	'name' => m('plugin')->getName('sign'),
	'list' => array(
		array('name' => '签到首页', 'url' => mobileUrl('sign', NULL, $full), 'url_wxapp' => ''),
		array('name' => '签到排行', 'url' => mobileUrl('sign/rank', NULL, $full), 'url_wxapp' => '')
		)
	);
		}

		if (p('qa') && !$platform) {
			$allUrls[] = array(
	'name' => m('plugin')->getName('qa'),
	'list' => array(
		array('name' => '帮助首页', 'url' => mobileUrl('qa', NULL, $full), 'url_wxapp' => ''),
		array('name' => '全部分类', 'url' => mobileUrl('qa/category', NULL, $full), 'url_wxapp' => ''),
		array('name' => '全部问题', 'url' => mobileUrl('qa/question', NULL, $full), 'url_wxapp' => '')
		)
	);
		}

		if (p('bargain') && !$platform) {
			$allUrls[] = array(
	'name' => m('plugin')->getName('bargain'),
	'list' => array(
		array('name' => '砍价首页', 'url' => mobileUrl('bargain', NULL, $full), 'url_wxapp' => '')
		)
	);
		}

		if (p('task') && !$platform) {
			$allUrls[] = array(
	'name' => m('plugin')->getName('task'),
	'list' => array(
		array('name' => '首页', 'url' => mobileUrl('task', NULL, $full), 'url_wxapp' => '')
		)
	);
		}

		if (p('creditshop') && !$platform) {
			$allUrls[] = array(
	'name' => m('plugin')->getName('creditshop'),
	'list' => array(
		array('name' => '商城首页', 'url' => mobileUrl('creditshop', NULL, $full), 'url_wxapp' => ''),
		array('name' => '全部商品', 'url' => mobileUrl('creditshop/lists', NULL, $full), 'url_wxapp' => ''),
		array('name' => '我的', 'url' => mobileUrl('creditshop/log', NULL, $full), 'url_wxapp' => ''),
		array('name' => '参与记录', 'url' => mobileUrl('creditshop/creditlog', NULL, $full), 'url_wxapp' => '')
		)
	);
		}

		if (p('seckill') && !$platform) {
			$allUrls[] = array(
	'name' => m('plugin')->getName('seckill'),
	'list' => array(
		array('name' => '秒杀首页', 'url' => mobileUrl('seckill', NULL, $full), 'url_wxapp' => '')
		)
	);
		}

		if (p('newstore') && !$platform) {
			$allUrls[] = array(
	'name' => m('plugin')->getName('newstore'),
	'list' => array(
		array('name' => '门店列表', 'url' => mobileUrl('newstore/stores', NULL, $full), 'url_wxapp' => '')
		)
	);
		}

		include $this->template();
	}

	public function query()
	{
		global $_W;
		global $_GPC;
		$type = trim($_GPC['type']);
		$kw = trim($_GPC['kw']);
		$full = intval($_GPC['full']);
		$platform = trim($_GPC['platform']);
		if (!empty($kw) && !empty($type)) {
			if ($type == 'good') {
				$list = pdo_fetchall('SELECT id,title,productprice,marketprice,thumb,sales,unit,minprice FROM ' . tablename('ewei_shop_goods') . ' WHERE uniacid= :uniacid and status=:status and deleted=0 AND title LIKE :title ', array(':title' => '%' . $kw . '%', ':uniacid' => $_W['uniacid'], ':status' => '1'));
				$list = set_medias($list, 'thumb');
			}
			else if ($type == 'article') {
				$list = pdo_fetchall('select id,article_title from ' . tablename('ewei_shop_article') . ' where article_title LIKE :title and article_state=1 and uniacid=:uniacid ', array(':uniacid' => $_W['uniacid'], ':title' => '%' . $kw . '%'));
			}
			else if ($type == 'coupon') {
				$list = pdo_fetchall('select id,couponname,coupontype from ' . tablename('ewei_shop_coupon') . ' where couponname LIKE :title and uniacid=:uniacid ', array(':uniacid' => $_W['uniacid'], ':title' => '%' . $kw . '%'));
			}
			else if ($type == 'groups') {
				$list = pdo_fetchall('select id,title from ' . tablename('ewei_shop_groups_goods') . ' where title LIKE :title and status=1 and deleted=0 and uniacid=:uniacid ', array(':uniacid' => $_W['uniacid'], ':title' => '%' . $kw . '%'));
			}
			else if ($type == 'sns') {
				$list_board = pdo_fetchall('select id,title from ' . tablename('ewei_shop_sns_board') . ' where title LIKE :title and status=1 and enabled=0 and uniacid=:uniacid order by id desc ', array(':uniacid' => $_W['uniacid'], ':title' => '%' . $kw . '%'));
				$list_post = pdo_fetchall('select id,title from ' . tablename('ewei_shop_sns_post') . ' where title LIKE :title and checked=1 and deleted=0 and uniacid=:uniacid order by id desc ', array(':uniacid' => $_W['uniacid'], ':title' => '%' . $kw . '%'));
				$list = array();
				if (!empty($list_board) && is_array($list_board)) {
					foreach ($list_board as &$board) {
						$board['type'] = 0;
						$board['url'] = mobileUrl('sns/board', array('id' => $board['id'], 'page' => 1), $full);
					}

					unset($board);
					$list = array_merge($list, $list_board);
				}

				if (!empty($list_post) && is_array($list_post)) {
					foreach ($list_post as &$post) {
						$post['type'] = 1;
						$post['url'] = mobileUrl('sns/post', array('id' => $post['id']), $full);
					}

					unset($post);
					$list = array_merge($list, $list_post);
				}
			}
			else {
				if ($type == 'creditshop') {
					$list = pdo_fetchall('select id, thumb, title, price, credit, money from ' . tablename('ewei_shop_creditshop_goods') . ' where title LIKE :title and status=1 and deleted=0 and uniacid=:uniacid ', array(':uniacid' => $_W['uniacid'], ':title' => '%' . $kw . '%'));
				}
			}
		}

		include $this->template('util/selecturl_tpl');
	}
}

?>
