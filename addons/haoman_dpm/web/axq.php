<?php
global $_GPC, $_W;
if ($_W['isajax']) {
	$uid = intval($_GPC['uid']);
	$rid = intval($_GPC['rid']);
	//粉丝数据

	$data = pdo_fetch("select * from " . tablename('haoman_dpm_fans') . ' where id = :id and uniacid = :uniacid', array(':uniacid' => $_W['uniacid'], ':id' => $uid));

    $award = pdo_fetchall("select * from " . tablename('haoman_dpm_award') . " where status = 1 and prizetype = 0 and rid = " . $rid . " and from_user='" . $data['from_user'] . "'");
    $nums =0;
    foreach($award as $k){
        $nums +=$k['credit'];
    }

    $list = pdo_fetchall("select * from " . tablename('haoman_dpm_award') . "  where rid = :rid and uniacid=:uniacid and from_user=:from_user order by id desc ", array(':uniacid' => $_W['uniacid'], ':rid' => $rid, ':from_user' => $data['from_user']));

	include $this->template('axq');

    exit();
}