<?php
global $_GPC, $_W;
$uniacid = $_W['uniacid'];
$rid = intval($_GPC['rid']);
//$themes = $_GPC['themes'];
$reply = pdo_fetch("SELECT * FROM " . tablename('haoman_dpm_reply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));

$yyyreply = pdo_fetch("SELECT isyyy FROM " . tablename('haoman_dpm_yyyreply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$bpreply = pdo_fetch("SELECT * FROM " . tablename('haoman_dpm_bpreply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$xysreply = pdo_fetch("SELECT isxys,is_pair,is_turntable,turntable_music,turntable_bg,turntable_banner FROM " . tablename('haoman_dpm_xysreply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$xyhreply = pdo_fetch("SELECT is_xysjh,is_xyh,xyh_bg,xyh_banner,xyh_music,xyh_lottery FROM " . tablename('haoman_dpm_xyhreply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$video = pdo_fetch("SELECT vodio_bg15 FROM " . tablename('haoman_dpm_mp4') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
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

if($xyhreply['is_xyh']==1){
    message('未开启抽奖功能，请先后台开启！', '', 'error');
}



//load()->model('reply');
//
//$keywords = reply_single($rid);
//


$lotteryNum = explode(',',$xyhreply['xyh_lottery']);//内定

$res = pdo_fetchall("SELECT `number` FROM " . tablename('haoman_dpm_xyhm') . " WHERE rid = :rid and uniacid = :uniacid and turntable = :turntable ORDER BY id DESC",array(':rid'=>$rid,':uniacid'=>$uniacid,':turntable'=>1));
if($res){
    foreach($res as $k=>$v){
        $ckres .= $v['number'];
    }
    $ckres = rtrim($ckres, ",");
    $newckres = explode(',',$ckres);

    $newckres =array_values(array_unique($newckres));
    $num =count($lotteryNum);
    for($i=0;$i<=$num;$i++){

        if(in_array($lotteryNum[$i],$newckres)){

            unset($lotteryNum[$i]);
        }

    }
}


$lotteryNum=array_values($lotteryNum);

if(empty($xyhreply['xyh_bg'])){
    $bg = "../addons/haoman_base/dpm/bg2.jpg";
}else{
    $bg = tomedia($xyhreply['xyh_bg']);
}
$music = tomedia($xyhreply['xyh_music']);

include $this->template('dpm_index15');