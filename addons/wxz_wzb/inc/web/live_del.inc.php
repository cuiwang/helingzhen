<?php

global $_W,$_GPC;
$uniacid = $_W['uniacid'];
load()->func('tpl');
$id = intval($_GPC['id']);
$rid = intval($_GPC['rid']);

$list = pdo_fetch("SELECT * FROM ".tablename('wxz_wzb_live_menu')." WHERE uniacid=:uniacid AND id=:id",array(':uniacid'=>$uniacid,':id'=>$id));
pdo_delete('wxz_wzb_live_menu',array('id'=>$id,'uniacid'=>$uniacid));


message('删除成功',$this->createWebUrl('live_menu',array('rid'=>$rid)),'success');
include $this->template('live_menu');