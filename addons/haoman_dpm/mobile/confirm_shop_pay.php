<?php
global $_GPC, $_W;
$rid = intval($_GPC['rid']);
$token = $_GPC['token'];
$from_user = $_W['openid'];
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
}

//网页授权借用结束
$reply = pdo_fetch( " SELECT * FROM ".tablename('haoman_dpm_bpreply')." WHERE rid='".$rid."' " );

$fans = pdo_fetch("select * from " . tablename('haoman_dpm_fans') . " where rid = '" . $rid . "' and from_user='" . $from_user . "'");

//是否是管理员判断
$isAdmin =0;
$admin = pdo_fetch("select id,free_times,uses_times from " . tablename('haoman_dpm_bpadmin') . "  where admin_openid=:admin_openid and status=0 and rid=:rid", array(':admin_openid' => $from_user,':rid'=>$rid));

if($admin){
//    $isAdmin =1;//1表示是管理员，0表示不是
}
$shoppingcar = pdo_fetchall("select * from " . tablename("haoman_dpm_shop_car") . " where rid =:rid and uniacid=:uniacid and from_user=:from_user and status=0",array(':rid'=>$rid,':uniacid'=>$uniacid,':from_user'=>$from_user));

if(empty($shoppingcar)){
    $data = array(
        'success' => 100,
        'msg' => "您还没挑选物品!!",
    );

    echo json_encode($data);
    exit;
}

//$temp =  pdo_update('haoman_dpm_shop_car',array('status'=>1),array('from_user'=>$from_user,'status'=>0));
$address =$_GPC['address'];

if(empty($address)){
    $data = array(
        'success' => 100,
        'msg' => "请输入正确的位置!",
    );

    echo json_encode($data);
    exit;
}

$money = '';

foreach ($shoppingcar as $v){
    $money +=$v['money']*$v['number'];

}


    $pay_money = $money;

    if($money<=0){
        $data = array(
            'success' => 100,
            'msg' => "支付金额出错!",
        );

        echo json_encode($data);
        exit;
    }


$tid = date('YmdHi').random(8, 1);
$result = pdo_insert('haoman_dpm_pay_order', array(
                    'uniacid' => $_W['uniacid'],
                    'transid'=>$tid,
                    'from_user' => $from_user,
                    'avatar' => $fans['avatar'],
                    'nickname' => $fans['nickname'],
                    'bptime' => 0,
                    'message' => 0,
                    'wordimg' => $address,
                    'pay_total' => $pay_money,
                    'pay_ip' => $_W['clientip'],
                    'rid' => $rid,
                    'status' => 1,
                    'isadmin' => 0,
                    'psy_type' => 0,
                    'pay_type' => 10,//商城购物
                    'createtime' => time(),
));

if (empty($result)) {
    $data = array(
        'success' => 100,
        'msg' => "支付失败",
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
                'msg' => "提交支付成功",
            );

            echo json_encode($data);
            exit;


        }
        if($admin['free_times']-$admin['uses_times']<=0&&$admin['free_times']!=0){
            $isAdmin==0;
        }
    }

    foreach ($shoppingcar as $row) {
        if (empty($row)) {
            continue;
        }
        $d = array(
            'rid' => $rid,
            'uniacid' => $_W['uniacid'],
            'goodsid' => $row['shopid'],
            'orderid' => $tid,
            'number' => $row['number'],
            'categoryid' => $row['categoryid'],
            'price' => $row['money'],
            'createtime' => TIMESTAMP,
            'status' => 0,
            'shop_name' => $row['shopname'],
        );

        pdo_insert('haoman_dpm_pay_order_goods', $d);

    }
    pdo_delete("haoman_dpm_shop_car", array('rid'=>$rid,"uniacid" => $_W['uniacid'], "from_user" => $from_user));


    if($token=='onBridge'){
        $data = array('fee' => floatval($pay_money), 'uniacid' => $_W['uniacid'], 'ordersn' => date('YmdHi').random(8, 1), 'openid' => $from_user, 'nickname' => $fans['nickname'], 'status' => 0, 'title' => "大屏幕商城购物", 'xq' => '微信支付', 'addtime' => date('Y-m-d H:i:s', time()));
        $params = array('tid' => $tid, 'ordersn' => $tid, 'title' => "大屏幕商城购物", 'user' => $from_user, 'fee' => floatval($pay_money), 'module' => 'haoman_dpm',);


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
        'pay_type' => 8,
        'isadmin' => 0,
        'orderid' => $orderid,
        'tid' => $tid,
        'pay_money' => $pay_money,
        'msg' => "提交订单成功",
    );

    echo json_encode($data);
    exit;
}