<?php
/**
 * [WeEngine System] Copyright (c) 2015 012WZ.COM
 
 */
defined('IN_IA') or exit('Access Denied');

$do = $_GPC['do'];
$dos = array('edit');
$do = in_array($do, $dos) ? $do: 'edit';

if($do == 'edit') {
	$_W['page']['title'] = '管理人员列表 - 管理人员';
	$works = pdo_fetchall("SELECT id, uid, role FROM ".tablename('uni_account_users')." WHERE uniacid = '{$_W['uniacid']}'", array(), 'uid');
	$uids = array();
	if (!empty($works)) {
		$member = pdo_fetchall("SELECT username, uid FROM ".tablename('users')." WHERE uid IN (".implode(',', array_keys($works)).")", array(), 'uid');
		foreach ($member as $v) {
			$uids[] = $v['uid'];
		}
	}
	
		$m = trim($_GPC['m']);
	if(!empty($m)) {
		$check = module_solution_check($m);
		if(is_error($check)) {
			message($check['message'], '', 'error');
		}
		$issolution = 1;
		
		$module_types = module_types();
		$module = module_fetch($m);
		define('ACTIVE_FRAME_URL', url('home/welcome/ext', array('m' => $m)));
	}
	template('profile/work');
}
