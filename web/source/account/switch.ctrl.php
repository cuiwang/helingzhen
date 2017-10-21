<?php
/**
 * [WECHAT 2017]
 
 */
defined('IN_IA') or exit('Access Denied');

$uniacid = intval($_GPC['uniacid']);
$role = uni_permission($_W['uid'], $uniacid);
if(empty($role)) {
	message('操作失败, 非法访问.');
}
isetcookie('__uniacid', $uniacid, 7 * 86400);
isetcookie('__uid', $_W['uid'], 7 * 86400);

if($_W['role'] == 'clerk' || $role == 'clerk') {
	header('location: ' . url('activity/desk'));
	die;
}
header('location: ' . url('home/welcome/platform'));