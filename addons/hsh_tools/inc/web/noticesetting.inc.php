<?php

global $_W, $_GPC;
$tableName = $this->modulename . '_notice_setting';
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == "post") { //编辑页面内容
	$id = intval($_GPC['id']);
	load()->func('tpl');
	if (checksubmit('submit')) {
		if (empty($_GPC['title'])) {
			message('抱歉，请输入项目名称！');
		}
		$data = array(
			'weid' => $_W['uniacid'],
			'title' => $_GPC['title'],
			'field_setting' => $_GPC['field_setting'],
			'options' => $_GPC['options'],
			'template_name' => $_GPC['template_name'],
			'notice_list' => json_encode($_GPC['notice_list']),
			'notice_option' => $_GPC['notice_option'],
			'message_script' => $_GPC['message_script'],
			'sms_template_id' => $_GPC['sms_template_id'],
			'foot_info' => $_GPC['foot_info'],
			'success_hint' => $_GPC['success_hint'],
			'opening_hour_begin' => intval($_GPC['opening_hour_begin']),
			'opening_hour_end' => intval($_GPC['opening_hour_end']),
			'closing_hint' => $_GPC['closing_hint'],
			'opening_time' => strtotime($_GPC['opening_time']),
			'pause_hint' => $_GPC['pause_hint'],
			'state' => 1,
		);
		//$data = array_map("htmlspecialchars_decode",$data);
		if (empty($data['weid'])) {
			message('非法参数');
		}
		if (!empty($id)) {
			pdo_update($tableName, $data, array('id' => $id));
		} else {
			$data['notice_list'] =  json_encode(array("0"));
			pdo_insert($tableName, $data);
			$id = pdo_insertid();
		}
		message('更新预约项目成功！', $this->createWebUrl('NoticeSetting', array('op' => 'display')), 'success');
		die();
	}
	if (!empty($id)) {
		$singleNoticeSetting = pdo_fetch("SELECT * FROM " . tablename($tableName) . " WHERE id = '$id'");
		$singleNoticeSetting = array_map("ihtmlspecialchars", $singleNoticeSetting);
		$noticeOption = json_decode(htmlspecialchars_decode($singleNoticeSetting['notice_option']), true);
		$noticeList = json_decode(htmlspecialchars_decode($singleNoticeSetting['notice_list']), true);
		if (is_array($noticeList)) {
			foreach ($noticeList as $key) {
				if($noticeOption[$key]) {
					$noticeOption[$key]['checked'] = 1;
				}
			}
		}
	} else {
		$singleNoticeSetting = array(
		);
	}
} else if ($operation == "delete") { //删除记录操作
	$id = intval($_GPC['id']);
	$singleNoticeSetting = pdo_fetch("SELECT id FROM " . tablename($tableName) . " WHERE id = '$id'");
	if (empty($singleNoticeSetting)) {
		message('抱歉，预约项目不存在或是已经被删除！', $this->createWebUrl('NoticeSetting', array('op' => 'display')), 'error');
	}
	pdo_delete($tableName, array('id' => $id));
	message(' 预约项目删除成功！', $this->createWebUrl('NoticeSetting', array('op' => 'display')), 'success');
	die();
} else {  //默认页面 列表页面
	$pageindex = max(intval($_GPC['page']), 1); // 当前页码
	$pagesize = 10; // 设置分页大小
	$where = " WHERE weid = '{$_W['uniacid']}' ";
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($tableName) . $where);
	$noticeSetting = pdo_fetchall("SELECT * FROM " . tablename($tableName) . $where . "  ORDER BY id DESC LIMIT " . ($pageindex - 1) * $pagesize . ",$pagesize");
	foreach ($noticeSetting as $key => $val) {
		$noticeSetting[$key]['app_url'] = $_W['siteroot'] . "app/" . $this->createMobileUrl('notice_form', array('notice_id' => $val['id']));
		$noticeSetting[$key]['notice_person'] = getNoticeList($noticeSetting[$key]['notice_list'], $noticeSetting[$key]);
	}
	//$noticeSetting = array_map("ihtmlspecialchars", $noticeSetting);
	$pager = pagination($total, $pageindex, $pagesize);
}
include $this->template('NoticeSetting'); //默认模板
function getNoticeList($jsonStr, $noticeSetting) {
	$str = "";
	$noticeIdList = json_decode(htmlspecialchars_decode($jsonStr));
	if (is_array($noticeIdList)) {
		$valueData = json_decode(htmlspecialchars_decode($noticeSetting['notice_option']), true);
		foreach ($noticeIdList as $index) {
			$str .="<span class='label label-info' style='margin:.1em;'>" . $valueData[$index]['name'] . "</span>";
		}
	}
	return $str;
}
