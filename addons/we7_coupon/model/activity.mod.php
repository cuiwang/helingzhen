<?php
defined('IN_IA') or exit('Access Denied');

function we7_coupon_activity_coupon_grant($id, $openid) {
	activity_coupon_type_init();
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
	$coupon = activity_coupon_info($id);
	$pcount = pdo_fetchcolumn("SELECT count(*) FROM " . tablename('coupon_record') . " WHERE `openid` = :openid AND `couponid` = :couponid", array(':couponid' => $id, ':openid' => $openid));
	$coupongroup = pdo_fetchall("SELECT * FROM " . tablename('coupon_groups') . " WHERE `couponid` = :couponid", array(':couponid' => $id), 'groupid');
	$coupon_group = array_keys($coupongroup);
	$member = pdo_get('mc_members', array('uniacid' => $_W['uniacid'], 'uid' => $fan['uid']));
	if (COUPON_TYPE == WECHAT_COUPON) {
		$fan_groups = $fan['tag']['tagid_list'];
	} else {
		$fan_groups[] = $user['groupid'];
	}
	$group = @array_intersect($coupon_group, $fan_groups);
	if (empty($coupon)) {
		return error(-1, '未找到指定卡券');
	} elseif (empty($group) && !empty($coupon_group)) {
		if (!empty($fan_groups)) {
			return error(-1, '无权限兑换');
		} else {
			if (is_array($coupon_group) && !in_array('0', $coupon_group)) {
				return error(-1, '无权限兑换');
			}
		}
	} elseif (strtotime(str_replace('.', '-', $coupon['date_info']['time_limit_end'])) < strtotime(date('Y-m-d')) && $coupon['date_info']['time_type'] != 2) {
		return error(-1, '活动已结束');
	} elseif ($coupon['quantity'] <= 0) {
		return error(-1, '卡券发放完毕');
	} elseif ($pcount >= $coupon['get_limit'] && !empty($coupon['get_limit'])) {
		return error(-1, '数量超限');
	} elseif (!empty($coupon['modules']) && !in_array($_W['current_module']['name'], array_keys($coupon['modules'])) && ($_GPC['c'] != 'activity' && $_GPC['c'] != 'mc' ) && $_W['current_module']['name'] != 'we7_coupon') {
		return error(-1, '该模块没有此卡券发放权限');
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
		'remark' => $give ? '系统赠送' : '用户使用' . $coupon['exchange']['credit'] . $credit_names[$coupon['exchange']['credittype']] . '兑换'
	);
	if ($coupon['source'] == 2) {
		$insert['card_id'] = $coupon['card_id'];
		$insert['code'] = '';
	}
	pdo_insert('coupon_record', $insert);
	pdo_update('coupon', array('quantity' => $coupon['quantity'] - 1, 'dosage' => $coupon['dosage'] + 1), array('uniacid' => $_W['uniacid'],'id' => $coupon['id']));
	return true;
}

function we7_coupon_activity_get_member($type, $param = array()) {
	activity_coupon_type_init();
	global $_W;
	$types = array('new_member', 'old_member', 'quiet_member', 'activity_member', 'group_member', 'cash_time', 'openids');
	if (!in_array($type, $types)) {
		return error('1', '没有匹配的用户类型');
	}
	$members = array();
	//获取会员属性
	$propertys = activity_member_propertys();
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

function we7_coupon_activity_coupon_owned() {
	global $_W, $_GPC;
	$uid = $_W['member']['uid'];
	$param = array('uniacid' => $_W['uniacid'], 'openid' => $_W['openid'], 'status' => 1);
	$data = pdo_getall('coupon_record', $param);
	foreach ($data as $key => $record) {
		$coupon = activity_coupon_info($record['couponid']);
		if ($coupon['source'] != COUPON_TYPE) {
			unset($data[$key]);
			continue;
		}
		if ($coupon['status'] != '3') {
			pdo_delete('coupon_record', array('id' => $record['id']));
			unset($data[$key]);
			continue;
		}
		if (is_error($coupon)) {
			unset($data[$key]);
			continue;
		}
		$modules = array();
		if (!empty($coupon['modules'])) {
			foreach ($coupon['modules'] as $module) {
				$modules[] = $module['name'];
			}
		}
		if (!empty($modules) && !in_array($_W['current_module']['name'], $modules) && !empty($_W['current_module']['name']) && $_W['current_module']['name'] != 'we7_coupon') {
			unset($data[$key]);
			continue;
		}
		if (is_array($coupon['date_info']) && $coupon['date_info']['time_type'] == '2') {
			$starttime = $record['addtime'] + $coupon['date_info']['deadline'] * 86400;
			$endtime = $starttime + ($coupon['date_info']['limit']) * 86400;
			if ($endtime < time()) {
				unset($data[$key]);
				pdo_delete('coupon_record', array('id' => $record['id']));
				continue;
			} else {
				$coupon['extra_date_info'] = '有效期:' . date('Y.m.d', $starttime) . '-' . date('Y.m.d', $endtime);
			}
		}
		if (is_array($coupon['date_info']) && $coupon['date_info']['time_type'] == '1') {
			$endtime = str_replace('.', '-', $coupon['date_info']['time_limit_end']);
			$endtime = strtotime($endtime);
			if ($endtime < time()) {
				pdo_delete('coupon_record', array('id' => $record['id']));
				unset($data[$key]);
				continue;
			}

		}
		if ($coupon['type'] == COUPON_TYPE_DISCOUNT) {
			$coupon['icon'] = '<div class="price">' . $coupon['extra']['discount'] * 0.1 . '<span>折</span></div>';
		} elseif($coupon['type'] == COUPON_TYPE_CASH) {
			$coupon['icon'] = '<div class="price">' . $coupon['extra']['reduce_cost'] * 0.01 . '<span>元</span></div><div class="condition">满' . $coupon['extra']['least_cost'] * 0.01 . '元可用</div>';
		} elseif($coupon['type'] == COUPON_TYPE_GIFT) {
			$coupon['icon'] = '<img src="resource/images/wx_gift.png" alt="" />';
		} elseif($coupon['type'] == COUPON_TYPE_GROUPON) {
			$coupon['icon'] = '<img src="resource/images/groupon.png" alt="" />';
		} elseif($coupon['type'] == COUPON_TYPE_GENERAL) {
			$coupon['icon'] = '<img src="resource/images/general_coupon.png" alt="" />';
		}
		$data[$key] = $coupon;
		$data[$key]['recid'] = $record['id'];
		$data[$key]['code'] = $record['code'];
		if ($coupon['source'] == '2') {
			if (empty($data[$key]['code'])) {
				$data[$key]['extra_ajax'] = url('entry', array('m' => 'we7_coupon', 'do' => 'activity', 'type' => 'coupon', 'op' => 'addcard'));
			} else {
				$data[$key]['extra_ajax'] = url('entry', array('m' => 'we7_coupon', 'do' => 'activity', 'type' => 'coupon', 'op' => 'opencard'));
			}
		}
	}
	return $data;
}

function we7_coupon_activity_paycenter_coupon_available() {
	$coupon_owned = we7_coupon_activity_coupon_owned();
	foreach ($coupon_owned as $key => &$val) {
		if (empty($val['code'])) {
			unset($val);
		}
		if ($val['type'] == '1' || $val['type'] == '2') {
			$coupon_available[$val['id']] = $val;
		}
	}
	return $coupon_available;
}

function we7_coupon_activity_store_sync() {
	global $_W;
	load()->classs('coupon');
	$cachekey = "storesync:{$_W['uniacid']}";
	$cache = cache_load($cachekey);
	if (!empty($cache) && $cache['expire'] > time()) {
		return false;
	}
	$stores = pdo_getall('activity_stores', array('uniacid' => $_W['uniacid'], 'source' => 2));
	foreach ($stores as $val) {
		if ($val['status'] == 3) {
			continue;
		}
		$coupon_api = new coupon($_W['acid']);
		$location = $coupon_api->LocationGet($val['location_id']);
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
		pdo_update('activity_stores', $location, array('uniacid' => $_W['uniacid'], 'id' => $val['id']));
	}
	cache_write($cachekey, array('expire' => time() + 1800));
	return true;
}

function we7_coupon_paycenter_order_status() {
	/*		SUCCESS—支付成功
		REFUND—转入退款
		NOTPAY—未支付
		CLOSED—已关闭
		REVOKED—已撤销（刷卡支付）
		USERPAYING--用户支付中
		PAYERROR--支付失败(其他原因，如银行返回失败)*/
	return array(
		'0' => array(
			'text' => '未支付',
			'class' => 'text-danger',
		),
		'1' => array(
			'text' => '已支付',
			'class' => 'text-success',
		),
		'2' => array(
			'text' => '已支付,退款中...',
			'class' => 'text-default',
		),
	);
}

function we7_coupon_paycenter_order_types() {
	return array(
		'wechat' => '微信支付',
		'alipay' => '支付宝支付',
		'credit' => '余额支付',
		'baifubao' => '百付宝'
	);
}

function we7_coupon_paycenter_order_trade_types() {
	return array(
		'native' => '扫码支付',
		'jsapi' => '公众号支付',
		'micropay' => '刷卡支付'
	);
}

function we7_coupon_paycenter_check_login() {
	global $_W, $_GPC;
	if (empty($_W['uid']) && $_GPC['do'] != 'login') {
		message('抱歉，您无权进行该操作，请先登录', murl('entry', array('m' => 'we7_coupon', 'do' => 'clerk', 'op' => 'login'), true, true), 'error');
	}
	if ($_W['user']['type'] == ACCOUNT_OPERATE_CLERK) {
		isetcookie('__uniacid', $_W['user']['uniacid'], 7 * 86400);
		isetcookie('__uid', $_W['uid'], 7 * 86400);
	} else {
		message('非法访问', '', 'error');
	}
}
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

/**
 * 检测会员信息是否存在(邮箱和手机号)
 * @param array $data 会员信息
 * @return mixed
 */
function we7_coupon_mc_check($data) {
	global $_W;
	if (!empty($data['email'])) {
		$email = trim($data['email']);
		if (!preg_match(REGULAR_EMAIL, $email)) {
			return error(-1, '邮箱格式不正确');
		}
		$isexist = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_members') . ' WHERE uniacid = :uniacid AND email = :email AND uid != :uid', array(':uniacid' => $_W['uniacid'], ':email' => $email, ':uid' => $_W['member']['uid']));
		if ($isexist >= 1) {
			return error(-1, '邮箱已被注册');
		}
	}
	if (!empty($data['mobile'])) {
		$mobile = trim($data['mobile']);
		if (!preg_match(REGULAR_MOBILE, $mobile)) {
			return error(-1, '手机号格式不正确');
		}
		$isexist = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_members') . ' WHERE uniacid = :uniacid AND mobile = :mobile AND uid != :uid', array(':uniacid' => $_W['uniacid'], ':mobile' => $mobile, ':uid' => $_W['member']['uid']));
		if ($isexist >= 1) {
			return error(-1, '手机号已被注册');
		}
	}
	return true;
}
