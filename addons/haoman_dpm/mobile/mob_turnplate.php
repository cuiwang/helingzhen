<?php
global $_GPC, $_W;
$rid = intval($_GPC['id']);
$uniacid = $_W['uniacid'];


$from_user = $_W['openid'];
//$from_user = 'oQAFAwCHF9BF8rzemKZ0I4a8STsY';
//$from_user='oQAFAwCS19dHrsZhSd4h0uRdEKUM';
//$user_agent = $_SERVER['HTTP_USER_AGENT'];
//if (strpos($user_agent, 'MicroMessenger') === false) {
//
//	header("HTTP/1.1 301 Moved Permanently");
//	header("Location: {$this->createMobileUrl('other',array('type'=>1,'id'=>$rid))}");
//	exit();
//}


////网页授权借用开始
//
//load()->model('account');
//$_W['account'] = account_fetch($_W['acid']);
//$cookieid = '__cookie_haoman_dpm_201606186_' . $rid;
//$cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
//if ($_W['account']['level'] != 4) {
//	$from_user = $cookie['openid'];
//	$avatar = $cookie['avatar'];
//	$nickname = $cookie['nickname'];
//}else{
//	$from_user = $_W['fans']['from_user'];
//	$avatar = $_W['fans']['tag']['avatar'];
//	$nickname = $_W['fans']['nickname'];
//}
//
//$code = $_GPC['code'];
//$urltype = '';
//if (empty($from_user) || empty($avatar) || empty($nickname)) {
//	if (!is_array($cookie) || !isset($cookie['avatar']) || !isset($cookie['openid']) || !isset($cookie['nickname'])) {
//		$userinfo = $this->get_UserInfo($rid, $code, $urltype);
//		$nickname = $userinfo['nickname'];
//		$avatar = $userinfo['headimgurl'];
//		$from_user = $userinfo['openid'];
//	} else {
//		$avatar = $cookie['avatar'];
//		$nickname = $cookie['nickname'];
//		$from_user = $cookie['openid'];
//	}
//}
//
////网页授权借用结束
//
//
//$params = array(':rid' => $rid,':uniacid' => $_W['uniacid'],':from_user'=>$from_user);
//
$reply = pdo_fetch("select * from " . tablename('haoman_dpm_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
$yyy = pdo_fetch("select isyyy from " . tablename('haoman_dpm_yyyreply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
$vote = pdo_fetch("select vote_status from " . tablename('haoman_dpm_newvote_set') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
$photo = pdo_fetch("select is_phone from " . tablename('haoman_dpm_photo_setting') . " where rid = :rid order by `id` desc", array(':rid' => $rid));

$punishment = pdo_fetch("select * from " . tablename('haoman_dpm_punishment') . " where rid = :rid and uniacid=:uniacid ", array(':rid'=>$rid,':uniacid'=>$_W['uniacid']));

if(empty($punishment)||$punishment['is_punishment']!=1||empty($punishment['punishment_content'])||empty($reply)){
    message('惩罚大转盘还未开启！');
}

$punishment_bg = empty($punishment['punishment_bg'])?'../addons/haoman_dpm/img12/mob_turnplate.jpg':tomedia($punishment['punishment_bg']);
$punishment_img = empty($punishment['punishment_img'])?'../addons/haoman_dpm/img12/turnplate-bg2.png':tomedia($punishment['punishment_img']);
$punishment_pointer = empty($punishment['punishment_pointer'])?'../addons/haoman_dpm/img12/turnplate-pointer2.png':tomedia($punishment['punishment_pointer']);

$music = tomedia($punishment['punishment_music']);

if($punishment['punishment_content']){
    $keywords= explode(',',$punishment['punishment_content']);
    $i=1;

    foreach($keywords as $k=>$v){
        $keyword[]=$v;
        if($k%2==0){
            $punishment_color[]='#FFFFFF';
        }else{
            $punishment_color[]='#FFF4D6';
        }

    }

    $keyword = json_encode($keyword);
    $punishment_color = json_encode($punishment_color);
}else{
    $keyword ="null";
}


        //分享信息
        $sharelink = $_W['siteroot'] . 'app/' . $this->createMobileUrl('share', array('rid' => $rid, 'from_user' => $page_from_user));
        $sharetitle = empty($reply['share_title']) ? '一起来玩惩罚大转盘吧!' : $reply['share_title'];
        $sharedesc = empty($reply['share_desc']) ? '亲，一起来玩惩罚大转盘吧！！' : str_replace("\r\n", " ", $reply['share_desc']);
        if (!empty($reply['share_imgurl'])) {
            $shareimg = toimage($reply['share_imgurl']);
        } else {
            $shareimg = toimage($reply['picture']);
        }

        //if(empty($reply['mobpicurl'])){
        //	$bg = "../addons/haoman_dpm/mobimg/bg.jpg";
        //}else{
        //	$bg = tomedia($reply['mobpicurl']);
        //}

        $jssdk = new JSSDK();
        $package = $jssdk->GetSignPackage();

    include $this->template('mob_turnplate');
