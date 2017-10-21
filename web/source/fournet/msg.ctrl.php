<?php
/**
 * [WECHAT 2017]
 
 */
defined('IN_IA') or exit('Access Denied');
uni_user_permission_check('fournet_msg');
$row = pdo_fetchcolumn("SELECT `msg` FROM ".tablename('uni_settings') . " WHERE uniacid = :uniacid", array(':uniacid' => $_W['uniacid']));
$msg = iunserializer($row) ? iunserializer($row) : array();
if(checksubmit('submit')) {
	$msg = array(
			'appkey' => $_GPC['appkey'],
			'pingtai' => $_GPC['pingtai'],
			'type'=>intval($_GPC['type']),
			'password' => $_GPC['password'],
			'secret' => $_GPC['secret'],
			'qianming' => $_GPC['qianming'],
			'sms_id' => $_GPC['sms_id'],
		);
		$row = array();
		$row['msg'] = iserializer($msg);
		if ($_GPC['mobile']){
			sendsms($_GPC['mobile'], array('code'=>'系统短信测试'), $_GPC['sms_id']);
		}
		pdo_update('uni_settings', $row, array('uniacid' => $_W['uniacid']));
		message('更新设置成功！', url('fournet/msg'));
	}
template('fournet/msg');
