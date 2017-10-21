<?php
global $_GPC, $_W;
$rid = intval($_GPC['rid']);
$from_user = $_W['openid'];
$uniacid = $_W['uniacid'];

load()->model('account');
$_W['account'] = account_fetch($_W['acid']);
$cookieid = '__cookie_haoman_dpm_201606186_' . $rid;
$cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
if ($_W['account']['level'] != 4) {
    $from_user = $cookie['openid'];
}

$sex =intval($_GPC['type']);
$page =intval($_GPC['page']);

$index = 100;

$start = ($page+1) * $index;
$limit .= " LIMIT {$start},{$index}";
//$reply = pdo_fetch("SELECT is_realname FROM " . tablename('haoman_dpm_reply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
if(!empty($sex)){
    $list = pdo_fetchall("SELECT * FROM " . tablename('haoman_dpm_fans') . " WHERE rid = :rid and uniacid = :uniacid and is_back !=1 and sex=:sex ORDER BY id DESC limit 100",array(':rid'=>$rid,':uniacid'=>$uniacid,':sex'=>$sex));
}else{
    $list = pdo_fetchall("SELECT * FROM " . tablename('haoman_dpm_fans') . " WHERE rid = :rid and uniacid = :uniacid and is_back !=1  ORDER BY id DESC ".$limit,array(':rid'=>$rid,':uniacid'=>$uniacid));

}
foreach($list as &$v){
    if($v['sex']==1){
        $v['sex'] = 'male';
    }elseif($v['sex']==2){
        $v['sex'] = 'female';
    }else{
        $v['sex'] = '';
    }
}
unset($v);



$result = array(
    'count'=>$rid,
    'page'=>$page,
    'list'=>$list,
);
$this->message($result);