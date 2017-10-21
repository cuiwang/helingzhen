<?php
global $_GPC,$_W;
$rid = intval($_GPC['id']);

$reply = pdo_fetch( " SELECT id,hbpici FROM ".tablename('haoman_dpm_reply')." WHERE rid='".$rid."' " );
if(empty($reply)){
	$data = array(
		'success' => 100,
		'msg' => "活动信息错误",
	);
	echo json_encode($data);
	exit;
}

pdo_update('haoman_dpm_reply', array('hbpici' => $reply['hbpici']+1), array('id' => $reply['id']));

$data = array(
	'success' => 1,
	'msg' => "活动重置成功",
);

echo json_encode($data);