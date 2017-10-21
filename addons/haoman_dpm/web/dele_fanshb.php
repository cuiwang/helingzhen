<?php
global $_GPC, $_W;
$rid = intval($_GPC['rid']);
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if($operation == 'del'){
    $uid = intval($_GPC['uid']);
    if(empty($uid)){
        message('获取ID出错，请刷新后重试', '', 'error');
    }
    pdo_delete('haoman_dpm_hb_log', array('id' => $uid));
    pdo_delete('haoman_dpm_hb_award', array('prize' => $uid));
    message("删除红包成功",$this->createWebUrl('fanshbshow',array('rid'=>$rid)),"success");

}else{
    $uid = intval($_GPC['id']);
    if(empty($uid)){
        message('获取ID出错，请刷新后重试', '', 'error');
    }
    pdo_delete('haoman_dpm_hb_award', array('id' => $uid));
    message("删除红包成功",$this->createWebUrl('fanshbshow',array('rid'=>$rid)),"success");
}