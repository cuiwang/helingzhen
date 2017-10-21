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
if(!empty($sex)&&$sex!=0){
    $list = pdo_fetchall("SELECT * FROM " . tablename('haoman_dpm_shop_goods') . " WHERE rid = :rid and uniacid = :uniacid and deleted!=1 and status=1 and categoryid=:categoryid ORDER BY sale_number DESC limit 200",array(':rid'=>$rid,':uniacid'=>$uniacid,':categoryid'=>$sex));
}else{
    $list = pdo_fetchall("SELECT * FROM " . tablename('haoman_dpm_shop_goods') . " WHERE rid = :rid and uniacid = :uniacid and deleted!=1 and status=1  ORDER BY sale_number DESC ",array(':rid'=>$rid,':uniacid'=>$uniacid));

}
foreach($list as &$v){
    $v['thumb'] = tomedia($v['thumb']);
    $v['number'] = pdo_fetch("select * from " . tablename("haoman_dpm_shop_car") . " where rid =:rid and uniacid=:uniacid and shopid=:shopid and from_user=:from_user and status=0",array(':rid'=>$rid,':uniacid'=>$uniacid,':shopid'=>$v['id'],':from_user'=>$from_user));
}
unset($v);



$result = array(
    'count'=>$rid,
    'page'=>$page,
    'list'=>$list,
);
$this->message($result);