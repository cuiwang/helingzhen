<?php
/**
 * [WECHAT 2017]
 
 */
defined('IN_IA') or exit('Access Denied');
$_W['page']['title'] = '卡券';

load()->model('mc');

if (!uni_user_permission_check('activity_coupon_display', false) && !uni_user_permission_check('activity_token_display', false)) {
	message('您没有进行该操作的权限', referer(), 'error');
}
$dos = array('display', 'post', 'del', 'detail', 'toggle', 'modifystock', 'sync', 'selfconsume', 'publish', 'exchange_coupon_type');
$do = in_array($do, $dos) ? $do : 'display';

$type = intval($_GPC['type']);

$creditnames = array();
$unisettings = uni_setting($_W['uniacid'], array('creditnames'));
$uni_setting = pdo_get('uni_settings', array('uniacid' => $_W['uniacid']), array('coupon_type'));
foreach ($unisettings['creditnames'] as $key=>$credit) {
	if (!empty($credit['enabled'])) {
		$creditnames[$key] = $credit['title'];
	}
}
if($do == 'display') {
	$_W['page']['title'] = '卡券管理 - 粉丝营销';
	$location_store = pdo_getall('activity_stores', array('uniacid' => $_W['uniacid'], 'status' => 1, 'location_id !=' => ''), array('id', 'location_id', 'business_name', 'branch_name', 'address'));
	$module = uni_modules();
	$groups = mc_fans_groups();
	$pageindex = max(1, $_GPC['page']);
	$psize = 15;
	$condition = array();
	$condition_sql = $join_sql = '';
	$condition_sql = ' c.uniacid = :uniacid';
	$condition[':uniacid'] = $_W['uniacid'];
	$condition_sql .= " AND c.source = :source";
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
	
	if (!empty($_GPC['groupid'])) {
		$join_sql .= " LEFT JOIN ".tablename('coupon_groups')." AS g ON c.id = g.couponid ";
		$condition_sql .= " AND g.groupid = :groupid";
		$condition[':groupid'] = intval($_GPC['groupid']);
	}
	
	if (!empty($_GPC['storeid'])) {
		$join_sql .= " LEFT JOIN ".tablename('coupon_store')." AS s ON c.id = s.couponid ";
		$condition_sql .= " AND s.storeid = :storeid";
		$condition[':storeid'] = intval($_GPC['storeid']);
	}
	
	if (!empty($_GPC['moduleid'])) {
		$join_sql .= " LEFT JOIN ".tablename('coupon_modules')." AS m ON c.id = m.couponid ";
		$condition_sql .= " AND m.module = :module";
		$condition[':module'] = $_GPC['moduleid'];
	}
	$list = pdo_fetchall("SELECT * FROM " . tablename('coupon') . " AS c " . $join_sql . " WHERE  " . $condition_sql . " ORDER BY c.id DESC LIMIT ".($pageindex - 1) * $psize.','.$psize, $condition);
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('coupon') . " AS c " . $join_sql . " WHERE  " . $condition_sql, $condition);
	foreach($list as &$row) {
		if (empty($row['card_id'])) {
			pdo_delete('coupon', array('id' => $row['id']));
		}
		$row['date_info'] = iunserializer($row['date_info']);
		if ($row['date_info']['time_type'] == 1) {
			$row['date_info'] = $row['date_info']['time_limit_start'].'-'. $row['date_info']['time_limit_end'];
		} elseif($row['date_info']['time_type'] == 2) {
			$row['date_info'] = '领取后'.$row['date_info']['limit'].'天有效';
		}
		$row['type'] = activity_coupon_type_label($row['type']);
		if ($row['status'] == 1) {
			$card = $coupon_api->fetchCard($row['card_id']);
			$coupon_status = activity_coupon_status();
			$status = $coupon_status[$card[$row['type'][1]]['base_info']['status']];
			pdo_update('coupon', array('status' => $status), array('uniacid' => $_W['uniacid'], 'card_id' => $row['card_id']));
		}
	}
	$pager = pagination($total, $pageindex, $psize);
	template('activity/coupon');
}
if($do == 'post') {
	$id = intval($_GPC['id']);
	$type = intval($_GPC['type']);
	$coupon_title = activity_coupon_type_label($type);
	$coupon_title = $coupon_title[0]; 	$groups = mc_fans_groups();
	if (checksubmit('submit')) {
		$coupon = Card::create($type);
		$coupon->logo_url = empty($_GPC['logo_url']) ? urlencode($setting['logourl']) : urlencode(trim($_GPC['logo_url']));
		$coupon->brand_name = $_GPC['brand_name'];
		$coupon->title = substr(trim($_GPC['title']), 0,27);
		$coupon->sub_title = trim($_GPC['sub_title']);
		$coupon->color = empty($_GPC['color']) ? 'Color082' : $_GPC['color'];
		$coupon->notice = $_GPC['notice'];
		$coupon->service_phone = $_GPC['service_phone'];
		$coupon->description = $_GPC['description'];
		$coupon->get_limit = intval($_GPC['get_limit']);
		$coupon->can_share = intval($_GPC['can_share']) ? true : false;
		$coupon->can_give_friend = intval($_GPC['can_give_friend']) ? true : false;
				if (intval($_GPC['time_type']) == COUPON_TIME_TYPE_RANGE) {
			$coupon->setDateinfoRange($_GPC['time_limit']['start'], $_GPC['time_limit']['end']);
		} else {
			$coupon->setDateinfoFix($_GPC['deadline'], $_GPC['limit']);
		}
				if(!empty($_GPC['promotion_url_name']) && !empty($_GPC['promotion_url'])) {
			$coupon->setPromotionMenu($_GPC['promotion_url_name'], $_GPC['promotion_url_sub_title'], $_GPC['promotion_url']);
		}
				if (!empty($_GPC['location-select'])) {
			$location_list = explode('-', $_GPC['location-select']);
			if (!empty($location_list)) {
				$coupon->setLocation($location_list);
			}
		}
		
		$coupon->setCustomMenu('立即使用', '', murl('entry', array('m' => 'paycenter', 'do' => 'consume'), true, true));
		$coupon->setQuantity($_GPC['quantity']);
		$coupon->setCodetype($_GPC['code_type']);
				$coupon->discount = intval($_GPC['discount']);
				$coupon->least_cost = $_GPC['least_cost'] * 100;
		$coupon->reduce_cost = $_GPC['reduce_cost'] * 100;
				$coupon->gift = $_GPC['gift'];
				$coupon->deal_detail = $_GPC['deal_detail'];
				$coupon->default_detail = $_GPC['default_detail'];
		
		$check = $coupon->validate();
		if (is_error($check)) {
			message($check['message'], '', 'error');
		}
		if (COUPON_TYPE == WECHAT_COUPON) {
			$status = $coupon_api->CreateCard($coupon->getCardData());
			if(is_error($status)) {
				message($status['message'], '', 'error');
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
		$card_exists = pdo_get('coupon', array('card_id' => $coupon->card_id), array('id'));
		if(empty($card_exists)) {
			pdo_insert('coupon', $cardinsert);
			$cardid = pdo_insertid();
		} else {
			$cardid = $card_exists['id'];
			unset($cardinsert['status']);
			pdo_update('coupon', $cardinsert, array('id' => $cardid));
		}
		$_GPC['module-select'] = trim($_GPC['module-select']);
		if(!empty($_GPC['module-select'])) {
			$enabled_modules = explode('@', $_GPC['module-select']);
			pdo_delete('coupon_modules', array('couponid' => $cardid));
			foreach($enabled_modules as $modulename) {
				$data = array(
					'uniacid' => $_W['uniacid'],
					'acid' => $_W['acid'],
					'couponid' => $cardid,
					'module' => $modulename
				);
				pdo_insert('coupon_modules', $data);
			}
		}
		$_GPC['groups'] = trim($_GPC['groups']);
		if (!empty($_GPC['groups'])) {
			$groups = explode('-', $_GPC['groups']);
			if (is_array($groups)) {
				pdo_delete('coupon_groups', array('couponid' => $cardid));
				foreach ($groups as $group) {
					$data = array(
						'uniacid' => $_W['uniacid'],
						'groupid' => $group,
						'couponid' => $cardid
					);
					pdo_insert('coupon_groups', $data);
				}
			}
		}
				if (!empty($location_list)) {
			pdo_delete('coupon_store', array('couponid' => $cardid));
			foreach ($location_list as $storeid) {
				$data = array(
					'uniacid' => $_W['uniacid'],
					'storeid' => $storeid,
					'couponid' => $cardid
				);
				pdo_insert('coupon_store', $data);
			}
		}
		message('卡券更新成功', url('activity/coupon'), 'success');
	}
	$location_store = pdo_getall('activity_stores', array('uniacid' => $_W['uniacid'], 'status' => 1, 'source' => COUPON_TYPE), array('id', 'location_id', 'business_name', 'branch_name', 'address'));

	$module = uni_modules();
	
	template('activity/coupon-post');
} elseif ($do == 'detail') {
	$id = intval($_GPC['id']);
	$type = intval($_GPC['type']);
	$coupon_title = activity_coupon_type_label($type);
	$coupon_title = $coupon_title[0]; 	$groups = mc_fans_groups();
	$colors = activity_coupon_colors();
	
	if(!empty($id)) {
		$coupon = activity_coupon_info($id);
		if(empty($coupon)) {
			message('卡券不存在或是已经删除', '', 'error');
		}
	}
	$coupon['location_count'] = count($coupon['location_id_list']);
	if ($coupon['type'] == COUPON_TYPE_CASH) {
		$coupon['detail']['least_cost'] = $coupon['extra']['least_cost'] * 0.01;
		$coupon['detail']['reduce_cost'] = $coupon['extra']['reduce_cost'] * 0.01;
	}
	template('activity/coupon-detail');
} elseif ($do == 'del') {
	$id = intval($_GPC['id']);
	$row = pdo_get('coupon', array('uniacid' => $_W['uniacid'], 'id' => $id));
	if (empty($row)) {
		message('抱歉，卡券不存在或是已经被删除！');
	}
	if (COUPON_TYPE == WECHAT_COUPON) {
		$return = $coupon_api->DeleteCard($row['card_id']);
		if(is_error($return)) {
			message('删除卡券失败，错误为' . $return['message'], '', 'error');
		}
	}
	pdo_delete('coupon', array('uniacid' => $_W['uniacid'], 'id' => $id));
	pdo_delete('coupon_record', array('uniacid' => $_W['uniacid'], 'couponid' => $id));
	pdo_delete('activity_exchange', array('uniacid' => $_W['uniacid'], 'extra' => $id));
	pdo_delete('coupon_groups', array('uniacid' => $_W['uniacid'],'couponid' => $id));
	pdo_delete('coupon_groups', array('uniacid' => $_W['uniacid'],'couponid' => $id));
	
	message('卡券删除成功！',url('activity/coupon/display'), 'success');
} elseif ($do == 'toggle') {
	$id = intval($_GPC['id']);
	$display_status = pdo_getcolumn('coupon', array('id' => $id, 'uniacid' => $_W['uniacid']), 'is_display');
	if($display_status == 1) {
		pdo_update('coupon', array('is_display' => 0), array('uniacid' => $_W['uniacid'], 'id' => $id));
	} else {
		pdo_update('coupon', array('is_display' => 1), array('uniacid' => $_W['uniacid'], 'id' => $id));
	}
	message(error(0, $display_status ? '下架成功' : '上架成功'), referer(), 'ajax');
} elseif ($do == 'modifystock') { 	$id = intval($_GPC['id']);
	$quantity = intval($_GPC['quantity']);
	$coupon = activity_coupon_info($id);
	if(empty($coupon)) {
		message('抱歉，卡券不存在或是已经被删除！');
	}
	pdo_update('coupon', array('quantity' => $quantity), array('id' => $id, 'uniacid' => $_W['uniacid']));
		$modify_quantity = $quantity - $coupon['quantity'];
	if ($coupon['source'] == WECHAT_COUPON) {
		$return = $coupon_api->ModifyStockCard($coupon['card_id'], $modify_quantity);
		if(is_error($return)) {
			message(error(1, '修改卡券库存失败，错误为' . $return['message']), '', 'ajax');
		}
	}
	message(error(0, '修改库存成功'), referer(), 'ajax');
} elseif ($do == 'sync') {
	$type = trim($_GPC['type']);
	if ($type == '1') {
		$cachekey = "couponsync:{$_W['uniacid']}";
		$cache = cache_delete($cachekey);
	}
	activity_coupon_sync();
	message(error(0, '更新卡券状态成功'), url('activity/coupon'), 'ajax');
} elseif ($do == 'selfconsume') {
	$id = intval($_GPC['id']);
	$coupon = activity_coupon_info($id);
	if(empty($coupon)) {
		message('抱歉，卡券不存在或是已经被删除！');
	}
	if (empty($coupon['location_id_list'])) {
		message(error(1, '该卡券未设置适用门店,无法设置自助核销'), '', 'ajax');
	}
	
	if ($coupon['source'] == WECHAT_COUPON) {
		$data = array(
			'card_id' => $coupon['card_id'],
			'is_open' => empty($coupon['is_selfconsume']) ? true : false,
		);
		$return = $coupon_api->selfConsume($data);
		if(is_error($return)) {
			message(error(1, '设置自助核销失败，错误为' . $return['message']), '', 'ajax');
		}
	}
	pdo_update('coupon', array('is_selfconsume' => empty($coupon['is_selfconsume']) ? 1 : 0), array('id' => $id));
	message(error(0, '设置自助核销成功'), url('activity/coupon'), 'ajax');
} elseif ($do == 'publish') {
	$cid = intval($_GPC['cid']);
	$coupon = pdo_get('coupon', array('id' => $cid));
	if(empty($coupon)) {
		return message('卡券不存在或已经删除', '', 'error');
	}
		$qrcode_sceneid = sprintf('11%012d', $cid);
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
	if ($response['code'] != '200' || empty($coupon_qrcode['url'])) {
		$coupon_qrcode_image = $coupon_api->QrCard($coupon['card_id'], $qrcode_sceneid);
		if (is_error($coupon_qrcode_image)) {
			if ($coupon_qrcode_image['errno'] == '40078') {
				pdo_update('coupon', array('status' => 2), array('id' => $cid));
			}
			message(error('1', '生成二维码失败，' . $coupon_qrcode_image['message']), '', 'ajax');
		}
		$cid = $coupon_qrcode['id'];
		unset($coupon_qrcode['id']);
		
		$coupon_qrcode['url'] = $coupon_qrcode_image['show_qrcode_url'];
		$coupon_qrcode['ticket'] = $coupon_qrcode_image['ticket'];
		$coupon_qrcode['expire'] = TIMESTAMP + $coupon_qrcode_image['expire_seconds'];
		pdo_update('qrcode', $coupon_qrcode, array('id' => $cid));
	}
	$coupon_qrcode['expire'] = date('Y-m-d H:i:s', $coupon_qrcode['expire']);
		$qrcode_list = pdo_getslice('qrcode_stat', array('qrcid' => $qrcode_sceneid), 10, $total, array('openid', 'createtime'));
	if (!empty($qrcode_list)) {
		$openids = array();
		foreach ($qrcode_list as &$row) {
						$fans = mc_fansinfo($row['openid']);
			$row['nickname'] = $fans['nickname'];
			$row['avatar'] = $fans['avatar'];
			$row['createtime'] = date('Y-m-d H:i:s', $row['createtime']);
		}
	}
	message(error('0', array('coupon' => $coupon_qrcode, 'record' => $qrcode_list, 'total' => $total)), '', 'ajax');
} elseif ($do == 'exchange_coupon_type') {
	$status = pdo_update('uni_settings', array('coupon_type' => intval($_GPC['status'])), array('uniacid' => $_W['uniacid']));
	if (!empty($status)) {
		cache_delete("unisetting:{$_W['uniacid']}");
		message(error(0, '修改成功'), referer(), 'ajax');
	} else {
		message(error(-1, '修改失败'), referer(), 'ajax');
	}
}
