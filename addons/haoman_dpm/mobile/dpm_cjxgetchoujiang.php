<?php
global $_GPC, $_W;
$uniacid = $_W['uniacid'];
$rid = intval($_GPC['rid']);
$winUserNum = intval($_GPC['winUserNum']);
$lotteryNumSel = intval($_GPC['lotteryNumSel']);

//if($lotteryNumSel>11){
//    $lotteryNumSel = 10;
//}

$iscjnum = intval($_GPC['iscjnum']);
$awardid = intval($_GPC['awardid']);
$turntable = 4;

$result = $this->dpm_getfans($rid,$lotteryNumSel,$iscjnum,$turntable,$awardid);
// $result = $this->dpm_getfans($rid,1,0,1,10);

// $list = pdo_fetchall("SELECT * FROM " . tablename('haoman_dpm_fans') . " WHERE rid = :rid and uniacid = :uniacid ORDER BY id DESC limit {$limit}",array(':rid'=>$rid,':uniacid'=>$uniacid));
if($result){
  $num = intval(count($result,0)+$winUserNum);
  $data = array(
      'ret' => 1,
      'msg' => "success",
      'num' => $num,
      "data"=> $result
  );
  echo json_encode($data);
  exit();
}else{
  $data = array(
      'ret' => 0,
      'msg' => "error",
      'num' => 0,
      "data"=> 0
  );
  echo json_encode($data);
  exit();
}