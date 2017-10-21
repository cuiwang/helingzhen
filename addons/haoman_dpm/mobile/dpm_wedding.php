<?php
global $_GPC, $_W;
$uniacid = $_W['uniacid'];
$rid = intval($_GPC['rid']);
$reply = pdo_fetch("SELECT * FROM " . tablename('haoman_dpm_reply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$xys = pdo_fetch("SELECT * FROM " . tablename('haoman_dpm_xysreply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$yyyreply = pdo_fetch("SELECT isyyy FROM " . tablename('haoman_dpm_yyyreply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$bpreply = pdo_fetch("SELECT * FROM " . tablename('haoman_dpm_bpreply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$xysreply = $xys;
$video = pdo_fetch("SELECT vodio_bg10 FROM " . tablename('haoman_dpm_mp4') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$shouqian = pdo_fetch("SELECT status,pm_status FROM " . tablename('haoman_dpm_shouqianBase') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));

//检查登陆状态
if(!empty($reply['loginpassword'])){
    $cookieid = '__cookie_haoman_dpmweb_201606186_' . $rid;
    $cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
    if($cookie['loginpassword'] != $reply['loginpassword']){
        message('登陆密码错误或已超时，请重新输入',$this->createMobileUrl("login",array('id'=>$rid)),'error');
    }
}

//检查登陆状态

load()->model('reply');
$keywords = reply_single($rid);

if($xys['isxys']==1){

    message('还未开启许愿树，请确认！','','error');
}

if($reply['isckmessage'] == 0){
    $isckmessage = 0;
    $totaldata = pdo_fetchcolumn("SELECT count(*) FROM " . tablename('haoman_dpm_messages') . " WHERE uniacid = :uniacid AND rid = :rid and status = 1", array(':uniacid' => $uniacid,':rid'=>$rid));
}else{
    $isckmessage = 1;
    $totaldata = pdo_fetchcolumn("SELECT count(*) FROM " . tablename('haoman_dpm_messages') . " WHERE uniacid = :uniacid AND rid = :rid", array(':uniacid' => $uniacid,':rid'=>$rid));
}
$color = empty($xys['xys_backcolor'])?"#8957a1":$xys['xys_backcolor'];
if(empty($xys['xys_bg'])){
    $bg = "../addons/haoman_dpm/static/wedding_xys/bg-2000-828.jpg";
}else{
    $bg = tomedia($xys['xys_bg']);
}
if(empty($xys['xys_mbg'])){
    $titlebg = "../addons/haoman_dpm/static/wedding_xys/title.png";
}else{
    $titlebg = tomedia($xys['xys_mbg']);
}
$music = tomedia($xys['xys_music']);
    $title = empty($xys['xys_banner']) ? "" :$xys['xys_banner'];
include $this->template('dpm_wedding');