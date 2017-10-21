<?php
global $_GPC, $_W;
$uniacid = $_W['uniacid'];
$rid = intval($_GPC['rid']);
$reply = pdo_fetch("SELECT id,rid,uniacid,loginpassword,up_qrcode,title,isyyy FROM " . tablename('haoman_dpm_reply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$yyy = pdo_fetch("select * from " . tablename('haoman_dpm_yyyreply') . " where rid = :rid order by `id` asc", array(':rid' => $rid));
$yyyreply = $yyy;
$bpreply = pdo_fetch("SELECT * FROM " . tablename('haoman_dpm_bpreply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$xyhreply = pdo_fetch("SELECT is_xysjh,is_xyh FROM " . tablename('haoman_dpm_xyhreply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$shouqian = pdo_fetch("SELECT status,pm_status FROM " . tablename('haoman_dpm_shouqianBase') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));

$xysreply = pdo_fetch("SELECT isxys,is_pair,is_turntable FROM " . tablename('haoman_dpm_xysreply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));

//检查登陆状态
if(!empty($reply['loginpassword'])){
    $cookieid = '__cookie_haoman_dpmweb_201606186_' . $rid;
    $cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
    if($cookie['loginpassword'] != $reply['loginpassword']){
        message('登陆密码错误或已超时，请重新输入',$this->createMobileUrl("login",array('id'=>$rid)),'error');
    }
}

//检查登陆状态

if($yyy['isyyy']==1){
    message('未开启摇一摇功能，请先后台开启！', '', 'error');
}

if($yyy['pici']==0){
    pdo_update('haoman_dpm_yyyreply', array('pici' => 1), array('id' => $yyy['id']));
}

if($yyy['status']==0){
    $startyyy = 0;
}else{
    $startyyy = 1;
}


load()->model('reply');
$keywords = reply_single($rid);


    $pdbg = tomedia($yyy['yyy_pdbg']);


if(empty($yyy['yyy_bg'])){
    $bg = "../addons/haoman_base/dpm/9.jpg";
}else{
    $bg = tomedia($yyy['yyy_bg']);
}
$music = tomedia($yyy['yyy_music']);

include $this->template('dpm_index10');