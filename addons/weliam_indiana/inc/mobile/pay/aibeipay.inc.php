<?php
	// 
	//  aibeipay.inc.php
	//  <project>
	//  爱贝支付
	//  Created by Administrator on 2016-08-23.
	//  Copyright 2016 Administrator. All rights reserved.
	// 
	global $_W,$_GPC,$h5url,$pcurl, $appkey, $platpkey, $transid;
	
	require_once ("aibeiconfig.inc.php");
	require_once ("aibeibase.inc.php");

	$openid = m('user') -> getOpenid();
	
	$ops = array('sendpay','getencstr','onResp','succe','succeSql',);
	$op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'ill';
	
	if($op == 'ill'){
		die(json_encode(array('status'=>1,'data'=>'','msg'=>'未找到相应方法')));
	}
	
	if($op == 'sendpay'){
		$ptype = $_GPC['ptype'];
		if($ptype == 'recharge'){
			$type = 'recharge';
		}else{
			$type = 'buy';
		}
		$req = $_GPC['req'];
		$resp = $_GPC['resp'];
		$orderid = $_GPC['orderid'];
		$paylog = pdo_fetch("select plid,tid,status,fee from".tablename('core_paylog')." where uniacid=:uniacid and module=:module and tid=:tid",array(':uniacid'=>$_W['uniacid'],':module'=>'weliam_indiana',':tid'=>$orderid));
		
		$orderReq['appid'] = $appid;
	    $orderReq['waresid'] = 1;
	    $orderReq['cporderid'] = (string)time(); //确保该参数每次 都不一样。否则下单会出问题。
	   
	    $orderReq['price'] = (float)$paylog['fee'];   //单位：元
	    $orderReq['currency'] = 'RMB';
	    $orderReq['appuserid'] = $_W['openid'];
	    $orderReq['cpprivateinfo'] = $orderid.'-'.$_W['uniacid'].'-'.$type;
	    $orderReq['notifyurl'] = $_W['siteroot'].'addons/weliam_indiana/core/api/iapppay.api.php';
//		print_r($orderReq);
	    //组装请求报文  对数据签名
	    $reqData = composeReq($orderReq, $appkey);

	    //发送到爱贝服务后台请求下单
	    $respData = request_by_curl($orderUrl, $reqData, 'order test');
	   
	    //验签数据并且解析返回报文
	    if(!parseResp($respData, $platpkey, $respJson)) {
	        echo "failed";
	    }else{
	    	//下单成功之后获取 transid
	    	$transid=$respJson->transid;
			echo $transid;
	    }
	}

	if($op == 'getencstr'){
		$transid = $_GPC['id'];
		$orderReq['transid'] = "$transid";
		$orderReq['redirecturl'] = $_W['siteroot'].$this->createMobileUrl('endbuy');
		$orderReq['cpurl'] = 'aaa';
		$reqData = composeReq($orderReq, $appkey);
		echo $reqData;
	}
	