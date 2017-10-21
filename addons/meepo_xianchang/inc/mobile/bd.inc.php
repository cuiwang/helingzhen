<?php
//录入个人资料
global $_W,$_GPC;
$weid = $_W['uniacid'];
$rid = intval($_GPC['rid']);
$frompage = $_GPC['frompage'];
if(empty($rid) || empty($frompage)){
	message('访问失败了！');
}
$bd_manage = pdo_fetch("SELECT `xm` FROM ".tablename($this->bd_manage_table)." WHERE weid = :weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid));
$bd_manage['xm'] = iunserializer($bd_manage['xm']);
include $this->template('bd');