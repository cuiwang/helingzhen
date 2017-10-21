<?php
defined('IN_IA') or exit('Access Denied');
global $_GPC,$_W;
load()->func('tpl');
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
	$list = pdo_fetchall("SELECT * FROM " . tablename($this->t_adv) . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY displayorder DESC");
} elseif ($operation == 'post') {
	$id = intval($_GPC['id']);
	if (checksubmit('submit')) {
		$data = array(
			'uniacid' => $_W['uniacid'],
			'advname' => $_GPC['advname'],
			'link' => $_GPC['link'],
			'enabled' => intval($_GPC['enabled']),
			'displayorder' => intval($_GPC['displayorder']),
			'thumb'=>$_GPC['thumb']
		);
		if (!empty($id)) {
			pdo_update($this->t_adv, $data, array('id' => $id));
		} else {
			pdo_insert($this->t_adv, $data);
			$id = pdo_insertid();
		}
		message('更新幻灯片成功！', $this->createWebUrl('adv', array('op' => 'display')), 'success');
	}
	$adv = pdo_fetch("select * from " . tablename($this->t_adv) . " where id=:id and uniacid=:uniacid limit 1", array(":id" => $id, ":uniacid" => $_W['uniacid']));
} elseif ($operation == 'delete') {
	$id = intval($_GPC['id']);
	$adv = pdo_fetch("SELECT id FROM " . tablename($this->t_adv) . " WHERE id = '$id' AND uniacid=" . $_W['uniacid'] . "");
	if (empty($adv)) {
		message('抱歉，幻灯片不存在或是已经被删除！', $this->createWebUrl('adv', array('op' => 'display')), 'error');
	}
	pdo_delete($this->t_adv, array('id' => $id));
	message('幻灯片删除成功！', $this->createWebUrl('adv', array('op' => 'display')), 'success');
} else {
	message('请求方式不存在');
}
include $this->template('adv');
?>