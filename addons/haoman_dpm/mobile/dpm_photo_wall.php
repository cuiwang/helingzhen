<?php
global $_GPC, $_W;
$uniacid = $_W['uniacid'];
$rid = intval($_GPC['rid']);
//$themes = $_GPC['themes'];
$reply = pdo_fetch("SELECT * FROM " . tablename('haoman_dpm_reply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));

$photo = pdo_fetch("SELECT * FROM " . tablename('haoman_dpm_photo_setting') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));


//检查登陆状态
if(!empty($reply['loginpassword'])){
    $cookieid = '__cookie_haoman_dpmweb_201606186_' . $rid;
    $cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
    if($cookie['loginpassword'] != $reply['loginpassword']){
        message('登陆密码错误或已超时，请重新输入',$this->createMobileUrl("login",array('id'=>$rid)),'error');
    }
}

//检查登陆状态

if($photo['photo_status']==0||empty($photo)){
    message('未开启相册功能，请先后台开启！', '', 'error');
}

$t = empty($photo['changetime'])?5000:abs($photo['changetime'])*1000;

$all_photo= pdo_fetchall("SELECT * FROM " . tablename('haoman_dpm_photo_add') . " WHERE rid = :rid and uniacid = :uniacid  ORDER BY photo_pid DESC",array(':rid'=>$rid,':uniacid'=>$uniacid));


if(empty($photo['photo_bg'])){
    $bg = "../addons/haoman_base/dpm/bg2.jpg";
}else{
    $bg = tomedia($photo['photo_bg']);
}
$music = tomedia($photo['photo_music']);

include $this->template('dpm_photo_wall');