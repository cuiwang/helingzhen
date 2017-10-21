<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
}

class Goods_EweiShopV2Page extends PluginWebPage
{
	public function main()
	{
		global $_W;
		global $_GPC;
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$condition = ' g.uniacid = :uniacid ';
		$params = array(':uniacid' => $_W['uniacid']);
		$type = $_GPC['type'];

		switch ($type) {
		case 'sale':
			$condition .= ' and g.deleted = 0 and g.stock > 0 and g.status = 1 ';
			break;

		case 'sold':
			$condition .= ' and g.deleted = 0 and g.stock <= 0 and g.status = 1 ';
			break;

		case 'store':
			$condition .= ' and g.deleted = 0 and g.status = 0 ';
			break;

		case 'recycle':
			$condition .= ' and g.deleted = 1 ';
			break;

		default:
			$condition .= ' and g.deleted = 0 and g.stock > 0 and g.status = 1 ';
		}

		if (!empty($_GPC['keyword'])) {
			$_GPC['keyword'] = trim($_GPC['keyword']);
			$condition .= ' AND title LIKE :title';
			$params[':title'] = '%' . trim($_GPC['keyword']) . '%';
		}

		if ($_GPC['status'] != '') {
			$condition .= ' AND status = :status';
			$params[':status'] = intval($_GPC['status']);
		}

		if ($_GPC['category'] != '') {
			$condition .= ' AND category = :category';
			$params[':category'] = intval($_GPC['category']);
		}

		$sql = 'SELECT c.*,g.* FROM ' . tablename('ewei_shop_groups_goods') . " AS g\r\n\t\t\t\tLEFT JOIN " . tablename('ewei_shop_groups_category') . " AS c ON g.category = c.id\r\n\t\t\t\twhere  1 = 1 and " . $condition . ' ORDER BY g.displayorder DESC,g.id DESC LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize;
		$list = pdo_fetchall($sql, $params);
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_groups_goods') . ' AS g where 1 and ' . $condition, $params);
		$pager = pagination2($total, $pindex, $psize);
		$category = pdo_fetchall('select id,name,thumb from ' . tablename('ewei_shop_groups_category') . ' where uniacid=:uniacid order by displayorder desc', array(':uniacid' => $_W['uniacid']), 'id');
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
		$id = intval($_GPC['id']);
		$item = pdo_fetch('SELECT g.*,c.name as catename FROM ' . tablename('ewei_shop_groups_goods') . " as g\r\n\t\t\t\tleft join " . tablename('ewei_shop_groups_category') . " as c on c.id = g.category\r\n\t\t\t\tWHERE g.id =:id and g.uniacid=:uniacid limit 1", array(':uniacid' => $_W['uniacid'], ':id' => $id));
		$category = pdo_fetchall('select id,name,thumb from ' . tablename('ewei_shop_groups_category') . ' where uniacid=:uniacid order by displayorder desc', array(':uniacid' => $_W['uniacid']));

		if (!empty($item['thumb'])) {
			$piclist = array_merge(array($item['thumb']), iunserializer($item['thumb_url']));
		}

		$stores = array();

		if (!empty($item['storeids'])) {
			$stores = pdo_fetchall('select id,storename from ' . tablename('ewei_shop_store') . ' where id in (' . $item['storeids'] . ' ) and uniacid=' . $_W['uniacid']);
		}

		$dispatch_data = pdo_fetchall('select * from ' . tablename('ewei_shop_dispatch') . ' where uniacid=:uniacid and enabled=1 order by displayorder desc', array(':uniacid' => $_W['uniacid']));

		if ($_W['ispost']) {
			$data = array('uniacid' => $_W['uniacid'], 'displayorder' => intval($_GPC['displayorder']), 'gid' => intval($_GPC['gid']), 'title' => trim($_GPC['title']), 'category' => intval($_GPC['category']), 'thumb' => '', 'thumb_url' => '', 'price' => floatval($_GPC['price']), 'groupsprice' => floatval($_GPC['groupsprice']), 'single' => intval($_GPC['single']), 'singleprice' => floatval($_GPC['singleprice']), 'goodsnum' => intval($_GPC['goodsnum']) < 1 ? 1 : intval($_GPC['goodsnum']), 'purchaselimit' => intval($_GPC['purchaselimit']), 'units' => trim($_GPC['units']), 'stock' => intval($_GPC['stock']), 'showstock' => intval($_GPC['showstock']), 'sales' => intval($_GPC['sales']), 'teamnum' => intval($_GPC['teamnum']), 'dispatchtype' => intval($_GPC['dispatchtype']), 'freight' => floatval($_GPC['freight']), 'status' => intval($_GPC['status']), 'isindex' => intval($_GPC['isindex']), 'groupnum' => intval($_GPC['groupnum']), 'endtime' => intval($_GPC['endtime']), 'description' => trim($_GPC['description']), 'goodssn' => trim($_GPC['goodssn']), 'productsn' => trim($_GPC['productsn']), 'content' => m('common')->html_images($_GPC['content']), 'createtime' => $_W['timestamp'], 'share_title' => trim($_GPC['share_title']), 'share_icon' => trim($_GPC['share_icon']), 'share_desc' => trim($_GPC['share_desc']), 'followneed' => intval($_GPC['followneed']), 'followtext' => trim($_GPC['followtext']), 'followurl' => trim($_GPC['followurl']), 'goodsid' => intval($_GPC['goodsid']), 'deduct' => floatval($_GPC['deduct']), 'isdiscount' => intval($_GPC['isdiscount']), 'discount' => intval($_GPC['discount']), 'headstype' => intval($_GPC['headstype']), 'headsmoney' => floatval($_GPC['headsmoney']), 'headsdiscount' => intval($_GPC['headsdiscount']), 'isverify' => intval($_GPC['isverify']), 'verifytype' => intval($_GPC['verifytype']), 'verifynum' => intval($_GPC['verifynum']), 'storeids' => is_array($_GPC['storeids']) ? implode(',', $_GPC['storeids']) : '');

			if ($data['groupsprice'] < $data['headsmoney']) {
				$data['headsmoney'] = $data['groupsprice'];
			}

			if (!empty($data['verifytype']) && ($data['verifynum'] < 1)) {
				$data['verifynum'] = 1;
			}

			if ($data['headsmoney'] < 0) {
				$data['headsmoney'] = 0;
			}

			if ($data['headsdiscount'] < 0) {
				$data['headsdiscount'] = 0;
			}

			if (100 < $data['headsdiscount']) {
				$data['headsdiscount'] = 100;
			}

			if ($data['goodsnum'] < 0) {
				show_json(0, '数量不能小于1！');
			}

			if ($data['groupnum'] < 2) {
				show_json(0, '开团人数至少为2人！');
			}

			if ($data['endtime'] < 1) {
				show_json(0, '组团限时不能小于1小时！');
			}

			if ($data['groupsprice'] <= 0) {
				show_json(0, '拼团价格不符合要求！');
			}

			if (($data['singleprice'] <= 0) && ($data['single'] == 1)) {
				show_json(0, '单购价格不符合要求！');
			}

			$data['title'] = empty($data['goodstype']) ? trim($_GPC['goodsid_text']) : trim($_GPC['couponid_text']);

			if (is_array($_GPC['thumbs'])) {
				$thumbs = $_GPC['thumbs'];
				$thumb_url = array();

				foreach ($thumbs as $th) {
					$thumb_url[] = trim($th);
				}

				$data['thumb'] = save_media($thumb_url[0]);
				unset($thumb_url[0]);
				$data['thumb_url'] = serialize(m('common')->array_images($thumb_url));
			}

			if (!empty($id)) {
				$goods_update = pdo_update('ewei_shop_groups_goods', $data, array('id' => $id, 'uniacid' => $_W['uniacid']));

				if (!$goods_update) {
					show_json(0, '商品编辑失败！');
				}

				plog('groups.goods.edit', '编辑拼团商品 ID: ' . $id . ' <br/>商品名称: ' . $data['title']);
			}
			else {
				$goods_insert = pdo_insert('ewei_shop_groups_goods', $data);

				if (!$goods_insert) {
					show_json(0, '商品添加失败！');
				}

				$id = pdo_insertid();
				$gid = intval($data['gid']);

				if ($gid) {
					pdo_update('ewei_shop_goods', array('groupstype' => 1), array('id' => $gid, 'uniacid' => $_W['uniacid']));
				}

				plog('groups.goods.add', '添加拼团商品 ID: ' . $id . '  <br/>商品名称: ' . $data['title']);
			}

			show_json(1, array('url' => webUrl('groups/goods/edit', array('op' => 'post', 'id' => $id, 'tab' => str_replace('#tab_', '', $_GPC['tab'])))));
		}

		include $this->template();
	}

	public function total()
	{
		global $_W;
		global $_GPC;
		$type = intval($_GPC['type']);
		$condition = ' uniacid = :uniacid ';
		$params[':uniacid'] = $_W['uniacid'];

		if ($type == 1) {
			$condition .= ' and deleted = 0 and stock > 0 and status = 1 ';
		}
		else if ($type == 2) {
			$condition .= ' and deleted = 0 and stock = 0 and status = 1';
		}
		else if ($type == 3) {
			$condition .= ' and deleted = 0 and status = 0 ';
		}
		else {
			if ($type == 4) {
				$condition .= ' and deleted = 1 ';
			}
		}

		$total = pdo_fetchcolumn('select count(1) from ' . tablename('ewei_shop_groups_goods') . ' where ' . $condition . ' ', $params);
		echo json_encode($total);
	}

	public function query()
	{
		global $_W;
		global $_GPC;
		$kwd = trim($_GPC['keyword']);
		$params = array();
		$params[':uniacid'] = $_W['uniacid'];
		$condition = ' and uniacid=:uniacid and merchid = 0 and type = 1 and status = 1 and deleted = 0 ';

		if (!empty($kwd)) {
			$condition .= ' AND `title` LIKE :keyword';
			$params[':keyword'] = '%' . $kwd . '%';
		}

		$ds = pdo_fetchall("SELECT id as gid,title,subtitle,thumb,thumb_url,marketprice,productprice,subtitle,content,goodssn,productsn,followtip,followurl\r\n\t\t\t\tFROM " . tablename('ewei_shop_goods') . ' WHERE 1 ' . $condition . ' order by createtime desc', $params);

		foreach ($ds as &$d) {
			if (!empty($d['thumb_url'])) {
				$d['thumb_url'] = iunserializer($d['thumb_url']);
			}
		}

		unset($d);
		$ds = set_medias($ds, array('share_icon'));

		if ($_GPC['suggest']) {
			exit(json_encode(array('value' => $ds)));
		}

		include $this->template();
	}

	public function delete1()
	{
		global $_W;
		global $_GPC;
		$id = intval($_GPC['id']);

		if (empty($id)) {
			$id = (is_array($_GPC['ids']) ? implode(',', $_GPC['ids']) : 0);
		}

		$items = pdo_fetchall('SELECT id,title,gid FROM ' . tablename('ewei_shop_groups_goods') . ' WHERE id in( ' . $id . ' ) AND uniacid=' . $_W['uniacid']);

		foreach ($items as $item) {
			pdo_delete('ewei_shop_groups_goods', array('id' => $item['id']));
			plog('groups.goods.edit', '从回收站彻底删除商品<br/>ID: ' . $item['id'] . '<br/>商品名称: ' . $item['title']);
		}

		show_json(1, array('url' => referer()));
	}

	public function restore()
	{
		global $_W;
		global $_GPC;
		$id = intval($_GPC['id']);

		if (empty($id)) {
			$id = (is_array($_GPC['ids']) ? implode(',', $_GPC['ids']) : 0);
		}

		$items = pdo_fetchall('SELECT id,title,gid FROM ' . tablename('ewei_shop_groups_goods') . ' WHERE id in( ' . $id . ' ) AND uniacid=' . $_W['uniacid']);

		foreach ($items as $item) {
			pdo_update('ewei_shop_groups_goods', array('deleted' => 0, 'status' => 0), array('id' => $item['id']));

			if (intval($item['gid'])) {
				pdo_update('ewei_shop_goods', array('groupstype' => 1), array('id' => $item['gid'], 'uniacid' => $_W['uniacid']));
			}

			plog('groups.goods.edit', '从回收站恢复商品<br/>ID: ' . $item['id'] . '<br/>商品名称: ' . $item['title']);
		}

		show_json(1, array('url' => referer()));
	}

	public function delete()
	{
		global $_W;
		global $_GPC;
		$id = intval($_GPC['id']);

		if (empty($id)) {
			$id = (is_array($_GPC['ids']) ? implode(',', $_GPC['ids']) : 0);
		}

		$items = pdo_fetchall('SELECT id,title,gid FROM ' . tablename('ewei_shop_groups_goods') . ' WHERE id in( ' . $id . ' ) AND uniacid=' . $_W['uniacid']);

		foreach ($items as $item) {
			$delete_update = pdo_update('ewei_shop_groups_goods', array('deleted' => 1, 'status' => 0), array('id' => $id, 'uniacid' => $_W['uniacid']));

			if (!$delete_update) {
				show_json(0, '删除商品失败！');
			}

			if (intval($item['gid'])) {
				pdo_update('ewei_shop_goods', array('groupstype' => 0), array('id' => $item['gid'], 'uniacid' => $_W['uniacid']));
			}

			plog('groups.goods.delete', '删除拼团商品 ID: ' . $item['id'] . '  <br/>商品名称: ' . $item['title'] . ' ');
		}

		show_json(1, array('url' => referer()));
	}

	public function status()
	{
		global $_W;
		global $_GPC;
		$id = intval($_GPC['id']);

		if (empty($id)) {
			$id = (is_array($_GPC['ids']) ? implode(',', $_GPC['ids']) : 0);
		}

		$status = intval($_GPC['status']);
		$items = pdo_fetchall('SELECT id,status FROM ' . tablename('ewei_shop_groups_goods') . ' WHERE id in( ' . $id . ' ) AND uniacid=' . $_W['uniacid']);

		foreach ($items as $item) {
			$status_update = pdo_update('ewei_shop_groups_goods', array('status' => $status), array('id' => $item['id']));

			if (!$status_update) {
				throw new Exception('商品状态修改失败！');
			}

			plog('groups.goods.edit', '修改拼团商品 ' . $item['id'] . ' <br /> 状态: ' . ($status == 0 ? '下架' : '上架'));
		}

		show_json(1, array('url' => referer()));
	}

	public function property()
	{
		global $_W;
		global $_GPC;
		$id = intval($_GPC['id']);
		$type = trim($_GPC['type']);
		$value = intval($_GPC['value']);

		if (in_array($type, array('status', 'displayorder'))) {
			$statusstr = '';

			if ($type == 'status') {
				$typestr = '上下架';
				$statusstr = ($value == 1 ? '上架' : '下架');
			}
			else if ($type == 'displayorder') {
				$typestr = '排序';
				$statusstr = '序号 ' . $value;
			}
			else {
				if ($type == 'isindex') {
					$typestr = '是否首页显示';
					$statusstr = ($value == 1 ? '是' : '否');
				}
			}

			$property_update = pdo_update('ewei_shop_groups_goods', array($type => $value), array('id' => $id, 'uniacid' => $_W['uniacid']));

			if (!$property_update) {
				throw new Exception('' . $typestr . '修改失败');
			}

			plog('groups.goods.edit', '修改拼团商品' . $typestr . '状态   ID: ' . $id . ' ' . $statusstr . ' ');
		}

		show_json(1);
	}
}

?>
