<?php
global $_GPC,$_W;
$rid = intval($_GPC['rid']);

$reply = pdo_fetch( " SELECT * FROM ".tablename('haoman_dpm_yyyreply')." WHERE rid='".$rid."' " );
if(empty($reply) || $reply['isyyy'] != 0){
    $data = array(
        'flag' => 100,
        'msg' => "活动信息错误",
    );
    echo json_encode($data);
    exit;
}

pdo_update('haoman_dpm_yyyreply', array('status' => 2), array('id' => $reply['id']));

$data = array(
    'flag' => 1,
    'msg' => "活动状态修改正确",
); 

echo json_encode($data);