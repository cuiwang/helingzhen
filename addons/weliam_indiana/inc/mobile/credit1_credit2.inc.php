<?php
	global $_W,$_GPC;
	$modulecredit1=$this->module['config']['credit1'];
	$modulecredit2=$this->module['config']['credit2'];
	$openid = m('user') -> getOpenid();
	$member = m('member') -> getInfoByOpenid($openid);
	if(intval($modulecredit1)==0){
		$modulecredit1=1;
	}
	if (checksubmit('submit')) {
		$money = intval($_GPC['money']);
		if($member['credit1'] < (($money/$modulecredit2)*$modulecredit1) || !is_int($money)){
			//验证所剩积分是否够
			message('积分数量不足，兑换失败！',$this->createMobileUrl('person'),'error');
		}
		
		$ordersn=date('Ymd').substr(time(), -5).substr(microtime(), 2, 5).sprintf('%02d', rand(0, 99));
		$data=array(
				'openid'=>$openid,
				'uniacid'=>$_W['uniacid'],
				'ordersn'=>$ordersn,
				'status'=>1,
				'num'=>$money,
				'createtime' => TIMESTAMP,
				'type'=>3
			);
		if(pdo_insert('weliam_indiana_rechargerecord',$data))
		{
			//积分夺宝币变化
			$c1 = ($money/$modulecredit2)*$modulecredit1;
			m('credit')->updateCredit1($openid,$_W['uniacid'],0-$c1);
			m('credit')->updateCredit2($openid,$_W['uniacid'],$money);
			message('积分兑换成功！',$this->createMobileUrl('person'),'success');
		}
	}
	$credit1=m('credit')->getCreditByOpenid($openid,$_W['uniacid']);
	$num = @$credit1['credit1']*$modulecredit2/$modulecredit1;
	$num=intval($num);
	include $this->template('credit1_credit2');
?>