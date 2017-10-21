<?php
defined('IN_IA') or exit('Access Denied');
global $_W,$_GPC;
$this->checkuser();
$id = intval($_GPC['id']);
$item = pdo_fetch("SELECT * FROM ".tablename($this->t_donation)." WHERE id=:id",array(':id'=>$id));
if (empty($item)) {
	message('您访问的活动不存在',referer(),'error');
}
if ($item['enabled'] != 1) {
	message('当前活动已经结束',referer(),'error');
}
$price = $_GPC['price'];
if (empty($price)) {
	message('您输入的捐赠金额有误',referer(),'error');
}
$order = array(
	'uniacid' => $_W['uniacid'],
	'did' => $id,
	'openid' => $_W['openid'],
	'ordersn' => date('md') . random(4, 1),
	'price' => $price,
	'realname' => $_GPC['realname'],
	'remark' => $_GPC['remark'],
	'mobile' => $_GPC['mobile'],
	'status' => 0,
	'createtime' => time()
	);
pdo_insert($this->t_order, $order);
$orderid = pdo_insertid();
$order = pdo_fetch("SELECT * FROM ".tablename($this->t_order)." WHERE id=:id",array(':id'=>$orderid));
$params['tid'] = $orderid;
$params['user'] = $_W['fans']['from_user'];
$params['fee'] = $order['price'];
$params['title'] = $_W['account']['name'];
$params['ordersn'] = $order['ordersn'];
$params['virtual'] = $order['return_type'] == 2 ? true : false;
include $this->template('pay');
?>