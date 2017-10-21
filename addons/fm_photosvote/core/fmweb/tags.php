<?php
/**
 * 女神来了模块定义
 *
 * @author 微赞科技
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');


$op = !empty($op) ? $op : $_GPC['op'];
$op = in_array($op, array('display', 'post', 'delete', 'fetch', 'tags', 'deleteall')) ? $op : 'display';

if ($op == 'display') {
	if (!empty($_GPC['displayorder'])) {
		foreach ($_GPC['displayorder'] as $id => $displayorder) {
			$update = array('displayorder' => $displayorder);
			pdo_update($this->table_tags, $update, array('id' => $id));
		}
		message('分组排序更新成功！', 'refresh', 'success');
	}
	$children = array();
	$category = pdo_fetchall("SELECT * FROM ".tablename($this->table_tags)." WHERE rid = '{$rid}' ".$uni." ORDER BY parentid, displayorder DESC, id");
	foreach ($category as $index => $row) {
		if (!empty($row['parentid'])){
			$children[$row['parentid']][] = $row;
			unset($category[$index]);
		}
	}
	if (checksubmit('submitdr')) {
		if($_GPC['leadExcel'] == "true") {
			$filename = $_FILES['inputExcel']['name'];
			$tmp_name = $_FILES['inputExcel']['tmp_name'];

			$msg = $this->uploadFile_tags($filename,$tmp_name,$rid);
			message($msg,referer(),'success');
		}
	}
	include $this->template('web/tags');
} elseif ($op == 'post') {
	$parentid = intval($_GPC['parentid']);
	$id = intval($_GPC['id']);
	if(!empty($id)) {
		$category = pdo_fetch("SELECT * FROM ".tablename($this->table_tags)." WHERE id = '$id' ".$uni."");
		if(empty($category)) {
			message('分组不存在或已删除', '', 'error');
		}

	} else {
		$category = array(
			'displayorder' => 0
		);
	}
	if (!empty($parentid)) {
		$parent = pdo_fetch("SELECT id, title FROM ".tablename($this->table_tags)." WHERE id = '$parentid'");
		if (empty($parent)) {
			message('抱歉，上级分组不存在或是已经被删除！', $this->createWebUrl('tags',array('rid'=>$rid)), 'error');
		}
	}

	if (checksubmit('submit')) {
		if (empty($_GPC['title'])) {
			message('抱歉，请输入分组名称！');
		}
		$data = array(
			'uniacid' => $_W['uniacid'],
			'rid' => $rid,
			'title' => $_GPC['title'],
			'icon' => intval($_GPC['icon']),
			'displayorder' => intval($_GPC['displayorder']),
			'parentid' => intval($parentid),
			'description' => $_GPC['description'],
			'images' => $_GPC['images'],
		);
		if (!empty($id)) {
			unset($data['parentid']);
			pdo_update($this->table_tags, $data, array('id' => $id));
		} else {
			pdo_insert($this->table_tags, $data);
			$id = pdo_insertid();
		}
		message('更新分组成功！', $this->createWebUrl('tags',array('rid'=>$rid)), 'success');
	}
	include $this->template('web/tags');
} elseif ($op == 'tags') {
	load()->func('tpl');
	$tagid = !empty($_GPC['category']['childid']) ? $_GPC['category']['childid'] : $_GPC['tagid'];
	$tagpid = !empty($_GPC['category']['parentid']) ? $_GPC['category']['parentid'] : $_GPC['tagpid'];
	$tagtid = !empty($_GPC['category']['threecs']) ? $_GPC['category']['threecs'] : $_GPC['tagtid'];
	$tagname = $this->gettagname($tagid,$tagpid,$tagtid,$rid);

	$tags = pdo_fetchall("SELECT id,parentid,title FROM ".tablename($this->table_tags)." WHERE rid = '{$rid}' ".$uni." ORDER BY parentid ASC, displayorder ASC, id ASC ", array(), 'id');
	$parent = array();
	$children = array();

	if (!empty($tags)) {
		$children = '';
		foreach ($tags as $cid => $cate) {
			$cate['name'] = $cate['title'];
			if (!empty($cate['parentid'])) {
				$children[$cate['parentid']][] = $cate;
			} else {
				$parent[$cate['id']] = $cate;
			}
		}
	}

	include $this->template('web/tags');
} elseif ($op == 'fetch') {
	$category = pdo_fetchall("SELECT id, title FROM ".tablename($this->table_tags)." WHERE parentid = '".intval($_GPC['parentid'])."' ORDER BY id ASC, displayorder ASC, id ASC ");
	message($category, '', 'ajax');
} elseif ($op == 'delete') {
	$id = intval($_GPC['id']);
	$category = pdo_fetch("SELECT id, parentid FROM ".tablename($this->table_tags)." WHERE id = '$id'");
	if (empty($category)) {
		message('抱歉，分组不存在或是已经被删除！',  $this->createWebUrl('tags',array('rid'=>$rid)), 'error');
	}

	pdo_delete($this->table_tags, array('id' => $id, 'parentid' => $id), 'OR');
	message('分组删除成功！',  $this->createWebUrl('tags',array('rid'=>$rid)), 'success');
} elseif ($op == 'deleteall') {
	$tags = pdo_fetch("SELECT id FROM ".tablename($this->table_tags)." WHERE rid = '$rid'");
	if (empty($tags)) {
		message('抱歉，分组不存在或是已经被删除！',  $this->createWebUrl('tags',array('rid'=>$rid)), 'error');
	}

	pdo_delete($this->table_tags, array('rid' => $rid), 'OR');
	message('分组删除成功！',  $this->createWebUrl('tags',array('rid'=>$rid)), 'success');
}
