<?php
global $_GPC,$_W;
$rid = intval($_GPC['rid']);
$type = $_GPC['type'];

$reply = pdo_fetch( " SELECT * FROM ".tablename('haoman_dpm_yyyreply')." WHERE rid='".$rid."' " );
if(empty($reply) || $reply['isyyy'] != 0){
    $data = array(
        'flag' => 100,
        'msg' => "活动信息错误",
    );
    echo json_encode($data);
    exit;
}

if($type == 'start'){
    pdo_update('haoman_dpm_yyyreply', array('status' => 1,'createtime' =>time()), array('id' => $reply['id']));

    $data = array(
        'flag' => 1,
        'msg' => "活动状态修改正确",
    );
}else{
    pdo_update('haoman_dpm_yyyreply', array('status' => 0,'pici'=>$reply['pici']+1), array('id' => $reply['id']));

    $data = array(
        'flag' => 1,
        'msg' => "活动状态修改正确",
    );
}

echo json_encode($data);