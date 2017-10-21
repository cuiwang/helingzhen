<?php
/**
 * [WECHAT 2017]
 
 */
defined('IN_IA') or exit('Access Denied');
$dos = array('category_post', 'category', 'category_del', 'list', 'post', 'batch_post', 'del');
$do = in_array($do, $dos) ? $do : 'list';

if($do == 'category_post') {
	$_W['page']['title'] = '编辑分类-问题分类';
	if(checksubmit('submit')) {
		$i = 0;
		if(!empty($_GPC['title'])) {
			foreach($_GPC['title'] as $k => $v) {
				$title = trim($v);
				if(empty($title)) {
					continue;
				}
				$data = array(
					'title' => $title,
					'displayorder' => intval($_GPC['displayorder'][$k]),
					'type' => 'wenda',
				);
				pdo_insert('article_category', $data);
				$i++;
			}
		}
		message('修改问题分类成功', url('website/wenda/category'), 'success');
	}
	template('website/wenda/wenda-category');
}

if($do == 'category') {
	$_W['page']['title'] = '分类列表-问题分类';
	if(checksubmit('submit')) {
		if(!empty($_GPC['ids'])) {
			foreach($_GPC['ids'] as $k => $v) {
				$data = array(
					'title' => trim($_GPC['title'][$k]),
					'displayorder' => intval($_GPC['displayorder'][$k]),
				);
				pdo_update('article_category', $data, array('id' => intval($v)));
			}
			message('修改问题分类成功', referer(), 'success');
		}
	}
	$data = pdo_fetchall('SELECT * FROM ' . tablename('article_category') . ' WHERE type = :type ORDER BY displayorder DESC', array(':type' => 'wenda'));
	template('website/wenda/wenda-category');
}

if($do == 'category_del') {
	$id = intval($_GPC['id']);
	pdo_delete('article_category', array('id' => $id, 'type' => 'wenda'));
	pdo_delete('article_wenda', array('cateid' => $id));
	message('删除分类成功', referer(), 'success');
}

if($do == 'post') {
	$_W['page']['title'] = '编辑问题-问题列表';
	$id = intval($_GPC['id']);
	$wenda = pdo_fetch('SELECT * FROM ' . tablename('article_wenda') . ' WHERE id = :id', array(':id' => $id));
	if(empty($wenda)) {
		$wenda = array(
			'is_display' => 1,
			'is_show_home' => 1,
		);
	}
	if(checksubmit()) {
		$title = trim($_GPC['title']) ? trim($_GPC['title']) : message('问题标题不能为空', '', 'error');
		$cateid = intval($_GPC['cateid']) ? intval($_GPC['cateid']) : message('问题分类不能为空', '', 'error');
		$content = trim($_GPC['content']) ? trim($_GPC['content']) : message('问题内容不能为空', '', 'error');
		$data = array(
			'title' => $title,
			'cateid' => $cateid,
			'modid' => intval($_GPC['modid']),
			'content' => htmlspecialchars_decode($content),
			'source' => trim($_GPC['source']),
			'author' => trim($_GPC['author']),
			'displayorder' => intval($_GPC['displayorder']),
			'click' => intval($_GPC['click']),
			'is_display' => intval($_GPC['is_display']),
			'is_show_home' => intval($_GPC['is_show_home']),
			'createtime' => TIMESTAMP,
		);
		if (!empty($_GPC['thumb'])) {
			$data['thumb'] = $_GPC['thumb'];
		} elseif (!empty($_GPC['autolitpic'])) {
			$match = array();
			preg_match('/attachment\/(.*?)(\.gif|\.jpg|\.png|\.bmp)/', $data['content'], $match);
			if (!empty($match[1])) {
				$data['thumb'] = $match[1].$match[2];
			}
		} else {
			$data['thumb'] = '';
		}

		if(!empty($wenda['id'])) {
			pdo_update('article_wenda', $data, array('id' => $id));
		} else {
			pdo_insert('article_wenda', $data);
		}
		message('编辑问题成功', url('website/wenda/list'), 'success');
	}
	$modules = pdo_fetchall('SELECT type, mid, title FROM ' .tablename('modules'). ' WHERE type <> :type ' ,array(':type' => 'system'));
	template('website/wenda/wenda');
}

if($do == 'list') {
	$_W['page']['title'] = '所有问题-问题列表';
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

	$modules = pdo_fetchall('SELECT * FROM ' . tablename('modules') . ' WHERE type <> :type', array(':type' => 'system'), 'mid');
	template('website/wenda/wenda');
}

if($do == 'batch_post') {
	if(checksubmit()) {
		if(!empty($_GPC['ids'])) {
			foreach($_GPC['ids'] as $k => $v) {
				$data = array(
					'title' => trim($_GPC['title'][$k]),
					'displayorder' => intval($_GPC['displayorder'][$k]),
					'click' => intval($_GPC['click'][$k]),
				);
				pdo_update('article_wenda', $data, array('id' => intval($v)));
			}
			message('编辑问题列表成功', referer(), 'success');
		}
	}
}

if($do == 'del') {
	$id = intval($_GPC['id']);
	pdo_delete('article_wenda', array('id' => $id));
	message('删除问题成功', referer(), 'success');
}





