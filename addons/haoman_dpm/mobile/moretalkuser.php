<?php
global $_GPC, $_W;
$rid = intval($_GPC['rid']);


$sex = $_GPC['sex'];

$uniacid = $_W['uniacid'];

$page =intval($_GPC['page']);

$index = 100;
$from_user = $_W['openid'];


load()->model('account');
$_W['account'] = account_fetch($_W['acid']);
$cookieid = '__cookie_haoman_dpm_201606186_' . $rid;
$cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
if ($_W['account']['level'] != 4) {
    $from_user = $cookie['openid'];
}


$start = ($page+1) * $index;
$limit .= " LIMIT {$start},{$index}";
$params = array(':rid' => $rid,':uniacid' => $_W['uniacid'],':from_user'=>$from_user);


$all_fanss = pdo_fetchall("select id,avatar,nickname,from_user,is_online,sex,createtime,last_onlinetime from " . tablename('haoman_dpm_fans') . " where rid = :rid and uniacid=:uniacid and from_user!=:from_user order by last_onlinetime desc,createtime desc ".$limit, $params);


foreach ($all_fanss as $k=>$v){

    if($v['sex']==1){
        $all_man_fans[$k]=$v;
    }else{
        $all_woman_fans[$k]=$v;
    }

}

if($sex==1){
    $all_fans = $all_man_fans;
}elseif ($sex==2){
    $all_fans = $all_woman_fans;
}else{
    $all_fans = $all_fanss;
}

foreach ($all_fans as $v){
    $then_time = empty($v['last_onlinetime'])?$v['createtime']:$v['last_onlinetime'];
    $v['createtime'] = $this->time_tran($then_time);
    if(empty($v['avatar'])){
        $v['avatar']="../addons/haoman_dpm/img9/ava_default.jpg";
    }else{
        $v['avatar'] = tomedia($v['avatar']);
    }
    if($v['sex']==1){
        $aa .='<li class="man" uid="'.$v['from_user'].'">';
    }else{
        $aa .='<li class="woman" uid="'.$v['from_user'].'">';
    }
    $aa .='<div class="avatar"  style="background-image: url('.$v['avatar'].')">';
    $aa .='</div>';
    $aa .='<div class="userinfo">';
    $aa .=' <div class="nickname">'.$v['nickname'].'</div>';
    $aa .=' <div class="tags">';
    if($v['sex']==1){
        $aa .=' <span class="gender">帅哥</span>';
    }else{
        $aa .=' <span class="gender">美女</span>';
    }

    $aa .='  </div>';
    $aa .='  </div>';
    $aa .=' <div class="timestr">'.$v['createtime'].'</div>';
    $aa .=' </li>';
    $aa .=' <ol></ol>';

}

if($all_fans){
    $result =$aa;

}else{
    $result ='';

}

$this->message($result);