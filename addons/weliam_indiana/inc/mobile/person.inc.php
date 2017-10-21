   <!--个人中心-->
<?php 
	global $_W , $GPC;
	$openid = m('user') -> getOpenid();
	$reply = m('member') -> getInfoByOpenid($openid);
	$myCart_num = pdo_fetchcolumn("select count(*) from".tablename('weliam_indiana_cart')."where openid = '{$openid}' and uniacid={$_W['uniacid']}");	
    include $this->template('person');

	?>