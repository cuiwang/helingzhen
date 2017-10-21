<?php
	global $_W,$_GPC;
	$modulecredit1=$this->module['config']['credit1'];
	$modulecredit2=$this->module['config']['credit2'];
	$isopen=$this->module['config']['creditstatus'];
	if($isopen == 2){
		$openid = m('user') -> getOpenid();
		$member = m('member') -> getInfoByOpenid($openid);
		if(intval($modulecredit1)==0){
			$modulecredit1=1;
		}
		if (checksubmit('submit')) {
			$money = intval($_GPC['money']);
			if($member['credit1'] < (($money/$modulecredit2)*$modulecredit1) || !is_int($money) || $money<1){
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
				$re = m('credit')->updateCredit1($openid,$_W['uniacid'],0-$c1,'积分兑换（积分操作）');
				if($re == -1){
					m('log')->WL_log('check_credit','检查积分兑换存在非法操作',$openid,$_W['uniacid']);exit;
				}
				m('credit')->updateCredit2($openid,$_W['uniacid'],$money,'积分兑换（余额操作）');
				message('积分兑换成功！',$this->createMobileUrl('person'),'success');
			}
		}
		$credit1=m('credit')->getCreditByOpenid($openid,$_W['uniacid']);
		$num = @$credit1['credit1']*$modulecredit2/$modulecredit1;
		$num=intval($num);
		include $this->template('invite/credit1_credit2');
	}else{
		die("<!DOCTYPE html>
            <html>
                <head>
                    <meta name='viewport' content='width=device-width, initial-scale=1, user-scalable=0'>
                    <title>访问出错</title><meta charset='utf-8'><meta name='viewport' content='width=device-width, initial-scale=1, user-scalable=0'><link rel='stylesheet' type='text/css' href='https://res.wx.qq.com/connect/zh_CN/htmledition/style/wap_err1a9853.css'>
                </head>
                <body>
                <div class='page_msg'><div class='inner'><span class='msg_icon_wrp'><i class='icon80_smile'></i></span><div class='msg_content'><h4>该功能被关闭，如需开启请联系管理员u</h4></div></div></div>
                </body>
            </html>");
	}
	
?>