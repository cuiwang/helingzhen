<?php


global $_W;
global $_GPC;
$uniacid = $_W['uniacid'];
$pindex = max(1, intval($_GPC['page']));
$psize = 8;
$op = $_GPC['op'];
$pid = intval($_GPC['pid']);

if (!empty($pid)) {
	$where = ' and a.pid=' . $pid;
	$where1 = ' and a.pid=' . $pid;
}
 else {
	$where = '';
	$where1 = '';
}

if ($op == 'italy') {
	$where2 = ' and b.italy=1';
}
 else if ($op == 'del') {
	$did = trim($_GPC['did']);
	$res1 = pdo_delete('enjoy_recuit_deliver', array('id' => $did, 'uniacid' => $uniacid));

	if ($res1 == 1) {
		message('投递记录删除成功！', $this->createWebUrl('voted'), 'success');
	}


	$where2 = '';
}
 else {
	$where2 = '';
}

$userlist = pdo_fetchall('select a.id as aid,b.*,c.pname,d.avatar as weavatar from ' . tablename('enjoy_recuit_deliver') . ' as a left join ' . tablename('enjoy_recuit_basic') . "\r\n" . '    as b on a.openid=b.openid left join ' . tablename('enjoy_recuit_position') . ' as c on a.pid=c.id left join ' . tablename('enjoy_recuit_fans') . "\r\n" . '    as d on a.openid=d.openid where a.uniacid=' . $uniacid . $where . $where2 . ' order by a.createtime desc');
$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('enjoy_recuit_deliver') . ' as a left join' . "\r\n" . tablename('enjoy_recuit_basic') . ' as b on a.openid=b.openid WHERE a.uniacid = \'' . $_W['uniacid'] . '\'' . $where1 . $where2);
$pager = pagination($total, $pindex, $psize);
include $this->template('voted');
