<?php
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
defined('IN_IA') or exit('Access Denied');
global $_W;
$we7_system_menu = array();
if ($_W['isfounder']) {
$we7_system_menu['system'] = array(
	'title' => '系统管理',
	'icon' => 'wi wi-setting',
	'url' => url('home/welcome/system'),
	'section' => array(
		'wxplatform' => array(
			'title' => '公众号',
			'menu' => array(
				'system_account' => array(
					'title' => ' 微信公众号',
					'url' => url('account/manage', array('account_type' => '1')),
					'icon' => 'wi wi-wechat',
					'permission_name' => 'system_account',
					'sub_permission' => array(
						array(
							'title' => '公众号管理设置',
							'permission_name' => 'system_account_manage',
						),
						array(
							'title' => '添加公众号',
							'permission_name' => 'system_account_post',
						),
						array(
							'title' => '公众号停用',
							'permission_name' => 'system_account_stop',
						),
						array(
							'title' => '公众号回收站',
							'permission_name' => 'system_account_recycle',
						),
						array(
							'title' => '公众号删除',
							'permission_name' => 'system_account_delete',
						),
						array(
							'title' => '公众号恢复',
							'permission_name' => 'system_account_recover',
						),
					),
				),
				'extension_module' => array(
					'title' => '公众号模块',
					'url' => url('extension/module', array('account_type' => '1')),
					'icon' => 'wi wi-wx-apply',
					'permission_name' => 'extension_module',
					'founder' => true,
				),
				'system_template' => array(
					'title' => '微官网模板',
					'url' => url('system/template'),
					'icon' => 'wi wi-wx-template',
					'permission_name' => 'system_template',
				),
				'system_platform' => array(
					'title' => ' 微信开放平台',
					'url' => url('system/platform'),
					'icon' => 'wi wi-exploitsetting',
					'permission_name' => 'system_platform',
				),
				'system_subscribe_subscribe' => array(
					'title' => '模块订阅管理',
					'url' => url('system/subscribe/subscribe'),
					'icon' => 'wi wi-exploitsetting',
					'permission_name' => 'system_subscribe_subscribe',
				),
				'system_service_display' => array(
					'title' => '常用服务API',
					'url' => url('system/service/display'),
					'icon' => 'wi wi-exploitsetting',
					'permission_name' => 'system_service_display',
				),
			)
		),
		'module' => array(
			'title' => '小程序',
			'menu' => array(
				'system_wxapp' => array(
					'title' => '微信小程序',
					'url' => url('account/manage', array('account_type' => '4')),
					'icon' => 'wi wi-wxapp',
					'permission_name' => 'system_wxapp',
					'sub_permission' => array(
						array(
							'title' => '小程序管理设置',
							'permission_name' => 'system_wxapp_manage',
						),
						array(
							'title' => '添加小程序',
							'permission_name' => 'system_wxapp_post',
						),
						array(
							'title' => '小程序停用',
							'permission_name' => 'system_wxapp_stop',
						),
						array(
							'title' => '小程序回收站',
							'permission_name' => 'system_wxapp_recycle',
						),
						array(
							'title' => '小程序删除',
							'permission_name' => 'system_wxapp_delete',
						),
						array(
							'title' => '小程序恢复',
							'permission_name' => 'system_wxapp_recover',
						),
					),
				),
				'system_module_wxapp' => array(
					'title' => '小程序应用',
					'url' => url('module/manage-system', array('account_type' => '4')),
					'icon' => 'wi wi-wxapp-apply',
					'permission_name' => 'system_module_wxapp',
				),
			)
		),
		'shop' => array(
			'title' => '应用商店',
			'menu' => array(
				'system_shop_module' => array(
					'title' => '应用管理',
					'url' => url('shop/module'),
					'icon' => 'wi wi-wxapp',
					'permission_name' => 'system_shop_module',
				),
				'system_shop_member_record' => array(
					'title' => '消费记录',
					'url' => url('shop/member/record'),
					'icon' => 'wi wi-wxapp-apply',
					'permission_name' => 'system_shop_member_record',
				),
				'system_shop_member_chongzhi' => array(
					'title' => '充值记录',
					'url' => url('shop/member/chongzhi'),
					'icon' => 'wi wi-wxapp-apply',
					'permission_name' => 'system_shop_member_chongzhi',
				),
				'system_shop_mpayset_payset' => array(
					'title' => '支付设置',
					'url' => url('shop/mpayset/payset'),
					'icon' => 'wi wi-wxapp-apply',
					'permission_name' => 'system_shop_mpayset_payset',
				),
				'system_shop_taocan' => array(
					'title' => '套餐绑定',
					'url' => url('shop/taocan'),
					'icon' => 'wi wi-wxapp-apply',
					'permission_name' => 'system_shop_taocan',
				),
			)
		),
		'user' => array(
			'title' => '帐户/用户',
			'menu' => array(
				'system_my' => array(
					'title' => '我的帐户',
					'url' => url('user/profile'),
					'icon' => 'wi wi-user',
					'permission_name' => 'system_my',
				),
				'system_user' => array(
					'title' => '用户管理',
					'url' => url('user/display'),
					'icon' => 'wi wi-user-group',
					'permission_name' => 'system_user',
					'sub_permission' => array(
						array(
							'title' => '编辑用户',
							'permission_name' => 'system_user_post',
						),
						array(
							'title' => '审核用户',
							'permission_name' => 'system_user_check',
						),
						array(
							'title' => '用户回收站',
							'permission_name' => 'system_user_recycle',
						),
						array(
							'title' => '用户属性设置',
							'permission_name' => 'system_user_fields',
						),
						array(
							'title' => '用户属性设置-编辑字段',
							'permission_name' => 'system_user_fields_post',
						),
						array(
							'title' => '用户注册设置',
							'permission_name' => 'system_user_registerset',
						),
					),
				),
				'system_ymmanage' => array(
					'title' => '用户域名',
					'url' => url('user/ymmanage'),
					'icon' => 'wi wi-user-group',
					'permission_name' => 'system_ymmanage',
				),
			)
		),
		'permission' => array(
			'title' => '权限管理',
			'menu' => array(
				'system_module_group' => array(
					'title' => '应用权限组',
					'url' => url('module/group'),
					'icon' => 'wi wi-appjurisdiction',
					'permission_name' => 'system_module_group',
					'sub_permission' => array(
						array(
							'title' => '添加应用权限组',
							'permission_name' => 'system_module_group_add',
						),
						array(
							'title' => '编辑应用权限组',
							'permission_name' => 'system_module_group_post',
						),
						array(
							'title' => '删除应用权限组',
							'permission_name' => 'system_module_group_del',
						),
					),
				),
				'system_user_group' => array(
					'title' => '用户权限组',
					'url' => url('user/group'),
					'icon' => 'wi wi-userjurisdiction',
					'permission_name' => 'system_user_group',
					'sub_permission' => array(
						array(
							'title' => '添加用户组',
							'permission_name' => 'system_user_group_add',
						),
						array(
							'title' => '编辑用户组',
							'permission_name' => 'system_user_group_post',
						),
						array(
							'title' => '删除用户组',
							'permission_name' => 'system_user_group_del',
						),
					),
				),
			)
		),
		'article' => array(
			'title' => '文章/公告',
			'menu' => array(
				'system_article' => array(
					'title' => '新闻管理',
					'url' => url('article/news'),
					'icon' => 'wi wi-article',
					'permission_name' => 'system_article_news',
				),
				'system_article_notice' => array(
					'title' => '公告管理',
					'url' => url('article/notice'),
					'icon' => 'wi wi-notice',
					'permission_name' => 'system_article_notice',
				),
				'system_article_about' => array(
					'title' => '关于我们',
					'url' => url('article/about'),
					'icon' => 'wi wi-article',
					'permission_name' => 'system_article_about',
				),
				'system_article_case' => array(
					'title' => '案例管理',
					'url' => url('article/case'),
					'icon' => 'wi wi-article',
					'permission_name' => 'system_article_case',
				),
				'system_article_product' => array(
					'title' => '产品管理',
					'url' => url('article/product'),
					'icon' => 'wi wi-article',
					'permission_name' => 'system_article_product',
				),
				'system_article_agent' => array(
					'title' => '代理公司',
					'url' => url('article/agent'),
					'icon' => 'wi wi-article',
					'permission_name' => 'system_article_agent',
				),
				'website_wenda' => array(
					'title' => '问答系统',
					'url' => url('website/wenda'),
					'icon' => 'wi wi-article',
					'permission_name' => 'website_wenda',
				),
				'system_article_link' => array(
					'title' => '友情链接',
					'url' => url('article/link'),
					'icon' => 'wi wi-article',
					'permission_name' => 'system_article_link',
				),
			)
		),
		'cache' => array(
			'title' => '缓存',
			'menu' => array(
				'system_setting_updatecache' => array(
					'title' => '更新缓存',
					'url' => url('system/updatecache'),
					'icon' => 'wi wi-update',
					'permission_name' => 'system_setting_updatecache',
				),
			),
		),
	),
);
}else{
$we7_system_menu['system'] = array(
	'title' => '系统管理',
	'url' => url('home/welcome/system'),
	'section' => array(
		'wxplatform' => array(
			'title' => '公众号',
			'menu' => array(
				'system_account' => array(
					'title' => ' 微信公众号',
					'url' => url('account/manage', array('account_type' => '1')),
					'icon' => 'wi wi-wechat',
					'permission_name' => 'system_account',
					'sub_permission' => array(
						array(
							'title' => '公众号管理设置',
							'permission_name' => 'system_account_manage',
						),
						array(
							'title' => '添加公众号',
							'permission_name' => 'system_account_post',
						),
						array(
							'title' => '公众号停用',
							'permission_name' => 'system_account_stop',
						),
						array(
							'title' => '公众号回收站',
							'permission_name' => 'system_account_recycle',
						),
						array(
							'title' => '公众号删除',
							'permission_name' => 'system_account_delete',
						),
						array(
							'title' => '公众号恢复',
							'permission_name' => 'system_account_recover',
						),
					),
				),
			)
		),
		'module' => array(
			'title' => '小程序',
			'menu' => array(
				'system_wxapp' => array(
					'title' => '微信小程序',
					'url' => url('account/manage', array('account_type' => '4')),
					'icon' => 'wi wi-wxapp',
					'permission_name' => 'system_wxapp',
					'sub_permission' => array(
						array(
							'title' => '小程序管理设置',
							'permission_name' => 'system_wxapp_manage',
						),
						array(
							'title' => '添加小程序',
							'permission_name' => 'system_wxapp_post',
						),
						array(
							'title' => '小程序停用',
							'permission_name' => 'system_wxapp_stop',
						),
						array(
							'title' => '小程序回收站',
							'permission_name' => 'system_wxapp_recycle',
						),
						array(
							'title' => '小程序删除',
							'permission_name' => 'system_wxapp_delete',
						),
						array(
							'title' => '小程序恢复',
							'permission_name' => 'system_wxapp_recover',
						),
					),
				),
			)
		),
		'user' => array(
			'title' => '帐户/用户',
			'menu' => array(
				'system_my' => array(
					'title' => '我的帐户',
					'url' => url('user/profile'),
					'icon' => 'wi wi-user',
					'permission_name' => 'system_my',
				),
				'system_my_xiaji' => array(
					'title' => '下级用户',
					'url' => url('user/myxiaji'),
					'icon' => 'wi wi-user',
					'permission_name' => 'system_my',
				),
				'system_shop_member' => array(
					'title' => '余额充值',
					'url' => url('shop/member/member'),
					'icon' => 'wi wi-user',
					'permission_name' => 'system_shop_member_member',
				),
				'system_shop_member_record' => array(
					'title' => '消费记录',
					'url' => url('shop/member/record'),
					'icon' => 'wi wi-wxapp-apply',
					'permission_name' => 'system_shop_member_record',
				),
				'system_shop_member_chongzhi' => array(
					'title' => '充值记录',
					'url' => url('shop/member/chongzhi'),
					'icon' => 'wi wi-wxapp-apply',
					'permission_name' => 'system_shop_member_chongzhi',
				),
			)
		),
		'permission' => array(
			'title' => '权限管理',
			'menu' => array(
				'system_module_group' => array(
					'title' => '应用权限组',
					'url' => url('system/module-group'),
					'icon' => 'wi wi-appjurisdiction',
					'permission_name' => 'system_module_group',
				),
			)
		),
		'userset' => array(
			'title' => '高级工具',
			'menu' => array(
				'user_set_yuming' => array(
					'title' => '域名绑定',
					'url' => url('user/set/yuming'),
					'icon' => 'wi wi-appjurisdiction',
					'permission_name' => 'user_set_yuming',
				),
				'user_set_copyright' => array(
					'title' => '版权设置',
					'url' => url('user/set/copyright'),
					'icon' => 'wi wi-appjurisdiction',
					'permission_name' => 'user_set_copyright',
				),
				'user_set_pifu' => array(
					'title' => '自定义皮肤',
					'url' => url('user/set/pifu'),
					'icon' => 'wi wi-appjurisdiction',
					'permission_name' => 'user_set_pifu',
				),
			)
		),
		'cache' => array(
			'title' => '缓存',
			'menu' => array(
				'system_setting_updatecache' => array(
					'title' => '更新缓存',
					'url' => url('system/updatecache'),
					'icon' => 'wi wi-update',
					'permission_name' => 'system_setting_updatecache',
				),
			),
		),
	),
);
}
$we7_system_menu['account'] = array(
	'title' => '公众号管理',
	'icon' => 'wi wi-white-collar',
	'url' => url('home/welcome/platform'),
	'section' => array(
		'platform_plus' => array(
			'title' => '增强功能',
			'menu' => array(
				'platform_reply' => array(
					'title' => '自动回复',
					'url' => url('platform/reply'),
					'icon' => 'wi wi-reply',
					'permission_name' => 'platform_reply',
					'sub_permission' => array(
																																																																													),
				),
				'platform_menu' => array(
					'title' => '自定义菜单',
					'url' => url('platform/menu'),
					'icon' => 'wi wi-custommenu',
					'permission_name' => 'platform_menu',
				),
				'platform_qr' => array(
					'title' => '二维码/转化链接',
					'url' => url('platform/qr'),
					'icon' => 'wi wi-qrcode',
					'permission_name' => 'platform_qr',
					'sub_permission' => array(
																																																					),
				),
				//'platform_mass_task' => array(
					//'title' => '定时群发',
					//'url' => url('platform/mass'),
					//'icon' => 'wi wi-crontab',
					//'permission_name' => 'platform_mass_task',
				//),
				'platform_material' => array(
					'title' => '素材/编辑器',
					'url' => url('platform/material'),
					'icon' => 'wi wi-redact',
					'permission_name' => 'platform_material',
				),
				'platform_site' => array(
					'title' => '微官网-文章',
					'url' => url('site/multi/display'),
					'icon' => 'wi wi-home',
					'permission_name' => 'platform_site',
					'sub_permission' => array(
																																																					),
				)
			),
		),
		'platform_module' => array(
			'title' => '应用模块',
			'menu' => array(),
			'is_display' => true,
		),
		'mc' => array(
			'title' => '粉丝',
			'menu' => array(
				'mc_fans' => array(
					'title' => '粉丝管理',
					'url' => url('mc/fans'),
					'icon' => 'wi wi-fansmanage',
					'permission_name' => 'mc_fans',
				),
				'mc_member' => array(
					'title' => '会员管理',
					'url' => url('mc/member'),
					'icon' => 'wi wi-fans',
					'permission_name' => 'mc_member',
				)
			),
		),
		'profile' => array(
			'title' => '配置',
			'menu' => array(
				'profile' => array(
					'title' => '参数配置',
					'url' => url('profile/passport'),
					'icon' => 'wi wi-parameter-setting',
					'permission_name' => 'profile_setting',
				),
				'payment' => array(
					'title' => '支付参数',
					'url' => url('profile/payment'),
					'icon' => 'wi wi-pay-setting',
					'permission_name' => 'profile_setting',
				),
			),
		),
		
		'shop' => array(
			'title' => '应用商店',
			'menu' => array(
				'shop_mymodule' => array(
					'title' => '应用商店',
					'url' => url('shop/mymodule'),
					'icon' => 'wi wi-wxapp',
					'permission_name' => 'shop_mymodule',
					'sub_permission' => array(
							array('title' => '模块购买',
								'permission_name' => 'shop_morder_post',
							),
						),
				),
				'shop_mrecord' => array(
					'title' => '消费记录',
					'url' => url('shop/mrecord'),
					'icon' => 'wi wi-wxapp',
					'permission_name' => 'shop_mrecord',
				),
			),
		),
		'fournet' => array(
			'title' => '四网融合',
			'menu' => array(
				'fournet_wxauth_mplis' => array(
					'title' => '多平台绑定',
					'url' => url('fournet/wxauth/mplist'),
					'icon' => 'wi wi-wxapp',
					'permission_name' => 'fournet_wxauth_mplis',
				),
				'fournet_domain_manage' => array(
					'title' => '域名管理',
					'url' => url('fournet/domain/manage'),
					'icon' => 'wi wi-wxapp',
					'permission_name' => 'fournet_domain_manage',
				),
				'fournet_msg' => array(
					'title' => '全局短信设置',
					'url' => url('fournet/msg'),
					'icon' => 'wi wi-wxapp',
					'permission_name' => 'fournet_msg',
				),
				'fournet_print' => array(
					'title' => '全局打印机设置',
					'url' => url('fournet/print'),
					'icon' => 'wi wi-wxapp',
					'permission_name' => 'fournet_print',
				),
				'fournet_cron' => array(
					'title' => '全局计划任务',
					'url' => url('cron/display'),
					'icon' => 'wi wi-wxapp',
					'permission_name' => 'fournet_cron',
				),
			),
		),
	),
);

$we7_system_menu['wxapp'] = array(
	'title' => '小程序管理',
	'icon' => 'wi wi-small-routine',
	'url' => url('wxapp/display/home'),
	'section' => array(
		'wxapp_entrance' => array(
			'title' => '小程序入口',
			'menu' => array(
				'module_link' => array(
					'title' => "入口页面",
					'url' => url('wxapp/version/module_entrance_link'),
					'is_display' => 1,
					'icon' => 'wi wi-data-synchro',
					'permission_name' => 'wxapp_module_entrance_link',
				),
			),
			'is_display' => true,
		),
		'wxapp_module' => array(
			'title' => '应用',
			'menu' => array(),
			'is_display' => true,
		),
		'platform_manage_menu' => array(
			'title' => '管理',
			'menu' => array(
				'module_link' => array(
					'title' => "数据同步",
					'url' => url('wxapp/version/module_link_uniacid'),
					'is_display' => 1,
					'icon' => 'wi wi-data-synchro',
					'permission_name' => 'wxapp_module_link_uniacid',
				),
				'wxapp_profile' => array(
					'title' => '支付参数',
					'url' => url('wxapp/payment'),
					'is_display' => 1,
					'icon' => 'wi wi-appsetting',
					'permission_name' => 'wxapp_payment',
				),
				'front_download' => array(
					'title' => '小程序下载',
					'url' => url('wxapp/version/front_download'),
					'is_display' => 1,
					'icon' => 'wi wi-wxapp-download',
					'permission_name' => 'wxapp_front_download',
				)
			)
		)
	),
);

$we7_system_menu['module'] = array(
	'title' => '应用',
	'icon' => 'wi wi-apply',
	'url' => url('module/display'),
	'section' => array(),
	'is_display' => 0
);

$we7_system_menu['site'] = array(
	'title' => '站点管理',
	'icon' => 'wi wi-system-site',
	'url' => url('system/site'),
	'section' => array(
		'cloud' => array(
			'title' => '云服务',
			'menu' => array(
				'system_profile' => array(
					'title' => '系统升级',
					'url' => url('cloud/up'),
					'icon' => 'wi wi-cache',
					'permission_name' => 'system_cloud_upgrade',
				),
				//'system_cloud_register' => array(
					//'title' => '注册站点',
					//'url' => url('cloud/profile'),
					//'icon' => 'wi wi-registersite',
					//'permission_name' => 'system_cloud_register',
				//),
				//'system_cloud_diagnose' => array(
				//	'title' => '云服务诊断',
				//	'url' => url('cloud/diagnose'),
				//	'icon' => 'wi wi-diagnose',
				//	'permission_name' => 'system_cloud_diagnose',
				//),
				//'system_cloud_addons' => array(
				//	'title' => '切换商城',
				//	'url' => url('system/addons'),
				//	'icon' => 'wi wi-wx-apply',
				//	'permission_name' => 'system_cloud_addons',
				//),
			)
		),
		'setting' => array(
			'title' => '设置',
			'menu' => array(
				'system_setting_site' => array(
					'title' => '站点设置',
					'url' => url('system/site'),
					'icon' => 'wi wi-site-setting',
					'permission_name' => 'system_setting_site',
				),
				'system_setting_theme' => array(
					'title' => '后台皮肤',
					'url' => url('extension/theme/web'),
					'icon' => 'wi wi-log',
					'permission_name' => 'system_setting_theme',
				),
				'system_setting_mbsite' => array(
					'title' => '手机站设置',
					'url' => url('system/mbsite'),
					'icon' => 'wi wi-log',
					'permission_name' => 'system_setting_mbsite',
				),
				'system_setting_menu' => array(
					'title' => '菜单设置',
					'url' => url('system/menu'),
					'icon' => 'wi wi-menu-setting',
					'permission_name' => 'system_setting_menu',
				),
				'system_setting_attachment' => array(
					'title' => '附件设置',
					'url' => url('system/attachment'),
					'icon' => 'wi wi-attachment',
					'permission_name' => 'system_setting_attachment',
				),
				'system_setting_systeminfo' => array(
					'title' => '系统信息',
					'url' => url('system/systeminfo'),
					'icon' => 'wi wi-system-info',
					'permission_name' => 'system_setting_systeminfo',
				),
				'system_setting_logs' => array(
					'title' => '查看日志',
					'url' => url('system/logs'),
					'icon' => 'wi wi-log',
					'permission_name' => 'system_setting_logs',
				),
				'system_setting_ipwhitelist' => array(
					'title' => 'IP白名单',
					'url' => url('system/ipwhitelist'),
					'icon' => 'wi wi-ip',
					'permission_name' => 'system_setting_ipwhitelist',
				),
			)
		),
		'utility' => array(
			'title' => '常用工具',
			'menu' => array(
				//'system_utility_filecheck' => array(
					//'title' => '系统文件校验',
					//'url' => url('system/filecheck'),
					//'icon' => 'wi wi-file',
					//'permission_name' => 'system_utility_filecheck',
				//),
				'system_utility_optimize' => array(
					'title' => '性能优化',
					'url' => url('system/optimize'),
					'icon' => 'wi wi-optimize',
					'permission_name' => 'system_utility_optimize',
				),
				'system_utility_database' => array(
					'title' => '数据库',
					'url' => url('system/database'),
					'icon' => 'wi wi-sql',
					'permission_name' => 'system_utility_database',
				),
				//'system_utility_scan' => array(
					//'title' => '木马查杀',
					//'url' => url('system/scan'),
					//'icon' => 'wi wi-safety',
					//'permission_name' => 'system_utility_scan',
			//	),
				'system_utility_bom' => array(
					'title' => '检测文件BOM',
					'url' => url('system/bom'),
					'icon' => 'wi wi-bom',
					'permission_name' => 'system_utility_bom',
				),
			)
		),
	),
	'founder' => true,
);
$we7_system_menu['cloud'] = array(
	'title' => '系统升级',
	'url' => url('cloud/up'),
	'section' => array(),
);

//$we7_system_menu['pcweb'] = array(
//	'title' => 'PC网站管理',
//	'url' => url('pcweb/pcmulti'),
//	'section' => array(),
//);
//$we7_system_menu['app'] = array(
//	'title' => 'APP管理',
//	'url' => url('app/list'),
//	'section' => array(),
//);
//$we7_system_menu['appmarket'] = array(
//	'title' => '应用市场',
//	'url' => url('cloud/appstore'),
//	'section' => array(),
//	'blank' => true,
//	'founder' => true,
//);
return $we7_system_menu;