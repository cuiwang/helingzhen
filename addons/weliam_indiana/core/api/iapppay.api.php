<?php
	require '../../../../framework/bootstrap.inc.php';
	require IA_ROOT. '/addons/weliam_indiana/defines.php';
	require WELIAM_INDIANA_INC.'function.php';
	require IA_ROOT. '/addons/weliam_indiana/inc/mobile/pay/aibeibase.inc.php';
	
	load()->func('communication');
	
	global $_W,$_GPC;
	
	$string = $_POST;//接收post请求数据

	if($string ==null){
		m('log')->WL_log('iapppay','爱贝支付回传参数','请使用post提交数据','2');
	}else{
		$transdata=$string['transdata'];
		if(stripos("%22",$transdata)){ //判断接收到的数据是否做过 Urldecode处理，如果没有处理则对数据进行Urldecode处理
		 	$string= array_map ('urldecode',$string);
		 }
		
		$jtransdata = json_decode($transdata);
		$cpprivate = explode('-', $jtransdata->cpprivate);
		
		$ordersno = $cpprivate[0];
		$uniacid = $cpprivate[1];
		$type = $cpprivate[2];
		$openid = $jtransdata->appuserid;
		
		$respData = 'transdata='.$string['transdata'].'&sign='.$string['sign'].'&signtype='.$string['signtype'];//把数据组装成验签函数要求的参数格式
		
		$settings = pdo_fetchcolumn("SELECT settings FROM ".tablename('uni_account_modules')." WHERE module = :module AND uniacid = :uniacid", array(':module' => 'weliam_indiana', ':uniacid' => $uniacid));
		$settings = unserialize($settings);
		$platpkey = $settings['iapppay_platp_key'];
		
		if(!parseResp($respData, $platpkey, $respJson)) {
			//验签失败
			echo 'failed';
		}else{
			//交易成功
			echo 'success';
			if($jtransdata->transtype == 0 && $type == 'recharge'){
				//充值
				$money = $jtransdata->money;
				$data['status'] = 1;
				$data['num'] = $money;
				pdo_update('weliam_indiana_rechargerecord', $data, array('ordersn' => $ordersno));
				m('credit')->updateCredit2($openid,$uniacid,$money,'爱贝充值支付操作');exit;
			}
			if($jtransdata->transtype == 0){
				$money = $jtransdata->money;
				m('credit')->updateCredit2($openid,$uniacid,$money,'爱贝购买支付操作');
				
				$paylog = pdo_fetch("select plid,tid,status,fee from".tablename('core_paylog')." where uniacid=:uniacid and module=:module and tid=:tid",array(':uniacid'=>$uniacid,':module'=>'weliam_indiana',':tid'=>$ordersno));
				m('log')->WL_log('iapppay_check','检查$paylog',$paylog,$uniacid);
				
				if($paylog['fee'] == $money){
					/****************检索购买夺宝码开始*****************/
					pdo_update('core_paylog',array('status'=>1,'tag'=>serialize($jtransdata->transid)),array('uniacid'=>$uniacid,'module'=>'weliam_indiana','tid'=>$ordersno));
					$numsql = "select * from".tablename('weliam_indiana_cart')."where uniacid = ".$uniacid." and openid = '".$openid."'";
					$num_money = pdo_fetchall($numsql);
					$allmoney = 0;
					foreach($num_money as $key =>$value){
						$goodsid = pdo_fetchcolumn("select goodsid from".tablename('weliam_indiana_period')."where period_number = '{$value['period_number']}'");
						$init_money = pdo_fetchcolumn("select init_money from".tablename('weliam_indiana_goodslist')."where id = '{$goodsid}'");
						$allmoney = $allmoney+$init_money*$value['num'];
					}
					
					if($money < 1 || $money != $allmoney || $money == ''){
						m('log')->WL_log('pay','非法操作，计算数量不相同(爱贝)','(爱贝)',$uniacid);
						exit;
					}
					/****************检索购买夺宝码结束****************/
					if(m('codes')->code($openid,$ordersno,$uniacid,'')){
						/****************自己购买返回积分开始*****************/
						$credit_num = $settings['buy_followed'];
						if($credit_num > 0){
							$sql = "select * from".tablename('weliam_indiana_invite')."where uniacid=:uniacid and invite_openid=:invite_openid and type=:type";
							$data = array(
								':uniacid'=>$uniacid,
								':invite_openid'=>$openid,
								':type'=>2
							);
							$result = pdo_fetch($sql,$data);
							if(empty($result)){
								$numi = $credit_num*$buy_codes;
								$datam = array(
									'uniacid'=>$uniacid,
									'beinvited_openid'=>'yourself',
									'invite_openid'=>$openid,
									'createtime'=>time(),
									'credit1'=>$numi,
									'type'=>2
								);
								$ins = pdo_insert("weliam_indiana_invite", $datam);
							}else{
								$numu = $result['credit1']+$credit_num*$buy_codes;
								$upd = pdo_update("weliam_indiana_invite",array('credit1'=>$numu),array('uniacid'=>$uniacid,'type'=>2,'invite_openid'=>$openid));
							}
							m('credit')->updateCredit1($openid,$uniacid,$credit_num*$buy_codes,'被邀请人积分增加');
						}
						/****************自己购买返回积分结束*****************/
						$level=$settings['level'];
						if($level==1){
							$level1=$settings['level1'];
							$invites=m('invite')->getInvitesByOpenid($openid,$uniacid);
							foreach($invites as$key=>$value){
								m('credit')->updateCredit1($value['invite_openid'],$uniacid,$level1*$buy_codes,'自己购买积分返回');
								m('invite')->updateBy2Openid($openid,$value['invite_openid'],$uniacid,$level1*$buy_codes,'邀请关注返夺宝币');
							}
						}
					}else{
						m('log')->WL_log('iapppay_check','分码失败','分码失败',$uniacid);
					}
				}else{
					m('log')->WL_log('iapppay_error','爱贝支付金额和订单账户不对',$jtransdata,$uniacid);
				}
			}else{
				m('log')->WL_log('iapppay','爱贝支付情况','支付状态失败',$uniacid);
			}
		}
	}
//	
