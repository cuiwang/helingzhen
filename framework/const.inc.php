<?php
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */

defined('IN_IA') or exit('Access Denied');

define('REGULAR_EMAIL', '/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/i');
define('REGULAR_MOBILE', '/^1\d{10}$/');
define('REGULAR_USERNAME', '/^[\x{4e00}-\x{9fa5}a-z\d_\.]{3,15}$/iu');

define('TEMPLATE_DISPLAY', 0);
define('TEMPLATE_FETCH', 1);
define('TEMPLATE_INCLUDEPATH', 2);

define('ACCOUNT_SUBSCRIPTION', 1);
define('ACCOUNT_SUBSCRIPTION_VERIFY', 3);
define('ACCOUNT_SERVICE', 2);
define('ACCOUNT_SERVICE_VERIFY', 4);
define('ACCOUNT_TYPE_OFFCIAL_NORMAL', 1);
define('ACCOUNT_TYPE_OFFCIAL_AUTH', 3);
define('ACCOUNT_TYPE_APP_NORMAL', 4);


define('ACCOUNT_OAUTH_LOGIN', 3);
define('ACCOUNT_NORMAL_LOGIN', 1);

define('WEIXIN_ROOT', 'https://mp.weixin.qq.com');

define('ACCOUNT_OPERATE_ONLINE', 1);
define('ACCOUNT_OPERATE_MANAGER', 2);
define('ACCOUNT_OPERATE_CLERK', 3);

define('ACCOUNT_MANAGE_NAME_CLERK', 'clerk');
define('ACCOUNT_MANAGE_TYPE_OPERATOR', 1);
define('ACCOUNT_MANAGE_NAME_OPERATOR', 'operator');
define('ACCOUNT_MANAGE_TYPE_MANAGER', 2);
define('ACCOUNT_MANAGE_NAME_MANAGER', 'manager');
define('ACCOUNT_MANAGE_TYPE_OWNER', 3);
define('ACCOUNT_MANAGE_NAME_OWNER', 'owner');
define('ACCOUNT_MANAGE_NAME_FOUNDER', 'founder');
define('ACCOUNT_MANAGE_GROUP_FOUNDER', 1);
define('ACCOUNT_MANAGE_TYPE_VICE_FOUNDER', 4);
define('ACCOUNT_MANAGE_NAME_VICE_FOUNDER', 'vice_founder');
define('ACCOUNT_MANAGE_GROUP_VICE_FOUNDER', 2);
define('ACCOUNT_MANAGE_GROUP_GENERAL', 0);
define('SYSTEM_COUPON', 1);
define('WECHAT_COUPON', 2);
define('COUPON_TYPE_DISCOUNT', '1');define('COUPON_TYPE_CASH', '2');define('COUPON_TYPE_GROUPON', '3');define('COUPON_TYPE_GIFT', '4');define('COUPON_TYPE_GENERAL', '5');define('COUPON_TYPE_MEMBER', '6');define('COUPON_TYPE_SCENIC', '7');define('COUPON_TYPE_MOVIE', '8');define('COUPON_TYPE_BOARDINGPASS', '9');define('COUPON_TYPE_MEETING', '10');define('COUPON_TYPE_BUS', '11');
define('ATTACH_FTP', 1);define('ATTACH_OSS', 2);define('ATTACH_QINIU', 3);define('ATTACH_COS', 4);
define('ATTACH_TYPE_IMAGE', 1);
define('ATTACH_TYPE_VOICE', 2);
define('ATTACH_TYPE_VEDIO', 3);
define('ATTACH_TYPE_NEWS', 4);

define('ATTACH_SAVE_TYPE_FIXED', 1);
define('ATTACH_SAVE_TYPE_TEMP', 2);

define('STATUS_OFF', 0); define('STATUS_ON', 1); define('STATUS_SUCCESS', 0); 
define('CACHE_EXPIRE_SHORT', 60);
define('CACHE_EXPIRE_MIDDLE', 300);
define('CACHE_EXPIRE_LONG', 3600);
define('CACHE_KEY_LENGTH', 100); 
define('CACHE_KEY_MODULE_SETTING', 'module_setting:%s:%s');
define('CACHE_KEY_MODULE_INFO', 'module_info:%s');
define('CACHE_KEY_ACCOUNT_MODULES', 'unimodules:%s:%s');
define('CACHE_KEY_ACCOUNT_MODULES_BINDING', 'unimodules:binding:%s');
define('CACHE_KEY_MEMBER_INFO', 'memberinfo:%s');
define('CACHE_KEY_UNI_GROUP', 'uni_group');
define('CACHE_KEY_ACCOUNT_SWITCH', 'lastaccount:%s');

define('MODULE_SUPPORT_WXAPP', 2);
define('MODULE_SUPPORT_ACCOUNT', 2);

define('PERMISSION_ACCOUNT', 'system');
define('PERMISSION_WXAPP', 'wxapp');
define('PERMISSION_SYSTEM', 'site');

define('PAYMENT_WECHAT_TYPE_NORMAL', 1);
define('PAYMENT_WECHAT_TYPE_BORROW', 2);
define('PAYMENT_WECHAT_TYPE_SERVICE', 3);
define('PAYMENT_WECHAT_TYPE_CLOSE', 4);

define('FANS_CHATS_FROM_SYSTEM', 1);

define('WXAPP_STATISTICS_DAILYVISITTREND', 2);
define('WXAPP_DIY', 1);
define('WXAPP_TEMPLATE', 2);
define('WXAPP_MODULE', 3);

define('MATERIAL_LOCAL', 'local');define('MATERIAL_WEXIN', 'perm');
define('MENU_CURRENTSELF', 1);
define('MENU_CONDITIONAL', 3);

define('USER_STATUS_CHECK', 1);
define('USER_STATUS_NORMAL', 2);
define('USER_STATUS_BAN', 3);

define('PERSONAL_BASE_TYPE', 1);
define('PERSONAL_AUTH_TYPE', 2);
define('PERSONAL_LIST_TYPE', 3);
