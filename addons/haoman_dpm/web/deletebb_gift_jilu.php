<?php
global $_GPC, $_W;
$id = intval($_GPC['id']);
$rule = pdo_fetch("select id from " . tablename('haoman_dpm_pay_order') . " where id = :id ", array(':id' => $id));
if (empty($rule)) {
    message('抱歉，参数错误！');
}else{

    pdo_delete('haoman_dpm_pay_order', array('id' => $id));

    message('打赏记录删除成功！', referer(), 'success');
}