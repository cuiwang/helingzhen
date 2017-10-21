<?php
global $_GPC, $_W;

$from_user = $_W['openid'];

$uniacid = $_W['uniacid'];
$rid = intval($_GPC['rid']);

load()->model('account');
$_W['account'] = account_fetch($_W['acid']);
$cookieid = '__cookie_haoman_dpm_201606186_' . $rid;
$cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
if ($_W['account']['level'] != 4) {
    $from_user = $cookie['openid'];
}

$fans = pdo_fetch("select id,from_user,is_online from " . tablename('haoman_dpm_fans') . " where rid =:rid and from_user = :from_user ", array(':rid'=>$rid,':from_user' => $from_user));

$list = pdo_fetchall("SELECT id FROM " . tablename('haoman_dpm_private_chat') . " WHERE rid = :rid and uniacid = :uniacid and message_to_fansid =:message_to_fansid ORDER BY id desc",array(':rid'=>$rid,':uniacid'=>$uniacid,':message_to_fansid'=>$fans['id']));

$num =count($list);

if($num>0){
    $result = array(
        'code' => 1,
        'data' => $num,

    );
}else{
    $result = array(
        'code' => 0,
        'data' => 0,

    );
}


$this->message($result);