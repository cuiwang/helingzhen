<?php

defined('IN_IA') or exit('Access Denied');

global $_W, $_GPC;
load()->model('mc');

$ops = array('edit', 'delete', 'deleteall', 'showall', 'status', 'copyroom');
$op = in_array(trim($_GPC['op']), $ops) ? trim($_GPC['op']) : 'display';

$store_base_id = intval($_GPC['store_base_id']);
$stores = pdo_getall('storex_bases', array('weid' => $_W['uniacid']), array(), 'id', array('store_type DESC', 'displayorder DESC'));
$store_type = !empty($_GPC['store_type'])? intval($_GPC['store_type']) : 0;
$condition = array('weid' => $_W['uniacid']);
if (!empty($store_base_id)) {
	foreach ($stores as $store_info) {
		if ($store_info['id'] == $store_base_id) {
			$store_type = $store_info['store_type'];
		} else {
			continue;
		}
	}
	$condition['store_base_id'] = $store_base_id;
}
$category = pdo_getall('storex_categorys', $condition, array(), 'id', array('parentid', 'displayorder DESC'));
if (!empty($category)) {
	$parent = $children = array();
	foreach ($category as $cid => $cate) {
		if (!empty($cate['parentid'])) {
			$children[$cate['parentid']][] = $cate;
		} else {
			$parent[$cate['id']] = $cate;
		}
	}
}
if (empty($parent)) {
	message('请先给该店铺添加一级分类！', '', 'error');
}
if (!empty($_GPC['store_base_id'])) {
	if (empty($stores[$_GPC['store_base_id']])) {
		message('抱歉，店铺不存在或是已经删除！', '', 'error');
	}
}
$storex_bases = $stores[$_GPC['store_base_id']];
//根据分类的一级id获取店铺的id
$category_store = pdo_get('storex_categorys', array('id' => intval($_GPC['category']['parentid']), 'weid' => intval($_W['uniacid'])), array('id', 'store_base_id'));
$table = gettablebytype($store_type);
if ($store_type == 1) {
	$store_field = 'hotelid';
} else {
	$store_field = 'store_base_id';
}

if ($op == 'copyroom') {
	$id = intval($_GPC['id']);
	if (empty($store_base_id) || empty($id)) {
		message('参数错误', 'refresh', 'error');
	}
	$store_info = pdo_get('storex_bases', array('id' => $store_base_id, 'weid' => $_W['uniacid']), array('id', 'store_type'));
	if (!empty($store_info)) {
		$table = gettablebytype($store_info['store_type']);
	} else {
		message('店铺不存在！');
	}
	$item = pdo_get($table, array('id' => $id, 'weid' => $_W['uniacid']));
	unset($item['id']);
	$item['status'] = 0;
	pdo_insert($table, $item);
	$id = pdo_insertid();
	$url = $this->createWebUrl('goodsmanage', array('op' => 'edit', 'store_base_id' => $store_base_id, 'id' => $id, 'store_type' => $item['store_type']));
	header("Location: $url");
	exit;
}

if ($op == 'edit') {
	$id = intval($_GPC['id']);
	if (!empty($category_store)){
		$store_base_id = $category_store['store_base_id'];
	}
	$usergroup_list = pdo_getall('mc_groups', array('uniacid' => $_W['uniacid']), array(), '', array('isdefault DESC', 'credit ASC'));
	if (!empty($id)) {
		$item = pdo_fetch("SELECT * FROM " . tablename($table) . " WHERE id = :id", array(':id' => $id));
		$store_base_id = $item[$store_field];
		if (empty($item)) {
			if ($store_type == 1) {
				message('抱歉，房型不存在或是已经删除！', '', 'error');
			} else {
				message('抱歉，商品不存在或是已经删除！', '', 'error');
			}
		}
		$piclist = iunserializer($item['thumbs']);
	}
	if (checksubmit('submit')) {
		if (empty($_GPC['store_base_id'])) {
			message('请选择店铺！', '', 'error');
		}
		if (empty($_GPC['title'])) {
			message('请输入房型！');
		}
		if ($storex_bases['category_set'] == 1) {
			if (empty($_GPC['category']['parentid'])) {
				message('一级分类不能为空！', '', 'error');
			}
		}
		if ($store_type == 1 && empty($_GPC['device'])) {
			message('商品说明不能为空！', '', 'error');
		}
		if (empty($_GPC['oprice']) || $_GPC['oprice'] <= 0 || empty($_GPC['cprice']) || $_GPC['cprice'] <= 0) {
			message('商品原价和优惠价不能为空！', '', 'error');
		}
		$common = array(
			'weid' => $_W['uniacid'],
			'title' => $_GPC['title'],
			'thumb'=>$_GPC['thumb'],
			'oprice' => $_GPC['oprice'],
			'cprice' => $_GPC['cprice'],
			'device' => $_GPC['device'],
			'score' => intval($_GPC['score']),
			'status' => $_GPC['status'],
			'sales' => $_GPC['sales'],
			'can_reserve' => intval($_GPC['can_reserve']),
			'reserve_device' => $_GPC['reserve_device'],
			'can_buy' => intval($_GPC['can_buy']),
			'sortid'=>intval($_GPC['sortid']),
			'sold_num' => intval($_GPC['sold_num']),
			'store_type' => intval($_GPC['store_type'])
		);
		if ($_GPC['store_type'] == 1) {
			$is_house = 1;
		} else {
			$is_house = 2;
		}
		if ($storex_bases['category_set'] == 1) {
			$common['pcate'] = $_GPC['category']['parentid'];
			$common['ccate'] = $_GPC['category']['childid'];
			if (!empty($category) && !empty($category[$_GPC['category']['parentid']])) {
				$is_house = $category[$_GPC['category']['parentid']]['category_type'];
			} else {
				$is_house = 2;
			}
		}
		$goods = array(
			'store_base_id' => $store_base_id,
		);
		$room = array(
			'hotelid' => $store_base_id,
			'breakfast' => $_GPC['breakfast'],
			'area' => $_GPC['area'],
			'area_show' => $_GPC['area_show'],
			'bed' => $_GPC['bed'],
			'bed_show' => $_GPC['bed_show'],
			'bedadd' => $_GPC['bedadd'],
			'bedadd_show' => $_GPC['bedadd_show'],
			'persons' => $_GPC['persons'],
			'persons_show' => $_GPC['persons_show'],
			'floor' => $_GPC['floor'],
			'floor_show' => $_GPC['floor_show'],
			'smoke' => $_GPC['smoke'],
			'smoke_show' => $_GPC['smoke_show'],
			'service' => intval($_GPC['service']),
			'is_house' => $is_house,
		);
	
		if (is_array($_GPC['thumbs'])) {
			$common['thumbs'] = serialize($_GPC['thumbs']);
		} else {
			$common['thumbs'] = serialize(array());
		}
		if ($store_type == 1) {
			$data = array_merge($room, $common);
			if (empty($id)) {
				pdo_insert($table, $data);
			} else {
				pdo_update($table, $data, array('id' => $id));
			}
			pdo_query("UPDATE " . tablename('storex_hotel') . " SET roomcount = (SELECT count(*) FROM " . tablename('storex_room') . " WHERE hotelid = :store_base_id AND is_house = :is_house) WHERE store_base_id = :store_base_id", array(':store_base_id' => $store_base_id, ':is_house' => $data['is_house']));
		} else {
			$data = array_merge($goods, $common);
			if (empty($id)) {
				pdo_insert($table, $data);
			} else {
				pdo_update($table, $data, array('id' => $id));
			}
		}
		message('商品信息更新成功！', $this->createWebUrl('goodsmanage', array('store_type' => $data['store_type'])), 'success');
	}
	include $this->template('room_form');
}

if ($op == 'delete') {
	$id = intval($_GPC['id']);
	pdo_delete($table, array('id' => $id, 'weid' => $_W['uniacid']));
	if ($store_type == 1) {
		pdo_query("UPDATE " . tablename('storex_hotel') . " SET roomcount = (SELECT count(*) FROM " . tablename('storex_room') . " WHERE hotelid = :store_base_id) WHERE store_base_id = :store_base_id", array(':store_base_id' => $store_base_id));
	}
	message('删除成功！', referer(), 'success');
}

if ($op == 'deleteall') {
	foreach ($_GPC['idArr'] as $k => $id) {
		$id = intval($id);
		pdo_delete($table, array('id' => $id, 'weid' => $_W['uniacid']));
		if ($store_type == 1) {
			pdo_query("UPDATE " . tablename('storex_hotel') . " SET roomcount = (SELECT count(*) FROM " . tablename('storex_room') . " WHERE hotelid = :hotelid) WHERE id = :hotelid", array(':hotelid' => $id));
		}
	}
	$this->web_message('删除成功！', '', 0);
	exit();
}
if ($op == 'showall') {
	if ($_GPC['show_name'] == 'showall') {
		$show_status = 1;
	} else {
		$show_status = 0;
	}
	foreach ($_GPC['idArr'] as $k => $id) {
		$id = intval($id);
		if (!empty($id)) {
			pdo_update($table, array('status' => $show_status), array('id' => $id));
		}
	}
	$this->web_message('操作成功！', '', 0);
	exit();
}
if ($op == 'status') {
	$id = intval($_GPC['id']);
	if (empty($id)) {
		message('抱歉，传递的参数错误！', '', 'error');
	}
	$temp = pdo_update($table, array('status' => $_GPC['status']), array('id' => $id));
	if ($temp == false) {
		message('抱歉，刚才操作数据失败！', '', 'error');
	} else {
		message('状态设置成功！', referer(), 'success');
	}
}
if ($op == 'display') {
	$storex_bases = pdo_fetch("SELECT title FROM " . tablename('storex_bases') . " WHERE store_type = :store_type LIMIT 1", array(':store_type' => $store_type));
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$sql = '';
	$params = array();
	if (!empty($_GPC['title'])) {
		$sql .= ' AND r.title LIKE :keywordds';
		$params[':keywordds'] = "%{$_GPC['title']}%";
	}
	if (!empty($_GPC['hoteltitle'])) {
		$sql .= ' AND h.title LIKE :keywords';
		$params[':keywords'] = "%{$_GPC['hoteltitle']}%";
	}
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$hotelid_as = '';
	if ($store_type == 1) {
		$hotelid_as = ' r.hotelid AS store_base_id,';
		$join_condition = ' r.hotelid = h.id ';
	} else {
		$join_condition = ' r.store_base_id = h.id ';
	}
	$list = pdo_fetchall("SELECT r.*, " . $hotelid_as . " h.title AS hoteltitle FROM " . tablename($table) . " r LEFT JOIN " . tablename('storex_bases') . " h ON " . $join_condition . " WHERE r.weid = '{$_W['uniacid']}' $sql ORDER BY h.id, r.sortid DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($table) . " r LEFT JOIN " . tablename('storex_bases') . " h ON " . $join_condition . " WHERE r.weid = '{$_W['uniacid']}' $sql", $params);
	$list = format_list($category, $list);
	$pager = pagination($total, $pindex, $psize);
	include $this->template('room');
}