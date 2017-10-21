<?php
global $_W, $_GPC;
$id = intval($_GPC['id']);
$weid = $_W['uniacid'];
if(empty($id)){
	message('活动id不存在');
}
$ridwall = pdo_fetch("SELECT * FROM ".tablename('weixin_wall_reply')." WHERE rid = :rid AND weid=:weid", array(':rid'=>$id,':weid'=>$weid));	
$weid = $_W['uniacid'];
$op = empty($_GPC['op'])? 'list': $_GPC['op'];
if($op=='list'){
$sign_url = str_replace('./','',$_W['siteroot'].'app/'.$this->createMobileUrl('qd',array('rid'=>$id)));
$msg_url = str_replace('./','',$_W['siteroot'].'app/'.$this->createMobileUrl('msg',array('rid'=>$id)));
$shake_url = str_replace('./','',$_W['siteroot'].'app/'.$this->createMobileUrl('shakehands',array('rid'=>$id)));
$tp_url = str_replace('./','',$_W['siteroot'].'app/'.$this->createMobileUrl('votehtml',array('rid'=>$id)));
$bahe_url = str_replace('./','',$_W['siteroot'].'app/'.$this->createMobileUrl('app_bahe',array('rid'=>$id)));
include $this->template('url');
}elseif($op=='change'){
	$type = $_GPC['type'];
	$status = intval($_GPC['status']);
	if($type=='qd'){
			pdo_update('weixin_wall_reply',array('qdqshow'=>$status),array('id'=>$ridwall['id'],'weid'=>$weid));
	}elseif($type=='tp'){
			pdo_update('weixin_wall_reply',array('tpshow'=>$status),array('id'=>$ridwall['id'],'weid'=>$weid));
	}elseif($type=='yyy'){
			pdo_update('weixin_wall_reply',array('yyyshow'=>$status),array('id'=>$ridwall['id'],'weid'=>$weid));
	}elseif($type=='bahe'){
			pdo_update('weixin_wall_reply',array('baheshow'=>$status),array('id'=>$ridwall['id'],'weid'=>$weid));
	}
	die('success');
}
