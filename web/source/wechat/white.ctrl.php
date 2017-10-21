<?php
/**
 * [WECHAT 2017]
 
 */
defined('IN_IA') or exit('Access Denied');
uni_user_permission_check('wechat_white_list');
$dos = array('list');
$do = in_array($do, $dos) ? $do : 'list';
$acid = $_W['acid'];
if($do == 'list') {
	$whitelist = pdo_fetchcolumn('SELECT whitelist FROM ' . tablename('coupon_setting') . ' WHERE uniacid = :aid AND acid = :acid', array(':aid' => $_W['uniacid'], ':acid' => $acid));
	if(!empty($whitelist)) {
		$whitelist = @iunserializer($whitelist);
	}
	if(checksubmit('submit')) {
		if(!empty($_GPC['username'])) {
			$data = array();
			foreach($_GPC['username'] as $da) {
				$da = trim($da);
				if(empty($da)) {
					continue;
				}
				$i++;
				$data[] = trim($da);
				if($i >= 10) {
					break;
				}
			}
		}

		load()->classs('coupon');
		$acc = new coupon($acid);
		$post['username'] = $data;
		$status = $acc->SetTestWhiteList($post);
		if(is_error($status)) {
			message($status['message'], '', 'error');
		} else {
			$data = iserializer($data);
			pdo_update('coupon_setting', array('whitelist' => $data), array('uniacid' => $_W['uniacid'], 'acid' => $acid));
		}
		message('设置测试白名单成功', referer(), 'success');
	}
}
template('wechat/white');
