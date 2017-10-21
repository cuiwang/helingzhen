<?php

global $_W, $_GPC;
$openId = trim($_GPC['openid']);
$noticeId = trim($_GPC['notice_id']);
$callBack = trim($_GPC['callback']);
$returnData = array();
if (intval($noticeId) <= 0) {
	$returnData['state'] = 0;
	$returnData['msg'] = "通知ID错误";
	returnJSON($returnData, $callBack);
	return;
}
//获取通知设置
$noticeSetting = pdo_fetch("SELECT * FROM " . tablename('hsh_tools_notice_setting') . " WHERE id = :id AND weid = :weid AND state = 1", array(":id" => $noticeId, ":weid" => $_W['uniacid']));
/* 判断是否在营业时间--start */
$openingHourBegin = $noticeSetting['opening_hour_begin'];  //营业开始时间
$openingHourEnd = $noticeSetting['opening_hour_end'];  //营业结束时间
$closingHint = $noticeSetting['closing_hint'];  //非营业时间提示
$openingTime = $noticeSetting['opening_time']; //正常营业起始时间
$pauseHint = $noticeSetting['pause_hint'];   //暂停营业提示
if (time() < $openingTime) {
	$returnData['state'] = 0;
	$returnData['msg'] = $pauseHint;
	returnJSON($returnData, $callBack);
	return;
}
if (date('G') < $openingHourBegin || date('G') >= $openingHourEnd) {
	$returnData['state'] = 0;
	$returnData['msg'] = $closingHint;
	returnJSON($returnData, $callBack);
	return;
}
/* 判断是否在营业时间--end */
$fieldSetting = json_decode(htmlspecialchars_decode($noticeSetting['field_setting']), true);
/* 存储数据 */
$fieldData = array();
foreach ($fieldSetting as $key => $val) {
	if ($val['show'] == "1") {
		$fieldData[$key] = trim($_GPC[$key]);
	}
}
$saveData = array();
$saveData['weid'] = $_W['uniacid'];
$saveData['notice_id'] = $noticeId;
$saveData['openid'] = $openId;
$saveData['field_value'] = returnJSON($fieldData, "none", false);
$saveData['notice_list'] = $noticeSetting['notice_list'];
$saveData['add_time'] = time();
$saveData['state'] = 1;
$addResult = pdo_insert("hsh_tools_notice_order_list", $saveData);
if ($addResult) {
	try {
		if ($noticeSetting['message_script'] == "") {
			throw new Exception;
		} else {
			$scriptFile = '/diy/' . $noticeSetting['message_script'] . '.php';
		}
		if (file_exists(MODULE_ROOT . $scriptFile)) {
			require_once MODULE_ROOT . $scriptFile;
		}
		/* 执行发送通知 */
		sendMsgForService($fieldData, $noticeSetting);
		if ($openId != "") {
			$returnData['openid']=$openId;
			sendMsgForClient($openId, $fieldData, $noticeSetting);
		}
	} catch (Exception $ex) {
		
	}
	$returnData['state'] = 1;
	$returnData['msg'] = $noticeSetting['success_hint'];
} else {
	$returnData['state'] = 0;
	$returnData['msg'] = "信息录入失败";
}
returnJSON($returnData, $callBack);
/* 存储数据 */
