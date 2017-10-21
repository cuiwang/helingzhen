<?php
	global $_W,$_GPC;
	$this->getuserinfo();
	$orderno = $_GPC['orderno'];
	$type = $_GPC['type'];
	if($type=='confirm'){
		pdo_update('wz_tuan_order',array('status'=>4),array('orderno'=>$orderno));
	}
	if(!empty($orderno)){
		$sql = 'SELECT * FROM '.tablename('wz_tuan_order').' WHERE orderno=:orderno  and uniacid = :uniacid';
		$params = array(':orderno'=>$orderno , ':uniacid'=>$_W['uniacid']);
		$order = pdo_fetch($sql, $params); 
		if(empty($order)){
			message('获取订单信息失败.'.$id, $this->createMobileUrl('index'));
		}
		$sql = 'SELECT gname,gprice,oprice,gimg,freight FROM '.tablename('wz_tuan_goods').' WHERE id=:gid and uniacid = :uniacid';
		$params = array(':gid'=>$order['g_id'], ':uniacid'=>$_W['uniacid']);
		$goods = pdo_fetch($sql, $params); 
		if(empty($goods)){
			message('获取商品信息失败', $this->createMobileUrl('index'));
		}
	}
	include $this->template('orderdetails');
?>

