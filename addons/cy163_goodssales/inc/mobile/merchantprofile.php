<?php
global $_GPC, $_W;
$merchant = $this->checkmergentauth();
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
	include $this->template('merchantprofile');
} elseif ($operation == 'post') {
	if (empty($_GPC['name'])) {
		$resArr['error'] = 1;
		$resArr['msg'] = '请填写店铺名称！';
		echo json_encode($resArr);
		exit();
	}
	if (empty($_GPC['avatar'])) {
		$resArr['error'] = 1;
		$resArr['msg'] = '请上传店铺头像！';
		echo json_encode($resArr);
		exit();
	}
	if (empty($_GPC['address'])) {
		$resArr['error'] = 1;
		$resArr['msg'] = '请填写店铺地址！';
		echo json_encode($resArr);
		exit();
	}
	$data = array(
		'weid' => $_W['uniacid'],
		'name' => trim($_GPC['name']),
		'avatar' => $_GPC['avatar'],
		'address' => trim($_GPC['address']),
	);
	pdo_update('cygoodssale_merchant', $data, array('id' => $merchant['id'], 'weid' => $_W['uniacid']));
	$resArr['error'] = 0;
	$resArr['msg'] = '修改资料成功！';
	echo json_encode($resArr);
	exit();
}
?>