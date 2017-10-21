<?php
defined('IN_IA') or exit('Access Denied');
global $_W,$_GPC;
$this->checkuser();
$id = intval($_GPC['id']);
$sid = intval($_GPC['sid']);
$item = pdo_fetch("SELECT * FROM ".tablename($this->t_donation)." WHERE id=:id and enabled=1",array(':id'=>$id));
if (empty($item)) {
	if ($_W['isajax']) {
		exit(json_encode(array('status'=>1,'info'=>'您访问的活动不存在')));
	}else{
		message('您访问的活动不存在',referer(),'error');
	}
	
}
$invitation = pdo_fetch("SELECT * FROM ".tablename($this->t_invitation)." WHERE id=:id",array(':id'=>$sid));
if (empty($invitation)) {
	if ($_W['isajax']) {
		exit(json_encode(array('status'=>1,'info'=>'您访问的邀请信息不存在')));
	}else{
		message('您访问的邀请信息不存在',referer(),'error');
	}
}
if ($_W['isajax']) {
	pdo_update($this->t_invitation, array('status'=>1), array('id'=>$sid));
	exit(json_encode(array('status'=>0)));
}else{
	$title = $item['title'];
	include $this->template('invitation_success');
}

?>