<?php
if (!(defined('IN_IA'))) {
	exit('Access Denied');
}

class MerchModel extends PluginModel
{
	static 	public $allPerms = array();
	static 	public $getLogTypes = array();
	static 	public $formatPerms = array();

	protected function build($condition, $params, $data)
	{
		foreach ($data as $key => $value ) {
			if (($key == 'column') || ($key == 'field')) {
				continue;
			}


			if (stripos($key, 'in') === 0) {
				$key = str_ireplace('in', '', $key);

				if (is_array($value)) {
					foreach ($value as &$val ) {
						$val = (int) $val;
					}

					unset($val);
					$key = str_ireplace('in', '', $key);
					$condition .= ' AND `' . $key . '` in(' . implode(',', $value) . ')';
				}


				continue;
			}


			if (stripos($key, 'orlike') === 0) {
				$key = str_ireplace('orlike', '', $key);

				if (is_array($value)) {
					$condition .= ' OR (';
					$i = 0;

					foreach ($value as $k => $val ) {
						if ($i == 0) {
							$condition .= '`' . $k . '`=:' . $k;
							$params[':' . $k] = $val;
						}
						 else {
							if ((stripos($val[0], 'and') !== false) || (stripos($val[0], 'or') !== false)) {
								$condition .= ' ' . strtoupper($val[0]) . ' `' . $k . '` like :' . $k;
								$params[':' . $k] = $val[1];
							}
							 else {
								$condition .= ' AND `' . $k . '` like :' . $k;
								$params[':' . $k] = '%' . $val . '%';
							}
						}

						++$i;
					}

					$condition .= ')';
					continue;
				}


				$condition .= ' OR `' . $key . '` like :' . $key;
				$params[':' . $key] = '%' . $value . '%';
				continue;
			}


			if (stripos($key, 'like') === 0) {
				$key = str_ireplace('like', '', $key);

				if (is_array($value)) {
					$condition .= ' AND (';
					$i = 0;

					foreach ($value as $k => $val ) {
						if ($i == 0) {
							$condition .= '`' . $k . '` like :' . $k;
							$params[':' . $k] = '%' . $val . '%';
						}
						 else {
							if ((stripos($val[0], 'and') !== false) || (stripos($val[0], 'or') !== false)) {
								$condition .= ' ' . strtoupper($val[0]) . ' `' . $k . '` like :' . $k;
								$params[':' . $k] = $val[1];
							}
							 else {
								$condition .= ' AND `' . $k . '` like :' . $k;
								$params[':' . $k] = '%' . $val . '%';
							}
						}

						++$i;
					}

					$condition .= ')';
					continue;
				}


				$condition .= ' AND `' . $key . '` like :' . $key;
				$params[':' . $key] = '%' . $value . '%';
				continue;
			}


			if (stripos($key, 'limit') === 0) {
				if (is_array($value)) {
					if (isset($value[1])) {
						$condition .= ' LIMIT ' . $value[0] . ',' . $value[1];
					}
					 else {
						$condition .= ' LIMIT ' . $value[0];
					}

					continue;
				}


				$condition .= ' LIMIT ' . $value;
				continue;
			}


			if (stripos($key, 'orderby') === 0) {
				if (is_array($value)) {
					$condition .= ' ORDER BY';
					$i = 0;

					foreach ($value as $k => $val ) {
						if ($i == 0) {
							$condition .= ' ' . $k . ' ' . $val;
						}
						 else {
							$condition .= ',' . $k . ' ' . $val;
						}

						++$i;
					}

					continue;
				}


				$condition .= ' LIMIT ' . $value;
				continue;
			}


			if (stripos($key, 'or') === 0) {
				$key = str_ireplace('or', '', $key);

				if (is_array($value)) {
					$condition .= ' OR (';
					$i = 0;

					foreach ($value as $k => $val ) {
						if ($i == 0) {
							$condition .= '`' . $k . '`=:' . $k;
							$params[':' . $k] = $val;
						}
						 else {
							if ((stripos($val[0], 'and') !== false) || (stripos($val[0], 'or') !== false)) {
								$condition .= ' ' . strtoupper($val[0]) . ' `' . $k . '`=:' . $k;
								$params[':' . $k] = $val[1];
							}
							 else {
								$condition .= ' AND `' . $k . '`=:' . $k;
								$params[':' . $k] = $val;
							}
						}

						++$i;
					}

					$condition .= ')';
					continue;
				}


				$condition .= ' OR `' . $key . '`=:' . $key;
				$params[':' . $key] = $value;
				continue;
			}


			if (stripos($key, 'and') === 0) {
				$key = str_ireplace('and', '', $key);

				if (is_array($value)) {
					$condition .= ' AND (';
					$i = 0;

					foreach ($value as $k => $val ) {
						if ($i == 0) {
							$condition .= '`' . $k . '`=:' . $k;
							$params[':' . $k] = $val;
						}
						 else {
							if ((stripos($val[0], 'and') !== false) || (stripos($val[0], 'or') !== false)) {
								$condition .= ' ' . strtoupper($val[0]) . ' `' . $k . '`=:' . $k;
								$params[':' . $k] = $val[1];
							}
							 else {
								$condition .= ' AND `' . $k . '`=:' . $k;
								$params[':' . $k] = $val;
							}
						}

						++$i;
					}

					$condition .= ')';
					continue;
				}


				$condition .= ' OR `' . $key . '`=:' . $key;
				$params[':' . $key] = $value;
				continue;
			}


			$condition .= ' AND `' . $key . '`=:' . $key;
			$params[':' . $key] = $value;
		}

		if (isset($data['field'])) {
			if (is_array($data['field'])) {
				$field = '`' . implode('`,`', $data['field']) . '``';
			}
			 else {
				$field = explode(',', $data['field']);

				foreach ($field as &$value ) {
					$temp = explode(' ', $value);

					if (strpos($value, '(') === false) {
						$value = str_replace($temp[0], '`' . $temp[0] . '`', $value);
					}

				}

				unset($value);
				$field = implode(',', $field);
			}
		}


		return array('condition' => $condition, 'params' => $params, 'column' => (isset($data['column']) ? $data['column'] : ''), 'field' => (isset($field) ? $field : '*'));
	}

	public function getGroups()
	{
		global $_W;
		return pdo_fetchall('select id,groupname from ' . tablename('ewei_shop_merch_group') . ' where uniacid=:uniacid and status=1 order by isdefault desc , id asc', array(':uniacid' => $_W['uniacid']), 'id');
	}

	public function getCategory($data = array())
	{
		global $_W;
		$condition = ' WHERE `uniacid` = :uniacid';
		$params = array(':uniacid' => $_W['uniacid']);
		$res = $this->build($condition, $params, $data);
		return pdo_fetchall('select ' . $res['field'] . ' from ' . tablename('ewei_shop_merch_category') . $res['condition'], $res['params'], $res['column']);
	}

	public function getCategorySwipe($data = array())
	{
		global $_W;
		$condition = ' WHERE `uniacid` = :uniacid';
		$params = array(':uniacid' => $_W['uniacid']);
		$res = $this->build($condition, $params, $data);
		return pdo_fetchall('select ' . $res['field'] . ' from ' . tablename('ewei_shop_merch_category_swipe') . $res['condition'], $res['params'], $res['column']);
	}

	public function getMerch($data = array())
	{
		global $_W;
		$condition = ' WHERE `uniacid` = :uniacid';
		$params = array(':uniacid' => $_W['uniacid']);
		$res = $this->build($condition, $params, $data);
		return pdo_fetchall('select ' . $res['field'] . ' from ' . tablename('ewei_shop_merch_user') . $res['condition'], $res['params'], $res['column']);
	}

	public function updateSet($values = array(), $merchid = 0)
	{
		global $_W;
		global $_GPC;
		$merchid = ((empty($merchid) ? $_W['merchid'] : $merchid));
		$keys = 'merch_sets_' . $merchid;
		$sets = $this->getSet('', $merchid, true);

		foreach ($values as $key => $value ) {
			foreach ($value as $k => $v ) {
				$sets[$key][$k] = $v;
			}
		}

		pdo_update('ewei_shop_merch_user', array('sets' => iserializer($sets)), array('id' => $merchid));
		m('cache')->set($keys, $sets);
	}

	public function refreshSet($merchid = 0)
	{
		global $_W;
		$merchid = ((empty($merchid) ? $_W['merchid'] : $merchid));
		$key = 'merch_sets_' . $merchid;
		$merch_set = pdo_fetch('select sets from ' . tablename('ewei_shop_merch_user') . ' where uniacid=:uniacid and id=:id limit 1 ', array(':uniacid' => $_W['uniacid'], ':id' => $merchid));
		$allset = iunserializer($merch_set['sets']);

		if (!(is_array($allset))) {
			$allset = array();
		}


		m('cache')->set($key, $allset);
		return $allset;
	}

	public function getSet($name = '', $merchid = 0, $refresh = false)
	{
		global $_W;
		global $_GPC;
		$merchid = ((empty($merchid) ? $_W['merchid'] : intval($merchid)));
		$key = 'merch_sets_' . $merchid;

		if ($refresh) {
			return $this->refreshSet($merchid);
		}


		$allset = m('cache')->getArray($key);

		if (!(empty($name)) && empty($allset[$name])) {
			$allset = $this->refreshSet($merchid);
		}
		return ($name ? $allset[$name] : $allset);
	}

	public function getGoodsTotals()
	{
		global $_W;
		return array('sale' => pdo_fetchcolumn('select count(1) from ' . tablename('ewei_shop_goods') . ' where checked=0 and status=1 and deleted=0 and total>0 and uniacid=:uniacid and merchid=:merchid', array(':uniacid' => $_W['uniacid'], ':merchid' => $_W['merchid'])), 'out' => pdo_fetchcolumn('select count(1) from ' . tablename('ewei_shop_goods') . ' where checked=0 and  status=1 and deleted=0 and total=0 and uniacid=:uniacid and merchid=:merchid', array(':uniacid' => $_W['uniacid'], ':merchid' => $_W['merchid'])), 'check' => pdo_fetchcolumn('select count(1) from ' . tablename('ewei_shop_goods') . ' where checked=1 and deleted=0 and uniacid=:uniacid and merchid=:merchid', array(':uniacid' => $_W['uniacid'], ':merchid' => $_W['merchid'])), 'stock' => pdo_fetchcolumn('select count(1) from ' . tablename('ewei_shop_goods') . ' where checked=0 and status=0 and deleted=0 and uniacid=:uniacid  and merchid=:merchid', array(':uniacid' => $_W['uniacid'], ':merchid' => $_W['merchid'])), 'cycle' => pdo_fetchcolumn('select count(1) from ' . tablename('ewei_shop_goods') . ' where deleted=1 and uniacid=:uniacid and merchid=:merchid', array(':uniacid' => $_W['uniacid'], ':merchid' => $_W['merchid'])));
	}

	public function getOrderTotals()
	{
		global $_W;
		$paras = array(':uniacid' => $_W['uniacid'], ':merchid' => $_W['merchid']);
		$condition = 'and isparent=0 and ismr=0';
		$totals['all'] = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename('ewei_shop_order') . '' . ' WHERE uniacid = :uniacid ' . $condition . ' and merchid = :merchid and deleted=0', $paras);
		$totals['status_1'] = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename('ewei_shop_order') . '' . ' WHERE uniacid = :uniacid ' . $condition . ' and merchid = :merchid and status=-1 and refundtime=0 and deleted=0', $paras);
		$totals['status0'] = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename('ewei_shop_order') . '' . ' WHERE uniacid = :uniacid ' . $condition . ' and merchid = :merchid and status=0 and paytype<>3 and deleted=0', $paras);
		$totals['status1'] = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename('ewei_shop_order') . '' . ' WHERE uniacid = :uniacid ' . $condition . ' and merchid = :merchid and ( status=1 or ( status=0 and paytype=3) ) and deleted=0', $paras);
		$totals['status2'] = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename('ewei_shop_order') . '' . ' WHERE uniacid = :uniacid ' . $condition . ' and merchid = :merchid and status=2 and deleted=0', $paras);
		$totals['status3'] = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename('ewei_shop_order') . '' . ' WHERE uniacid = :uniacid ' . $condition . ' and merchid = :merchid and status=3 and deleted=0', $paras);
		$totals['status4'] = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename('ewei_shop_order') . '' . ' WHERE uniacid = :uniacid ' . $condition . ' and merchid = :merchid and refundstate>0 and refundid<>0 and deleted=0', $paras);
		$totals['status5'] = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename('ewei_shop_order') . '' . ' WHERE uniacid = :uniacid ' . $condition . ' and merchid = :merchid and refundtime<>0 and deleted=0', $paras);
		return $totals;
	}

	public function getListUser($list, $return = 'all')
	{
		global $_W;

		if (!(is_array($list))) {
			return $this->getListUserOne($list);
		}


		$merch = array();

		foreach ($list as $value ) {
			$merchid = $value['merchid'];

			if (empty($merchid)) {
				$merchid = 0;
			}


			if (empty($merch[$merchid])) {
				$merch[$merchid] = array();
			}


			array_push($merch[$merchid], $value);
		}

		if (!(empty($merch))) {
			$merch_ids = array_keys($merch);
			$merch_user = pdo_fetchall('select * from ' . tablename('ewei_shop_merch_user') . ' where uniacid=:uniacid and id in(' . implode(',', $merch_ids) . ')', array(':uniacid' => $_W['uniacid']), 'id');
			$all = array('merch' => $merch, 'merch_user' => $merch_user);
			return ($return == 'all' ? $all : $all[$return]);
		}


		return array();
	}

	public function getListUserOne($merchid)
	{
		global $_W;
		$merchid = intval($merchid);

		if ($merchid) {
			$merch_user = pdo_fetch('select * from ' . tablename('ewei_shop_merch_user') . ' where uniacid=:uniacid and id=' . $merchid, array(':uniacid' => $_W['uniacid']));
			return $merch_user;
		}


		return false;
	}

	public function allPerms()
	{
		if (empty(self::$allPerms)) {
			$perms = array('shop' => $this->perm_shop(), 'goods' => $this->perm_goods(), 'order' => $this->perm_order(), 'statistics' => $this->perm_statistics(), 'sale' => $this->perm_sale(), 'creditshop' => $this->perm_creditshop(), 'perm' => $this->perm_perm(), 'apply' => $this->perm_apply(), 'exhelper' => $this->perm_exhelper(), 'diypage' => $this->perm_diypage(), 'quick' => $this->perm_quick());
			self::$allPerms = $perms;
		}


		return self::$allPerms;
	}

	protected function perm_diypage()
	{
		return array(
	'text' => m('plugin')->getName('diypage'),
	'page' => array(
		'sys' => array('text' => '系统页面', 'main' => '查看列表', 'add' => '添加-log', 'edit' => '编辑-log', 'delete' => '删除-log', 'savetemp' => '另存为模板-log'),
		'plu' => array('text' => '应用页面', 'main' => '查看列表', 'add' => '添加-log', 'edit' => '编辑-log', 'delete' => '删除-log', 'savetemp' => '另存为模板-log'),
		'diy' => array('text' => '自定义页面', 'main' => '查看列表', 'add' => '添加-log', 'edit' => '编辑-log', 'delete' => '删除-log', 'savetemp' => '另存为模板-log'),
		'mod' => array('text' => '公用模块', 'main' => '查看列表', 'add' => '添加-log', 'edit' => '编辑-log', 'delete' => '删除-log')
		),
	'menu' => array('text' => '自定义菜单', 'main' => '查看列表', 'add' => '添加-log', 'edit' => '编辑-log', 'delete' => '删除-log'),
	'shop' => array(
		'text'      => '商城页面设置',
		'page'      => array('text' => '页面设置', 'main' => '查看', 'save' => '保存-log'),
		'menu'      => array('text' => '按钮设置', 'main' => '查看', 'save' => '保存-log'),
		'layer'     => array('text' => '悬浮按钮', 'main' => '编辑-log'),
		'followbar' => array('text' => '关注条', 'main' => '编辑-log'),
		'danmu'     => array('text' => '下单提醒', 'main' => '编辑-log'),
		'adv'       => array('text' => '启动广告', 'main' => '查看列表', 'add' => '添加-log', 'edit' => '编辑-log', 'delete' => '删除-log')
		),
	'temp' => array(
		'text'     => '模板管理',
		'main'     => '通过模板创建页面',
		'delete'   => '删除模板',
		'category' => array('text' => '模板分类', 'main' => '查看', 'add' => '添加-log', 'edit' => '编辑-log', 'delete' => '删除-log')
		)
	);
	}

	protected function perm_quick()
	{
		return array(
	'text'  => m('plugin')->getName('quick'),
	'adv'   => array(
		'text'   => '幻灯片管理',
		'main'   => '查看列表',
		'view'   => '查看',
		'add'    => '添加-log',
		'edit'   => '编辑-log',
		'delete' => '删除-log',
		'xxx'    => array('enabled' => 'edit')
		),
	'pages' => array(
		'text'   => '页面管理',
		'main'   => '查看列表',
		'add'    => '添加-log',
		'edit'   => '编辑-log',
		'delete' => '删除-log',
		'xxx'    => array('status' => 'edit')
		)
	);
	}

	protected function perm_creditshop()
	{
		return array(
	'goods'   => array(
		'text'   => '商品',
		'main'   => '查看列表',
		'view'   => '查看详细',
		'add'    => '添加-log',
		'edit'   => '修改-log',
		'delete' => '删除-log',
		'xxx'    => array('property' => 'edit')
		),
	'log'     => array('text' => '订单/记录', 'exchange' => '兑换记录', 'draw' => '抽奖记录', 'order' => '待发货', 'convey' => '待收货', 'finish' => '已完成', 'verifying' => '待核销', 'verifyover' => '已核销', 'verify' => '全部核销', 'detail' => '详情', 'doexchange' => '确认兑换-log', 'export' => '导出明细-log'),
	'comment' => array('text' => '评价管理', 'edit' => '回复评价', 'check' => '审核评价')
	);
	}

	protected function perm_shop()
	{
		return array(
	'text'          => '商城管理',
	'adv'           => array(
		'text'   => '幻灯片',
		'main'   => '查看列表',
		'view'   => '查看内容',
		'add'    => '添加-log',
		'edit'   => '修改-log',
		'delete' => '删除-log',
		'xxx'    => array('displayorder' => 'edit', 'enabled' => 'edit')
		),
	'nav'           => array(
		'text'   => '首页导航图标',
		'main'   => '查看列表',
		'view'   => '查看内容',
		'add'    => '添加-log',
		'edit'   => '修改-log',
		'delete' => '删除-log',
		'xxx'    => array('displayorder' => 'edit', 'status' => 'edit')
		),
	'banner'        => array(
		'text'   => '首页广告',
		'main'   => '查看列表',
		'view'   => '查看内容',
		'add'    => '添加-log',
		'edit'   => '修改-log',
		'delete' => '删除-log',
		'xxx'    => array('displayorder' => 'edit', 'enabled' => 'edit', 'setswipe' => 'edit')
		),
	'cube'          => array('text' => '首页魔方', 'main' => '查看', 'edit' => '修改-log'),
	'recommand'     => array('text' => '首页商品推荐', 'main' => '编辑推荐商品-log', 'setstyle' => '设置商品组样式-log'),
	'sort'          => array('text' => '首页元素排版', 'main' => '修改-log'),
	'dispatch'      => array(
		'text'   => '配送方式',
		'main'   => '查看列表',
		'view'   => '查看内容',
		'add'    => '添加-log',
		'edit'   => '修改-log',
		'delete' => '删除-log',
		'xxx'    => array('displayorder' => 'edit', 'enabled' => 'edit', 'setdefault' => 'edit')
		),
	'notice'        => array(
		'text'   => '公告',
		'main'   => '查看列表',
		'view'   => '查看内容',
		'add'    => '添加-log',
		'edit'   => '修改-log',
		'delete' => '删除-log',
		'xxx'    => array('displayorder' => 'edit', 'status' => 'edit')
		),
	'comment'       => array('text' => '评价', 'main' => '查看列表', 'add' => '添加-log', 'edit' => '编辑-log', 'post' => '回复-log', 'delete' => '删除-log'),
	'refundaddress' => array(
		'text'   => '退货地址',
		'main'   => '查看列表',
		'view'   => '查看内容',
		'add'    => '添加-log',
		'edit'   => '修改-log',
		'delete' => '删除-log',
		'xxx'    => array('setdefault' => 'edit')
		),
	'verify'        => array(
		'text'  => 'O2O核销',
		'saler' => array(
			'text'   => '店员管理',
			'main'   => '查看列表',
			'view'   => '查看内容',
			'add'    => '添加-log',
			'edit'   => '修改-log',
			'delete' => '删除-log',
			'xxx'    => array('status' => 'edit')
			),
		'store' => array(
			'text'   => '门店管理',
			'main'   => '查看列表',
			'view'   => '查看内容',
			'add'    => '添加-log',
			'edit'   => '修改-log',
			'delete' => '删除-log',
			'xxx'    => array('displayorder' => 'edit', 'status' => 'edit')
			),
		'set'   => array('text' => '关键词设置', 'main' => '查看', 'edit' => '编辑-log')
		)
	);
	}

	protected function perm_goods()
	{
		return array(
	'text'     => '商品管理',
	'main'     => '浏览列表',
	'view'     => '查看详情',
	'add'      => '添加-log',
	'edit'     => '修改-log',
	'delete'   => '删除-log',
	'delete1'  => '彻底删除-log',
	'restore'  => '恢复到仓库-log',
	'xxx'      => array('status' => 'edit', 'property' => 'edit', 'change' => 'edit'),
	'category' => array(
		'text'   => '商品分类',
		'add'    => '添加-log',
		'edit'   => '修改-log',
		'delete' => '删除-log',
		'xxx'    => array('enabled' => 'edit')
		),
	'virtual'  => array(
		'text'     => '虚拟卡密',
		'temp'     => array('text' => '卡密模板管理', 'view' => '浏览', 'add' => '添加-log', 'edit' => '修改-log', 'delete' => '删除-log'),
		'category' => array('text' => '卡密分类管理', 'add' => '添加-log', 'edit' => '编辑-log', 'delete' => '删除-log'),
		'data'     => array('text' => '卡密数据', 'add' => '添加-log', 'edit' => '修改-log', 'delete' => '删除-log', 'export' => '导出-log', 'temp' => '下载模板', 'import' => '导入-log')
		)
	);
	}

	protected function perm_sale()
	{
		$array = array(
			'text'   => '营销',
			'coupon' => array(
				'text'     => '优惠券管理',
				'view'     => '浏览',
				'add'      => '添加-log',
				'edit'     => '修改-log',
				'delete'   => '删除-log',
				'send'     => '发放-log',
				'set'      => '修改设置-log',
				'xxx'      => array('displayorder' => 'edit'),
				'category' => array('text' => '优惠券分类', 'main' => '查看', 'edit' => '修改-log'),
				'log'      => array('text' => '优惠券记录', 'main' => '查看', 'export' => '导出记录')
				)
			);
		$sale = array('enough' => '修改满额立减-log', 'enoughfree' => '修改满额包邮-log');
		$array = array_merge($array, $sale);
		return $array;
	}

	protected function perm_statistics()
	{
		return array(
	'text'            => '数据统计',
	'sale'            => array('text' => '销售统计', 'main' => '查看', 'export' => '导出-log'),
	'sale_analysis'   => array('text' => '销售指标', 'main' => '查看'),
	'order'           => array('text' => '订单统计', 'main' => '查看', 'export' => '导出-log'),
	'goods'           => array('text' => '商品销售明细', 'main' => '查看', 'export' => '导出-log'),
	'goods_rank'      => array('text' => '商品销售排行', 'main' => '查看', 'export' => '导出-log'),
	'goods_trans'     => array('text' => '商品销售转化率', 'main' => '查看', 'export' => '导出-log'),
	'member_cost'     => array('text' => '会员消费排行', 'main' => '查看', 'export' => '导出-log'),
	'member_increase' => array('text' => '会员增长趋势', 'main' => '查看')
	);
	}

	protected function perm_order()
	{
		return array(
	'text'      => '订单',
	'detail'    => array('text' => '订单详情', 'edit' => '编辑'),
	'export'    => array(
		'text' => '自定义导出-log',
		'main' => '浏览页面',
		'xxx'  => array('save' => 'main', 'delete' => 'main', 'gettemplate' => 'main', 'reset' => 'main')
		),
	'batchsend' => array(
		'text' => '批量发货',
		'main' => '批量发货-log',
		'xxx'  => array('import' => 'main')
		),
	'list'      => array('text' => '订单管理', 'main' => '浏览全部订单', 'status_1' => '浏览关闭订单', 'status0' => '浏览待付款订单', 'status1' => '浏览已付款订单', 'status2' => '浏览已发货订单', 'status3' => '浏览完成的订单', 'status4' => '浏览退货申请订单', 'status5' => '浏览已退货订单'),
	'op'        => array(
		'text'          => '操作',
		'delete'        => '订单删除-log',
		'pay'           => '确认付款-log',
		'send'          => '发货-log',
		'sendcancel'    => '取消发货-log',
		'finish'        => '确认收货(快递单)-log',
		'verify'        => '确认核销(核销单)-log',
		'fetch'         => '确认取货(自提单)-log',
		'close'         => '关闭订单-log',
		'changeprice'   => '订单改价-log',
		'changeaddress' => '修改收货地址-log',
		'remarksaler'   => '订单备注-log',
		'paycancel'     => '订单取消付款-log',
		'fetchcancel'   => '订单取消取货-log',
		'changeexpress' => '修改快递状态',
		'refund'        => array('text' => '维权', 'main' => '维权信息', 'submit' => '提交维权申请')
		)
	);
	}

	protected function perm_perm()
	{
		return array(
	'text' => '权限系统',
	'log'  => array('text' => '操作日志', 'main' => '查看列表'),
	'role' => array(
		'text'   => '角色管理',
		'main'   => '查看列表',
		'add'    => '添加-log',
		'edit'   => '修改-log',
		'delete' => '删除-log',
		'xxx'    => array('status' => 'edit', 'query' => 'main')
		),
	'user' => array(
		'text'   => '操作员管理',
		'main'   => '查看列表',
		'add'    => '添加-log',
		'edit'   => '修改-log',
		'delete' => '删除-log',
		'xxx'    => array('status' => 'edit')
		)
	);
	}

	protected function perm_apply()
	{
		return array(
	'text'   => '提现',
	'detail' => array('text' => '提现详情', 'export' => '导出提现申请订单详情'),
	'list'   => array('text' => '提现管理', 'post' => '申请提现', 'status1' => '浏览待审核申请', 'status2' => '浏览待结算申请', 'status3' => '浏览已结算申请', 'export' => '导出申请')
	);
	}

	protected function perm_exhelper()
	{
		return array(
	'text'     => '快递助手',
	'print'    => array(
		'single' => array('text' => '单个打印', 'express' => '打印快递单-log', 'invoice' => '打印发货单-log', 'dosend' => '一键发货-log'),
		'batch'  => array('text' => '批量打印', 'express' => '打印快递单-log', 'invoice' => '打印发货单-log', 'dosend' => '一键发货-log')
		),
	'temp'     => array(
		'express' => array(
			'text'   => '快递单模板管理',
			'add'    => '添加-log',
			'edit'   => '修改-log',
			'delete' => '删除-log',
			'xxx'    => array('setdefault' => 'edit')
			),
		'invoice' => array(
			'text'   => '发货单模板管理',
			'add'    => '添加-log',
			'edit'   => '修改-log',
			'delete' => '删除-log',
			'xxx'    => array('setdefault' => 'edit')
			)
		),
	'sender'   => array(
		'text'   => '发货人信息管理',
		'main'   => '查看列表',
		'view'   => '查看',
		'add'    => '添加-log',
		'edit'   => '修改-log',
		'delete' => '删除-log',
		'xxx'    => array('setdefault' => 'edit')
		),
	'short'    => array('text' => '商品简称', 'main' => '查看', 'edit' => '修改-log'),
	'printset' => array('text' => '打印端口设置', 'main' => '查看', 'edit' => '修改-log')
	);
	}

	public function check_edit($permtype = '', $item = array())
	{
		if (empty($permtype)) {
			return false;
		}


		if (!($this->check_perm($permtype))) {
			return false;
		}


		if (empty($item['id'])) {
			$add_perm = $permtype . '.add';

			if (!($this->check($add_perm))) {
				return false;
			}


			return true;
		}


		$edit_perm = $permtype . '.edit';

		if (!($this->check($edit_perm))) {
			return false;
		}


		return true;
	}

	public function check_perm($permtypes = '')
	{
		global $_W;
		$check = true;

		if (empty($permtypes)) {
			return false;
		}


		if (!(strexists($permtypes, '&')) && !(strexists($permtypes, '|'))) {
			$check = $this->check($permtypes);
		}
		 else if (strexists($permtypes, '&')) {
			$pts = explode('&', $permtypes);

			foreach ($pts as $pt ) {
				while (!($check)) {
					$check = $this->check($pt);
					break;
				}
			}
		}
		 else if (strexists($permtypes, '|')) {
			$pts = explode('|', $permtypes);

			foreach ($pts as $pt ) {
				while ($check) {
					$check = $this->check($pt);
					break;
				}
			}
		}


		return $check;
	}

	private function check($permtype = '')
	{
		global $_W;
		global $_GPC;

		if ($_W['merchisfounder'] == 1) {
			return true;
		}


		$uid = $_W['merchuid'];

		if (empty($permtype)) {
			return false;
		}


		$user = pdo_fetch('select u.status as userstatus,r.status as rolestatus,r.perms as roleperms,u.roleid from ' . tablename('ewei_shop_merch_account') . ' u ' . ' left join ' . tablename('ewei_shop_merch_perm_role') . ' r on u.roleid = r.id ' . ' where u.id=:merchuid limit 1 ', array(':merchuid' => $uid));
		if (empty($user) || empty($user['userstatus'])) {
			return false;
		}


		if (!(empty($user['roleid'])) && empty($user['rolestatus'])) {
			return false;
		}


		$perms = explode(',', $user['roleperms']);

		if (empty($perms)) {
			return false;
		}


		$is_xxx = $this->check_xxx($permtype);

		if ($is_xxx) {
			if (!(in_array($is_xxx, $perms))) {
				return false;
			}

		}
		 else if (!(in_array($permtype, $perms))) {
			return false;
		}


		return true;
	}

	/**
     * 查看是不是继承
     * @param $permtype
     * @return bool|string
     */
	public function check_xxx($permtype)
	{
		if ($permtype) {
			$allPerm = $this->allPerms();
			$permarr = explode('.', $permtype);

			if (isset($permarr[3])) {
				$is_xxx = ((isset($allPerm[$permarr[0]][$permarr[1]][$permarr[2]]['xxx'][$permarr[3]]) ? $allPerm[$permarr[0]][$permarr[1]][$permarr[2]]['xxx'][$permarr[3]] : false));
			}
			 else if (isset($permarr[2])) {
				$is_xxx = ((isset($allPerm[$permarr[0]][$permarr[1]]['xxx'][$permarr[2]]) ? $allPerm[$permarr[0]][$permarr[1]]['xxx'][$permarr[2]] : false));
			}
			 else if (isset($permarr[1])) {
				$is_xxx = ((isset($allPerm[$permarr[0]]['xxx'][$permarr[1]]) ? $allPerm[$permarr[0]]['xxx'][$permarr[1]] : false));
			}
			 else {
				$is_xxx = false;
			}

			if ($is_xxx) {
				$permarr = explode('.', $permtype);
				array_pop($permarr);
				$is_xxx = implode('.', $permarr) . '.' . $is_xxx;
			}


			return $is_xxx;
		}


		return false;
	}

	public function getLogName($type = '', $logtypes = NULL)
	{
		if (!($logtypes)) {
			$logtypes = $this->getLogTypes();
		}


		foreach ($logtypes as $t ) {
			while ($t['value'] == $type) {
				return $t['text'];
			}
		}

		return '';
	}

	public function getLogTypes($all = false)
	{
		if (empty(self::$getLogTypes)) {
			$perms = $this->allPerms();
			$array = array();

			foreach ($perms as $key => $value ) {
				if (is_array($value)) {
					foreach ($value as $ke => $val ) {
						if (!(is_array($val))) {
							if ($all) {
								if ($ke == 'text') {
									$text = str_replace('-log', '', $value['text']);
								}
								 else {
									$text = str_replace('-log', '', $value['text'] . '-' . $val);
								}

								$array[] = array('text' => $text, 'value' => str_replace('.text', '', $key . '.' . $ke));
							}
							 else if (strexists($val, '-log')) {
								$text = str_replace('-log', '', $value['text'] . '-' . $val);

								if ($ke == 'text') {
									$text = str_replace('-log', '', $value['text']);
								}


								$array[] = array('text' => $text, 'value' => str_replace('.text', '', $key . '.' . $ke));
							}

						}


						if (is_array($val) && ($ke != 'xxx')) {
							foreach ($val as $k => $v ) {
								if (!(is_array($v))) {
									if ($all) {
										if ($ke == 'text') {
											$text = str_replace('-log', '', $value['text'] . '-' . $val['text']);
										}
										 else {
											$text = str_replace('-log', '', $value['text'] . '-' . $val['text'] . '-' . $v);
										}

										$array[] = array('text' => $text, 'value' => str_replace('.text', '', $key . '.' . $ke . '.' . $k));
									}
									 else if (strexists($v, '-log')) {
										$text = str_replace('-log', '', $value['text'] . '-' . $val['text'] . '-' . $v);

										if ($k == 'text') {
											$text = str_replace('-log', '', $value['text'] . '-' . $val['text']);
										}


										$array[] = array('text' => $text, 'value' => str_replace('.text', '', $key . '.' . $ke . '.' . $k));
									}

								}


								if (is_array($v) && ($k != 'xxx')) {
									foreach ($v as $kk => $vv ) {
										if (!(is_array($vv))) {
											if ($all) {
												if ($ke == 'text') {
													$text = str_replace('-log', '', $value['text'] . '-' . $val['text'] . '-' . $v['text']);
												}
												 else {
													$text = str_replace('-log', '', $value['text'] . '-' . $val['text'] . '-' . $v['text'] . '-' . $vv);
												}

												$array[] = array('text' => $text, 'value' => str_replace('.text', '', $key . '.' . $ke . '.' . $k . '.' . $kk));
											}
											 else if (strexists($vv, '-log')) {
												if (empty($val['text'])) {
													$text = str_replace('-log', '', $value['text'] . '-' . $v['text'] . '-' . $vv);
												}
												 else {
													$text = str_replace('-log', '', $value['text'] . '-' . $val['text'] . '-' . $v['text'] . '-' . $vv);
												}

												if ($kk == 'text') {
													$text = str_replace('-log', '', $value['text'] . '-' . $val['text'] . '-' . $v['text']);
												}


												$array[] = array('text' => $text, 'value' => str_replace('.text', '', $key . '.' . $ke . '.' . $k . '.' . $kk));
											}

										}

									}
								}

							}
						}

					}
				}

			}

			self::$getLogTypes = $array;
		}


		return self::$getLogTypes;
	}

	public function log($type = '', $op = '')
	{
		global $_W;
		$this->check_xxx($type);

		if ($is_xxx = $this->check_xxx($type)) {
			$type = $is_xxx;
		}


		static $_logtypes;

		if (!($_logtypes)) {
			$_logtypes = $this->getLogTypes();
		}


		$log = array('uniacid' => $_W['uniacid'], 'uid' => $_W['merchuid'], 'merchid' => $_W['merchid'], 'name' => $this->getLogName($type, $_logtypes), 'type' => $type, 'op' => $op, 'ip' => CLIENT_IP, 'createtime' => time());
		pdo_insert('ewei_shop_merch_perm_log', $log);
	}

	public function formatPerms()
	{
		if (empty(self::$formatPerms)) {
			$perms = $this->allPerms();
			$array = array();

			foreach ($perms as $key => $value ) {
				if (is_array($value)) {
					foreach ($value as $ke => $val ) {
						if (!(is_array($val))) {
							$array['parent'][$key][$ke] = $val;
						}


						if (is_array($val) && ($ke != 'xxx')) {
							foreach ($val as $k => $v ) {
								if (!(is_array($v))) {
									$array['son'][$key][$ke][$k] = $v;
								}


								if (is_array($v) && ($k != 'xxx')) {
									foreach ($v as $kk => $vv ) {
										if (!(is_array($vv))) {
											$array['grandson'][$key][$ke][$k][$kk] = $vv;
										}

									}
								}

							}
						}

					}
				}

			}

			self::$formatPerms = $array;
		}


		return self::$formatPerms;
	}

	public function select_operator($merchid = 0)
	{
		global $_W;
		$merchid = ((empty($merchid) ? $_W['merchid'] : intval($merchid)));
		$total = pdo_fetchcolumn('SELECT count(*) FROM ' . tablename('ewei_shop_merch_account') . ' WHERE 1  and uniacid = :uniacid and merchid = :merchid and isfounder<>1', array(':uniacid' => $_W['uniacid'], ':merchid' => $_W['merchid']));
		return $total;
	}

	public function getEnoughs($set)
	{
		global $_W;
		$allenoughs = array();
		$enoughs = $set['enoughs'];

		if ((0 < floatval($set['enoughmoney'])) && (0 < floatval($set['enoughdeduct']))) {
			$allenoughs[] = array('enough' => floatval($set['enoughmoney']), 'money' => floatval($set['enoughdeduct']));
		}


		if (is_array($enoughs)) {
			foreach ($enoughs as $e ) {
				if ((0 < floatval($e['enough'])) && (0 < floatval($e['give']))) {
					$allenoughs[] = array('enough' => floatval($e['enough']), 'money' => floatval($e['give']));
				}

			}
		}


		usort($allenoughs, 'merch_sort_enoughs');
		return $allenoughs;
	}

	public function getMerchs($merch_array)
	{
		$merchs = array();

		if (!(empty($merch_array))) {
			foreach ($merch_array as $key => $value ) {
				$merchid = $key;

				if (0 < $merchid) {
					$merchs[$merchid]['merchid'] = $merchid;
					$merchs[$merchid]['goods'] = $value['goods'];
					$merchs[$merchid]['ggprice'] = $value['ggprice'];
				}

			}
		}


		return $merchs;
	}

	public function getUserTotals()
	{
		global $_W;
		global $_GPC;
		$totals = array('reg0' => pdo_fetchcolumn('select count(*) from ' . tablename('ewei_shop_merch_reg') . ' where uniacid=:uniacid and status=0 limit 1', array(':uniacid' => $_W['uniacid'])), 'reg_1' => pdo_fetchcolumn('select count(*) from ' . tablename('ewei_shop_merch_reg') . ' where uniacid=:uniacid and status=-1 limit 1', array(':uniacid' => $_W['uniacid'])), 'user0' => pdo_fetchcolumn('select count(*) from ' . tablename('ewei_shop_merch_user') . ' where uniacid=:uniacid and status=0 limit 1', array(':uniacid' => $_W['uniacid'])), 'user1' => pdo_fetchcolumn('select count(*) from ' . tablename('ewei_shop_merch_user') . ' where uniacid=:uniacid and status=1 limit 1', array(':uniacid' => $_W['uniacid'])), 'user2' => pdo_fetchcolumn('select count(*) from ' . tablename('ewei_shop_merch_user') . ' where uniacid=:uniacid and status=2 limit 1', array(':uniacid' => $_W['uniacid'])), 'user3' => pdo_fetchcolumn('select count(*) from ' . tablename('ewei_shop_merch_user') . ' where uniacid=:uniacid and status=1 and TIMESTAMPDIFF(DAY,now(),FROM_UNIXTIME(accounttime))<=30  limit 1', array(':uniacid' => $_W['uniacid'])));
		return $totals;
	}

	public function getClearTotals()
	{
		global $_W;
		global $_GPC;
		$totals = array('status0' => pdo_fetchcolumn('select count(*) from ' . tablename('ewei_shop_merch_clearing') . ' where uniacid=:uniacid and status=0 limit 1', array(':uniacid' => $_W['uniacid'])), 'status1' => pdo_fetchcolumn('select count(*) from ' . tablename('ewei_shop_merch_clearing') . ' where uniacid=:uniacid and status=1 limit 1', array(':uniacid' => $_W['uniacid'])), 'status2' => pdo_fetchcolumn('select count(*) from ' . tablename('ewei_shop_merch_clearing') . ' where uniacid=:uniacid and status=2 limit 1', array(':uniacid' => $_W['uniacid'])));
		return $totals;
	}

	public function getMerchOrderTotals($type = 0)
	{
		global $_W;
		$condition = ' and o.uniacid=:uniacid and o.merchid>0 and o.isparent=0';

		if ($type == 0) {
			$condition .= ' and o.status >= 0 ';
		}
		 else if ($type == 1) {
			$condition .= ' and o.status >= 1 ';
		}
		 else if ($type == 3) {
			$condition .= ' and o.status = 3 ';
		}


		$params = array(':uniacid' => $_W['uniacid']);
		$condition .= ' and o.deleted = 0 ';
		$sql = 'select sum(o.price) as totalmoney from ' . tablename('ewei_shop_order') . ' o ' . ' left join ' . tablename('ewei_shop_merch_user') . ' u on u.id = o.merchid ' . ' where 1 ' . $condition . ' ';
		$price = pdo_fetch($sql, $params);
		$totalmoney = round($price['totalmoney'], 2);
		$sql = 'select count(o.id) as totalcount from ' . tablename('ewei_shop_order') . ' o ' . ' left join ' . tablename('ewei_shop_merch_user') . ' u on u.id = o.merchid ' . ' where 1 ' . $condition . ' ';
		$totalcount = pdo_fetchcolumn($sql, $params);
		$data = array();
		$data['totalmoney'] = $totalmoney;
		$data['totalcount'] = $totalcount;
		return $data;
	}

	public function sendMessage($sendData, $message_type)
	{
		$notice = m('common')->getPluginset('merch');
		$tm = $notice['tm'];
		$templateid = $tm['templateid'];

		if (($message_type == 'merch_apply') && empty($usernotice['merch_apply'])) {
			$tm['msguser'] = 0;
			$data = array('[商户名称]' => $sendData['merchname'], '[主营项目]' => $sendData['salecate'], '[联系人]' => $sendData['realname'], '[手机号]' => $sendData['mobile'], '[申请时间]' => date('Y-m-d H:i:s', $sendData['applytime']));
			$message = array('keyword1' => (!(empty($tm['merch_applytitle'])) ? $tm['merch_applytitle'] : '商户入驻申请'), 'keyword2' => (!(empty($tm['merch_apply'])) ? $tm['merch_apply'] : '[商户名称]在[申请时间]提交了入驻申请，请到后台查看~'));
			return $this->sendNotice($tm, 'merch_apply_advanced', $data, $message);
		}


		if (($message_type == 'merch_apply_money') && empty($usernotice['merch_apply_money'])) {
			$tm['msguser'] = 1;
			$data = array('[商户名称]' => $sendData['merchname'], '[金额]' => $sendData['money'], '[联系人]' => $sendData['realname'], '[手机号]' => $sendData['mobile'], '[申请时间]' => date('Y-m-d H:i:s', $sendData['applytime']));
			$message = array('keyword1' => (!(empty($tm['merch_applymoneytitle'])) ? $tm['merch_applymoneytitle'] : '商户提现申请'), 'keyword2' => (!(empty($tm['merch_applymoney'])) ? $tm['merch_applymoney'] : '[商户名称]在[申请时间]提交了提现申请,提现金额' . $sendData['money'] . '.[联系人] [手机号].请到后台查看~'));
			return $this->sendNotice($tm, 'merch_apply_advanced', $data, $message);
		}

	}

	protected function sendNotice($tm, $tag, $datas, $message)
	{
		global $_W;

		if (!(empty($tm['is_advanced'])) && !(empty($tm[$tag]))) {
			$advanced_template = pdo_fetch('select * from ' . tablename('ewei_shop_member_message_template') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $tm[$tag], ':uniacid' => $_W['uniacid']));

			if (!(empty($advanced_template))) {
				$url = ((!(empty($advanced_template['url'])) ? $this->replaceArray($datas, $advanced_template['url']) : ''));
				$advanced_message = array(
					'first'  => array('value' => $this->replaceArray($datas, $advanced_template['first']), 'color' => $advanced_template['firstcolor']),
					'remark' => array('value' => $this->replaceArray($datas, $advanced_template['remark']), 'color' => $advanced_template['remarkcolor'])
					);
				$data = iunserializer($advanced_template['data']);

				foreach ($data as $d ) {
					$advanced_message[$d['keywords']] = array('value' => $this->replaceArray($datas, $d['value']), 'color' => $d['color']);
				}

				$tm['templateid'] = $advanced_template['template_id'];
				$this->sendMoreAdvanced($tm, $tag, $advanced_message, $url);
			}

		}
		 else {
			$tag = str_replace('_advanced', '', $tag);
			$this->sendMore($tm, $message, $datas);
		}

		return true;
	}

	protected function sendMore($tm, $message, $datas)
	{
		$message['keyword2'] = $this->replaceArray($datas, $message['keyword2']);
		$msg = array(
			'keyword1' => array('value' => $message['keyword1'], 'color' => '#73a68d'),
			'keyword2' => array('value' => $message['keyword2'], 'color' => '#73a68d')
			);

		if ($tm['msguser'] == 1) {
			$openid = $tm['applyopenid'];
		}
		 else {
			$openid = $tm['openid'];
		}

		if (!(empty($openid))) {
			foreach ($openid as $openid ) {
				$send = m('message')->sendTplNotice($openid, $tm['templateid'], $msg);

				if (is_error($send)) {
					m('message')->sendCustomNotice($openid, $msg);
				}

			}
		}

	}

	protected function sendMoreAdvanced($tm, $tag, $msg, $url)
	{
		if ($tm['msguser'] == 1) {
			$openid = $tm['applyopenid'];
		}
		 else {
			$openid = $tm['openid'];
		}

		if (!(empty($openid))) {
			foreach ($openid as $openid ) {
				if (!(empty($tm[$tag])) && !(empty($tm['templateid']))) {
					m('message')->sendTplNotice($openid, $tm['templateid'], $msg, $url);
				}
				 else {
					m('message')->sendCustomNotice($openid, $msg, $url);
				}
			}
		}

	}

	protected function replaceArray(array $array, $message)
	{
		foreach ($array as $key => $value ) {
			$message = str_replace($key, $value, $message);
		}

		return $message;
	}

	public function getMerchOrderTotalPrice($merchid)
	{
		global $_W;
		$data = array();
		$list = $this->getMerchPrice($merchid, 1);
		$data['status0'] = $list['realprice'];
		$params = array(':uniacid' => $_W['uniacid'], ':merchid' => $merchid);
		$condition = ' and uniacid=:uniacid and merchid=:merchid';
		$sql = 'select sum(realprice) as totalmoney from ' . tablename('ewei_shop_merch_bill') . ' where 1 ' . $condition . ' and status=1';
		$price = pdo_fetch($sql, $params);
		$data['status1'] = round($price['totalmoney'], 2);
		$sql = 'select sum(realprice) as totalmoney from ' . tablename('ewei_shop_merch_bill') . ' where 1 ' . $condition . ' and status=2';
		$price = pdo_fetch($sql, $params);
		$data['status2'] = round($price['totalmoney'], 2);
		$sql = 'select sum(finalprice) as totalmoney from ' . tablename('ewei_shop_merch_bill') . ' where 1 ' . $condition . ' and status=3';
		$price = pdo_fetch($sql, $params);
		$data['status3'] = round($price['totalmoney'], 2);
		return $data;
	}

	public function getMerchPrice($merchid, $flag = 0)
	{
		global $_W;
		$merch_data = m('common')->getPluginset('merch');

		if (empty($merch_data)) {
			$merch_data = $this->getPluginsetByMerch('merch');
		}


		if (!(empty($merch_data['deduct_commission']))) {
			$deduct_commission = 1;
		}
		 else {
			$deduct_commission = 0;
		}

		$condition = ' and u.uniacid=:uniacid and u.id=:merchid and o.status=3 and o.isparent=0 and o.merchapply<=0 and o.paytype<>3 ';
		$params = array(':uniacid' => $_W['uniacid'], ':merchid' => $merchid);
		$con = 'u.id,u.merchname,u.payrate,sum(o.price) price,sum(o.goodsprice) goodsprice,sum(o.dispatchprice) dispatchprice,sum(o.discountprice) discountprice,sum(o.deductprice) deductprice,sum(o.deductcredit2) deductcredit2,sum(o.isdiscountprice) isdiscountprice,sum(o.deductenough) deductenough,sum(o.merchdeductenough) merchdeductenough,sum(o.merchisdiscountprice) merchisdiscountprice,sum(o.changeprice) changeprice,sum(o.seckilldiscountprice) seckilldiscountprice';
		$tradeset = m('common')->getSysset('trade');
		$refunddays = intval($tradeset['refunddays']);

		if (0 < $refunddays) {
			$finishtime = intval(time() - ($refunddays * 3600 * 24));
			$condition .= ' and o.finishtime<:finishtime';
			$params['finishtime'] = $finishtime;
		}


		$sql = 'select ' . $con . ' from ' . tablename('ewei_shop_merch_user') . ' u ' . ' left join ' . tablename('ewei_shop_order') . ' o on u.id=o.merchid' . ' where 1 ' . $condition . ' limit 1';
		$list = pdo_fetch($sql, $params);
		$merchcouponprice = pdo_fetchcolumn('select sum(o.couponprice) from ' . tablename('ewei_shop_merch_user') . ' u ' . ' left join ' . tablename('ewei_shop_order') . ' o on u.id=o.merchid' . ' where o.couponmerchid>0 ' . $condition . ' limit 1', $params);

		if (0 < $flag) {
			$sql = 'select o.id,o.agentid from ' . tablename('ewei_shop_merch_user') . ' u ' . ' left join ' . tablename('ewei_shop_order') . ' o on u.id=o.merchid' . ' where 1 ' . $condition;
			$order = pdo_fetchall($sql, $params);
			$orderids = array();
			$commission = 0;

			if (!(empty($order))) {
				foreach ($order as $k => $v ) {
					$orderids[] = $v['id'];
					$commission += m('order')->getOrderCommission($v['id'], $v['agentid']);
				}
			}


			$list['orderids'] = $orderids;
			$list['commission'] = $commission;
		}


		$list['orderprice'] = $list['goodsprice'] + $list['dispatchprice'] + $list['changeprice'];
		$list['realprice'] = $list['orderprice'] - $list['merchdeductenough'] - $list['merchisdiscountprice'] - $merchcouponprice - $list['seckilldiscountprice'];

		if ($deduct_commission) {
			$list['realprice'] -= $list['commission'];
		}


		$list['realpricerate'] = ((100 - floatval($list['payrate'])) * $list['realprice']) / 100;
		$list['merchcouponprice'] = $merchcouponprice;
		return $list;
	}

	public function getMerchPriceList($merchid, $orderid = 0, $flag = 0)
	{
		global $_W;
		$merch_data = m('common')->getPluginset('merch');

		if (empty($merch_data)) {
			$merch_data = $this->getPluginsetByMerch('merch');
		}


		if (!(empty($merch_data['deduct_commission']))) {
			$deduct_commission = 1;
		}
		 else {
			$deduct_commission = 0;
		}

		$condition = ' and u.uniacid=:uniacid and u.id=:merchid and o.status=3 and o.isparent=0 and o.paytype<>3 ';
		$params = array(':uniacid' => $_W['uniacid'], ':merchid' => $merchid);

		switch ($flag) {
		case 0:
			$condition .= ' and o.merchapply <= 0';
			break;

		case 1:
			$condition .= ' and o.merchapply = 1';
			break;

		case 2:
			$condition .= ' and o.merchapply = 2';
			break;

		case 3:
			$condition .= ' and o.merchapply = 3';
			break;
		}

		$tradeset = m('common')->getSysset('trade');
		$refunddays = intval($tradeset['refunddays']);

		if (0 < $refunddays) {
			$finishtime = intval(time() - ($refunddays * 3600 * 24));
			$condition .= ' and o.finishtime<:finishtime';
			$params['finishtime'] = $finishtime;
		}


		if (!(empty($orderid))) {
			$condition .= ' and o.id=:id Limit 1';
			$params['id'] = $orderid;
		}


		$con = 'o.id,u.merchname,u.payrate,o.price,o.goodsprice,o.dispatchprice,discountprice,' . 'o.deductprice,o.deductcredit2,o.isdiscountprice,o.deductenough,o.changeprice,o.agentid,o.seckilldiscountprice,' . 'o.merchdeductenough,o.merchisdiscountprice,o.couponmerchid,o.couponprice,o.couponmerchid,o.ordersn,o.finishtime,o.merchapply';
		$sql = 'select ' . $con . ' from ' . tablename('ewei_shop_merch_user') . ' u ' . ' left join ' . tablename('ewei_shop_order') . ' o on u.id=o.merchid' . ' where 1 ' . $condition;
		$order = pdo_fetchall($sql, $params);

		foreach ($order as &$list ) {
			$merchcouponprice = 0;

			if (0 < $list['couponmerchid']) {
				$merchcouponprice = $list['couponprice'];
			}


			$list['commission'] = m('order')->getOrderCommission($list['id'], $list['agentid']);
			$list['orderprice'] = $list['goodsprice'] + $list['dispatchprice'] + $list['changeprice'];
			$list['realprice'] = $list['orderprice'] - $list['merchdeductenough'] - $list['merchisdiscountprice'] - $merchcouponprice - $list['seckilldiscountprice'];

			if ($deduct_commission) {
				$list['realprice'] -= $list['commission'];
			}


			$list['realpricerate'] = ((100 - floatval($list['payrate'])) * $list['realprice']) / 100;
			$list['merchcouponprice'] = $merchcouponprice;
		}

		unset($list);

		if (!(empty($orderid))) {
			return $order[0];
		}


		return $order;
	}

	public function getOneApply($id)
	{
		global $_W;
		$condition = ' and u.uniacid=:uniacid and b.id=:id';
		$params = array(':uniacid' => $_W['uniacid'], ':id' => $id);
		$sql = 'select b.*,u.merchname,u.realname,u.mobile from ' . tablename('ewei_shop_merch_bill') . ' b ' . ' left join ' . tablename('ewei_shop_merch_user') . ' u on b.merchid = u.id' . ' where 1 ' . $condition . ' Limit 1';
		$data = pdo_fetch($sql, $params);
		return $data;
	}

	public function getPassApplyPrice($merchid, $orderids)
	{
		global $_W;
		$data = array();
		$data['realprice'] = 0;
		$data['realpricerate'] = 0;
		$data['orderprice'] = 0;

		if (!(empty($orderids))) {
			foreach ($orderids as $key => $orderid ) {
				$item = $this->getMerchPriceList($merchid, $orderid, 1);
				$data['realprice'] += $item['realprice'];
				$data['realpricerate'] += $item['realpricerate'];
				$data['orderprice'] += $item['orderprice'];
			}
		}


		return $data;
	}

	public function getMerchApplyTotals($merchid = 0)
	{
		global $_W;
		$totals = array();
		$condition = ' and uniacid=:uniacid';
		$params = array();
		$params[':uniacid'] = $_W['uniacid'];

		if (0 < $merchid) {
			$condition .= ' and merchid=:merchid';
			$params[':merchid'] = $merchid;
		}


		$totals['status1'] = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename('ewei_shop_merch_bill') . ' WHERE 1 ' . $condition . ' and status=1', $params);
		$totals['status2'] = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename('ewei_shop_merch_bill') . ' WHERE 1 ' . $condition . ' and status=2', $params);
		$totals['status3'] = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename('ewei_shop_merch_bill') . ' WHERE 1 ' . $condition . ' and status=3', $params);
		$totals['status_1'] = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename('ewei_shop_merch_bill') . ' WHERE 1 ' . $condition . ' and status=-1', $params);
		return $totals;
	}

	public function getAllUserTotals()
	{
		global $_W;
		$totals = pdo_fetchcolumn('select count(1) from ' . tablename('ewei_shop_merch_user') . ' where uniacid=:uniacid', array(':uniacid' => $_W['uniacid']));
		return $totals;
	}

	public function checkMaxMerchUser($type = 0)
	{
		global $_W;
		$totals = $this->getAllUserTotals();
		$max_merch = $this->getMaxMerchUser();
		$flag = 0;

		if (0 < $max_merch) {
			if ($max_merch <= $totals) {
				if ($type == 1) {
					$flag = 1;
				}
				 else {
					show_json(0, '已经达到最大商户数量,不能再添加商户.');
				}
			}

		}


		return $flag;
	}

	public function getMaxMerchUser()
	{
		global $_W;
		$data = pdo_fetch('select datas from ' . tablename('ewei_shop_perm_plugin') . ' where acid=:acid Limit 1', array(':acid' => $_W['uniacid']));
		$max_merch = 0;

		if (!(empty($data['datas']))) {
			$datas = json_decode($data['datas']);
			$max_merch = $datas->max_merch;

			if (empty($max_merch)) {
				$max_merch = 0;
			}

		}


		return $max_merch;
	}

	public function getInsertData($fields, $memberdata)
	{
		global $_W;
		return '';
	}

	public function tempData($type, $merchid = 0)
	{
		global $_W;
		global $_GPC;
		$merchid = ((empty($merchid) ? $_W['merchid'] : $merchid));
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$condition = ' uniacid = :uniacid and type=:type and merchid=:merchid';
		$params = array(':uniacid' => $_W['uniacid'], ':type' => $type, ':merchid' => $merchid);

		if (!(empty($_GPC['keyword']))) {
			$_GPC['keyword'] = trim($_GPC['keyword']);
			$condition .= ' AND expressname LIKE :expressname';
			$params[':expressname'] = '%' . trim($_GPC['keyword']) . '%';
		}


		$sql = 'SELECT id,expressname,expresscom,isdefault FROM ' . tablename('ewei_shop_exhelper_express') . ' where  1 and ' . $condition . ' ORDER BY isdefault desc, id DESC LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize;
		$list = pdo_fetchall($sql, $params);
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_exhelper_express') . ' where 1 and ' . $condition, $params);
		$pager = pagination($total, $pindex, $psize);
		return array('list' => $list, 'total' => $total, 'pager' => $pager, 'type' => $type);
	}

	public function setDefault($id, $type, $merchid = 0)
	{
		global $_W;
		$merchid = ((empty($merchid) ? $_W['merchid'] : $merchid));
		$item = pdo_fetch('SELECT id,expressname,type FROM ' . tablename('ewei_shop_exhelper_express') . ' WHERE id=:id and type=:type AND uniacid=:uniacid and merchid=:merchid', array(':id' => $id, ':type' => $type, ':uniacid' => $_W['uniacid'], ':merchid' => $merchid));

		if (!(empty($item))) {
			pdo_update('ewei_shop_exhelper_express', array('isdefault' => 0), array('type' => $type, 'uniacid' => $_W['uniacid'], 'merchid' => $merchid));
			pdo_update('ewei_shop_exhelper_express', array('isdefault' => 1), array('id' => $id, 'merchid' => $merchid));

			if ($type == 1) {
				plog('merch.exhelper.temp.express.setdefault', '设置默认快递单 ID: ' . $item['id'] . '， 模板名称: ' . $item['expressname'] . ' ');
			}
			 else if ($type == 2) {
				plog('merch.exhelper.temp.invoice.setdefault', '设置默认发货单 ID: ' . $item['id'] . '， 模板名称: ' . $item['expressname'] . ' ');
			}

		}

	}

	public function tempDelete($id, $type, $merchid = 0)
	{
		global $_W;
		$merchid = ((empty($merchid) ? $_W['merchid'] : $merchid));
		$items = pdo_fetchall('SELECT id,expressname FROM ' . tablename('ewei_shop_exhelper_express') . ' WHERE id in( ' . $id . ' ) and type=:type and uniacid=:uniacid and merchid=:merchid', array(':type' => $type, ':uniacid' => $_W['uniacid'], ':merchid' => $merchid));

		foreach ($items as $item ) {
			pdo_delete('ewei_shop_exhelper_express', array('id' => $item['id'], 'uniacid' => $_W['uniacid'], 'merchid' => $merchid));

			if ($type == 1) {
				plog('merch.exhelper.temp.express.delete', '删除 快递助手 快递单模板 ID: ' . $item['id'] . '， 模板名称: ' . $item['expressname'] . ' ');
			}
			 else if ($type == 2) {
				plog('merch.exhelper.temp.invoice.delete', '删除 快递助手 发货单模板 ID: ' . $item['id'] . '， 模板名称: ' . $item['expressname'] . ' ');
			}

		}
	}

	public function getTemp($merchid = 0)
	{
		global $_W;
		global $_GPC;
		$merchid = ((empty($merchid) ? $_W['merchid'] : $merchid));
		$temp_sender = pdo_fetchall('SELECT id,isdefault,sendername,sendertel FROM ' . tablename('ewei_shop_exhelper_senduser') . ' WHERE uniacid=:uniacid and merchid=:merchid order by isdefault desc ', array(':uniacid' => $_W['uniacid'], ':merchid' => $merchid));
		$temp_express = pdo_fetchall('SELECT id,type,isdefault,expressname FROM ' . tablename('ewei_shop_exhelper_express') . ' WHERE type=1 and uniacid=:uniacid and merchid=:merchid order by isdefault desc ', array(':uniacid' => $_W['uniacid'], ':merchid' => $merchid));
		$temp_invoice = pdo_fetchall('SELECT id,type,isdefault,expressname FROM ' . tablename('ewei_shop_exhelper_express') . ' WHERE type=2 and uniacid=:uniacid and merchid=:merchid order by isdefault desc ', array(':uniacid' => $_W['uniacid'], ':merchid' => $merchid));
		return array('temp_sender' => $temp_sender, 'temp_express' => $temp_express, 'temp_invoice' => $temp_invoice);
	}

	public function updateOrderPay()
	{
		global $_W;
		$params = array();
		$params[':uniacid'] = $_W['uniacid'];
		$sql = 'select id,parentid from ' . tablename('ewei_shop_order') . ' where parentid>0 and status>0 and paytype=0 and uniacid=:uniacid';
		$list = pdo_fetchall($sql, $params);

		if (!(empty($list))) {
			foreach ($list as $k => $v ) {
				$params[':orderid'] = $v['parentid'];
				$sql1 = 'select paytype from ' . tablename('ewei_shop_order') . ' where id=:orderid and status>0 and paytype>0 and uniacid=:uniacid';
				$item = pdo_fetch($sql1, $params);

				if (0 < $item['paytype']) {
					pdo_update('ewei_shop_order', array('paytype' => $item['paytype']), array('id' => $v['id']));
				}

			}
		}

	}

	public function getPluginsetByMerch($key = '')
	{
		global $_W;
		$uniacid = $_W['uniacid'];
		$set = pdo_fetch('select * from ' . tablename('ewei_shop_sysset') . ' where uniacid=:uniacid limit 1', array(':uniacid' => $uniacid));

		if (empty($set)) {
			$set = array();
		}


		$allset = iunserializer($set['plugins']);
		$retsets = array();

		if (!(empty($key))) {
			if (is_array($key)) {
				foreach ($key as $k ) {
					$retsets[$k] = ((isset($allset[$k]) ? $allset[$k] : array()));
				}
			}
			 else {
				$retsets = ((isset($allset[$key]) ? $allset[$key] : array()));
			}

			return $retsets;
		}


		return $allset;
	}

	public function getPluginList($merchid = 0)
	{
		$category = m('plugin')->getList(1);
		$has_plugins = array();
		$perm = com('perm');
		if (p('exhelper') && $perm->is_perm_plugin('exhelper')) {
			$has_plugins[] = 'exhelper';
		}


		if (p('taobao') && $perm->is_perm_plugin('taobao')) {
			$has_plugins[] = 'taobao';
		}


		if (p('diypage') && $perm->is_perm_plugin('diypage')) {
			$has_plugins[] = 'diypage';
		}


		if (p('creditshop') && $perm->is_perm_plugin('creditshop')) {
			$has_plugins[] = 'creditshop';
		}


		if (p('quick') && $perm->is_perm_plugin('quick')) {
			$has_plugins[] = 'quick';
		}


		if (!(empty($merchid))) {
			$item = $this->getListUserOne($merchid);

			if (!(empty($item['pluginset']))) {
				$pluginset = iunserializer($item['pluginset']);
			}

		}


		$plugins_list = array();
		$plugins_all = array();

		foreach ($category as $key => $value ) {
			foreach ($value['plugins'] as $k => $v ) {
				$plugins_all[$v['identity']] = $v;

				if (in_array($v['identity'], $has_plugins)) {
					$is_has = 1;

					if (!(empty($pluginset))) {
						if ($pluginset[$v['identity']]['close'] == 1) {
							$is_has = 0;
						}
					}
					if ($is_has) {
						$plugins_list[] = $v;
					}

				}

			}
		}

		$data = array();
		$data['plugins_list'] = $plugins_list;
		$data['plugins_all'] = $plugins_all;
		return $data;
	}

	public function CheckPlugin($plugin, $merchid = 0, $flag = 0)
	{
		global $_W;

		if (empty($merchid)) {
			$merchid = $_W['merchid'];
		}


		$plugins_data = $this->getPluginList($merchid);
		$plugins_list = $plugins_data['plugins_list'];
		$check = 0;

		foreach ($plugins_list as $k => $v ) {
			while ($v['identity'] == $plugin) {
				$check = 1;
				break;
			}
		}

		if (empty($flag)) {
			if ($check == 0) {
				show_message('您没有该应用的权限!');
			}

		}
		 else {
			return $check;
		}
	}
}

function merch_sort_enoughs($a, $b)
{
	$enough1 = floatval($a['enough']);
	$enough2 = floatval($b['enough']);

	if ($enough1 == $enough2) {
		return 0;
	}


	return ($enough1 < $enough2 ? 1 : -1);
}


?>