<?php
global $_GPC, $_W;
$rid = intval($_GPC['rid']);
$code = $_GPC['code'];
$titleid = intval($_GPC['rawardid']);

if($code == "reset"){
    if (pdo_delete('haoman_dpm_award', array('rid' => $rid,'titleid'=>$titleid,'turntable'=>1))) {
        $data = array(
            'ret' => 1,
            'msg' => "重置奖品成功"
        );
        echo json_encode($data);
        exit;
    }else{
        $data = array(
            'ret' => 2,
            'msg' => "重置奖品失败"
        );
        echo json_encode($data);
        exit;
    }

}elseif($code == "reset_turntable"){
    if (pdo_delete('haoman_dpm_award', array('rid' => $rid,'turntable'=>3,'probalilty'=>1))) {
        $data = array(
            'ret' => 1,
            'msg' => "重置奖品成功"
        );
        echo json_encode($data);
        exit;
    }else{
        $data = array(
            'ret' => 2,
            'msg' => "重置奖品失败"
        );
        echo json_encode($data);
        exit;
    }

}elseif($code == "cjxreset"){
    if (pdo_delete('haoman_dpm_award', array('rid' => $rid,'titleid'=>$titleid,'turntable'=>4))) {
        $data = array(
            'ret' => 1,
            'msg' => "重置奖品成功"
        );
        echo json_encode($data);
        exit;
    }else{
        $data = array(
            'ret' => 2,
            'msg' => "重置奖品失败"
        );
        echo json_encode($data);
        exit;
    }
}elseif($code == "cjxresetAll"){
    if (pdo_delete('haoman_dpm_award', array('rid' => $rid,'turntable'=>4))) {
        $data = array(
            'ret' => 1,
            'msg' => "重置奖品成功"
        );
        echo json_encode($data);
        exit;
    }else{
        $data = array(
            'ret' => 2,
            'msg' => "重置奖品失败"
        );
        echo json_encode($data);
        exit;
    }
} else{
    if (pdo_delete('haoman_dpm_award', array('rid' => $rid,'turntable'=>1))) {
        $data = array(
            'ret' => 1,
            'msg' => "重置奖品成功"
        );
        echo json_encode($data);
        exit;
    }else{
        $data = array(
            'ret' => 2,
            'msg' => "重置奖品失败"
        );
        echo json_encode($data);
        exit;
    }
}
        