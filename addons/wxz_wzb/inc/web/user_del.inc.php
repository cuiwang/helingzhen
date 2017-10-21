<?php
global $_W,$_GPC;
$id = intval($_GPC['id']);
$user = pdo_fetch("SELECT * FROM ".tablename('wxz_wzb_user')." WHERE id=:id",array(':id'=>$id));
if(empty($user)){
	message('改用户不存在或是已经被删除',referer());
}else{
	pdo_delete('wxz_wzb_user',array('id'=>$id));
}
message('删除成功',referer());
