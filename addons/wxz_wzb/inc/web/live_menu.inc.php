<?php

global $_W,$_GPC;
$uniacid = $_W['uniacid'];
load()->func('tpl');
$rid = intval($_GPC['rid']);
if(empty($rid)){
	message('直播id不存在',$this->createWebUrl('list_list'),'error');
}
$zhibo_list = pdo_fetch("SELECT * FROM ".tablename('wxz_wzb_live_setting')." WHERE uniacid=:uniacid AND rid=:rid",array(':uniacid'=>$uniacid,':rid'=>$rid));
if(empty($zhibo_list)){
	message('此直播不存在',$this->createWebUrl('live_manage'),'error');
}
$menus = pdo_fetchall("SELECT * FROM ".tablename('wxz_wzb_live_menu')." WHERE uniacid=:uniacid AND rid=:rid ORDER BY sort ASC,dateline DESC",array(':uniacid'=>$uniacid,':rid'=>$rid));

include $this->template('live_menu');