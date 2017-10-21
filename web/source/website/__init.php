<?php
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
defined('IN_IA') or exit('Access Denied');
if (in_array($action, array('wenda-show'))) {
	define('FRAME', 'website');
} else {
	define('FRAME', 'system');
}
