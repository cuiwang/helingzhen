<?php
/**
 * 悟空源 码论 坛bbs . xte c.cc微站定义
 *
 * @author 微赞
 * @url
 */
//superman_creditmall_kv: skey
define('SUPERMAN_SKEY_ACCESS_SETTING', 'access_setting');

//regular
define('SUPERMAN_REGULAR_EMAIL', '/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/i');
define('SUPERMAN_REGULAR_MOBILE', '/1\d{10}/');
define('SUPERMAN_REGULAR_USERNAME', '/^[a-z\d_]{4,16}$/i');
define('SUPERMAN_REGULAR_PASSWORD', '/^\w{6,16}$/i');
if (file_exists(IA_ROOT.'/local.lock')) {
    define('LOCAL_DEVELOPMENT', true);
} else if (file_exists(IA_ROOT.'/online-dev.lock')) {
    define('ONLINE_DEVELOPMENT', true);
}