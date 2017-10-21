<?php
global $_GPC, $_W;
$rid = intval($_GPC['rid']);
$rule = pdo_fetch("select id from " . tablename('haoman_dpm_pay_order') . " where rid = :rid and pay_type =2", array(':rid' => $rid));
if (empty($rule)) {
    message('抱歉，您要删除的订单不存在或是已经被删除！', '', 'error');
}
pdo_delete('haoman_dpm_pay_order', array('rid' => $rid,'pay_type'=>2));

$data = array(
    'flag' => 1,
    'msg' => "删除成功",
);

echo json_encode($data);
// message('批量审核成功！', referer(), 'success');