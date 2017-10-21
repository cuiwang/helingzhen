<?php
global $_GPC, $_W;
$uniacid = $_W['uniacid'];
$rid = intval($_GPC['rid']);
$reply = pdo_fetch("SELECT id,rid,uniacid,title,loginpassword,tpbg_url,tpbg_voice,up_qrcode,toupiaotitle,isyyy,istoupiao FROM " . tablename('haoman_dpm_reply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$yyyreply = pdo_fetch("SELECT isyyy FROM " . tablename('haoman_dpm_yyyreply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$bpreply = pdo_fetch("SELECT * FROM " . tablename('haoman_dpm_bpreply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$xysreply = pdo_fetch("SELECT isxys,is_pair,is_turntable FROM " . tablename('haoman_dpm_xysreply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$xyhreply = pdo_fetch("SELECT is_xysjh,is_xyh FROM " . tablename('haoman_dpm_xyhreply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$shouqian = pdo_fetch("SELECT status,pm_status FROM " . tablename('haoman_dpm_shouqianBase') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));

$video = pdo_fetch("SELECT vodio_bg9 FROM " . tablename('haoman_dpm_mp4') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));

//检查登陆状态
if(!empty($reply['loginpassword'])){
    $cookieid = '__cookie_haoman_dpmweb_201606186_' . $rid;
    $cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
    if($cookie['loginpassword'] != $reply['loginpassword']){
        message('登陆密码错误或已超时，请重新输入',$this->createMobileUrl("login",array('id'=>$rid)),'error');
    }
}

//if($reply['istoupiao']==1){
//    message('未开启投票功能，请先后台开启！', '', 'error');
//}
//检查登陆状态

load()->model('reply');
$keywords = reply_single($rid);
$t=time();
$project = pdo_fetch("SELECT id,vote_name,status FROM " . tablename('haoman_dpm_newvote') . " WHERE uniacid=:uniacid AND rid = :rid and vote_status=1  and vote_starttime<:vote_starttime and vote_endtime >:vote_endtime order by vote_pid desc LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid,':vote_starttime'=>$t,':vote_endtime'=>$t));
if(empty($project)){
    message('未开启投票，请先后台开启!!！', '', 'error');
}else{

    $toupiao = pdo_fetchall("select `id`,`pid`,`rid`,`uniacid`,`get_num`,`number`,`name`,`avatar`,`status` from " . tablename('haoman_dpm_toupiao') . " where rid = '" . $rid . "' and uniacid = '" . $uniacid . "' and status=0 and vote_id='".$project['id']."' order by pid desc");
}

foreach ($toupiao as $value) {
    $totalnum += $value['get_num'];
}
if($totalnum==0){
    $totalnum =0.01;
}

if(empty($reply['tpbg_url'])){
    $bg = "../addons/haoman_base/dpm/bg2.jpg";
}else{
    $bg = tomedia($reply['tpbg_url']);
}
$music = tomedia($reply['tpbg_voice']);

include $this->template('dpm_newindex9');