<?php
global $_GPC, $_W;
$rid = intval($_GPC['rid']);
$point = intval($_GPC['count']);
$countmax = intval($_GPC['countmax']);
$getpici = intval($_GPC['pici']);
$createtime = intval($_GPC['createtime']);
$uniacid = $_W['uniacid'];
$from_user = $_W['fans']['from_user'];
$avatar = $_W['fans']['tag']['avatar'];
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


if (empty($from_user)) {
    $data = array(
        'shenyu' => 0,
        'msg' => '获取不到您的会员信息，请刷新页面重试',
        'status' => $reply['status'],
        'code' => 11,
    );
    echo json_encode($data);
    exit;
}

$create_time = pdo_fetchcolumn( " SELECT createtime FROM ".tablename('haoman_dpm_yyyreply')." WHERE rid='".$rid."' and pici =  '".$getpici."' order by id desc" );

 $user= pdo_fetch( " SELECT * FROM ".tablename('haoman_dpm_yyyuser')." WHERE rid='".$rid."' and pici =  '".$getpici."' and from_user = '".$from_user."'" );

if(empty($from_user) || empty($avatar) || empty($nickname)){
    $fans = pdo_fetch("select * from " . tablename('haoman_dpm_fans') . " where rid = '" . $rid . "' and from_user='" . $from_user . "'");
    $from_user = empty($from_user) ? $fans['from_user'] : $from_user;
    $avatar = empty($avatar) ? $fans['avatar'] : $avatar;
    $nickname = empty($nickname) ? $fans['nickname'] : $nickname;
}

if(empty($user)){
    $data = array(
        'code' => 333,
    );
    echo json_encode($data);
    exit;

}


if($user['point'] > $countmax){
    $point = $countmax;
}

if($user['is_back']==1)
{
    $point=0;
}

pdo_update('haoman_dpm_yyyuser', array('createtime'=>$create_time,'point'=>$point,'endtime' => time()), array('id' => $user['id']));

$data = array(
    'code' => 99,
);


echo json_encode($data);