<?php
/**
 * [WeEngine System] Copyright (c) 2015 012WZ.COM
 
 */
defined('IN_IA') or exit('Access Denied');
$dos = array( 'detail', 'list');
$do = in_array($do, $dos) ? $do : 'list';
load()->model('article');
$links = article_link_home();
if($do == 'detail') {
	$id = intval($_GPC['id']);
	$case = article_case_info($id);
	$catecases = article_catecase('case');
	if(is_error($case)) {
		message('案例不存在或已删除', referer(), 'error');
	}
	$_W['page']['title'] = $case['title'] . '-案例列表';
}

if($do == 'list') {
	$catecases = article_catecase('case');
	$catecases[0] = array('title' => '所有案例');
	$cateid = intval($_GPC['cateid']);
	$_W['page']['title'] = $catecases[$cateid]['title'] . '-案例列表';

	$filter = array('cateid' => $cateid);
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$cases = article_case_all($filter, $pindex, $psize);
	$total = intval($cases['total']);
	$data = $cases['case'];
	$pager = pagination($total, $pindex, $psize);
}

template('article/case-show');
