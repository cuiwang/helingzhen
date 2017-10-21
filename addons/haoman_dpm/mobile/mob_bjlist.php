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

$reply = pdo_fetch( " SELECT * FROM ".tablename('haoman_dpm_reply')." WHERE rid='".$rid."' " );
$bp = pdo_fetch("select bp_pay2 from " . tablename('haoman_dpm_bpreply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));

$fans = pdo_fetch("select id from " . tablename('haoman_dpm_fans') . " where rid = '" . $rid . "' and from_user='" . $from_user . "'");
    if ($fans == false) {

        if($reply['isbaoming']==1){
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: " . $this->createMobileUrl('go_baoming', array('id' => $rid,'from_user'=>$page_from_user)) . "");
            exit();
        }else{
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: " . $this->createMobileUrl('information', array('id' => $rid,'from_user'=>$page_from_user)) . "");
            exit();
        }
    } else {

        if($fans['isbaoming']==1){
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: " . $this->createMobileUrl('ve_baoming', array('id' => $rid,'from_user'=>$page_from_user)) . "");
            exit();
        }
    }
 pdo_query("DELETE FROM ".tablename('haoman_dpm_shop_car')." WHERE status != :status and from_user=:from_user", array(':status' => '2',':from_user' => $from_user));
//   pdo_delete('haoman_dpm_shop_car', array('status'=>0,'from_user' => $from_user));
$category = pdo_fetchall("select * from " . tablename('haoman_dpm_shop_category') . " where `rid` = :rid and uniacid =:uniacid and enabled=0 order by `bianhao`",array(':rid'=>$rid,':uniacid'=>$uniacid));

$goods= pdo_fetchall("select * from " . tablename("haoman_dpm_shop_goods") . " where rid =:rid and uniacid=:uniacid and deleted!=1 and status=1 order by `sale_number` desc limit 100",array(':rid'=>$rid,':uniacid'=>$uniacid));


    //分享信息
    $sharelink = $_W['siteroot'] . 'app/' . $this->createMobileUrl('share', array('rid' => $rid, 'from_user' => $page_from_user));
    $sharetitle = empty($reply['share_title']) ? '一起来聊一聊吧!' : $reply['share_title'];
    $sharedesc = empty($reply['share_desc']) ? '亲，一起来聊一聊吧！！' : str_replace("\r\n", " ", $reply['share_desc']);
    if (!empty($reply['share_imgurl'])) {
        $shareimg = toimage($reply['share_imgurl']);
    } else {
        $shareimg = toimage($reply['picture']);
    }
    $jssdk = new JSSDK();
    $package = $jssdk->GetSignPackage();

    include $this->template('mob_bjlist');
