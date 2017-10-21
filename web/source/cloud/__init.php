<?php
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */

define('IN_GW', true);
define('FRAME', 'site');
if(in_array($action, array('profile', 'device', 'callback', 'appstore', 'sms'))) {
	$do = $action;
	$action = 'redirect';
}
if($action == 'touch') {
	exit('success');
}
