<?php
global $_GPC, $_W;
$rid = intval($_GPC['rid']);
$from_user = $_W['openid'];
$uniacid = $_W['uniacid'];

$data = intval($_GPC['size']);



for($i = 0; $i < $data; $i++) {

    $insertpair = array(
        'rid' => $rid,
        'uniacid' => $uniacid,
        'one_openid' => $_GPC[$i.'_OneFansId'],
        'other_openid' => $_GPC[$i.'_AnotherFansId'],
        'one_nickname' => $_GPC[$i.'_OneNickName'],
        'other_nickname' => $_GPC[$i.'_AnotherNickName'],
        'one_avatar' => $_GPC[$i.'_OneHead'],
        'other_avatar' => $_GPC[$i.'_AnotherHead'],
        'pair' => $i+1,
        'createtime' => time(),
    );
 $stem = pdo_insert('haoman_dpm_pair_combination', $insertpair);
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