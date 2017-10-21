<?php
 global $_W,$_GPC;
$weid = $_W['uniacid'];
$rid = intval($_GPC['rid']);
if(!empty($_GPC['img_url'])){
	$img_url = $_GPC['img_url'];
		load()->func('file');
		$check = pdo_fetchcolumn('SELECT `id` FROM '.tablename('weixin_wall_reply').' WHERE 3dsign_logo=:3dsign_logo AND weid=:weid AND rid=:rid',array(':3dsign_logo'=>$img_url,':weid'=>$weid,':rid'=>$rid));
		if(strexists($img_url,'uploadfile') && !empty($check)){
			pdo_update('weixin_wall_reply',array('3dsign_logo'=>''),array('id'=>$check,'rid'=>$rid));
			$del_file = 'yes';
		}else{
			$del_file = 'no';
		}
	  file_delete(MODULE_ROOT . '/' . $img_url);
		die(json_encode(error(0,$del_file)));
}