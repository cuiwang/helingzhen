<?php
//检查每个文件的传值是否为空
function check_params() {
	global $_W, $_GPC;
	if (!empty($_GPC['userid'])) {
		$user_info = pdo_get('mc_mapping_fans', array('uid' => $_GPC['userid']), array('openid', 'uid'));
		$_W['openid'] = $user_info['openid'];
	}
	$permission_lists = array(
		'common' => array(
			'uniacid' => intval($_W['uniacid'])
		),
		'user' => array(
			'login' => array(),
			'register' => array(),
		),
		'store' => array(
			'common' => array(
				'uniacid' => intval($_W['uniacid'])
			),
			'store_list' => array(),
			'store_detail' => array(
				'store_id' => intval($_GPC['store_id'])
			),
			'store_comment' => array(
				'id' => intval($_GPC['id']),
			),
		),
		'category' => array(
			'common' => array(
				'uniacid' => intval($_W['uniacid']),
				'id' => intval($_GPC['id'])
			),
			'category_list' => array(),
			'goods_list' => array(
				'first_id' => intval($_GPC['first_id'])
			),
			'more_goods' => array(
				'id' => intval($_GPC['id']),
			),
			'class' => array(
				'id' => intval($_GPC['id']),
			),
			'sub_class' => array(
				'id' => intval($_GPC['id']),
			),
		),
		'goods' => array(
			'common' => array(
				'uniacid' => intval($_W['uniacid']),
 				'openid' => $_W['openid']
			),
			'goods_info' => array(
				'id' => intval($_GPC['id']),
				'goodsid' => intval($_GPC['goodsid'])
			),
			'info' => array(),
			'order' => array(
				'id' => intval($_GPC['id']),
				'goodsid' => intval($_GPC['goodsid']),
				'action' => trim($_GPC['action'])
			)
		),
		'orders' => array(
			'common' => array(
				'uniacid' => intval($_W['uniacid']),
 				'openid' => $_W['openid']
			),
			'order_list' => array(),
			'order_detail' => array(
				'id' => intval($_GPC['id']),
			),
			'orderpay' => array(
				'id' => intval($_GPC['id']),
			),
			'cancel' => array(
				'id' => intval($_GPC['id']),
			),
			'confirm_goods' => array(
				'id' => intval($_GPC['id']),
			),
			'order_comment' => array(
				'id' => intval($_GPC['id']),
			)
		),
		'usercenter' => array(
			'common' => array(
				'uniacid' => intval($_W['uniacid']),
				'openid' => $_W['openid']
			),
			'personal_info' => array(),
			'personal_update' => array(),
			'credits_record' => array(
				'credittype' => $_GPC['credittype']
			),
			'address_lists' => array(),
			'current_address' => array(
				'id' => intval($_GPC['id'])
			),
			'address_post' => array(),
			'address_default' => array(
				'id' => intval($_GPC['id'])
			),
			'address_delete' => array(
				'id' => intval($_GPC['id'])
			),
			'extend_switch' => array(),
		),
		'clerk' => array(
			'common' => array(
				'uniacid' => intval($_W['uniacid']),
				'openid' => $_W['openid'],
			),
			'clerkindex' => array(),
			'order' => array(),
			'order_info' => array(
				'orderid' => $_GPC['orderid'],
			),
			'edit_order' => array(
				'orderid' => $_GPC['orderid'],
			),
			'room' => array(),
			'room_info' => array(
				'room_id' => $_GPC['room_id'],
			),
			'edit_room' => array(
				'room_id' => $_GPC['room_id'],
			),
			'permission_storex' => array(
				'type' => $_GPC['type'],
			),
		),
		'sign' => array(
			'common' => array(
				'uniacid' => intval($_W['uniacid']),
				'openid' => $_W['openid'],
			),
			'sign_info' => array(),
			'sign' => array(
				'day' => intval($_GPC['day']),
			),
			'remedy_sign' => array(
				'day' => intval($_GPC['day']),
			),
		),
		'notice' => array(
			'common' => array(
				'uniacid' => intval($_W['uniacid']),
				'openid' => $_W['openid'],
			),
			'notice_list' => array(),
			'read_notice' => array(
				'id' => intval($_GPC['id']),
			),
			'get_info' => array(),
		),
		'membercard' => array(
			'common' => array(
				'uniacid' => intval($_W['uniacid']),
				'openid' => $_W['openid'],
			),
			'receive_info' => array(),
			'receive_card' => array(),
		),
		'coupon' => array(
			'common' => array(
				'uniacid' => intval($_W['uniacid']),
				'openid' => $_W['openid'],
			),
			'exchange' => array(
				'id' => intval($_GPC['id']),
			),
			'mine' => array(),
			'detail' => array(
				'couponid' => intval($_GPC['couponid']),
				'id' => intval($_GPC['recid']),
			),
		),
		'recharge' => array(
			'common' => array(
				// 'uniacid' => intval($_W['uniacid']),
				// 'openid' => $_W['openid']
			),
		)
	);
	$do = trim($_GPC['do']);
	$op = trim($_GPC['op']);
	if (!empty($permission_lists[$do])) {
		if (!empty($permission_lists[$do]['common'])) {
			foreach ($permission_lists[$do]['common'] as $val) {
				if (empty($val)) {
					if ($val == 'openid') {
						message(error(41009, '未登录！'), '', 'ajax');
					}
					message(error(-1, '参数错误'), '', 'ajax');
				}
			}
		}
		if (!empty($permission_lists[$do][$op])) {
			foreach ($permission_lists[$do][$op] as $val) {
				if (empty($val)) {
					message(error(-1, '参数错误'), '', 'ajax');
				}
			}
		}
	}
}

/**
 * action 1预定  2购买
 * 获取订单的状态 
 */
function orders_check_status($item) {
	$order_status_text = array(
		'1' => '待付款',
		'2' => '等待店铺确认',
		'3' => '订单已取消',
		'4' => '正在退款中',
		'5' => '待入住',
		'6' => '店铺已拒绝',
		'7' => '已退款',
		'8' => '已入住',
		'9' => '已完成',
		'10' => '未发货',
		'11' => '已发货',
		'12' => '已收货',
		'13' => '订单已确认'
	);
	//1是显示,2不显示
	$item['is_pay'] = 2;//立即付款 is_pay
	$item['is_cancle'] = 2;//取消订单is_cancle
	$item['is_confirm'] = 2;//确认收货is_confirm
	$item['is_over'] = 2;//再来一单is_over
	$item['is_comment'] = 2;//显示评价is_comment
	if ($item['status'] == 0) {
		if ($item['action'] == 1) {
			$status = STORE_SURE_STATUS;
		} else {
			if ($item['paystatus'] == 0) {
				$status = STORE_UNPAY_STATUS;
				$item['is_pay'] = 1;
			} else {
				$status = STORE_SURE_STATUS;
			}
		}
		$item['is_cancle'] = 1;
	} elseif ($item['status'] == -1) {
		if ($item['paystatus'] == 0) {
			$status = STORE_CANCLE_STATUS;
			$item['is_over'] = 1;
		} else {
			$status = STORE_REPAY_STATUS;
		}
	} elseif ($item['status'] == 1) {
		if ($item['store_type'] == 1) {//酒店
			if ($item['action'] == 1) {
				$status = STORE_CONFIRM_STATUS;
				$item['is_cancle'] = 1;
			} else {
				$status = STORE_UNLIVE_STATUS;
				$item['is_cancle'] = 1;
				if ($item['paystatus'] == 0) {
					$item['is_pay'] = 1;
				}
			}
		} else {
			if ($item['action'] == 1 || $item['paystatus'] == 1) {//预定
				if ($item['mode_distribute'] == 1) {//自提
					$item['is_cancle'] = 1;
					$status = STORE_CONFIRM_STATUS;
				} elseif ($item['mode_distribute'] == 2) {
					if ($item['goods_status'] == 1) {
						$item['is_cancle'] = 1;
						$status = STORE_UNSENT_STATUS;
					} elseif ($item['goods_status'] == 2) {
						$item['is_confirm'] = 1;
						$status = STORE_SENT_STATUS;
					} elseif ($item['goods_status'] == 3) {
						$status = STORE_GETGOODS_STATUS;
					} else {
						$item['is_cancle'] = 1;
						$status = STORE_CONFIRM_STATUS;
					}
				}
			} else {
				if ($item['paystatus'] == 0) {
					if ($item['mode_distribute'] == 1 ) {//自提
						$item['is_cancle'] = 1;
						$item['is_pay'] = 1;
						$status = STORE_CONFIRM_STATUS;
					} elseif ($item['mode_distribute'] == 2) {
						if ($item['goods_status'] == 1) {
							$item['is_cancle'] = 1;
							$item['is_pay'] = 1;
							$status = STORE_UNSENT_STATUS;
						} elseif ($item['goods_status'] == 2) {
							$item['is_confirm'] = 1;
							$status = STORE_SENT_STATUS;
						} elseif ($item['goods_status'] == 3) {
							$status = STORE_GETGOODS_STATUS;
						} else {
							$item['is_cancle'] = 1;
							$item['is_pay'] = 1;
							$status = STORE_CONFIRM_STATUS;
						}
					}
				} else {
					$status = STORE_REPAY_STATUS;
				}
			}
		}
	} elseif ($item['status'] == 2) {
		if ($item['paystatus'] == 0) {
			$status = STORE_REFUSE_STATUS;
		} else {
			$status = STORE_REPAY_SUCCESS_STATUS;
		}
	} elseif ($item['status'] == 4) {
		$status = STORE_LIVE_STATUS;
		$item['is_over'] = 1;
	} elseif ($item['status'] == 3) {
		$status = STORE_OVER_STATUS;
		$item['is_over'] = 1;
		if ($item['comment'] == 0) {
			$item['is_comment'] = 1;
		}
	}
	$setting = pdo_get('storex_set', array('weid' => intval($_W['uniacid'])));
	if ($setting['refund'] == 1) {
		$item['is_cancle'] = 2;
	}
	$item['order_status'] = $order_status_text[$status];
	return $item;
}

/**店员可操作订单的行为
 * $order  订单信息
 * $store_type  店铺类型
*/
function clerk_order_operation ($order, $store_type) {
	$status = array(
			'is_cancel' => false,	//-1
			'is_confirm' => false,	//1
			'is_refuse' => false,	//2
			'is_over' => false,		//3
			'is_send' => false,		//goods_status 2
			'is_access' => false,	//4
	);
	if ($order['status'] == -1 || $order['status'] == 3 || $order['status'] == 2) {
		$status = array();
	} elseif ($order['status'] == 1) {
		if ($store_type == 1) {
			$status['is_access'] = true;
		} else {
			if ($order['mode_distribute'] == 2) {//配送
				if ($order['goods_status'] == 1 || empty($order['goods_status'])) {
					$status['is_send'] = true;
				}
			}
		}
		$status['is_over'] = true;
	} elseif ($order['status'] == 4) {
		$status['is_over'] = true;
	} else {
		$status['is_cancel'] = true;
		$status['is_confirm'] = true;
		$status['is_refuse'] = true;
	}
	//可以执行的操作
	$order['operate'] = $status;
	return $order;
}

/**格式化图片的路径
 * $urls  url数组
 */
function format_url($urls) {
	foreach ($urls as $k => $url) {
		$urls[$k] = tomedia($url);
	}
	return $urls;
}
//获取店铺信息
function get_store_info($id) {
	global $_W, $_GPC;
	$store_info = pdo_get('storex_bases', array('weid' => $_W['uniacid'], 'id' => $id), array('id', 'store_type', 'status', 'title', 'phone', 'category_set'));
	if (empty($store_info)) {
		message(error(-1, '店铺不存在'), '', 'ajax');
	} else {
		if ($store_info['status'] == 0) {
			message(error(-1, '店铺已隐藏'), '', 'ajax');
		} else {
			return $store_info;
		}
	}
}
//根据店铺的类型返回表名
function get_goods_table($store_type) {
	if ($store_type == 1) {
		return 'storex_room';
	} else {
		return 'storex_goods';
	}
}
//根据坐标计算距离
function distanceBetween($longitude1, $latitude1, $longitude2, $latitude2) {
	$radLat1 = radian($latitude1);
	$radLat2 = radian($latitude2);
	$a = radian($latitude1) - radian($latitude2);
	$b = radian($longitude1) - radian($longitude2);
	$s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2)));
	$s = $s * 6378.137; //乘上地球半径，单位为公里
	$s = round($s * 10000) / 10000; //单位为公里(km)
	return $s * 1000; //单位为m
}
function radian($d) {
	return $d * 3.1415926535898 / 180.0;
}
//支付
function pay_info($order_id) {
	global $_W;
	$order_info = pdo_get('storex_order', array('id' => $order_id, 'weid' => intval($_W['uniacid']), 'openid' => $_W['openid']));
	if (!empty($order_info)) {
		$params = array(
			'ordersn' => $order_info['ordersn'],
			'tid' => $order_info['id'],//支付订单编号, 应保证在同一模块内部唯一
			'title' => $order_info['style'],
			'fee' => $order_info['sum_price'],//总费用, 只能大于 0
			'user' => $_W['openid']//付款用户, 付款的用户名(选填项)
		);
		return $params;
	} else {
		message(error(-1, '获取订单信息失败'), '', 'ajax');
	}
}

//获取某一级分类下的所有二级分类
function category_sub_class() {
	global $_W, $_GPC;
	return pdo_getall('storex_categorys', array('weid' => $_W['uniacid'],'parentid' => intval($_GPC['first_id']), 'enabled' => 1), array(), '', 'displayorder DESC');
}
function check_price($goods_info) {
	$goods[] = $goods_info;
	$goods = room_special_price($goods);
	$goods_info = $goods['0'];
	return $goods_info;
}
//获取一二级分类下的商品信息
function category_store_goods($table, $condition, $fields, $limit = array()) {
	$goods = pdo_getall($table, $condition, $fields, '', 'sortid DESC', $limit);
	foreach ($goods as $k => $info) {
		if (!empty($info['thumb'])) {
			$goods[$k]['thumb'] = tomedia($info['thumb']);
		}
		if (!empty($info['thumbs'])) {
			foreach ($info['thumbs'] as $key => $url) {
				$goods[$k]['thumbs'][$key] = tomedia($url);
			}
		}
	}
	if ($table == 'storex_room') {
		$goods = room_special_price($goods);
	}
	return $goods;
}
//根据日期和数量获取可预定的房型
function category_room_status($goods_list) {
	global $_GPC,$_W;
	$btime = $_GPC['btime'];
	$etime = $_GPC['etime'];
	$num = intval($_GPC['num']);
	if (!empty($btime) && !empty($etime) && !empty($num)) {
		if ($num <= 0 || strtotime($etime) < strtotime($btime) || strtotime($btime) < strtotime('today')) {
			message(error(-1, '搜索参数错误！'), '', 'ajax');
		}
	} else {
		$btime = date('Y-m-d');
		$etime = date('Y-m-d', time() + 86400);
	}
	$days = ceil((strtotime($etime) - strtotime($btime)) / 86400);
	$sql = "SELECT * FROM " . tablename('storex_room_price') . " WHERE weid = :weid AND roomdate >= :btime AND roomdate <= :etime ";
	$modify_recored = pdo_fetchall($sql, array(':weid' => intval($_W['uniacid']), ':btime' => strtotime($btime), ':etime' => strtotime($etime)));
	if (!empty($modify_recored)) {
		foreach ($modify_recored as $value) {
			foreach ($goods_list as &$info) {
				if ($value['roomid'] == $info['id'] && $value['hotelid'] == $info['hotelid']) {
					if (isset($info['max_room']) && $info['max_room'] == 0) {
						$info['room_counts'] = 0;
						continue;
					}
					if ($value['status'] == 1) {
						if ($value['num'] == -1) {
							if (empty($info['max_room']) && $info['max_room'] != 0) {
								$info['max_room'] = 8;
								$info['room_counts'] = '不限';
							}
						} else {
							if ($value['num'] > 8 && $value['num'] > $info['max_room']) {
								$info['max_room'] = 8;
							} elseif ($value['num'] < $info['max_room'] || !isset($info['max_room'])) {
								$info['max_room'] = $value['num'];
							}
							$info['room_counts'] = $value['num'];
						}
					} else {
						$info['max_room'] = 0;
						$info['room_counts'] = 0;
					}
				}
			}
		}
	}
	foreach ($goods_list as $k => $val) {
		if (!isset($val['max_room'])) {
			$val['max_room'] = 8;
			$val['room_counts'] = '不限';
		} elseif (!empty($num) && $val['max_room'] < $num) {
			unset($goods_list[$k]);
			continue;
		}
		$goods_list[$k] = get_room_params($val);
	}
	return $goods_list;
}
function get_room_params($info) {
	$info['params'] = '';
	if ($info['bed_show'] == 1) {
		$info['params'] = "床位(" . $info['bed'] . ")";
	}
	if ($info['floor_show'] == 1) {
		if (!empty($info['params'])) {
			$info['params'] .= " | 楼层(" . $info['floor'] . ")";
		} else {
			$info['params'] = "楼层(" . $info['floor'] . ")";
		}
	}
	return $info;
}
//获取日期格式
function get_dates($btime, $days) {
	$dates = array();
	$dates[0]['date'] = $btime;
	$dates[0]['day'] = date('j', strtotime($btime));
	$dates[0]['time'] = strtotime($btime);
	$dates[0]['month'] = date('m',strtotime($btime));
	if ($days > 1) {
		for ($i = 1; $i < $days; $i++) {
			$dates[$i]['time'] = $dates[$i - 1]['time'] + 86400;
			$dates[$i]['date'] = date('Y-m-d', $dates[$i]['time']);
			$dates[$i]['day'] = date('j', $dates[$i]['time']);
			$dates[$i]['month'] = date('m', $dates[$i]['time']);
		}
	}
	return $dates;
}
//根据信息获取房型的某一天的价格
function room_special_price($goods) {
	global $_W;
	if (!empty($goods)) {
		$btime = strtotime(date('Y-m-d'));
		$etime = $btime + 86400;
		$sql = 'SELECT `id`, `roomdate`, `num`, `status`, `oprice`, `cprice`, `roomid` FROM ' . tablename('storex_room_price') . ' WHERE `weid` = :weid AND `roomdate` >= :btime AND `roomdate` < :etime ORDER BY roomdate DESC';
		$params = array(':weid' => $_W['uniacid'], ':btime' => $btime, ':etime' => $etime);
		$room_price_list = pdo_fetchall($sql, $params, 'roomid');
		foreach ($goods as $key => $val) {
			if (!empty($room_price_list[$val['id']])) {
				$goods[$key]['oprice'] = $room_price_list[$val['id']]['oprice'];
				$goods[$key]['cprice'] = $room_price_list[$val['id']]['cprice'];
				if ($room_price_list[$val['id']]['num'] == -1) {
					$goods[$key]['max_room'] = 8;
				} else {
					$goods[$key]['max_room'] = $room_price_list[$val['id']]['num'];
				}
			} else {
				$goods[$key]['max_room'] = 8;
			}
		}
	}
	return $goods;
}
//检查条件
function goods_check_action($action, $goods_info) {
	if (empty($goods_info)) {
		message(error(-1, '商品未找到, 请联系管理员!'), '', 'ajax');
	}
	if ($action == 'reserve' && $goods_info['can_reserve'] != 1) {
		message(error(-1, '该商品不能预定'), '', 'ajax');
	}
	if ($action == 'buy' && $goods_info['can_buy'] != 1) {
		message(error(-1, '该商品不能购买'), '', 'ajax');
	}
}

//检查结果
function goods_check_result($action, $order_id) {
	if ($action == 'reserve') {
		if (!empty($order_id)) {
			message(error(0, $order_id), '', 'ajax');
		} else {
			message(error(-1, '预定失败'), '', 'ajax');
		}
	} else {
		if (!empty($order_id)) {
			message(error(0, $order_id), '', 'ajax');
		} else {
			message(error(-1, '下单失败'), '', 'ajax');
		}
	}
}

//检查店员    id:店铺id
function get_clerk_permission($id = 0) {
	global $_W;
	$clerk_info = pdo_get('storex_clerk', array('from_user' => trim($_W['openid']), 'weid' => intval($_W['uniacid'])));
	if (!empty($clerk_info) && !empty($clerk_info['permission'])) {
		if ($clerk_info['status'] != 1) {
			message(error(-1, '您没有进行此操作的权限！'), '', 'ajax');
		}
		$clerk_info['permission'] = iunserializer($clerk_info['permission']);
		if (!empty($id)) {
			if (!empty($clerk_info['permission'][$id])) {
				return $clerk_info['permission'][$id];
			}
		} else {
			return $clerk_info['permission'];
		}
		
	}
	message(error(-1, '您没有进行此操作的权限！'), '', 'ajax');
}
function check_clerk_permission($storexid, $permit) {
	$clerk_info = get_clerk_permission($storexid);
	$is_permission = false;
	foreach ($clerk_info as $permission) {
		if ($permission == $permit) {
			$is_permission = true;
			break;
		}
	}
	if (empty($is_permission)) {
		message(error(-1, '您没有进行此操作的权限！'), '', 'ajax');
	}
}
function clerk_permission_storex($type) {
	global $_W;
	$clerk_info = get_clerk_permission();
	foreach ($clerk_info as $id => $permission) {
		if (!empty($permission) && is_array($permission)) {
			$exist = false;
			foreach ($permission as $v) {
				if ($v == 'wn_storex_permission_' . $type) {
					$exist = true;
					break;
				}
			}
			if (empty($exist)) {
				unset($clerk_info[$id]);
			}
		}
	}
	$manage_storex_ids = array_keys($clerk_info);
	return $manage_storex_ids;
}
function send_custom_notice ($msgtype, $text, $touser) {
	$acc = WeAccount::create();
	$custom = array(
			'msgtype' => $msgtype,
			'text' => $text,
			'touser' => $touser,
	);
	$status = $acc->sendCustomNotice($custom);
	return $status;
}
function code2city($code) {
	$city_json = '{"A":[{"id":"152900","name":"阿拉善盟"},{"id":"210300","name":"鞍山市"},{"id":"340800","name":"安庆市"},{"id":"410500","name":"安阳市"},{"id":"513200","name":"阿坝藏族羌族自治州"},{"id":"520400","name":"安顺市"},{"id":"542500","name":"阿里地区"},{"id":"610900","name":"安康市"},{"id":"652900","name":"阿克苏地区"},{"id":"654300","name":"阿勒泰地区"},{"id":"820100","name":"澳门半岛"},{"id":"659002","name":"阿拉尔市"}],"B":[{"id":"110100","name":"北京市"},{"id":"130600","name":"保定市"},{"id":"150200","name":"包头市"},{"id":"150800","name":"巴彦淖尔市"},{"id":"210500","name":"本溪市"},{"id":"220600","name":"白山市"},{"id":"220800","name":"白城市"},{"id":"340300","name":"蚌埠市"},{"id":"341600","name":"亳州市"},{"id":"371600","name":"滨州市"},{"id":"450500","name":"北海市"},{"id":"451000","name":"百色市"},{"id":"511900","name":"巴中市"},{"id":"522400","name":"毕节地区"},{"id":"530500","name":"保山市"},{"id":"610300","name":"宝鸡市"},{"id":"620400","name":"白银市"},{"id":"652700","name":"博尔塔拉蒙古自治州"},{"id":"652800","name":"巴音郭楞蒙古自治州"}],"C":[{"id":"130800","name":"承德市"},{"id":"130900","name":"沧州市"},{"id":"140400","name":"长治市"},{"id":"150400","name":"赤峰市"},{"id":"220100","name":"长春市"},{"id":"320400","name":"常州市"},{"id":"341100","name":"滁州市"},{"id":"341400","name":"巢湖市"},{"id":"341700","name":"池州市"},{"id":"430100","name":"长沙市"},{"id":"430700","name":"常德市"},{"id":"431000","name":"郴州市"},{"id":"445100","name":"潮州市"},{"id":"451400","name":"崇左市"},{"id":"500100","name":"重庆市"},{"id":"510100","name":"成都市"},{"id":"532300","name":"楚雄彝族自治州"},{"id":"542100","name":"昌都地区"},{"id":"652300","name":"昌吉回族自治州"}],"D":[{"id":"140200","name":"大同市"},{"id":"210200","name":"大连市"},{"id":"210600","name":"丹东市"},{"id":"230600","name":"大庆市"},{"id":"232700","name":"大兴安岭地区"},{"id":"370500","name":"东营市"},{"id":"371400","name":"德州市"},{"id":"441900","name":"东莞市"},{"id":"510600","name":"德阳市"},{"id":"511700","name":"达州市"},{"id":"532900","name":"大理白族自治州"},{"id":"533100","name":"德宏傣族景颇族自治州"},{"id":"533400","name":"迪庆藏族自治州"},{"id":"621100","name":"定西市"},{"id":"469003","name":"儋州市"}],"E":[{"id":"150600","name":"鄂尔多斯市"},{"id":"420700","name":"鄂州市"},{"id":"422800","name":"恩施土家族苗族自治州"}],"F":[{"id":"210400","name":"抚顺市"},{"id":"210900","name":"阜新市"},{"id":"341200","name":"阜阳市"},{"id":"350100","name":"福州市"},{"id":"361000","name":"抚州市"},{"id":"440600","name":"佛山市"},{"id":"450600","name":"防城港市"}],"G":[{"id":"360700","name":"赣州市"},{"id":"440100","name":"广州市"},{"id":"450300","name":"桂林市"},{"id":"450800","name":"贵港市"},{"id":"510800","name":"广元市"},{"id":"511600","name":"广安市"},{"id":"513300","name":"甘孜藏族自治州"},{"id":"520100","name":"贵阳市"},{"id":"623000","name":"甘南藏族自治州"},{"id":"632600","name":"果洛藏族自治州"},{"id":"640400","name":"固原市"},{"id":"710200","name":"高雄市"},{"id":"712300","name":"高雄县"}],"H":[{"id":"130400","name":"邯郸市"},{"id":"131100","name":"衡水市"},{"id":"150100","name":"呼和浩特市"},{"id":"150700","name":"呼伦贝尔市"},{"id":"211400","name":"葫芦岛市"},{"id":"230100","name":"哈尔滨市"},{"id":"230400","name":"鹤岗市"},{"id":"231100","name":"黑河市"},{"id":"320800","name":"淮安市"},{"id":"330100","name":"杭州市"},{"id":"330500","name":"湖州市"},{"id":"340100","name":"合肥市"},{"id":"340400","name":"淮南市"},{"id":"340600","name":"淮北市"},{"id":"341000","name":"黄山市"},{"id":"371700","name":"菏泽市"},{"id":"410600","name":"鹤壁市"},{"id":"420200","name":"黄石市"},{"id":"421100","name":"黄冈市"},{"id":"430400","name":"衡阳市"},{"id":"431200","name":"怀化市"},{"id":"441300","name":"惠州市"},{"id":"441600","name":"河源市"},{"id":"451100","name":"贺州市"},{"id":"451200","name":"河池市"},{"id":"460100","name":"海口市"},{"id":"532500","name":"红河哈尼族彝族自治州"},{"id":"610700","name":"汉中市"},{"id":"632100","name":"海东地区"},{"id":"632200","name":"海北藏族自治州"},{"id":"632300","name":"黄南藏族自治州"},{"id":"632500","name":"海南藏族自治州"},{"id":"632800","name":"海西蒙古族藏族自治州"},{"id":"652200","name":"哈密地区"},{"id":"653200","name":"和田地区"},{"id":"712600","name":"花莲县"}],"J":[{"id":"140500","name":"晋城市"},{"id":"140700","name":"晋中市"},{"id":"210700","name":"锦州市"},{"id":"220200","name":"吉林市"},{"id":"230300","name":"鸡西市"},{"id":"230800","name":"佳木斯市"},{"id":"330400","name":"嘉兴市"},{"id":"330700","name":"金华市"},{"id":"360200","name":"景德镇市"},{"id":"360400","name":"九江市"},{"id":"360800","name":"吉安市"},{"id":"370100","name":"济南市"},{"id":"370800","name":"济宁市"},{"id":"410800","name":"焦作市"},{"id":"420800","name":"荆门市"},{"id":"421000","name":"荆州市"},{"id":"440700","name":"江门市"},{"id":"445200","name":"揭阳市"},{"id":"620200","name":"嘉峪关市"},{"id":"620300","name":"金昌市"},{"id":"620900","name":"酒泉市"},{"id":"710500","name":"金门县"},{"id":"710700","name":"基隆市"},{"id":"710900","name":"嘉义市"},{"id":"810200","name":"九龙"},{"id":"410881","name":"济源市"},{"id":"711900","name":"嘉义县"}],"K":[{"id":"410200","name":"开封市"},{"id":"530100","name":"昆明市"},{"id":"650200","name":"克拉玛依"},{"id":"653000","name":"克孜勒苏柯尔克孜自治州"},{"id":"653100","name":"喀什地区"}],"L":[{"id":"131000","name":"廊坊市"},{"id":"141000","name":"临汾市"},{"id":"141100","name":"吕梁市"},{"id":"211000","name":"辽阳市"},{"id":"220400","name":"辽源市"},{"id":"320700","name":"连云港市"},{"id":"331100","name":"丽水市"},{"id":"341500","name":"六安市"},{"id":"350800","name":"龙岩市"},{"id":"371200","name":"莱芜市"},{"id":"371300","name":"临沂市"},{"id":"371500","name":"聊城市"},{"id":"410300","name":"洛阳市"},{"id":"431300","name":"娄底市"},{"id":"450200","name":"柳州市"},{"id":"451300","name":"来宾市"},{"id":"510500","name":"泸州市"},{"id":"511100","name":"乐山市"},{"id":"513400","name":"凉山彝族自治州"},{"id":"520200","name":"六盘水市"},{"id":"530700","name":"丽江市"},{"id":"530900","name":"临沧市"},{"id":"540100","name":"拉萨市"},{"id":"542600","name":"林芝地区"},{"id":"620100","name":"兰州市"},{"id":"621200","name":"陇南市"},{"id":"622900","name":"临夏回族自治州"},{"id":"820200","name":"离岛"}],"M":[{"id":"231000","name":"牡丹江"},{"id":"340500","name":"马鞍山"},{"id":"440900","name":"茂名市"},{"id":"441400","name":"梅州市"},{"id":"510700","name":"绵阳市"},{"id":"511400","name":"眉山市"},{"id":"711500","name":"苗栗县"}],"N":[{"id":"320100","name":"南京市"},{"id":"320600","name":"南通市"},{"id":"330200","name":"宁波市"},{"id":"350700","name":"南平市"},{"id":"350900","name":"宁德市"},{"id":"360100","name":"南昌市"},{"id":"411300","name":"南阳市"},{"id":"450100","name":"南宁市"},{"id":"511000","name":"内江市"},{"id":"511300","name":"南充市"},{"id":"533300","name":"怒江傈僳族自治州"},{"id":"542400","name":"那曲地区"},{"id":"710600","name":"南投县"}],"P":[{"id":"211100","name":"盘锦市"},{"id":"350300","name":"莆田市"},{"id":"360300","name":"萍乡市"},{"id":"410400","name":"平顶山市"},{"id":"410900","name":"濮阳市"},{"id":"510400","name":"攀枝花市"},{"id":"530800","name":"普洱市"},{"id":"620800","name":"平凉市"},{"id":"712400","name":"屏东县"},{"id":"712700","name":"澎湖县"}],"Q":[{"id":"130300","name":"秦皇岛市"},{"id":"230200","name":"齐齐哈尔市"},{"id":"230900","name":"七台河市"},{"id":"330800","name":"衢州市"},{"id":"350500","name":"泉州市"},{"id":"370200","name":"青岛市"},{"id":"441800","name":"清远市"},{"id":"450700","name":"钦州市"},{"id":"522300","name":"黔西南布依族苗族自治州"},{"id":"522600","name":"黔东南苗族侗族自治州"},{"id":"522700","name":"黔南布依族苗族自治州"},{"id":"530300","name":"曲靖市"},{"id":"621000","name":"庆阳市"},{"id":"429005","name":"潜江市"}],"R":[{"id":"371100","name":"日照市"},{"id":"542300","name":"日喀则地区"}],"S":[{"id":"130100","name":"石家庄市"},{"id":"140600","name":"朔州市"},{"id":"210100","name":"沈阳市"},{"id":"220300","name":"四平市"},{"id":"220700","name":"松原市"},{"id":"230500","name":"双鸭山市"},{"id":"231200","name":"绥化市"},{"id":"310100","name":"上海市"},{"id":"320500","name":"苏州市"},{"id":"321300","name":"宿迁市"},{"id":"330600","name":"绍兴市"},{"id":"341300","name":"宿州市"},{"id":"350400","name":"三明市"},{"id":"361100","name":"上饶市"},{"id":"411200","name":"三门峡市"},{"id":"411400","name":"商丘市"},{"id":"420300","name":"十堰市"},{"id":"421300","name":"随州市"},{"id":"430500","name":"邵阳市"},{"id":"440200","name":"韶关市"},{"id":"440300","name":"深圳市"},{"id":"440500","name":"汕头市"},{"id":"441500","name":"汕尾市"},{"id":"460200","name":"三亚市"},{"id":"510900","name":"遂宁市"},{"id":"542200","name":"山南地区"},{"id":"611000","name":"商洛市"},{"id":"640200","name":"石嘴山市"},{"id":"429021","name":"神农架林区"},{"id":"659001","name":"石河子市"},{"id":"460300","name":"三沙市"}],"T":[{"id":"120100","name":"天津市"},{"id":"130200","name":"唐山市"},{"id":"140100","name":"太原市"},{"id":"150500","name":"通辽市"},{"id":"211200","name":"铁岭市"},{"id":"220500","name":"通化市"},{"id":"321200","name":"泰州市"},{"id":"331000","name":"台州市"},{"id":"340700","name":"铜陵市"},{"id":"370900","name":"泰安市"},{"id":"411100","name":"漯河市"},{"id":"522200","name":"铜仁地区"},{"id":"610200","name":"铜川市"},{"id":"620500","name":"天水市"},{"id":"652100","name":"吐鲁番地区"},{"id":"654200","name":"塔城地区"},{"id":"710100","name":"台北市"},{"id":"710300","name":"台南市"},{"id":"710400","name":"台中市"},{"id":"429006","name":"天门市"},{"id":"659003","name":"图木舒克市"},{"id":"711100","name":"台北县"},{"id":"711400","name":"桃园县"},{"id":"711600","name":"台中县"},{"id":"712200","name":"台南县"},{"id":"712500","name":"台东县"}],"W":[{"id":"150300","name":"乌海市"},{"id":"150900","name":"乌兰察布市"},{"id":"320200","name":"无锡市"},{"id":"330300","name":"温州市"},{"id":"340200","name":"芜湖市"},{"id":"370700","name":"潍坊市"},{"id":"371000","name":"威海市"},{"id":"420100","name":"武汉市"},{"id":"450400","name":"梧州市"},{"id":"532600","name":"文山壮族苗族自治州"},{"id":"610500","name":"渭南市"},{"id":"620600","name":"武威市"},{"id":"640300","name":"吴忠市"},{"id":"650100","name":"乌鲁木齐市"},{"id":"659004","name":"五家渠市"}],"X":[{"id":"130500","name":"邢台市"},{"id":"140900","name":"忻州市"},{"id":"152200","name":"兴安盟"},{"id":"152500","name":"锡林郭勒盟"},{"id":"320300","name":"徐州市"},{"id":"341800","name":"宣城市"},{"id":"350200","name":"厦门市"},{"id":"360500","name":"新余市"},{"id":"410700","name":"新乡市"},{"id":"411000","name":"许昌市"},{"id":"411500","name":"信阳市"},{"id":"420600","name":"襄樊市"},{"id":"420900","name":"孝感市"},{"id":"421200","name":"咸宁市"},{"id":"430300","name":"湘潭市"},{"id":"433100","name":"湘西土家族苗族自治州"},{"id":"532800","name":"西双版纳傣族自治州"},{"id":"610100","name":"西安市"},{"id":"610400","name":"咸阳市"},{"id":"630100","name":"西宁市"},{"id":"710800","name":"新竹市"},{"id":"810100","name":"香港岛"},{"id":"810300","name":"新界市"},{"id":"429004","name":"仙桃市"},{"id":"711300","name":"新竹县"}],"Y":[{"id":"140300","name":"阳泉市"},{"id":"140800","name":"运城市"},{"id":"210800","name":"营口市"},{"id":"222400","name":"延边朝鲜族自治州"},{"id":"230700","name":"伊春市"},{"id":"320900","name":"盐城市"},{"id":"321000","name":"扬州市"},{"id":"360600","name":"鹰潭市"},{"id":"360900","name":"宜春市"},{"id":"370600","name":"烟台市"},{"id":"420500","name":"宜昌市"},{"id":"430600","name":"岳阳市"},{"id":"430900","name":"益阳市"},{"id":"431100","name":"永州市"},{"id":"441700","name":"阳江市"},{"id":"445300","name":"云浮市"},{"id":"450900","name":"玉林市"},{"id":"511500","name":"宜宾市"},{"id":"511800","name":"雅安市"},{"id":"530400","name":"玉溪市"},{"id":"610600","name":"延安市"},{"id":"610800","name":"榆林市"},{"id":"632700","name":"玉树藏族自治州"},{"id":"640100","name":"银川市"},{"id":"654000","name":"伊犁哈萨克自治州"},{"id":"711200","name":"宜兰县"},{"id":"712100","name":"云林县"}],"Z":[{"id":"130700","name":"张家口"},{"id":"211300","name":"朝阳市"},{"id":"321100","name":"镇江市"},{"id":"330900","name":"舟山市"},{"id":"350600","name":"漳州市"},{"id":"370300","name":"淄博市"},{"id":"370400","name":"枣庄市"},{"id":"410100","name":"郑州市"},{"id":"411600","name":"周口市"},{"id":"411700","name":"驻马店市"},{"id":"430200","name":"株洲市"},{"id":"430800","name":"张家界市"},{"id":"440400","name":"珠海市"},{"id":"440800","name":"湛江市"},{"id":"441200","name":"肇庆市"},{"id":"442000","name":"中山市"},{"id":"510300","name":"自贡市"},{"id":"512000","name":"资阳市"},{"id":"520300","name":"遵义市"},{"id":"530600","name":"昭通市"},{"id":"620700","name":"张掖市"},{"id":"640500","name":"中卫市"},{"id":"711700","name":"彰化县"}]}';
	$name = '';
	$city_lists = json_decode($city_json, true);
	foreach ($city_lists as $k => $v) {
		foreach ($v as $key => $value) {
			if ($value['id'] == $code) {
				$name = $value['name'];
			}
		}
	}
	return $name;
}

function extend_switch_fetch() {
	global $_W;
	$cachekey = "wn_storex_switch:{$_W['uniacid']}";
	$cache = cache_load($cachekey);
	if (!empty($cache)) {
		return $cache;
	}
	$extend_info = pdo_get('storex_set', array('weid' => $_W['uniacid']), array('extend_switch'));
	$extend_switchs = iunserializer($extend_info['extend_switch']);
	$switchs['card'] = !empty($extend_switchs['card']) ? $extend_switchs['card'] : 2;
	$switchs['sign'] = !empty($extend_switchs['sign']) ? $extend_switchs['sign'] : 2;
	cache_write($cachekey, $switchs);
	return $switchs;
}

function get_plugin_list() {
	load()->model('module');
	$plugin_list = module_get_plugin_list('wn_storex');
	if (!empty($plugin_list) && is_array($plugin_list)) {
		foreach ($plugin_list as $name => $plugin) {
			$plugins[$name] = true;
		}
	}
	return $plugins;
}