<?php
global $_GPC, $_W;
$id = intval($_GPC['id']);
$rule = pdo_fetch("select id from " . tablename('haoman_dpm_messages') . " where id = :id ", array(':id' => $id));
if (empty($rule)) {
	message('抱歉，参数错误！');
}
if (pdo_update('haoman_dpm_messages', array('status' => 1), array('id' => $id))) {
	message('审核成功！', referer(), 'success');
}