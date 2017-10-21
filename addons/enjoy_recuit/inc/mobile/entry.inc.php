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

$com = pdo_fetch('select * from ' . tablename('enjoy_recuit_culture') . ' where uniacid = \'' . $_W['uniacid'] . '\'');

if (checksubmit('submit')) {
	$keyword = $_GPC['keyword'];
	$list = pdo_fetchall('select * from ' . tablename('enjoy_recuit_position') . ' as a left join ' . tablename('enjoy_recuit_position_range') . ' as b on a.id=b.pid WHERE a.uniacid = \'' . $_W['uniacid'] . '\' and CONCAT(a.key,a.pname) like \'%' . $keyword . '%\' and play=0 order by hot desc');
}
 else {
	$list = pdo_fetchall('select * from ' . tablename('enjoy_recuit_position') . ' as a left join ' . tablename('enjoy_recuit_position_range') . ' as b on a.id=b.pid WHERE a.uniacid = \'' . $_W['uniacid'] . '\' and play=0 order by hot desc');
}

$sharelink = $_W['siteroot'] . 'app/' . $this->createMobileUrl('entry');
$sharetitle = $com['cname'] . '-职位浏览';
$sharedesc = '快来看看有没有适合你的职位吧!';
$shareimg = tomedia($com['logo']);
include $this->template('position');
