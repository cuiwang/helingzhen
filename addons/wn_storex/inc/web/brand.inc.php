<?php

defined('IN_IA') or exit('Access Denied');

global $_W, $_GPC;
load()->model('mc');

$ops = array('edit', 'delete', 'deleteall', 'showall', 'status');
$op = in_array(trim($_GPC['op']), $ops) ? trim($_GPC['op']) : 'display';

if ($op == 'edit') {
	$id = intval($_GPC['id']);
	if (!empty($id)) {
		$item = pdo_get('storex_brand', array('id' => $id));
		if (empty($item)) {
			message('抱歉，品牌不存在或是已经删除！', '', 'error');
		}
	}
	if (checksubmit('submit')) {
		$data = array(
			'weid' => $_W['uniacid'],
			'title' => $_GPC['title'],
			'status' => $_GPC['status'],
		);
		if (empty($id)) {
			pdo_insert('storex_brand', $data);
		} else {
			pdo_update('storex_brand', $data, array('id' => $id));
		}
		message('品牌信息更新成功！', $this->createWebUrl('brand'), 'success');
	}
	include $this->template('brand_form');
}

if ($op == 'delete') {
	$id = intval($_GPC['id']);
	pdo_delete('storex_brand', array('id' => $id));
	message('删除成功！', referer(), 'success');
}

if ($op == 'deleteall') {
	foreach ($_GPC['idArr'] as $k => $id) {
		$id = intval($id);
		pdo_delete('storex_brand', array('id' => $id));
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
			pdo_update('storex_brand', array('status' => $show_status), array('id' => $id));
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
	$temp = pdo_update('storex_brand', array('status' => $_GPC['status']), array('id' => $id));
	
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
	$list = pdo_getall('storex_brand', array('weid' => $_W['uniacid'], 'title LIKE' => "%{$_GPC['title']}%"), array(), '', 'displayorder DESC', ($pindex - 1) * $psize.','.$psize);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('storex_brand') . " WHERE weid = '{$_W['uniacid']}' $sql", $params);
	$pager = pagination($total, $pindex, $psize);
	include $this->template('brand');
}