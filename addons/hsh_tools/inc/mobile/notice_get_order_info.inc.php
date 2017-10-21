<?php

global $_W, $_GPC;
//var_dump($_W['config']['db']['tablepre']);
$openId = trim($_GPC['openid']);
$noticeId = trim($_GPC['notice_id']);
$callBack = trim($_GPC['callback']);
$fields = trim($_GPC['fields']);
$returnData = array();

if ($openId == '' || $noticeId == '' || $fields == '') {
	$returnData['state'] = 0;
	$returnData['msg'] = "arguments error";
	returnJSON($returnData, $callBack);
	return;
}
$fieldsArray = explode(",", $fields);
//var_dump($fieldsArray);
$noticeOrderInfo = pdo_fetch("SELECT * FROM " . tablename('hsh_tools_notice_order_list') . " WHERE openid = :openid AND notice_id = :notice_id AND state = 1 ORDER BY add_time DESC", array(':openid' => $openId, 'notice_id' => $noticeId));
//var_dump($noticeOrderInfo);
$fieldValue = json_decode(htmlspecialchars_decode($noticeOrderInfo['field_value']), true);
//var_dump($fieldValue);
$fieldValueCount = 0;
$dataValue=array();
foreach ($fieldsArray as $field) {
	if (isset($fieldValue[$field])) {
		$dataValue[$field] = $fieldValue[$field];
		$fieldValueCount++;
	}
}
if ($fieldValueCount > 0) {
	$returnData['state'] = 1;
	$returnData['count'] = $fieldValueCount;
	$returnData['data'] = $dataValue;
}
returnJSON($returnData, $callBack);
