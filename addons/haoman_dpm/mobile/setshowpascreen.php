<?php
global $_GPC, $_W;
$id = intval($_GPC['id']);
$from_user = $_W['openid'];
$uniacid = $_W['uniacid'];
if($id){
    $temp = pdo_update('haoman_dpm_messages', array('is_bpshow' => 0), array('id' => $id));
}
if($temp){
    $result = array(
        'isResultTrue' => 1,
    );

    $this->message($result);
}