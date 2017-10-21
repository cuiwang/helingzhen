<?php
global $_GPC, $_W;
$cfg = $this->module['config'];
$styleid = $_GPC['styleid'];//模板
$wid = base64_decode($_GPC['wid']);//简记id
if (!empty($styleid)){
	$style = pdo_fetch('select status,id,price from '.tablename($this->modulename."_style")." where id='{$styleid}'");
	if(empty($style)){
		$result = array(
				"errcode"=>1,
				"errmsg"=>'该模板已删除，不可购买模板',
		);
		die(json_encode($result));
	}
	if(!$style['status']){
		$result = array(
				"errcode"=>1,
				"errmsg"=>'该模板已禁止开放，不可购买模板',
		);
		die(json_encode($result));
	}
	$fee = $style['price'];
	$preresult = $this->unifiedOrder('模板购买支付',$fee,$wid,$styleid);
}else{
	$fee = floatval($_GPC['fee']);
	if($fee < 1 || $fee > 20000){
		$result = array(
				"errcode"=>1,
				"errmsg"=>'请输入有效金额',
		);
		die(json_encode($result));
	}
	$works = pdo_fetch('select status,openid from '.tablename($this->modulename."_works")." where id='{$wid}'");
	if (empty($works)){
		$result = array(
				"errcode"=>1,
				"errmsg"=>"该{$cfg['UI']['title']}已删除，不可打赏",
		);
		die(json_encode($result));
	}
	if ($works['status']){
		$result = array(
				"errcode"=>1,
				"errmsg"=>"该{$cfg['UI']['title']}已禁止开放，不可打赏",
		);
		die(json_encode($result));
	}
	$m = pdo_fetch('select status from '.tablename($this->modulename.'_member')." where openid='{$works['openid']}'");
	if (empty($m)){
		$result = array(
				"errcode"=>1,
				"errmsg"=>"该{$cfg['UI']['title']}用户不存在，不可打赏",
		);
		die(json_encode($result));
	}
	if ($m['status']){
		$result = array(
				"errcode"=>1,
				"errmsg"=>"该{$cfg['UI']['title']}用户已被拉黑，不可打赏",
		);
		die(json_encode($result));
	}
	$preresult = $this->unifiedOrder("{$cfg['UI']['title']}打赏支付",$fee,$wid);
}
$result = array();
if($preresult['errcode'] == 0){
	$system = $this->module['config'];
	if($system['auth']==0 && $_W['openid']){
		$params = $this->getWxPayJsParams($preresult['prepay_id']);
		$result = array(
				"errcode"=>0,
				"auth"=>0,
				"timeStamp"=>$params['timeStamp'],
				"nonceStr"=>$params['nonceStr'],
				"package"=>$params['package'],
				"signType"=>$params['signType'],
				"paySign"=>$params['paySign'],
				"orderno"=>$preresult['orderno'],
				"orderid"=>$preresult['orderid'],
		);
	}else{
		$url = "http://paysdk.weixin.qq.com/example/qrcode.php?data=";
		$result = array(
				"errcode"=>0,
				"auth"=>1,
				"orderno"=>$preresult['orderno'],
				"orderid"=>$preresult['orderid'],
				"code_url"=>$url.$preresult['code_url'],
		);

	}
}else{
	$result = array(
			"errcode"=>1,
			"errmsg"=>$preresult['errmsg']
	);
}
die(json_encode($result));