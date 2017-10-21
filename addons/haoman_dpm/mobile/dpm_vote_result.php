<?php
global $_GPC, $_W;
$uniacid = $_W['uniacid'];
$rid = intval($_GPC['rid']);
$themes = $_GPC['themes'];
$reply = pdo_fetch("SELECT * FROM " . tablename('haoman_dpm_reply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$yyyreply = pdo_fetch("SELECT isyyy FROM " . tablename('haoman_dpm_yyyreply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$bpreply = pdo_fetch("SELECT * FROM " . tablename('haoman_dpm_bpreply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$xysreply = pdo_fetch("SELECT isxys,is_pair,pair_bg,pair_banner,pair_backcolor,pair_music,is_turntable FROM " . tablename('haoman_dpm_xysreply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$xyhreply = pdo_fetch("SELECT is_xysjh,is_xyh FROM " . tablename('haoman_dpm_xyhreply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$video = pdo_fetch("SELECT vodio_bg12 FROM " . tablename('haoman_dpm_mp4') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
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

$toupiao = pdo_fetchall("select * from " . tablename('haoman_dpm_newvote') . " where rid = '" . $rid . "' and uniacid = '" . $uniacid . "' order by vote_pid desc");

foreach ($toupiao as &$row){
    $row['abc'] = pdo_fetch("select max(get_num) as get_num,`number`,sum(get_num) as totalnum,`name`,avatar from " . tablename('haoman_dpm_toupiao') . " where rid = '" . $rid . "' and uniacid = '" . $uniacid . "' and status=0 and vote_id='".$row['id']."' order by pid desc");
    $row['get_num'] = $row['abc']['get_num'];
    $row['number'] = $row['abc']['number'];
}
unset($row);

//$toupiao = pdo_fetchall("select * from " . tablename('haoman_dpm_toupiao') . " where rid = '" . $rid . "' and uniacid = '" . $uniacid . "' and status=0 and vote_id=0 order by pid desc");

//foreach ($toupiao as $value) {
//    $totalnum += $value['get_num'];
//}
//if($totalnum==0){
//    $totalnum =0.01;
//}
load()->model('reply');
$keywords = reply_single($rid);


if(empty($xysreply['pair_bg'])){
    $bg = "../addons/haoman_base/dpm/bg2.jpg";
}else{
    $bg = tomedia($xysreply['pair_bg']);
}
$music = tomedia($xysreply['pair_music']);

    include $this->template('dpm_index122');