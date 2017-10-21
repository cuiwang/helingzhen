<?php
global $_GPC, $_W;
$uniacid = $_W['uniacid'];
$rid = intval($_GPC['rid']);
$reply = pdo_fetch("SELECT * FROM " . tablename('haoman_dpm_reply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$yyyreply = pdo_fetch("SELECT isyyy FROM " . tablename('haoman_dpm_yyyreply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$bpreply = pdo_fetch("SELECT * FROM " . tablename('haoman_dpm_bpreply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$xysreply = pdo_fetch("SELECT isxys,is_pair,is_turntable FROM " . tablename('haoman_dpm_xysreply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$xyhreply = pdo_fetch("SELECT is_xysjh,is_xyh FROM " . tablename('haoman_dpm_xyhreply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$shouqian = pdo_fetch("SELECT status,pm_status FROM " . tablename('haoman_dpm_shouqianBase') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));

$video = pdo_fetch("SELECT vodio_bg6 FROM " . tablename('haoman_dpm_mp4') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));

//检查登陆状态
if(!empty($reply['loginpassword'])){
    $cookieid = '__cookie_haoman_dpmweb_201606186_' . $rid;
    $cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
    if($cookie['loginpassword'] != $reply['loginpassword']){
        message('登陆密码错误或已超时，请重新输入',$this->createMobileUrl("login",array('id'=>$rid)),'error');
    }
}

//检查登陆状态

if($reply['isqhb']==1){
	message('未开启抢红包功能，请先后台开启！', '', 'error');
}

if($reply['hbpici']==0){
	pdo_update('haoman_dpm_reply', array('hbpici' => 1), array('id' => $reply['id']));
}

pdo_update('haoman_dpm_reply', array('isqhbshow' => 0), array('id' => $reply['id']));

load()->model('reply');
$keywords = reply_single($rid);

$totaldata = pdo_fetchcolumn("SELECT count(*) FROM " . tablename('haoman_dpm_fans') . " WHERE uniacid = :uniacid AND rid = :rid and isbaoming=0", array(':uniacid' => $uniacid,':rid'=>$rid));


$list = pdo_fetchall("SELECT * FROM " . tablename('haoman_dpm_prize') . " WHERE rid = :rid and uniacid = :uniacid and turntable = :turntable ORDER BY sort DESC",array(':rid'=>$rid,':uniacid'=>$uniacid,':turntable'=>1));

$turntable = 2;
$hbpici = $reply['hbpici'];
$awards = pdo_fetch("SELECT * FROM " . tablename('haoman_dpm_award') . " WHERE rid = :rid and uniacid = :uniacid and turntable = :turntable AND hbpici = :hbpici ORDER BY id DESC limit 1",array(':rid'=>$rid,':uniacid'=>$uniacid,':turntable'=>$turntable,':hbpici'=>$hbpici));
// print_r($awards);
// exit;

if($awards == false){
	$text = '开始抢红包';
	$gameover = 0;
}else{
	$text = '活动已结束';
	$gameover = 1;
}

if(empty($reply['adpic'])){
	$bg = "../addons/haoman_base/dpm/bg13.jpg";
}else{
	$bg = tomedia($reply['adpic']);
}
$music2 = tomedia($reply['ten_time']);
$music = tomedia($reply['adpicurl']);

if(empty($reply['end_hour'])){
	$end_hour = 10;
}else{
	$end_hour = $reply['end_hour'];
}

if(empty($reply['start_hour'])){
	$start_hour = 500;
}else{
	$start_hour = $reply['start_hour'];
}

include $this->template('dpm_index');