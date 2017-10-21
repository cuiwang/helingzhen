<?php
global $_GPC, $_W;
$rid = intval($_GPC['rid']);
$uniacid = $_W['uniacid'];

$op = empty($_GPC['op']) ? 'getfans' : $_GPC['op'];
if($op == 'getfans'){
    $reply = pdo_fetch( " SELECT * FROM ".tablename('haoman_dpm_yyyreply')." WHERE rid='".$rid."' " );
    if($reply['status']!=0){
        $data = array(
            'shenyu' => 0,
            'code' => 100,
        );
        echo json_encode($data);
        exit();
    }

    $fans = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('haoman_dpm_yyyuser') . " WHERE pici = :pici and  rid = " . $rid . " and uniacid=" . $uniacid . "",array(':pici'=>$reply['pici']));

    if($reply['yyy_mannum']>=10){
        if($fans>$reply['yyy_mannum']){
            $data = array(
                'shenyu' => $reply['yyy_mannum'],
                'code' => 100,
            );
            echo json_encode($data);
            exit();
        }
    }
    $fans = ($fans < 0) ? 0 : $fans;

    $data = array(
        'shenyu' => $fans,
        'code' => 1,
    );
    echo json_encode($data);
}else{
    $data = array(
        'shenyu' => 0,
        'code' => 100,
    );
    echo json_encode($data);
}