<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2020 http://www.startingline.com.cn All rights reserved.
// +----------------------------------------------------------------------
// | Describe: member data combine
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
global $_W,$_GPC;

$mainid = $_GPC['mainid'];
$combineid = $_GPC['combineid'];
if(empty($mainid) || empty($combineid)){
	m('log')->WL_log('datacombine','数据不能为空','数据不能为空',$_W['uniacid']);
}
m('log')->WL_log('datacombine','数据不能为空','数据不能为空',$_W['uniacid']);
//pdo_delete("weliam_indiana_member",array('uniacid'=>$_W['uniacid'],'openid'=>$combineid));
//
//pdo_delete("ims_weliam_indiana_member",array('uniacid'=>$_W['uniacid'],'openid'=>$combineid));
//$cfans = pdo_fetch("select uid,fanid from".tablename("mc_mapping_fans")."where uniacid=:uniacid and openid=:openid",array(':uniacid'=>$_W['uniacid'],':openid'=>$combineid));
//$mfans = pdo_fetch("select uid,fanid from".tablename("mc_mapping_fans")."where uniacid=:uniacid and openid=:openid",array(':uniacid'=>$_W['uniacid'],':openid'=>$mainid));
//
//$cmember = pdo_fetch("select credit1,credit2 from".tablename("mc_members")."where uniacid=:uniacid and uid=:uid",array(':uniacid'=>$_W['uniacid'],':uid'=>$cfans['uid']));
//$mmember = pdo_fetch("select credit1,credit2 from".tablename("mc_members")."where uniacid=:uniacid and uid=:uid",array(':uniacid'=>$_W['uniacid'],':uid'=>$mfans['uid']));
//
//pdo_update("mc_members",array('credit1'=>$cmember['credit1']+$mmember['credit1'],'credit2'=>$cmember['credit2']+$mmember['credit2']),array('uniacid'=>$_W['uniacid'],'uid'=>$mfans['uid']));
//pdo_delete("mc_members",array('uniacid'=>$_W['uniacid'],'uid'=>$cfans['uid']));
//pdo_delete("mc_mapping_fans",array('uniacid'=>$_W['uniacid'],'openid'=>$combineid));
//pdo_delete("weliam_indiana_cart",array('uniacid'=>$_W['uniacid'],'openid'=>$combineid));
//
//pdo_update("weliam_indiana_invite",array('beinvited_openid'=>$mainid),array('beinvited_openid'=>$combineid));
//pdo_update("weliam_indiana_invite",array('invite_openid'=>$mainid),array('invite_openid'=>$combineid));
//
//$tables = array(
//	"weliam_indiana_consumerecord",
//	"weliam_indiana_discuss",
//	"weliam_indiana_login_session",
//	"weliam_indiana_period",
//	"weliam_indiana_rechargerecord",
//	"weliam_indiana_showprize",
//	"weliam_indiana_withdraw",
//	"weliam_indiana_address",
//	"weliam_indiana_record",
//);
//foreach($tables as $key => $value){
//	pdo_update($value,array('openid'=>$mainid),array('openid'=>$combineid));
//}
?>