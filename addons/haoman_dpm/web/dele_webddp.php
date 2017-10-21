<?php
global $_W  ,$_GPC;
// checklogin();
$rid = intval($_GPC['rid']);
load()->model('reply');

$uid = intval($_GPC['uid']);
if(empty($uid)){
    message('获取ID出错，请刷新后重试', '', 'error');
}
pdo_delete('haoman_dpm_pair_combination', array('id' => $uid));
message("删除成功",$this->createWebUrl('ddpshow',array('rid'=>$rid)),"success");