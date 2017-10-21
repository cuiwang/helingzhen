<?php
/**
 * ������ת����
 */
define('IN_API', true);
require '../framework/bootstrap.inc.php';
require_once '../addons/hsh_tools/include/core.class.php';
define('DEFAULT_GO_URL', 'http://m.hshcs.com/');
define('NOT_SUBSCRIBE_BACK_URL', "http://mp.weixin.qq.com/s?__biz=MzA3NTU5MTAzMQ==&mid=200529207&idx=1&sn=d33b71a3aa6cf21746f1166777ce70af#rd");
$redirectUrl = new RedirectUrl();
$redirectUrl->Index();