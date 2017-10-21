<?php
global $_GPC,$_W;
$rid = intval($_GPC['id']);
$isqhbshow = intval($_GPC['isqhbshow']);


$reply = pdo_fetch( " SELECT * FROM ".tablename('haoman_dpm_reply')." WHERE rid='".$rid."' " );
if(empty($reply)){
	$data = array(
		'success' => 100,
		'msg' => "活动信息错误",
	);
	echo json_encode($data);
	exit;
}


pdo_update('haoman_dpm_reply', array('isqhbshow' => $isqhbshow), array('id' => $reply['id']));

$data = array(
	'success' => 1,
	'msg' => "活动状态修改正确",
);

echo json_encode($data);