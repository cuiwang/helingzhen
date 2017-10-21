<?php
global $_GPC, $_W;
$rid = intval($_GPC['rid']);
$uid= $_GPC['from_user'];
$to_uid= $_GPC['to_from_user'];
$uniacid = $_W['uniacid'];

$from_user = $_W['openid'];

load()->model('account');
$_W['account'] = account_fetch($_W['acid']);
$cookieid = '__cookie_haoman_dpm_201606186_' . $rid;
$cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
if ($_W['account']['level'] != 4) {
    $from_user = $cookie['openid'];
}

if($from_user){
    $is_online = pdo_fetch("select id,createtime,from_user from " . tablename('haoman_dpm_on_line') . " where uniacid=:uniacid and rid =:rid and from_user = :from_user and to_from_user=:to_from_user", array(':uniacid'=>$uniacid,':rid'=>$rid,':from_user' => $from_user,':to_from_user'=>$to_uid));
    $time =31;//一分钟
    $now_time=time();
    if($is_online){
        $time =$now_time- $is_online['createtime'];

    }
    if($time<30){
        //在线
        pdo_update('haoman_dpm_on_line', array('createtime' => $now_time), array('id' => $is_online['id']));

    }else{
        //离线
    }
}


