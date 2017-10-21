<?php
global $_GPC, $_W;
$merchant = $this->checkmergentauth();
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
	$cservicelist = pdo_fetchall("SELECT * FROM " . tablename('cygoodssale_cservice') . " WHERE weid = '{$_W['uniacid']}' AND merchant_id = {$merchant['id']} ORDER BY displayorder ASC");
	include $this->template('merchantcservice');
} elseif ($operation == 'post') {
	$id = intval($_GPC['id']);
	if (!empty($id)) {
		$cservice = pdo_fetch("SELECT * FROM " . tablename('cygoodssale_cservice') . " WHERE id = {$id} AND weid = {$_W['uniacid']}");
	}
	if ($_GPC['isdo'] == 1) {
		if (empty($_GPC['name'])) {
			$resArr['error'] = 1;
			$resArr['msg'] = '请输入客服名称！';
			echo json_encode($resArr);
			exit();
		}
		if (empty($_GPC['ctype'])) {
			$resArr['error'] = 1;
			$resArr['msg'] = '请选择客服类型！';
			echo json_encode($resArr);
			exit();
		}
		$ctype = intval($_GPC['ctype']);
		if (empty($_GPC['content'])) {
			$resArr['error'] = 1;
			$resArr['msg'] = '请输入客服内容！';
			echo json_encode($resArr);
			exit();
		}else{
			$content = trim($_GPC['content']);
			if($ctype == 1){
				$content = $_W['fans']['from_user'];
			}
		}
		if (empty($_GPC['thumb'])) {
			$resArr['error'] = 1;
			$resArr['msg'] = '请上传客服头像！';
			echo json_encode($resArr);
			exit();
		}
		$data = array(
			'weid' => $_W['uniacid'],
			'name' => trim($_GPC['name']),
			'ctype' => $ctype,
			'content' => $content,
			'thumb' => $_GPC['thumb'],
			'displayorder' => intval($_GPC['displayorder']),
			'merchant_id'=>$merchant['id'],
		);
		if (!empty($id)) {
			pdo_update('cygoodssale_cservice', $data, array('id' => $id, 'weid' => $_W['uniacid']));
		} else {
			pdo_insert('cygoodssale_cservice', $data);
		}
		$resArr['error'] = 0;
		$resArr['msg'] = '操作成功！';
		echo json_encode($resArr);
		exit();
	}
	include $this->template('merchantcservice_edit');
} elseif ($operation == 'delete') {
	$id = intval($_GPC['id']);
	$cservice = pdo_fetch("SELECT id FROM " . tablename('cygoodssale_cservice') . " WHERE id = {$id} AND merchant_id = {$merchant['id']}");
	if (empty($cservice)) {
		$resArr['error'] = 1;
		$resArr['msg'] = '抱歉，该客服信息不存在或是已经被删除！';
		echo json_encode($resArr);
		exit();
	}
	pdo_delete('cygoodssale_cservice', array('id' => $id));
	pdo_delete('cygoodssale_goodscservice',array('cserviceid' => $id));
	$resArr['error'] = 0;
	$resArr['msg'] = '删除服信息成功！';
	echo json_encode($resArr);
	exit();
}
?>