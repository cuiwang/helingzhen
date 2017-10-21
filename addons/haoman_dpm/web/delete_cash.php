<?php
global $_GPC, $_W;
$id = intval($_GPC['id']);
$rule = pdo_fetch("select id from " . tablename('haoman_dpm_cash') . " where id = :id ", array(':id' => $id));
if (empty($rule)) {
    message('抱歉，参数错误！');
}
if(pdo_delete('haoman_dpm_cash', array('id' => $rule['id']))){
    message('删除成功！', referer(), 'success');
}
//        if (pdo_update('haoman_dpm_pay_order', array('status'=>3), array('id'=>$rule['id']))) {
//            message('删除成功！', referer(), 'success');
//        }