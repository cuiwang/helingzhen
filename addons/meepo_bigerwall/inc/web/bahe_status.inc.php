<?php
global $_W,$_GPC;
$weid = $_W['uniacid'];
$id = intval($_GPC['id']);
$data = array();
$bahe_status = intval($_GPC['bahe_status']);
pdo_update('weixin_wall_reply',array('bahe_status'=>$bahe_status),array('rid'=>$id,'weid'=>$weid));
message('修改成功',$this->createWebUrl('bahe_phb',array('id'=>$id)));