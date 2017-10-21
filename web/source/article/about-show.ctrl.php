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
	$about = article_about_info($id);
	if(is_error($about)) {
		message('文章不存在或已删除', referer(), 'error');
	}
	$_W['page']['title'] = $about['title'] . '-文章列表';
	$cateid = intval($_GPC['cateid']);
	$filter = array('cateid' => $cateid);
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$abouts = article_about_all($psize);
	$total = intval($abouts['total']);
	$data = $abouts['abouts'];
}

if($do == 'list') {
	$categroys = article_categorys('about');
	$categroys[0] = array('title' => '所有文章');
	$cateid = intval($_GPC['cateid']);
	$_W['page']['title'] = $categroys[$cateid]['title'] . '-文章列表';

	$filter = array('cateid' => $cateid);
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$abouts = article_about_all($filter, $pindex, $psize);
	$total = intval($abouts['total']);
	$data = $abouts['abouts'];
	$pager = pagination($total, $pindex, $psize);
}

template('article/about-show');
