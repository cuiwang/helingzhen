<?php
	global $_W,$_GPC;
	$money = intval($_GPC['money']);
	$openid = m('user') -> getOpenid();
	if (checksubmit('submit')) {
		$ordersn=date('Ymd').substr(time(), -5).substr(microtime(), 2, 5).sprintf('%02d', rand(0, 99));
		$data=array(
				'openid'=>$openid,
				'uniacid'=>$_W['uniacid'],
				'ordersn'=>$ordersn,
				'status'=>0,
				'num'=>$money,
				'createtime' => TIMESTAMP,
				'type'=>1
			);
		if(pdo_insert('weliam_indiana_rechargerecord',$data))
		{
			$orderid = pdo_insertid();
			header("location:".$this->createMobileUrl('pay', array('type'=>'recharge','id' => $orderid)));
		}else{
			message('提交失败！');
		}
	}
	
	include $this->template('recharge');
?>