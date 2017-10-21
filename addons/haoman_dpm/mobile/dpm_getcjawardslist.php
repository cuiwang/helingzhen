<?php
global $_GPC, $_W;
$uniacid = $_W['uniacid'];
$rid = intval($_GPC['rid']);
$awardid = intval($_GPC['awardid']);
$turntable = 1;
$reply = pdo_fetch("SELECT iscjnum FROM " . tablename('haoman_dpm_reply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));

if($reply['iscjnum']==0){
    $awardslists = pdo_fetchall("SELECT * FROM " . tablename('haoman_dpm_award') . " WHERE rid = :rid and uniacid = :uniacid and turntable = :turntable ORDER BY id DESC",array(':rid'=>$rid,':uniacid'=>$uniacid,':turntable'=>1));

}
    $awardslist = pdo_fetchall("SELECT * FROM " . tablename('haoman_dpm_award') . " WHERE rid = :rid and uniacid = :uniacid and turntable = :turntable and titleid = :titleid ORDER BY id DESC",array(':rid'=>$rid,':uniacid'=>$uniacid,':turntable'=>1,':titleid'=>$awardid));

if($reply['iscjnum']==0){
    $winUserNum = 0;
    foreach ($awardslists as $v) {

        $winUserNum++;
    }
}else{
    $winUserNum = 0;
    foreach ($awardslist as $v) {
        $winUserNum++;
    }
}

$data = array(
    'ret' => 1,
    'msg' => "success",
    'num' => $winUserNum,
    "data"=> $awardslist
);


echo json_encode($data);