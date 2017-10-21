<?php
global $_W, $_GPC;
$rid = intval($_GPC['rid']);
$pici = intval($_GPC['pici']);
$uniacid = $_W['uniacid'];
$from_user = $_W['fans']['from_user'];
$avatar = $_W['fans']['tag']['avatar'];
$nickname = $_W['fans']['nickname'];
if (!empty($rid)) {
    $reply = pdo_fetch( " SELECT pici,yyy_maxnum,status FROM ".tablename('haoman_dpm_yyyreply')." WHERE rid='".$rid."' " );
}

if(empty($pici)){
   $data = array(
        'flag' => 11,
        'msg' => "获取不到您的批次信息，请刷新后重试",
    ); 
   echo json_encode($data);
   exit;
}

if($reply['pici'] == 1 && $reply['status']==0){
   $data = array(
        'flag' => 11,
        'msg' => "活动还没开始，获取不到排名信息",
    ); 
   echo json_encode($data);
   exit;
}

if($reply['status'] == 1){
   $data = array(
        'flag' => 11,
        'msg' => "活动还未结束，请结束后再查看排名",
    ); 
   echo json_encode($data);
   exit;
}

$newPici = array();
for($i=0;$i<$reply['pici'];$i++){
    $newPici[$i]['pici'] = $i+1;
}

load()->model('account');
$_W['account'] = account_fetch($_W['acid']);
$cookieid = '__cookie_haoman_dpm_201606186_' . $rid;
$cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
if ($_W['account']['level'] != 4) {
    $from_user = authcode(base64_decode($_GPC['from_user']), 'DECODE');
    $avatar = $cookie['avatar'];
    $nickname = $cookie['nickname'];
}

if(empty($yyyreply['yyy_maxnum'])){
    $yyyreply['yyy_maxnum'] = 100;
}

if($reply['pici'] != 1 && $pici != $reply['pici']){
    $pici = $reply['pici'] - 1;
    $data = pdo_fetchall("SELECT from_user FROM " . tablename('haoman_dpm_yyyuser') . " WHERE rid = :rid AND uniacid = :uniacid AND pici = :pici  AND point != 0 ORDER BY point DESC,endtime ASC", array(':rid' => $rid, ':uniacid' => $uniacid, ':pici' => $pici));
    $order = 1;
    foreach ($data as $v) {
        if($v['from_user'] == $from_user){
            break;
        }
        $order++;
    }
    $data = array(
        'flag' => 1,
        'msg' => "您上轮排名第".$order."名",
    );


}else{
    $pici = $reply['pici'];
    $data = pdo_fetchall("SELECT from_user FROM " . tablename('haoman_dpm_yyyuser') . " WHERE rid = :rid AND uniacid = :uniacid AND pici = :pici  AND point != 0 ORDER BY point DESC,endtime ASC", array(':rid' => $rid, ':uniacid' => $uniacid, ':pici' => $pici));
    $order = 1;
    foreach ($data as $v) {
        if($v['from_user'] == $from_user){
            break;
        }
        $order++;
    }
    $data = array(
        'flag' => 1,
        'msg' => "您本轮排名第".$order."名",
    );
}

echo json_encode($data);
