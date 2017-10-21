<?php
global $_GPC, $_W;
$uniacid = $_W['uniacid'];
$rid = intval($_GPC['rid']);

$project_id = intval($_GPC['project_id']);

if(empty($project_id)){
    return;
}

//$fans = pdo_fetchcolumn("SELECT vote_people_times FROM " . tablename('haoman_dpm_newvote') . " WHERE rid = " . $rid . " and uniacid=" . $uniacid . " and ");
//
//$fans = ($fans < 0) ? 0 : $fans;

$project = pdo_fetch( " SELECT * FROM ".tablename('haoman_dpm_newvote')." WHERE rid=:rid and uniacid=:uniacid and id =:id and status=1 and vote_status =1",array(':rid'=>$rid,':uniacid'=>$uniacid,':id'=>$project_id) );


$votelist = pdo_fetchall("SELECT `id`,`pid`,`rid`,`uniacid`,`get_num`,`number`,`name`,`avatar`,`status` FROM " . tablename('haoman_dpm_toupiao') . " WHERE rid = :rid and uniacid = :uniacid and status = 0 and vote_id=:vote_id ORDER BY pid DESC",array(':rid'=>$rid,':uniacid'=>$uniacid,':vote_id'=>$project['id']));
$totalnum = 0;
foreach ($votelist as $value) {
    $totalnum += $value['get_num'];
}
 if($totalnum==0){
     $totalnum =1;
 }
$data = array(
    'ret' => 1,
    'shenyu'=>$project['vote_people_times'],
    'msg' => "success",
    'num' => $totalnum,
    "datalist"=> $votelist
);


echo json_encode($data);