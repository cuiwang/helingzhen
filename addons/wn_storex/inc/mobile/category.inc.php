<?php

defined('IN_IA') or exit('Access Denied');

global $_W, $_GPC;
$ops = array('category_list', 'goods_list', 'more_goods', 'class', 'sub_class');
$op = in_array($_GPC['op'], $ops) ? trim($_GPC['op']) : 'error';

check_params();

//获取店铺分类
if ($op == 'category_list') {
	$pcate_lists = pdo_getall('storex_categorys', array('weid' => $_W['uniacid'], 'parentid' => '0', 'store_base_id' => intval($_GPC['id']), 'enabled' => 1), array('id', 'name', 'category_type'), '', 'displayorder DESC');
	if (!empty($pcate_lists)) {
		foreach ($pcate_lists as $val) {
			$storex_categorys[$val['id']] = $val['name'];
		}
	}
	message(error(0, $storex_categorys), '', 'ajax');
}
//获取一级分类下的二级分类以及商品
if ($op == 'goods_list') {
	$store_id = intval($_GPC['id']);
	$store_info = get_store_info($store_id);
	$first_id = intval($_GPC['first_id']);
	$first_class = pdo_get('storex_categorys', array('weid' => $_W['uniacid'], 'store_base_id' => $store_id, 'id' => $first_id));
	if (empty($first_class)) {
		message(error(-1, '分类不存在'), '', 'ajax');
	}
	$can_reserve = intval($_GPC['can_reserve']);
	$sub_class = category_sub_class();
	//存在二级分类就找其下的商品
	$fields = array('id', 'title', 'thumb', 'oprice', 'cprice', 'sold_num', 'sales');
	$list = array();
	$goods = array();
	if (!empty($sub_class)) {
		$goods = $sub_class;
		$list['have_subclass'] = 1;
		$condition = array('weid' => $_W['uniacid'], 'pcate' => $first_id, 'status' => 1);
		if (!empty($can_reserve)) {
			$condition['can_reserve'] = 1;
		}
		if ($store_info['store_type'] == 1) {//酒店
			$condition['hotelid'] = $store_id;
			$fields[] = 'hotelid';
			foreach ($sub_class as $key => $sub_classinfo) {
				$condition['ccate'] = $sub_classinfo['id'];
				$goods_list = category_store_goods('storex_room', $condition, $fields);
				if (!empty($goods_list)) {
					$goods[$key]['store_goods'] = array_slice($goods_list, 0, 2);
					$goods[$key]['total'] = count($goods_list);
				}
			}
		} else {
			$fields[] = 'store_base_id';
			$condition['store_base_id'] = $store_id;
			foreach ($sub_class as $key => $sub_classinfo) {
				$condition['ccate'] = $sub_classinfo['id'];
				$goods_list = category_store_goods('storex_goods', $condition, $fields);
				if (!empty($goods_list)) {
					$goods[$key]['store_goods'] = array_slice($goods_list, 0, 2);
					$goods[$key]['total'] = count($goods_list);
				}
			}
		}
	} else {
		$list['have_subclass'] = 0;
		$condition = array('weid' => $_W['uniacid'], 'pcate' => $first_id, 'status' => 1);
		if (!empty($can_reserve)) {
			$condition['can_reserve'] = 1;
		}
		if ($store_info['store_type'] == 1) {
			$fields[] = 'hotelid';
			$condition['hotelid'] = $store_id;
			$goods_list = category_store_goods('storex_room', $condition, $fields);
			if (!empty($goods_list)) {
				$goods['store_goods'] = array_slice($goods_list, 0, 2);
				$goods['total'] = count($goods_list);
			}
		} else {
			$fields[] = 'store_base_id';
			$condition['store_base_id'] = $store_id;
			$goods_list = category_store_goods('storex_goods', $condition, $fields);
			if (!empty($goods_list)) {
				$goods['store_goods'] = array_slice($goods_list, 0, 2);
				$goods['total'] = count($goods_list);
			}
		}
	}
	$list['list'] = $goods;
	message(error(0, $list), '', 'ajax');
}

//获取更多的商品信息
if ($op == 'more_goods') {
	$store_id = intval($_GPC['id']);
	$storex_bases = get_store_info($store_id);
	$condition = array();
	if ($storex_bases['category_set'] == 1) {
		$sub_classid = intval($_GPC['sub_id']);
		$category = pdo_get('storex_categorys', array('id' => $sub_classid, 'weid' => $_W['uniacid'], 'store_base_id' => $store_id), array('id', 'parentid'));
		if (empty($category)) {
			message(error(-1, '参数错误'), '', 'ajax');
		}
		if ($category['parentid'] == 0) {
			$sub_category = pdo_getall('storex_categorys', array('parentid' => $sub_classid, 'weid' => $_W['uniacid'], 'store_base_id' => $store_id), array('id', 'parentid'));
			if (!empty($sub_category)) {
				message(error(-1, '参数错误'), '', 'ajax');
			}
			$condition['pcate'] = $sub_classid;
		} else {
			$condition['ccate'] = $sub_classid;
		}
	}
	$can_reserve = intval($_GPC['can_reserve']);
	if (!empty($can_reserve)) {
		$condition['can_reserve'] = 1;
	}
	$keyword = trim($_GPC['keyword']);
	if (!empty($keyword)) {
		$condition['title LIKE'] = "%{$keyword}%";
	}
	$condition['status'] = 1;
	if ($storex_bases['store_type'] == 1) {
		$condition['hotelid'] = $storex_bases['id'];
		$goods_list = pdo_getall('storex_room', $condition);
		if (!empty($goods_list)) {
			$goods_list = category_room_status($goods_list);
			$goods_list = room_special_price($goods_list);
		}
	} else {
		$condition['store_base_id'] = $storex_bases['id'];
		$goods_list = pdo_getall('storex_goods', $condition);
	}
	if (!empty($goods_list)) {
		foreach ($goods_list as &$goods_info) {
			if ($goods_info['oprice'] > $goods_info['cprice']) {
				$goods_info['reduced_price'] = bcsub($goods_info['oprice'], $goods_info['cprice'], 2);
			} else {
				$goods_info['reduced_price'] = 0;
			}
		}
	}
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$list = array();
	$total = count($goods_list);
	if ($total <= $psize) {
		$list['list'] = $goods_list;
	} else {
		// 需要分页
		if ($pindex > 0) {
			$list_array = array_chunk($goods_list, $psize, true);
			if (!empty($list_array[($pindex-1)])) {
				foreach ($list_array[($pindex-1)] as $val) {
					$list['list'][] = $val;
				}
			}
		} else {
			$list['list'] = $goods_list;
		}
	}
	$list['psize'] = $psize;
	$list['result'] = 1;
	$page_array = get_page_array($total, $pindex, $psize);
	ob_start();
	$list['code'] = ob_get_contents();
	ob_clean();
	$list['total'] = $total;
	$list['isshow'] = $page_array['isshow'];
	if ($page_array['isshow'] == 1) {
		$list['nindex'] = $page_array['nindex'];
	}
	if (!empty($list['list'])) {
		foreach ($list['list'] as $k => $info) {
			$list['list'][$k]['thumb'] = tomedia($info['thumb']);
			$list['list'][$k]['thumbs'] = format_url(iunserializer($info['thumbs']));
		}
	}
	message(error(0, $list), '', 'ajax');
}
//获取该店铺下的一级分类
if ($op == 'class') {
	$id = intval($_GPC['id']);
	$sql = "SELECT count(*) num, parentid FROM " . tablename('storex_categorys') . " WHERE weid = {$_W['uniacid']} AND parentid != 0 AND store_base_id = {$id} group by parentid";
	$class = pdo_fetchall($sql);
	if (!empty($class)) {
		$sub_class = array();
		foreach ($class as $val) {
			$sub_class[$val['parentid']] = $val;
		}
	}
	$pcate_lists = pdo_getall('storex_categorys', array('weid' => intval($_W['uniacid']), 'parentid' => '0', 'store_base_id' => $id, 'enabled' => 1), array('id', 'name', 'thumb', 'category_type'), '', 'displayorder DESC');
	if (!empty($pcate_lists)) {
		foreach ($pcate_lists as $key => $val) {
			if (!empty($val['thumb'])) {
				$pcate_lists[$key]['thumb'] = tomedia($val['thumb']);
			}
			if (!empty($sub_class[$val['id']]) && $sub_class[$val['id']]['num'] > 0) {
				$pcate_lists[$key]['is_child'] = 1;
			} else {
				$pcate_lists[$key]['is_child'] = 0;
			}
		}
	}
	message(error(0, $pcate_lists), '', 'ajax');
}
//获取一级分类下的二级分类列表
if ($op == 'sub_class') {
	$id = intval($_GPC['id']);
	$class = pdo_get('storex_categorys', array('weid' => intval($_W['uniacid']), 'id' => $id), array('id', 'store_base_id', 'name'));
	$sub_class = pdo_getall('storex_categorys', array('weid' => intval($_W['uniacid']), 'parentid' => $id), array('id', 'store_base_id', 'name', 'thumb', 'category_type'), '', 'displayorder DESC');
	if (empty($sub_class)) {
		message(error(-1, '无子分类'), '', 'ajax');
	} else {
		foreach ($sub_class as $k => $info) {
			if (!empty($info['thumb'])) {
				$sub_class[$k]['thumb'] = tomedia($info['thumb']);
				$sub_class[$k]['is_child'] = 0;
			}
		}
		$list['list'] = $sub_class;
		$list['class'] = $class;
		message(error(0, $list), '', 'ajax');
	}
}