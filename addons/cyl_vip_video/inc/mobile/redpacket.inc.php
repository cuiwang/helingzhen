<?php
global $_W,$_GPC;
$redpacket = pdo_get('navlange_redpacket',array('tid'=>$_GPC['redpacket_tid']));
include 'lib/WxPay.Api.php';
$input = new \WxPayRedPacketPay();
$input->SetNonce_str(\WxPayApi::getNonceStr());
$input->SetMch_billno($redpacket['tid']);
$pay_setting = uni_setting($_W['uniacid'],'payment');
$wechat_pay_setting = $pay_setting['payment']['wechat'];
$input->SetMch_id($wechat_pay_setting['mchid']);
$wechat = $_W['cache']['uniaccount:'.$_W['uniacid']];
$input->SetWxappid($wechat['key']);
$input->SetSend_name($wechat['name']);
$input->SetRe_openid($redpacket['openid']);
$input->SetTotal_amount(round($redpacket['money']*100));
$input->SetTotal_num(1);
$pay_conf = pdo_get('navlange_pay_conf',array('uniacid'=>$_W['uniacid']));
$input->SetWishing($pay_conf['redpacket_wish']);
$input->SetClient_ip($_SERVER['REMOTE_ADDR']);
$input->SetAct_name("分销红包");
$input->SetRemark("红包");		
$input->SetSign($wechat_pay_setting['signkey']);
$res = \WxPayApi::redPacketPay($input);
libxml_disable_entity_loader(true);
$result = (array)simplexml_load_string($res, 'SimpleXMLElement', LIBXML_NOCDATA);
if($result['result_code'] == 'SUCCESS') {
	pdo_update('navlange_redpacket',array('status'=>'1'),array('tid'=>$_GPC['redpacket_tid']));
}
echo json_encode($result);
?>