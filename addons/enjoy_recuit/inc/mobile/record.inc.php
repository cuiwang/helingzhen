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

$openid = $_W['openid'];
$com = pdo_fetch('select * from ' . tablename('enjoy_recuit_culture') . ' where uniacid = \'' . $_W['uniacid'] . '\'');
$myrecord = pdo_fetchall('select a.*,b.pname,b.type,c.* from ' . tablename('enjoy_recuit_deliver') . ' as a left join' . "\r\n\t\t\t\t" . ' ' . tablename('enjoy_recuit_position') . ' as b on a.pid=b.id left join ' . tablename('enjoy_recuit_position_range') . ' as c' . "\r\n\t\t\t\t" . 'on c.pid=b.id where a.openid=\'' . $openid . '\' and a.uniacid=' . $_W['uniacid'] . ' order by a.createtime desc limit 10');
include $this->template('record');
