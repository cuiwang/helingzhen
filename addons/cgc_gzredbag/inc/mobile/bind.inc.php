<?php
load()->func('logging');
global $_W, $_GPC;

$uniacid = $_W["uniacid"];
$acid = $_W["acid"];
$settings = $this->module['config'];
$modulename = $this->modulename;
$openid = empty ($_GPC['fromuser']) ? $_W['openid'] : $_GPC['fromuser'];

if (!empty ($settings['wechat_mode']) && empty ($openid)) {
	$message = "借用模式，请用关键字进入这个界面。";
	include $this->template('bind');
	return;
}

$userinfo = getFromUser($settings, $modulename);
$userinfo = json_decode($userinfo, true);
$jy_openid = $userinfo['openid'];

$gzredbag_user = new gzredbag_user();
$item = $gzredbag_user->selectByOpenid($openid);

if (!empty ($item['jy_openid'])) {
	$message = "已经绑定过";
	//绑定成功后，直接发红包；
	if (empty ($item['send_status']) || $item['send_status'] == '0') {

		//红包金额
		$money = mt_rand(($settings["min_money"]) * 100, ($settings["max_money"]) * 100);

		//借用发红包模式。
		if ($settings['wechat_mode']) {
			$openid = $item['jy_openid'];
		}

		if ($settings['sendtype']) {
			//企业红包
			$ret = send_qyfk($settings, $openid, $money, "红包来了");
		} else {
			//现金红包
			$ret = send_xjhb($settings, $openid, $money, "红包来了");
		}

		if ($ret['code'] != 0) {
			logging_run($ret); //回滚
			$message .= "," . $ret['message'];
			include $this->template('bind');
			return;
		} else {
			post_send_text($openid, "红包来了");
		}

		$ret = $gzredbag_user->modifyByOpenid($item['openid'], array (
			"money" => $money,
			"send_status" => 1
		));

		if (empty ($ret)) {
			$message .= ",发红包成功,状态改变失败";
		} else {
			$message .= ",发红包成功";
		}
	}

	include $this->template('bind');
	return;
}

$temp = $gzredbag_user->modifyByOpenid($openid, array (
	"jy_openid" => $jy_openid
));

if ($temp == false) {
	$message = "绑定失败";
} else {
	$message = "绑定账号成功";

	//绑定成功后，直接发红包；
	if (empty ($item['send_status']) || $item['send_status'] == '0') {

		//红包金额
		$money = mt_rand(($settings["min_money"]) * 100, ($settings["max_money"]) * 100);

		//借用发红包模式。
		if ($settings['wechat_mode']) {
			$openid = $item['jy_openid'];
		}

		if ($settings['sendtype']) {
			//企业红包
			$ret = send_qyfk($settings, $openid, $money, "红包来了");
		} else {
			//现金红包
			$ret = send_xjhb($settings, $openid, $money, "红包来了");
		}

		if ($ret['code'] != 0) {
			logging_run($ret); //回滚
			$message .= "," . $ret['message'];
			include $this->template('bind');
			return;
		} else {
			post_send_text($openid, "红包来了");
		}

		$ret = $gzredbag_user->modifyByOpenid($item['openid'], array (
			"money" => $money,
			"send_status" => 1
		));

		if (empty ($ret)) {
			$message .= ",发红包成功,状态改变失败";
		}
		$message .= ",发红包成功";
	}
}

include $this->template('bind');