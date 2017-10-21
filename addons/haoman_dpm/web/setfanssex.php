<?php
global $_GPC, $_W;
$sex = intval($_GPC['sex']);
$fansid = intval($_GPC['fansid']);

if (empty($fansid)) {
    message('抱歉，找不到该粉丝！', '', 'error');
}

$temp = pdo_update('haoman_dpm_fans', array('sex' => $sex), array('id' => $fansid));
message('修改性别成功！', referer(), 'success');