<?php
/**
 * [WECHAT 2017]
 
 */
defined('IN_IA') or exit('Access Denied');
$do = in_array($do, array('display', 'post', 'delete')) ? $do : 'display';

if($do == 'display') {
} elseif($do == 'post') {
} elseif($do == 'delete') {
}
template('pcweb/pcdiy');

