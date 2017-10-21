<?php
global $_GPC, $_W;
$id = intval($_GPC['id']);
$rid = intval($_GPC['rid']);
$status = intval($_GPC['status']);
$reply = pdo_fetch("select * from " . tablename('haoman_dpm_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
if (empty($id)) {
	message('抱歉，传递的参数错误！', '', 'error');
}
$p = array('status' => $status);
if ($status == 2) {
	$p['consumetime'] = TIMESTAMP;
}
if ($status == 3) {
	$p['consumetime'] = '';
	$p['status'] = 1;
}
$temp = pdo_update('haoman_dpm_award', $p, array('id' => $id));
if ($temp == false) {
	message('抱歉，刚才操作数据失败！', '', 'error');
} else {
	//从奖池减少奖品
	message('状态设置成功！', $this->createWebUrl('awardlist', array('rid' => $_GPC['rid'])), 'success');
}