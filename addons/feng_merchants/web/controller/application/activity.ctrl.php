<?php 
/**
 * [weliam] Copyright (c) 2016/4/4
 * 优惠券
 */

defined('IN_IA') or exit('Access Denied');
$ops = array('list', 'create', 'edit', 'disable');
$op_names = array('优惠券列表', '添加优惠券', '编辑优惠券', '使优惠券失效');
foreach($ops as$key=>$value){
	permissions('do', 'ac', 'op', 'application', 'activity', $ops[$key], '应用与营销', '优惠券', $op_names[$key]);
}
$do = in_array($op, $ops) ? $op : 'list';
wl_load()->model('activity');
if ($do == 'list') {
	$_W['page']['title'] = '应用和营销  - 优惠券列表';
	$opp=$_GPC['opp'];
	if (empty($_GPC['opp'])) {
		$opp = 'all';
	}
	$where = " WHERE uniacid = {$_W['uniacid']}";
	$params = array();
	$keyword = trim($_GPC['keyword']);
	if (!empty($keyword)) {
		$where .= " AND name LIKE :name";
		$params[':name'] = "%{$keyword}%";
	}
	if ($opp == 'future') {
		$where .= " AND start_time > :time AND enable = :enable";
		$params[':time'] = TIMESTAMP;
		$params[':enable'] = 1;
	} elseif ($opp == 'on') {
		$where .= " AND start_time < :time AND end_time > :time AND enable = :enable";
		$params[':time'] = TIMESTAMP;
		$params[':enable'] = 1;
	} elseif ($opp == 'end') {
		$where .= " AND enable = :enable";
		$params[':enable'] = 0;
	}
	$size = 10;
	$page = !empty($_GPC['page'])?$_GPC['page']:1;
	$sql = "select * from".tablename('tg_coupon_template')." $where LIMIT " . ($page - 1) * $size . " , " . $size;
	$tg_coupon_templates = pdo_fetchall($sql,$params);
	
	$total = pdo_fetchall("select id from".tablename('tg_coupon_template')." WHERE uniacid = {$_W['uniacid']}");
	$total = count($total);
	
	$sql = "SELECT `coupon_template_id`, COUNT(DISTINCT `uid`) as 'count_receive_person', COUNT('id') as 'count_receive_num' FROM " .tablename('tg_coupon'). " GROUP BY `coupon_template_id`";
	$coupon_count = pdo_fetchall($sql, array(), 'coupon_template_id');
	foreach ($tg_coupon_templates as &$tg_coupon_template) {
		if ($tg_coupon_template['end_time'] < TIMESTAMP) {
			pdo_update('tg_coupon_template', array('enable' => 0), array('id' => $tg_coupon_template['id']));
		}
		$tg_coupon_template['stock'] = $tg_coupon_template['total'] - $tg_coupon_template['quantity_issue'];
		$tg_coupon_template['count_receive_num'] = $coupon_count[$tg_coupon_template['id']]['count_receive_num'];
		$tg_coupon_template['count_receive_person'] = $coupon_count[$tg_coupon_template['id']]['count_receive_person'];
	}
	$pager = pagination($total, $page, $size);
	include wl_template('application/activity/coupon_template_list');
}
if ($do == 'create' || $do == 'edit') {
	$insert = $do == 'create';
	$_W['page']['title'] = '应用和营销 - 优惠券';
	$id = intval($_GPC['id']);
	if ($id) {
		$coupon_template = coupon_template($id);
		if (empty($coupon_template)) {
			message('非法访问：访问记录不存在', url('application/activity/list'), 'warning');
		}
	}
	if (checksubmit('submit')) {
		$tg_coupon_template_data = array(
			'name' => trim($_GPC['name']),
			'total' => intval(trim($_GPC['total'])),
			'value' => currency_format($_GPC['value']),
			'value_to' => currency_format($_GPC['value_to']),
			'is_random' => $_GPC['is_random'] ? $_GPC['is_random'] : 0,
			'is_at_least' => $_GPC['is_at_least'],
			'at_least' => currency_format($_GPC['at_least']),
			'user_level' => $_GPC['user_level'],
			'quota' => intval($_GPC['quota']),
			'start_time' => strtotime($_GPC['start_time'] . ' 00:00:00'),
			'end_time' => strtotime($_GPC['end_time'] . ' 23:59:59'),
			'range_type' => $_GPC['range_type'],
			'description' => trim($_GPC['description']),
			'createtime' => TIMESTAMP,
			'enable' => 1,
			'uid' => $_W['uid'],
			'uniacid' => $_W['uniacid']
		);
//		wl_debug($tg_coupon_template_data);
		if (empty($id)) {
			pdo_insert('tg_coupon_template', $tg_coupon_template_data);
			$id = pdo_insertid();
		} else {
			if ($coupon_template['enable'] == 0) {
				message('已失效不可编辑', referer(), 'warning');
			}
			pdo_update('tg_coupon_template', array('name' => trim($_GPC['name']), 'total' => intval(trim($_GPC['total'])), 'description' => trim($_GPC['description'])), array('id' => $id));
		}
		message($insert ? '添加成功' : '编辑成功', web_url('application/activity/coupon_template/edit', array('id' => $id)), 'success');
	}
	
	if (!$insert) {
		$coupon_template_id = intval($_GPC['id']);
		$coupon = coupon_template($tg_coupon_template_id);
	}
	
	include wl_template('application/activity/coupon_template_edit');
}

if ($do == 'disable') {
	$coupon_template_id = $_GPC['id'];
	$coupon_template = coupon_template($coupon_template_id);
	if (empty($coupon_template)) {
		message(error(1,'优惠券不存在或已删除'));
	}
	pdo_update('tg_coupon_template', array('enable' => 0), array('id' => $coupon_template_id));
	message(error(0, '处理失效成功!'));
}
if ($do == 'get_all') {
//	ajax_only();
//	$sql = "SELECT `id`, `name` FROM " . tablename('tg_coupon_template') . " WHERE `enable` = :enable AND `end_time` > :this_time ORDER BY `id` DESC";
//	$tg_coupon_templates = pdo_fetchall($sql, array(':enable' => ON, ':this_time' => TIMESTAMP), 'id');
//	if (empty($tg_coupon_templates)) {
//		message(error(1,'无可用优惠券'));
//	}
//	message($tg_coupon_templates);
}

