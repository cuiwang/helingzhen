<?php

function activity_get_coupon_type() {
	global $_W;
	$setting = pdo_get('storex_set', array('weid' => $_W['uniacid']), 'source');
	if ($setting['source'] == 1) {
		define('COUPON_TYPE', SYSTEM_COUPON);
	} else {
		define('COUPON_TYPE', WECHAT_COUPON);
	}
}

/**
 * 返回卡券类型的中文标题和英文标识，此标识与微信文档同步
 * @param int $type
 */
function activity_get_coupon_label($type = '') {
	$types = array(
		COUPON_TYPE_DISCOUNT => array('title' => '折扣券', 'value' => 'discount'),
		COUPON_TYPE_CASH => array('title' => '代金券', 'value' => 'cash'),
		COUPON_TYPE_GIFT => array('title' => '礼品券', 'value' => 'gift'),
		COUPON_TYPE_GROUPON => array('title' => '团购券', 'value' => 'groupon'),
		COUPON_TYPE_GENERAL => array('title' => '优惠券', 'value' => 'general_coupon'),
	);
	return $types[$type] ? $types[$type] : $types;
}

function activity_get_coupon_colors() {
	$colors = array(
		'Color010' => '#55bd47',
		'Color020' => '#10ad61',
		'Color030' => '#35a4de',
		'Color040' => '#3d78da',
		'Color050' => '#9058cb',
		'Color060' => '#de9c33',
		'Color070' => '#ebac16',
		'Color080' => '#f9861f',
		'Color081' => '#f08500',
		'Color082' => '#a9d92d',
		'Color090' => '#e75735',
		'Color100' => '#d54036',
		'Color101' => '#cf3e36',
		'Color102' => '#5e6671',
	);
	return $colors;
}

/**
 * 获取某卡券信息
 * @param int $couponid 卡券ID
 * @param int $uniacid 公众号ID
 * @return array
 */
function activity_get_coupon_info($id) {
	global $_W;
	$id = intval($id);
	$coupon = pdo_get('storex_coupon', array('uniacid' => $_W['uniacid'], 'id' => $id));
	if (empty($coupon)) {
		return error(1, '卡券不存在或是已经被删除');
	}
	$coupon['date_info'] = iunserializer($coupon['date_info']);
	if ($coupon['date_info']['time_type'] == '1') {
		$coupon['extra_date_info'] = '有效期:' . $coupon['date_info']['time_limit_start'] . '-' . $coupon['date_info']['time_limit_end'];
	} else {
		$coupon['extra_date_info'] = '有效期:领取后' . ($coupon['date_info']['deadline'] == 0 ? '当' : $coupon['date_info']['deadline']) . '天可用，有效期' . $coupon['date_info']['limit'] . '天';
	}
	$coupon['extra'] = iunserializer($coupon['extra']);	
	$coupon['logo_url'] = tomedia($coupon['logo_url']);
	$coupon['description'] = htmlspecialchars_decode($coupon['description']);
	$coupon_stores = pdo_getall('storex_coupon_store', array('uniacid' => $_W['uniacid'], 'couponid' => $coupon['id']), array(), 'storeid');
	if (!empty($coupon_stores)) {
		$stores = pdo_getall('storex_bases', array('id' => array_keys($coupon_stores)), array('title', 'id'), 'id');
		$coupon['location_id_list'] = $stores;
	}
	return $coupon;
}

/**
 * 指定会员领取指定卡券
 * @param int $openid 会员ID或者openid
 * @param int $id 卡券自增id
 * @param int $granttype 获取方式 :1兑换 2扫码
 * @return mixed
 */
function activity_user_get_coupon($id, $openid, $granttype = 1) {
	global $_W, $_GPC;
	if (empty($openid)) {
		$openid = $_W['openid'];
		if (empty($openid)) {
			$openid = $_W['member']['uid'];
		}
		if (empty($openid)) {
			return error(-1, '没有找到指定会员');
		}
	}
	$fan = mc_fansinfo($openid, '', $_W['uniacid']);
	$openid = $fan['openid'];
	if (empty($openid)) {
		return error(-1, '兑换失败');
	}
	$code = base_convert(md5(uniqid() . random(4)), 16, 10);
	$code = substr($code, 1, 16);
	$user = mc_fetch($fan['uid'], array('groupid'));
	$credit_names = array('credit1' => '积分', 'credit2' => '余额');
	$coupon = activity_get_coupon_info($id);
	$pcount = pdo_fetchcolumn("SELECT count(*) FROM " . tablename('storex_coupon_record') . " WHERE `openid` = :openid AND `couponid` = :couponid", array(':couponid' => $id, ':openid' => $openid));
	if (empty($coupon)) {
		return error(-1, '未找到指定卡券');
	} elseif (strtotime(str_replace('.', '-', $coupon['date_info']['time_limit_end'])) < strtotime(date('Y-m-d')) && $coupon['date_info']['time_type'] != 2) {
		return error(-1, '活动已结束');
	} elseif (strtotime(str_replace('.', '-', $coupon['date_info']['time_limit_start'])) > strtotime(date('Y-m-d')) && $coupon['date_info']['time_type'] != 2) {
		return error(-1, '活动未开始');
	} elseif ($coupon['quantity'] <= 0) {
		return error(-1, '卡券发放完毕');
	} elseif ($pcount >= $coupon['get_limit'] && !empty($coupon['get_limit'])) {
		return error(-1, '领取次数不足!');
	}
	$give = $_W['activity_coupon_id'] ? true :false;
	$uid = !empty($_W['member']['uid']) ? $_W['member']['uid'] : $fan['uid'];
	$insert = array(
		'couponid' => $id,
		'uid' => $uid,
		'uniacid' => $_W['uniacid'],
		'openid' => $fan['openid'],
		'code' => $code,
		'grantmodule' => $give ? $_W['activity_coupon_id'] : $_W['current_module']['name'],
		'addtime' => TIMESTAMP,
		'status' => 1,
		'granttype' => $granttype,
	);
	if ($granttype == 1) {
		$exchange = pdo_get('storex_activity_exchange', array('uniacid' => $_W['uniacid'], 'extra' => $id), array());
		$insert['remark'] = $give ? '系统赠送' : '用户使用' . $exchange['credit'] . $credit_names[$exchange['credittype']] . '兑换';
	} elseif ($granttype == 2) {
		$insert['remark'] = "扫码获取";
	} elseif ($granttype == 3) {
		$insert['remark'] = "系统派发";
	}
	if ($coupon['source'] == 2) {
		$insert['card_id'] = $coupon['card_id'];
		$insert['code'] = '';
	}
	if (empty($insert['card_id'])) {
		$insert['card_id'] = $coupon['card_id'];
	}
	pdo_insert('storex_coupon_record', $insert);
	$insert_id = pdo_insertid();
	pdo_update('storex_coupon', array('quantity' => $coupon['quantity'] - 1, 'dosage' => $coupon['dosage'] + 1), array('uniacid' => $_W['uniacid'],'id' => $coupon['id']));
	if ($granttype == 1) {
		pdo_update('storex_activity_exchange', array('num' => ($exchange['num'] + 1)), array('id' => $exchange['id']));
	}
	return $insert_id;
}

/**
 * 获取当前会员当前已有卡券及使用情况
 * @return array
 */
function activity_get_user_couponlist() {
	global $_W, $_GPC;
	activity_get_coupon_type();
	$uid = $_W['member']['uid'];
	$coupon_record = pdo_getall('storex_coupon_record', array('uniacid' => $_W['uniacid'], 'openid' => $_W['openid'], 'status' => 1), array(), '', 'addtime DESC');
	foreach ($coupon_record as $key => $record) {
		$coupon = activity_get_coupon_info($record['couponid']);
		if ($coupon['source'] != COUPON_TYPE) {
			unset($coupon_record[$key]);
			continue;
		}
		if ($coupon['status'] != '3') {
			pdo_delete('storex_coupon_record', array('id' => $record['id']));
			unset($coupon_record[$key]);
			continue;
		}
		if (is_error($coupon)) {
			unset($coupon_record[$key]);
			continue;
		}
		if (is_array($coupon['date_info']) && $coupon['date_info']['time_type'] == '2') {
			$starttime = $record['addtime'] + $coupon['date_info']['deadline'] * 86400;
			$endtime = $starttime + ($coupon['date_info']['limit']) * 86400;
			if ($endtime < time()) {
				unset($coupon_record[$key]);
				pdo_update('storex_coupon_record', array('status' => 2), array('id' => $record['id']));
				continue;
			} else {
				$coupon['extra_date_info'] = '有效期:' . date('Y.m.d', $starttime) . '-' . date('Y.m.d', $endtime);
			}
		}
		if (is_array($coupon['date_info']) && $coupon['date_info']['time_type'] == '1') {
			$endtime = str_replace('.', '-', $coupon['date_info']['time_limit_end']);
			$endtime = strtotime($endtime);
			if ($endtime < time()) {
				pdo_update('storex_coupon_record', array('status' => 2), array('id' => $record['id']));
				// pdo_delete('coupon_record', array('id' => $record['id']));
				unset($coupon_record[$key]);
				continue;
			}

		}
		$coupon_record[$key] = $coupon;
		$coupon_record[$key]['recid'] = $record['id'];
		$coupon_record[$key]['code'] = $record['code'];
	}
	return $coupon_record;
}

function activity_paycenter_get_coupon() {
	$coupon_owned = activity_get_user_couponlist();
	foreach ($coupon_owned as $key => &$val) {
		if (empty($val['code'])) {
			unset($val);
		}
		if ($val['type'] == '1' || $val['type'] == '2') {
			$coupon_available[] = $val;
		}
	}
	return $coupon_available;
}

function activity_coupon_consume($couponid, $recid, $store_id) {
	global $_W, $_GPC;
	$clerk_name = $_W['user']['name'];
	$clerk_id = $_W['user']['clerk_id'];
	$clerk_type = $_W['user']['type'];
	$coupon_record = pdo_get('storex_coupon_record', array('id' => $recid, 'status' => '1'));
	if (empty($coupon_record)) {
		return error(-1, '没有可使用的卡券');
	}
	$coupon_info = activity_get_coupon_info($couponid);
	if (empty($coupon_info)) {
		return error(-1, '没有指定的卡券信息');
	}
	$uid = $coupon_record['uid'];
	$location_id_list = $coupon_info['location_id_list'];
	if (!empty($location_id_list)) {
		if (!in_array($store_id, array_keys($location_id_list))) {
			return error(-1, '该门店无法使用');
		}
	}
	$date_info = iunserializer($coupon_info['date_info']);
	if ($date_info['time_type'] == '1') {
		if (strtotime(str_replace('.', '-', $date_info['time_limit_start'])) > strtotime(date('Y-m-d'))) {
			return error(-1, '卡券活动尚未开始');
		} elseif (strtotime(str_replace('.', '-', $date_info['time_limit_end'])) < strtotime(date('Y-m-d'))) {
			return error(-1, '卡券活动已经结束');
		}
	} else {
		$starttime = strtotime(date('Y-m-d', $coupon_record['addtime'])) + $date_info['deadline'] * 86400;
		$endtime = $starttime + $date_info['limit'] * 86400;
		if ($starttime > strtotime(date('Y-m-d'))) {
			return error(-1, '卡券活动尚未开始');
		} elseif ($endtime < strtotime(date('Y-m-d'))) {
			return error(-1, '卡券活动已经结束');
		}
	}
	if ($coupon_info['source'] == '2') {
		load()->classs('coupon');
		$coupon_api = new coupon($_W['acid']);
		$status = $coupon_api->ConsumeCode(array('code' => $coupon_record['code']));
		if (is_error($status)) {
			return error(-1, $status['message']);
		}
	}
	$update = array(
		'status' => 2 ,
		'usetime' => TIMESTAMP,
		'clerk_name' => $clerk_name,
		'clerk_id' => intval($clerk_id),
		'clerk_type' => $clerk_type,
		'store_id' => $store_id
	);
	pdo_update('storex_coupon_record', $update, array('id' => $coupon_record['id']));
	return true;
}

//卡券状态
function activity_get_coupon_status() {
	return array(
		'CARD_STATUS_NOT_VERIFY' => 1, //待审核
		'CARD_STATUS_VERIFY_FAIL' => 2, //未通过
		'CARD_STATUS_VERIFY_OK' => 3, //通过审核
		'CARD_STATUS_USER_DELETE' => 4,
		'CARD_STATUS_DELETE' => 4,//卡券被商户删除
		'CARD_STATUS_USER_DISPATCH' => 5, //在公众平台投放过的卡券
		'CARD_STATUS_DISPATCH' => 5, //在公众平台投放过的卡券
	);
}

//微信卡券同步
function activity_wechat_coupon_sync() {
	global $_W;
	$cachekey = "wn_storex_couponsync:{$_W['uniacid']}";
	$cache = cache_load($cachekey);
	if (!empty($cache) && $cache['expire'] > time()) {
		return false;
	}
	load()->classs('coupon');
	$coupon_api = new coupon();
	activity_get_coupon_type();
	$cards = pdo_getall('storex_coupon', array('uniacid' => $_W['uniacid'], 'source' => COUPON_TYPE), array('id', 'status', 'card_id', 'acid'));
	foreach ($cards as $val) {
		$card = $coupon_api->fetchCard($val['card_id']);
		if (is_error($card)) {
			return error(-1, $card['message']);
		}
		$type = strtolower($card['card_type']);
		$coupon_status = activity_get_coupon_status();
		$status = $coupon_status[$card[$type]['base_info']['status']];
		$status = pdo_update('storex_coupon', array('status' => $status), array('id' => $val['id']));
	}
	cache_write($cachekey, array('expire' => time() + 1800));
	return true;
}

/*
 * 获取（新用户，老用户，活跃用户， 沉寂用户，自定义用户的人数）
* @param string $type 用户类型
* @param array $param 获取自定义用户所需参数
*/
function activity_get_member_by_type($type, $param = array()) {
	activity_get_coupon_type();
	global $_W;
	$types = array('new_member', 'old_member', 'quiet_member', 'activity_member', 'group_member', 'cash_time', 'openids');
	if (!in_array($type, $types)) {
		return error('1', '没有匹配的用户类型');
	}
	$members = array();
	//获取会员属性
	$propertys = activity_storex_member_propertys();
	//新会员，一个月内消费不超过一次
	if ($type == 'new_member') {
		$property_time = strtotime('-' . $propertys['newmember'] . ' month', time());
		$mc_members = pdo_getall('mc_members', array('uniacid' => $_W['uniacid'], 'createtime >' => $property_time), array('uid'), 'uid');
		$mc_uids = array_keys($mc_members);
		$uids = implode(',',$mc_uids);
		$mc_cash_record_sql = "SELECT uid FROM " . tablename('mc_cash_record') . " WHERE uid IN ( :uids ) AND createtime > :time GROUP BY uid HAVING COUNT(*) < 2 " ;
		$mc_cash_record = pdo_fetchall($mc_cash_record_sql, array(':uids' => $uids, ':time' => $property_time));
		if (!empty($mc_cash_record)) {
			foreach ($mc_cash_record as $v) {
				if (!in_array($v['uid'], $mc_uids)) {
					unset($mc_uids[$v['uid']]);
				}
			}
		}
	}
	//老会员，注册超过两个月的会员
	if ($type == 'old_member') {
		$property_time = strtotime('-' . $propertys['oldmember'] . ' month', time());
		$mc_members = pdo_getall('mc_members', array('uniacid' => $_W['uniacid'], 'createtime <' => $property_time), array('uid'), 'uid');
		$mc_uids = array_keys($mc_members);
	}
	if ($type == 'activity_member') {
		$property_time = strtotime('-' . $propertys['activitymember'] . ' month', time());
		$mc_cash_record_sql = "SELECT * FROM " . tablename('mc_cash_record') . " WHERE uniacid = :uniacid AND createtime > :time GROUP BY uid HAVING COUNT(*) > 2";
		$mc_cash_record = pdo_fetchall($mc_cash_record_sql, array(':uniacid' => $_W['uniacid'], ':time' => $property_time), 'uid');
		$mc_uids = array_keys($mc_cash_record);
	}
	if (!empty($mc_uids)) {
		$members = pdo_getall('mc_mapping_fans', array('uid' => $mc_uids, 'openid !=' => ''), array('openid'), 'openid');
	}
	if ($type == 'quiet_member') {
		$property_time = strtotime('-' . $propertys['quietmember'] . ' month', time());
		$members = pdo_fetchall("SELECT a.openid FROM " . tablename('mc_mapping_fans') . " as a LEFT JOIN " 
				. tablename('mc_cash_record')." as b ON a.uid = b.uid WHERE a.uniacid = :uniacid AND b.id is null 
				GROUP BY a.uid ", array(':uniacid' => $_W['uniacid']), 'openid');
		$member = pdo_fetchall("SELECT a.openid FROM " . tablename('mc_mapping_fans') . " as a LEFT JOIN "
				 . tablename('mc_cash_record')." as b ON a.uid = b.uid WHERE a.uniacid = :uniacid AND b.createtime > :time 
				GROUP BY a.uid ", array(':uniacid' => $_W['uniacid'], ':time' => $property_time), 'openid');
		if (!empty($member)) {
			foreach ($member as $key => $mem) {
				unset($members[$key]);
			}
		}
	}
	if ($type == 'group_member') {
		if (empty($param)) {
			return error(1, '请选择会员组');
		}
		if (COUPON_TYPE == WECHAT_COUPON) {
			$members = pdo_getall('mc_mapping_fans', array('uniacid' => $_W['uniacid']), array(), 'openid');
			foreach ($members as $key => &$fan) {
				$fan['groupid'] = explode(',', $fan['groupid']);
				if (!is_array($fan['groupid']) || !in_array($param['groupid'], $fan['groupid'])) {
					unset($members[$key]);
				}
			}
		} else {
			$mc_members = pdo_getall('mc_members', array('groupid' => $param['groupid'], 'uniacid' => $_W['uniacid']), array('uid'), 'uid');
			$mc_uids = array_keys($mc_members);
			$members = array();
			if (!empty($mc_uids)) {
				$members = pdo_getall('mc_mapping_fans', array('openid !=' => '', 'uid' => $mc_uids), array('openid'), 'openid');
			}
		}
	}
	if ($type == 'cash_time') {
		$mc_cash_record = pdo_fetchall("SELECT uid FROM " . tablename('mc_cash_record') . " WHERE createtime >= :start AND createtime <= :end", 
				array(':start' => $param['start'], ':end' => $param['end']), 'uid');
		$mc_uids = array_keys($mc_cash_record);
		$members = array();
		if (!empty($mc_uids)) {
			$members = pdo_getall('mc_mapping_fans', array('uniacid' => $_W['uniacid'], 'uid' => $mc_uids), array('openid'), 'openid');
		}
	}
	if ($type == 'openids') {
		$members = json_decode($_COOKIE['fans_openids'.$_W['uniacid']]);
	}

	if (is_array($members)) {
		$member = $type == 'openids' ? $members : array_keys($members);
		$members = array();
		$members['members'] = $member;
		$members['total'] = count($members['members']);
	} else {
		$members = array();
	}
	return $members;
}

function activity_storex_member_propertys() {
	global $_W;
	$current_property_info = pdo_get('storex_mc_member_property', array('uniacid' => $_W['uniacid']));
	if (!empty($current_property_info)) {
		$propertys = json_decode($current_property_info['property'], true);
	} else {
		$propertys = array(
			'newmember' => '1',
			'oldmember' => '2',
			'activitymember' => '1',
			'quietmember' => '1'
		);
	}
	return $propertys;
}

/**
 * 同步微信门店最新状态
 */
function activity_storex_sync() {
	global $_W;
	load()->classs('coupon');
	$cachekey = "storexsync:{$_W['uniacid']}";
	$cache = cache_load($cachekey);
	if (!empty($cache) && $cache['expire'] > time()) {
		return false;
	}
	$stores = pdo_getall('storex_activity_stores', array('uniacid' => $_W['uniacid'], 'source' => 2));
	foreach ($stores as $val) {
		if ($val['status'] == 3) {
			continue;
		}
		$acc = new coupon($_W['acid']);
		$location = $acc->LocationGet($val['location_id']);
		if (is_error($location)) {
			return error(-1, $location['message']);
		}
		$location = $location['business']['base_info'];
		$status2local = array('', 3, 2, 1, 3);
		$location['status'] = $status2local[$location['available_state']];
		$location['location_id'] = $location['poi_id'];
		$category_temp = explode(',', $location['categories'][0]);
		$location['category'] = iserializer(array('cate' => $category_temp[0], 'sub' => $category_temp[1], 'clas' => $category_temp[2]));
		$location['photo_list'] = iserializer($location['photo_list']);
		unset($location['categories'], $location['poi_id'], $location['update_status'], $location['available_state'], $location['offset_type'], $location['sid'], $location['type'], $location['qualification_list'], $location['upgrade_comment'], $location['upgrade_status'], $location['mapid']);
		pdo_update('storex_activity_stores', $location, array('uniacid' => $_W['uniacid'], 'id' => $val['id']));
	}
	cache_write($cachekey, array('expire' => time() + 1800));
	return true;
}

/*
 * 会员积分|优惠券信息变更操作员
* */
function storex_account_change_operator($clerk_type, $store_id, $clerk_id) {
	global $stores, $clerks, $_W;
	if (empty($stores) || empty($clerks)) {
		$clerks = pdo_getall('storex_activity_clerks', array('uniacid' => $_W['uniacid']), array('id', 'name'), 'id');
		$stores = pdo_getall('storex_activity_stores', array('uniacid' => $_W['uniacid']), array('id', 'business_name', 'branch_name'), 'id');
	}
	$data = array(
		'clerk_cn' => '',
		'store_cn' => '',
	);
	if ($clerk_type == 1) {
		$data['clerk_cn'] = '系统';
	} elseif ($clerk_type == 2) {
		$data['clerk_cn'] = pdo_fetchcolumn('SELECT username FROM ' . tablename('users') . ' WHERE uid = :uid', array(':uid' => $clerk_id));
	} elseif ($clerk_type == 3) {
		if (empty($clerk_id)) {
			$data['clerk_cn'] = '本人操作';
		} else {
			$data['clerk_cn'] = $clerks[$clerk_id]['name'];
		}
		$data['store_cn'] = $stores[$store_id]['business_name'] . ' ' . $stores[$store_id]['branch_name'];
	}
	if (empty($data['store_cn'])) {
		$data['store_cn'] = '暂无门店信息';
	}
	if (empty($data['clerk_cn'])) {
		$data['clerk_cn'] = '暂无操作员信息';
	}
	return $data;
}

/**
 * 获取礼品兑换信息(仅用于判断真实物品或活动参与次数)
 * @param int $exchangeid 兑换ID
 * @param int $uniacid 公众号ID
 * @return array
 **/
function activity_get_exchange_info($exchangeid, $uniacid = 0) {
	global $_W;
	$uniacid = intval($uniacid) ? intval($uniacid) : $_W['uniacid'];
	$exchange = pdo_get('storex_activity_exchange', array('id' => $exchangeid, 'uniacid' => $uniacid));
	if (!empty($exchange) && !empty($exchange['extra'])) {
		$exchange['extra'] = iunserializer($exchange['extra']);
	}
	return $exchange;
}

/**
 * 指定会员兑换指定真实物品
 * @param int $uid  会员UID
 * @param int $exid  真实物品ID
 * @return mixed
 */
function activity_user_get_goods($uid, $exid) {
	global $_W;
	$exid = intval($exid);
	$uid = intval($uid);
	$exchange = activity_get_exchange_info($exid, $_W['uniacid']);
	if (empty($exchange)) {
		return error(-1, '没有指定的实物兑换');
	}
	if ($exchange['starttime'] > TIMESTAMP) {
		return error(-1, '该实物兑换尚未开始');
	}
	if ($exchange['endtime'] < TIMESTAMP) {
		return error(-1, '该实物兑换已经结束');
	}
	$pnum = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('storex_activity_exchange_trades') . ' WHERE uniacid = :uniacid AND uid = :uid AND exid = :exid', array(':uniacid' => $_W['uniacid'], ':uid' => $uid, ':exid' => $exid));
	if ($pnum >= $exchange['pretotal']) {
		return error(-1, '该实物兑换每人只能使用' . $exchange['pretotal'] . '次');
	}
	if ($exchange['num'] >= $exchange['total']) {
		return error(-1, '该实物兑换已兑换完');
	}
	$data = array(
		'uniacid' => $_W['uniacid'],
		'uid' => $uid,
		'type' => 3,
		'exid' => $exid,
		'createtime' => TIMESTAMP,
	);
	pdo_insert('storex_activity_exchange_trades', $data);
	$insert_id = pdo_insertid();
	if (empty($insert_id)) {
		return error(-1, '实物兑换失败');
	}
	$insert = array(
		'tid' => $insert_id,
		'uniacid' => $_W['uniacid'],
		'uid' => $uid,
		'status' => 0,
		'exid' => $exid,
		'createtime' => TIMESTAMP
	);
	pdo_insert('storex_activity_exchange_trades_shipping', $insert);
	pdo_update('storex_activity_exchange', array('num' => $exchange['num'] + 1), array('id' => $exid, 'uniacid' => $_W['uniacid']));
	return $insert_id;
}