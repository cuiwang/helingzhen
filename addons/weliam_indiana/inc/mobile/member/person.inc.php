<?php 
global $_W , $_GPC;
$openid = m('user') -> getOpenid();

$reply = m('member') -> getInfoByOpenid($openid);
$myCart_num = pdo_fetchcolumn("select count(*) from".tablename('weliam_indiana_cart')."where openid = '{$openid}' and uniacid={$_W['uniacid']}");	
$consume = pdo_fetchcolumn("select SUM(num) from".tablename('weliam_indiana_consumerecord')."where openid = '{$openid}' and uniacid = '{$_W['uniacid']}' and num <> 0 and period_number <> '' ");
$recharge = pdo_fetchcolumn("select SUM(num) from".tablename('weliam_indiana_rechargerecord')."where openid = '{$openid}' and status = 1 and uniacid = '{$_W['uniacid']}' and type = 1");

$account = pdo_fetchcolumn("select account from".tablename('weliam_indiana_member')." where openid=:openid",array(':openid'=>$openid));

include $this->template('member/person');
