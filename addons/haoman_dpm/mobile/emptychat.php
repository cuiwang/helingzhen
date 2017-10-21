<?php
global $_GPC, $_W;


$rid = intval($_GPC['rid']);
$uid = $_GPC['from_user'];//$from_user
$for_from_user = $_GPC['uid'];
$uniacid = $_W['uniacid'];
$from_user = $_W['fans']['from_user'];

load()->model('account');
$_W['account'] = account_fetch($_W['acid']);
$cookieid = '__cookie_haoman_dpm_201606186_' . $rid;
$cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
if ($_W['account']['level'] != 4) {
    $from_user = $cookie['openid'];
}
if (empty($from_user)) {
    $result = array(
        'code' => 100,
        'data' => "提交失败,请刷新页面重试！",

    );

    $this->message($result);
    exit();
}
//$from_user='oQAFAwCS19dHrsZhSd4h0uRdEKUM';


if($from_user) {
    $fans = pdo_fetch("select id from " . tablename('haoman_dpm_fans') . " where rid = '" . $rid . "' and from_user='" . $from_user . "'");

//    $for_fans = pdo_fetch("select id,avatar,nickname,from_user,is_online from " . tablename('haoman_dpm_fans') . " where rid = '" . $rid . "' and from_user='" . $for_from_user . "'");

    $list = pdo_fetchall("SELECT * FROM " . tablename('haoman_dpm_private_chat') . " WHERE rid = :rid and uniacid = :uniacid and from_user in(:from_user,:for_from_user)  and for_from_user in(:from_user,:for_from_user)  ORDER BY id desc",array(':rid'=>$rid,':uniacid'=>$uniacid,':from_user'=>$from_user,':for_from_user'=>$for_from_user));

    if($list){
        foreach($list as $k=> $v){


             $a= explode(',',$v['all_fansid']);
            $arr = array_merge(array_diff($a, array($fans['id'])));
            $arr= implode(',',$arr);

            pdo_update('haoman_dpm_private_chat', array('all_fansid' => $arr), array('id' => $v['id']));

        }
    }

    $result = array(
        'code' => 1,
        'data' => "提交成功！",

    );

    $this->message($result);

}







