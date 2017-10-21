<?php

defined('IN_IA') or exit('Access Denied');

global $_W, $_GPC;
load()->model('mc');

$ops = array('delete', 'deleteall', 'display');
$op = in_array(trim($_GPC['op']), $ops) ? trim($_GPC['op']) : 'display';

if ($op == 'delete') {
	$cid = intval($_GPC['cid']);
	pdo_delete('storex_comment', array('id' => $cid));
	$this->web_message('删除成功！', '', 0);
	exit();
}
if ($op == 'deleteall') {
	foreach ($_GPC['idArr'] as $k => $id) {
		$id = intval($id);
		pdo_delete('storex_comment', array('id' => $id));
	}
	$this->web_message('删除成功！', '', 0);
	exit();
}
if ($op == 'display') {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$id = intval($_GPC['id']);//商品id
	$store_type = intval($_GPC['store_type']);
	$table = gettablebytype($store_type);
	$store_base_id = intval($_GPC['store_base_id']);
	$comments = pdo_fetchall("SELECT c.*, g.title FROM " . tablename('storex_comment') . " AS c LEFT JOIN " .tablename($table). " AS g ON c.goodsid = g.id WHERE c.hotelid = :store_base_id AND c.goodsid = :id AND g.weid = :weid " . "LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(':store_base_id' => $store_base_id, ':id' => $id, 'weid' => $_W['uniacid']));
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('storex_comment') . " AS c LEFT JOIN " .tablename($table) . " AS g ON c.goodsid = g.id WHERE c.hotelid = :store_base_id AND c.goodsid = :id AND g.weid = :weid ", array(':store_base_id' => $store_base_id, ':id' => $id, 'weid' => $_W['uniacid']));
	if (!empty($comments)) {
		foreach ($comments as $k => $val) {
			$comments[$k]['createtime'] = date('Y-m-d :H:i:s', $val['createtime']);
			$uids[] = $val['uid'];
		}
		if (!empty($uids)) {
			$user_info = pdo_getall('mc_members', array('uid' => $uids), array('uid', 'avatar', 'nickname'), 'uid');
			if (!empty($user_info)) {
				foreach ($user_info as &$val) {
					if (!empty($val['avatar'])) {
						$val['avatar'] = tomedia($val['avatar']);
					}
				}
				unset($val);
			}
			foreach ($comments as $key => $infos) {
				$comments[$key]['user_info'] = array();
				if (!empty($user_info[$infos['uid']])) {
					$comments[$key]['user_info'] = $user_info[$infos['uid']];
				}
			}
		}
	}
	$pager = pagination($total, $pindex, $psize);
	include $this->template('goodscomment');
}