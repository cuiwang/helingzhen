<?php
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
defined('IN_IA') or exit('Access Denied');

function article_categorys($type = 'news') {
	$categorys = pdo_fetchall('SELECT * FROM ' . tablename('article_category') . ' WHERE type = :type ORDER BY displayorder DESC', array(':type' => $type), 'id');
	return $categorys;
}
function article_catecase($type = 'case') {
	$catecases = pdo_fetchall('SELECT * FROM ' . tablename('article_catecase') . ' WHERE type = :type ORDER BY displayorder DESC', array(':type' => $type), 'id');
	return $catecases;
}

function article_news_info($id) {
	$id = intval($id);
	$news = pdo_fetch('SELECT * FROM ' . tablename('article_news') . ' WHERE id = :id', array(':id' => $id));
	$news['info'] = cutstr(strip_tags($news['content']), 88);
	if(empty($news)) {
		return error(-1, '新闻不存在或已经删除');
	}else {
		pdo_update('article_news',array('click' => $news['click']+1),array('id' => $id));
	}
	return $news;
}

function article_notice_info($id) {
	$id = intval($id);
	$news = pdo_fetch('SELECT * FROM ' . tablename('article_notice') . ' WHERE id = :id', array(':id' => $id));
	$news['info'] = cutstr(strip_tags($news['content']), 88);
	if(empty($news)) {
		return error(-1, '公告不存在或已经删除');
	}
	return $news;
}

function article_case_info($id) {
	$id = intval($id);
	$case = pdo_fetch('SELECT * FROM ' . tablename('article_case') . ' WHERE id = :id', array(':id' => $id));
	$case['info'] = cutstr(strip_tags($case['content']), 88);
	if(empty($case)) {
		return error(-1, '案例不存在或已经删除');
	}else {
		pdo_update('article_case',array('click' => $case['click']+1),array('id' => $id));
	}
	return $case;
}

function article_link_info($id) {
	$id = intval($id);
	$link = pdo_fetch('SELECT * FROM ' . tablename('article_link') . ' WHERE id = :id', array(':id' => $id));
	if(empty($link)) {
		return error(-1, '链接不存在或已经删除');
	}
	return $link;
}
function article_about_info($id) {
	$id = intval($id);
	$about = pdo_fetch('SELECT * FROM ' . tablename('article_about') . ' WHERE id = :id', array(':id' => $id));
	$about['info'] = cutstr(strip_tags($about['content']), 88);
	if(empty($about)) {
		return error(-1, '文章不存在或已经删除');
	}else {
		pdo_update('article_about',array('click' => $about['click']+1),array('id' => $id));
	}
	return $about;
}
function article_agent_info($id) {
	$id = intval($id);
	$agent = pdo_fetch('SELECT * FROM ' . tablename('article_agent') . ' WHERE id = :id', array(':id' => $id));
	$agent['info'] = cutstr(strip_tags($agent['content']), 88);
	if(empty($agent)) {
		return error(-1, '公司不存在或已经删除');
	}else {
		pdo_update('article_agent',array('click' => $agent['click']+1),array('id' => $id));
	}
	return $agent;
}
function article_wenda_info($id) {
	$id = intval($id);
	$wenda = pdo_fetch('SELECT * FROM ' . tablename('article_wenda') . ' WHERE id = :id', array(':id' => $id));
	$wenda['info'] = cutstr(strip_tags($wenda['content']), 88);
	if(empty($wenda)) {
		return error(-1, '问题不存在或已经删除');
	}else {
		pdo_update('article_agent',array('click' => $wenda['click']+1),array('id' => $id));
	}
	return $wenda;
}
function article_product_info($id) {
	$id = intval($id);
	$product = pdo_fetch('SELECT * FROM ' . tablename('article_product') . ' WHERE id = :id', array(':id' => $id));
	$product['info'] = cutstr(strip_tags($product['content']), 88);
	if(empty($product)) {
		return error(-1, '产品不存在或已经删除');
	}else {
		pdo_update('article_product',array('click' => $product['click']+1),array('id' => $id));
	}
	return $product;
}
function article_news_home($limit = 10) {
	$limit = intval($limit);
	$news = pdo_fetchall('SELECT * FROM ' . tablename('article_news') . ' WHERE is_display = 1 AND is_show_home = 1 ORDER BY displayorder DESC,id DESC LIMIT ' . $limit, array(), 'id');
	return $news;
}

function article_notice_home($limit = 10) {
	$limit = intval($limit);
	$notice = pdo_fetchall('SELECT * FROM ' . tablename('article_notice') . ' WHERE is_display = 1 AND is_show_home = 1 ORDER BY displayorder DESC,id DESC LIMIT ' . $limit, array(), 'id');
	return $notice;
}
function article_case_home($limit = 9) {
	$limit = intval($limit);
	$case = pdo_fetchall('SELECT * FROM ' . tablename('article_case') . ' WHERE is_display = 1 AND is_show_home = 1 ORDER BY displayorder DESC,id DESC LIMIT ' . $limit, array(), 'id');
	return $case;
}
function article_case2_home($limit = 9) {
	$limit = intval($limit);
	$case2 = pdo_fetchall('SELECT * FROM ' . tablename('article_case') . ' WHERE id > 10 AND is_display = 1 AND is_show_home = 1 ORDER BY displayorder DESC,id DESC LIMIT ' . $limit, array(), 'id');
	return $case2;
}
function article_case3_home($limit = 9) {
	$limit = intval($limit);
	$case3 = pdo_fetchall('SELECT * FROM ' . tablename('article_case') . ' WHERE id > 20 AND is_display = 1 AND is_show_home = 1 ORDER BY displayorder DESC,id DESC LIMIT ' . $limit, array(), 'id');
	return $case3;
}
function article_link_home($limit = 30) {
	$limit = intval($limit);
	$links = pdo_fetchall('SELECT * FROM ' . tablename('article_link') . ' WHERE is_display = 1 AND is_show_home = 1 ORDER BY displayorder DESC,id DESC LIMIT ' . $limit, array(), 'id');
	return $links;
}
function article_about_home($limit = 30) {
	$limit = intval($limit);
	$about = pdo_fetchall('SELECT * FROM ' . tablename('article_about') . ' WHERE is_display = 1 AND is_show_home = 1 ORDER BY displayorder DESC,id DESC LIMIT ' . $limit, array(), 'id');
	return $about;
}
function article_agent_home($limit = 30) {
	$limit = intval($limit);
	$agent = pdo_fetchall('SELECT * FROM ' . tablename('article_agent') . ' WHERE is_display = 1 AND is_show_home = 1 ORDER BY displayorder DESC,id DESC LIMIT ' . $limit, array(), 'id');
	return $agent;
}
function article_product_home($limit = 9) {
	$limit = intval($limit);
	$product = pdo_fetchall('SELECT * FROM ' . tablename('article_product') . ' WHERE is_display = 1 AND is_show_home = 1 ORDER BY displayorder DESC,id DESC LIMIT ' . $limit, array(), 'id');
	return $product;
}
function article_wenda_home($limit = 30) {
	$limit = intval($limit);
	$wenda = pdo_fetchall('SELECT * FROM ' . tablename('article_wenda') . ' WHERE is_display = 1 AND is_show_home = 1 ORDER BY displayorder DESC,id DESC LIMIT ' . $limit, array(), 'id');
	return $wenda;
}
function article_news_all($filter = array(), $pindex = 1, $psize = 10) {
	$condition = ' WHERE is_display = 1';
	$params = array();
	if(!empty($filter['title'])) {
		$condition .= ' AND titie LIKE :title';
		$params[':title'] = "%{$filter['title']}%";
	}
	if($filter['cateid'] > 0) {
		$condition .= ' AND cateid = :cateid';
		$params[':cateid'] = $filter['cateid'];
	}
	$limit = ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('article_news') . $condition, $params);
	$news = pdo_fetchall('SELECT * FROM ' . tablename('article_news') . $condition . ' ORDER BY displayorder DESC ' . $limit, $params, 'id');
	return array('total' => $total, 'news' => $news);
}

function article_notice_all($filter = array(), $pindex = 1, $psize = 10) {
	$condition = ' WHERE is_display = 1';
	$params = array();
	if(!empty($filter['title'])) {
		$condition .= ' AND titie LIKE :title';
		$params[':title'] = "%{$filter['title']}%";
	}
	if($filter['cateid'] > 0) {
		$condition .= ' AND cateid = :cateid';
		$params[':cateid'] = $filter['cateid'];
	}
	$limit = ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('article_notice') . $condition, $params);
	$notice = pdo_fetchall('SELECT * FROM ' . tablename('article_notice') . $condition . ' ORDER BY displayorder DESC ' . $limit, $params, 'id');
	return array('total' => $total, 'notice' => $notice);
}
function article_case_all($filter = array(), $pindex = 1, $psize = 10) {
	$condition = ' WHERE is_display = 1';
	$params = array();
	if(!empty($filter['title'])) {
		$condition .= ' AND titie LIKE :title';
		$params[':title'] = "%{$filter['title']}%";
	}
	if($filter['cateid'] > 0) {
		$condition .= ' AND cateid = :cateid';
		$params[':cateid'] = $filter['cateid'];
	}
	$limit = ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('article_case') . $condition, $params);
	$case = pdo_fetchall('SELECT * FROM ' . tablename('article_case') . $condition . ' ORDER BY displayorder DESC ' . $limit, $params, 'id');
	return array('total' => $total, 'case' => $case);
}
function article_link_all($filter = array(), $pindex = 1, $psize = 10) {
	$condition = ' WHERE is_display = 1';
	$params = array();
	if(!empty($filter['title'])) {
		$condition .= ' AND titie LIKE :title';
		$params[':title'] = "%{$filter['title']}%";
	}
	if($filter['cateid'] > 0) {
		$condition .= ' AND cateid = :cateid';
		$params[':cateid'] = $filter['cateid'];
	}
	$limit = ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('article_link') . $condition, $params);
	$link = pdo_fetchall('SELECT * FROM ' . tablename('article_link') . $condition . ' ORDER BY displayorder DESC ' . $limit, $params, 'id');
	return array('total' => $total, 'link' => $link);
}
function article_about_all($filter = array(), $pindex = 1, $psize = 10) {
	$condition = ' WHERE is_display = 1';
	$params = array();
	if(!empty($filter['title'])) {
		$condition .= ' AND titie LIKE :title';
		$params[':title'] = "%{$filter['title']}%";
	}
	if($filter['cateid'] > 0) {
		$condition .= ' AND cateid = :cateid';
		$params[':cateid'] = $filter['cateid'];
	}
	$limit = ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('article_about') . $condition, $params);
	$abouts = pdo_fetchall('SELECT * FROM ' . tablename('article_about') . $condition . ' ORDER BY displayorder DESC ' . $limit, $params, 'id');
	return array('total' => $total, 'abouts' => $abouts);
}
function article_agent_all($filter = array(), $pindex = 1, $psize = 10) {
	$condition = ' WHERE is_display = 1';
	$params = array();
	if(!empty($filter['title'])) {
		$condition .= ' AND titie LIKE :title';
		$params[':title'] = "%{$filter['title']}%";
	}
	if($filter['cateid'] > 0) {
		$condition .= ' AND cateid = :cateid';
		$params[':cateid'] = $filter['cateid'];
	}
	$limit = ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('article_agent') . $condition, $params);
	$agents = pdo_fetchall('SELECT * FROM ' . tablename('article_agent') . $condition . ' ORDER BY displayorder DESC ' . $limit, $params, 'id');
	return array('total' => $total, 'agents' => $agents);
}
function article_wenda_all($filter = array(), $pindex = 1, $psize = 10) {
	$condition = ' WHERE is_display = 1';
	$params = array();
	if(!empty($filter['title'])) {
		$condition .= ' AND titie LIKE :title';
		$params[':title'] = "%{$filter['title']}%";
	}
	if($filter['cateid'] > 0) {
		$condition .= ' AND cateid = :cateid';
		$params[':cateid'] = $filter['cateid'];
	}
	$limit = ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('article_wenda') . $condition, $params);
	$wendas = pdo_fetchall('SELECT * FROM ' . tablename('article_wenda') . $condition . ' ORDER BY displayorder DESC ' . $limit, $params, 'id');
	return array('total' => $total, 'wendas' => $wendas);
}
function article_product_all($filter = array(), $pindex = 1, $psize = 10) {
	$condition = ' WHERE is_display = 1';
	$params = array();
	if(!empty($filter['title'])) {
		$condition .= ' AND titie LIKE :title';
		$params[':title'] = "%{$filter['title']}%";
	}
	if($filter['cateid'] > 0) {
		$condition .= ' AND cateid = :cateid';
		$params[':cateid'] = $filter['cateid'];
	}
	$limit = ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('article_product') . $condition, $params);
	$product = pdo_fetchall('SELECT * FROM ' . tablename('article_product') . $condition . ' ORDER BY displayorder DESC ' . $limit, $params, 'id');
	return array('total' => $total, 'product' => $product);
}
function cutstr_html($string, $sublen){
  $string = strip_tags($string);
  $string = preg_replace ('/\n/is', '', $string);
  $string = preg_replace ('/ |　/is', '', $string);
  $string = preg_replace ('/&nbsp;/is', '', $string);
  preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $string, $t_string);
  if(count($t_string[0]) - 0 > $sublen) $string = join('', array_slice($t_string[0], 0, $sublen))."…";   
  else $string = join('', array_slice($t_string[0], 0, $sublen));
  return $string;
}
function cutstr_num($string, $sublen){
$string = strip_tags($string);
  preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $string, $t_string);
  if(count($t_string[0]) - 0 > $sublen) $string = join('', array_slice($t_string[0], 0, $sublen))."…";   
  else $string = join('', array_slice($t_string[0], 0, $sublen));
  return $string;
}


function article_category_delete($id) {
	$id = intval($id);
	if (empty($id)) {
		return false;
	}
	load()->func('file');
	$category = pdo_fetch("SELECT id, parentid, nid FROM " . tablename('site_category')." WHERE id = " . $id);
	if (empty($category)) {
		return false;
	}
	if ($category['parentid'] == 0) {
		$children_cates = pdo_getall('site_category', array('parentid' => $id));
		pdo_update('site_article', array('pcate' => 0), array('pcate' => $id));
		if (!empty($children_cates)) {
			$children_cates_id = array_column($children_cates, 'id');
			pdo_update('site_article', array('ccate' => 0), array('ccate' => $children_cates_id), 'OR');
		}
	} else {
		pdo_update('site_article', array('ccate' => 0), array('ccate' => $id));
	}
	$navs = pdo_fetchall("SELECT icon, id FROM ".tablename('site_nav')." WHERE id IN (SELECT nid FROM ".tablename('site_category')." WHERE id = {$id} OR parentid = '$id')", array(), 'id');
	if (!empty($navs)) {
		foreach ($navs as $row) {
			file_delete($row['icon']);
		}
		pdo_query("DELETE FROM ".tablename('site_nav')." WHERE id IN (".implode(',', array_keys($navs)).")");
	}
	pdo_delete('site_category', array('id' => $id, 'parentid' => $id), 'OR');
	return true;
}

