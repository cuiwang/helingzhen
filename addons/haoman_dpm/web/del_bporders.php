<?php
global $_GPC, $_W;
foreach ($_GPC['idArr'] as $k=>$rid) {
    $rid = intval($rid);
    if ($rid == 0 ||$rid ==1)
        continue;
    $rule = pdo_fetch("select id from " . tablename('haoman_dpm_pay_order') . " where id = :id ", array(':id' => $rid));
    if (empty($rule)) {
        message('抱歉，您选择的订单不存在或是已经被删除！', '', 'error');
    }
    pdo_delete('haoman_dpm_pay_order', array('id' => $rid));
}

$data = array(
    'flag' => 1,
    'msg' => "批量删除成功",
);

echo json_encode($data);
// message('批量审核成功！', referer(), 'success');