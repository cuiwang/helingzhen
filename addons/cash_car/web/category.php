<?php
/**
 * 项目分类管理
 * ============================================================================
 * 版权所有 2015-2016 米粒源码，并保留所有权利。
 * 网站地址: http://www.webmili.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！已购买用户允许对程序代码进行修改和使用，但是不允许对
 * 程序代码以任何形式任何目的的再发布，作者将保留追究法律责任的权力和最终解
 * 释权。
 * ============================================================================
 */
 
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
	if (checksubmit('submit')) { //排序
		if (is_array($_GPC['displayorder'])) {
			foreach ($_GPC['displayorder'] as $key => $val) {
				$data = array('displayorder' => intval($_GPC['displayorder'][$key]));
				pdo_update($this->table_category, $data, array('id' => $key));
			}
		}
		message('操作成功!',$this->createWebUrl('category'),'success');
	}

	$pindex = max(1, intval($_GPC['page']));
	$psize = 5;
	
	$condition = " weid='{$weid}' AND parentid=0 ";

	$category = pdo_fetchall("SELECT * FROM " . tablename($this->table_category) . " WHERE {$condition} ORDER BY displayorder DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);

	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_category) . " WHERE {$condition} ");
	$pager = pagination($total, $pindex, $psize);

}elseif($operation == 'post') {
	$id = intval($_GPC['id']); //当前分类id
	$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_category) . " WHERE parentid = 0 ORDER BY displayorder DESC");

	if (!empty($id)) {
		$category = pdo_fetch("SELECT * FROM " . tablename($this->table_category) . " WHERE id = '$id'");
	} else {
		$category = array(
			'displayorder' => 0,
		);
	}

	if (checksubmit('submit')) {
		if (empty($_GPC['catename'])) {
			message('抱歉，请输入分类名称！');
		}

		$data = array(
			'weid'         => $_W['uniacid'],
			'name'         => $_GPC['catename'],
			'parentid'     => 0,
			'displayorder' => intval($_GPC['displayorder']),
		);

		if (!empty($id)) {
			pdo_update($this->table_category, $data, array('id' => $id));
		} else {
			pdo_insert($this->table_category, $data);
			$id = pdo_insertid();
		}
		message('更新分类成功！', $this->createWebUrl('category', array('op' => 'display')), 'success');
	}

}elseif ($operation == 'delete') {
	$id = intval($_GPC['id']);
	$category = pdo_fetch("SELECT id, parentid FROM " . tablename($this->table_category) . " WHERE id = '{$id}'");
	if (empty($category)) {
		message('抱歉，分类不存在或是已经被删除！', $this->createWebUrl('category', array('op' => 'display')), 'error');
	}

	pdo_delete($this->table_category, array('id' => $id, 'parentid' => $id), 'OR');

	message('分类删除成功！', $this->createWebUrl('category', array('op' => 'display')), 'success');
}

include $this->template('category');