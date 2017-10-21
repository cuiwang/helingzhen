<?php
/**
 * 女神来了模块定义
 *
 * @author 微赞科技
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');


$op = !empty($op) ? $op : $_GPC['op'];
$op = in_array($op, array('display', 'post', 'delete', 'fetch', 'school', 'deleteall')) ? $op : 'display';

if ($op == 'display') {
	if (!empty($_GPC['displayorder'])) {
		foreach ($_GPC['displayorder'] as $id => $displayorder) {
			$update = array('displayorder' => $displayorder);
			pdo_update($this->table_school, $update, array('id' => $id));
		}
		message('学校排序更新成功！', 'refresh', 'success');
	}
	$children = array();
	$category = pdo_fetchall("SELECT * FROM ".tablename($this->table_school)." WHERE rid = '{$rid}' ".$uni." ORDER BY parentid, displayorder DESC, id");
	foreach ($category as $index => $row) {
		if (!empty($row['parentid'])){
			$children[$row['parentid']][] = $row;
			unset($category[$index]);
		}
	}
	include $this->template('web/school');
} elseif ($op == 'post') {
	$parentid = intval($_GPC['parentid']);
	$id = intval($_GPC['id']);
	if(!empty($id)) {
		$category = pdo_fetch("SELECT * FROM ".tablename($this->table_school)." WHERE id = '$id' ".$uni."");
		if(empty($category)) {
			message('学校不存在或已删除', '', 'error');
		}

	} else {
		$category = array(
			'displayorder' => 0
		);
	}
	if (checksubmit('submit')) {
		if (empty($_GPC['title'])) {
			message('抱歉，请输入学校名称！');
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
			pdo_update($this->table_school, $data, array('id' => $id));
		} else {
			pdo_insert($this->table_school, $data);
			$id = pdo_insertid();
		}
		message('更新学校成功！', $this->createWebUrl('school',array('rid'=>$rid)), 'success');
	}
	include $this->template('web/school');
} elseif ($op == 'school') {
	load()->func('tpl');
	$tagid = !empty($_GPC['category']['childid']) ? $_GPC['category']['childid'] : $_GPC['tagid'];
	$tagpid = !empty($_GPC['category']['parentid']) ? $_GPC['category']['parentid'] : $_GPC['tagpid'];
	$tagtid = !empty($_GPC['category']['threecs']) ? $_GPC['category']['threecs'] : $_GPC['tagtid'];
	$tagname = $this->gettagname($tagid,$tagpid,$tagtid,$rid);

	$school = pdo_fetchall("SELECT id,parentid,title FROM ".tablename($this->table_school)." WHERE rid = '{$rid}' ".$uni." ORDER BY parentid ASC, displayorder ASC, id ASC ", array(), 'id');
	$parent = array();
	$children = array();

	if (!empty($school)) {
		$children = '';
		foreach ($school as $cid => $cate) {
			$cate['name'] = $cate['title'];
			if (!empty($cate['parentid'])) {
				$children[$cate['parentid']][] = $cate;
			} else {
				$parent[$cate['id']] = $cate;
			}
		}
	}

	include $this->template('web/school');
} elseif ($op == 'fetch') {
	$category = pdo_fetchall("SELECT id, title FROM ".tablename($this->table_school)." WHERE parentid = '".intval($_GPC['parentid'])."' ORDER BY id ASC, displayorder ASC, id ASC ");
	message($category, '', 'ajax');
} elseif ($op == 'delete') {
	$id = intval($_GPC['id']);
	$category = pdo_fetch("SELECT id, parentid FROM ".tablename($this->table_school)." WHERE id = '$id'");
	if (empty($category)) {
		message('抱歉，学校不存在或是已经被删除！',  $this->createWebUrl('school',array('rid'=>$rid)), 'error');
	}

	pdo_delete($this->table_school, array('id' => $id, 'parentid' => $id), 'OR');
	message('学校删除成功！',  $this->createWebUrl('school',array('rid'=>$rid)), 'success');
} elseif ($op == 'deleteall') {
	$school = pdo_fetch("SELECT id FROM ".tablename($this->table_school)." WHERE rid = '$rid'");
	if (empty($school)) {
		message('抱歉，学校不存在或是已经被删除！',  $this->createWebUrl('school',array('rid'=>$rid)), 'error');
	}

	pdo_delete($this->table_school, array('rid' => $rid), 'OR');
	message('学校删除成功！',  $this->createWebUrl('school',array('rid'=>$rid)), 'success');
}
