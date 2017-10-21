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
	$product = article_product_info($id);
	if(is_error($product)) {
		message('产品不存在或已删除', referer(), 'error');
	}
	$_W['page']['title'] = $product['title'] . '-产品列表';
}

if($do == 'list') {
	$categroys = article_categorys('product');
	$categroys[0] = array('title' => '所有产品');
	$cateid = intval($_GPC['cateid']);
	$_W['page']['title'] = $categroys[$cateid]['title'] . '-产品列表';

	$filter = array('cateid' => $cateid);
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$product = article_product_all($filter, $pindex, $psize);
	$total = intval($product['total']);
	$data = $product['product'];
	$pager = pagination($total, $pindex, $psize);
}

template('article/product-show');
