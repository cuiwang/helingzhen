<?php
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
defined('IN_IA') or exit('Access Denied');

load()->model('article');

$dos = array('category_post', 'category', 'category_del', 'list', 'post', 'batch_post', 'del');
$do = in_array($do, $dos) ? $do : 'list';
uni_user_permission_check('system_article_notice');

if ($do == 'category_post') {
	$_W['page']['title'] = '公告分类-公告管理-文章-系统管理';
	if (checksubmit('submit')) {
		$i = 0;
		if (!empty($_GPC['title'])) {
			foreach ($_GPC['title'] as $k => $v) {
				$title = trim($v);
				if  (empty($title)) {
					continue;
				}
				$data = array(
					'title' => $title,
					'displayorder' => intval($_GPC['displayorder'][$k]),
					'type' => 'notice',
				);
				pdo_insert('article_category', $data);
				$i++;
			}
		}
		itoast('添加公告分类成功', url('article/notice/category'), 'success');
	}
	template('article/notice-category-post');
}

if ($do == 'category') {
	$_W['page']['title'] = '分类列表-公告分类-公告管理-文章-系统管理';
	if (checksubmit('submit')) {
		if (!empty($_GPC['ids'])) {
			foreach ($_GPC['ids'] as $k => $v) {
				$data = array(
					'title' => trim($_GPC['title'][$k]),
					'displayorder' => intval($_GPC['displayorder'][$k])
				);
				pdo_update('article_category', $data, array('id' => intval($v)));
			}
			itoast('修改公告分类成功', referer(), 'success');
		}
	}
	$data = pdo_fetchall('SELECT * FROM ' . tablename('article_category') . ' WHERE type = :type ORDER BY displayorder DESC', array(':type' => 'notice'));
	template('article/notice-category');
}

if ($do == 'category_del') {
	$id = intval($_GPC['id']);
	pdo_delete('article_category', array('id' => $id,'type' => 'notice'));
	pdo_delete('article_notice', array('cateid' => $id));
	itoast('删除公告分类成功', referer(), 'success');
}

if ($do == 'post') {
	$_W['page']['title'] = '编辑公告-公告管理-文章-系统管理';
	$id = intval($_GPC['id']);
	$notice = pdo_fetch('SELECT * FROM ' . tablename('article_notice') . ' WHERE id = :id', array(':id' => $id));
	if (empty($notice)) {
		$notice = array(
			'is_display' => 1,
			'is_show_home' => 1,
		);
	}
	if (checksubmit()) {
		$title = trim($_GPC['title']) ? trim($_GPC['title']) : itoast('公告标题不能为空', '', 'error');
		$cateid = intval($_GPC['cateid']) ? intval($_GPC['cateid']) : itoast('公告分类不能为空', '', 'error');
		$content = trim($_GPC['content']) ? trim($_GPC['content']) : itoast('公告内容不能为空', '', 'error');
		$data = array(
			'title' => $title,
			'cateid' => $cateid,
			'content' => htmlspecialchars_decode($content),
			'displayorder' => intval($_GPC['displayorder']),
			'click' => intval($_GPC['click']),
			'is_display' => intval($_GPC['is_display']),
			'is_show_home' => intval($_GPC['is_show_home']),
			'createtime' => TIMESTAMP,
		);

		if (!empty($notice['id'])) {
			pdo_update('article_notice', $data, array('id' => $id));
		} else {
			pdo_insert('article_notice', $data);
		}
		itoast('编辑公告成功', url('article/notice/list'), 'success');
	}
	$categorys = pdo_fetchall('SELECT * FROM ' . tablename('article_category') . ' WHERE type = :type ORDER BY displayorder DESC', array(':type' => 'notice'));
	template('article/notice-post');
}

if ($do == 'list') {
	$_W['page']['title'] = '公告列表-公告管理-文章-系统管理';
	$condition = ' WHERE 1';
	$cateid = intval($_GPC['cateid']);
	$createtime = intval($_GPC['createtime']);
	$search_title = trim($_GPC['title']);
	$params = array();
	if ($cateid > 0) {
		$condition .= ' AND cateid = :cateid';
		$params[':cateid'] = $cateid;
	}
	if ($createtime > 0) {
		$condition .= ' AND createtime >= :createtime';
		$params[':createtime'] = strtotime("-{$createtime} days");
	}
	if (!empty($search_title)) {
		$condition .= " AND title LIKE :title";
		$params[':title'] = "%{$search_title}%";
	}

	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$sql = 'SELECT * FROM ' . tablename('article_notice') . $condition . " ORDER BY displayorder DESC LIMIT " . ($pindex - 1) * $psize .',' .$psize;
	$notices = pdo_fetchall($sql, $params);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('article_notice') . $condition, $params);
	$pager = pagination($total, $pindex, $psize);

	$categorys = pdo_fetchall('SELECT * FROM ' . tablename('article_category') . ' WHERE type = :type ORDER BY displayorder DESC', array(':type' => 'notice'), 'id');
	template('article/notice');
}

if ($do == 'batch_post') {
	if (checksubmit()) {
		if (!empty($_GPC['ids'])) {
			foreach ($_GPC['ids'] as $k => $v) {
				$data = array(
					'title' => trim($_GPC['title'][$k]),
					'displayorder' => intval($_GPC['displayorder'][$k]),
					'click' => intval($_GPC['click'][$k]),
				);
				pdo_update('article_notice', $data, array('id' => intval($v)));
			}
			itoast('编辑公告列表成功', referer(), 'success');
		}
	}
}

if ($do == 'del') {
	$id = intval($_GPC['id']);
	pdo_delete('article_notice', array('id' => $id));
	pdo_delete('article_unread_notice', array('notice_id' => $id));
	itoast('删除公告成功', referer(), 'success');
}