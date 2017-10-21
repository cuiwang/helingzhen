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
$video = pdo_fetch("SELECT vodio_bg2,vodio_bg8 FROM " . tablename('haoman_dpm_mp4') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));

//检查登陆状态
if(!empty($reply['loginpassword'])){
    $cookieid = '__cookie_haoman_dpmweb_201606186_' . $rid;
    $cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
    if($cookie['loginpassword'] != $reply['loginpassword']){
        message('登陆密码错误或已超时，请重新输入',$this->createMobileUrl("login",array('id'=>$rid)),'error');
    }
}

//检查登陆状态
if(empty($_GPC['themes'])){
	if($reply['timenum']==1){
		message('未开启开幕墙，请先后台开启！', '', 'error');
	}
	$themes = "start";
    $vedio_bg = tomedia($video['vodio_bg2']);
	if(empty($reply['kaimubg'])){
		$bg = "../addons/haoman_base/dpm/bg2.jpg";
        
	}else{
		$bg = tomedia($reply['kaimubg']);
        
	}
	$music = tomedia($reply['timeadurl']);
}else{
	if($reply['share_type']==1){
		message('未开启闭幕墙，请先后台开启！', '', 'error');
	}
	$themes = "over";
    $vedio_bg = tomedia($video['vodio_bg8']);
	if(empty($reply['bimubg'])){
		$bg = "../addons/haoman_base/dpm/bg2.jpg";
	}else{
		$bg = tomedia($reply['bimubg']);
	}
	$music = tomedia($reply['noip_url']);
}

load()->model('reply');
$keywords = reply_single($rid);

include $this->template('dpm_index5');