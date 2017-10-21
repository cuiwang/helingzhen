<?php
/**
 * [WECHAT 2017]
 
 */
defined('IN_IA') or exit('Access Denied');
$dos = array('list', 'add', 'edit', 'batch_post', 'delete', 'record');
$do = in_array($do, $dos) ? $do : 'list';
$user = agent_single($id);
$founders = explode(',', $_W['config']['setting']['founder']);
if ($do == 'add') {
	if(checksubmit()) {
	load()->model('user');
	$user = array();
	$user['name'] = trim($_GPC['name']);
	if(!preg_match(REGULAR_USERNAME, $user['name'])) {
		message('必须输入用户名，格式为 3-15 位字符，可以包括汉字、字母（不区分大小写）、数字、下划线和句点。');
	}
	if(user_check(array('name' => $user['name']))) {
		message('非常抱歉，此用户名已经被注册，你需要更换注册名称！');
	}
	$user['password'] = $_GPC['password'];
	if(istrlen($user['password']) < 8) {
		message('必须输入密码，且密码长度不得低于8位。');
	}
	$user['intro'] = $_GPC['intro'];
	$user['endtime']= strtotime($_GPC['endtime']);
	$user['createtime'] = TIMESTAMP;
	$user['siteurl']= $_GPC['siteurl'];
	$user['mp']= $_GPC['mp'];
	$user['moneybalance']= $_GPC['moneybalance'];
	$timeadd = 0;
	$uid = agent_add($user);
	if($uid > 0) {
		unset($user['password']);
		message('用户增加成功！', url('agent/agent'));
	}
	message('增加用户失败，请稍候重试或联系网站管理员解决！');
	}
	template('agent/agent');
}
if ($do == 'edit') {
	$id = intval($_GPC['id']);
	$user = agent_single($id);
	if(checksubmit('submit')) {
		$_GPC['password'] = trim($_GPC['password']);
		if(!empty($record['password']) && istrlen($record['password']) < 8) {
			message('必须输入密码，且密码长度不得低于8位。');
		}
		load()->model('user');
		$record = array();
				if(!empty($_GPC['endtime'])) {
			$record['endtime'] = strtotime($_GPC['endtime']);
		}
		$record['id'] = $id;
		$record['password'] = $_GPC['password'];
		$record['salt'] = $user['salt'];
		$record['intro'] = $_GPC['intro'];
		$record['endtime']= strtotime($_GPC['endtime']);
		$record['siteurl']= $_GPC['siteurl'];
		$record['mp']= $_GPC['mp'];
		$record['moneybalance']= $_GPC['moneybalance'];
		agent_update($record);
		message('保存用户资料成功！',  url('agent/agent'));
	}
	template('agent/agent');
	exit();
}

	
if($do == 'list') {
	$_W['page']['title'] = '代理商列表';
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$params = array();
	$sql = 'SELECT * FROM ' . tablename('agent') .$where . " LIMIT " . ($pindex - 1) * $psize .',' .$psize;
	$users = pdo_fetchall($sql, $params);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('agent') . $where, $params);
	$pager = pagination($total, $pindex, $psize);

	$founders = explode(',', $_W['config']['setting']['founder']);

	foreach($users as &$user) {
		$user['founder'] = in_array($user['id'], $founders);
	}
	unset($user);
	template('agent/agent');
}
if($do == 'batch_post') {
	if(checksubmit()) {
		if(!empty($_GPC['ids'])) {
			foreach($_GPC['ids'] as $k => $v) {
				$data = array(
					'siteurl' => trim($_GPC['siteurl'][$k]),
				);
				pdo_update('agent', $data, array('id' => intval($v)));
			}
			message('编辑代理商列表成功', referer(), 'success');
		}
	}
}
if($do == 'record') {
	$_W['page']['title'] = '代理商记录';
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$params = array();
	$sql = 'SELECT * FROM ' . tablename('agent_expenserecords') .$where . " LIMIT " . ($pindex - 1) * $psize .',' .$psize;
	$users = pdo_fetchall($sql, $params);

	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('agent_expenserecords') . $where, $params);
	$pager = pagination($total, $pindex, $psize);

	$founders = explode(',', $_W['config']['setting']['founder']);
	foreach($users as &$user) {
		$user['founder'] = in_array($user['id'], $founders);
	}
	unset($user);

	function m_kind($mana){
		switch($mana){
			case 0:
			return '<font color="red">未成功</font>';
			break;
			case 1:
			return '<font color="green">已成功</font>';
			break;
		}
	}//end function
	function c_pro(){
		$id=$_REQUEST['id'];
		load()->model('user');
			$record = array();
			$record['id'] = $id;
			$record['status']='1';
			record($record);
			message('审核通过！', '');
		}

	switch(trim($_REQUEST['act'])){	
			case "pro";
			c_pro();
			break;
		}
	template('agent/agent');
}
if($do == 'delete') {
	$id = intval($_GPC['id']);
	pdo_delete('agent', array('id' => $id));
	message('删除代理商成功', referer(), 'success');
}


