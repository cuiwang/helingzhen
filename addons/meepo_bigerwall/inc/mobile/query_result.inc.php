<?php
global $_W,$_GPC;
$weid = $_W['uniacid'];
$rid = intval($_GPC['rid']);
if($_W['isajax']){
	$data = pdo_fetchall("SELECT * FROM ".tablename('weixin_shake_toshake')." WHERE weid=:weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid));
	if(empty($data)){
		die(json_encode(error(-1,array())));
	}else{
		die(json_encode(error(-1,$data)));
	}
}