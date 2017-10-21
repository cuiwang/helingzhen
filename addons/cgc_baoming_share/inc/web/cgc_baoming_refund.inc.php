<?php
require_once IA_ROOT . "/addons/" . $this->modulename . "/inc/common.php";

global $_W, $_GPC;
$settings = $this->module['config'];
load()->func('tpl');
$op = !empty ($_GPC['op']) ? $_GPC['op'] : 'display';
$uniacid = $_W["uniacid"];
$id = $_GPC['id'];
$cgc_baoming_refund = new cgc_baoming_refund();
$user_id = $_GPC['user_id'];
$activity_id = $_GPC['activity_id'];
if ($op == 'display') {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$con = "uniacid=$uniacid and activity_id=$activity_id";


	if (!empty ($user_id)) {
		$con .= " and user_id= $user_id";
	}

	$total = 0;

	$list = $cgc_baoming_refund->getAll($con, $pindex, $psize, $total);

	$pager = pagination($total, $pindex, $psize);
	include $this->template('cgc_baoming_refund');
	exit ();
}

if ($op == 'post') {
	$id = $_GPC['id'];
	$activity_id = $_GPC['activity_id'];
	if (!empty ($id)) {
		$item = $cgc_baoming_refund->getOne($id);
	}

		$data = array (			
			"is_return"=>2,
			"createtime"=>time(),	
		);

		if (!empty ($id)) {
			$temp = $cgc_baoming_refund->modify($id, $data);
		} else {
			$temp = $cgc_baoming_refund->insert($data);
		}
		message('退款成功', $this->createWebUrl('cgc_baoming_refund', array (
			'op' => 'display',
			'activity_id' => $activity_id,
			'user_id' => $user_id
		)), 'success');


	
}

if ($op == 'delete') {
	$id = $_GPC['id'];
	$cgc_baoming_refund->delete($id);
	message('删除成功！', referer(), 'success');
}

if ($op == 'delete_all') {
	$id = $_GPC['activity_id'];
	$cgc_baoming_refund->deleteAll($id);
	message('删除成功！', $this->createWebUrl('cgc_baoming_refund', array (
		'op' => 'display',
		'user_id' => $user_id
	)), 'success');
}

