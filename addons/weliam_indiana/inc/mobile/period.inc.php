<?php
global $_W,$_GPC;
	if (empty($_GPC['id']) or empty($_GPC['sid'])) {
        message('抱歉，参数错误！', '', 'error');
    }
    $openid = m('user') -> getOpenid();
	$goods = pdo_fetch("SELECT * FROM " . tablename('weliam_indiana_goodslist') . " WHERE uniacid= '{$_W['uniacid']}' AND id= '{$_GPC['id']}'");
	$allgoods = pdo_fetchall("SELECT * FROM " . tablename('weliam_indiana_goodslist') . " WHERE uniacid= '{$_W['uniacid']}' AND sid= '{$_GPC['sid']}' AND status = 0 ORDER BY q_end_time DESC");

	include $this->template('period');
?>