<?php
global $_GPC, $_W;

$rid = intval($_GPC['rid']);
$id= intval($_GPC['status']);
$op = $_GPC['op'];
$uniacid = $_W['uniacid'];
if(empty($id)){
    $data = array(
        'success' => 100,
        'msg' => "获取项目信息错误",
    );
    echo json_encode($data);
    exit;
}
$project = pdo_fetch( " SELECT * FROM ".tablename('haoman_dpm_newvote')." WHERE rid=:rid and uniacid=:uniacid and id =:id",array(':rid'=>$rid,':uniacid'=>$uniacid,':id'=>$id) );
    if(empty($project)){
        $data = array(
            'success' => 100,
            'msg' => "获取项目信息错误!",
        );
        echo json_encode($data);
        exit;
    }
    if($op=='is_enable'){
        $temp = pdo_update('haoman_dpm_newvote',array('vote_status'=>0,'status'=>0),array('id' =>$project['id']));

        if($temp){
            $data = array(
                'success' => 1,
                'msg' => "投票禁止成功",
            );
            echo json_encode($data);
            exit;
        }else{
            $data = array(
                'success' => 100,
                'msg' => "投票禁止失败",
            );
            echo json_encode($data);
            exit;
        }
    }elseif ($op=='is_stop'){
         if($project['status']!=1){
             $data = array(
                 'success' => 100,
                 'msg' => "投票未开启",
             );
             echo json_encode($data);
             exit;
         }else{
             $temp = pdo_update('haoman_dpm_newvote', array('status'=>2), array('id' => $project['id']));
              if($temp){
                  $data = array(
                      'success' => 1,
                      'msg' => "已结束",
                  );
                  echo json_encode($data);
                  exit;
              }else{
                  $data = array(
                      'success' => 100,
                      'msg' => "失败",
                  );
                  echo json_encode($data);
                  exit;
              }
         }
    }elseif($op=='is_disable'){
        $temp = pdo_update('haoman_dpm_newvote', array('vote_status'=>1), array('id' => $project['id']));
        if($temp){
            $data = array(
                'success' => 1,
                'msg' => "投票启用成功",
            );
            echo json_encode($data);
            exit;
        }else{
            $data = array(
                'success' => 100,
                'msg' => "投票启用失败",
            );
            echo json_encode($data);
            exit;
        }
    }
