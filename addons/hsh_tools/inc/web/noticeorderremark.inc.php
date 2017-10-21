<?php

global $_W, $_GPC;
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'default';
$tableName = $this->modulename . '_notice_order_list';
$callback = !empty($_GPC['callback']) ? $_GPC['callback'] : 'callback';
$noticeOrderId = $_GPC['id'];
$noticeOrderInfo = pdo_fetch("SELECT * FROM ". tablename($tableName) ." WHERE id = :id AND weid = :weid AND state <> 0",array(":id" => $noticeOrderId ,":weid" => $_W['uniacid'] ));
if ($operation == "saveData") {
	$orderState = $_GPC['order_state'];
	$remarkInfo=$_GPC['remark_info'];
	$saveData = array(
		'remark' => $remarkInfo,
		'state' => $orderState,
	);
	$saveResult = pdo_update($tableName, $saveData, $noticeOrderInfo);
	$returnData =array();
	if($saveResult) {
		$returnData["state"] = 1;
		$returnData['msg'] ="修改成功";
	} else {
		$returnData["state"] = 0;
		$returnData['msg'] ="修改失败";
	}
	returnJSON($returnData,$callback);
} else {
	include $this->template('NoticeOrderRemark'); //默认模板
}

