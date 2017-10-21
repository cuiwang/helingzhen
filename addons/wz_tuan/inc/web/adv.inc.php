<?php
global $_W, $_GPC;
$this->backlists();
load()->func('tpl');
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
	$list = pdo_fetchall("SELECT * FROM " . tablename('wz_tuan_adv') . " WHERE weid = '{$_W['uniacid']}' ORDER BY displayorder DESC");
} elseif ($operation == 'post') {
	$id = intval($_GPC['id']);
	if (checksubmit('submit')) {
		$data = array(
			'weid' => $_W['uniacid'],
			'advname' => $_GPC['advname'],
			'link' => $_GPC['link'],
			'enabled' => intval($_GPC['enabled']),
			'displayorder' => intval($_GPC['displayorder']),
			'thumb'=>$_GPC['thumb']
		);
		if (!empty($id)) {
			pdo_update('wz_tuan_adv', $data, array('id' => $id));
		} else {
			pdo_insert('wz_tuan_adv', $data);
			$id = pdo_insertid();
		}
		message('更新幻灯片成功！', $this->createWebUrl('adv', array('op' => 'display')), 'success');
	}
	$adv = pdo_fetch("select * from " . tablename('wz_tuan_adv') . " where id=:id and weid=:weid limit 1", array(":id" => $id, ":weid" => $_W['uniacid']));
} elseif ($operation == 'delete') {
	$id = intval($_GPC['id']);
	$adv = pdo_fetch("SELECT id FROM " . tablename('wz_tuan_adv') . " WHERE id = '$id' AND weid=" . $_W['uniacid'] . "");
	if (empty($adv)) {
		message('抱歉，幻灯片不存在或是已经被删除！', $this->createWebUrl('adv', array('op' => 'display')), 'error');
	}
	pdo_delete('wz_tuan_adv', array('id' => $id));
	message('幻灯片删除成功！', $this->createWebUrl('adv', array('op' => 'display')), 'success');
} else {
	message('请求方式不存在');
}
include $this->template('web/adv', TEMPLATE_INCLUDEPATH, true);
?>