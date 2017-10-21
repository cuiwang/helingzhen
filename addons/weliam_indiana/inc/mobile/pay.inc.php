<?php
	global $_W,$_GPC;
	$openid = m('user') -> getOpenid();
	if($_GPC['type']=='credit2'){
		//余额支付
		$num_money = pdo_fetchall("select * from".tablename('weliam_indiana_cart')."where uniacid = '{$_W['uniacid']}' and openid = '{$openid}'");
		$money = 0;
		foreach($num_money as $key =>$value){
			$goodsid = pdo_fetchcolumn("select goodsid from".tablename('weliam_indiana_period')."where period_number = '{$value['period_number']}'");
			$init_money = pdo_fetchcolumn("select init_money from".tablename('weliam_indiana_goodslist')."where id = '{$goodsid}'");
			$money = $money+$init_money*$value['num'];
		}
		$uniacid = $_GPC['uniacid'];
		$ordersn = $_GPC['ordersn'];
		$thismember = m('member') -> getInfoByOpenid($openid);
		$credit2 = $thismember['credit2']-$money;
		if($credit2>=0){
			
			if(m('codes')->code($openid,$ordersn,$uniacid)){
				//通知
				/****************自己购买返回积分开始*****************/
				$credit_num = $this->module['config']['buy_followed'];
				if($credit_num > 0){
					$sql = "select * from".tablename('weliam_indiana_invite')."where uniacid=:uniacid and invite_openid=:invite_openid and type=:type";
					$data = array(
						':uniacid'=>$_W['uniacid'],
						':invite_openid'=>$openid,
						':type'=>2
					);
					$result = pdo_fetch($sql,$data);
					if(empty($result)){
						$numi = $credit_num*$money;
						$datam = array(
							'uniacid'=>$_W['uniacid'],
							'beinvited_openid'=>'yourself',
							'invite_openid'=>$openid,
							'createtime'=>time(),
							'credit1'=>$numi,
							'type'=>2
						);
						$ins = pdo_insert("weliam_indiana_invite", $datam);
					}else{
						$numu = $result['credit1']+$credit_num*$money;
						$upd = pdo_update("weliam_indiana_invite",array('credit1'=>$numu),array('uniacid'=>$_W['uniacid'],'type'=>2,'invite_openid'=>$openid));
					}
					m('credit')->updateCredit1($openid,$_W['uniacid'],$credit_num*$money);
				}
				/****************自己购买返回积分结束*****************/
				$url2 = $_W['siteroot'] . 'app/' . $this -> createMobileUrl('order');
				$tpl_id_short = $this->module['config']['m_pay'];
				$data  = array(
					"name"=>array( "value"=> "余额支付成功！预祝中大奖！","color"=>"#173177"),
					"remark"=>array('value' => "\r点击查看详情！", "color" => "#4a5077"),
				);
				m('common')->sendTplNotice($openid,$tpl_id_short,$data,$url2,'');
				
				$level=$this->module['config']['level'];
				if($level==1){
					$level1=$this->module['config']['level1'];
					$invites=m('invite')->getInvitesByOpenid($openid,$_W['uniacid']);
					foreach($invites as$key=>$value){
						m('credit')->updateCredit1($value['invite_openid'],$_W['uniacid'],$level1*$money);
						m('invite')->updateBy2Openid($openid,$value['invite_openid'],$_W['uniacid'],$level1*$money);
					}
				}
				die(json_encode(array("result" => 1,"money"=>$money)));	
			}else{
				die(json_encode(array("result" => 0,"why"=>'本期结束，购买失败!')));
			}
		}else{
			die(json_encode(array("result" => 0,"why"=>'余额不足')));	
		}
	}elseif($_GPC['type']=='recharge'){
		//充值
		$id = $_GPC['id'];
		$thisrecharge = pdo_fetch("select * from".tablename('weliam_indiana_rechargerecord')."where id={$id} ");
		$params['tid'] = $thisrecharge['ordersn'];
		$params['user'] = $thisrecharge['openid'];
		$params['out_trade_no'] = $thisrecharge['ordersn'];
		$params['fee'] = $thisrecharge['num'];
		$params['title'] = $_W['account']['name'];
		$params['ordersn'] = $thisrecharge['ordersn'];
		$this->pay($params);
	}else if($_GPC['wechat']){
		//微信支付
		if (empty($_GPC['id'])) {
	        message('抱歉，参数错误！', '', 'error');
	    }
		$orderid = intval($_GPC['id']);
		$uniacid=$_W['uniacid'];
		$order = pdo_fetch("SELECT * FROM " . tablename('weliam_indiana_rechargerecord') . " WHERE id ='{$orderid}'");
		$params['tid'] = $order['ordersn'];
		$params['user'] = $openid;
		$params['fee'] = $order['num'];
		$params['title'] = $_W['account']['name'];
		$params['ordersn'] = $order['ordersn'];
		$this->pay($params);
	}else{
		//云支付
		if (empty($_GPC['id'])) {
	        message('抱歉，参数错误！', '', 'error');
	    }
		$orderid = intval($_GPC['id']);
		$uniacid=$_W['uniacid'];
		$order = pdo_fetch("SELECT * FROM " . tablename('weliam_indiana_rechargerecord') . " WHERE id ='{$orderid}'");
		$params['tid'] = $order['ordersn'];
		$params['out_trade_no'] = $order['ordersn'];
		$params['user'] = $openid;
		$params['fee'] = $order['num'];
		$params['title'] = $_W['account']['name'];
		$params['ordersn'] = $order['ordersn'];
		$this->pay($params);
	}
	
	
	
?>