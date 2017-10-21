<?php

defined('IN_IA') or exit('Access Denied');

global $_W, $_GPC;

load()->model('reply');
load()->model('module');
mload()->model('activity');

$ops = array('display', 'post', 'delete', 'stat_trend');
$op = in_array(trim($_GPC['op']), $ops) ? trim($_GPC['op']) : 'display';

activity_get_coupon_type();

if ($op == 'display') {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$cids = $parentcates = $list =  array();
	$types = array('', '等价', '包含', '正则表达式匹配', '直接接管');

	$condition = ' uniacid = :uniacid AND `module`=:module ';
	$params = array();
	$params[':uniacid'] = $_W['uniacid'];
	$params[':module'] = 'wxcard';
	$status = isset($_GPC['status']) ? intval($_GPC['status']) : -1;
	if ($status != -1) {
		$condition .= " AND status = '{$status}'";
	}
	if (isset($_GPC['keyword'])) {
		$condition .= ' AND `name` LIKE :keyword';
		$params[':keyword'] = "%{$_GPC['keyword']}%";
	}
	$replies = reply_search($condition, $params, $pindex, $psize, $total);
	$pager = pagination($total, $pindex, $psize);
	if (!empty($replies)) {
		foreach ($replies as &$item) {
			$condition = '`rid`=:rid';
			$params = array();
			$params[':rid'] = $item['id'];
			$item['keywords'] = reply_keywords_search($condition, $params);
			$entries = module_entries('wxcard', array('rule'),$item['id']);
			if (!empty($entries)) {
				$item['options'] = $entries['rule'];
			}
		}
		unset($item);
	}
}

if ($op == 'post') {
	$rid = intval($_GPC['rid']);
	if (!empty($rid)) {
		$replies = pdo_getall('wxcard_reply', array('rid' => $rid));
	}
	if ($_W['isajax'] && $_W['ispost']) {
		/*检测规则是否已经存在*/
		$result = pdo_getall('rule_keyword', array('uniacid' => intval($_W['uniacid']), 'content' => trim($_GPC['keyword'])));
		if (!empty($result)) {
			$keywords = array();
			foreach ($result as $reply) {
				$keywords[] = $reply['rid'];
			}
			$rules = pdo_getall('rule', array('id' => $keywords));
			exit(@json_encode($rules));
		}
		exit('success');
	}
	$rid = intval($_GPC['rid']);
	if (!empty($rid)) {
		$reply = reply_single($rid);
		if (empty($reply) || $reply['uniacid'] != $_W['uniacid']) {
			message('抱歉，您操作的规则不在存或是已经被删除！', $this->createWebUrl('wxcardreply', array('op' => 'display')), 'error');
		}
		foreach ($reply['keywords'] as &$kw) {
			$kw = array_elements(array('type', 'content'), $kw);
		}
		unset($kw);
	}
	
	//获取卡券
	$condition = ' WHERE uniacid = :uniacid AND is_display = 1 AND status = 3 AND source = :source AND quantity > 0';
	$param = array(
		':uniacid' => $_W['uniacid'],
		':source' => COUPON_TYPE,
	);
	$pindex = max(1, intval($_GPC['page']));
	$psize = 15;
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM '. tablename('storex_coupon') . $condition, $param);
	$storex_coupon = pdo_fetchall('SELECT * FROM ' . tablename('storex_coupon') . $condition . ' ORDER BY id DESC LIMIT ' . ($pindex - 1) * $psize . ', ' . $psize, $param, 'id');
	if (!empty($storex_coupon)) {
		foreach ($storex_coupon as $key => &$value) {
			$value['date_info'] = iunserializer($value['date_info']);
			$value['media_id'] = $value['card_id'];
			$value['logo_url'] = url('utility/wxcode/image', array('attach' => $value['logo_url']));
			$value['ctype'] = $value['type'];
			$value['extra'] = iunserializer($value['extra']);
			if ($value['type'] == '1') {
				$value['extra']['discount'] = $value['extra']['discount'] * 0.1;
			} elseif ($value['type'] == '2') {
				$value['extra']['reduce_cost'] = $value['extra']['reduce_cost'] * 0.01;
			}
			if ($value['date_info']['time_type'] == '1') {
				$starttime = strtotime(str_replace('.', '-', $value['date_info']['time_limit_start']));
				$endtime = strtotime(str_replace('.', '-', $value['date_info']['time_limit_end']));
				if ($starttime > strtotime(date('Y-m-d')) || $endtime < strtotime(date('Y-m-d'))) {
					unset($storex_coupon[$key]);
				}
			}
		}
		unset($value);
	}
	if (checksubmit('submit')) {
		if (empty($_GPC['name'])) {
			message('必须填写回复规则名称.');
		}
		$keywords = @json_decode(htmlspecialchars_decode($_GPC['keywords']), true);
		if (empty($keywords)) {
			message('必须填写有效的触发关键字.');
		}
		$rule = array(
			'uniacid' => $_W['uniacid'],
			'name' => $_GPC['name'],
			'module' => 'wxcard',
			'status' => intval($_GPC['status']),
			'displayorder' => intval($_GPC['displayorder_rule']),
		);
		if (!empty($_GPC['istop'])) {
			$rule['displayorder'] = 255;
		} else {
			$rule['displayorder'] = range_limit($rule['displayorder'], 0, 254);
		}
		$module = WeUtility::createModule('wxcard');
	
		if (empty($module)) {
			message('抱歉，模块不存在！');
		}
		$msg = $module->fieldsFormValidate();
	
		if (is_string($msg) && trim($msg) != '') {
			message($msg);
		}
		if (!empty($rid)) {
			$result = pdo_update('rule', $rule, array('id' => $rid));
		} else {
			$result = pdo_insert('rule', $rule);
			$rid = pdo_insertid();
		}
		if (!empty($rid)) {
			//更新，添加，删除关键字
			pdo_delete('rule_keyword', array('rid' => $rid, 'uniacid' => intval($_W['uniacid'])));
				
			$rowtpl = array(
				'rid' => $rid,
				'uniacid' => $_W['uniacid'],
				'module' => $rule['module'],
				'status' => $rule['status'],
				'displayorder' => $rule['displayorder'],
			);
			foreach ($keywords as $kw) {
				$krow = $rowtpl;
				$krow['type'] = range_limit($kw['type'], 1, 4);
				$krow['content'] = $kw['content'];
				pdo_insert('rule_keyword', $krow);
			}
			$rowtpl['incontent'] = $_GPC['incontent'];
			$module->fieldsFormSubmit($rid);
			message('回复规则保存成功！', $this->createWebUrl('wxcardreply', array('op' => 'display', 'rid' => $rid)));
		} else {
			message('回复规则保存失败, 请联系网站管理员！');
		}
	}
}

if ($op == 'delete') {
	$rids = $_GPC['rid'];
	if (!is_array($rids)) {
		$rids = array($rids);
	}
	if (empty($rids)) {
		message('非法访问.');
	}
	foreach ($rids as $rid) {
		$rid = intval($rid);
		$reply = reply_single($rid);
		if (empty($reply) || $reply['uniacid'] != $_W['uniacid']) {
			message('抱歉，您操作的规则不在存或是已经被删除！', referer(), 'error');
		}
		//删除回复，关键字及规则
		if (pdo_delete('rule', array('id' => $rid))) {
			pdo_delete('rule_keyword', array('rid' => $rid));
			//删除统计相关数据
			pdo_delete('stat_rule', array('rid' => $rid));
			pdo_delete('stat_keyword', array('rid' => $rid));
			//调用模块中的删除
			$module = WeUtility::createModule($reply['module']);
			if (method_exists($module, 'ruleDeleted')) {
				$module->ruleDeleted($rid);
			}
		}
	}
	message('规则操作成功！', referer(), 'success');
}

if ($op == 'stat_trend') {
	$_W['page']['title'] = '关键指标详解 - 数据统计';
	$id = intval($_GPC['id']);
	$starttime = empty($_GPC['time']['start']) ? strtotime(date('Y-m-d')) - 7 * 86400 : strtotime($_GPC['time']['start']);
	$endtime = empty($_GPC['time']['end']) ? TIMESTAMP : strtotime($_GPC['time']['end']) + 86399;
	$list = pdo_fetchall("SELECT createtime, hit FROM " . tablename('stat_rule') . " WHERE uniacid = '{$_W['uniacid']}' AND rid = :rid AND createtime >= :createtime AND createtime <= :endtime ORDER BY createtime ASC", array(':rid' => $id, ':createtime' => $starttime, ':endtime' => $endtime));
	$day = $hit = array();
	if (!empty($list)) {
		foreach ($list as $row) {
			$day[] = date('m-d', $row['createtime']);
			$hit[] = intval($row['hit']);
		}
	}
	/*添加规则默认数据*/
	for ($i = 0; $i = count($hit) < 2; $i++) {
		$day[] = date('m-d', $endtime);
		$hit[] = $day[$i] == date('m-d', $endtime) ? $hit[0] : '0';
	}
	$list = pdo_fetchall("SELECT createtime, hit, rid, kid FROM " . tablename('stat_keyword') . " WHERE uniacid = '{$_W['uniacid']}' AND rid = :rid AND createtime >= :createtime AND createtime <= :endtime ORDER BY createtime ASC", array(':rid' => $id, ':createtime' => $starttime, ':endtime' => $endtime));
	if (!empty($list)) {
		foreach ($list as $row) {
			$keywords[$row['kid']]['hit'][] = $row['hit'];
			$keywords[$row['kid']]['day'][] = date('m-d', $row['createtime']);
		}
		foreach ($keywords as &$value) {
			/*添加所属关键字默认数据*/
			if (count($value['hit']) < 2) {
				$value['hit'][] = $value['day'][0] == date('m-d', $endtime) ? $value['hit'][0] : '0';
				$value['day'][] = date('m-d', $endtime);
			}
		}
		unset($value);
		$keywordnames = pdo_getall('rule_keyword', array('id' => array_keys($keywords)), array('content', 'id'), 'id');
	}
}
include $this->template('wxcardreply');