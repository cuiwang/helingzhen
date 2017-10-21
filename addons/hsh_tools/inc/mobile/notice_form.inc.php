<?php

global $_W, $_GPC;
$code = trim($_GPC['code']);
$noticeId = trim($_GPC['notice_id']);
$openId = trim($_GPC['openid']);
/* 判断用户来源----start */
if ($code != "") {
	
}
if ($openId != "") {
	$noticeOrderInfo = pdo_fetch("SELECT * FROM " . tablename('hsh_tools_notice_order_list') . " WHERE openid = :openid AND notice_id = :notice_id AND state = 1 ORDER BY add_time DESC", array(':openid' => $openId, 'notice_id' => $noticeId));
	$fieldValue = json_decode(htmlspecialchars_decode($noticeOrderInfo['field_value']), true);
}
/* 判断用户来源----end  稍后修改 */
if (intval($noticeId) <= 0) {
	message('通知ID错误!');
	return;
}
$noticeField = pdo_fetch("SELECT * FROM " . tablename('hsh_tools_notice_setting') . " WHERE id = :id AND weid = :weid AND state = 1", array(":id" => $noticeId, ":weid" => $_W['uniacid']));
//$noticeField = array_map("htmlspecialchars_decodeForArray",$noticeField);
$noticeField = htmlspecialchars_decodeForArray($noticeField);
$fieldSetting = json_decode(htmlspecialchars_decode($noticeField['field_setting']), true);
/* 前台输出 */
foreach ($fieldSetting as $key => $val) {
	if ($val['show'] != "1") {
		unset($fieldSetting[$key]);
	}
}
load()->func('tpl');
if ($noticeField['template_name'] != "") {
	include $this->template($noticeField['template_name']);
} else {
	include $this->template('notice_form');
}

/* 前台输出 */
return;
