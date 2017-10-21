<?php
global $_GPC, $_W;
$id = intval($_GPC['id']);
$rule = pdo_fetch("select id,toupiaoip from " . tablename('haoman_dpm_tp_log') . " where id = :id ", array(':id' => $id));
if (empty($rule)) {
    message('抱歉，参数错误！');
}else{
    $tp = pdo_fetch("select id,get_num from " . tablename('haoman_dpm_toupiao') . " where id = :id ", array(':id' => $rule['toupiaoip']));

    pdo_update('haoman_dpm_toupiao',array('get_num'=>$tp['get_num']-1),array('id'=>$tp['id']));

    pdo_delete('haoman_dpm_tp_log', array('id' => $id));

    message('投票记录删除成功！', referer(), 'success');
}