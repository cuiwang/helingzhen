<?php
global $_GPC, $_W;
$id = intval($_GPC['msgid']);
$rid = intval($_GPC['rid']);
$uid = $_GPC['uid'];
$from_user = $_W['openid'];
$uniacid = $_W['uniacid'];

$admin = pdo_fetch("select id from " . tablename('haoman_dpm_bpadmin') . "  where admin_openid=:admin_openid and status=0 and rid=:rid ", array(':admin_openid' => $from_user,':rid'=>$rid));
if($admin||$uid==$from_user){
	$rule = pdo_fetch("select id from " . tablename('haoman_dpm_messages') . " where id = :id ", array(':id' => $id));
	if (empty($rule)) {
		$result = array(
			'isResultTrue' => 0,
			'msg'=>1,

		);

		echo json_encode($result);
		exit();
	}
	if (pdo_delete('haoman_dpm_messages', array('id' => $id))) {
		$result = array(
			'isResultTrue' => 1,
			'msg'=>2,

		);

		echo json_encode($result);
		exit();
	}

}else{
	$result = array(
		'isResultTrue' => 0,
		'msg'=>3,

	);

	echo json_encode($result);
	exit();
}



