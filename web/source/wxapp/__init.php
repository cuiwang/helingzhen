<?php
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
defined('IN_IA') or exit('Access Denied');

if (!in_array($action, array('display', 'post'))) {
	checkwxapp();
}

if (($action == 'version' && in_array($do, array('home', 'module_link_uniacid', 'front_download', 'module_entrance_link'))) || ($action == 'payment')) {
	define('FRAME', 'wxapp');
}
