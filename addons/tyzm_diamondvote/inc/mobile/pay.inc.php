<?php

define('IN_MOBILE', true);
global $_W,$_GPC;

$tys = array('wechat', 'native');
$ty=trim($_GPC['ty']);
$ty = in_array($ty, $tys) ? $ty : 'wechat';

if($ty=='native'){
	error_reporting(0);	
	$order=pdo_fetch("SELECT * FROM " . tablename($this->tablegift) . " WHERE ptid = :ptid  ", array(':ptid' => $_GPC['ordertoken']));
	//新建订单
	
	
	//引入支付库文件
	require_once IA_ROOT . '/addons/tyzm_diamondvote/lib/WxPayApi.php';
	require_once IA_ROOT . '/addons/tyzm_diamondvote/lib/WxPay.NativePay.php';
	//获取微赞支付系统回调域名，
	$url = $_W['siteroot'] . 'payment/wechat/notify.php';
	
	//订单信息
	//获取金额
	$fee = $order['fee'];
	
	//获取当前公众号的支付参数
	$setting = uni_setting($_W['uniacid'], array('payment'));
	//获取当前订单号，微信支付商户号+时间
	$orderid = $setting['payment']['wechat']['mchid'].date("YmdHis");
	$tid=date('YmdHi').random(12, 1);
	//实例化支付类
	$notify = new NativePay();
	$input = new WxPayUnifiedOrder();
	$input->SetAppid($_W['account']['key']);//此值为当前公众号的appid
	$input->SetMch_id($setting['payment']['wechat']['mchid']);//此值为当前公众号的微信支付商户号
	$input->SetBody('投票送礼付款');//此值为当前支付的主体描述
	$input->SetAttach($_W['uniacid']);////此值为附件参数，请填写公众号id
	$input->SetOut_trade_no($orderid); //商户订单号
	$input->SetTotal_fee($fee*100); //支付金额
	$input->SetTime_start(date("YmdHis"));//订单开始时间
	$input->SetTime_expire(date("YmdHis", time() + 600)); //订单失效时间
	$input->SetGoods_tag("投票送礼付款");
	$input->SetNotify_url($url);//回调地址
	$input->SetTrade_type("NATIVE");//支付类型
	$input->SetProduct_id($order['tid']);//商品ID
	$result = $notify->GetPayUrl($input,$setting['payment']['wechat']['apikey']);
	
	
	//第二个参数为当前公众号的支付秘钥
	$url2 = $result["code_url"];
	
	
	//
	//如果失败，返回状态1。如果成功返回状态2，生成二维码必须的url，当前订单号(用于查询是否支付成功)
	if($result['return_code'] == 'FAIL'){
		//message("抱歉，支付失败，请刷新后再试！");
		print_r($order);
		exit;	
	}else{ //如果生成二维码链接成功，就将此次订单信息插入core_paylog表中，等待用户支付成功后微赞系统更新status字段(为1)。
		$record = array();
		$record['uniacid'] = $_W['uniacid'];
		$record['openid'] = $order['openid'];//这里需要填入你传递过来或者查询获得的用户openid，用于后续查询用户的订单
		$record['module'] = 'tyzm_diamondvote';//这里需要填入你的模块标识，
		$record['type'] = 'wechat';
		$record['tid'] = $tid;
		$record['uniontid'] = $orderid;
		$record['fee'] = $fee;
		$record['status'] = '0'; //这里要是0，0代表用户只是生成订单还未支付。当用户支付成功后，微赞系统会在core_paylog表中更新这个字段为1.
		$record['is_usecard'] = 0;
		$record['card_id'] = 0;
		$record['encrypt_code'] = '';
		$record['acid'] = $_W['acid'];
		if(pdo_insert('core_paylog', $record)){
			//记录投票
			$plid = pdo_insertid();
	        $order['ptid']=$tid;
			unset($order['id']);
			if(pdo_insert($this->tablegift, $order)){
				$_W['page']['sitename']="微信扫码支付";
				include $this->template('nativepay');
			}
		
		}
	}
	exit;
}


if($ty=='wechat'){
$sl = $_GPC['ps'];
$params = @json_decode(base64_decode($sl), true);
if($_GPC['done'] == '1') {
	$sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `plid`=:plid';
	$pars = array();
	$pars[':plid'] = $params['tid'];
	$log = pdo_fetch($sql, $pars);
	if(!empty($log) && !empty($log['status'])) {
		if (!empty($log['tag'])) {
			$tag = iunserializer($log['tag']);
			$log['uid'] = $tag['uid'];
		}
		$site = WeUtility::createModuleSite($log['module']);
		if(!is_error($site)) {
			$method = 'payResult';
			if (method_exists($site, $method)) {
				$ret = array();
				$ret['weid'] = $log['uniacid'];
				$ret['uniacid'] = $log['uniacid'];
				$ret['result'] = 'success';
				$ret['type'] = $log['type'];
				$ret['from'] = 'return';
				$ret['tid'] = $log['tid'];
				$ret['uniontid'] = $log['uniontid'];
				$ret['user'] = $log['openid'];
				$ret['fee'] = $log['fee'];
				$ret['tag'] = $tag;
				$ret['is_usecard'] = $log['is_usecard'];
				$ret['card_type'] = $log['card_type'];
				$ret['card_fee'] = $log['card_fee'];
				$ret['card_id'] = $log['card_id'];
				exit($site->$method($ret));
			}
		}
	}
}

$sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `plid`=:plid';
$log = pdo_fetch($sql, array(':plid' => $params['tid']));


if(!empty($log) && $log['status'] != '0') {
	exit('这个订单已经支付成功, 不需要重复支付.');
}
$auth = sha1($sl . $log['uniacid'] . $_W['config']['setting']['authkey']);
if($auth != $_GPC['auth']) {
	exit('参数传输错误.');
}
load()->model('payment');
$_W['uniacid'] = intval($log['uniacid']);
$_W['openid'] = intval($log['openid']);
$setting = uni_setting($_W['uniacid'], array('payment'));
if(!is_array($setting['payment'])) {
	exit('没有设定支付参数.');
}
$wechat = $setting['payment']['wechat'];
$sql = 'SELECT `key`,`secret` FROM ' . tablename('account_wechats') . ' WHERE `acid`=:acid';
$row = pdo_fetch($sql, array(':acid' => $wechat['account']));
$wechat['appid'] = $row['key'];
$wechat['secret'] = $row['secret'];
$params = array(
	'tid' => $log['tid'],
	'fee' => $log['card_fee'],
	'user' => $log['openid'],
	'title' => urldecode($params['title']),
	'uniontid' => $log['uniontid'],
);
$wOpt = wechat_build($params, $wechat);
if (is_error($wOpt)) {
	if ($wOpt['message'] == 'invalid out_trade_no' || $wOpt['message'] == 'OUT_TRADE_NO_USED') {
		$id = date('YmdH');
		pdo_update('core_paylog', array('plid' => $id), array('plid' => $log['plid']));
		pdo_query("ALTER TABLE ".tablename('core_paylog')." auto_increment = ".($id+1).";");
		message("抱歉，发起支付失败，系统已经修复此问题，请重新尝试支付。");
	}
	message("抱歉，发起支付失败，具体原因为：“{$wOpt['errno']}:{$wOpt['message']}”。请及时联系站点管理员。");
	exit;
}
}
?>

<script type="text/javascript">
document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
	WeixinJSBridge.invoke('getBrandWCPayRequest', {
		'appId' : '<?php echo $wOpt['appId'];?>',
		'timeStamp': '<?php echo $wOpt['timeStamp'];?>',
		'nonceStr' : '<?php echo $wOpt['nonceStr'];?>',
		'package' : '<?php echo $wOpt['package'];?>',
		'signType' : '<?php echo $wOpt['signType'];?>',
		'paySign' : '<?php echo $wOpt['paySign'];?>'
	}, function(res) {
		if(res.err_msg == 'get_brand_wcpay_request:ok') {
			location.search += '&done=1';
		} else {
			//alert('启动微信支付失败, 请检查你的支付参数. 详细错误为: ' + res.err_msg);
			//history.go(-1);
			window.location.href="../app/index.php?c=entry&do=pay&m=tyzm_diamondvote&i=<?php echo $_W['uniacid'];?>&ty=native&ordertoken=<?php echo $params['tid'];?>"; 
			
			
		}
	});
}, false);
</script>

