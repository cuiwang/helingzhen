<?php
global $_GPC, $_W;
$rid = intval($_GPC['id']);
$token = $_GPC['token'];

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
$item_number = $_GPC['item_number'];
$item_id = $_GPC['item_id'];
$item_price = $_GPC['item_price'];
$anonymous = $_GPC['anonymous'];
$pay_type = $_GPC['type'];
$uid = $_GPC['uid'];

$to_fans = pdo_fetch("select * from " . tablename('haoman_dpm_fans') . " where rid = '" . $rid . "' and from_user='" . $uid . "'");

    if(empty($to_fans)){
        $data = array(
            'success' => 100,
            'msg' => "您要送礼的对象不存在",
        );

        echo json_encode($data);
        exit;
        }

if(empty($item_number)||$item_number<1){
    $data = array(
        'success' => 100,
        'msg' => "赠送数量不能为空",
    );

    echo json_encode($data);
    exit;
}


if(empty($nickname) || empty($avatar)){
    $nickname = $fans['nickname'];
    $avatar = tomedia($fans['avatar']);
}

if(empty($guest_id)&&empty($item_id)){
    $data = array(
        'success' => 100,
        'msg' => "您输入的礼物不存在",
    );

    echo json_encode($data);
    exit;
}

if($reply['is_gift']!=1){
    $data = array(
        'success' => 100,
        'msg' => "未开启礼物模式!!",
    );

    echo json_encode($data);
    exit;
}

$item_list = pdo_fetch("SELECT bb_price,bb_time FROM " . tablename('haoman_dpm_bbgift') . " WHERE rid = :rid and uniacid = :uniacid and type =1 and id =:id ORDER BY id desc",array(':rid'=>$rid,':uniacid'=>$_W['uniacid'],':id'=>$item_id));

//$guest_list = pdo_fetch("SELECT id FROM " . tablename('haoman_dpm_guest') . " WHERE rid = :rid and uniacid = :uniacid and turntable =1 and id =:id ORDER BY id desc",array(':rid'=>$rid,':uniacid'=>$_W['uniacid'],':id'=>$guest_id));

if(empty($item_list)){
    $data = array(
        'success' => 100,
        'msg' => "礼物不存在!",
    );

    echo json_encode($data);
    exit;
}

$tid = date('YmdHi').random(8, 1);
$bptime= $item_list['bb_time']*$item_number;
$pay_total= $item_list['bb_price']*$item_number;
$result = pdo_insert('haoman_dpm_pay_order', array(
    'uniacid' => $_W['uniacid'],
    'transid'=>$tid,
    'from_user' => $from_user,
    'avatar' => $avatar,
    'nickname' => $nickname,
    'from_realname' => $uid,
    'bptime' => $bptime,
    'message' => $item_number,
    'wordimg' =>$item_id,
    'pay_total' => $pay_total,
    'pay_ip' => $_W['clientip'],
    'rid' => $rid,
    'status' => 1,
    'pay_addr' => '',
    'isadmin' => 0,
    'pay_type' => 7,
    'psy_type' => 7,
    'createtime' => time(),
));



if (empty($result)) {
    $data = array(
        'success' => 100,
        'msg' => "送礼失败",
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
            $update['pay_total'] = $pay_total;


            $ress =  $this->modify($transid,$update);

            $data = array(
                'success' => 1,
                'isadmin'=>1,
                'msg' => "提交送礼成功",
            );
//        $ret = pdo_update('haoman_dpm_guest', array('type'=>$item_list['type']+1), array('id'=>$item_list['id']));
            echo json_encode($data);
            exit;
        }
        if($admin['free_times']-$admin['uses_times']<=0&&$admin['free_times']!=0){
            $isAdmin==0;
        }
    }

    if($token=='onBridge'){
        $data = array('fee' => floatval($pay_total), 'uniacid' => $_W['uniacid'], 'ordersn' => date('YmdHi').random(8, 1), 'openid' => $from_user, 'nickname' => $nickname, 'status' => 0, 'title' => "大屏幕送礼物费用", 'xq' => '微信支付', 'addtime' => date('Y-m-d H:i:s', time()));
        $params = array('tid' => $tid, 'ordersn' => $tid, 'title' => "大屏幕送礼物费用", 'user' => $from_user, 'fee' => floatval($pay_total), 'module' => 'haoman_dpm',);


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
        'pay_type' => 3,
        'orderid' => $orderid,
        'tid' => $tid,
        'pay_money' => $pay_total,
        'msg' => "送礼成功",
        'isadmin' => 0,
    );

    echo json_encode($data);
    exit;
}