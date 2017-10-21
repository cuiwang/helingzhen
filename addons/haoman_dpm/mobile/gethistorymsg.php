<?php
global $_GPC, $_W;
$rid = intval($_GPC['rid']);
$uid = $_GPC['uid'];
$from_user = $_W['openid'];
$minid = $_GPC['minid'];

load()->model('account');
$_W['account'] = account_fetch($_W['acid']);
$cookieid = '__cookie_haoman_dpm_201606186_' . $rid;
$cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
if ($_W['account']['level'] != 4) {
    $from_user = $cookie['openid'];
}
$bpreply = pdo_fetch("SELECT status,bp_mesages_num FROM " . tablename('haoman_dpm_bpreply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$num = $bpreply['bp_mesages_num'];


$isAdmin =0;//1表示是管理员，0表示不是
if($uid==$from_user){
    $admin = pdo_fetch("select id from " . tablename('haoman_dpm_bpadmin') . "  where admin_openid=:admin_openid and status=0 and rid=:rid ", array(':admin_openid' => $from_user,':rid'=>$rid));
    if($admin){
        $isAdmin =1;//1表示是管理员，0表示不是
    }
}
$limit = 20;
if($num>0){
    if($limit>$num){
        $limit =$num;
    }

}else{
    if($limit>20){
        $limit =20;
    }
}
$list = pdo_fetchall("SELECT * FROM " . tablename('haoman_dpm_messages') . " WHERE rid = :rid and uniacid = :uniacid and status = 1 and is_back !=1 and is_xy !=1  and id < :id ORDER BY id desc limit ".$limit,array(':rid'=>$rid,':uniacid'=>$_W['uniacid'],':id'=>$minid));

$list = array_reverse($list);
$aa='';
foreach($list as &$v){
    $times = date("m-d H:i", $v['createtime']) ;
//            $v['createtime'] = date("m-d H:i", $v['createtime']) ;
    $v['wordimg'] = tomedia($v['wordimg']);
    if(empty($v['avatar'])){
        $v['avatar']="../addons/haoman_dpm/img9/ava_default.jpg";
    }
}
unset($v);
if($list){
    $result = array(
        'time' => $times,
        'uid' => $uid,
        'isMaster' => $isAdmin,
        'data' => $list,
    );
}else{
    $result = array(
        'time' => 0,
        'uid' => 0,
        'isMaster' => 0,
        'data' => 0,
    );
}

$this->message($result);