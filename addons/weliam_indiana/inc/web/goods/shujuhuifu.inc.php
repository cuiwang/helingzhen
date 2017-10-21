<?php 
	// 
	//  shujuhuifu.inc.php
	//  <project>
	//  数据恢复
	//  Created by Administrator on 2016-05-15.
	//  Copyright 2016 Administrator. All rights reserved.
	// 
	global $_W,$_GPC;
	$op = $_GPC['op'];
	if($op == 'tiaoqi'){
		//恢复跳期数据
		$goodslist = pdo_fetchall("select id,periods,init_money from".tablename('weliam_indiana_goodslist')."where uniacid = '{$_W['uniacid']}'");
		foreach($goodslist as $key=>$value){
			$periods = pdo_fetch("select count(id) from".tablename('weliam_indiana_period')."where uniacid = '{$_W['uniacid']}' and goodsid = '{$value['id']}' and canyurenshu < zong_codes");
			if($periods > 1){
				$need_period = pdo_fetchall("select period_number from".tablename('weliam_indiana_period')."where uniacid = '{$_W['uniacid']}' and goodsid = '{$value['id']}' and periods < '{$value['periods']}'");
				foreach($need_period as $key2=>$value2){
					$records = pdo_fetchall("select openid , id , count from".tablename('weliam_indiana_record')."where uniacid = '{$_W['uniacid']}' and period_number = '{$value2['period_number']}' and status = 1");
					pdo_delete('weliam_indiana_period',array('uniacid'=>$_W['uniacid'],'period_number'=>$value2['period_number']));
				}
			}
		}
		message('恢复成功',referer(),'success');
	}
	
	if($op == 'return'){
		//退款和发送模板消息
		$need_period_number = $_GPC['period_number'];
		$result_consumerecord = pdo_fetchall("select * from ".tablename('weliam_indiana_consumerecord')." where uniacid = '{$_W['uniacid']}' and period_number = '{$need_period_number}' and status = 2");
		echo '<pre>';
		print_r($result_consumerecord);
		foreach($result_consumerecord as $key => $value){
			//循环退款和发送模板消息
//			m('credit')->updateCredit2($value['openid'],$_W['uniacid'],$value['num']);
//			pdo_update('weliam_indiana_consumerecord',array('status'=>2),array('id'=>$value['id'],'uniacid'=>$_W['uniacid']));
			
			$sql = 'SELECT `settings` FROM ' . tablename('uni_account_modules') . ' WHERE `uniacid` = :uniacid AND `module` = :module';
			$settings = pdo_fetchcolumn($sql, array(':uniacid' => $_W['uniacid'], ':module' => 'weliam_indiana'));
			$settings = iunserializer($settings);
			$template_id = $settings['m_ref'];
			$datam = array(
				"first"=>array( "value"=> "退款通知","color"=>"#173177"),
				"reason"=>array( "value"=> "由于技术原因，您购买的【哈雷型电动车】退款到您的余额，给您造成的不便，我公司深表歉意！","color"=>"#173177"),
				"refund"=>array( "value"=> '￥'.$value['num'],"color"=>"#173177"),
				"remark"=>array( "value"=> "退款通知","color"=>"#173177"),
			);
			$account= WeAccount :: create($_W['acid']);
			//$account -> sendTplNotice($value['openid'], $template_id, $datam, '');
		}
		/*message('退款成功',referer(),'success');*/
	}

	if($op == 'cwsp'){
		//返还错误商品
		$need_period_number = $_GPC['period_number'];
		$period = pdo_fetch("select id,goodsid from".tablename('weliam_indiana_period')."where uniacid = '{$_W['uniacid']}' and period_number = '{$need_period_number}'");
		$title = pdo_fetchcolumn("select title from".tablename('weliam_indiana_goodslist')."where uniacid = '{$_W['uniacid']}' and id = '{$period['goodsid']}'");
		/*pdo_update('weliam_indiana_goodslist',array('status'=>0),array('uniacid'=>$_W['uniacid'],'id'=>$period['goodsid']));*/
		pdo_update('weliam_indiana_period',array('status'=>'-1'),array('uniacid'=>$_W['uniacid'],'id'=>$period['id']));
		$result_consumerecord = pdo_fetchall("select * from ".tablename('weliam_indiana_consumerecord')." where uniacid = '{$_W['uniacid']}' and period_number = '{$need_period_number}' and status = 1");
		
		foreach($result_consumerecord as $key => $value){
			//循环退款和发送模板消息
			m('credit')->updateCredit2($value['openid'],$_W['uniacid'],$value['num']);
			pdo_update('weliam_indiana_consumerecord',array('status'=>2),array('id'=>$value['id'],'uniacid'=>$_W['uniacid']));	
			$sql = 'SELECT `settings` FROM ' . tablename('uni_account_modules') . ' WHERE `uniacid` = :uniacid AND `module` = :module';
			$settings = pdo_fetchcolumn($sql, array(':uniacid' => $_W['uniacid'], ':module' => 'weliam_indiana'));
			$settings = iunserializer($settings);
			$template_id = $settings['m_ref'];
			$datam = array(
				"first"=>array( "value"=> "退款通知","color"=>"#173177"),
				"reason"=>array( "value"=> "由于技术原因，您购买的【".$title."】退款到您的余额，给您造成的不便，我公司深表歉意！","color"=>"#173177"),
				"refund"=>array( "value"=> '￥'.$value['num'],"color"=>"#173177"),
				"remark"=>array( "value"=> "退款通知","color"=>"#173177"),
			);
			$account= WeAccount :: create($_W['acid']);
			$account -> sendTplNotice($value['openid'], $template_id, $datam, '');
		}
		message('【'.$title.'】退款成功',referer(),'success');
	}
	?>