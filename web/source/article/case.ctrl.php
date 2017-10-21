<?php
/**
 * [WeEngine System] Copyright (c) 2015 012WZ.COM
 
 */
defined('IN_IA') or exit('Access Denied');
$dos = array('catecase_post', 'catecase', 'catecase_del', 'list', 'post', 'batch_post', 'del');
$do = in_array($do, $dos) ? $do : 'list';

if($do == 'catecase_post') {
	$_W['page']['title'] = '编辑分类-案例分类';
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
					'type' => 'case',
				);
				pdo_insert('article_catecase', $data);
				$i++;
			}
		}
		message('修改文章分类成功', url('article/case/catecase'), 'success');
	}
	template('article/case-catecase');
}

if($do == 'catecase') {
	$_W['page']['title'] = '分类列表-案例分类';
	if(checksubmit('submit')) {
		if(!empty($_GPC['ids'])) {
			foreach($_GPC['ids'] as $k => $v) {
				$data = array(
					'title' => trim($_GPC['title'][$k]),
					'displayorder' => intval($_GPC['displayorder'][$k])
				);
				pdo_update('article_catecase', $data, array('id' => intval($v)));
			}
			message('修改案例分类成功', referer(), 'success');
		}
	}
	$data = pdo_fetchall('SELECT * FROM ' . tablename('article_catecase') . ' WHERE type = :type ORDER BY displayorder DESC', array(':type' => 'case'));
	template('article/case-catecase');
}

if($do == 'catecase_del') {
	$id = intval($_GPC['id']);
	pdo_delete('article_catecase', array('id' => $id, 'type' => 'case'));
	pdo_delete('article_case', array('cateid' => $id));
	message('删除分类成功', referer(), 'success');
}

if($do == 'post') {
	$_W['page']['title'] = '编辑案例-案例列表';
	$id = intval($_GPC['id']);
	$case = pdo_fetch('SELECT * FROM ' . tablename('article_case') . ' WHERE id = :id', array(':id' => $id));
	if(empty($case)) {
		$case = array(
			'is_display' => 1,
			'is_show_home' => 1,
		);
	}
	if(checksubmit()) {
		$title = trim($_GPC['title']) ? trim($_GPC['title']) : message('案例标题不能为空', '', 'error');
		$cateid = intval($_GPC['cateid']) ? intval($_GPC['cateid']) : message('案例分类不能为空', '', 'error');
		$content = trim($_GPC['content']) ? trim($_GPC['content']) : message('案例内容不能为空', '', 'error');
		$data = array(
			'title' => $title,
			'cateid' => $cateid,
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

		if(!empty($case['id'])) {
			pdo_update('article_case', $data, array('id' => $id));
		} else {
			pdo_insert('article_case', $data);
		}
		message('编辑文章成功', url('article/case/list'), 'success');
	}
	$catecases = pdo_fetchall('SELECT * FROM ' . tablename('article_catecase') . ' WHERE type = :type ORDER BY displayorder DESC', array(':type' => 'case'));
	template('article/case');
}

if($do == 'list') {
	$_W['page']['title'] = '所有案例-案例列表';
	$condition = ' WHERE 1';
	$cateid = intval($_GPC['cateid']);
	$createtime = intval($_GPC['createtime']);
	$title = trim($_GPC['title']);

	$params = array();
	if($cateid > 0) {
		$condition .= ' AND cateid = :cateid';
		$params[':cateid'] = $cateid;
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
	$sql = 'SELECT * FROM ' . tablename('article_case') . $condition . " ORDER BY displayorder DESC LIMIT " . ($pindex - 1) * $psize .',' .$psize;
	$case = pdo_fetchall($sql, $params);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('article_case') . $condition, $params);
	$pager = pagination($total, $pindex, $psize);

	$catecases = pdo_fetchall('SELECT * FROM ' . tablename('article_catecase') . ' WHERE type = :type ORDER BY displayorder DESC', array(':type' => 'case'), 'id');
	template('article/case');
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
				pdo_update('article_case', $data, array('id' => intval($v)));
			}
			message('编辑案例列表成功', referer(), 'success');
		}
	}
}

if($do == 'del') {
	$id = intval($_GPC['id']);
	pdo_delete('article_case', array('id' => $id));
	message('删除文章成功', referer(), 'success');
}





