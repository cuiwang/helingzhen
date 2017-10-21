<?php
defined('IN_IA') or exit('Access Denied');
global $_W,$_GPC;
$this->checkuser();
$id = intval($_GPC['id']);
$item = pdo_fetch("SELECT * FROM ".tablename($this->t_donation)." WHERE id=:id",array(':id'=>$id));
if (empty($item)) {
	exit(json_encode(array('status'=>1,'info'=>'您访问的活动不存在')));
}
$insert = array(
	'uniacid' => $_W['uniacid'],
	'did' => $id,
	'openid' => $_W['openid'],
	'content' => $_GPC['content'],
	'status' => 0,
	'createtime' => time(), 
	);
pdo_insert($this->t_invitation, $insert);
$sid = pdo_insertid();
exit(json_encode(array('status'=>0,'data'=>$sid)));
?>