<?php
global $_W,$_GPC;
$weid = $_W['uniacid'];
if($_W['isajax']){
	$rid = intval($_GPC['rid']);
	$isopen = pdo_fetchcolumn("SELECT `isopen` FROM ".tablename('weixin_wall_reply')." WHERE weid=:weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid));
	if($isopen==1){
		die(json_encode(error(0,'活动未开始')));
	}elseif($isopen==2){
		die(json_encode(error(-1,'活动已开始')));
	}else{	
		die(json_encode(error(-2,'活动不存在或是已经被删除！')));
	}
}