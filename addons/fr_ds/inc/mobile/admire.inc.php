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
if ($_W['ispost']) {
    $ordersn = genOrderSN();
    $fee = intval($_GPC['fee']);
    if ($fee < 1) {
        exit(json_encode(array('status' => 0, 'msg' => '打赏金额错误')));
    }
    $params = array(
        'module' => 'fr_ds',
        'tid' => $ordersn,
        'ordersn' => $ordersn,
        'title' => '赞赏',
        'fee' => $fee,
    );
    $log = pdo_get('core_paylog', array('uniacid' => $_W['uniacid'], 'module' => $params['module'], 'tid' => $params['tid']));
    //在pay方法中，要检测是否已经生成了paylog订单记录，如果没有需要插入一条订单数据
    //未调用系统pay方法的，可以将此代码放至自己的pay方法中，进行漏洞修复
    if (empty($log)) {
        $log = array(
            'uniacid' => $_W['uniacid'],
            'acid' => $_W['acid'],
            'openid' => $_W['member']['uid'],
            'module' => $params['module'], //模块名称，请保证$this可用
            'tid' => $params['tid'],
            'fee' => $params['fee'],
            'card_fee' => $params['fee'],
            'status' => '0',
            'is_usecard' => '0',
        );
        pdo_insert('core_paylog', $log);
    }
    exit(json_encode(array('status' => 1, 'params' => base64_encode(json_encode($params)))));
}
if (empty($_SESSION['__fr_ds_session'])) {
    message("非法访问！");
}
$result = iunserializer($_SESSION['__fr_ds_session']);
$title = "赞赏" . $result['author'];
$appreciate = $this->module['config']['appreciate'];
if (empty($appreciate)) {
	$appreciate = array(
		"min" => 1,
		"max" => 256,
		"quick" => "1,5,10,20,42,64",
	);
}
$appreciate['quick'] = explode(",", $appreciate['quick']);
include $this->template('admire');