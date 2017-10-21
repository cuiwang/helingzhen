<?php
global $_GPC, $_W;
if ($_W['isajax']) {
	$from_user = $_GPC['uid'];
	$rid = intval($_GPC['rid']);
	//粉丝数据


	$data = pdo_fetch("select * from " . tablename('haoman_dpm_fans') . ' where from_user = :from_user and uniacid = :uniacid', array(':uniacid' => $_W['uniacid'], ':from_user' => $from_user));

	$list = pdo_fetchall("select * from " . tablename('haoman_dpm_award') . "  where titleid >5 and rid = :rid and uniacid=:uniacid and from_user=:from_user order by id desc ", array(':uniacid' => $_W['uniacid'], ':rid' => $rid, ':from_user' => $data['from_user']));
	include $this->template('axq2');
	exit();
}