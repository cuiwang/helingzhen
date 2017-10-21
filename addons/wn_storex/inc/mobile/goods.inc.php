<?php

defined('IN_IA') or exit('Access Denied');

global $_W, $_GPC;
$ops = array('goods_info', 'info', 'order');
$op = in_array($_GPC['op'], $ops) ? trim($_GPC['op']) : 'error';

check_params();
mload()->model('activity');
mload()->model('card');
$uid = mc_openid2uid($_W['openid']);
$store_id = intval($_GPC['id']);//店铺id
$goodsid = intval($_GPC['goodsid']);//商品id
$max_room = 8;

//获取某个商品的详细信息
if ($op == 'goods_info') {
	$id = intval($_GPC['id']);
	$store_info = get_store_info($id);
	$condition = array('weid' => intval($_W['uniacid']), 'id' => $goodsid, 'status' => 1);
	if ($store_info['store_type'] == 1) {
		$condition['hotelid'] = $store_id;
		$table = 'storex_room';
	} else {
		$condition['store_base_id'] = $store_id;
		$table = 'storex_goods';
	}
	$goods_info = pdo_get($table, $condition);
	if (empty($goods_info)) {
		message(error(-1, '商品不存在'), '', 'ajax');
	} else {
		if ($goods_info['status'] == 0) {
			message(error(-1, '店铺已隐藏'), '', 'ajax');
		}
	}
	$goods_info['store_type'] = $store_info['store_type'];
	$goods_info['thumbs'] = iunserializer($goods_info['thumbs']);
	if (!empty($goods_info['thumb'])) {
		$goods_info['thumb'] = tomedia($goods_info['thumb']);
	}
	if (!empty($goods_info['thumbs'])) {
		$goods_info['thumbs'] = format_url($goods_info['thumbs']);
	}
	if (!empty($goods_info['reserve_device'])) {
		$goods_info['reserve_device'] = htmlspecialchars_decode($goods_info['reserve_device']);
	}
	if (!empty($goods_info['device'])) {
		$goods_info['device'] = htmlspecialchars_decode($goods_info['device']);
	}
	$goods_info = get_room_params($goods_info);
	if ($store_info['store_type'] == 1) {
		$goods_info = check_price($goods_info);
	}
	// $goods_info['cprice'] = card_discount_price($uid, $goods_info['cprice']);
	message(error(0, $goods_info), '', 'ajax');
}

//进入预定页面的信息
if ($op == 'info') {
	$id = intval($_GPC['id']);
	$store_info = get_store_info($id);
	$member = array();
	$member['from_user'] = $_W['openid'];
	$member['weid'] = intval($_W['uniacid']);
	$record = hotel_member_single($member);
	$info = array();
	if (!empty($record)) {
		$info['name'] = $record['realname'];
		$info['mobile'] = $record['mobile'];
		$info['contact_name'] = $record['realname'];
	}
	$condition = array('weid' => intval($_W['uniacid']), 'id' => $goodsid, 'status' => 1);
	if ($store_info['store_type'] == 1) {
		$condition['hotelid'] = $store_id;
		$table = 'storex_room';
		$goods_info = pdo_get($table, $condition);
		$goods_info = check_price($goods_info);
	} else {
		$condition['store_base_id'] = $store_id;
		$table = 'storex_goods';
		$goods_info = pdo_get($table, $condition);
	}
	$paycenter_couponlist = activity_paycenter_get_coupon();
	// $goods_info['cprice'] = card_discount_price($uid, $goods_info['cprice']);
	$address = pdo_getall('mc_member_address', array('uid' => $uid, 'uniacid' => intval($_W['uniacid'])));
	$infos['info'] = $info;
	$infos['goods_info'] = $goods_info;
	$infos['address'] = $address;
	$infos['coupon_list'] = $paycenter_couponlist;
	$card_activity_info = card_return_credit_info();
	$infos['card_disounts_info'] = array();
	if (!empty($card_activity_info)) {
		$user_group = card_group_id($uid);
		if ($card_activity_info['discount_type'] == 1) {
			$discount_info['discount_type'] = 1;
			$discount_info['condition'] = $card_activity_info['discounts'][$user_group['groupid']]['condition_1'];
			$discount_info['discount'] = $card_activity_info['discounts'][$user_group['groupid']]['discount_1'];
		} elseif ($card_activity_info['discount_type'] == 2) {
			$discount_info['discount_type'] = 2;
			$discount_info['condition'] = $card_activity_info['discounts'][$user_group['groupid']]['condition_2'];
			$discount_info['discount'] = $card_activity_info['discounts'][$user_group['groupid']]['discount_2'];
		}
		$infos['card_disounts_info'] = $discount_info;
	}
	message(error(0, $infos), '', 'ajax');
}

//预定提交预定信息
if ($op == 'order') {
	$id = intval($_GPC['id']);
	$store_info = get_store_info($id);
	$order_info = array(
		'weid' => intval($_W['uniacid']),
		'hotelid' => $store_id,
		'openid' => $_W['openid'],
		'contact_name' => trim($_GPC['order']['contact_name']),//联系人
		'roomid' => $goodsid,					//商品id
		'mobile' => trim($_GPC['order']['mobile']),
		'remark' => trim($_GPC['order']['remark']),		//留言
		'nums' => intval($_GPC['order']['nums']),				//数量
		'time' => TIMESTAMP,					//下单时间（TIMESTAMP）
	);
	$selected_coupon = $_GPC['order']['coupon'];
	if ($selected_coupon['type'] == 3) {
		$coupon_info = activity_get_coupon_info($selected_coupon['couponid']);
		if (empty($coupon_info)) {
			message(error(-1, '卡券信息有误'), '', 'ajax');
		}
	}
	if (empty($order_info['mobile'])) {
		message(error(-1, '手机号码不能为空'), '', 'ajax');
	}
	if (!preg_match(REGULAR_MOBILE, $order_info['mobile'])) {
		message(error(-1, '手机号码格式不正确'), '', 'ajax');
	}
	if ($order_info['nums'] <= 0) {
		message(error(-1, '数量不能是零'), '', 'ajax');
	}
	$action = trim($_GPC['action']);//是预定还是购买
	if ($action == 'reserve') {
		$order_info['action'] = 1;
		$order_info['paytype'] = 3;//支付方式，表示预定，只能到店支付
	} elseif ($action == 'buy') {
		$order_info['action'] = 2;
		$paysetting = uni_setting(intval($_W['uniacid']), array('payment', 'creditbehaviors'));
		$_W['account'] = array_merge($_W['account'], $paysetting);
	}
	$condition = array('weid' => intval($_W['uniacid']), 'id' => $goodsid, 'status' => 1);
	if (empty($order_info['contact_name'])) {
		message(error(-1, '联系人不能为空!'), '', 'ajax');
	}
	$user_info = hotel_get_userinfo();
	$memberid = intval($user_info['id']);
	//预定直接将数据加进order表
	if ($store_info['store_type'] == 1) {
		$table = 'storex_room';
		$condition['hotelid'] = $store_id;
	} else {
		$table = 'storex_goods';
		$condition['store_base_id'] = $store_id;
	}
	$goods_info = pdo_get($table, $condition);
	goods_check_action($action, $goods_info);//检查是否符合条件
	$insert = array(
		'ordersn' => date('md') . sprintf("%04d", $_W['fans']['fanid']) . random(4, 1),
		'memberid' => $memberid,
		'style' => $goods_info['title'],
		'oprice' => $goods_info['oprice'],
		'cprice' => $goods_info['cprice'],
	);
	if ($goods_info['cprice'] == 0) {
		message(error(-1, '商品价格不能是0，请联系管理员!'), '', 'ajax');
	}
	$reply = pdo_get('storex_bases', array('id' => $store_id), array('title', 'mail', 'phone', 'thumb', 'description'));
	if (empty($reply)) {
		message(error(-1, '店铺未找到, 请联系管理员!'), '', 'ajax');
	}
	if ($store_info['store_type'] == 1) {//酒店
		$setInfo = pdo_get('storex_set', array('weid' => intval($_W['uniacid'])), array('weid', 'tel', 'is_unify', 'email', 'template', 'templateid', 'smscode'));
		if ($goods_info['is_house'] == 1) {
			$order_info['btime'] = strtotime($_GPC['order']['btime']);
			$order_info['etime'] = strtotime($_GPC['order']['etime']);
			if (!empty($_GPC['order']['day'])) {
				$order_info['day'] = intval($_GPC['order']['day']);
			} else {
				$order_info['day'] = ceil(($order_info['etime'] - $order_info['btime'])/86400);
			}
			if ($order_info['day'] <= 0) {
				message(error(-1, '天数不能是零'), '', 'ajax');
			}
			if ($order_info['btime'] < strtotime('today')) {
				message(error(-1, '预定的开始日期不能小于当日的日期!'), '', 'ajax');
			}
			if ($max_room < $order_info['nums']) {
				message(error(-1, '订单购买数量超过最大限制!'), '', 'ajax');
			}
			$btime = $order_info['btime'];
			$bdate = date('Y-m-d', $order_info['btime']);
			$days = $order_info['day'];
			$etime = $order_info['etime'];
			$edate = date('Y-m-d', $order_info['etime']);
			$dates = get_dates($bdate, $days);
			//酒店信息
			$sql = 'SELECT `id`, `roomdate`, `num`, `status` FROM ' . tablename('storex_room_price') . ' WHERE `roomid` = :roomid
				AND `roomdate` >= :btime AND `roomdate` < :etime AND `status` = :status';
			$params = array(':roomid' => $goodsid, ':btime' => $btime, ':etime' => $etime, ':status' => '1');
			$room_date_list = pdo_fetchall($sql, $params);
			$flag = intval($room_date_list);
			$list = array();
			if ($flag == 1) {
				for($i = 0; $i < $days; $i++) {
					$k = $dates[$i]['time'];
					foreach ($room_date_list as $p_key => $p_value) {
						// 判断价格表中是否有当天的数据
						if ($p_value['roomdate'] == $k) {
							if ($p_value['num'] == -1) {
								$max_room = 8;
							} else {
								$room_num = $p_value['num'];
								if (empty($room_num)) {
									$max_room = 0;
									$list['num'] = 0;
									$list['date'] =  $dates[$i]['date'];
								} elseif ($room_num > 0 && $room_num <= $max_room) {
									$max_room = $room_num;
									$list['num'] =  $room_num;
									$list['date'] =  $dates[$i]['date'];
								} elseif ($room_num > 0 && $room_num > $max_room) {
									$list['num'] =  $max_room;
									$list['date'] =  $dates[$i]['date'];
								} else {
									$max_room = 0;
								}
							}
							break;
						}
					}
					if ($max_room == 0 || $max_room < $order_info['nums']) {
						message(error(-1, '房间数量不足,请选择其他房型或日期!'), '', 'ajax');
					}
				}
			}
			$r_sql = 'SELECT `roomdate`, `num`, `oprice`, `cprice`, `status` FROM ' . tablename('storex_room_price') .
			' WHERE `roomid` = :roomid AND `weid` = :weid AND `hotelid` = :hotelid AND `roomdate` >= :btime AND ' .
			' `roomdate` < :etime  ORDER BY roomdate DESC';
			$params = array(':roomid' => $goodsid, ':weid' => intval($_W['uniacid']), ':hotelid' => $store_id, ':btime' => $btime, ':etime' => $etime);
			$price_list = pdo_fetchall($r_sql, $params);
			if (!empty($price_list)) {
				//价格表中存在
				foreach ($price_list as $k => $v) {
					$goods_info['oprice'] = $v['oprice'];
					$goods_info['cprice'] = $v['cprice'];
				}
			}
			if ($order_info['nums'] > $max_room) {
				message(error(-1, '您的预定数量超过最大限制!'), '', 'ajax');
			}
			if ($setInfo['smscode'] == 1) {
				$code = pdo_get('storex_code', array('mobile' => $mobile, 'weid' => intval($_W['uniacid'])), array('code'));
				if ($mobilecode != $code['code']) {
					message(error(-1, '您的验证码错误，请重新输入!'), '', 'ajax');
				}
			}
			$insert = array_merge($order_info, $insert);
			$insert['sum_price'] = ($goods_info['cprice'] + $goods_info['service']) * $days * $insert['nums'];
			pdo_query('UPDATE ' . tablename('storex_order') . " SET status = '-1' WHERE time < :time AND weid = :weid AND paystatus = '0' AND status <> '1' AND status <> '3'", array(':time' => time() - 86400, ':weid' => $_W['uniacid']));
			$order_exist = pdo_get('storex_order', array('hotelid' => $insert['hotelid'], 'roomid' => $insert['roomid'], 'openid' => $insert['openid'], 'status' => 0));
			if ($order_exist) {
				//message(error(0, "您有未完成订单,不能重复下单"), '', 'ajax');
			}
		} else {
			$insert = array_merge($order_info, $insert);
			$insert['sum_price'] = $goods_info['cprice'] * $insert['nums'];
		}
	} else {
		$order_info['mode_distribute'] = intval($_GPC['order']['mode_distribute']);
		if (empty($_GPC['order']['order_time'])) {
			message(error(-1, '请选择时间！'), '', 'ajax');
		}
		$order_info['order_time'] = strtotime(intval($_GPC['order']['order_time']));
		if ($order_info['mode_distribute'] == 2) {//配送
			if (empty($_GPC['order']['addressid'])) {
				message(error(-1, '地址不能为空！'), '', 'ajax');
			}
			$order_info['addressid'] = intval($_GPC['order']['addressid']);
			$order_info['goods_status'] = 1; //到货确认  1未发送， 2已发送 ，3已收货
		}
		$insert = array_merge($order_info, $insert);
		$insert['sum_price'] = $insert['cprice'] * $insert['nums'];
	}
	//根据优惠方式计算总价
	if ($selected_coupon['type'] == 3) {
		$extra_info = $coupon_info['extra'];
		if ($coupon_info['type'] == COUPON_TYPE_DISCOUNT) {
			$insert['sum_price'] = $insert['sum_price'] * $extra_info['discount'] / 100;
		} elseif ($coupon_info['type'] == COUPON_TYPE_CASH) {
			$least_cost = $extra_info['least_cost'] * 0.01;
			$reduce_cost = $extra_info['reduce_cost'] * 0.01;
			if ($insert['sum_price'] >= $least_cost) {
				$insert['sum_price'] = $insert['sum_price'] - $reduce_cost;
			}
		}
		$insert['coupon'] = $selected_coupon['recid'];
	} elseif ($selected_coupon['type'] == 2) {
		$insert['sum_price'] = card_discount_price($uid, $insert['sum_price']);
	}
	$insert['sum_price'] = sprintf ('%1.2f', $insert['sum_price']);
	$post_total = trim($_GPC['order']['total']);
	if ($post_total != $insert['sum_price']) {
		message(error(-1, '价格错误'), '', 'ajax');
	}
	if ($insert['sum_price'] <= 0) {
		message(error(-1, '总价为零，请联系管理员！'), '', 'ajax');
	}
	if ($selected_coupon['type'] == 3) {
		$result = activity_coupon_consume($selected_coupon['couponid'], $selected_coupon['recid'], $store_info['id']);
		if (is_error($result)) {
			message($result, '', 'ajax');
		}
	}
	pdo_insert('storex_order', $insert);
	$order_id = pdo_insertid();
	if ($store_info['store_type'] == 1 && $goods_info['is_house'] == 1) {
		//如果有接受订单的邮件,
		if (!empty($reply['mail'])) {
			$subject = "微信公共帐号 [" . $_W['account']['name'] . "] 万能小店订单提醒.";
			$body = "您后台有一个预定订单: <br/><br/>";
			$body .= "预定店铺: " . $reply['title'] . "<br/>";
			$body .= "预定商品: " . $goods_info['title'] . "<br/>";
			$body .= "预定数量: " . $insert['nums'] . "<br/>";
			$body .= "预定价格: " . $insert['sum_price'] . "<br/>";
			$body .= "预定人: " . $insert['contact_name'] . "<br/>";
			$body .= "预定电话: " . $insert['mobile'] . "<br/>";
			$body .= "到店时间: " . $bdate . "<br/>";
			$body .= "离店时间: " . $edate . "<br/><br/>";
			$body .= "请您到管理后台仔细查看. <a href='" . $_W['siteroot'] . create_url('member/login') . "' target='_blank'>立即登录后台</a>";
			load()->func('communication');
			ihttp_email($reply['mail'], $subject, $body);
		}
		$order = pdo_get('storex_order', array('id' => $order_id, 'weid' => intval($_W['uniacid'])));
		//订单下单成功减库存
		$starttime = $insert['btime'];
		for ($i = 0; $i < $insert['day']; $i++) {
			$day = pdo_get('storex_room_price', array('weid' => intval($_W['uniacid']), 'roomid' => $insert['roomid'], 'roomdate' => $starttime));
			if ($day && $day['num'] != -1) {
				pdo_update('storex_room_price', array('num' => $day['num'] - $insert['nums']), array('id' => $day['id']));
			}
			$starttime += 86400;
		}
	}
	if ($action == 'reserve') {
		$acc = WeAccount::create($_W['acid']);
		$setInfo = pdo_get('storex_set', array('weid' => $_W['uniacid']), array('email', 'mobile', 'nickname', 'template', 'confirm_templateid', 'templateid'));
		$clerk = array();
		if (!empty($setInfo['nickname'])) {
			$from_user = pdo_get('mc_mapping_fans', array('nickname' => $setInfo['nickname'], 'uniacid' => $_W['uniacid']));
			if (!empty($from_user)) {
				$clerk['from_user'] = $from_user['openid'];
			}
		}
		$info = '店铺有新的订单,为保证用户体验度，请及时处理!';
		$custom = array(
			'msgtype' => 'text',
			'text' => array('content' => urlencode($info)),
			'touser' => $clerk['from_user'],
		);
		$status = $acc->sendCustomNotice($custom);
	}
	pdo_update('storex_member', array('mobile' => $insert['mobile'], 'realname' => $insert['contact_name']), array('weid' => intval($_W['uniacid']), 'from_user' => $_W['openid']));
	goods_check_result($action, $order_id);
}
