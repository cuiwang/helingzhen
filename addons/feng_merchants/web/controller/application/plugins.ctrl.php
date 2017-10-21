<?php 
/**
 * [MicroEngine Mall] Copyright (c) 2014 012wz.com
 * MicroEngine Mall is NOT a free software, it under the license terms, visited http://www.012wz.com/ for more details.
 */

defined('IN_IA') or exit('Access Denied');

$ops = array('list', 'create', 'edit', 'delete', 'disable', 'present_get');
$op = in_array($op, $ops) ? $op : 'list';

if ($op == 'list') {
	$_W['page']['title'] = '应用和营销  - 应用列表';
	
	include wl_template('application/plugins_list');
}
