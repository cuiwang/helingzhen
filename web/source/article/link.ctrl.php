<?php
/**
 * [WECHAT 2017]
 
 */
defined('IN_IA') or exit('Access Denied');
$dos = array('list', 'post', 'batch_post', 'del');
$do = in_array($do, $dos) ? $do : 'list';


if($do == 'post') {
	$_W['page']['title'] = '编辑链接-链接列表';
	$id = intval($_GPC['id']);
	$link = pdo_fetch('SELECT * FROM ' . tablename('article_link') . ' WHERE id = :id', array(':id' => $id));
	if(empty($link)) {
		$link = array(
			'is_display' => 1,
			'is_show_home' => 1,
		);
	}
	if(checksubmit()) {
		$title = trim($_GPC['title']) ? trim($_GPC['title']) : message('链接标题不能为空', '', 'error');
		$siteurl = trim($_GPC['siteurl']) ? trim($_GPC['siteurl']) : message('链接地址不能为空', '', 'error');
		$data = array(
			'title' => $title,
			'cateid' => $cateid,
			'siteurl' => $siteurl,
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

		if(!empty($link['id'])) {
			pdo_update('article_link', $data, array('id' => $id));
		} else {
			pdo_insert('article_link', $data);
		}
		message('编辑文章成功', url('article/link/list'), 'success');
	}
	template('article/link');
}

if($do == 'list') {
	$_W['page']['title'] = '所有链接-链接列表';
	$condition = ' WHERE 1';
	$cateid = intval($_GPC['cateid']);
	$createtime = intval($_GPC['createtime']);
	$title = trim($_GPC['title']);
	$siteurl = trim($_GPC['siteurl']);

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
	$sql = 'SELECT * FROM ' . tablename('article_link') . $condition . " ORDER BY displayorder DESC LIMIT " . ($pindex - 1) * $psize .',' .$psize;
	$link = pdo_fetchall($sql, $params);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('article_link') . $condition, $params);
	$pager = pagination($total, $pindex, $psize);
	template('article/link');
}

if($do == 'batch_post') {
	if(checksubmit()) {
		if(!empty($_GPC['ids'])) {
			foreach($_GPC['ids'] as $k => $v) {
				$data = array(
					'title' => trim($_GPC['title'][$k]),
					'siteurl' => trim($_GPC['siteurl'][$k]),
					'displayorder' => intval($_GPC['displayorder'][$k]),
					'click' => intval($_GPC['click'][$k]),
				);
				pdo_update('article_link', $data, array('id' => intval($v)));
			}
			message('编辑链接列表成功', referer(), 'success');
		}
	}
}

if($do == 'del') {
	$id = intval($_GPC['id']);
	pdo_delete('article_link', array('id' => $id));
	message('删除文章成功', referer(), 'success');
}





