<?php
global $_GPC, $_W;
$uniacid = $_W['uniacid'];
$rid = intval($_GPC['rid']);

$fans = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('haoman_dpm_fans') . " WHERE rid = " . $rid . " and uniacid=" . $uniacid . " and isbaoming = 0");
$fans = ($fans < 0) ? 0 : $fans;

$votelist = pdo_fetchall("SELECT `id`,`pid`,`rid`,`uniacid`,`get_num`,`number`,`name`,`avatar`,`status` FROM " . tablename('haoman_dpm_toupiao') . " WHERE rid = :rid and uniacid = :uniacid and status = 0 and vote_id=0 ORDER BY pid DESC",array(':rid'=>$rid,':uniacid'=>$uniacid));
$totalnum = 0;
foreach ($votelist as $value) {
    $totalnum += $value['get_num'];
}
 if($totalnum==0){
     $totalnum =1;
 }
$data = array(
    'ret' => 1,
    'shenyu'=>$fans,
    'msg' => "success",
    'num' => $totalnum,
    "datalist"=> $votelist
);


echo json_encode($data);