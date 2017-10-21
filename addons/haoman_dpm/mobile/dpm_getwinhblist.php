<?php
global $_GPC, $_W;
$uniacid = $_W['uniacid'];
$rid = intval($_GPC['rid']);
$hbpici = intval($_GPC['hbpici']);
$turntable = 2;

$awardslist = pdo_fetchall("SELECT id,avatar,nickname,credit,awardname,prizetype,prize FROM " . tablename('haoman_dpm_award') . " WHERE rid = :rid and uniacid = :uniacid and turntable = :turntable AND hbpici = :hbpici ORDER BY iszhuangyuan DESC,credit DESC limit 30",array(':rid'=>$rid,':uniacid'=>$uniacid,':turntable'=>$turntable,':hbpici'=>$hbpici));
$winUserNum = 0;
foreach ($awardslist as $v) {
	$winUserNum++;
}

$data = array(
    'ret' => 1,
    'msg' => "success",
    'num' => $winUserNum,
    "data"=> $awardslist
);


echo json_encode($data);