<?php
global $_GPC, $_W;
foreach ($_GPC['idArr'] as $k=>$rid) {
	$rid = intval($rid);
	if ($rid == 0 ||$rid ==1)
		continue;
	$rule = pdo_fetch("select id from " . tablename('haoman_dpm_addad') . " where id = :id ", array(':id' => $rid));
	if (empty($rule)) {
		message('抱歉，要修改的规则不存在或是已经被删除！', '', 'error');
	}
	if (pdo_delete('haoman_dpm_addad', array('id' => $rid))) {

	}
}
message('删除成功！', referer(), 'success');