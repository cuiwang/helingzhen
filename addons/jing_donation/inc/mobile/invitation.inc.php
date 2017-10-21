<?php
defined('IN_IA') or exit('Access Denied');
global $_W,$_GPC;
$this->checkuser();
$id = intval($_GPC['id']);
$item = pdo_fetch("SELECT * FROM ".tablename($this->t_donation)." WHERE id=:id and enabled=1",array(':id'=>$id));
if (empty($item)) {
	message('您访问的活动不存在',referer(),'error');
}
$title = $item['title'];
include $this->template('invitation');
?>