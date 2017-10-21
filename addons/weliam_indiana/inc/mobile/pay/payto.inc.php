<?php 
	// 
	//  payto.inc.php
	//  <project>
	//  支付过滤
	//  Created by Administrator on 2016-03-31.
	//  Copyright 2016 Administrator. All rights reserved.
	//
	
		global $_W,$_GPC;
		$pay_type = $_GPC['pay_type'];
		$openid = m('user')->getOpenid();
		$params = @json_decode(base64_decode($_GPC['params']), true);
		$tid = $params['tid'];
		$fee = pdo_fetchcolumn("select num from".tablename('weliam_indiana_rechargerecord')."where uniacid = :uniacid and ordersn = :ordersn",array(':uniacid' => $_W['uniacid'],':ordersn' => $tid));
		$params['fee'] = $fee;
		$params = base64_encode(json_encode($params));
		/*$params = @json_decode(base64_decode($params), true);
		echo '<pre>';
		print_r($params);
		exit;*/
		if($pay_type == 'wq_wx_pub'){
		//微赞微信支付
		header("location:".url('mc/cash/wechat')."&params=".$params);	
		exit;
		}elseif($pay_type == 'wq_alipay_wap'){
		//微赞支付宝支付
		header("location:".url('mc/cash/wechat')."&params=".$params);	
		exit;
		}elseif($pay_type == 'yun_pay'){
		//微信云支付
		header("location:".url('mc/cash/wechat')."&params=".$params);	
		exit;
		}elseif($pay_type == 'ping_pay'){
		//ping++支付
		
		} 
	
	?>