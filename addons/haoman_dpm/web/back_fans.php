<?php
global $_GPC, $_W;
foreach ($_GPC['idArr'] as $k=>$rid) {
    $rid = intval($rid);
    if ($rid == 0 ||$rid ==1)
        continue;
    $rule = pdo_fetch("select id,from_user from " . tablename('haoman_dpm_fans') . " where id = :id ", array(':id' => $rid));
    if (empty($rule)) {
        message('抱歉，您选择的粉丝不存在或是已经被删除！', '', 'error');
    }
    pdo_update('haoman_dpm_messages', array('is_back' => 1), array('from_user' => $rule['from_user']));
    pdo_update('haoman_dpm_fans', array('is_back' => 1), array('id' => $rid));
    pdo_update('haoman_dpm_yyyuser', array('is_back' => 1), array('from_user' => $rule['from_user']));
}

$data = array(
    'flag' => 1,
    'msg' => "批量拉黑成功",
);

echo json_encode($data);
// message('批量审核成功！', referer(), 'success');