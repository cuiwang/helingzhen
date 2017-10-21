<?php
global $_GPC, $_W;
$uniacid = $_W['uniacid'];
$rid = intval($_GPC['rid']);
$len = intval($_GPC['len']);
$hbpici = intval($_GPC['hbpici']);
$turntable = 2;
// $reply = pdo_fetch( " SELECT hbpici FROM ".tablename('haoman_dpm_reply')." WHERE rid='".$rid."' " );

$totaldata = pdo_fetchcolumn("SELECT count(*) FROM " . tablename('haoman_dpm_award') . " WHERE uniacid = :uniacid AND rid = :rid AND turntable = :turntable AND hbpici = :hbpici", array(':uniacid' => $uniacid,':rid'=>$rid,':turntable'=>$turntable,':hbpici'=>$hbpici));

$limit = $totaldata - $len;

$awardslist = pdo_fetchall("SELECT * FROM " . tablename('haoman_dpm_award') . " WHERE rid = :rid and uniacid = :uniacid and turntable = :turntable AND hbpici = :hbpici ORDER BY id DESC limit {$limit}",array(':rid'=>$rid,':uniacid'=>$uniacid,':turntable'=>$turntable,':hbpici'=>$hbpici));
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