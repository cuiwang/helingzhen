<?php
/**
 * 课程分类管理
 */
if ($operation == 'display') {
	if (checksubmit('submit')) { /* 排序 */
		if (is_array($_GPC['displayorder'])) {
			foreach ($_GPC['displayorder'] as $key => $val) {
				$data = array('displayorder' => intval($_GPC['displayorder'][$key]));
				pdo_update($this->table_category, $data, array('id' => $key));
			}
		}
		message("操作成功!",$this->createWebUrl('category'),"success");
	}

	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	
	$condition = " uniacid='{$uniacid}' AND parentid=0 ";

	$category = pdo_fetchall("SELECT * FROM " . tablename($this->table_category) . " WHERE {$condition} ORDER BY displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);

	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_category) . " WHERE {$condition} ");
	$pager = pagination($total, $pindex, $psize);

}elseif($operation == 'post') {
	$id = intval($_GPC['id']); /* 当前分类id */
	$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_category) . " WHERE uniacid = '$uniacid' AND parentid = 0 ORDER BY displayorder DESC");

	if (!empty($id)) {
		$category = pdo_fetch("SELECT * FROM " . tablename($this->table_category) . " WHERE uniacid = '$uniacid' AND id = '$id'");
		if(empty($category)){
			message("该分类不存在或已被删除！", "", "error");
		}
	}

	if (checksubmit('submit')) {
		if (empty($_GPC['catename'])) {
			message("抱歉，请输入分类名称！");
		}

		$data = array(
			'uniacid'      => $_W['uniacid'],
			'name'         => trim($_GPC['catename']),
			'ico'          => trim($_GPC['ico']),
			'parentid'     => 0,
			'displayorder' => intval($_GPC['displayorder']),
			'addtime'      => time(),
		);

		if (!empty($id)) {
			unset($data['addtime']);
			$res = pdo_update($this->table_category, $data, array('id' => $id));
			if($res){
				$this->addSysLog($_W['uid'], $_W['username'], 3, "课程分类", "编辑ID:{$id}的课程分类");
			}
		} else {
			pdo_insert($this->table_category, $data);
			$cid = pdo_insertid();
			if($cid){
				$this->addSysLog($_W['uid'], $_W['username'], 3, "课程分类", "新增ID:{$cid}的课程分类");
			}
		}
		message("更新分类成功！", $this->createWebUrl('category', array('op' => 'display')), "success");
	}

}elseif ($operation == 'delete') {
	$id = intval($_GPC['id']);
	$category = pdo_fetch("SELECT id, parentid FROM " . tablename($this->table_category) . " WHERE uniacid = '$uniacid' AND id = '{$id}'");
	if (empty($category)) {
		message("抱歉，分类不存在或是已经被删除！", $this->createWebUrl('category', array('op' => 'display')), "error");
	}

	$res = pdo_delete($this->table_category, array('uniacid'=>$uniacid,'id' => $id));
	if($res){
		$this->addSysLog($_W['uid'], $_W['username'], 2, "课程分类", "删除ID:{$id}的课程分类");
	}

	message("删除分类成功！", $this->createWebUrl('category', array('op' => 'display')), "success");
}

include $this->template('category');

?>