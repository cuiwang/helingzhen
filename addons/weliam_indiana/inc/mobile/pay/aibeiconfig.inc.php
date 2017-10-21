<?php

	//爱贝商户后台接入url
	// $coolyunCpUrl="http://pay.coolyun.com:6988";
	$iapppayCpUrl="http://ipay.iapppay.com:9999";
	//登录令牌认证接口 url
	$tokenCheckUrl=$iapppayCpUrl . "/openid/openidcheck";
	
	//下单接口 url
	// $orderUrl=$coolyunCpUrl . "/payapi/order";
	$orderUrl=$iapppayCpUrl . "/payapi/order";
	
	//支付结果查询接口 url
	$queryResultUrl=$iapppayCpUrl ."/payapi/queryresult";
	
	//契约查询接口url
	$querysubsUrl=$iapppayCpUrl."/payapi/subsquery";
	
	//契约鉴权接口Url
	$ContractAuthenticationUrl=$iapppayCpUrl."/payapi/subsauth";
	
	//取消契约接口Url
	$subcancel=$iapppayCpUrl."/payapi/subcancel";
	//H5和PC跳转版支付接口Url
	$h5url="https://web.iapppay.com/h5/exbegpay?";
	$pcurl="https://web.iapppay.com/pc/exbegpay?";
	
	//应用编号
	$appid=$this->module['config']['iapppay_appid'];
	//应用私钥
	$appkey=$this->module['config']['iapppay_appv_key'];
	//平台公钥
	$platpkey=$this->module['config']['iapppay_platp_key'];