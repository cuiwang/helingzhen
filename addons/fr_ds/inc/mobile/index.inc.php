<?php
// +----------------------------------------------------------------------
// | Author: 微赞 
// +----------------------------------------------------------------------
defined('IN_IA') or exit('Access Denied');
include MODULE_ROOT . '/inc/common.php';
$uniacid = $_W["uniacid"];
$openid = $_W['openid'];
$skin = $_GPC['skin'] ? trim($_GPC['skin']) : 'default';
$style_url = get_style_url($skin);
if (empty($_SESSION['__fr_ds_session'])) {
    message("非法访问！");
}
$result = iunserializer($_SESSION['__fr_ds_session']);
include $this->template('index');