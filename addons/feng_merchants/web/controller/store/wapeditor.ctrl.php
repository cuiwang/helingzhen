<?php
defined('IN_IA') or exit('Access Denied');
global $_W,$_GPC;
$do = !empty($_GPC['op']) ? $_GPC['op'] : 'widget';

if ($do == 'widget') {
	$file = !empty($_GPC['file']) ? $_GPC['file'] : exit('');
	$file = strtolower($file);
	template('wapeditor/'.$file);
} elseif ($do == 'goodsselecter') {
	$result = array();
	load()->model('attachment');
	$where = ' WHERE isshow = '. 1;
	$size = 10;
	$page = $_GPC['page'];
	$sqlTotal = pdo_sql_select_count_from('tg_goods') . $where;
	$sqlData = pdo_sql_select_all_from('tg_goods', array('id', 'gname', 'gimg', 'createtime', 'mprice', 'gprice', 'is_discount')) . $where . ' ORDER BY createtime DESC, id ASC ';
	$result['goods'] = pdo_pagination($sqlTotal, $sqlData, $params, 'id', $total, $page, $size);
	if (!empty($result['goods'])) {
		foreach ($result['goods'] as $k => &$v) {
			$v['thumbs'] = tomedia($v['gimg']);
			$v['createtime'] = date('Y-m-d H:i', $v['createtime']);
			$v['price'] = $v['is_discount'] == 1 ? $v['gprice'] : $v['mprice'];
			$v['name'] = cutstr($v['gname'], 10);
		}
		$result['pagetotal'] = ceil($total / $size);
		$result['pageindex'] = $page;
		$result['pager'] = pagination($total, $page, $size, '', array('before' => '2', 'after' => '3', 'ajaxcallback'=>'null'));
	}
	message($result, '', 'ajax');
} elseif ($do == 'cateselecter') {
	$category = pdo_fetch_many('goods_category', array(), array('id', 'parentid', 'name'), 'id', 'ORDER BY parentid, displayorder DESC, id ASC');
	foreach ($category as $index => $row) {
		if (!empty($row['parentid'])){
			$category[$row['parentid']]['children'][$row['id']] = $row;
			unset($category[$index]);
		}
	}
	message($category, '', 'ajax');
} elseif ($do == 'pagelist') {
	$result = array();
	$where = " WHERE type <= '2'";
	$size = 10;
	$page = $_GPC['page'];
	$sqlTotal = pdo_sql_select_count_from('page') . $where;
	$sqlData = pdo_sql_select_all_from('page', array('id', 'title', 'createtime')) . $where . ' ORDER BY id DESC ';
	$result['list'] = pdo_pagination($sqlTotal, $sqlData, $params, 'id', $total, $page, $size);
	if (!empty($result['list'])) {
		foreach ($result['list'] as $k => &$v) {
			$v['createtime'] = date('Y-m-d H:i', $v['createtime']);
		}
		$result['pager'] = pagination($total, $page, $size, '', array('before' => '2', 'after' => '3', 'ajaxcallback'=>'null'));
	}
	message($result, '', 'ajax');
} elseif ($do == 'newslist') {
	$result = array();
	$where = " WHERE pid = '0'";
	$size = 10;
	$page = $_GPC['page'];
	$sqlTotal = pdo_sql_select_count_from('reply_news') . $where;
	$sqlData = pdo_sql_select_all_from('reply_news', array('id', 'title', 'createtime')) . $where . ' ORDER BY id DESC ';
	$result['list'] = pdo_pagination($sqlTotal, $sqlData, $params, 'id', $total, $page, $size);
	if (!empty($result['list'])) {
		foreach ($result['list'] as $k => &$v) {
			$v['createtime'] = date('Y-m-d H:i', $v['createtime']);
		}
		$result['pager'] = pagination($total, $page, $size, '', array('before' => '2', 'after' => '3', 'ajaxcallback'=>'null'));
	}
	message($result, '', 'ajax');
}
