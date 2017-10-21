<?php
/**
 * [WECHAT 2017]
 
 */
defined('IN_IA') or exit('Access Denied');
$dos = array( 'detail', 'list');
$do = in_array($do, $dos) ? $do : 'list';
load()->model('article');
$links = article_link_home();
if($do == 'detail') {
	$id = intval($_GPC['id']);
	$link = article_link_info($id);
	if(is_error($link)) {
		message('链接不存在或已删除', referer(), 'error');
	}
	$_W['page']['title'] = $link['title'] . '-链接列表';
}

if($do == 'list') {
	$cateid = intval($_GPC['cateid']);
	$_W['page']['title'] = $categroys[$cateid]['title'] . '-链接列表';

	$filter = array('cateid' => $cateid);
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$link = article_link_all($filter, $pindex, $psize);
	$total = intval($link['total']);
	$data = $link['link'];
	$pager = pagination($total, $pindex, $psize);
}

template('article/link-show');
