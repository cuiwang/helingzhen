<?php

defined('IN_IA') or exit('Access Denied');

global $_W, $_GPC;
$ops = array('order_list', 'order_detail', 'orderpay', 'cancel', 'confirm_goods', 'order_comment');
$op = in_array($_GPC['op'], $ops) ? trim($_GPC['op']) : 'error';

check_params();
$uid = mc_openid2uid($_W['openid']);
if ($op == 'order_list') {
	$field = array('id', 'weid', 'hotelid', 'roomid', 'style', 'nums', 'sum_price', 'status', 'paystatus', 'paytype', 'mode_distribute', 'goods_status', 'openid', 'action', 'track_number', 'express_name');
	$orders = pdo_getall('storex_order', array('weid' => intval($_W['uniacid']), 'openid' => $_W['openid']), $field, '', 'time DESC');
	$order_list = array(
		'over' => array(),
		'unfinish' => array(),
	);
	if (!empty($orders)) {
		$store_base = pdo_getall('storex_bases', array('weid' => intval($_W['uniacid'])), array('id', 'store_type'));
		if (!empty($store_base)) {
			$stores = array();
			foreach ($store_base as $val) {
				$stores[$val['id']] = $val['store_type'];
			}
			foreach ($orders as $k => $info) {
				if (isset($stores[$info['hotelid']])) {
					$orders[$k]['store_type'] = $stores[$info['hotelid']];
				}
			}
		}
		foreach ($orders as $k => $info) {
			if (isset($info['store_type'])) {
				if ($info['store_type'] == 1) {
					$goods_info = pdo_get('storex_room', array('weid' => intval($_W['uniacid']), 'id' => $info['roomid']), array('id', 'thumb'));
				} else {
					$goods_info = pdo_get('storex_goods', array('weid' => intval($_W['uniacid']), 'id' => $info['roomid']), array('id', 'thumb'));
				}
				if (!empty($goods_info)) {
					$info['thumb'] = tomedia($goods_info['thumb']);
				} else {
					continue;
				}
			} else {
				continue;
			}
			$info = orders_check_status($info);
			if ($info['status'] == 3) {
				$order_list['over'][] = $info;
			} else {
				$order_list['unfinish'][] = $info;
			}
		}
	}
	message(error(0, $order_list), '', 'ajax');
}

if ($op == 'order_detail') {
	$id = intval($_GPC['id']);
	$order_info = pdo_get('storex_order', array('weid' => intval($_W['uniacid']), 'id' => $id, 'openid' => $_W['openid']));
	if (empty($order_info)) {
		message(error(-1, '找不到该订单了'), '', 'ajax');
	}
	//时间戳转换
	$order_info['btime'] = date('Y-m-d', $order_info['btime']);
	$order_info['etime'] = date('Y-m-d', $order_info['etime']);
	$order_info['time'] = date('Y-m-d', $order_info['time']);
	if (!empty($order_info['mode_distribute'])) {
		$order_info['order_time'] = date('Y-m-d', $order_info['order_time']);//自提或配送时间
	}
	$store_info = pdo_get('storex_bases', array('weid' => intval($_W['uniacid']), 'id' => $order_info['hotelid']), array('id', 'title', 'store_type'));
	$order_info['store_info'] = $store_info;
	$order_info['store_type'] = $store_info['store_type'];
	if ($order_info['store_type'] == 1) {
		$goods_info = pdo_get('storex_room', array('id' => $order_info['roomid'], 'weid' => $order_info['weid']));
		$order_info['is_house'] = $goods_info['is_house'];
	} else {
		$goods_info = pdo_get('storex_goods', array('id' => $order_info['roomid'], 'weid' => $order_info['weid']));
	}
	$order_info['thumb'] = tomedia($goods_info['thumb']);
	if (!empty($order_info['addressid'])) {
		$order_address = pdo_get('mc_member_address', array('uid' => $uid, 'uniacid' => intval($_W['uniacid']), 'id' => $order_info['addressid']));
		if (!empty($order_address)) {
			$order_info['address'] = $order_address['province'] . $order_address['city'] . $order_address['district'] . $order_address['address'];
		}
	}
	//订单状态
	$order_info = orders_check_status($order_info);
	message(error(0, $order_info), '', 'ajax');
}

if ($op == 'orderpay') {
	$order_id = intval($_GPC['id']);
	$params = pay_info($order_id);
	$pay_info = $this->pay($params);
	message(error(0, $pay_info), '', 'ajax');
}

if ($op == 'cancel') {
	$id = intval($_GPC['id']);
	$order_info = pdo_get('storex_order', array('weid' => intval($_W['uniacid']), 'id' => $id, 'openid' => $_W['openid']));
	$store_info = pdo_get('storex_bases', array('weid' => intval($_W['uniacid']), 'id' => $order_info['hotelid']), array('id', 'store_type'));
	$order_info['store_type'] = $store_info['store_type'];
	$setting = pdo_get('storex_set', array('weid' => intval($_W['uniacid'])));
	if ($setting['refund'] == 1) {
		message(error(-1, '该店铺不能取消订单！'), '', 'ajax');
	}
	$order_info = orders_check_status($order_info);
	if ($order_info['is_cancle'] == 2 || $order_info['status'] == 3) {
		message(error(-1, '该订单不能取消！'), '', 'ajax');
	}
	pdo_update('storex_order', array('status' => -1), array('id' => $id, 'weid' => $_W['uniacid']));
	if (!empty($order_info['coupon'])) {
		pdo_update('storex_coupon_record', array('status' => 1), array('id' => $order_info['coupon']));
	}	
	message(error(0, '订单成功取消！'), '', 'ajax');
}

if ($op == 'confirm_goods') {
	$id = intval($_GPC['id']);
	$order_info = pdo_get('storex_order', array('weid' => intval($_W['uniacid']), 'id' => $id, 'openid' => $_W['openid']));
	$store_info = pdo_get('storex_bases', array('weid' => intval($_W['uniacid']), 'id' => $order_info['hotelid']), array('id', 'store_type'));
	$order_info['store_type'] = $store_info['store_type'];
	$order_info = orders_check_status($order_info);
	if ($order_info['status'] == -1) {
		message(error(-1, '该订单已经取消了！'), '', 'ajax');
	}
	if ($order_info['status'] == 3) {
		message(error(-1, '该订单已经完成了！'), '', 'ajax');
	}
	if ($order_info['mode_distribute'] == 1) {
		message(error(-1, '订单方式不是配送！'), '', 'ajax');
	}
	pdo_update('storex_order', array('goods_status' => 3), array('id' => $id, 'weid' => $_W['uniacid']));
	if (!empty($order_info['coupon'])) {
		pdo_update('storex_coupon_record', array('status' => 3), array('id' => $order_info['coupon']));
	}
	message(error(0, '订单收货成功！'), '', 'ajax');
}

if ($op == 'order_comment') {
	$id = intval($_GPC['id']);
	$comment_level = intval($_GPC['comment_level']);
	$comment = trim($_GPC['comment']);
	if (empty($comment)) {
		message(error(-1, '评价不能为空！'), '', 'ajax');
	}
	$order_info = pdo_get('storex_order', array('weid' => intval($_W['uniacid']), 'id' => $id, 'openid' => $_W['openid']));
	if (empty($order_info)) {
		message(error(-1, '找不到该订单了！'), '', 'ajax');
	}
	if ($comment_level > 5 || $comment_level < 1 || empty($comment_level)) {
		$comment_level = 5;
	}
	if ($order_info['status'] == 3 && $order_info['comment'] == 0) {
		$comment_info = array(
			'uniacid' => $_W['uniacid'],
			'uid' => $uid,
			'createtime' => time(),
			'comment' => $comment,
			'hotelid' => $order_info['hotelid'],
			'goodsid' => $order_info['roomid'], 
			'comment_level' => $comment_level,
		);
		pdo_insert('storex_comment', $comment_info);
		pdo_update('storex_order', array('comment' => 1), array('weid' => $_W['uniacid'], 'id' => $id));
		message(error(0, '评论成功！'), '', 'ajax');
	} else {
		message(error(-1, '订单已经评价过了！'), '', 'ajax');
	}
}