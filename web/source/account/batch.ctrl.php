<?php
/**
 * [WECHAT 2017]
 
 */
$_W['page']['title'] = '公众号列表 - 公众号';
$tables = array(1 => 'account_wechats', 2 => 'account_yixin');
$do = in_array($do, array('display', 'delete', 'group', 'modal', 'operator')) ? $do : display;
if($do == 'display') {
	
	$pindex = max(1, intval($_GPC['page']));
	$psize = 15;
	$start = ($pindex - 1) * $psize;
	
	$condition = '';
	$pars = array();
	$keyword = trim($_GPC['keyword']);
	if(!empty($keyword)) {
		$condition =" AND `name` LIKE :name";
		$pars[':name'] = "%{$keyword}%";
	}
	
	$uid = $_W['uid'];
	if(empty($_W['isfounder'])) {
		$condition = " AND `uniacid` IN (SELECT `uniacid` FROM " . tablename('uni_account_users') . " WHERE `uid`=:uid AND role = :role)";
		$pars[':uid'] = $uid;
		$pars[':role'] = 'manager';
	}
	$tsql = "SELECT COUNT(*) FROM " . tablename('uni_account') . " WHERE 1 = 1{$condition}";
	$total = pdo_fetchcolumn($tsql, $pars);
	$sql = "SELECT * FROM " . tablename('uni_account') . " WHERE 1 = 1{$condition} ORDER BY `uniacid` DESC LIMIT {$start}, {$psize}";
	$pager = pagination($total, $pindex, $psize);
	$list = pdo_fetchall($sql, $pars);
	
	$groups = pdo_fetchall("SELECT * FROM ".tablename('uni_group'), array(), 'id');
	$groups[0] = array('id' => 0, 'name' => '基础服务');
	$groups[-1] = array('id' => -1, 'name' => '所有服务');
	
	if(!empty($list)) {
		foreach($list as &$account) {
			$account['group'] = $groups[$account['groupid']];
			$settings = uni_setting($account['uniacid'], array('groupdata'));
			$account['groupdata'] = $settings['groupdata'] ? $settings['groupdata'] : array();
			$account['userdata'] = pdo_fetchall('SELECT u.username, a.role FROM ' . tablename('uni_account_users') . ' AS a LEFT JOIN ' . tablename('users') . ' AS u ON a.uid = u.uid WHERE a.uniacid = :uniacid', array(':uniacid' => $account['uniacid']));
		}
	}

} elseif($do == 'delete') {
	if(!$_GPC['accoundid']) {
		message('您没有选择要操作的公众号');
	}
	
	if(!$_W['isfounder']) {
		$account = pdo_fetchall('SELECT uniacid FROM ' . tablename('uni_account_user') . ' WHERE uid = :uid AND role = :role', array(':uid' => $_W['uid'], ':role' => 'manager'), 'uniacid');
		$account = array_keys($account);
		foreach($_GPC['accoundid'] as $index => $uniacid) {
			if(!in_array($uniacid, $account)) {
				unset($_GPC['accoundid'][$index]);
			}
		}
	}
	
	$modules = array();
	foreach($_GPC['accoundid'] as $uniacid) {
		$uniacid = intval($uniacid);
				$rules = pdo_fetchall("SELECT id, module FROM ".tablename('rule')." WHERE uniacid = '{$uniacid}'");
		if (!empty($rules)) {
			foreach ($rules as $index => $rule) {
				$deleteid[] = $rule['id'];
				if (empty($modules[$rule['module']])) {
					$file = IA_ROOT . '/source/modules/'.$rule['module'].'/module.php';
					if (file_exists($file)) {
						include_once $file;
					}
					$modules[$rule['module']] = WeUtility::createModule($rule['module']);
				}
				if (method_exists($modules[$rule['module']], 'ruleDeleted')) {
					$modules[$rule['module']]->ruleDeleted($rule['id']);
				}
			}
			pdo_delete('rule', "id IN ('".implode("','", $deleteid)."')");
		}
		
				$subaccount = pdo_fetchall("SELECT acid FROM ".tablename('account')." WHERE uniacid = :uniacid", array(':uniacid' => $uniacid));
		if (!empty($subaccount)) {
			foreach ($subaccount as $account) {
				@unlink(IA_ROOT . '/attachment/qrcode_'.$account['acid'].'.jpg');
				@unlink(IA_ROOT . '/attachment/headimg_'.$account['acid'].'.jpg');
			}
		}
		
				$tables = pdo_fetchall("SHOW TABLES;");
		foreach ($tables as $table) {
			$tablename = str_replace($GLOBALS['_W']['config']['db']['tablepre'], '', array_shift($table));
			if (pdo_fieldexists($tablename, 'uniacid')) {
				pdo_delete($tablename, array('uniacid' => $uniacid));
			}
			
			if (pdo_fieldexists($tablename, 'weid')) {
				pdo_delete($tablename, array('weid' => $uniacid));
			}
		}		
	}
	message('公众帐号信息删除成功！', url('account/batch'), 'success');
	
} elseif($do == 'group') {
	if(!$_GPC['accountarr']) {
		message('您没有选择要操作的公众号');
	}
	$isexpire = intval($_GPC['isexpire']);
	$endtime = strtotime($_GPC['endtime']);
	if($isexpire && $endtime <= TIMESTAMP) {
		message('套餐过期时间必须大于当前时间');
	}
	$groupid = intval($_GPC['groupid']);
	$_GPC['accountarr'] = explode(',', $_GPC['accountarr']);
	if(!$_W['isfounder']) {
		$account = pdo_fetchall('SELECT uniacid FROM ' . tablename('uni_account_user') . ' WHERE uid = :uid AND role = :role', array(':uid' => $_W['uid'], ':role' => 'manager'), 'uniacid');
		$account = array_keys($account);
		foreach($_GPC['accountarr'] as $index => $uniacid) {
			if(!in_array($uniacid, $account)) {
				unset($_GPC['accountarr'][$index]);
			}
		}
	}
	foreach($_GPC['accountarr'] as $uniacid) {
		$uniacid = intval($uniacid);
		$oldgroupid = pdo_fetchcolumn('SELECT groupid FROM ' . tablename('uni_account') . ' WHERE uniacid = :uniacid', array(':uniacid' => $uniacid));
		pdo_update('uni_account', array('groupid' => $groupid), array('uniacid' => $uniacid));
		$updatedata = $isexpire ? iserializer(array('isexpire' => 1, 'endtime' => $endtime, 'oldgroupid' => $oldgroupid)) : iserializer(array('isexpire' => 0, 'endtime' => TIMESTAMP, 'oldgroupid' => $oldgroupid));
		pdo_update('uni_settings', array('groupdata' => $updatedata), array('uniacid' => $uniacid));
	}
	load()->model('module');
	module_build_privileges();
	message('更改公众号套餐成功', url('account/batch'), 'success');
	
} elseif($do == 'modal') {	
	if($_W['isajax']) {
		load()->func('tpl');
		if($_W['isfounder']) {
			$groups = pdo_fetchall("SELECT * FROM ".tablename('uni_group'), array(), 'id');
		} else {
			$groups = pdo_fetch("SELECT package FROM ".tablename('users_group') . ' WHERE id = :id', array(':id' => $_W['user']['groupid']), 'id');
			$groups = uni_groups((array)iunserializer($groups['package']));
		}
				
		$arr = $_GPC['arr'];
		template('account/modal');
		exit();
	}
} elseif($do == 'operator') {
	if(!$_W['isfounder']) exit('您没有操作权限,请联系系统管理员');
	if($_W['ispost']) {
		if(empty($_GPC['uid'])) exit('没有选择要添加的操作员');
		$uniacidarr = explode(',', trim($_GPC['uniacidstr']));
		if(empty($uniacidarr)) exit('没有选择要操作的公众号');
		foreach($uniacidarr as $uniacid) {
			foreach($_GPC['uid'] as $uid) {
				if(intval($uid) > 0) {
					$exists = pdo_fetch("SELECT * FROM ".tablename('uni_account_users')." WHERE uid = :uid AND uniacid = :uniacid", array(':uniacid' => $uniacid, ':uid' => $uid));
					if(empty($exists)) {
						pdo_insert('uni_account_users', array('uniacid' => $uniacid, 'uid' => $uid, 'role' => 'manager'));
					}
				}
			}
		}
		exit('success');
	}
	exit('非法访问');
}
 
template('account/batch');
