<?php
global $_GPC, $_W;
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
    $sex = $cookie['sex'];
    $nickname = $cookie['nickname'];
}

$reply = pdo_fetch( " SELECT * FROM ".tablename('haoman_dpm_reply')." WHERE rid='".$rid."' " );

//        $fans = pdo_fetch("select * from " . tablename('haoman_dpm_fans') . " where rid = '" . $rid . "' and from_user='" . $from_user . "'");
$realname = trim($_GPC['realname']);
$mobile = trim($_GPC['mobile']);
$address = trim($_GPC['address']);
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

if($reply['isbaoming_pay']==0||$reply['isbaoming_paymoney']<=0){
    $data = array(
        'success' => 100,
        'msg' => "未开启支付报名!",
    );

    echo json_encode($data);
    exit;
}

$payorder = pdo_fetch("select * from " . tablename('haoman_dpm_pay_order') . " where rid = '" . $rid . "' and from_user='" . $from_user . "'and status =2");
//$nickname = $this->strFilter($nickname);
    if(empty($payorder)) {
        $result = pdo_insert('haoman_dpm_pay_order', array(
                            'uniacid' => $_W['uniacid'],
                            'transid'=>date('YmdHi').random(8, 1),
                            'from_user' => $from_user,
                            'avatar' => $avatar,
                            'nickname' => $nickname,
                            'from_realname' => $realname,
                            'mobile' => $mobile,
                            'pay_addr' => $address,
                            'pay_total' => $reply['isbaoming_paymoney'],
                            'pay_ip' => $_W['clientip'],
                            'rid' => $rid,
                            'status' => 1,
                            'pay_type' => 0,
                            'sex' => $sex,
                            'createtime' => time(),
        ));
    }else{
        $data = array(
            'success' => 100,
            'msg' => "您已经支付报名过了!",
        );

        echo json_encode($data);
        exit;
    }

    if (empty($result)) {
        $data = array(
            'success' => 100,
            'msg' => "支付报名失败",
        );
        echo json_encode($data);
        exit;

    }else{
        $orderid = pdo_insertid();



    $data = array(
        'success' => 1,
        'orderid' => $orderid,
        'msg' => "提交报名支付成功",
    );

    echo json_encode($data);
    exit;
    }