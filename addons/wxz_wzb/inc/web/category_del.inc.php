<?php
global $_W,$_GPC;
$uniacid = $_W['uniacid'];
$id = intval($_GPC['id']);
$category = pdo_fetch("SELECT * FROM ".tablename('wxz_wzb_category')." WHERE uniacid=:uniacid AND id=:id",array(':uniacid'=>$uniacid,':id'=>$id));
if(empty($category)){
	message('此项不存在或是已经被删除',referer());
}else{
	$live_lists = pdo_fetchall("SELECT `id` FROM ".tablename('wxz_wzb_live_setting')." WHERE uniacid=:uniacid AND cid=:cid",array(':uniacid'=>$uniacid,':cid'=>$id));
	if(!empty($live_lists) && is_array($live_lists)){
		foreach($live_lists as $row){
			pdo_delete('wxz_wzb_comment',array('rid'=>$row['id'],'uniacid'=>$uniacid));
			pdo_delete('wxz_wzb_live_setting',array('rid'=>$row['id'],'uniacid'=>$uniacid));
			pdo_delete('wxz_wzb_live_menu',array('rid'=>$row['id'],'uniacid'=>$uniacid));
			pdo_delete('wxz_wzb_live_setting',array('id'=>$row['id'],'uniacid'=>$uniacid));
		}
	}
	pdo_delete('wxz_wzb_category',array('id'=>$id,'uniacid'=>$uniacid));
}
message('删除成功',referer());
