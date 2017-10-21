<?php
/**
 * Created by PhpStorm.
 * 任务
 * User: yike
 * Date: 2016/6/6
 * Time: 11:28
 */
if (!defined('IN_IA')) {
    exit('Access Denied');
}
global $_W, $_GPC;
$callback = $_GPC['callback'];
$renwu = pdo_fetch("SELECT * FROM " .tablename('yike_guess_sysset'));
$sets = unserialize($renwu['sets']);
// var_dump($sets);
$_W['member']['uid']=3;
$last_sign = pdo_fetch('select * from '.tablename('yike_members').' where uid = :uid', array(':uid' => $_W['member']['uid']));
// var_dump($last_sign);