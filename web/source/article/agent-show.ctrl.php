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
	$agent = article_agent_info($id);
	if(is_error($agent)) {
		message('公司不存在或已删除', referer(), 'error');
	}
	$_W['page']['title'] = $agent['title'] . '-公司列表';
	$cateid = intval($_GPC['cateid']);
	$filter = array('cateid' => $cateid);
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$agents = article_agent_all($psize);
	$total = intval($agents['total']);
	$data = $agents['agents'];
}

if($do == 'list') {
	$categroys = article_categorys('agent');
	$categroys[0] = array('title' => '所有公司');
	$cateid = intval($_GPC['cateid']);
	$_W['page']['title'] = $categroys[$cateid]['title'] . '-公司列表';

	$filter = array('cateid' => $cateid);
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$agents = article_agent_all($filter, $pindex, $psize);
	$total = intval($agents['total']);
	$data = $agents['agents'];
	$pager = pagination($total, $pindex, $psize);
}

template('article/agent/agent-show');
