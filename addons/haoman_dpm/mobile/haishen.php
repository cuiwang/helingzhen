<?php
global $_GPC, $_W;
$rid = intval($_GPC['rid']);
$gets = $_GPC['gets'];
$uniacid = $_W['uniacid'];
if($gets == 'message'){

    if($_GPC['isckmessage'] == 0){
    	$totaldata = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('haoman_dpm_messages') . " WHERE uniacid = :uniacid AND rid = :rid and status = 1 and is_xy !=1 and is_bp !=1", array(':uniacid' => $uniacid,':rid'=>$rid));
    }else{
    	$totaldata = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('haoman_dpm_messages') . " WHERE uniacid = :uniacid AND rid = :rid and is_xy !=1 and is_bp !=1", array(':uniacid' => $uniacid,':rid'=>$rid));
    }
    $data = array(
        'shenyu' => $totaldata,
        'code' => 99,
    );

}elseif($gets == 'fans'){
	$uniacid = $_W['uniacid'];
    $fans = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('haoman_dpm_fans') . " WHERE rid = " . $rid . " and uniacid=" . $uniacid . " and isbaoming=0 and is_back !=1");
    $fans = ($fans < 0) ? 0 : $fans;
    $data = array(
        'shenyu' => $fans,
        'code' => 99,
    );
}else{
	$data = array(
        'shenyu' => 0,
        'code' => 990,
    );
}

echo json_encode($data);