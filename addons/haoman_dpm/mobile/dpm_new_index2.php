<?php
global $_GPC, $_W;
$uniacid = $_W['uniacid'];
$rid = intval($_GPC['rid']);

$reply = pdo_fetch("SELECT id,rid,uniacid,isqd,loginpassword,qdshow,isqdthemes,panzi,qjbpic,qdthemes,title,timenum,up_qrcode,3dlogo,3dlogo1,3dlogo2,3dlogo3,isyyy,is3ddaojishi,daojishinum,daojishimusic FROM " . tablename('haoman_dpm_reply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$xysreply = pdo_fetch("SELECT isxys,is_pair,is_turntable FROM " . tablename('haoman_dpm_xysreply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$yyyreply = pdo_fetch("SELECT isyyy FROM " . tablename('haoman_dpm_yyyreply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$bpreply = pdo_fetch("SELECT isbp FROM " . tablename('haoman_dpm_bpreply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$xyhreply = pdo_fetch("SELECT is_xysjh,is_xyh FROM " . tablename('haoman_dpm_xyhreply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$video = pdo_fetch("SELECT vodio_bg4 FROM " . tablename('haoman_dpm_mp4') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
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
if($shouqian['pm_status']==0){
message('未开启普通签到墙，请先后台开启！', '', 'error');
}

load()->model('reply');
$keywords = reply_single($rid);

$totaldata = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('haoman_dpm_fans') . " WHERE uniacid = :uniacid AND rid = :rid", array(':uniacid' => $uniacid,':rid'=>$rid));


$list = pdo_fetchall("SELECT id,avatar,nickname FROM " . tablename('haoman_dpm_fans') . " WHERE rid = :rid and uniacid = :uniacid and isbaoming =0 and avatar != '' ORDER BY id DESC limit 170 ",array(':rid'=>$rid,':uniacid'=>$uniacid));

$vedio_bg = tomedia($shouqian['pm_Vedio']);

if(empty($shouqian['pm_Bg'])){
    $bg = "../addons/haoman_base/dpm/bg2.jpg";
}else{
    $bg = tomedia($shouqian['pm_Bg']);
}
$music = tomedia($shouqian['pm_Music']);
//foreach ($list as $k => $v) {
//$numStr = strlen(trim(strrchr(tomedia($v['avatar']), '/'),'/'));
//$list[$k]['thumb_image'] = substr_replace(tomedia($v['avatar']),'64',-$numStr);
//$list[$k]['thumb_image_46'] = substr_replace(tomedia($v['avatar']),'46',-$numStr);
//}

include $this->template('dpm_new_index2');