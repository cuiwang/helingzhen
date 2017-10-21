<?php
// 
//  notice.inc.php
//  <project>
//  公告栏
//  Created by Administrator on 2016-06-28.
//  Copyright 2016 Administrator. All rights reserved.
// 
global $_W,$_GPC;

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
	$list = pdo_fetchall("SELECT * FROM " . tablename('weliam_indiana_notice') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY id DESC");
} elseif ($operation == 'post') {
	$id = intval($_GPC['id']);
	if (checksubmit('submit')) {
		$data = array(
			'uniacid' => $_W['uniacid'],
			'title' => $_GPC['title'],
			'content' => htmlspecialchars_decode($_GPC['content']),
			'enabled' => intval($_GPC['enabled']),
			'createtime'=> time()
		);
		if (!empty($id)) {
			pdo_update('weliam_indiana_notice', $data, array('id' => $id));
		} else {
			pdo_insert('weliam_indiana_notice', $data);
			$id = pdo_insertid();
		}
		message('更新公告成功！',$this->createWebUrl('notice'), 'success');
	}
	$adv = pdo_fetch("select * from " . tablename('weliam_indiana_notice') . " where id=:id and uniacid=:uniacid limit 1", array(":id" => $id, ":uniacid" => $_W['uniacid']));
} 

if ($operation == 'delete') {
	$id = intval($_GPC['id']);
	$adv = pdo_fetch("SELECT id FROM " . tablename('weliam_indiana_notice') . " WHERE id = '$id' AND uniacid = " . $_W['uniacid'] . "");
	if (empty($adv)) {
		message('抱歉，首页公告不存在或是已经被删除！', $this->createWebUrl('notice'), 'error');
	}
	pdo_delete('weliam_indiana_notice', array('id' => $id));
	message('公告删除成功！', $this->createWebUrl('notice'), 'success');
}
include $this->template('notice');
