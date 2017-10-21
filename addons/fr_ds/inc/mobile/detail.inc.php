<?php
// +----------------------------------------------------------------------
// | Author: 微赞 
// +----------------------------------------------------------------------
defined('IN_IA') or exit('Access Denied');
include MODULE_ROOT . '/inc/common.php';
$uniacid = $_W["uniacid"];
$openid = $_W['openid'];
if (empty($_SESSION['__fr_ds_session'])) {
    message("非法访问！");
}
$result = iunserializer($_SESSION['__fr_ds_session']);
$pageIndex = max(1, $_GPC['page']);
$list = getPageList('record', $pageIndex, " AND status = 1 AND sign = '{$result['sign']}'", ' id DESC', 120);
if ($list['list']) {
    foreach ($list['list'] as $key => $value) {
        $list['list'][$key]['avatar'] = getMemberAvatar($value['openid']);
    }
}
include $this->template('detail');