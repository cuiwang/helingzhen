<?php
global $_GPC, $_W;

$rid = intval($_GPC['rid']);
$id= intval($_GPC['_id']);
$num= intval($_GPC['_num']);
$op = $_GPC['op'];
$uniacid = $_W['uniacid'];
if(empty($id)){
    $data = array(
        'success' => 100,
        'msg' => "获取投票信息错误",
    );
    echo json_encode($data);
    exit;
}
$toupiao = pdo_fetch( " SELECT * FROM ".tablename('haoman_dpm_toupiao')." WHERE rid=:rid and uniacid=:uniacid and id =:id",array(':rid'=>$rid,':uniacid'=>$uniacid,':id'=>$id) );
    if(empty($toupiao)){
        $data = array(
            'success' => 100,
            'msg' => "获取投票不存在!",
        );
        echo json_encode($data);
        exit;
    }

        $temp = pdo_update('haoman_dpm_toupiao',array('get_num'=>$toupiao['get_num']+$num,'new_number'=>$toupiao['new_number']+$num),array('id' =>$id));

        if($temp){
            $data = array(
                'success' => 1,
                'msg' => "修改成功",
            );
            echo json_encode($data);
            exit;
        }else{
            $data = array(
                'success' => 100,
                'msg' => "修改失败",
            );
            echo json_encode($data);
            exit;
        }
