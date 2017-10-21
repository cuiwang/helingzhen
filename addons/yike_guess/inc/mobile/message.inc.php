<?php 
/**
 * Created by PhpStorm.
 * 当前签到信息
 * User: yike
 * Date: 2016/6/6
 * Time: 11:28
 */
if (!defined('IN_IA')) {
    exit('Access Denied');
}
global $_W, $_GPC;
$callback = $_GPC['callback'];
$user = pdo_fetch('select * from '.tablename('yike_members').' where uid = :uid', array(':uid' => $_W['member']['uid']));
$num = $user['sign_num'];
if(date('Y-m-d', time()) > date('Y-m-d', $user['sign_time'] + 24*60*60)){
	$num = 0;
}
if (!empty($num)) {
	show_jsonp(1,$num,$callback);
}