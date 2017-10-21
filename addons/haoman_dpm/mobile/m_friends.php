<?php
global $_GPC, $_W;
$rid = intval($_GPC['rid']);
$uniacid = $_W['uniacid'];


$from_user = $_W['openid'];
//$from_user = 'oQAFAwCHF9BF8rzemKZ0I4a8STsY';
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



if(empty($fans)){
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: " . $this->createMobileUrl('information', array('id' => $rid,'from_user'=>$from_user)) . "");
    exit();
}
$all_fanss = pdo_fetchall("select id,avatar,nickname,from_user,is_online,sex,createtime,last_onlinetime from " . tablename('haoman_dpm_fans') . " where rid = :rid and uniacid=:uniacid and from_user!=:from_user order by last_onlinetime desc,createtime desc limit 60", $params);

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


//
//
//
//if(empty($fans)||empty($for_fans)){
//	message('未获取到信息，请重新进入！');
//	exit();
//}else{
//	if($fans['from_user']==$for_fans['from_user']){
//		message('自己不能跟自己聊天！');
//		exit();
//	}
//
//	$now_time =time();
//	$is_online = pdo_fetch("select id,createtime,from_user from " . tablename('haoman_dpm_on_line') . " where uniacid=:uniacid and rid =:rid and from_user =:from_user and to_from_user=:to_from_user ", array(':uniacid'=>$uniacid,':rid'=>$rid,':from_user' => $from_user,':to_from_user'=>$uid));
//    if(empty($is_online)){
//		$insert = array(
//			'uniacid' => $uniacid,
//			'rid' => $rid,
//			'from_user' => $from_user,
//			'to_from_user' => $uid,
//			'createtime' => $now_time,
//		);
//		$temp = pdo_insert('haoman_dpm_on_line',$insert);
//	}else{
//		pdo_update('haoman_dpm_on_line', array('createtime' => $now_time), array('id' => $is_online['id']));
//	}
//}
//   if($_W['account']['level'] == 4){
//	   $follow = pdo_fetchcolumn("select follow from " . tablename('mc_mapping_fans') . " where openid=:openid and uniacid=:uniacid order by `fanid` desc", array(":openid" => $uid, ":uniacid" => $uniacid));
//	   if ($follow == 0) {
//		   //聊天对象未关注公众号
//		   $is_follow=0;
//	   }else{
//		   $is_follow=1;
//	   }
//   }
//
//$fashb = pdo_fetch( " SELECT top_bg FROM ".tablename('haoman_dpm_hb_setting')." WHERE rid='".$rid."' " );
//
//$bp = pdo_fetch("select isbp,isds,bp_pay,bp_pay2,bp_listword,bp_keyword,ishb,isvo,isbb,is_mf,is_gift from " . tablename('haoman_dpm_bpreply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
//
//if($bp['is_mf']!=1){
//	message('私聊功能已被关闭！');
//}
//	//检测是否关注
//	if (!empty($reply['share_url'])) {
//		//查询是否为关注用户
//		$fansID = $_W['member']['uid'];
//		$follow = pdo_fetchcolumn("select follow from " . tablename('mc_mapping_fans') . " where uid=:uid and uniacid=:uniacid order by `fanid` desc", array(":uid" => $fansID, ":uniacid" => $uniacid));
//
//		if ($follow == 0) {
//			header("HTTP/1.1 301 Moved Permanently");
//			header("Location: " . $reply['share_url'] . "");
//			exit();
//		}
//
//	}
//
////if(empty($from_user)){
////	$from_user ='oJfLAvhM011uRt7SKeI-p1ad0s0M';
////}
//
//if($bp['bp_keyword']){
//	$keywords= explode(',',$bp['bp_keyword']);
//	$i=1;
//
//	foreach($keywords as $k=>$v){
//		$keyword[$i]=$v;
//		$i++;
//	}
//
//	$keyword = json_encode($keyword);
//}else{
//	$keyword ="null";
//}
//
//if($bp['bp_listword']){
//	$bp_listwords = explode(',',$bp['bp_listword']);
//	$i=1;
//
//	foreach($bp_listwords as $k=>$v){
//		$bp_listword[$i]=$v;
//		$i++;
//	}
//	$bp_listword = json_encode($bp_listword);
//}else{
//	$bp_listword = "null";
//}
////
////
////
////
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

    include $this->template('mob_m_friends');
