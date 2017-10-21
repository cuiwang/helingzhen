<?php

define('DEFAULT_GO_URL', 'http://m.hshcs.com/');
define('NOT_SUBSCRIBE_BACK_URL', "http://mp.weixin.qq.com/s?__biz=MzA3NTU5MTAzMQ==&mid=200529207&idx=1&sn=d33b71a3aa6cf21746f1166777ce70af#rd");
global $_W, $_GPC;
$redirectUrl = new RedirectUrl();
/*测试内容 ——----begin*/
$targetUrl=$_W['siteroot'] . "app/" .$this->createMobileUrl('rurl',array('rid'=>1),true);
if($_GPC['code'] != ""){
	//var_dump($_GPC['code']);
	echo $redirectUrl->WxHelper->getUserInfo($_GPC['code']);
}
if($_GPC['state'] != ""){
	//var_dump($_GPC['state']);
}
/*测试内容 ——----end*/



$redirectUrl->Index();
