<?php

/* *
 * 功能：服务器同步通知页面
 */
global $_W, $_GPC;
$weid = $_W['uniacid'];
$quan_id = intval($_GPC['quan_id']);
$id = intval($_GPC['id']);
$member = $this->get_member();
$from_user = $member['openid'];
$subscribe = $member['follow'];
$quan = $this->get_quan();
$adv = $this->get_adv();
$config = $this->settings;
$settings = $this->module['config'];

if (empty ($settings['ye_pay']) && $adv['total_pay']>$member['credit']) {
	$this->returnError('非法提交');
}


if ($adv['total_pay']>$member['credit']) {
	$this->returnError('金额不足');
}


/*pdo_update('cgc_ad_adv', array (
	"message_send" => 1
), array (
	'id' => $adv['id']
));*/
/*$_userlist = pdo_fetchall('SELECT openid FROM ' . tablename('cgc_ad_member') . " where weid=" . $weid . " and quan_id={$adv['quan_id']} and type=1 and message_notify =1 and status=1 limit 5000");

$_url = $_W['siteroot'] . 'app/' . substr($this->createMobileUrl('foo', array (
	'form' => 'detail',
	'quan_id' => $adv['quan_id'],
	'id' => $adv['id'],
	'model' => $adv['model']
)), 2);
$htt = str_replace('"', "'", htmlspecialchars_decode($config['tuisong']));
$_tdata = array (
	'first' => array (
		'value' => '系统通知',
		'color' => '#173177'
	),
	'keyword1' => array (
		'value' => $config['tuisong'],
		'color' => '#173177'
	),
	'keyword2' => array (
		'value' => date('Y-m-d H:i:s', time()),
		'color' => '#173177'
	),
	'keyword3' => array (
		'value' => '抢钱通知',
		'color' => '#173177'
	),
	'remark' => array (
		'value' => '点击详情进入。如果你不想接受此类消息，可以在个人中心选择关闭。',
		'color' => '#173177'
	),
	
);

foreach ($_userlist as $key => $r) {
	if ($config['is_type'] == 1) {
		$a = sendTemplate_common($r['openid'], $config['template_id'], $_url, $_tdata);
	} else {
		$a = post_send_text($r['openid'], $htt);
	}
}
*/

//发送红包
send_red_commission($member,$quan,$adv,$config);

//是否管理人员 
$is_kf = 0;

$_kf = explode(',', $this->settings['kf_openid']);
if (!empty ($adv['openid']) && in_array($adv['openid'], $_kf)) {
	$is_kf = 1;
}

$_kf = explode(',', $quan['kf_openid']);
if (!empty ($adv['openid']) && in_array($adv['openid'], $_kf)) {
	$is_kf = 1;
}



$money = abs($adv['total_pay']);
$ret3 = pdo_update("cgc_ad_member", array (
	'credit' => $member['credit'] - $money
), array (
	'id' => $member['id']
));
if (empty ($ret3)) {
	$this->returnError('扣款失败');
}	


$op = empty ($quan['shenhe']) ? "pay" : "shenhe";

 $status=!empty($quan['shenhe'])?"3":"1";
 
 if (!empty ($is_kf)) { //管理员通过审核
	$status=1;
	$op = "pay";
}
 $ret=pdo_update('cgc_ad_adv', array (
		"status" => $status
	), array (
		'id' => $adv['id']
	));
	
	
if (empty ($ret)) {
	$this->returnError($adv['id'].'状态更新失败');
}	
	



/*if (!empty ($quan['shenhe']) && empty ($adv['check_message_send'])) {
	pdo_update('cgc_ad_adv', array (
		"check_message_send" => 1
	), array (
		'id' => $adv['id']
	));
	$this->check_msg($config, $quan, $adv);
}*/
$url = $_W['siteroot'] . 'app/' . substr($this->createMobileUrl('success', array (
	'quan_id' => $adv['quan_id'],
	'model' => $adv['model'],
	'id' => $adv['id'],
	'op' => $op
)), 2);

header("location:" . $url);
exit ();