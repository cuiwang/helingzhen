<?php
global $_W, $_GPC;
$id = intval($_GPC['id']);
$weid = $_W['uniacid'];
if(empty($id)){
	message('活动id不存在');
}
include $this->template('howuse');