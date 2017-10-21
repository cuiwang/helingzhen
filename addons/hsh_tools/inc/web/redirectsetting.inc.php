<?php

global $_W, $_GPC;
$tableName=$this->modulename . '_url_redirect';
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == "post") {
	$id = intval($_GPC['id']);
	load()->func('tpl');
	if (checksubmit('submit')) {
		if (empty($_GPC['title'])) {
			message('抱歉，请输入跳转名称！');
		}
		if (empty($_GPC['go_url'])) {
			message('抱歉，请输入跳转链接！');
		}
		$data = array(
			'weid' => $_W['uniacid'],
			'title' => $_GPC['title'],
			'go_url' => $_GPC['go_url'],
			'back_url' => $_GPC['back_url'],
			'redirect_type' => $_GPC['redirect_type'],
			'test_mode' => $_GPC['test_mode'],
			'arg_state' => $_GPC['arg_state'],
			'count' => $_GPC['count'],
		);
		if (empty($data['count'])) {
			$data['count'] = '0'; 
		}

		if (empty($data['weid'])) {
			message('非法参数');
		}

		if (!empty($id)) {
			pdo_update($tableName, $data, array('id' => $id));
		} else {
			pdo_insert($tableName, $data);
			$id = pdo_insertid();
		}
		message('更新数据成功！', $this->createWebUrl('RedirectSetting', array('op' => 'display')), 'success');
		die();
	}
	if (!empty($id)) {
		$redirectSetting = pdo_fetch("SELECT * FROM " . tablename($tableName) . " WHERE id = '$id'");
	} else {
		$redirectSetting = array(
		);
	}
}
if ($operation == "delete") {
	$id = intval($_GPC['id']);
	$redirectSetting = pdo_fetch("SELECT id FROM " . tablename($tableName) . " WHERE id = '$id'");
	if (empty($redirectSetting)) {
		message('抱歉，数据不存在或是已经被删除！', $this->createWebUrl('RedirectSetting', array('op' => 'display')), 'error');
	}
	pdo_update($tableName, array('state' => -1), $redirectSetting);
	message('数据删除成功！', $this->createWebUrl('RedirectSetting', array('op' => 'display')), 'success');
	die();
}
$pageindex = max(intval($_GPC['page']), 1); // 当前页码
$pagesize = 10; // 设置分页大小
$where = " WHERE weid = '{$_W['uniacid']}' and state <> -1 ";
$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($tableName) . $where);
$redirectSettings = pdo_fetchall("SELECT * FROM " . tablename($tableName) . $where."  ORDER BY id DESC LIMIT " . ($pageindex - 1) * $pagesize . ",$pagesize");
$pager = pagination($total, $pageindex, $pagesize);
$redirectUrl = new RedirectUrl();
foreach($redirectSettings as $key => $val) {
	$redirectSettings[$key]['redirect_url'] =  $_W['siteroot']."r?rid=".$val['id'];
	$redirectSettings[$key]['oAuth_url'] =  $redirectUrl->getOAuthUrl($redirectSettings[$key]['redirect_url']);
}
include $this->template('RedirectSetting');
