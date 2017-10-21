<?php
$sn = $_GPC['sn'];
$openid = $_W['openid'];
$user = pdo_fetch('SELECT * FROM '.tablename($this->table_user).' WHERE openid=:openid AND sn=:sn',array(':openid'=>$openid,':sn'=>$sn));
$coupon = pdo_fetch('SELECT * FROM '.tablename($this->table_coupon).' WHERE id=:bonusid',array(':bonusid'=>$user['prizeid']));
$reply = pdo_fetch('SELECT * FROM '.tablename($this->table_reply).' WHERE id=:replyid',array(':replyid'=>$user['replyid']));

// 校验 
if(empty($sn) || !$user || $user['status']<=1 || empty($user['prizeid']) || empty($reply) || empty($coupon) ){
	$error = '参数错误，请联系管理员';
    include $this->template('no');
    exit();
}

if($_GPC['op']=='add'){
    if($user['prize']) {
        $log = array();
        $log['uniacid'] = $_W['uniacid'];
        $log['userid'] = $user['id'];
        $log['status'] = 1;
        $log['log'] = $user['prize'];
        pdo_insert($this->table_coupon_log, $log);
        pdo_update($this->table_user,array('status'=>3),array('sn'=>$sn,'openid'=>$openid));
        die(json_encode(array('result'=>true,'msg'=>'success')));
    }else{
        $log = array();
        $log['uniacid'] = $_W['uniacid'];
        $log['userid'] = $user['id'];
        $log['status'] = 0;
        $log['log'] = $sn;
        pdo_insert($this->table_coupon_log, $log);
        die(json_encode(array('result'=>false,'msg'=>'false')));
    }
}

// 获取ticket
$api = $this->module['config']['coupon'];
$nowtime = time();

if(($nowtime-intval($api['time']))>7000){

    $accObj = WeiXinAccount::create($_W['account']);
    $access_token = $accObj->fetch_available_token();
    $url="https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=".$access_token."&type=wx_card";
    load()->func('communication');
    $response = ihttp_get($url);
    $temp = @json_decode($response['content'], true);
    if(!empty($temp['errcode'])){
        $error = $temp['errmsg'];
        include $this->template('no');
        exit();
    }
    $ticket = $temp['ticket'];
    $this->module['config']['coupon']['ticket'] = $ticket;
    $this->module['config']['coupon']['time'] = $nowtime;
    $this->saveSettings($this->module['config']);
}else{
    $ticket = $api['ticket'];
}

//构建
$card=array(
    'api_ticket'=>$ticket,
    'timestamp'=>$nowtime,
    'cardid'=>trim($coupon['couponcode'])
);
sort($card,SORT_STRING);
$signature = sha1(implode($card));

//更新数据
$temp = array();
$temp['signature'] = $signature;
$temp['time'] = $nowtime;
$temp['card'] = $card;
$temp['coupon'] = $coupon;
pdo_update($this->table_user,array('prize'=>json_encode($temp)),array('sn'=>$sn,'openid'=>$openid));
include $this->template('coupon');

?>