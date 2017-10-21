<?php

global $_W, $_GPC;
$tableName = $this->modulename . '_notice_order_list';
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$nid = intval($_GPC['nid']);
if ($operation == "delete") { //删除记录操作
	$id = intval($_GPC['id']);
	$singleNoticeOrder = pdo_fetch("SELECT id FROM " . tablename($tableName) . " WHERE id = '$id'");
	if (empty($singleNoticeOrder)) {
		message('抱歉，预约订单不存在或是已经被删除！', $this->createWebUrl('NoticeSetting', array('op' => 'display')), 'error');
	}
	//pdo_delete($tableName, array('state' => -1));
	pdo_update($tableName, array('state' => 0), $singleNoticeOrder);
	message(' 预约订单删除成功！', $this->createWebUrl('NoticeOrders', array('op' => 'display', 'nid' => $nid)), 'success');
	die();
} else {  //默认页面 列表页面
	$pageindex = max(intval($_GPC['page']), 1); // 当前页码
	$pagesize = 10; // 设置分页大小
	$where = " WHERE weid = :weid and notice_id = :nid  and state <> 0";
	$params = array(":weid" => $_W['uniacid'], ":nid" => $nid);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($tableName) . $where, $params);
	$noticeOrders = pdo_fetchall("SELECT * FROM " . tablename($tableName) . $where . "  ORDER BY id DESC LIMIT " . ($pageindex - 1) * $pagesize . ",$pagesize", $params);
	$noticeOrders = array_map("ihtmlspecialchars", $noticeOrders);

	/* 结果处理加工 */
	$noticeSetting = pdo_fetch("SELECT * FROM " . tablename($this->modulename . '_notice_setting') . "WHERE weid = :weid and id = :nid  ORDER BY id DESC ", $params);
	$noticeFieldSetting = json_decode(htmlspecialchars_decode($noticeSetting['field_setting']), true);
	foreach ($noticeOrders as $index => $value) {
		$valueData = json_decode(htmlspecialchars_decode($value['field_value']), true);
		$fieldValueStr = "";
		foreach ($noticeFieldSetting as $field => $setting) {
			if ($setting['list_show'] == "1") {
				if ($fieldValueStr == "") {
					$fieldValueStr .= "【" . $setting['prompt'] . "】" . $valueData[$field];
				} else {
					$fieldValueStr .= "，【" . $setting['prompt'] . "】" . $valueData[$field];
				}
			}
		}
		$noticeOrders[$index]['fieldValueStr'] = $fieldValueStr;
		$noticeOrders[$index]['notice_person'] = getNoticeList($value['notice_list'], $noticeSetting);
		$noticeOrders[$index]['state_str'] = getStateStr($value['state']);
	}


	$pager = pagination($total, $pageindex, $pagesize);
}
include $this->template('NoticeOrders'); //默认模板

function getNoticeList($jsonStr, $noticeSetting) {
	//notice_option
	$noticeIdList = json_decode(htmlspecialchars_decode($jsonStr));
	$valueData = json_decode(htmlspecialchars_decode($noticeSetting['notice_option']), true);
	foreach ($noticeIdList as $index) {
		$str .="<span class='label label-info' style='margin:.1em;'>" . $valueData[$index]['name'] . "</span>";
	}
	return $str;
}

function getStateStr($state) {
	$returnStr = "";
	switch ($state) {
		case 1:$returnStr = "已确认";
			break;
		case 2:$returnStr = "已完成";
			break;
		case -1:$returnStr = "已取消";
			break;
	}
	return $returnStr;
}
