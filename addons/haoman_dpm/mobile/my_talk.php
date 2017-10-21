<?php
global $_GPC, $_W;
$rid = intval($_GPC['rid']);
$uniacid = $_W['uniacid'];


$from_user = $_W['openid'];

//$from_user='oQAFAwCS19dHrsZhSd4h0uRdEKUM';
$user_agent = $_SERVER['HTTP_USER_AGENT'];
if (strpos($user_agent, 'MicroMessenger') === false) {

	header("HTTP/1.1 301 Moved Permanently");
	header("Location: {$this->createMobileUrl('other',array('type'=>1,'id'=>$rid))}");
	exit();
}


//网页授权借用开始

load()->model('account');
$_W['account'] = account_fetch($_W['acid']);
$cookieid = '__cookie_haoman_dpm_201606186_' . $rid;
$cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
if ($_W['account']['level'] != 4) {
	$from_user = $cookie['openid'];
	$avatar = $cookie['avatar'];
	$nickname = $cookie['nickname'];
}else{
	$from_user = $_W['fans']['from_user'];
	$avatar = $_W['fans']['tag']['avatar'];
	$nickname = $_W['fans']['nickname'];
}

$code = $_GPC['code'];
$urltype = '';
if (empty($from_user) || empty($avatar) || empty($nickname)) {
	if (!is_array($cookie) || !isset($cookie['avatar']) || !isset($cookie['openid']) || !isset($cookie['nickname'])) {
		$userinfo = $this->get_UserInfo($rid, $code, $urltype);
		$nickname = $userinfo['nickname'];
		$avatar = $userinfo['headimgurl'];
		$from_user = $userinfo['openid'];
	} else {
		$avatar = $cookie['avatar'];
		$nickname = $cookie['nickname'];
		$from_user = $cookie['openid'];
	}
}

//网页授权借用结束


$params = array(':rid' => $rid,':uniacid' => $_W['uniacid'],':from_user'=>$from_user);



//

$fans = pdo_fetch("select id,avatar,nickname,from_user,is_online,sex,createtime,last_onlinetime from " . tablename('haoman_dpm_fans') . " where rid = :rid and uniacid=:uniacid and from_user=:from_user", array(':rid'=>$rid,':uniacid'=>$_W['uniacid'],':from_user'=>$from_user));

$fans_id=$fans['id'];

if(empty($fans)){
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: " . $this->createMobileUrl('information', array('id' => $rid,'from_user'=>$from_user)) . "");
    exit();
}
//$all_fanss = pdo_fetchall("select for_from_user from " . tablename('haoman_dpm_private_chat') . " where rid = :rid and uniacid=:uniacid and from_user=:from_user ", $params);
//$all_fanss = pdo_fetchall("SELECT a.for_from_user,a.message_to_fansid,b.id,b.avatar,b.nickname,b.from_user,b.is_online,b.sex,b.createtime,b.last_onlinetime FROM " . tablename('haoman_dpm_private_chat') . "as a left join" . tablename('haoman_dpm_fans') . "as b on b.from_user in (a.for_from_user,a.from_user) WHERE  a.uniacid=:uniacid and a.rid=:rid and b.from_user!=:from_user  and find_in_set('$fans_id',a.all_fansid) GROUP BY a.message_to_fansid order by b.last_onlinetime desc,b.createtime desc ",$params);

//print_r($all_fanss);
//exit();

$list = pdo_fetchall("SELECT for_from_user,from_user,all_fansid,message_to_fansid FROM " . tablename('haoman_dpm_private_chat') . " WHERE rid = :rid and uniacid = :uniacid and find_in_set('$fans_id',all_fansid) GROUP BY ground_id  ORDER BY createtime desc ",array(':rid'=>$rid,':uniacid'=>$uniacid));

foreach ($list as $k=>$v){

    $all_fanss[$k]=pdo_fetch("SELECT id,avatar,nickname,from_user,is_online,sex,createtime,last_onlinetime FROM " . tablename('haoman_dpm_fans') . " WHERE rid = :rid and uniacid = :uniacid and from_user in(:from_user,:for_from_user) and from_user!=:fromuser  ORDER BY createtime desc ",array(':rid'=>$rid,':uniacid'=>$uniacid,':from_user'=>$v['from_user'],':for_from_user'=>$v['for_from_user'],'fromuser'=>$from_user));
    if($v['message_to_fansid']){
        $all_fanss[$k]['message_to_fansid']=1;

    }
}

foreach ($all_fanss as $k=>$v){

        if($v['sex']==1){
            $all_man_fans[$k]=$v;
        }else{
            $all_woman_fans[$k]=$v;
        }

}

if($_GPC['sex']==1){
    $all_fans = $all_man_fans;
}elseif ($_GPC['sex']==2){
    $all_fans = $all_woman_fans;
}else{
    $all_fans = $all_fanss;
}

foreach ($all_fans as &$v){
     $then_time = empty($v['last_onlinetime'])?$v['createtime']:$v['last_onlinetime'];
    $v['createtime'] = $this->time_tran($then_time);

}
unset($v);


////分享信息
//$sharelink = $_W['siteroot'] . 'app/' . $this->createMobileUrl('share', array('rid' => $rid, 'from_user' => $page_from_user));
//$sharetitle = empty($reply['share_title']) ? '一起来聊一聊吧!' : $reply['share_title'];
//$sharedesc = empty($reply['share_desc']) ? '亲，一起来聊一聊吧！！' : str_replace("\r\n", " ", $reply['share_desc']);
//if (!empty($reply['share_imgurl'])) {
//	$shareimg = toimage($reply['share_imgurl']);
//} else {
//	$shareimg = toimage($reply['picture']);
//}
//
////if(empty($reply['mobpicurl'])){
////	$bg = "../addons/haoman_dpm/mobimg/bg.jpg";
////}else{
////	$bg = tomedia($reply['mobpicurl']);
////}
//
//$jssdk = new JSSDK();
//$package = $jssdk->GetSignPackage();

    include $this->template('my_talk');
