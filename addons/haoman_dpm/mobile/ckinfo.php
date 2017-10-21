<?php
global $_GPC,$_W;
// $this->checkFollow;
// $this->checkBowser;
$rid = intval($_GPC['id']);

$user_agent = $_SERVER['HTTP_USER_AGENT'];
if (strpos($user_agent, 'MicroMessenger') === false) {

    header("HTTP/1.1 301 Moved Permanently");
    header("Location: {$this->createMobileUrl('other',array('type'=>1,'id'=>$rid))}");
    exit();
}

$from_user = $_W['fans']['from_user'];
$avatar = $_W['fans']['tag']['avatar'];
$sex = $_W['fans']['tag']['sex'];
$nickname = $_W['fans']['nickname'];

load()->model('account');
$_W['account'] = account_fetch($_W['acid']);
$cookieid = '__cookie_haoman_dpm_201606186_' . $rid;
$cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
if ($_W['account']['level'] != 4) {
    $from_user = authcode(base64_decode($_GPC['from_user']), 'DECODE');
    $avatar = $cookie['avatar'];
    $nickname = $cookie['nickname'];
}
$sex= $cookie['sex'];

$reply = pdo_fetch( " SELECT * FROM ".tablename('haoman_dpm_reply')." WHERE rid='".$rid."' " );

    $fans = pdo_fetch("select * from " . tablename('haoman_dpm_fans') . " where rid = '" . $rid . "' and from_user='" . $from_user . "'");
    $max_qd = pdo_fetchcolumn("select count(id) from " . tablename('haoman_dpm_fans') . "  where rid = :rid and uniacid=:uniacid ", array(':rid'=>$rid,':uniacid'=>$_W['uniacid']));
    $max_qd = ($max_qd%2000)+1;
    $realname = trim($_GPC['realname']);
    $mobile = trim($_GPC['mobile']);
    $address = trim($_GPC['address']);
    $qdword = trim($_GPC['qdword']);
    $tokens = $_GPC['tokens'];
    if($tokens==1){
        $sex = $_GPC['sex'];
    }

if(empty($nickname)){
    $nickname = trim($_GPC['nickname']);
}
if(empty($avatar)){
    $avatar = $_GPC['avatar'];
}

if($reply['ziliao'] ==2 || $reply['ziliao'] ==3){
    if(empty($mobile)){
        $data = array(
            'success' => 100,
            'msg' => "请填写手机号码",
        );

        echo json_encode($data);
        exit;
    }
    $chars = "/^((\(\d{2,3}\))|(\d{3}\-))?1(3|5|8|9|7)\d{9}$/";
    $flag = preg_match($chars, $mobile);
    if($flag == false){
        $data = array(
            'success' => 100,
            'msg' => "手机号码格式错误",
        );

        echo json_encode($data);
        exit;
    }
}

if($reply['isbaoming']==1){
    if($reply['bm_starttime']>time()){
        $data = array(
            'success' => 100,
            'msg' => "报名时间还没开始",
        );
        echo json_encode($data);
        exit;
    }
    if($reply['bm_endtime']<time()){
        $data = array(
            'success' => 100,
            'msg' => "报名时间已经结束了",
        );
        echo json_encode($data);
        exit;
    }
    $num1 = pdo_fetchcolumn("select count(id) from " . tablename('haoman_dpm_fans') . "  where rid = :rid and uniacid=:uniacid", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
    if($num1>=$reply['bm_pnumber']&&$reply['bm_pnumber']!=0){
        message('抱歉，报名人数已经满了，下次再来吧！', '', 'error');
        exit();
    }
    if($reply['isbaoming_pay']!=0&&$reply['isbaoming_paymoney']>0){
        $data = array(
            'success' => 200,
            'msg' => "支付报名",
        );
        echo json_encode($data);
        exit;
    }
    $isbaoming = 1;
}else{
    $isbaoming = 0;
}
//$nickname = $this->strFilter($nickname);
if(empty($fans)){

    $insert = array(
        'uniacid' => $_W['uniacid'],
        'from_user' => $from_user,
        'avatar' => $avatar,
        'nickname' => $nickname,
        'realname' => $realname,
        'mobile' => $mobile,
        'address' => $address,
        'qdword' => $qdword,
        'rid' => $rid,
        'isbaoming' => $isbaoming,
        'is_back' => 0,
        'sex' => $sex,
        'awardingid' => $max_qd,
        'createtime' => time(),
    );


    pdo_insert('haoman_dpm_fans',$insert);
    pdo_update('haoman_dpm_reply', array('fansnum' => $reply['fansnum'] + 1, 'viewnum' => $reply['viewnum'] + 1), array('id' => $reply['id']));

}else{
    $fans['avatar'] = $avatar;
    $fans['nickname'] = $nickname;
    $fans['realname'] = $realname;
    $fans['mobile'] = $mobile;
    $fans['address'] = $address;
    $fans['qdword'] = $qdword;
    $fans['sex'] = $sex;
    pdo_update('haoman_dpm_fans',$fans,array('id'=>$fans['id']));
}
$uniacid=$_W['uniacid'];
$url ="../addons/haoman_dpm/sign/$uniacid/$rid/";



$filename=$url."sign.txt";

$handle=fopen($filename,"a+");
$avatar =empty($avatar)?"{$_W['siteroot']}addons/haoman_dpm/common/item2.jpg":$avatar;
$str=fwrite($handle,$from_user."|".$nickname."|".$avatar."|".date('Y/m/d H:i',time())."\n");

fclose($handle);

$data = array(
    'success' => 1,
    'qd_pid' => $max_qd,
    'msg' => "签到成功",
);

echo json_encode($data);