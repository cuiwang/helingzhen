<?php
/**
 */
define('IN_MOBILE', true);
global $_W,$_GPC;


is_weixin();
$rid=intval($_GPC['rid']);
$id=intval($_GPC['id']);
$ty=$_GPC['ty'];
$count=intval($_GPC['count']);
$count=empty($count) ? 1 : $count;
$userinfo=$this->oauthuser;
$oauth_openid=$userinfo['oauth_openid'];
if(empty($oauth_openid)){
	message("无法获取OPNEID，请查看是否借权或配置好公众号！(0101)"); 
}
$reply = pdo_fetch("SELECT rid,title,sharetitle,shareimg,sharedesc,config,giftdata,endtime,apstarttime,apendtime,votestarttime,voteendtime,status  FROM " . tablename($this->tablereply) . " WHERE rid = :rid ", array(':rid' => $rid));
$reply=array_merge ($reply,unserialize($reply['config']));unset($reply['config']);
if(empty($reply)){
	message("参数错误"); 
}

if($reply['starttime']>time()){
	message("活动还没有开始"); 
}
 
//活动未开始
if($reply['endtime']<time()){
	message("活动已经结束"); 
}

//活动未开始
if(empty($reply['status'])){
	message("活动已禁用"); 
}

//投票时间
if($reply['votestarttime']> time()){
	message("未开始投票！"); 
}elseif($reply['voteendtime']<time()){
	message("已结束投票！");
}
$giftdata=@unserialize($reply['giftdata']);	



$voteuser = pdo_fetch("SELECT * FROM " . tablename($this->tablevoteuser) . " WHERE rid = :rid AND  id = :id ", array(':rid' => $rid,':id' => $id));


	//是否达到最小人数
	if(!empty($reply['minnumpeople'])){
		$condition="";
		if($reply['ischecked']==1){
		  $condition.=" AND status=1 ";
		}
		$jointotal = pdo_fetchcolumn('SELECT COUNT(id) FROM ' . tablename($this->tablevoteuser) . " WHERE   rid = :rid  ".$condition , array(':rid' => $rid));
		if($reply['minnumpeople']>$jointotal){
			exit(json_encode(array('status' => '0', 'msg' => "活动还未开始，没有达到最小参赛人数！")));
		}
	}
	$gift=$giftdata[$_GPC['giftid']];
	
	
	
	//最多送礼物
	$diamondsy=$reply['everyonediamond']-$voteuser['giftcount'];
	if($gift['giftprice']*$count > $diamondsy && !empty($reply['everyonediamond'])){
		if($diamondsy>0){
			exit(json_encode(array('status' => '0', 'msg' => "最多还能送".$diamondsy."元礼物，修改后再送！:-D")));
		}else{
			exit(json_encode(array('status' => '0', 'msg' => "最多能送".$reply['everyonediamond']."元礼物，给其他人点机会吧！:-D")));
		} 
	}	
	$tid=date('YmdHi').random(12, 1);
	$params = array(
		'tid' => $tid,
		'ordersn' => $tid,
		'title' => '投票送礼付款',
		'fee' => sprintf("%.2f",$gift['giftprice']*$count),
		'user' => $_W['member']['uid'],
		'module' => $this->module['name'],
	);
		$giftdata = array(
				'rid'=>$rid, 
				'tid'=>$id,
				'uniacid'=>$_W['uniacid'],
				'oauth_openid'=>$userinfo['oauth_openid'],
				'openid'=>$userinfo['openid'],
				'avatar' =>$userinfo['avatar'],
				'nickname'=>$userinfo['nickname'],
				'user_ip'=>$_W['clientip'],
				'gifticon'=>$gift['gifticon'],
				'giftcount'=>$count,
				'gifttitle'=>$gift['gifttitle'],
				'giftvote'=>$gift['giftvote']*$count,
				'fee'=>$params['fee'],
				'ptid'=>$tid,
				'ispay'=>0,
				'status'=>0,
				'createtime'=>time()
		);
		if(pdo_insert($this->tablegift, $giftdata)){
			// $out['status'] = 'success';
			// $out['fee'] = $params['fee'];
			// $out['ordertid']=$tid;
			// exit(json_encode($out));
			//


		$getparams  = array('method' => 'wechat', 'tid'=>$tid,'title'=>"支付".$params['fee']."元",'fee'=>$params['fee'],'module'=>"tyzm_diamondvote");
		
		// if(empty($params) || !array_key_exists($params['module'], $moduels)) {
		// 	iajax(1, '模块不存在', '');
		// }
		
		// $setting = uni_setting($_W['uniacid'], 'payment');
		// $dos = array();
		// if(!empty($setting['payment']['credit']['switch'])) {
		// 	$dos[] = 'credit';
		// }
		// if(!empty($setting['payment']['alipay']['switch'])) {
		// 	$dos[] = 'alipay';
		// }
		// if(!empty($setting['payment']['wechat']['switch'])) {
		// 	$dos[] = 'wechat';
		// }
		// if(!empty($setting['payment']['delivery']['switch'])) {
		// 	$dos[] = 'delivery';
		// }
		// if(!empty($setting['payment']['unionpay']['switch'])) {
		// 	$dos[] = 'unionpay';
		// }
		// if(!empty($setting['payment']['baifubao']['switch'])) {
		// 	$dos[] = 'baifubao';
		// }
		// $type = in_array($params['method'], $dos) ? $params['method'] : '';
		// if(empty($type)) {
		// 	iajax(1, '暂无有效支付方式,请联系商家', '');
		// }
		$moduleid = pdo_getcolumn('modules', array('name' => $getparams['module']), 'mid');
		$moduleid = empty($moduleid) ? '000000' : sprintf("%06d", $moduleid);
		$uniontid = date('YmdHis').$moduleid.random(8,1);
		
		$paylog = pdo_get('core_paylog', array('uniacid' => $_W['uniacid'], 'module' => $getparams['module'], 'tid' => $getparams['tid']));
		if (empty($paylog)) {
			$paylog = array(
				'uniacid' => $_W['uniacid'],
				'acid' => $_W['acid'],
				'openid' => $_W['member']['uid'],
				'module' => $getparams['module'],
				'tid' => $getparams['tid'],
				'uniontid' => $uniontid,
				'fee' => $getparams['fee'],
				'card_fee' => $getparams['fee'],
				'status' => '0',
				'is_usecard' => '0',
			);
			pdo_insert('core_paylog', $paylog);
			$paylog['plid'] = pdo_insertid();
		}
		if(!empty($paylog) && $paylog['status'] != '0') {
			iajax(1, '这个订单已经支付成功, 不需要重复支付.', '');
		}
		if (!empty($paylog) && empty($paylog['uniontid'])) {
			pdo_update('core_paylog', array(
				'uniontid' => $uniontid,
			), array('plid' => $paylog['plid']));
		}
		$paylog['title'] = $getparams['title'];
	
		load()->model('payment');
		
		pdo_update('core_paylog', array(
			'openid' => $_W['openid'], 
			'tag' => iserializer(array('acid' => $_W['acid'], 'uid' => $_W['member']['uid']))
		), array('plid' => $paylog['plid']));
		
		$_W['uniacid'] = $paylog['uniacid'];
		$_W['openid'] = $paylog['openid'];
		
		$setting = uni_setting($_W['uniacid'], array('payment'));
		$wechat_payment = $setting['payment']['wechat'];
		
		$account = pdo_get('account_wechats', array('acid' => $wechat_payment['account']), array('key', 'secret'));
		
		$wechat_payment['appid'] = $account['key'];
		$wechat_payment['secret'] = $account['secret'];
		
		$params_b = array(
			'tid' => $paylog['tid'],
			'fee' => $paylog['card_fee'],
			'user' => $paylog['openid'],
			'title' => urldecode($paylog['title']),
			'uniontid' => $paylog['uniontid'],
		);
		$wechat_payment['openid']=$wechat['openid'];

		if (intval($wechat_payment['switch']) == 2 || intval($wechat_payment['switch']) == 3) {
			$wechat_payment_params = wechat_proxy_build($params_b, $wechat_payment);
		} else {
			unset($wechat_payment['sub_mch_id']);
			$wechat_payment_params = wechat_build($params_b, $wechat_payment);
		}
		if (is_error($wechat_payment_params)) {
			//iajax(1, $wechat_payment_params);
			message(error(1, $wechat_payment_params), '', 'ajax', false);
		} else {
			//iajax(0, $wechat_payment_params);
			message(error(0, $wechat_payment_params), '', 'ajax', false);
		}

}
		

