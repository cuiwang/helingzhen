<?php
global $_GPC, $_W;


$rid = intval($_GPC['rid']);
$uid = $_GPC['from_user'];//$from_user
$for_from_user = $_GPC['acceptid'];
$uniacid = $_W['uniacid'];
$from_user = $_W['openid'];

load()->model('account');
$_W['account'] = account_fetch($_W['acid']);
$cookieid = '__cookie_haoman_dpm_201606186_' . $rid;
$cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
if ($_W['account']['level'] != 4) {
    $from_user = $cookie['openid'];
}
//$from_user='oQAFAwCS19dHrsZhSd4h0uRdEKUM';


if($from_user){
    $fans = pdo_fetch("select id,avatar,nickname,from_user from " . tablename('haoman_dpm_fans') . " where rid = '" . $rid . "' and from_user='" . $uid . "'");

    $for_fans = pdo_fetch("select id,avatar,nickname,from_user,is_online from " . tablename('haoman_dpm_fans') . " where rid = '" . $rid . "' and from_user='" . $for_from_user . "'");


    $content = $_GPC['content'];

    $image = $_GPC['imgId'];
     if($fans['id']<$for_fans['id']){
         $all_fansid = $fans['id'].','.$for_fans['id'];
         $ground_id = $fans['id'].'#'.$for_fans['id'];
     }else{
         $all_fansid = $for_fans['id'].','.$fans['id'];
         $ground_id = $for_fans['id'].'#'.$fans['id'];
     }
    $insert = array(
        'uniacid' => $uniacid,
        'avatar' => $fans['avatar'],
        'nickname' => $fans['nickname'],
        'from_user' => $fans['from_user'],
        'fansid' => $fans['id'],
        'for_avatar' => $for_fans['avatar'],
        'for_nickname' => $for_fans['nickname'],
        'for_from_user' => $for_fans['from_user'],
        'for_fansid' => $for_fans['id'],
        'word' => $content,
        'all_fansid' => $all_fansid,
        'ground_id' => $ground_id,
        'wordimg' => $image,
        'rid' => $rid,
        'status' => 1,
        'message_to_fansid' => 0,
        'createtime' => time(),
    );
    $temp = pdo_insert('haoman_dpm_private_chat',$insert);
     $id = pdo_insertid();

         //不在线
        $mbid = pdo_fetch("select p_templateid from " . tablename('haoman_dpm_notifications') . "  where rid=:rid", array(':rid'=>$rid));
        if($mbid&&$for_fans['is_online']==0){
            if($image){
                $content='图片';
            }

            $is_online = pdo_fetch("select createtime from " . tablename('haoman_dpm_on_line') . " where uniacid=:uniacid and rid =:rid and from_user =:from_user and to_from_user=:to_from_user ", array(':uniacid'=>$uniacid,':rid'=>$rid,':from_user' => $for_from_user,':to_from_user'=>$from_user));


            $time =31;//一分钟

            $now_time=time();
            if($is_online){
            $time =$now_time- $is_online['createtime'];
                if($time<20){
                    //在线


                }else{
                    //离线

                    $tempss = pdo_update('haoman_dpm_private_chat', array('message_to_fansid' => $for_fans['id']), array('rid' => $rid, 'uniacid' => $_W['uniacid'], 'id' => $id));

                    $this->private_chat_template($for_fans['from_user'],$mbid['p_templateid'],$fans['from_user'],$rid,$fans['nickname'],$content);

                }
            }else{
                $tempss = pdo_update('haoman_dpm_private_chat', array('message_to_fansid' => $for_fans['id']), array('rid' => $rid, 'uniacid' => $_W['uniacid'], 'id' => $id));

                $this->private_chat_template($for_fans['from_user'],$mbid['p_templateid'],$fans['from_user'],$rid,$fans['nickname'],$content);

            }

        }


    $result = array(
        'code' => 1,
        'data' => "提交成功！",

    );

    $this->message($result);

}else{
    $result = array(
        'code' => 0,
        'data' => "提交失败！",

    );

    $this->message($result);
}








