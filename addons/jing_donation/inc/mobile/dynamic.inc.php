<?php
defined('IN_IA') or exit('Access Denied');
global $_W,$_GPC;
$this->checkuser();
$id = intval($_GPC['id']);
$dynamicid = intval($_GPC['dynamicid']);
$item = pdo_fetch("SELECT * FROM ".tablename($this->t_donation)." WHERE id=:id AND enabled=1",array(':id'=>$id));
if (empty($item)) {
	message('您访问的活动不存在',referer(),'error');
}
$dynamic = pdo_fetch("SELECT * FROM ".tablename($this->t_dynamic)." WHERE id=:id",array(':id'=>$dynamicid));
if (empty($dynamic)) {
	message('您访问的信息不存在',referer(),'error');
}
if (!empty($dynamic['link'])) {
	header("Location:" . $dynamic['link']);
}else{
	$title = $dynamic['title'];
	include $this->template('dynamic');
}
?>