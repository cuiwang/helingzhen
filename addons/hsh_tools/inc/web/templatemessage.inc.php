<?php

global $_W, $_GPC;
$tableName = $this->modulename . '_tm';
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == "post") {
	$id = intval($_GPC['id']);
	load()->func('tpl');
	if (checksubmit('submit')) {
		if (empty($_GPC['title'])) {
			message('抱歉，请输入模板名称！');
		}
		if (empty($_GPC['template_id'])) {
			message('抱歉，请输入模板ID！');
		}
		$data = array(
			'weid' => $_W['uniacid'],
			'title' => $_GPC['title'],
			'template_id' => $_GPC['template_id'],
			'url' => $_GPC['url'],
			'topcolor' => $_GPC['topcolor'],
			'data' => $_GPC['data'],
		);

		if (empty($data['weid'])) {
			message('非法参数');
		}

		if (!empty($id)) {
			unset($data['parentid']);
			pdo_update($tableName, $data, array('id' => $id));
		} else {
			pdo_insert($tableName, $data);
			$id = pdo_insertid();
		}
		message('更新消息模板成功！', $this->createWebUrl('TemplateMessage', array('op' => 'display')), 'success');
		die();
	}
	if (!empty($id)) {
		$messageTemplate = pdo_fetch("SELECT * FROM " . tablename($tableName) . " WHERE id = '$id'");
	} else {
		$messageTemplate = array(
		);
	}
}
if ($operation == "delete") {
	$id = intval($_GPC['id']);
	$messageTemplate = pdo_fetch("SELECT id FROM " . tablename($tableName) . " WHERE id = '$id'");
	if (empty($messageTemplate)) {
		message('抱歉，消息模板不存在或是已经被删除！', $this->createWebUrl('TemplateMessage', array('op' => 'display')), 'error');
	}
	pdo_delete($tableName, array('id' => $id));
	message('消息模板删除成功！', $this->createWebUrl('TemplateMessage', array('op' => 'display')), 'success');
	die();
}

$pageindex = max(intval($_GPC['page']), 1); // 当前页码
$pagesize = 10; // 设置分页大小
$where = " WHERE weid = '{$_W['uniacid']}' ";
$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($tableName) . $where);
$messageTemplates = pdo_fetchall("SELECT * FROM " . tablename($tableName) . $where."  ORDER BY id DESC LIMIT " . ($pageindex - 1) * $pagesize . ",$pagesize");
$pager = pagination($total, $pageindex, $pagesize);
include $this->template('TemplateMessage');
