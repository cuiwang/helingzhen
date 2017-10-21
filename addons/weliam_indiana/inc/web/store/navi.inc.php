<?php
global $_W,$_GPC;
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
	$list = pdo_fetchall("SELECT * FROM " . tablename('weliam_indiana_navi') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY displayorder DESC");
} elseif ($operation == 'post') {
	$id = intval($_GPC['id']);
	if (checksubmit('submit')) {
		$data = array(
			'uniacid' => $_W['uniacid'],
			'name' => $_GPC['advname'],
			'link' => $_GPC['link'],
			'enabled' => intval($_GPC['enabled']),
			'displayorder' => intval($_GPC['displayorder']),
			'thumb'=>$_GPC['thumb']
		);
		if (!empty($id)) {
			pdo_update('weliam_indiana_navi', $data, array('id' => $id));
		} else {
			pdo_insert('weliam_indiana_navi', $data);
			$id = pdo_insertid();
		}
		message('更新导航成功！', $this->createWebUrl('navi', array('op' => 'display')), 'success');
	}
	$adv = pdo_fetch("select * from " . tablename('weliam_indiana_navi') . " where id=:id and uniacid=:uniacid limit 1", array(":id" => $id, ":uniacid" => $_W['uniacid']));
} elseif ($operation == 'delete') {
	$id = intval($_GPC['id']);
	$adv = pdo_fetch("SELECT id FROM " . tablename('weliam_indiana_navi') . " WHERE id = '$id' AND uniacid = " . $_W['uniacid'] . "");
	if (empty($adv)) {
		message('抱歉，首页导航不存在或是已经被删除！', $this->createWebUrl('navi', array('op' => 'display')), 'error');
	}
	pdo_delete('weliam_indiana_navi', array('id' => $id));
	message('导航删除成功！', $this->createWebUrl('navi', array('op' => 'display')), 'success');
} else {
	message('请求方式不存在');
}
include $this->template('navi', TEMPLATE_INCLUDEPATH, true);
?>