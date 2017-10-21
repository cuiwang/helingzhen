<?php

defined('IN_IA') or exit('Access Denied');

global $_W, $_GPC;
load()->model('mc');

$ops = array('edit', 'delete', 'deleteall', 'showall', 'status');
$op = in_array(trim($_GPC['op']), $ops) ? trim($_GPC['op']) : 'display';

if ($op == 'edit') {
	$id = intval($_GPC['id']);
	if (!empty($id)) {
		$item = pdo_get('storex_business', array('id' => $id));
		if (empty($item)) {
			message('抱歉，商圈不存在或是已经删除！', '', 'error');
		}
	}
	if (checksubmit('submit')) {
		$data = array(
			'weid' => $_W['uniacid'],
			'title' => $_GPC['title'],
			'location_p' => $_GPC['district']['province'],
			'location_c' => $_GPC['district']['city'],
			'location_a' => $_GPC['district']['district'],
			'displayorder' => $_GPC['displayorder'],
			'status' => $_GPC['status'],
		);
		if (empty($id)) {
			pdo_insert('storex_business', $data);
		} else {
			pdo_update('storex_business', $data, array('id' => $id));
		}
		message('商圈信息更新成功！', $this->createWebUrl('business'), 'success');
	}
	include $this->template('business_form');
}

if ($op == 'delete') {
	$id = intval($_GPC['id']);
	pdo_delete('storex_business', array('id' => $id));
	message('删除成功！', referer(), 'success');
}

if ($op == 'deleteall') {
	foreach ($_GPC['idArr'] as $k => $id) {
		$id = intval($id);
		pdo_delete('storex_business', array('id' => $id));
	}
	$this->web_message('规则操作成功！', '', 0);
	exit();
}

if ($op == 'showall') {
	if ($_GPC['show_name'] == 'showall') {
		$show_status = 1;
	} else {
		$show_status = 0;
	}
	foreach ($_GPC['idArr'] as $k => $id) {
		$id = intval($id);
		if (!empty($id)) {
			pdo_update('storex_business', array('status' => $show_status), array('id' => $id));
		}
	}
	$this->web_message('操作成功！', '', 0);
	exit();
}

if ($op == 'status') {
	$id = intval($_GPC['id']);
	if (empty($id)) {
		message('抱歉，传递的参数错误！', '', 'error');
	}
	$temp = pdo_update('storex_business', array('status' => $_GPC['status']), array('id' => $id));
	if ($temp == false) {
		message('抱歉，刚才操作数据失败！', '', 'error');
	} else {
		message('状态设置成功！', referer(), 'success');
	}
}

if ($op == 'display') {
	$sql = "";
	$params = array();
	if (!empty($_GPC['title'])) {
		$sql .= ' AND `title` LIKE :title';
		$params[':title'] = "%{$_GPC['title']}%";
	}
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$list = pdo_getall('storex_business', array('weid' => $_W['uniacid'], 'title LIKE' => "%{$_GPC['title']}%"), array(), '', 'id DESC', ($pindex - 1) * $psize . ',' . $psize);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('storex_business') . " WHERE weid = '{$_W['uniacid']}' $sql", $params);
	$pager = pagination($total, $pindex, $psize);
	include $this->template('business');
}