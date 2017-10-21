<?php


global $_W;
global $_GPC;
$uniacid = $_W['uniacid'];
$openid = $_GPC['openid'];
$item = pdo_fetch('select a.*,b.birth,b.register,b.address,b.marriage,b.weight,b.height,b.school,c.avatar as weavatar from ' . tablename('enjoy_recuit_basic') . ' as a left join ' . tablename('enjoy_recuit_info') . ' as b on a.openid=b.openid left join' . "\r\n\t\t\t\t" . ' ' . tablename('enjoy_recuit_fans') . ' as c on a.openid=c.openid where a.openid=\'' . $openid . '\' and a.uniacid=' . $uniacid . '');
$myexpers = pdo_fetchall('select * from ' . tablename('enjoy_recuit_exper') . ' where openid=\'' . $openid . '\' and uniacid=' . $uniacid . '');
$item['exper'] = $myexpers;
$mycard = pdo_fetchall('select * from ' . tablename('enjoy_recuit_card') . ' where openid=\'' . $openid . '\' and uniacid=' . $uniacid . '');
$item['card'] = $mycard;
include $this->template('rdetail');
