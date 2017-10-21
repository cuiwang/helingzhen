<?php
global $_GPC, $_W;
$uniacid = $_W['uniacid'];
$rid = intval($_GPC['rid']);
$isbp = $_GPC['bp'];
$reply = pdo_fetch("SELECT * FROM " . tablename('haoman_dpm_reply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$yyyreply = pdo_fetch("SELECT isyyy FROM " . tablename('haoman_dpm_yyyreply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$bpreply = pdo_fetch("SELECT * FROM " . tablename('haoman_dpm_bpreply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$xysreply = pdo_fetch("SELECT isxys,is_pair,is_turntable FROM " . tablename('haoman_dpm_xysreply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$xyhreply = pdo_fetch("SELECT is_xysjh,is_xyh FROM " . tablename('haoman_dpm_xyhreply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$video = pdo_fetch("SELECT vodio_bg11 FROM " . tablename('haoman_dpm_mp4') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$shouqian = pdo_fetch("SELECT status,pm_status FROM " . tablename('haoman_dpm_shouqianBase') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$fashb = pdo_fetch( " SELECT bp_type,bp_opacity,bb_bgcoclor FROM ".tablename('haoman_dpm_hb_setting')." WHERE rid='".$rid."' " );

if($isbp!='isbp'){
    //检查登陆状态
    if(!empty($reply['loginpassword'])){
        $cookieid = '__cookie_haoman_dpmweb_201606186_' . $rid;
        $cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
        if($cookie['loginpassword'] != $reply['loginpassword']){
            message('登陆密码错误或已超时，请重新输入',$this->createMobileUrl("login",array('id'=>$rid)),'error');
        }
    }

//检查登陆状态
}


if($bpreply['isbp']==0&&$bpreply['isds']==0){
    message('未开启大屏幕功能，请先后台开启！', '', 'error');
}

load()->model('reply');
$keywords = reply_single($rid);


if($bpreply['isbd']==1){
//    $t = time();
//    $start = mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t));
//    $end = mktime(23,59,59,date("m",$t),date("d",$t),date("Y",$t));



    $params = array(':rid' => $rid, ':uniacid' => $_W['uniacid']);
    $where.=' and createtime>=:createtime1 and createtime<=:createtime2 ';

    if($bpreply['bp_maxnum']==0){
        $params[':createtime1'] = time()-12*60*60;
        $params[':createtime2'] = time();
    }else{
        $params[':createtime1'] = $bpreply['bd_starttime'];
        $params[':createtime2'] = $bpreply['bd_endtime'];
    }



    $topfans = pdo_fetchall("SELECT id,avatar,pay_total,nickname,sum(pay_total)as pt FROM " . tablename('haoman_dpm_pay_order') . " WHERE uniacid = :uniacid and rid =:rid AND status= 2 " . $where . " GROUP BY from_user  ORDER BY pt DESC limit 5", $params);

}
//print_r($topfans);
$dsDatas = pdo_fetchall("SELECT `name`,`id`,`says`,`ds_time`as`time`,`ds_pic`as`img`,`ds_vodiobg`as`src`,`sort`as`iconName` FROM " . tablename('haoman_dpm_guest') . " WHERE rid = :rid and uniacid = :uniacid and turntable =2 ORDER BY id DESC ",array(':rid'=>$rid,':uniacid'=>$uniacid));


if($dsDatas){
    foreach($dsDatas as $v){
        $res[$v['id']]=$v;
        $arr[]=$v['id'];
    }

    $dsData = json_encode($res);
    $arr = json_encode($arr);
}
else{
    $dsData='123';
    $arr='123';
}

if(empty($bpreply['bp_bg'])){
    $bg = "../addons/haoman_base/dpm/bg2.jpg";
}else{
    $bg = tomedia($bpreply['bp_bg']);
}
if(empty($bpreply['bp_voice'])){
    $bg_voice = "../addons/haoman_base/dpm/bp.mp3";
}else{
    $bg_voice = tomedia($bpreply['bp_voice']);
}
$music = tomedia($bpreply['bp_music']);
if($fashb['bp_type']==1){
    include $this->template('dpm_bp2');
}else{
    include $this->template('dpm_bp');
}
