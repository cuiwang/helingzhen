<?php

defined('IN_IA') or exit('Access Denied');

global $_W, $_GPC;
load()->model('mc');
mload()->model('card');

$ops = array('edit', 'post', 'delete', 'deleteall');
$op = in_array(trim($_GPC['op']), $ops) ? trim($_GPC['op']) : 'display';

checklogin();
$store_type = isset($_GPC['store_type']) ? $_GPC['store_type'] : 0;
$hotelid = intval($_GPC['hotelid']);
$hotel = pdo_get('storex_bases', array('id' => $hotelid), array('id', 'title', 'phone'));
$roomid = intval($_GPC['roomid']);

$table = gettablebytype($store_type);
$room = pdo_get($table, array('id' => $roomid), array('id', 'title', 'sold_num'));

if ($op == 'edit') {
	$id = $_GPC['id'];
	if (!empty($id)) {
		$item = pdo_get('storex_order', array('id' => $id));
		$paylog = pdo_get('core_paylog', array('uniacid' => $item['weid'], 'tid' => $item['id'], 'module' => 'wn_storex'), array('uniacid', 'uniontid', 'tid'));
		if (!empty($paylog)) {
			$item['uniontid'] = $paylog['uniontid'];
		}
		if (empty($item)) {
			message('抱歉，订单不存在或是已经删除！', '', 'error');
		}
	}
	$express = express_name();
	if (checksubmit('submit')) {
		$old_status = $_GPC['old_status'];
		$setting = pdo_get('storex_set', array('weid' => $_W['uniacid']));
		$data = array(
			'status' => $_GPC['status'],
			'msg' => $_GPC['msg'],
			'mngtime' => time(),
			'track_number' => trim($_GPC['track_number']),
			'express_name' => trim($_GPC['express_name']),
		);
		if ($item['status'] == 1 || $item['status'] == 4 || $item['goods_status'] == 2 || $item['goods_status'] == 3 ) {
			if ($data['status'] == -1 || $data['status'] == 2) {
				message('订单已确认或发货，不能操做！', '', 'error');
			}
		}
		if ($item['status'] == -1) {
			message('订单状态已经取消，不能操做！', '', 'error');
		}
		if ($item['status'] == 3) {
			message('订单状态已经完成，不能操做！', '', 'error');
		}
		if ($data['status'] == $item['status']) {
			message('订单状态已经是该状态了，不要重复操作！', '', 'error');
		}
		if ($store_type == 1) {
			//订单取消
			if ($data['status'] == -1 || $data['status'] == 2) {
				$room_date_list = pdo_getall('storex_room_price', array('roomid' => $item['roomid'], 'roomdate >=' => $item['btime'], 'roomdate <' => $item['etime'], 'status' => 1), 
					array('id', 'roomdate', 'num', 'status'));
				if (!empty($room_date_list)) {
					foreach ($room_date_list as $key => $value) {
						$num = $value['num'];
						if ($num >= 0) {
							$now_num = $num + $item['nums'];
							pdo_update('storex_room_price', array('num' => $now_num), array('id' => $value['id']));
						}
					}
				}
			}
		}
		if ($data['status'] != $item['status']) {
			//订单退款
			if ($data['status'] == 2) {
				if (!empty($setting['template']) && !empty($setting['refuse_templateid'])) {
					$tplnotice = array(
						'first' => array('value'=>'尊敬的宾客，非常抱歉的通知您，您的预订订单被拒绝。'),
						'keyword1' => array('value' => $item['ordersn']),
						'keyword2' => array('value' => date('Y.m.d', $item['btime']). '-'. date('Y.m.d', $item['etime'])),
						'keyword3' => array('value' => $item['nums']),
						'keyword4' => array('value' => $item['sum_price']),
						'keyword5' => array('value' => '商品不足'),
					);
					$acc = WeAccount::create();
					$acc->sendTplNotice($item['openid'], $setting['refuse_templateid'], $tplnotice);
				} else {
					$info = '您在'.$hotel['title'].'预订的'.$room['title']."不足。已为您取消订单";
					$status = send_custom_notice ('text', array('content' => urlencode($info)), $item['openid']);
				}
			}
			//订单确认提醒
			if ($data['status'] == 1) {
				//TM00217
				if (!empty($setting['template']) && !empty($setting['templateid'])) {
					$tplnotice = array(
						'first' => array('value' => '您好，您已成功预订' . $hotel['title'] . '！'),
						'order' => array('value' => $item['ordersn']),
						'Name' => array('value' => $item['name']),
						'datein' => array('value' => date('Y-m-d', $item['btime'])),
						'dateout' => array('value' => date('Y-m-d', $item['etime'])),
						'number' => array('value' => $item['nums']),
						'room type' => array('value' => $item['style']),
						'pay' => array('value' => $item['sum_price']),
						'remark' => array('value' => '酒店预订成功')
					);
					$acc = WeAccount::create();
					$result = $acc->sendTplNotice($item['openid'], $setting['templateid'], $tplnotice);
				} else {
					$info = '您在' . $hotel['title'] . '预订的' . $room['title'] . '已预订成功';
					$status = send_custom_notice ('text', array('content' => urlencode($info)), $item['openid']);
				}
			}
			//已入住提醒
			if ($data['status'] == 4) {
				//TM00058
				if (!empty($setting['template']) && !empty($setting['check_in_templateid'])) {
					$tplnotice = array(
						'first' =>array('value' =>'您好,您已入住' . $hotel['title'] . $room['title']),
						'hotelName' => array('value' => $hotel['title']),
						'roomName' => array('value' => $room['title']),
						'date' => array('value' => date('Y-m-d', $item['btime'])),
						'remark' => array('value' => '如有疑问，请咨询' . $hotel['phone'] . '。'),
					);
					$acc = WeAccount::create();
					$result = $acc->sendTplNotice($item['openid'], $setting['check_in_templateid'], $tplnotice);
				} else {
					$info = '您已成功入住' . $hotel['title'] . '预订的' . $room['title'];
					$status = send_custom_notice ('text', array('content' => urlencode($info)), $item['openid']);
				}
			}
	
			//订单完成提醒
			if ($data['status'] == 3) {
				$uid = mc_openid2uid(trim($item['openid']));
				//订单完成后增加积分
				card_give_credit($uid, $item['sum_price']);
				//增加出售货物的数量
				add_sold_num($room);
				//OPENTM203173461
				if (!empty($setting['template']) && !empty($setting['finish_templateid']) && $store_type == 1) {
					$tplnotice = array(
						'first' => array('value' =>'您已成功办理离店手续，您本次入住酒店的详情为'),
						'keyword1' => array('value' =>date('Y-m-d', $item['btime'])),
						'keyword2' => array('value' =>date('Y-m-d', $item['etime'])),
						'keyword3' => array('value' =>$item['sum_price']),
						'remark' => array('value' => '欢迎您的下次光临。')
					);
					$acc = WeAccount::create();
					$result = $acc->sendTplNotice($item['openid'], $setting['finish_templateid'], $tplnotice);
				} else {
					$info = '您在'.$hotel['title'] . '预订的' . $room['title'] . '订单已完成,欢迎下次光临';
					$status = send_custom_notice ('text', array('content' => urlencode($info)), $item['openid']);
				}
			}
			if ($data['status'] == 5) {
				$data['status'] = 1;
				$data['goods_status'] = 2;
				$info = '您在' . $hotel['title'] . '预订的' . $room['title'] . '已发货';
				$status = send_custom_notice ('text', array('content' => urlencode($info)), $item['openid']);
			}
			if ($data['status'] == -1) {
				$info = '您在' . $store_info['title'] . '预订的' . $goods_info['title'] . "订单已取消，请联系管理员！";
				$status = send_custom_notice ('text', array('content' => urlencode($info)), $item['openid']);
			}
		}
		if (!empty($item['coupon'])) {
			if ($data['status'] == '-1' || $data['status'] == '2') {
				pdo_update('storex_coupon_record', array('status' => 1), array('id' => $item['coupon']));
			} elseif ($data['status'] == '3') {
				pdo_update('storex_coupon_record', array('status' => 3), array('id' => $item['coupon']));
			}
		}
		pdo_update('storex_order', $data, array('id' => $id));
		message('订单信息处理完成！', $this->createWebUrl('order', array('hotelid' => $hotelid, "roomid" => $roomid, 'store_type' => $store_type)), 'success');
	}
	if ($store_type == 1) {
		$btime = $item['btime'];
		$etime = $item['etime'];
		$start = date('m-d', $btime);
		$end = date('m-d', $etime);
		//日期列
		$days = ceil(($etime - $btime) / 86400);
		$date_array = array();
		$date_array[0]['date'] = $start;
		$date_array[0]['day'] = date('j', $btime);
		$date_array[0]['time'] = $btime;
		$date_array[0]['month'] = date('m', $btime);
		if ($days > 1) {
			for ($i = 1; $i < $days; $i++) {
				$date_array[$i]['time'] = $date_array[$i - 1]['time'] + 86400;
				$date_array[$i]['date'] = date('Y-m-d', $date_array[$i]['time']);
				$date_array[$i]['day'] = date('j', $date_array[$i]['time']);
				$date_array[$i]['month'] = date('m', $date_array[$i]['time']);
			}
		}
		$room_date_list = pdo_getall('storex_room_price', array('roomid' => $item['roomid'], 'roomdate >=' => $item['btime'], 'roomdate <' => $item['etime'], 'status' => 1), array('id', 'roomdate', 'num', 'status'));
		$flag = 0;
		if (!empty($room_date_list)) {
			$flag = 1;
		}
		$list = array();
		if ($flag == 1) {
			for ($i = 0; $i < $days; $i++) {
				$k = $date_array[$i]['time'];
				foreach ($room_date_list as $p_key => $p_value) {
					//判断价格表中是否有当天的数据
					if ($p_value['roomdate'] == $k) {
						$list[$k]['status'] = $p_value['status'];
						if (empty($p_value['num'])) {
							$list[$k]['num'] = 0;
						} elseif ($p_value['num'] == -1) {
							$list[$k]['num'] = '不限';
						} else {
							$list[$k]['num'] = $p_value['num'];
						}
						$list[$k]['has'] = 1;
						break;
					}
				}
				//价格表中没有当天数据
				if (empty($list[$k])) {
					$list[$k]['num'] = '不限';
					$list[$k]['status'] = 1;
				}
			}
		} else {
			//价格表中没有数据
			for ($i = 0; $i < $days; $i++) {
				$k = $date_array[$i]['time'];
				$list[$k]['num'] = "不限";
				$list[$k]['status'] = 1;
			}
		}
	}
	$member_info = pdo_get('storex_member', array('id' => $item['memberid']), array('from_user', 'isauto'));
	include $this->template('order_form');
}

if ($op == 'delete') {
	$id = intval($_GPC['id']);
	$item = pdo_get('storex_order', array('id' => $id), array('id'));
	if (empty($item)) {
		message('抱歉，订单不存在或是已经删除！', '', 'error');
	}
	pdo_delete('storex_order', array('id' => $id));
	message('删除成功！', referer(), 'success');
}

if ($op == 'deleteall') {
	foreach ($_GPC['idArr'] as $k => $id) {
		$id = intval($id);
		pdo_delete('storex_order', array('id' => $id));
	}
	$this->web_message('删除成功！', '', 0);
	exit();
}

if ($op == 'display') {
	$weid = $_W['uniacid'];
	$realname = $_GPC['realname'];
	$mobile = $_GPC['mobile'];
	$ordersn = $_GPC['ordersn'];
	$roomtitle = $_GPC['roomtitle'];
	$hoteltitle = $_GPC['hoteltitle'];
	$condition = '';
	$condition .= " AND h.store_type = " . $store_type;
	$params = array();
	if (!empty($hoteltitle)) {
		$condition .= ' AND h.title LIKE :hoteltitle';
		$params[':hoteltitle'] = "%{$hoteltitle}%";
	}
	if (!empty($roomtitle)) {
		$condition .= ' AND r.title LIKE :roomtitle';
		$params[':roomtitle'] = "%{$roomtitle}%";
	}
	if (!empty($realname)) {
		$condition .= ' AND o.name LIKE :realname';
		$params[':realname'] = "%{$realname}%";
	}
	if (!empty($mobile)) {
		$condition .= ' AND o.mobile LIKE :mobile';
		$params[':mobile'] = "%{$mobile}%";
	}
	if (!empty($ordersn)) {
		$condition .= ' AND o.ordersn LIKE :ordersn';
		$params[':ordersn'] = "%{$ordersn}%";
	}
	if (!empty($hotelid)) {
		$condition .= " AND o.hotelid=" . $hotelid;
	}
	if (!empty($roomid)) {
		$condition .= " AND o.roomid=" . $roomid;
	}
	$status = $_GPC['status'];
	if ($status != '') {
		$condition .= " AND o.status=" . intval($status);
	}
	$paystatus = $_GPC['paystatus'];
	if ($paystatus != '') {
		$condition .= " and o.paystatus=" . intval($paystatus);
	}
	$date = $_GPC['date'];
	if (!empty($date)) {
		$condition .= " AND o.time > ". strtotime($date['start'])." AND o.time < ".strtotime($date['end']);
	}
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	pdo_query('UPDATE ' . tablename('storex_order') . " SET status = -1 WHERE time < :time AND weid = '{$_W['uniacid']}' AND paystatus = 0 AND status <> 1 AND status <> 3", array(':time' => time() - 86400));
	$show_order_lists = pdo_fetchall("SELECT o.*, h.title AS hoteltitle, r.title AS roomtitle FROM " . tablename('storex_order') . " AS o LEFT JOIN " . tablename('storex_bases') . " h ON o.hotelid = h.id LEFT JOIN " . tablename($table) . " AS r ON r.id = o.roomid WHERE o.weid = '{$_W['uniacid']}' $condition ORDER BY o.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
	getOrderUniontid($show_order_lists);
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('storex_order') . " AS o LEFT JOIN " . tablename('storex_bases') . " AS h on o.hotelid = h.id LEFT JOIN " . tablename($table) . " AS r on r.id = o.roomid  WHERE o.weid = '{$_W['uniacid']}' $condition", $params);
	if ($_GPC['export'] != '') {
		$export_order_lists = pdo_fetchall("SELECT o.*, h.title as hoteltitle, r.title AS roomtitle FROM " . tablename('storex_order') . " o LEFT JOIN " . tablename('storex_bases') . " AS h on o.hotelid = h.id LEFT JOIN " . tablename($table) . " AS r ON r.id = o.roomid  WHERE o.weid = '{$_W['uniacid']}' $condition ORDER BY o.id DESC" . ',' . $psize, $params);
		getOrderUniontid($export_order_lists);
		/* 输入到CSV文件 */
		$html = "\xEF\xBB\xBF";
		/* 输出表头 */
		$filter = array(
			'ordersn' => '订单号',
			'uniontid' => '商户订单号',
			'hoteltitle' => '酒店',
			'roomtitle' => '房型',
			'name' => '预订人',
			'mobile' => '手机',
			'nums' => '预订数量',
			'sum_price' => '总价',
			'btime' => '到店时间',
			'etime' => '离店时间',
			'paytype' => '支付方式',
			'time' => '订单生成时间',
			'paystatus' => '订单状态'
		);
		foreach ($filter as $key => $title) {
			$html .= $title . "\t,";
		}
		$html .= "\n";
		foreach ($export_order_lists as $k => $v) {
			foreach ($filter as $key => $title) {
				if ($key == 'time') {
					$html .= date('Y-m-d H:i:s', $v[$key]) . "\t, ";
				} elseif ($key == 'btime') {
					$html .= date('Y-m-d', $v[$key]) . "\t, ";
				} elseif ($key == 'etime') {
					$html .= date('Y-m-d', $v[$key]) . "\t, ";
				} elseif ($key == 'paytype') {
					if ($v[$key] == 1) {
						$html .= '余额支付' . "\t, ";
					}
					if ($v[$key] == 21) {
						$html .= '微信支付' . "\t, ";
					}
					if ($v[$key] == 22) {
						$html .= '支付宝支付' . "\t, ";
					}
					if ($v[$key] == 3) {
						$html .= '到店支付' . "\t, ";
					}
					if ($v[$key] == '0') {
						$html .= '未支付(或其它)' . "\t, ";
					}
				} elseif ($key == 'paystatus') {
					if ($v[$key] == 0) {
						if ($v['status'] == 0) {
							if ($v['paytype'] == 1 || $v['paytype'] == 2) {
								$html .= '待付款' . "\t, ";
							} else {
								$html .= '等待确认' . "\t, ";
							}
						} elseif ($v['status'] == -1) {
							$html .= '已取消' . "\t, ";
						} elseif ($v['status'] == 1) {
							$html .= '已接受' . "\t, ";
						} elseif ($v['status'] == 2) {
							$html .= '已拒绝' . "\t, ";
						} elseif ($v['status'] == 3) {
							$html .= '订单完成' . "\t, ";
						}
					} else {
						if ($v['status'] == 0) {
							$html .= '已支付等待确认'."\t, ";
						} elseif ($v['status'] == -1) {
							if ($v['paytype'] == 3) {
								$html .= '已取消' . "\t, ";
							} else {
								$html .= '已支付，取消并退款' . "\t, ";
							}
						} elseif ($v['status'] == 1) {
							$html .= '已确认，已接受' . "\t, ";
						} elseif ($v['status'] == 2) {
							$html .= '已支付，已退款' . "\t, ";
						} elseif ($v['status'] == 3) {
							$html .= '订单完成' . "\t, ";
						}
					}
				} else {
					$html .= $v[$key] . "\t, ";
				}
			}
			$html .= "\n";
		}
		/* 输出CSV文件 */
		header("Content-type:text/csv");
		header("Content-Disposition:attachment; filename=全部数据.csv");
		echo $html;
		exit();
	}
	$pager = pagination($total, $pindex, $psize);
	include $this->template('order');
}