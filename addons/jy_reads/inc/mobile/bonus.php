<?php
$sn = $_GPC['sn'];
// $openid = $this->getOpenid();
// $openid = $this->getUser();
//  $openid = $openid['openid'];
$openid = $_W['openid'];

$user = pdo_fetch('SELECT * FROM '.tablename($this->table_user).' WHERE openid=:openid AND sn=:sn',array(':openid'=>$openid,':sn'=>$sn));
$bonus = pdo_fetch('SELECT * FROM '.tablename($this->table_bonus).' WHERE id=:bonusid',array(':bonusid'=>$user['prizeid']));
$reply = pdo_fetch('SELECT * FROM '.tablename($this->table_reply).' WHERE id=:replyid',array(':replyid'=>$user['replyid']));
// 校验
$api = $this->module['config']['bonus'];
if($user['status']==3){
  $error = '你已经领取过红包';
    include $this->template('no');
    exit();
}
else if(empty($sn) || !$user || $user['status']!=2 || empty($user['prizeid']) || empty($api) || empty($reply) || empty($bonus) ){
	$error = '参数设置错误，或者信息缺失，请联系管理员';
    include $this->template('no');
    exit();
}

// 正常逻辑
if($user['prize'] && $user['status']!='1'){
    $prize = json_decode($user['prize'],true);
}
// 发红包逻辑========

// 获取支付金额 单位分
if($bonus['isrange']==1){
    $temp = explode('-', $bonus['bonusvaluerange']);
    $begin = floatval($temp[0]) * 100;
    $end = floatval($temp[1]) * 100;
    if($end > $begin){
        $fee = mt_rand(intval($begin),intval($end));
    }else{
        $fee = floatval($bonus['bonusvalue']) * 100;
    }
} else{
    $fee = floatval($bonus['bonusvalue']) * 100;
}

if($prize['total_amount']){
    $fee = intval($prize['total_amount']);
}

// cert文件目录
$certdir = MODULE_ROOT . '/cert/' . $_W['uniacid'];
// 请求接口
$url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';

// 请求参数
$pars = array();
$pars['nonce_str'] = random(32);
$pars['mch_billno'] = $api['mchid'] . date('Ymd') . sprintf('%010d', $user['id']);
$pars['mch_id'] = $api['mchid'];
$pars['wxappid'] = $api['appid'];
$pars['send_name'] = empty($bonus['sendname'])?'集阅读':$bonus['sendname'];
$pars['re_openid'] = $user['openid'];
$pars['total_amount'] = $fee;
$pars['total_num'] = 1;
$pars['wishing'] = empty($bonus['wishing'])?'恭喜':$bonus['wishing'];
$pars['client_ip'] = $api['ip'];
$pars['act_name'] = empty($bonus['actname'])?'集阅读':$bonus['actname'];
$pars['remark'] = empty($bonus['remark'])?'集阅读':$bonus['remark'];

ksort($pars, SORT_STRING);
$string1 = '';
foreach($pars as $k => $v) {
    $string1 .= "{$k}={$v}&";
}
$string1 .= "key={$api['mchkey']}";
$pars['sign'] = strtoupper(md5($string1));
$xml = array2xml($pars);
$extras = array();
$extras['CURLOPT_CAINFO'] = $certdir . '/' . MD5($api['time'].'ca') . '.pem';
$extras['CURLOPT_SSLCERT'] = $certdir . '/' .  MD5($api['time'].'apiclient_cert') . '.pem';
$extras['CURLOPT_SSLKEY'] = $certdir . '/' . MD5($api['time'].'apiclient_key') . '.pem';

// var_dump($extras);
// exit();
load()->func('communication');
$procResult = null;
$resp = ihttp_request($url, $xml, $extras);
if(is_error($resp)) {
    $procResult = $resp;
} else {
    $xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp['content'];
    $dom = new \DOMDocument();
    if($dom->loadXML($xml)) {
        $xpath = new \DOMXPath($dom);
        $code = $xpath->evaluate('string(//xml/return_code)');
        $ret = $xpath->evaluate('string(//xml/result_code)');
        if(strtolower($code) == 'success' && strtolower($ret) == 'success') {
            $procResult = true;
        } else {
            $error = $xpath->evaluate('string(//xml/err_code_des)');
            $procResult = error(-2, $error);
        }
    } else {
        $procResult = error(-1, 'error response');
    }
}

if(is_error($procResult)) {
    $log = array();
    $log['uniacid'] = $_W['uniacid'];
    $log['userid'] = $user['id'];
    $log['status'] = 0;
    $log['log'] = json_encode($resp);
    pdo_insert($this->table_bonus_log, $log);
    include $this->template('no');
} else {
	$log = array();
	$log['uniacid'] = $_W['uniacid'];
    $log['userid'] = $user['id'];
    $log['status'] = 1;
    $log['log'] = json_encode($pars);
    pdo_insert($this->table_bonus_log, $log);
    pdo_update($this->table_user,array('status'=>3,'prize'=>json_encode($pars)),array('sn'=>$sn,'openid'=>$openid));
	include $this->template('bonus');
}
// ==================

