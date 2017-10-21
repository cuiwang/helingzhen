<?php

global $_W, $_GPC;
load()->func('tpl');
$ZH_Array = array();
$toolsModule = pdo_fetch("SELECT * FROM " . tablename('uni_account_modules') . " WHERE module = 'hsh_tools' and uniacid= :weid", array(":weid" => $_W['uniacid']));
$toolsConfig = iunserializer($toolsModule['settings']);
if ($toolsConfig['specialQrCodeCount'] != "") {
	$ZH_Array = json_decode(htmlspecialchars_decode($toolsConfig['specialQrCodeCount']), true);
}
$quickLink = array(
	"统计查询" => $this->createMobileUrl("fanscount", array("type" => "ZHCount"), true),
	"详细查询" => $this->createMobileUrl("fanscount", array("type" => "Query"), true),
	"增粉报表" => $this->createMobileUrl("fanscount", array("type" => "Other"), true),
);

$queryType = $_GPC['type'];
if ($queryType == '') {
	$queryType = "ZHCount";
}

$time_begin = $_GPC["tb"];
if ($time_begin == "") {
	$time_begin = date('Y-m-d', mktime(0, 0, 0));
}
$time_begin = strtotime($time_begin);

$time_end = $_GPC["te"];
if ($time_end == "") {
	$time_end = date('Y-m-d', mktime(0, 0, 0));
}
$time_end = strtotime($time_end) + 60 * 60 * 24 - 1;
$returnArray = array();

$personNames = $ZH_Array;
if (empty($personNames)) {
	$personNames = array();
}
switch ($queryType) {
	case "ZHCount":
		foreach ($personNames as $key) {
			$itemAdd = array();
			$itemAdd["name"] = $key;
			$codeIdList = pdo_fetchall("SELECT qrcid FROM " . tablename("qrcode") . " WHERE name like :keyword", array(":keyword" => $key . "%"));
			$codeIdArray = array();
			foreach ($codeIdList as $k => $v) {
				$codeIdArray[] = $v['qrcid'];
			}
			$itemAdd["id_list"] = implode(",", $codeIdArray);
			$countAll = pdo_fetchcolumn("SELECT count(*) FROM " . tablename("hsh_tools_interaction_time") . " WHERE scene_id in (" . implode(",", $codeIdArray) . ")");
			$itemAdd["count_all"] = $countAll;
			$countToday = pdo_fetchcolumn("SELECT count(*) FROM " . tablename("hsh_tools_interaction_time") . " WHERE scene_id in (" . implode(",", $codeIdArray) . ") and add_time>=$time_begin and add_time<=$time_end");
			$itemAdd["count_today"] = $countToday;
			$returnArray[] = $itemAdd;
		}
		$time_begin = date('Y-m-d', $time_begin);
		$time_end = date('Y-m-d', $time_end);
		include $this->template('Count_index'); //默认模板
		break;
	case "Other":
		/* 排除纵横 */
		$whereStr = "";
		foreach ($personNames as $key) {
			if ($whereStr == "") {
				$whereStr = "name not like '$key%'";
			} else {
				$whereStr.=" and name not like '$key%'";
			}
		}
		$qrCodeArray = pdo_fetchall("SELECT * FROM " . tablename("qrcode") . " WHERE " . $whereStr . ($whereStr == "" ? "" : " and ") . " uniacid = " . $_W['uniacid']);
		$returnArray = array();
		foreach ($qrCodeArray as $val) {
			$itemAdd = array();
			$itemAdd["name"] = $val['name'];
			$countAll = pdo_fetchcolumn("SELECT count(*) FROM " . tablename("hsh_tools_interaction_time") . " WHERE scene_id = " . $val['qrcid']);
			$itemAdd["count_all"] = $countAll;
			$countToday = pdo_fetchcolumn("SELECT count(*) FROM " . tablename("hsh_tools_interaction_time") . " WHERE scene_id = '" . $val['qrcid'] . "'  and add_time>=$time_begin and add_time<=$time_end");
			$itemAdd["count_today"] = $countToday;

			$returnArray[] = $itemAdd;
		}
		$time_begin = date('Y-m-d', $time_begin);
		$time_end = date('Y-m-d', $time_end);
		include $this->template('Count_report'); //默认模板
		break;
	case "Query":
		/* 排除纵横 */
		$returnArray = array();
		$queryName = $_GPC["queryname"];
		if ($queryName != "") {
			$codeIdList = pdo_fetchall("SELECT * FROM " . tablename("qrcode") . " WHERE name like '$queryName%' and uniacid =" . $_W['uniacid']);
			foreach ($codeIdList as $val) {
				$itemAdd = array();
				$itemAdd["name"] = $val['name'];
				$itemAdd["id"] = $val['qrcid'];
				$itemAdd["count_all"] = pdo_fetchcolumn("SELECT count(*) FROM " . tablename("hsh_tools_interaction_time") . " WHERE scene_id = " . $val['qrcid']);
				$returnArray[] = $itemAdd;
			}
		}
		include $this->template('Count_showall'); //默认模板
		break;
}
?>