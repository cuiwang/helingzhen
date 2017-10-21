<?php

if (!defined('IN_IA')) {
	exit('Access Denied');
}

return array(
'version' => '1.0', 
'id' => 'app', 
'name' => '小程序',
'v3' => true,
	'menu'    => array(
		'title'     => '小程序',
		'plugincom' => 1,
		'icon'      => 'page',
		'items'     => array(
			
			array(
				'title' => '页面设置',
				'items' => array(
					array('title' => '幻灯片', 'route' => 'shop.adv'),
					array('title' => '导航图标', 'route' => 'shop.nav'),
					array('title' => '广告设置', 'route' => 'shop.banner'),
					array('title' => '魔方推荐', 'route' => 'shop.cube'),
					array('title' => '商品推荐', 'route' => 'shop.recommand'),
					array('title' => '公告设置', 'route' => 'shop.notice'),
					array('title' => '排版设置', 'route' => 'shop.sort')
					)
				),
			array(
				'title' => '支付设置',
				'items' => array(
					array('title' => '基本设置', 'route' => 'setting'),
					array('title' => '支付设置', 'route' => 'pay')
					)
				)
			)
		)
	
);

?>