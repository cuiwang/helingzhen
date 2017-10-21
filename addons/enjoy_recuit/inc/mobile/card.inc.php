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

$jssdk = new JSSDK();
$signPackage = $jssdk->GetSignPackage();
$pid = $_GPC['pid'];
$openid = $_W['openid'];
$id = Intval($_GPC['id']);
$com = pdo_fetch('select * from ' . tablename('enjoy_recuit_culture') . ' where uniacid = \'' . $_W['uniacid'] . '\'');

if (!empty($id)) {
	$item = pdo_fetch('select * from ' . tablename('enjoy_recuit_card') . ' where id=\'' . $id . '\' and uniacid=' . $_W['uniacid'] . '');
}


if ($_GPC['op'] == 'carddel') {
	$cardid = $_GPC['cardid'];
	$res = pdo_delete('enjoy_recuit_card', array('id' => $cardid));
	message('删除证书成功', $this->createMobileUrl('resume'), 'success');
}


if (checksubmit('submit')) {
	$data = array('uniacid' => $_W['uniacid'], 'openid' => $_GPC['openid'], 'cname' => $_GPC['cname']);

	if (!empty($id)) {
		pdo_update('enjoy_recuit_card', $data, array('id' => $id));
		pdo_update('enjoy_recuit_basic', array('createtime' => TIMESTAMP), array('openid' => $openid, 'uniacid' => $_W['uniacid']));
		$message = '更新证书成功！';
	}
	 else {
		pdo_insert('enjoy_recuit_card', $data);
		pdo_update('enjoy_recuit_basic', array('createtime' => TIMESTAMP), array('openid' => $openid, 'uniacid' => $_W['uniacid']));
		$message = '添加证书成功！';
	}

	header('location:' . $this->createMobileUrl('resume', array('pid' => $pid)) . '');
}


include $this->template('card');
