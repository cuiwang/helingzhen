<?php
global $_W  ,$_GPC;
checklogin();
$uniacid = $_W['uniacid'];
$rid = $_GPC['rid'];
//if($uniacid!=9){
//print_r("新功能建设中....");
//    exit();
//}

$_GPC['do']='bb';
load()->model('reply');
load()->func('tpl');
$sql = "uniacid = :uniacid and `module` = :module";
$params = array();
$params[':uniacid'] = $_W['uniacid'];
$params[':module'] = 'haoman_dpm';
$rowlist = reply_search($sql, $params);
include $this->template('bb_gift');