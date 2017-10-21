<?php
global $_GPC, $_W;
$rid = intval($_GPC['id']);
$token = $_GPC['token'];
$uniacid =$_W['uniacid'];
load()->func('file');
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
$reply = pdo_fetch( " SELECT * FROM ".tablename('haoman_dpm_bpreply')." WHERE rid='".$rid."' " );

$fans = pdo_fetch("select * from " . tablename('haoman_dpm_fans') . " where rid = '" . $rid . "' and from_user='" . $from_user . "'");

//是否是管理员判断
$isAdmin =0;
$admin = pdo_fetch("select id,free_times,uses_times from " . tablename('haoman_dpm_bpadmin') . "  where admin_openid=:admin_openid and status=0 and rid=:rid", array(':admin_openid' => $from_user,':rid'=>$rid));

if($admin){
    $isAdmin =1;//1表示是管理员，0表示不是
}

$message = trim($_GPC['message']);
$pbtime = $_GPC['pbtime'];
$bppic = $_GPC['bppic'];
$pay_type = $_GPC['type'];
$bp_index = $_GPC['bp_index'];

$img = str_replace('data:image/png;base64,', '', $bppic);
$img = str_replace(' ', '+', $img);
$img = base64_decode($img);

$filename ="images/$uniacid/haoman_dpm_".md5(uniqid()).'.png';
//$fileName = '../addons/haoman_dpm/qrcode/'.md5(uniqid()).'.png';
//$f = fopen($fileName, 'w+');
file_write($filename, $img);
//fclose($f);


if($bp_index!=$pbtime){
    $data = array(
        'success' => 100,
        'msg' => "参数错误！"
    );

    echo json_encode($data);
    exit;
}

if(empty($nickname) || empty($avatar)){
    $nickname = $fans['nickname'];
    $avatar = tomedia($fans['avatar']);
}

if(empty($message)&&empty($bppic)){
    $data = array(
        'success' => 100,
        'msg' => "请输入霸屏语或上传图片",
    );

    echo json_encode($data);
    exit;
}
if(empty($pbtime)){
    $data = array(
        'success' => 100,
        'msg' => "请选择霸屏时长",
    );

    echo json_encode($data);
    exit;
}
//       if(isset($message{30})){
//           $data = array(
//               'success' => 100,
//               'msg' => "霸屏限制30个字以内！",
//           );
//
//           echo json_encode($data);
//           exit;
//       }
//if($reply['isbp']!=1&&$reply['isvo']!=1){
//    $data = array(
//        'success' => 100,
//        'msg' => "未开启霸屏模式!!",
//    );
//
//    echo json_encode($data);
//    exit;
//}

$paymoney = pdo_fetch("select bp_money,bp_time from " . tablename('haoman_dpm_bpmoney') . " where rid = '" . $rid . "' and id='" . $bp_index . "'and status =0 and bp_type=1");


if($paymoney){
    $pay_money = $paymoney['bp_money'];
    $pbtime = $paymoney['bp_time'];
}else{
    $data = array(
        'success' => 100,
        'msg' => "霸屏金额不存在!",
    );

    echo json_encode($data);
    exit;
}
$tid = date('YmdHi').random(8, 1);
$result = pdo_insert('haoman_dpm_pay_order', array(
    'uniacid' => $_W['uniacid'],
    'transid'=>$tid,
    'from_user' => $from_user,
    'avatar' => $avatar,
    'nickname' => $nickname,
    'from_realname' => '',
    'bptime' => $pbtime,
    'message' => $message,
    'wordimg' => $filename,
    'pay_total' => $pay_money,
    'pay_ip' => $_W['clientip'],
    'rid' => $rid,
    'status' => 1,
    'isadmin' => 0,
    'psy_type' => $pay_type,
    'pay_type' => 2,
    'createtime' => time(),
));

if (empty($result)) {
    $data = array(
        'success' => 100,
        'msg' => "霸屏失败",
    );
    echo json_encode($data);
    exit;

}else{
    $orderid = pdo_insertid();

    if($isAdmin==1){
        if($admin['free_times']-$admin['uses_times']>0||$admin['free_times']==0){
            $update = array();
            $update['status'] = 2;
            $update['paytime'] = TIMESTAMP;
            $transid = $tid;
            $update['orderid'] = 2;
            $update['isadmin'] = 1;
            $update['pay_total'] = $pay_money;
            $ress =  $this->modify($transid,$update);

            $data = array(
                'success' => 1,
                'isadmin'=>1,
                'msg' => "提交霸屏支付成功",
            );

            echo json_encode($data);
            exit;


        }
        if($admin['free_times']-$admin['uses_times']<=0&&$admin['free_times']!=0){
            $isAdmin==0;
        }
    }


    if($token=='onBridge'){
        $data = array('fee' => floatval($pay_money), 'uniacid' => $_W['uniacid'], 'ordersn' => date('YmdHi').random(8, 1), 'openid' => $from_user, 'nickname' => $nickname, 'status' => 0, 'title' => "大屏幕霸屏费用", 'xq' => '微信支付', 'addtime' => date('Y-m-d H:i:s', time()));
        $params = array('tid' => $tid, 'ordersn' => $tid, 'title' => "大屏幕霸屏费用", 'user' => $from_user, 'fee' => floatval($pay_money), 'module' => 'haoman_dpm',);


        $log = pdo_get('core_paylog', array('uniacid' => $_W['uniacid'], 'module' => $params['module'], 'tid' => $params['tid']));
        if (empty($log)) {
            $log = array('uniacid' => $_W['uniacid'], 'acid' => $_W['acid'], 'openid' => $_W['member']['uid'], 'module' => $params['module'], 'tid' => $params['tid'], 'fee' => $params['fee'], 'card_fee' => $params['fee'], 'status' => '0', 'is_usecard' => '0');
            pdo_insert('core_paylog', $log);
        }


        $params = base64_encode(json_encode($params));
    }
    $data = array(
        'success' => 1,
        'params' => $params,
        'arr' => $data,
        'pay_type' => 2,
        'isadmin' => 0,
        'orderid' => $orderid,
        'tid' => $tid,
        'pay_money' => $pay_money,
        'msg' => "提交霸屏支付成功",
    );

    echo json_encode($data);
    exit;
}