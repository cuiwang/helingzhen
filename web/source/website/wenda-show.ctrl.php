<?php
/**
 * [WECHAT 2017]
 
 */
defined('IN_IA') or exit('Access Denied');
$dos = array( 'detail', 'list');
$do = in_array($do, $dos) ? $do : 'list';
load()->model('article');
$links = article_link_home();
$modules = pdo_fetchall('SELECT * FROM ' . tablename('modules') . ' WHERE type <> :type', array(':type' => 'system'), 'mid');
if($do == 'detail') {
	$id = intval($_GPC['id']);
	$categroys = article_categorys('wenda');
	$wenda = article_wenda_info($id);
	if(is_error($wenda)) {
		message('问题不存在或已删除', referer(), 'error');
	}
	$_W['page']['title'] = $wenda['title'];
	$cateid = intval($_GPC['cateid']);
	$filter = array('cateid' => $cateid);
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$wendas = article_wenda_all($psize);
	$total = intval($wendas['total']);
	$data = $wendas['wendas'];
}

if($do == 'list') {
	$_W['page']['title'] = '教程列表';
	$condition = ' WHERE 1';
	$cateid = intval($_GPC['cateid']);
	$modid = intval($_GPC['modid']);
	$createtime = intval($_GPC['createtime']);
	$title = trim($_GPC['title']);

	$params = array();
	if($cateid > 0) {
		$condition .= ' AND cateid = :cateid';
		$params[':cateid'] = $cateid;
	}
	if($modid > 0) {
		$condition .= ' AND modid = :modid';
		$params[':modid'] = $modid;
	}
	if($createtime > 0) {
		$condition .= ' AND createtime >= :createtime';
		$params[':createtime'] = strtotime("-{$createtime} days");
	}
	if(!empty($title)) {
		$condition .= " AND title LIKE :title";
		$params[':title'] = "%{$title}%";
	}

	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$sql = 'SELECT * FROM ' . tablename('article_wenda') . $condition . " ORDER BY displayorder DESC LIMIT " . ($pindex - 1) * $psize .',' .$psize;
	$wenda = pdo_fetchall($sql, $params);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('article_wenda') . $condition, $params);
	$pager = pagination($total, $pindex, $psize);
}

template('website/wenda/wenda-show');
