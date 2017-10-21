<?php
global $_GPC, $_W;
$rid = intval($_GPC['rid']);
$from_user = $_W['openid'];
$uniacid = $_W['uniacid'];

$stem =  pdo_delete('haoman_dpm_pair_combination', array('rid' => $rid));
if($stem){
    $result = array(
        'ResultType' => 1,
        'msg' => "删除成功！",
    );
    $this->message($result);
    exit();
}else{
    $result = array(
        'ResultType' => 0,
        'msg' => "删除失败，请稍等在试！",
    );
    $this->message($result);
    exit();
}