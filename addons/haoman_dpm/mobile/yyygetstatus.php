<?php
global $_GPC, $_W;
$rid = intval($_GPC['rid']);
// $count = intval($_GPC['count']);
$getpici = intval($_GPC['pici']);
$uniacid = $_W['uniacid'];
$from_user = $_W['fans']['from_user'];

$reply = pdo_fetch( " SELECT id,pici,yyy_maxnum,status,isyyy,createtime FROM ".tablename('haoman_dpm_yyyreply')." WHERE rid='".$rid."' " );

if($reply['isyyy'] == 1){
    $data = array(
        'shenyu' => $count,
        'msg' => '未开启摇一摇功能，请先后台开启！',
        'code' => 11,
    );
    echo json_encode($data);
    exit;
}

if($reply['pici'] != $getpici){
    $data = array(
        'shenyu' => 0,
        'msg' => '活动已开始，刷新后参与',
        'status' => 0,
        'code' => 33,
    );
    echo json_encode($data);
    exit;
}

if($reply['status'] == 0 || $reply['status'] == 2){
    $data = array(
        'shenyu' => 0,
        'msg' => '活动未开始或是已经结束',
        'status' => $reply['status'],
        'code' => 22,
    );
    echo json_encode($data);
    exit;
}




$data = array(
    'status' => $reply['status'],
    'msg' => $reply['pici'],
    'code' => 99,
);


echo json_encode($data);