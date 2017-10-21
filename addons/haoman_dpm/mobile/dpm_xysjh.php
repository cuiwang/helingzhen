<?php
global $_GPC, $_W;
$uniacid = $_W['uniacid'];
$rid = intval($_GPC['rid']);
//$themes = $_GPC['themes'];
$reply = pdo_fetch("SELECT * FROM " . tablename('haoman_dpm_reply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));

$yyyreply = pdo_fetch("SELECT isyyy FROM " . tablename('haoman_dpm_yyyreply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$bpreply = pdo_fetch("SELECT * FROM " . tablename('haoman_dpm_bpreply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$xysreply = pdo_fetch("SELECT isxys,is_pair,is_turntable,turntable_music,turntable_bg,turntable_banner FROM " . tablename('haoman_dpm_xysreply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$xyhreply = pdo_fetch("SELECT is_xysjh,is_xyh,xysjh_type,xysjh_bg,xysjh_banner,xysjh_music,xysjh_lottery FROM " . tablename('haoman_dpm_xyhreply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$video = pdo_fetch("SELECT vodio_bg16 FROM " . tablename('haoman_dpm_mp4') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
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

if($xyhreply['is_xysjh']==1){
    message('未开启抽奖功能，请先后台开启！', '', 'error');
}

load()->model('reply');

$mobile = pdo_fetchall("SELECT mobile FROM " . tablename('haoman_dpm_fans') . " WHERE rid = :rid and uniacid = :uniacid and mobile!='' and is_back !=1 ORDER BY id DESC",array(':rid'=>$rid,':uniacid'=>$uniacid));

foreach($mobile as $k=>$v){
    $newmobile[$k] = $v['mobile'];
}

$newmobile =array_values(array_unique($newmobile));


$otteryNum = explode(',',$xyhreply['xysjh_lottery']);//内定

$res = pdo_fetchall("SELECT `number` FROM " . tablename('haoman_dpm_xyhm') . " WHERE rid = :rid and uniacid = :uniacid and turntable = :turntable ORDER BY id DESC",array(':rid'=>$rid,':uniacid'=>$uniacid,':turntable'=>2));
if($res){
    foreach($res as $k=>$v){
        $ckres .= $v['number'];
    }
    $ckres = rtrim($ckres, ",");
    $newckres = explode(',',$ckres);
    $num = count($otteryNum);
    for($i=0;$i<=$num;$i++){
        if(in_array($otteryNum[$i],$newckres)){

            unset($otteryNum[$i]);
        }

    }

    $num2 = count($newmobile);

    for($i=0;$i<=$num2;$i++){
        if(in_array($newmobile[$i],$newckres)){

            unset($newmobile[$i]);
        }

    }
    $newmobile =array_values($newmobile);

    $otteryNum=array_values($otteryNum);
}


if($xyhreply['xysjh_type']==0){
    $number_show =1;
}else{
    $number_show =0;
}
//if($xyhreply['xysjh_type']==0){
//    foreach($mobile as $k=>$v){
//        $newmobile[$k] = substr_replace($v['mobile'],'****',3,4);
//    }
//
////    $newmobile[] =array_flip(array_flip($newmobile));
////print_r($newmobile);
//    $otteryNum = explode(',',$xyhreply['xysjh_lottery']);//内定
//
//    for($i=0;$i<count($otteryNum);$i++){
//        $otteryNum[$i] = substr_replace($otteryNum[$i],'****',3,4);
//    }
//
//}else{
//    foreach($mobile as $k=>$v){
//        $newmobile[$k] = $v['mobile'];
//    }
//
//    $otteryNum = explode(',',$xyhreply['xysjh_lottery']);//内定
//
//}



//$newmobile = $this->unique_arr($newmobile,true);

if(empty($xyhreply['xysjh_bg'])){
    $bg = "../addons/haoman_base/dpm/bg2.jpg";
}else{
    $bg = tomedia($xyhreply['xysjh_bg']);
}
$music = tomedia($xyhreply['xysjh_music']);

include $this->template('dpm_index16');