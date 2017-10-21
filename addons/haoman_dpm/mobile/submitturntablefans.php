<?php
global $_GPC, $_W;
$rid = intval($_GPC['rid']);

$uniacid = $_W['uniacid'];

$data = intval($_GPC['size']);

$xysreply = pdo_fetch("SELECT is_meg FROM " . tablename('haoman_dpm_xysreply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));


for($i = 0; $i < $data; $i++) {


$fans = pdo_fetch("SELECT from_user FROM " . tablename('haoman_dpm_fans') . " WHERE rid = :rid and uniacid = :uniacid and id =:id",array(':rid'=>$rid,':uniacid'=>$uniacid,':id'=>$_GPC[$i.'_FansId']));

$prize = pdo_fetch("SELECT * FROM " . tablename('haoman_dpm_prize') . " WHERE rid = :rid and uniacid = :uniacid and id =:id",array(':rid'=>$rid,':uniacid'=>$uniacid,':id'=>$_GPC[$i.'_AwardId']));

    $insert = array(
        'rid' => $rid,
        'uniacid' => $_W['uniacid'],
        'turntable' => 3,
        'from_user' => $fans['from_user'],
        'avatar' => $_GPC[$i.'_FansHead'],
        'nickname' => $_GPC[$i.'_FansNickName'],
        'awardname' => $prize['prizename'],
        'awardsimg' => $prize['awardsimg'],
        'prizetype' => $prize['ptype'],
        'credit' => $prize['credit'],
        'prize' => $prize['sort'],
        'titleid' => $_GPC[$i.'_AwardId'],
        'createtime' => time(),
        'status' => 1,
        'probalilty' => 1,
    );
    $stem = pdo_insert('haoman_dpm_award', $insert);
    pdo_update('haoman_dpm_prize', array('prizedraw' => $prize['prizedraw'] + 1), array('id' => $prize['id']));

    if($xysreply['is_meg']==0){
         $actions = "恭喜您".$_GPC[$i.'_FansNickName']."，参加大屏幕抽奖，抽中了：" . $prize['prizename'] . "，请留意领奖时间！";

         $this->sendText($fans['from_user'], $actions);
     }

//            $insertpair = array(
//                'rid' => $rid,
//                'uniacid' => $uniacid,
//                'one_openid' => $_GPC[$i.'_AwardId'],
//                'other_openid' => $_GPC[$i.'_FansId'],
//                'one_nickname' => $_GPC[$i.'_FansNickName'],
//                'other_nickname' => $_GPC[$i.'_FansHead'],
//                'createtime' => time(),
//            );
//            $stem = pdo_insert('haoman_dpm_award', $insertpair);
}
if($stem){


    $result = array(
        'ResultType' => 1,
        'msg' => $data,
    );
    $this->message($result);
    exit();
}else{
    $result = array(
        'ResultType' => 0,
        'msg' => "提交失败，请稍等在试！",
    );
    $this->message($result);
    exit();
}
