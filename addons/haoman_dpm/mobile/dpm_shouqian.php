<?php

global $_GPC, $_W;
$uniacid = $_W['uniacid'];
$rid = intval($_GPC['rid']);
$themes = $_GPC['themes'];
$reply = pdo_fetch("SELECT loginpassword FROM " . tablename('haoman_dpm_reply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$cjxreply = pdo_fetch("SELECT * FROM " . tablename('haoman_dpm_cjxreply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$yyyreply = pdo_fetch("SELECT isyyy FROM " . tablename('haoman_dpm_yyyreply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$bpreply = pdo_fetch("SELECT * FROM " . tablename('haoman_dpm_bpreply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$xysreply = pdo_fetch("SELECT isxys,is_pair,is_turntable FROM " . tablename('haoman_dpm_xysreply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$xyhreply = pdo_fetch("SELECT is_xysjh,is_xyh FROM " . tablename('haoman_dpm_xyhreply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$shouqian = pdo_fetch("SELECT * FROM " . tablename('haoman_dpm_shouqianBase') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));


//检查登陆状态
if(!empty($reply['loginpassword'])){
    $cookieid = '__cookie_haoman_dpmweb_201606186_' . $rid;
    $cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
    if($cookie['loginpassword'] != $reply['loginpassword']){
        message('登陆密码错误或已超时，请重新输入',$this->createMobileUrl("login",array('id'=>$rid)),'error');
    }
}

//检查登陆状态

if($shouqian['status']==0){
	message('未开启手签功能，请先后台开启！', '', 'error');
}


load()->model('reply');
$keywords = reply_single($rid);

if($shouqian['qrcode'] == 0){
    $imgName = "haomandpmsq".$_W['uniacid'].$rid;
    $linkUrl = $_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&m=haoman_dpm&do=mob_shouqian&id=".$rid;
    $imgUrl = "../addons/haoman_dpm/qrcode/".$imgName.".png";
    load()->func('file');
    mkdirs(ROOT_PATH . '/qrcode');
    $dir = $imgUrl;
    $flag = file_exists($dir);
    if($flag == false){
        //生成二维码图片
        $errorCorrectionLevel = "L";
        $matrixPointSize = "4";
        QRcode::png($linkUrl,$imgUrl,$errorCorrectionLevel,$matrixPointSize);
        //生成二维码图片
    } 
}


if(empty($shouqian['shouqianBg'])){
	$bg = "../addons/haoman_dpm/static/shouqian/shouqianbg.jpg";
}else{
	$bg = tomedia($shouqian['shouqianBg']);
}
$music = tomedia($shouqian['shouqianMusic']);


include $this->template('dpm_shouqian');
