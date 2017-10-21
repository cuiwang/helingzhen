<?php
global $_GPC, $_W;
$uniacid = $_W['uniacid'];
$rid = intval($_GPC['rid']);
$len = intval($_GPC['last_id']);

$reply = pdo_fetch("SELECT isckmessage FROM " . tablename('haoman_dpm_reply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));



if($reply['isckmessage'] == 0){
    $totaldata = pdo_fetchcolumn("SELECT count(*) FROM " . tablename('haoman_dpm_messages') . " WHERE uniacid = :uniacid AND rid = :rid and status = 1 and is_back !=1 and is_xy =1", array(':uniacid' => $uniacid,':rid'=>$rid));
    // $limit = $totaldata - $len;
    $list = pdo_fetchall("SELECT * FROM " . tablename('haoman_dpm_messages') . " WHERE rid = :rid and uniacid = :uniacid and status = 1 and id>:id  and is_back !=1 and is_xy =1 ORDER BY id DESC limit 23",array(':rid'=>$rid,':uniacid'=>$uniacid,':id'=>$len));
}else{
    $totaldata = pdo_fetchcolumn("SELECT count(*) FROM " . tablename('haoman_dpm_messages') . " WHERE uniacid = :uniacid AND rid = :rid and is_back !=1 and is_xy =1", array(':uniacid' => $uniacid,':rid'=>$rid));
    // $limit = $totaldata - $len;
    $list = pdo_fetchall("SELECT * FROM " . tablename('haoman_dpm_messages') . " WHERE rid = :rid and uniacid = :uniacid and id>:id  and is_back !=1 and is_xy =1 ORDER BY id DESC limit 23",array(':rid'=>$rid,':uniacid'=>$uniacid,':id'=>$len));
}

$data = array(
    'ret' => 1,
    'data' => $list
);

echo json_encode($data);