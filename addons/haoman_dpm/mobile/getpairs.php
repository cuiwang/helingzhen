<?php
global $_GPC, $_W;
$rid = intval($_GPC['rid']);
$from_user = $_W['openid'];
$uniacid = $_W['uniacid'];

$list = pdo_fetchall("SELECT * FROM " . tablename('haoman_dpm_pair_combination') . " WHERE rid = :rid and uniacid = :uniacid ORDER BY id DESC limit 66",array(':rid'=>$rid,':uniacid'=>$uniacid));

if($list){

    $result =$list;
    $this->message($result);
}else{
    $result = '';
    $this->message($result);
}