<?php
/**
 * [WECHAT 2017]
 
 */
defined('IN_IA') or exit('Access Denied');
$dos = array('category_post', 'category', 'category_del', 'list', 'post', 'batch_post', 'del');
$do = in_array($do, $dos) ? $do : 'list';

if($do == 'category_post') {
	$_W['page']['title'] = '编辑分类-公司分类';
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
					'type' => 'agent',
				);
				pdo_insert('article_category', $data);
				$i++;
			}
		}
		message('修改公司分类成功', url('article/agent/category'), 'success');
	}
	template('article/agent/agent-category');
}

if($do == 'category') {
	$_W['page']['title'] = '分类列表-公司分类';
	if(checksubmit('submit')) {
		if(!empty($_GPC['ids'])) {
			foreach($_GPC['ids'] as $k => $v) {
				$data = array(
					'title' => trim($_GPC['title'][$k]),
					'displayorder' => intval($_GPC['displayorder'][$k])
				);
				pdo_update('article_category', $data, array('id' => intval($v)));
			}
			message('修改公司分类成功', referer(), 'success');
		}
	}
	$data = pdo_fetchall('SELECT * FROM ' . tablename('article_category') . ' WHERE type = :type ORDER BY displayorder DESC', array(':type' => 'agent'));
	template('article/agent/agent-category');
}

if($do == 'category_del') {
	$id = intval($_GPC['id']);
	pdo_delete('article_category', array('id' => $id, 'type' => 'agent'));
	pdo_delete('article_agent', array('cateid' => $id));
	message('删除分类成功', referer(), 'success');
}

if($do == 'post') {
	$_W['page']['title'] = '编辑公司-公司列表';
	$id = intval($_GPC['id']);
	$agent = pdo_fetch('SELECT * FROM ' . tablename('article_agent') . ' WHERE id = :id', array(':id' => $id));
	if(empty($agent)) {
		$agent = array(
			'is_display' => 1,
			'is_show_home' => 1,
		);
	}
	if(checksubmit()) {
		$title = trim($_GPC['title']) ? trim($_GPC['title']) : message('公司标题不能为空', '', 'error');
		$cateid = intval($_GPC['cateid']) ? intval($_GPC['cateid']) : message('公司分类不能为空', '', 'error');
		$content = trim($_GPC['content']) ? trim($_GPC['content']) : message('公司内容不能为空', '', 'error');
		$data = array(
			'title' => $title,
			'cateid' => $cateid,
			'content' => htmlspecialchars_decode($content),
			'source' => trim($_GPC['source']),
			'author' => trim($_GPC['author']),
			'displayorder' => intval($_GPC['displayorder']),
			'click' => intval($_GPC['click']),
			'telephone' => trim($_GPC['telephone']),
			'mobilephone' => trim($_GPC['mobilephone']),
			'address' => trim($_GPC['address']),
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

		if(!empty($agent['id'])) {
			pdo_update('article_agent', $data, array('id' => $id));
		} else {
			pdo_insert('article_agent', $data);
		}
		message('编辑公司成功', url('article/agent/list'), 'success');
	}
	$categorys = pdo_fetchall('SELECT * FROM ' . tablename('article_category') . ' WHERE type = :type ORDER BY displayorder DESC', array(':type' => 'agent'));
	template('article/agent/agent');
}

if($do == 'list') {
	$_W['page']['title'] = '所有公司-公司列表';
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
	$sql = 'SELECT * FROM ' . tablename('article_agent') . $condition . " ORDER BY displayorder DESC LIMIT " . ($pindex - 1) * $psize .',' .$psize;
	$agent = pdo_fetchall($sql, $params);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('article_agent') . $condition, $params);
	$pager = pagination($total, $pindex, $psize);

	$categorys = pdo_fetchall('SELECT * FROM ' . tablename('article_category') . ' WHERE type = :type ORDER BY displayorder DESC', array(':type' => 'agent'), 'id');
	template('article/agent/agent');
}

if($do == 'batch_post') {
	if(checksubmit()) {
		if(!empty($_GPC['ids'])) {
			foreach($_GPC['ids'] as $k => $v) {
				$data = array(
					'title' => trim($_GPC['title'][$k]),
					'displayorder' => intval($_GPC['displayorder'][$k]),
					'telephone' => intval($_GPC['telephone'][$k]),
				);
				pdo_update('article_agent', $data, array('id' => intval($v)));
			}
			message('编辑公司列表成功', referer(), 'success');
		}
	}
}

if($do == 'del') {
	$id = intval($_GPC['id']);
	pdo_delete('article_agent', array('id' => $id));
	message('删除公司成功', referer(), 'success');
}





