<?php
global $_W,$_GPC;
$refund = pdo_get('navlange_refund',array('tid'=>$_GPC['tid']));
include 'lib/WxPay.Api.php';
$input = new \WxPayRefund();
$pay_setting = uni_setting($_W['uniacid'],'payment');
$wechat_pay_setting = $pay_setting['payment']['wechat'];
$wechat = $_W['cache']['uniaccount:'.$_W['uniacid']];
$input->SetAppid($wechat['key']);
$input->SetMch_id($wechat_pay_setting['mchid']);
$input->SetNonce_str(\WxPayApi::getNonceStr());	
$input->SetOut_trade_no($refund['uniontid']);
$input->SetOut_refund_no($refund['tid']);
$input->SetTotal_fee($refund['total_fee']*100);
$input->SetRefund_fee($refund['refund_fee']*100);
$input->SetOp_user_id($wechat_pay_setting['mchid']);
$input->SetSign($wechat_pay_setting['signkey']);
$res = \WxPayApi::refund($input);
if($res['result_code'] == 'SUCCESS') {
	pdo_update('navlange_refund',array('status'=>'1'),array('tid'=>$_GPC['tid']));
}
echo json_encode($res);
?>