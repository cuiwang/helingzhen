<?php
	global $_W, $_GPC;
	$op = $_GPC['op'];
	$uid = $_W['member']['uid'];
	$share_data = $this->module['config'];
	$openid = m('user') -> getOpenid();
	$now = time();
	if(empty($op)){
		$op='main';
	}
	//待使用优惠券
	if($op=='main'){
		$type='djj';
			$djjs = pdo_fetchall("select * from".tablename('weliam_indiana_coupon_record')."where uniacid={$_W['uniacid']} and couponnum<>0 and endtime>'{$now}' and secondopenid='{$openid}'");
			foreach($djjs as$k=>$v){
				$d = pdo_fetch("select * from".tablename('weliam_indiana_coupon')."where couponid={$v['couponid']} and uniacid={$_W['uniacid']}");
				$djjs[$k]['discount'] = $d['discount'];
				$djjs[$k]['condition'] = $d['condition'];
			}
	}elseif($op=='do'){
	//不可用优惠券
		$type='zkj';
		//查询已经使用的优惠券
			$djjs = pdo_fetchall("select * from".tablename('weliam_indiana_coupon_record')."where uniacid={$_W['uniacid']} and usedcouponnum>0  and secondopenid='{$openid}'");
			foreach($djjs as$k=>$v){
				$d = pdo_fetch("select * from".tablename('weliam_indiana_coupon')."where couponid={$v['couponid']} and uniacid={$_W['uniacid']}");
				$djjs[$k]['discount'] = $d['discount'];
				$djjs[$k]['condition'] = $d['condition'];
			}
		//查询已经过期的优惠券	
			$djjz = pdo_fetchall("select * from".tablename('weliam_indiana_coupon_record')."where uniacid={$_W['uniacid']} and couponnum<>0 and endtime<'{$now}' and secondopenid='{$openid}'");
			foreach($djjz as$k=>$v){
				$d = pdo_fetch("select * from".tablename('weliam_indiana_coupon')."where couponid={$v['couponid']} and uniacid={$_W['uniacid']}");
				$djjz[$k]['discount'] = $d['discount'];
				$djjz[$k]['condition'] = $d['condition'];
			}					
		
	}
	include $this->template('credit');
	
?>
