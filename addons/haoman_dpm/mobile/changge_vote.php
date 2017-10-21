<?php
global $_GPC,$_W;
$rid = intval($_GPC['rid']);
$uniacid = $_W['uniacid'];
$status = intval($_GPC['status']);
$project_id = intval($_GPC['project_id']);
$token = empty($_GPC['token'])?'display':$_GPC['token'];
if(empty($project_id)){
    $data = array(
        'success' => 100,
        'msg' => "活动信息错误",
    );
    echo json_encode($data);
    exit;
}

$project = pdo_fetch( " SELECT * FROM ".tablename('haoman_dpm_newvote')." WHERE rid=:rid and uniacid=:uniacid and id =:id",array(':rid'=>$rid,':uniacid'=>$uniacid,':id'=>$project_id) );
if(empty($project)){
	$data = array(
		'success' => 100,
		'msg' => "活动信息错误",
	);
	echo json_encode($data);
	exit;
}
if($token=='button'){
    if($status==1){
        $status=2;
        $img_url ='../addons/haoman_dpm/img9/start.png';
    }elseif ($status==2){
        $status=0;
        $img_url ='../addons/haoman_dpm/img9/start.png';
    }
    else{
        $status=1;
        $img_url ='../addons/haoman_dpm/img9/end.png';
    }



    pdo_update('haoman_dpm_newvote', array('status' => $status), array('id' => $project['id']));

    $data = array(
        'success' => 1,
        'status' => $status,
        'url' => $img_url,
        'msg' => "状态改变成功",
    );

    echo json_encode($data);
}
