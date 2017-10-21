<?php

defined('IN_IA') or exit('Access Denied');

global $_W, $_GPC;

$ops = array('personal_info', 'personal_update', 'credits_record', 'address_lists', 'current_address', 'address_post', 'address_default', 'address_delete', 'extend_switch');
$op = in_array(trim($_GPC['op']), $ops) ? trim($_GPC['op']) : 'error';

check_params();
load()->model('mc');
mload()->model('card');
$uid = mc_openid2uid($_W['openid']);

if (in_array($op, array('address_post', 'address_default', 'address_delete')) && !empty($_GPC['id'])) {
	$address_info = pdo_get('mc_member_address', array('uniacid' => $_W['uniacid'], 'uid' => $uid, 'id' => intval($_GPC['id'])));
	if (empty($address_info)) {
		message(error(-1, '设置失败'), '', 'ajax');
	}
}

if ($op == 'extend_switch') {
	$extend_switch = extend_switch_fetch();
	$notices = card_notices();
	$notice_unread_num = 0;
	if (!empty($notices)) {
		foreach ($notices as $val) {
			if (empty($val['read_status'])) {
				$notice_unread_num++;
			}
		}
	}
	$plugin_list = get_plugin_list();
	$extend_switch['plugin_list'] = $plugin_list;
	$extend_switch['notice_unread_num'] = $notice_unread_num;
	message(error(0, $extend_switch), '', 'ajax');
}

if ($op == 'personal_info') {
	$user_info = mc_fetch($_W['openid']);
	$storex_clerk = pdo_get('storex_clerk', array('weid' => intval($_W['uniacid']), 'from_user' => trim($_W['openid']), 'status !=' => -1), array('id', 'from_user'));
	if (!empty($storex_clerk)) {
		$user_info['clerk'] = 1;
	} else {
		$user_info['clerk'] = 0;
	}
	$card_info = card_setting_info();
	$user_info['mycard'] = pdo_get('storex_mc_card_members', array('uniacid' => intval($_W['uniacid']), 'uid' => $uid));
	if (!empty($user_info['mycard'])) {
		$user_info['mycard']['is_receive'] = 1;//是否领取,1已经领取，2没有领取
		$user_info['mycard']['fields'] = iunserializer($user_info['mycard']['fields']);
		$user_info['mycard']['group'] = array();
		$user_info['mycard']['group'] = card_group_id($uid);
	} else {
		$user_info['mycard']['is_receive'] = 2;
	}
	if (!empty($card_info)) {
		$show_fields = array('title', 'color', 'background', 'logo', 'description');
		foreach ($show_fields as $val) {
			if (!empty($card_info[$val])) {
				$user_info['mycard'][$val] = $card_info[$val];
			}
			if ($val == 'background') {
				if ($card_info[$val]['background'] == 'user') {
					$user_info['mycard'][$val]['image'] = $user_info['mycard'][$val]['image'];
				} else {
					$png = $user_info['mycard'][$val]['image'];
					$png = !empty($png) ? $png : '1';
					$user_info['mycard'][$val]['image'] = tomedia("addons/wn_storex/template/style/img/card/" . $png . ".png");
				}
			}
		}
		if (!empty($card_info['params']['cardBasic']['params'])) {
			$user_info['mycard']['card_level'] = $card_info['params']['cardBasic']['params']['card_level'];
			$user_info['mycard']['card_label'] = $card_info['params']['cardBasic']['params']['card_label'];
		}
		
	}
	message(error(0, $user_info), '', 'ajax');
}
if ($op == 'personal_update') {
	if (!empty($_GPC['fields'])) {
		foreach ($_GPC['fields'] as $key=>$value) {
			if (empty($value) || empty($key)) {
				message(error(-1, '信息不完整'), '', 'ajax');
			}
		}
	}
	$result = mc_update($_W['openid'], $_GPC['fields']);
	if (!empty($result)) {
		message(error(0, '修改成功'), '', 'ajax');
	} else {
		message(error(-1, '修改失败'), '', 'ajax');
	}
}
if ($op == 'credits_record') {
	$credits = array();
	$credits_record = pdo_getall('mc_credits_record', array('uniacid' => $_W['uniacid'], 'credittype' => $_GPC['credittype'], 'uid' => $uid, 'module' => 'wn_storex'), array('num', 'createtime', 'module'), '', 'id DESC');
	if (!empty($credits_record)) {
		foreach ($credits_record as $data) {
			$data['createtime'] = date('Y-m-d H:i:s', $data['createtime']);
			$offset = $_GPC['credittype'] == 'credit2' ? '元' : '积分';
			if ($data['num'] > 0) {
				$data['remark'] = '充值' . $data['num'] . $offset;
				$credits['recharge'][] = $data;
			} else {
				$data['remark'] = '消费' . - $data['num'] . $offset;
				$credits['consume'][] = $data;
			}
		}
	}
	message(error(0, $credits), '', 'ajax');
}
if ($op == 'address_lists') {
	$address_info = pdo_getall('mc_member_address', array('uid' => $uid, 'uniacid' => $_W['uniacid']), '', '', 'isdefault DESC');
	message(error(0, $address_info), '', 'ajax');
}
if ($op == 'current_address') {
	$current_info = pdo_get('mc_member_address', array('id' => intval($_GPC['id']), 'uid' => $uid, 'uniacid' => $_W['uniacid']));
	message(error(0, $current_info), '', 'ajax');
}
if ($op == 'address_post') {
	$address_info = $_GPC['fields'];
	if (empty($address_info['username']) || empty($address_info['zipcode']) || empty($address_info['province']) || empty($address_info['city'])  || empty($address_info['district']) || empty($address_info['address'])) {
		message(error(-1, '请填写正确的信息'), '', 'ajax');
	}
	if (empty($address_info['mobile'])) {
		message(error(-1, '手机号码不能为空'), '', 'ajax');
	}
	if (!preg_match(REGULAR_MOBILE, $address_info['mobile'])) {
		message(error(-1, '手机号码格式不正确'), '', 'ajax');
	}
	unset($address_info['id']);
	if (!empty($_GPC['id'])) {
		$result = pdo_update('mc_member_address', $address_info, array('id' => intval($_GPC['id'])));
		message(error(0, $result), '', 'ajax');
	} else {
		$address_info['uid'] = $uid;
		$address_info['uniacid'] = $_W['uniacid'];
		$address = pdo_get('mc_member_address', array('uniacid' => $_W['uniacid'], 'uid' => $uid));
		if (empty($address)) {
			$address_info['isdefault'] = 1;
		}
		$result = pdo_insert('mc_member_address', $address_info);
		message(error(0, $result), '', 'ajax');
	}
}
if ($op == 'address_default') {
	$default_result = pdo_update('mc_member_address', array('isdefault' => '0'), array('uid' => $uid, 'uniacid' => $_W['uniacid']));
	$result = pdo_update('mc_member_address', array('isdefault' => '1'), array('id' => intval($_GPC['id'])));
	message(error(0, '设置成功'), '', 'ajax');

}
if ($op == 'address_delete') {
	$result = pdo_delete('mc_member_address', array('id' => intval($_GPC['id'])));
	message(error(0, '删除成功'), '', 'ajax');
}
