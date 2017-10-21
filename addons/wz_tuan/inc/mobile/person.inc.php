<?php
	global $_W, $_GPC;
	$this->getuserinfo();
	load()->model('mc');
	session_start();
	$zkj_num = 0;
	$_SESSION['goodsid']='';
	$_SESSION['tuan_id']='';
	$_SESSION['groupnum']='';
	$share_data = $this -> module['config'];
	$result =  pdo_fetch("select * from" . tablename('wz_tuan_member') . "where openid = :openid limit 1",array(':openid'=>$_W['openid']));
	$zkj_num = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('activity_coupon_record')." WHERE uniacid=:uniacid and uid=:uid and grantmodule=:grantmodule and status = 1",array(':uniacid'=>$_W['uniacid'],':uid'=>$_W['member']['uid'],':grantmodule'=>'wz_tuan'));
	load()->model('account');
	$modules = uni_modules();
	$uid = $_W['member']['uid'];
	$credit=mc_credit_fetch($uid);
	$mycredit = $credit['credit1'];
	include $this->template('person');
?>
