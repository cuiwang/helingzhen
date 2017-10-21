<?php
global $_W, $_GPC;
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
	if (!empty($_GPC['displayorder'])) {
		foreach ($_GPC['displayorder'] as $id => $displayorder) {
			pdo_update(BEST_ADV, array('displayorder' => $displayorder), array('id' => $id, 'weid' => $_W['uniacid']));
		}
		message('幻灯片排序更新成功！', $this->createWebUrl('adv', array('op' => 'display')), 'success');
	}
	$list = pdo_fetchall("SELECT * FROM ".tablename(BEST_ADV)." WHERE weid = {$_W['uniacid']} ORDER BY displayorder ASC");
} elseif ($operation == 'post') {
	$id = intval($_GPC['id']);
	if (checksubmit('submit')) {
		$thumb = $_GPC['thumb'];
		if(empty($thumb)){
			message("请上传幻灯片图片！");
		}
		$data = array(
			'weid' => $_W['uniacid'],
			'advname' => $_GPC['advname'],
			'link' => $_GPC['link'],
			'displayorder' => intval($_GPC['displayorder']),
			'thumb'=>$thumb,
		);
		if (!empty($id)) {
			pdo_update(BEST_ADV, $data, array('id' => $id));
		} else {
			pdo_insert(BEST_ADV, $data);
		}
		message('操作成功！', $this->createWebUrl('adv', array('op' => 'display')), 'success');
	}
	$adv = pdo_fetch("select * from ".tablename(BEST_ADV)." where id = {$id} and weid= {$_W['uniacid']}");
} elseif ($operation == 'delete') {
	$id = intval($_GPC['id']);
	$adv = pdo_fetch("SELECT id FROM ".tablename(BEST_ADV)." WHERE id = {$id} AND weid = {$_W['uniacid']}");
	if (empty($adv)) {
		message('抱歉，幻灯片不存在或是已经被删除！', $this->createWebUrl('adv', array('op' => 'display')), 'error');
	}
	pdo_delete(BEST_ADV, array('id' => $id));
	message('幻灯片删除成功！', $this->createWebUrl('adv', array('op' => 'display')), 'success');
} else {
	message('请求方式不存在');
}
include $this->template('web/adv');
?>