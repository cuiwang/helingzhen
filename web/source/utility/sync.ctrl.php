<?php 
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
defined('IN_IA') or exit('Access Denied');

load()->model('account');
load()->model('mc');

if(!empty($_W['uniacid'])) {
	$setting = uni_setting($_W['uniacid'], array('sync'));
	$sync_setting = $setting['sync'];
	if($sync_setting == 1 && $_W['account']['type'] == 1 && $_W['account']['level'] >= ACCOUNT_TYPE_OFFCIAL_AUTH) {
		$fans = pdo_getall('mc_mapping_fans', array('uniacid' => $_W['uniacid'], 'follow' => 1), array('fanid', 'openid', 'acid', 'uid', 'uniacid'), 'fanid', 'fanid DESC', '10');
		if(!empty($fans)) {
			foreach($fans as $row) {
				mc_init_fans_info($row['openid']);
			}
		}
	}
}