<?php 
	global $_W,$_GPC;
	load() -> func('tpl');
	$operation = !empty($_GPC['op']) ? $_GPC['op'] :'display';

	if ($operation == 'display') {
	//分页数据
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$total = pdo_fetchcolumn(" SELECT COUNT(*) FROM " . tablename('zofui_dthb_question') . " WHERE uniacid ={$_W['uniacid']} ");
	//分页数据结束
	//查询文章
	$questiones = pdo_fetchall("select * from" . tablename('zofui_dthb_question') . "where uniacid ={$_W['uniacid']} ORDER BY id DESC " . "LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
	//分页函数
	$pager = pagination($total, $pindex, $psize);

	} elseif ($operation == 'add') {
		if (checksubmit()) {
			if (empty($_GPC['questionq']) || empty($_GPC['rightan'])) {
				message('请输入题目或答案！');
			}
			$data = array(
				'uniacid' => $_W['uniacid'], 
				'question' => $_GPC['questionq'], 
				'right' => $_GPC['rightan'], 
				'time' => time(), 
				'state' => intval($_GPC['state'])
			);
			pdo_insert('zofui_dthb_question', $data);
			$id = pdo_insertid();
			message('添加题目成功', $this -> createWebUrl('admin', array('op' => 'add')), 'success');
			}
	}

elseif ($operation == 'delete') {
	$id = intval($_GPC['id']);
	$category = pdo_fetch("SELECT id FROM " . tablename('zofui_dthb_question') . " WHERE id = '$id'");
	if (empty($category)) {
		message('抱歉，此问题不存在或是已经被删除！', $this -> createWebUrl('admin', array('op' => 'display')), 'error');
	}
	pdo_delete('zofui_dthb_question', array('id' => $id), 'OR');
	message('删除成功！', $this -> createWebUrl('admin', array('op' => 'display')), 'success');
	
}elseif ($operation == 'edit') {
	$id = intval($_GPC['id']);
	$questiones = pdo_fetch("select * from" . tablename('zofui_dthb_question') . "where uniacid ={$_W['uniacid']} AND id = {$id}");
	if(checksubmit()){
		$data = array(
			'uniacid' => $_W['uniacid'], 
			'question' => $_GPC['questionq'], 
			'right' => $_GPC['rightan'], 
			'state' => intval($_GPC['state'])
		);
		pdo_update('zofui_dthb_question', $data , array('id' => $id));
		message('修改成功', $this -> createWebUrl('admin', array('op' => 'display')), 'success');
	}
}

	

include $this->template('web/admin');
?>