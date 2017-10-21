<?php

global $_GPC, $_W;
$rid = intval($_GPC['id']);
$uniacid = $_W['uniacid'];

//网页授权借用开始（特殊代码）

$from_user = $_W['fans']['from_user'];
$avatar = $_W['fans']['tag']['avatar'];
$nickname = $_W['fans']['nickname'];

load()->model('account');
$_W['account'] = account_fetch($_W['acid']);
$cookieid = '__cookie_haoman_dpm_201606186_' . $rid;
$cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
if ($_W['account']['level'] != 4||empty($nickname)) {
    $from_user = authcode(base64_decode($_GPC['from_user']), 'DECODE');
    $avatar = $cookie['avatar'];
    $nickname = $cookie['nickname'];
}

//网页授权借用结束（特殊代码）

$img = str_replace('data:image/png;base64,', '', $_POST['params']);
$img = str_replace(' ', '+', $img);
$img = base64_decode($img);

$filename ="images/$uniacid/haoman_dpm_".md5(uniqid()).'.png';
//$fileName = '../addons/haoman_dpm/qrcode/'.md5(uniqid()).'.png';
$f = fopen($fileName, 'w+');
fwrite($f, $img);
fclose($f);

$insert = array(
    'rid'=>$rid,
    'uniacid'=>$uniacid,
    'from_user'=>$from_user,
    'nickname'=>$nickname,
    'avatar'=>$avatar,
    'img'=>$fileName,
    'status'=>0,
    'createtime'=>time(),
    );

$temp = pdo_insert('haoman_dpm_shouqian',$insert);

if($temp == false){
    $data = array(
        'flag'=>100,
    );
}else{
    $data = array(
        'flag'=>1,
    );
}

echo json_encode($data);

