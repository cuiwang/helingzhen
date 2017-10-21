<?php
global $_GPC, $_W;
$merchant = $this->checkmergentauth();
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
	$list = pdo_fetchall("SELECT * FROM ".tablename(BEST_ADV)." WHERE weid = {$_W['uniacid']} AND merchant_id = {$merchant['id']} ORDER BY displayorder ASC");
	include $this->template('merchantadv');
}elseif ($operation == 'post') {
	$id = intval($_GPC['id']);
	if (!empty($id)) {
		$item = pdo_fetch("SELECT * FROM " . tablename(BEST_ADV) . " WHERE merchant_id = {$merchant['id']} AND id = {$id}");
		if (empty($item)) {
			message('抱歉，幻灯片不存在或是已经删除！', '', 'error');
		}
	}
	if ($_GPC['submit']) {
		if (empty($_GPC['advname'])) {
			$resArr['error'] = 1;
			$resArr['msg'] = '请输入幻灯片名称！';
			echo json_encode($resArr);
			exit();
		}
		$data = array(			
			'weid' => intval($_W['uniacid']),
			'displayorder' => intval($_GPC['displayorder']),
			'advname' => $_GPC['advname'],
			'link' => $_GPC['link'],
			'thumb' => $_GPC['thumb'],
			'merchant_id'=>$merchant['id'],
		);
		if (empty($id)) {
			pdo_insert(BEST_ADV, $data);
		} else {
			pdo_update(BEST_ADV, $data, array('id' => $id));
		}	
		$resArr['error'] = 0;
		$resArr['msg'] = '操作成功！';
		echo json_encode($resArr);
		exit();
	}
	include $this->template('merchantadv_edit');
}elseif ($operation == 'delete') {
	$id = intval($_GPC['id']);
	$row = pdo_fetch("SELECT id FROM " . tablename(BEST_ADV) . " WHERE merchant_id = {$merchant['id']} AND id = {$id}");
	if (empty($row)) {
		$resArr['error'] = 1;
		$resArr['msg'] = '抱歉，幻灯片不存在或是已经被删除！';
		echo json_encode($resArr);
		exit();
	}
	pdo_delete(BEST_ADV, array('id' => $id));
	$resArr['error'] = 0;
	$resArr['msg'] = '删除成功！';
	echo json_encode($resArr);
	exit();
}
?>