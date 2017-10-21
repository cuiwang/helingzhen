<?php
echo "\r\n";

if (!defined('IN_IA')) {
	exit('Access Denied');
}

return array(
	'version' => '1.0',
	'id'      => 'quick',
	'name'    => '快速购买',
	'v3'      => true,
	'menu'    => array(
		'title'     => '页面',
		'plugincom' => 1,
		'icon'      => 'page',
		'items'     => array(
			array(
				'title' => '购买页面',
				'items' => array(
					array('title' => '全部页面', 'route' => 'pages', 'route_must' => 1),
					array('title' => '新建页面', 'route' => 'pages.add')
					)
				),
			array(
				'title' => '公用设置',
				'items' => array(
					array('title' => '幻灯片', 'route' => 'adv')
					)
				)
			)
		)
	);

?>
