<?php
/**
 * [WECHAT 2017]
 
 */

defined('IN_IA') or exit('Access Denied');
if (in_array($action, array('theme'))) {
	define('FRAME', 'site');
} else {
	define('FRAME', 'system');
}