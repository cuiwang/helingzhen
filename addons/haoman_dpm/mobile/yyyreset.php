<?php
global $_GPC,$_W;
$rid = intval($_GPC['rid']);
$pici = intval($_GPC['pici']);

$reply = pdo_fetch( " SELECT * FROM ".tablename('haoman_dpm_yyyreply')." WHERE rid='".$rid."' " );
if(empty($reply) || $reply['isyyy'] != 0){
    $data = array(
        'flag' => 100,
        'msg' => "活动信息错误",
    );
    echo json_encode($data);
    exit;
}

if($reply['status'] == 0 || $reply['status'] == 1 && $pici != $reply['pici']){
   $data = array(
        'flag' => 1,
        'msg' => "活动状态修改正确",
    ); 
}else{
    $data = array(
        'flag' => 2,
        'msg' => "下一轮活动还未开启，请关注大屏幕",
    ); 
}



echo json_encode($data);