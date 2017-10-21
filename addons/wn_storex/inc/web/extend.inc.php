<?php

defined('IN_IA') or exit('Access Denied');

global $_W, $_GPC;
$storex_set = pdo_get('storex_set', array('weid' => $_W['uniacid']));
if (empty($storex_set)) {
	$set_insert = array(
		'weid' => intval($_W['uniacid']),
		'version' => 1,
			
	);
	pdo_insert('storex_set', $set_insert);
}
include $this->template('extend');