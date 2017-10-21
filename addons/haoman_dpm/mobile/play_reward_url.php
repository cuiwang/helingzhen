<?php
global $_GPC, $_W;

$rid = intval($_GPC['rid']);
$uniacid = $_W['uniacid'];
$from_user = $_W['openid'];

$item_list = pdo_fetchall("SELECT * FROM " . tablename('haoman_dpm_guest') . " WHERE rid = :rid and uniacid = :uniacid and turntable =2  ORDER BY id desc",array(':rid'=>$rid,':uniacid'=>$uniacid));
$guest_list = pdo_fetchall("SELECT * FROM " . tablename('haoman_dpm_guest') . " WHERE rid = :rid and uniacid = :uniacid and turntable =1 ORDER BY id desc",array(':rid'=>$rid,':uniacid'=>$uniacid));


// foreach($item_list as &$v){
//     $v['pic'] = tomedia($v['pic']);
// }
// unset($v);
// foreach($guest_list as &$k){
//     $k['pic'] = tomedia($k['pic']);
// }
// unset($k);

// print_r($guest_list);
// print_r($item_list);
// exit;
if($guest_list&&$item_list){
    $result = array(
        'error' => 0,
        'guest_list' => $guest_list,
        'item_list' => $item_list,
    );
}else{
    $result = array(
        'error' => 1,
    );
}

$this->message($result);