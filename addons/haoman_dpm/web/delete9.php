<?php
global $_GPC, $_W;
$id = intval($_GPC['id']);
$rule = pdo_fetch("select id from " . tablename('haoman_dpm_addad') . " where id = :id ", array(':id' => $id));
if (empty($rule)) {
	message('抱歉，参数错误！');
}
if (pdo_delete('haoman_dpm_addad', array('id' => $id))) {
	message('删除成功！', referer(), 'success');
}