<?php
echo "\r\n";

if (!defined('IN_IA')) {
	exit('Access Denied');
}

return array(
	'version' => '1.0',
	'id'      => 'task',
	'name'    => '任务中心',
	'v3'      => true,
	'menu'    => array(
		'title'     => '页面',
		'plugincom' => 1,
		'icon'      => 'page',
		'items'     => array(
			array('title' => '海报任务', 'route' => ''),
			array('title' => '单次任务', 'route' => 'extension.single'),
			array('title' => '周期任务', 'route' => 'extension.repeat'),
			array(
				'title' => '系统设置',
				'items' => array(
					array('title' => '通知设置', 'route' => 'default'),
					array('title' => '入口设置', 'route' => 'default.setstart')
					)
				)
			)
		)
	);

?>
