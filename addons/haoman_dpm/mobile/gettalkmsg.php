<?php
global $_GPC, $_W;
$rid = intval($_GPC['rid']);
$uid = $_GPC['from_user'];//$from_user
$sid = $_GPC['sid'];//$for_from_user
$from_user = $_W['openid'];
$maxid = $_GPC['maxid'];
$lt = $_GPC['lasttime'];
$uniacid = $_W['uniacid'];


load()->model('account');
$_W['account'] = account_fetch($_W['acid']);
$cookieid = '__cookie_haoman_dpm_201606186_' . $rid;
$cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
if ($_W['account']['level'] != 4) {
    $from_user = $cookie['openid'];
}
//$sid = 'oQAFAwCHF9BF8rzemKZ0I4a8STsY';
//$uid = 'oQAFAwCS19dHrsZhSd4h0uRdEKUM';
//$from_user='oQAFAwCS19dHrsZhSd4h0uRdEKUM';
if($uid==$from_user){

}

$fans = pdo_fetch("select id,from_user,is_online from " . tablename('haoman_dpm_fans') . " where rid =:rid and from_user = :from_user ", array(':rid'=>$rid,':from_user' => $uid));
$fans_id=$fans['id'];
$for_fans = pdo_fetch("select id,from_user,is_online from " . tablename('haoman_dpm_fans') . " where rid =:rid and from_user = :from_user ", array(':rid'=>$rid,':from_user' => $sid));

$list = pdo_fetchall("SELECT * FROM " . tablename('haoman_dpm_private_chat') . " WHERE rid = :rid and uniacid = :uniacid and from_user in(:from_user,:for_from_user)  and for_from_user in(:from_user,:for_from_user) and find_in_set('$fans_id',all_fansid)and id > :id and createtime > :createtime ORDER BY id desc limit 20",array(':rid'=>$rid,':uniacid'=>$uniacid,':id'=>$maxid,':createtime'=>$lt,':from_user'=>$from_user,':for_from_user'=>$sid));

$maxid = $list[0]['id'];
$list = array_reverse($list);

$aa='';
//$maxid = $list[0]['id'];
foreach($list as $k=>$v){
    $v['wordimg'] = tomedia($v['wordimg']);

    $v['createtime2'] = $v['createtime'] ;
    $v['createtime'] = date("m-d H:i:s", $v['createtime']) ;


    if(empty($v['avatar'])){
        $v['avatar']="../addons/haoman_dpm/img9/ava_default.jpg";
    }else{
        $v['avatar2'] = $v['avatar'];
        $v['avatar'] = tomedia($v['avatar']);


    }

    $aa .='<div class="timestr" val="'.$v['createtime2'].'">'.$v['createtime'].'</div>';
    if($v['from_user']==$uid){
        $aa .='<div class="msg_box mine" id="msg_box_'.$v['id'].'" mId="'.$v['id'].'">';
    }else{
        $aa .='<div class="msg_box" id="msg_box_'.$v['id'].'" mId="'.$v['id'].'">';
    }

    $aa .='<div class="avatar" style="background-image: url('.$v['avatar'].')" avatar="'.$v['avatar2'].'" nickname="'.$v['nickname'].'" uid="'.$v['from_user'].'"></div>';


        $aa .='<div class="content">';
        $aa .='<span class="say_point"></span>'.$v['word'].'';
        if($v['wordimg']){
            $aa .='<img src="'.$v['wordimg'].'" _src="'.$v['wordimg'].'" class="msg_ctx_image"/>';
        }




    $aa .='</div>';


    $aa .='</div>';


}
if($list){

    $result = array(
        'maxid' => $maxid,
        'data' => $aa,
        'count' => $maxid
    );
}else{
    $result = array(
        'maxid' => $maxid,

        'data' => $aa,

    );
}


$this->message($result);