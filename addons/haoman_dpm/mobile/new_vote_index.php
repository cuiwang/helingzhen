<?php
global $_GPC, $_W;
$rid = intval($_GPC['id']);
$uniacid = $_W['uniacid'];

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

$page_from_user = base64_encode(authcode($from_user, 'ENCODE'));


if (empty($rid)) {
    message('抱歉，参数错误！', '', 'error');//调试代码
}
if (empty($from_user)) {
  message('获取不到您的OpenID,请从新进入活动页面！','', "error");
}

$fans = pdo_fetch("select * from " . tablename('haoman_dpm_fans') . " where rid = '" . $rid . "' and from_user='" . $from_user . "'");
if($fans==false){
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: " . $this->createMobileUrl('information', array('id' => $rid,'from_user'=>$page_from_user)) . "");
    exit();
}
//
//
$reply = pdo_fetch("select rules,share_title,picture,share_imgurl,share_desc,istoupiao,isjiabin,isqhb from " . tablename('haoman_dpm_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
$vote = pdo_fetch("select vote_status from " . tablename('haoman_dpm_newvote_set') . " where rid = :rid order by `id` asc", array(':rid' => $rid));
if($vote['vote_status']==0){
    message('抱歉，还未开启！', '', 'error');
}
$yyy = pdo_fetch("select isyyy from " . tablename('haoman_dpm_yyyreply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
$photo = pdo_fetch("select is_phone from " . tablename('haoman_dpm_photo_setting') . " where rid = :rid order by `id` desc", array(':rid' => $rid));

if(ISCUSTOM == 1 && CUSTOM_VERSION == 'DS'){
    $dsreply = pdo_fetch("select * from " . tablename('haoman_dpm_ds_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
}
if(ISCUSTOM == 1 && CUSTOM_VERSION == 'ZNL'){
    $znlreply = pdo_fetch( " SELECT * FROM ".tablename('haoman_dpm_znl_reply')." WHERE rid='".$rid."' " );
}

$option_img =  pdo_fetchall("select vote_img from " . tablename('haoman_dpm_newvote') . " where uniacid =:uniacid and rid = :rid", array(':rid' => $rid,':uniacid' => $_W['uniacid']));

//
if(empty($reply)){
    message('抱歉，活动已经结束，下次再来吧！', '', 'error');
}

$option_id = intval($_GPC['option_id']);//投票项目名称

if($_W['ispost']){
    $keyword=$_GPC['keyword'];
    $type=$_GPC['type'];

//    $data = array(
//        'status' => 0,
//        'list' => $aa,
//    );
//    echo json_encode($data);
//    exit();
    $t=time();
    $params = array(':rid' => $rid, ':uniacid' => $_W['uniacid'],'vote_status'=>1,':vote_starttime'=>$t,':vote_endtime'=>$t);

    if(!empty($option_id)&&$option_id!=0){
        $where.=' and id = :id';
        $params[':id'] = $option_id;
    }

    if(empty($keyword)){
        $nowpage=$_GPC['limit'];
    }else{
        $nowpage=1;
        if(is_numeric($keyword)){
            $condition .= " AND number={$keyword} ";
        }else{
            $condition .= " AND CONCAT(`name`) LIKE '%{$keyword}%'";
        }
    }



    $option =  pdo_fetch("select id,vote_endtime from " . tablename('haoman_dpm_newvote') . " where uniacid =:uniacid and rid = :rid and vote_status =:vote_status  and   vote_starttime <:vote_starttime  and vote_endtime > :vote_endtime " . $where . "  order by vote_pid desc,id desc", $params);

    if(empty($option)){
        exit(json_encode(array('status' => 401, 'content' => '没有更多投票')));
    }

//    if($type==2){
//        $toupiao = pdo_fetchall("select * from " . tablename('haoman_dpm_toupiao') . " where rid = '" . $rid . "' and uniacid = '" . $uniacid . "' and status=0 ".$condition."  order by get_num desc");
//
//    }

//    $pindex = max(1, intval($nowpage));
//    $psize = 10;
    $list = pdo_fetchall("SELECT * FROM " . tablename('haoman_dpm_toupiao') . " WHERE rid = :rid and uniacid =:uniacid and vote_id = :vote_id and status =0 ".$condition." order by rand() desc",array(':rid' => $rid, ':uniacid' => $_W['uniacid'],'vote_id'=>$option['id']));
    if(!empty($keyword) && empty($list)){
        exit(json_encode(array('status' => 301, 'content' => $list)));
    }
    if (!empty($list)){
        foreach ($list as $key => $value) {
            $list[$key]['img']=tomedia($list[$key]['img']);
//            $list[$key]['url']=$this->createMobileUrl('view', array('rid' => $rid,'id' => $list[$key]['id']));
//            if($value['attestation']){
//                $list[$key]['name']='<img class="jiavicon" src="'.MODULE_URL.'/template/static/images/jiavicon.png" height="16" width="16"/>'.$list[$key]['name'];
//            }

        }
        $sta =200;
        $endtime = $option['vote_endtime'] -time();
    }else{
        $sta =-103;
    }
    exit(json_encode(array('status' => $sta, 'content' => $list,'endtime'=>$endtime)));
}

//if ($reply == false) {
//    message('抱歉，活动已经结束，下次再来吧！', '', 'error');
//}
//if($reply['istoupiao']==1){
//    message('抱歉，活动还未开启，请留意大屏幕！', '', 'success');
//}
//$toupiao = pdo_fetchall("select * from " . tablename('haoman_dpm_toupiao') . " where rid = '" . $rid . "' and uniacid = '" . $uniacid . "' and status=0 and vote_id=0  order by get_num desc");
//
//if($toupiao==false){
//    message('抱歉，目前没有投票，下次再来吧！', '', 'error');
//}
//

//$nowtime = mktime(0, 0, 0);
//
//if ($fans['last_time'] < $nowtime) {
//
//    $fans['tp_times'] = 0;
//
//    $temp = pdo_update('haoman_dpm_fans', array('tp_times' =>$fans['tp_times'],'last_time' => $nowtime), array('id' => $fans['id']));
//
//}
//$total = pdo_fetchcolumn("select sum(get_num) from " . tablename('haoman_dpm_toupiao') . "  where rid = :rid and uniacid=:uniacid and status=0 and vote_id=0",array(':rid'=>$rid,':uniacid'=>$uniacid));
//
//foreach ($toupiao as &$v){
//    if($total>0){
//        $v['xyz'] = round(($v['get_num']/$total)*100,2);
//        if($v['xyz']>100){
//            $v['xyz']=100;
//        }
//    }else{
//        $v['xyz']=0;
//    }
//
//}
//unset($v);
//
//
//
//分享信息
$sharelink = $_W['siteroot'] . 'app/' . $this->createMobileUrl('share', array('rid' => $rid, 'from_user' => $page_from_user));
$sharetitle = empty($reply['share_title']) ? '一起来帮TA投票吧!!' : $reply['share_title'];
$sharedesc = empty($reply['share_desc']) ? '亲，一起来帮TA投票吧!，赢大奖哦！！' : str_replace("\r\n", " ", $reply['share_desc']);
if (!empty($reply['share_imgurl'])) {
    $shareimg = toimage($reply['share_imgurl']);
} else {
    $shareimg = toimage($reply['picture']);
}
$jssdk = new JSSDK();
$package = $jssdk->GetSignPackage();

include $this->template('new_vote_index');