<?php

defined('IN_IA') or exit('Access Denied');

global $_W, $_GPC;
load()->model('mc');
load()->classs('coupon');
mload()->model('activity');
$ops = array('display', 'post', 'detail', 'toggle', 'modifystock', 'delete', 'publish', 'status', 'sync');
$op = in_array(trim($_GPC['op']), $ops) ? trim($_GPC['op']) : 'display';

$coupon_colors = activity_get_coupon_colors();
$coupon_api = new coupon();
activity_get_coupon_type();
if ($op == 'display') {
	$setting = pdo_get('storex_set', array('weid' => $_W['uniacid']), array('source'));
	$store_lists = pdo_getall('storex_bases', array('status' => 1, 'weid' => $_W['uniacid']), array('id', 'title'), 'id');
	$type = intval($_GPC['type']);
	$pageindex = max(1, $_GPC['page']);
	$psize = 15;
	$condition = array();
	$condition_sql = $join_sql = '';
	$condition_sql = ' c.uniacid = :uniacid AND c.source = :source';
	$condition[':uniacid'] = $_W['uniacid'];
	$condition[':source'] = COUPON_TYPE;

	if(!empty($_GPC['status'])) {
		$condition_sql .= " AND c.status = :status";
		$condition[':status'] = intval($_GPC['status']);
	}
	
	if(!empty($_GPC['title'])) {
		$condition_sql .= " AND c.title LIKE :title";
		$condition[':title'] = "%".$_GPC['title']."%";
	}
	
	if (!empty($_GPC['type'])) {
		$condition_sql .= " AND c.type = :type";
		$condition[':type'] = intval($_GPC['type']);
	}
	$store_id = intval($_GPC['storeid']);
	// if (!empty($_GPC['storeid'])) {
	// 	$join_sql .= " LEFT JOIN ".tablename('storex_coupon_store')." AS s ON c.id = s.couponid ";
	// 	$condition_sql .= " AND s.storeid = :storeid";
	// 	$condition[':storeid'] = intval($_GPC['storeid']);
	// }
	$coupon_stores = pdo_getall('storex_coupon_store', array('uniacid' => $_W['uniacid']), array('storeid', 'id', 'couponid'), 'id');
	if (!empty($coupon_stores)) {
		foreach ($coupon_stores as $key => $stores) {
			$storelist[$stores['couponid']][$key] = $stores['storeid'];
		}
	}
	$coupon_ids = array();
	if (!empty($storelist) && is_array($storelist)) {
		$coupon_ids = array_keys($storelist);
	}
	$couponlist = pdo_fetchall("SELECT * FROM " . tablename('storex_coupon') . " AS c " . $join_sql . " WHERE  " . $condition_sql . " ORDER BY c.id DESC LIMIT ".($pageindex - 1) * $psize.','.$psize, $condition);
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('storex_coupon') . " AS c " . $join_sql . " WHERE  " . $condition_sql, $condition);
	foreach($couponlist as $key=>&$row) {
		$row['date_info'] = iunserializer($row['date_info']);
		if ($row['date_info']['time_type'] == 1) {
			$row['date_info'] = $row['date_info']['time_limit_start'].'-'. $row['date_info']['time_limit_end'];
		} elseif($row['date_info']['time_type'] == 2) {
			$row['date_info'] = '领取后'.$row['date_info']['limit'].'天有效';
		}
		$row['type'] = activity_get_coupon_label($row['type']);
		if (in_array($row['id'], $coupon_ids)) {
			if (!empty($store_id) && !in_array($store_id, $storelist[$row['id']])) {
				unset($couponlist[$key]);
			}
		}
	}
	unset($row);
	$pager = pagination($total, $pageindex, $psize);
}

if ($op == 'post') {
	$type = !empty($_GPC['type']) ? intval($_GPC['type']) : 1;
	$coupon_title = activity_get_coupon_label($type);
	$coupon_label = json_encode($coupon_title);
	$store_lists = pdo_getall('storex_bases', array('status' => 1, 'weid' => $_W['uniacid']), array('id', 'title', 'location_p', 'location_c', 'location_a', 'address', 'thumb'), 'id');
	$starttime = date('Y-m-d', time());
	$endtime = date('Y-m-d', time() + 30 * 86400);
	foreach ($store_lists as $key => &$store) {
		$store['address_info'] = $store['location_p'] . $store['location_c'] . $store['location_a'] . $store['address'];
		$store['thumb'] = tomedia($store['thumb']);
	}
	if ($_W['isajax'] && $_W['ispost']) {
		$params = $_GPC['params'];
		$type = intval($params['type']);
		$coupon = Card::create($type);
		$coupon->logo_url = empty($params['logo_url']) ? urlencode($setting['logourl']) : urlencode(trim($params['logo_url']));
		$coupon->brand_name = $params['brand_name'];
		$coupon->title = substr(trim($params['title']), 0,27);
		$coupon->sub_title = trim($params['sub_title']);
		$coupon->color = empty($params['color']) ? 'Color082' : $params['color'];
		$coupon->notice = $params['notice'];
		$coupon->service_phone = $params['service_phone'];
		$coupon->description = $params['description'];
		$coupon->get_limit = intval($params['get_limit']);
		$coupon->can_share = intval($params['can_share']) ? true : false;
		$coupon->can_give_friend = intval($params['can_give_friend']) ? true : false;
		//有效期
		if (intval($params['time_type']) == COUPON_TIME_TYPE_RANGE) {
			$coupon->setDateinfoRange($params['time_limit']['start'], $params['time_limit']['end']);
		} else {
			$coupon->setDateinfoFix($params['deadline'], $params['limit']);
		}
		//自定义菜单
		if(!empty($params['promotion_url_name']) && !empty($params['promotion_url'])) {
			$coupon->setPromotionMenu($params['promotion_url_name'], $params['promotion_url_sub_title'], $params['promotion_url']);
		}
		
		// $coupon->setCustomMenu('立即使用', '', murl('entry', array('m' => 'paycenter', 'do' => 'consume'), true, true));
		$coupon->setQuantity($params['quantity']);
		$coupon->setCodetype($params['code_type']);
		//折扣券
		$coupon->discount = intval($params['discount']);
		//代金券，单位为分
		$coupon->least_cost = $params['least_cost'] * 100;
		$coupon->reduce_cost = $params['reduce_cost'] * 100;
		//礼品券
		$coupon->gift = $params['gift'];
		//团购券
		$coupon->deal_detail = $params['deal_detail'];
		//优惠券
		$coupon->default_detail = $params['default_detail'];
		
		$check = $coupon->validate();
		if (is_error($check)) {
			message(error(-1, $check['message']), '', 'ajax');
		}

		if (COUPON_TYPE == WECHAT_COUPON) {
			$status = $coupon_api->CreateCard($coupon->getCardData());
			if(is_error($status)) {
				message($status['message'], '', 'ajax');
			}
			$coupon->card_id = $status['card_id'];
			$coupon->source = 2;
			$coupon->status = 1;
		} else {
			$coupon->status = 3;
			$coupon->source = 1;
			$coupon->setCodetype(3);
			$coupon->card_id = 'AB' . $_W['uniacid'] . date('YmdHis');
		}
		$cardinsert = $coupon->getCardArray();
		$cardinsert['uniacid'] = $_W['uniacid'];
		$cardinsert['acid'] = $_W['acid'];
		$card_exists = pdo_get('storex_coupon', array('card_id' => $coupon->card_id), array('id'));
		if(empty($card_exists)) {
			pdo_insert('storex_coupon', $cardinsert);
			$cardid = pdo_insertid();
		} else {
			$cardid = $card_exists['id'];
			unset($cardinsert['status']);
			pdo_update('storex_coupon', $cardinsert, array('id' => $cardid));
		}
		//启用门店
		if (COUPON_TYPE == SYSTEM_COUPON) {
			if (!empty($params['location_select'])) {
				foreach ($params['location_select'] as $store) {
					$data = array(
						'uniacid' => $_W['uniacid'],
						'storeid' => $store['id'],
						'couponid' => $cardid
					);
					pdo_insert('storex_coupon_store', $data);
				}
			}
		}
		message(error(0, '创建卡券成功'), $this->createWebUrl('couponmanage'), 'ajax');
	}
}

if ($op == 'detail') {
	$coupon_info = activity_get_coupon_info($_GPC['id']);
	$coupon_info['coupon_label'] = activity_get_coupon_label($coupon_info['type']);
	if ($coupon_info['type'] == COUPON_TYPE_CASH) {
		$coupon_info['detail']['least_cost'] = $coupon_info['extra']['least_cost'] * 0.01;
		$coupon_info['detail']['reduce_cost'] = $coupon_info['extra']['reduce_cost'] * 0.01;
	}
}

if ($op == 'toggle') {
	$id = intval($_GPC['id']);
	$display_status = pdo_getcolumn('storex_coupon', array('id' => $id, 'uniacid' => $_W['uniacid']), 'is_display');
	if($display_status == 1) {
		pdo_update('storex_coupon', array('is_display' => 0), array('uniacid' => $_W['uniacid'], 'id' => $id));
	} else {
		pdo_update('storex_coupon', array('is_display' => 1), array('uniacid' => $_W['uniacid'], 'id' => $id));
	}
	message(error(0, $display_status ? '下架成功' : '上架成功'), referer(), 'ajax');
}

if ($op == 'modifystock') {
	$id = intval($_GPC['id']);
	$quantity = intval($_GPC['quantity']);
	$coupon = activity_get_coupon_info($id);
	if(empty($coupon)) {
		message('抱歉，卡券不存在或是已经被删除！');
	}
	pdo_update('storex_coupon', array('quantity' => $quantity), array('id' => $id, 'uniacid' => $_W['uniacid']));
	message(error(0, '修改库存成功'), referer(), 'ajax');
}

if ($op == 'delete') {
	$id = intval($_GPC['id']);
	$coupon_info = pdo_get('storex_coupon', array('uniacid' => $_W['uniacid'], 'id' => $id));
	if (empty($coupon_info)) {
		message('抱歉，卡券不存在或是已经被删除！');
	}
	if (COUPON_TYPE == WECHAT_COUPON) {
		$status = $coupon_api->DeleteCard($coupon_info['card_id']);
		if(is_error($status)) {
			message('删除卡券失败，错误为' . $status['message'], '', 'error');
		}
	}
	pdo_delete('storex_coupon', array('uniacid' => $_W['uniacid'], 'id' => $id));
	// pdo_delete('storex_coupon_record', array('uniacid' => $_W['uniacid'], 'couponid' => $id));
	// pdo_delete('activity_exchange', array('uniacid' => $_W['uniacid'], 'extra' => $id));
	pdo_delete('storex_coupon_store', array('uniacid' => $_W['uniacid'], 'couponid' => $id));
	
	message('卡券删除成功！', referer(), 'success');
}

if ($op == 'publish') {
	$setting = pdo_get('storex_set', array('weid' => $_W['uniacid']), array('source'));
	$couponid = intval($_GPC['cid']);
	if ($setting['source'] == 1) {
		$url = murl('entry', array('do' => 'coupon', 'op' => 'publish', 'id' => $couponid, 'm' => 'wn_storex'), true, true);
		$coupon_record = pdo_getall('storex_coupon_record', array('uniacid' => $_W['uniacid'], 'couponid' => $couponid, 'granttype' => 2),
				array('id', 'uniacid', 'uid', 'addtime'));
		if (!empty($coupon_record)) {
			foreach ($coupon_record as &$info) {
				$fansinfo = mc_fansinfo($info['uid']);
				$info['nickname'] = $fansinfo['nickname'];
				$info['addtime'] = date('Y-m-d H:i', $info['addtime']);
				$info['avatar'] = $fansinfo['avatar'];
			}
		}
		$total = count($coupon_record);
		message(error('0', array('url' => $url, 'record' => $coupon_record, 'total' => $total)), '', 'ajax');
	} else {
		$coupon = pdo_get('storex_coupon', array('id' => $couponid));
		if(empty($coupon)) {
			return message('卡券不存在或已经删除', '', 'error');
		}
		//二维码投入场景Id,文档中是写的19位的longint型，实际测试大于14位会丢失精度
		$qrcode_sceneid = sprintf('11%012d', $couponid);
		$coupon_qrcode = pdo_get('qrcode', array('qrcid' => $qrcode_sceneid, 'type' => 'card'));
		if (empty($coupon_qrcode)) {
			$insert = array(
				'uniacid' => $_W['uniacid'],
				'acid' => $_W['acid'],
				'qrcid' => $qrcode_sceneid,
				'keyword' => '',
				'name' => $coupon['title'],
				'model' => 1,
				'ticket' => '',
				'expire' => '',
				'url' => '',
				'createtime' => TIMESTAMP,
				'status' => '1',
				'type' => 'card',
			);
			pdo_insert('qrcode', $insert);
			$coupon_qrcode['id'] = pdo_insertid();
		}
		$response = ihttp_request($coupon_qrcode['url']);
		load()->classs('coupon');
		$coupon_api = new coupon();
		if ($response['code'] != '200' || empty($coupon_qrcode['url'])) {
			$coupon_qrcode_image = $coupon_api->QrCard($coupon['card_id'], $qrcode_sceneid);
			if (is_error($coupon_qrcode_image)) {
				if ($coupon_qrcode_image['errno'] == '40078') {
					pdo_update('storex_coupon', array('status' => 2), array('id' => $couponid));
				}
				message(error('1', '生成二维码失败，' . $coupon_qrcode_image['message']), '', 'ajax');
			}
			$couponid = $coupon_qrcode['id'];
			unset($coupon_qrcode['id']);
		
			$coupon_qrcode['url'] = $coupon_qrcode_image['show_qrcode_url'];
			$coupon_qrcode['ticket'] = $coupon_qrcode_image['ticket'];
			$coupon_qrcode['expire'] = TIMESTAMP + $coupon_qrcode_image['expire_seconds'];
			pdo_update('qrcode', $coupon_qrcode, array('id' => $couponid));
		}
		$coupon_qrcode['expire'] = date('Y-m-d H:i:s', $coupon_qrcode['expire']);
		//获取扫码记录
		$qrcode_list = pdo_getslice('qrcode_stat', array('qrcid' => $qrcode_sceneid), 10, $total, array('openid', 'createtime'));
		if (!empty($qrcode_list)) {
			$openids = array();
			foreach ($qrcode_list as &$row) {
				//由于粉丝不多，循环内直接查询
				$fans = mc_fansinfo($row['openid']);
				$row['nickname'] = $fans['nickname'];
				$row['avatar'] = $fans['avatar'];
				$row['addtime'] = $row['createtime'] = date('Y-m-d H:i:s', $row['createtime']);
			}
		}
		message(error('0', array('coupon' => $coupon_qrcode, 'record' => $qrcode_list, 'total' => $total)), '', 'ajax');
	}
}

if ($op == 'status') {
	$status = pdo_update('storex_set', array('source' => intval($_GPC['status'])), array('weid' => $_W['uniacid']));
	if (!empty($status)) {
		message(error(0, '修改成功'), referer(), 'ajax');
	} else {
		message(error(-1, '修改失败'), referer(), 'ajax');
	}
}

if ($op == 'sync') {
	$type = trim($_GPC['type']);
	if ($type == 1) {
		$cachekey = "wn_storex_couponsync:{$_W['uniacid']}";
		$cache = cache_delete($cachekey);
	}
	activity_wechat_coupon_sync();
	message(error(0, '更新卡券状态成功'), referer(), 'ajax');
}

include $this->template('couponmanage');