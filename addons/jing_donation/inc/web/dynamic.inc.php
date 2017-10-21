<?php
defined('IN_IA') or exit('Access Denied');
global $_GPC,$_W;
load()->func('tpl');
$id = intval($_GPC['id']);
$item = pdo_fetch("SELECT * FROM ".tablename($this->t_donation)." WHERE id=:id",array(':id'=>$id));
if (empty($item)) {
	message('您访问的活动不存在',referer(),'error');
}
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
	$list = pdo_fetchall("SELECT * FROM " . tablename($this->t_dynamic) . " WHERE uniacid = '{$_W['uniacid']}'");
} elseif ($operation == 'post') {
	$dynamicid = intval($_GPC['dynamicid']);
	if (checksubmit('submit')) {
		$data = array(
			'uniacid' => $_W['uniacid'],
			'did' => $id,
			'title' => $_GPC['title'],
			'description' => $_GPC['description'],
			'content' => htmlspecialchars_decode($_GPC['content']),
			'link' => $_GPC['link'],
			'enabled' => intval($_GPC['enabled']),
			'thumb'=>$_GPC['thumb'],
			'createtime' => time()
		);
		if (!empty($dynamicid)) {
			pdo_update($this->t_dynamic, $data, array('id' => $dynamicid));
		} else {
			pdo_insert($this->t_dynamic, $data);
			$id = pdo_insertid();
		}
		message('更新成功！', $this->createWebUrl('dynamic', array('op' => 'display','id'=>$id)), 'success');
	}
	$dynamic = pdo_fetch("select * from " . tablename($this->t_dynamic) . " where id=:id and uniacid=:uniacid limit 1", array(":id" => $dynamicid, ":uniacid" => $_W['uniacid']));
} elseif ($operation == 'delete') {
	$dynamicid = intval($_GPC['dynamicid']);
	$dynamic = pdo_fetch("SELECT id FROM " . tablename($this->t_dynamic) . " WHERE id = '$dynamicid' AND uniacid=" . $_W['uniacid'] . "");
	if (empty($dynamic)) {
		message('抱歉，项目不存在或是已经被删除！', $this->createWebUrl('dynamic', array('op' => 'display','id'=>$id)), 'error');
	}
	pdo_delete($this->t_dynamic, array('id' => $dynamicid));
	message('删除成功！', $this->createWebUrl('dynamic', array('op' => 'display','id'=>$id)), 'success');
} else {
	message('请求方式不存在');
}
include $this->template('dynamic');
?>