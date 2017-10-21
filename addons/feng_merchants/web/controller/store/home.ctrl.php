<?php 
/**
 * [weliam] Copyright (c) 2016/3/26
 * 商城首页自定义控制器
 */
defined('IN_IA') or exit('Access Denied');
wl_load()->model('page');
$op = in_array($op, array('design', 'display', 'quickmenu', 'uc', 'delete', 'set_home')) ? $op : 'display';
if ($op == 'design') {
	$_W['page']['title'] = '商城管理 - 商城首页';
	$id = intval($_GPC['id']);
	if (!empty($_GPC['wapeditor'])) {
		$params = $_GPC['wapeditor']['params'];
		if (empty($params)) {
			message('请您先设计手机端页面.', '', 'error');
		}
		$params = json_decode(html_entity_decode(urldecode($params)), true);
		
		if (empty($params)) {
			message('请您先设计手机端页面.', '', 'error');
		}
		$page = $params[0];
		$html = htmlspecialchars_decode($_GPC['wapeditor']['html'], ENT_QUOTES);
//		echo "<pre>";print_r($params);
//		echo "<pre>";print_r(".............................................");
//		echo "<pre>";print_r($_GPC['wapeditor']['html']);exit;
		$page2 = pdo_fetch_one('tg_page', array('type' => 1));
		if(empty($page2)){
			$type = 1;
		}else{
			$type = 0;
		}
		$data = array(
			'title' => $page['params']['title'],
			'description' => $page['params']['description'],
			'type' => $type,
			'status' => 1,
			'params' => json_encode($params),
			'html' => $html,
			'createtime' => TIMESTAMP,
		);
		if (empty($id)) {
			pdo_insert('tg_page', $data);
			$id = pdo_insertid();
		} else {
			unset($data['type']);
			pdo_update('tg_page', $data, array('id' => $id));
		}
		message('页面保存成功.', web_url('store/home/design', array('id' => $id)), 'success');
	} else {
		$page = pdo_fetch_one('tg_page', array('id' => $id));
		include wl_template('store/home');
	}
} elseif ($op == 'quickmenu') {} elseif ($op == 'uc') {} elseif ($op == 'display') {
	$_W['page']['title'] = '商城管理 - 商城首页';
	
	$where = " WHERE type in(0,1)";
	$params = array();
	$size = 20;
	$page = $_GPC['page'];
	$sqlTotal = pdo_sql_select_count_from('tg_page') . $where;
	$sqlData = pdo_sql_select_all_from('tg_page') . $where . ' ORDER BY id DESC ';
	$list = pdo_pagination($sqlTotal, $sqlData, $params, '', $total, $page, $size);
	
	if (!empty($list)) {
		foreach ($list as &$row) {
			$row['params'] = json_decode($row['params'], true);
		}
		unset($row);
	}
	$site_home = pdo_fetch_one('tg_page', array('type' => 1));
	$pager = pagination($total, $page, $size);
	include wl_template('store/home');
} elseif ($op == 'delete') {
	$id = intval($_GPC['id']);
	$page = pdo_fetch_one('tg_page', array('id' => $id));
	if ($page['type'] == 1) {
		message(error(1, '首页不可删除'));
	}
	pdo_delete('tg_page', array('id' => $id));
	message(error(0, '删除成功'));
	
} elseif ($op == 'set_home') {
	$id = intval($_GPC['id']);
	pdo_update('page', array('type' => 1), array('id' => $id));
	$site_home = pdo_fetch_one('tg_page', array('type' => 1));
	$site_home['createtime'] = date('Y-m-d H:i', $site_home['createtime']);
	message($site_home);
}


