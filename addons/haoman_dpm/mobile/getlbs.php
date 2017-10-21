<?php
global $_GPC, $_W;
//$id = intval($_GPC['id']);
$lat1 = $_GPC['lat'];
$lon1 = $_GPC['lon'];
$allowlbsip = explode("|",$_GPC['lbsip']);
$lat2 = $allowlbsip[0];
$lon2 = $allowlbsip[1];
$dis = intval($allowlbsip[3]);
$res = intval($this->getDistance($lat1,$lon1,$lat2,$lon2));
if ($res <= $dis) {
	$data = array(
		'success' => 1,
		'msg' => '您可以正常参加活动！',
	);
} else {
	$data = array(
		'success' => 100,
		'msg' => '您不在允许参加的活动范围内！',
	);
}
echo json_encode($data);