<?php


global $_W;
global $_GPC;

if (!empty($this->module['config']['appid']) && !empty($this->module['config']['appsecret'])) {
	$this->auth();
}
 else {
	$user_agent = $_SERVER['HTTP_USER_AGENT'];

	if (strpos($user_agent, 'MicroMessenger') === false) {
		exit('本页面仅支持微信访问!非微信浏览器禁止浏览!');
	}

}

$id = intval($_GPC['id']);
$openid = $_W['openid'];
$uniacid = $_W['uniacid'];
$pid = $id;
$com = pdo_fetch('select * from ' . tablename('enjoy_recuit_culture') . ' where uniacid = \'' . $_W['uniacid'] . '\'');
$res = pdo_fetchcolumn('select count(*) from ' . tablename('enjoy_recuit_view') . ' where uniacid = \'' . $_W['uniacid'] . '\' and pid=' . $id . ' and openid=\'' . $openid . '\'');

if (0 < Intval($res)) {
}
 else {
	$data = array('uniacid' => $_W['uniacid'], 'openid' => $_W['openid'], 'pid' => $id, 'time' => TIMESTAMP);
	pdo_insert('enjoy_recuit_view', $data);
	pdo_query('update ' . tablename('enjoy_recuit_position') . ' set views=views+1 where uniacid = \'' . $_W['uniacid'] . '\' and id=' . $id);
}

$ctime = pdo_fetchcolumn('select createtime from ' . tablename('enjoy_recuit_deliver') . ' where uniacid=' . $uniacid . ' and openid=\'' . $openid . '\' and pid=' . $pid . ' order by createtime desc');
$time7 = 7 * 24 * 60 * 60;
$time = Intval($ctime) + $time7;

if ($time <= TIMESTAMP) {
	$vote = 1;
}
 else {
	$vote = 0;
}

$item = pdo_fetch('select * from ' . tablename('enjoy_recuit_position') . ' as a left join ' . tablename('enjoy_recuit_position_range') . ' as b on a.id=b.pid WHERE a.uniacid = \'' . $_W['uniacid'] . '\' and a.id=' . $id);
$hotlist = pdo_fetchall('select * from ' . tablename('enjoy_recuit_position') . ' WHERE uniacid = \'' . $_W['uniacid'] . '\' order by hot desc limit 3');
$openid = $_W['openid'];
$sharelink = $_W['siteroot'] . 'app/' . $this->createMobileUrl('pdetail', array('id' => $item['id']));
$sharetitle = str_replace('{cname}', $com['cname'], $com['share_title']);
$sharetitle = str_replace('{pname}', $item['pname'], $sharetitle);
$sharedesc = str_replace('{cname}', $com['cname'], $com['share_desc']);
$sharedesc = str_replace('{pname}', $item['pname'], $sharedesc);
$shareimg = tomedia($com['share_icon']);
include $this->template('pdetail');
