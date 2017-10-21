<?php 
/**
 * [WECHAT 2017]
 
 */
defined('IN_IA') or exit('Access Denied');
$_W['page']['title'] = '用户列表 - 用户管理 - 用户管理';
load()->func('tpl');
$user = pdo_fetch('SELECT username, groupid, status, joindate, viptime FROM '.tablename('users').' WHERE `uid` = :uid LIMIT 1',array(':uid' => $_W['uid']));
$user = array(
		'username' => $user['username'],
		'groupid' => $user['groupid'],
		'status' => $user['status'],
		'joindate' => $user['joindate'],
		'viptime' => $user['viptime'],
		);
$viptime = $user['viptime'];
$groupid = $user['groupid'];
$usergroups = pdo_fetch('SELECT * FROM '.tablename('users_group').' WHERE `id` = :id LIMIT 1',array(':id' => $groupid));


template('user/userinfo');
