<?php
global $_GPC, $_W;
$uniacid = $_W['uniacid'];
$rid = intval($_GPC['rid']);
$isqdthemes = $_GPC['isqdthemes'];
$reply = pdo_fetch("SELECT k_templateid,id,rid,uniacid,isqd,loginpassword,qdshow,isqdthemes,panzi,qjbpic,qdthemes,title,timenum,up_qrcode,3dlogo,3dlogo1,3dlogo2,3dlogo3,isyyy,is3ddaojishi,daojishinum,daojishimusic,qdgap FROM " . tablename('haoman_dpm_reply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$xysreply = pdo_fetch("SELECT isxys,is_pair,is_turntable FROM " . tablename('haoman_dpm_xysreply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$yyyreply = pdo_fetch("SELECT isyyy FROM " . tablename('haoman_dpm_yyyreply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$bpreply = pdo_fetch("SELECT isbp FROM " . tablename('haoman_dpm_bpreply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$xyhreply = pdo_fetch("SELECT is_xysjh,is_xyh FROM " . tablename('haoman_dpm_xyhreply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$video = pdo_fetch("SELECT vodio_bg4 FROM " . tablename('haoman_dpm_mp4') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
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
if($reply['isqd']==1){
message('未开启3d签到墙，请先后台开启！', '', 'error');
}

load()->model('reply');
$keywords = reply_single($rid);

$totaldata = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('haoman_dpm_fans') . " WHERE uniacid = :uniacid AND rid = :rid", array(':uniacid' => $uniacid,':rid'=>$rid));


if($isqdthemes == 'torus'){
$reply['isqdthemes']=4;
}elseif($isqdthemes == 'sphere'){
$reply['isqdthemes']=1;
}elseif($isqdthemes == 'helix'){
$reply['isqdthemes']=2;
}elseif($isqdthemes == 'grid'){
$reply['isqdthemes']=3;
}else{
$reply['isqdthemes']=0;
}

if(empty($reply['panzi'])){
$bg = "../addons/haoman_base/dpm/bg2.jpg";
}else{
$bg = tomedia($reply['panzi']);
}
$music = tomedia($reply['qjbpic']);

if(!empty($reply['3dlogo1'])){
$n3dlogo1 = tomedia($reply['3dlogo1']);
$arr1 = getimagesize($n3dlogo1);
$logo1 = "data:{$arr1['mime']};base64," . base64_encode(file_get_contents($n3dlogo1));
}else{
$logo1 = "";
}

if(!empty($reply['3dlogo2'])){
$n3dlogo2 = tomedia($reply['3dlogo2']);
$arr2 = getimagesize($n3dlogo2);
$logo2 = "data:{$arr2['mime']};base64," . base64_encode(file_get_contents($n3dlogo2));
}else{
$logo2 = "";
}

if(!empty($reply['3dlogo3'])){
$n3dlogo3 = tomedia($reply['3dlogo3']);
$arr3 = getimagesize($n3dlogo3);
$logo3 = "data:{$arr3['mime']};base64," . base64_encode(file_get_contents($n3dlogo3));
}else{
$logo3 = "";
}


if($reply['isqd']==0 || $reply['isqdthemes']==0){

if(!empty($reply['3dlogo1']) && !empty($reply['3dlogo2']) && !empty($reply['3dlogo3'])){
    $logoList = "#icon ".$logo1."|#icon ".$logo2."|#icon ".$logo3."|";
}elseif(!empty($reply['3dlogo1']) && !empty($reply['3dlogo2'])){
    $logoList = "#icon ".$logo1."|#icon ".$logo2."|";
}elseif(!empty($reply['3dlogo1']) && !empty($reply['3dlogo3'])){
    $logoList = "#icon ".$logo1."|#icon ".$logo3."|";
}elseif(!empty($reply['3dlogo2']) && !empty($reply['3dlogo3'])){
    $logoList = "#icon ".$logo2."|#icon ".$logo3."|";
}elseif(!empty($reply['3dlogo1']) && empty($reply['3dlogo2']) && empty($reply['3dlogo3'])){
    $logoList = "#icon ".$logo1."|";
}elseif(!empty($reply['3dlogo2']) && empty($reply['3dlogo1']) && empty($reply['3dlogo3'])){
    $logoList = "#icon ".$logo2."|";
}elseif(!empty($reply['3dlogo3']) && empty($reply['3dlogo2']) && empty($reply['3dlogo1'])){
    $logoList = "#icon ".$logo2."|";
}else{
    $logoList = "";
}
}

if($reply['isqd']==0){
$signwall_show_str = $reply['3dlogo']."|#gameOver|".$logoList."#gameOver|#grid|#helix|#torus|#sphere";
}elseif($reply['isqd']==3){
$signwall_show_str = "#grid|#helix|#torus|#sphere";
}else{
if($reply['isqdthemes']==1){
    $signwall_show_str = "#sphere";
}elseif($reply['isqdthemes']==2){
    $signwall_show_str = "#helix";
}elseif($reply['isqdthemes']==3){
    $signwall_show_str = "#grid";
}elseif($reply['isqdthemes']==4){
    $signwall_show_str = "#torus";
}else{
    $signwall_show_str = $reply['3dlogo']."|#gameOver|".$logoList."#gameOver";
}
}

if($reply['is3ddaojishi']!=0){
$daojishinum = empty($reply['daojishinum']) ? 5 : intval($reply['daojishinum']);
$signwall_show_str = "#countdown ".$daojishinum."|".$signwall_show_str;
}

$daojishimusic = tomedia($reply['daojishimusic']);



$list = pdo_fetchall("SELECT id,avatar,nickname FROM " . tablename('haoman_dpm_fans') . " WHERE rid = :rid and uniacid = :uniacid and isbaoming =0 and avatar != '' ORDER BY id DESC limit 170 ",array(':rid'=>$rid,':uniacid'=>$uniacid));

foreach ($list as $k => $v) {
$numStr = strlen(trim(strrchr(tomedia($v['avatar']), '/'),'/'));
$list[$k]['thumb_image'] = substr_replace(tomedia($v['avatar']),'64',-$numStr);
$list[$k]['thumb_image_46'] = substr_replace(tomedia($v['avatar']),'46',-$numStr);
}

$reply['qdgap'] = empty($reply['qdgap']) ? 14 : $reply['qdgap'];

include $this->template('dpm_newindex3');