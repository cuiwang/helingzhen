<?php


global $_W;
global $_GPC;
$uniacid = $_W['uniacid'];
$pindex = max(1, intval($_GPC['page']));
$psize = 8;
$userlist = pdo_fetchall('select a.*,b.avatar as weavatar from ' . tablename('enjoy_recuit_basic') . ' as a left join ' . tablename('enjoy_recuit_fans') . ' as b on a.openid=b.openid where a.uniacid=' . $uniacid . ' order by a.createtime desc LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize);
$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('enjoy_recuit_basic') . ' WHERE uniacid = \'' . $_W['uniacid'] . '\'');
$op = $_GPC['op'];

if ($op == 'italy') {
	$userlist = pdo_fetchall('select a.*,b.avatar as weavatar from ' . tablename('enjoy_recuit_basic') . ' as a left join ' . tablename('enjoy_recuit_fans') . ' as b on a.openid=b.openid where a.uniacid=' . $uniacid . ' and italy=1 order by a.createtime desc LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('enjoy_recuit_basic') . ' WHERE uniacid = \'' . $_W['uniacid'] . '\' and italy=1');
}
 else if ($op == 'del') {
	$openid = trim($_GPC['openid']);
	$res1 = pdo_delete('enjoy_recuit_basic', array('openid' => $openid, 'uniacid' => $uniacid));
	$res2 = pdo_delete('enjoy_recuit_card', array('openid' => $openid, 'uniacid' => $uniacid));
	$res3 = pdo_delete('enjoy_recuit_info', array('openid' => $openid, 'uniacid' => $uniacid));

	if ($res1 == 1) {
		message('简历删除成功！', $this->createWebUrl('mresume', array('openid' => $openid)), 'success');
	}

}


$pager = pagination($total, $pindex, $psize);
include $this->template('mresume');
