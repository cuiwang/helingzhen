<?php
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
defined('IN_IA') or exit('Access Denied');

load()->model('payment');
load()->model('account');
load()->func('communication');

$dos = array('save_setting', 'display', 'test_alipay', 'get_setting');
$do = in_array($do, $dos) ? $do : 'display';
uni_user_permission_check('profile_setting');
$_W['page']['title'] = '支付参数 - 公众号选项';

if ($do == 'get_setting') {
	$setting = uni_setting_load('payment', $_W['uniacid']);
	$pay_setting = $setting['payment'];
	if(!is_array($pay_setting) || empty($pay_setting)) {
		$pay_setting = array(
			'delivery' => array('switch' => false),
			'credit' => array('switch' => false),
			'alipay' => array('switch' => false),
			'wechat' => array('switch' => false),
			'wechat_facilitator' => array('switch' => false),
			'unionpay' => array('switch' => false),
			'baifubao' => array('switch' => false),
			'line' => array('switch' => false),
		);
	}
	iajax(0, $pay_setting, '');
}

if ($do == 'test_alipay') {
	$alipay = $_GPC['param'];
	$pay_data = array(
		'uniacid' => $_W['uniacid'],
		'acid' => $_W['acid'],
		'uniontid' => date('Ymd', time()).time(),
		'module' => 'system',
		'fee' => '0.01',
		'status' => 0,
		'card_fee' => 0.01
	);
	$params = array();
	$params['tid'] = md5(uniqid());
	$params['user'] = '测试用户';
	$params['fee'] = '0.01';
	$params['title'] = '测试支付接口';
	$params['uniontid'] = $pay_data['uniontid'];

	$result = alipay_build($params, $alipay);
	iajax(0, $result['url'], '');
}

if ($do == 'save_setting') {
	$type = $_GPC['type'];
	$param = $_GPC['param'];
	$setting = uni_setting_load('payment', $_W['uniacid']);
	$pay_setting = $setting['payment'];
	if ($type == 'credit' || $type == 'delivery') {
		$param['switch'] = $param['switch'] == 'false' ? true : false;
	}
	if ($type == 'alipay' || $type == 'wechat_facilitator' || $type == 'baifubao' || $type == 'line') {
		$param['switch'] = $param['switch'] == 'true' ? true : false;
	}
	if ($type == 'wechat') {
		$param['account'] = $_W['acid'];
		$param['signkey'] = $param['version'] == 2 ? trim($param['apikey']) : trim($param['signkey']);
	}
	if ($type == 'unionpay') {
		$unionpay = $_GPC['unionpay'];
		if ($unionpay['switch'] && empty($_FILES['unionpay']['tmp_name']['signcertpath']) && !file_exists(IA_ROOT . '/attachment/unionpay/PM_'.$_W['uniacid'].'_acp.pfx')) {
			itoast('请上联银商户私钥证书.', referer(), 'error');
		}
		$param = array(
			'switch' => $unionpay['switch'] == 'false'? false : true,
			'merid' => $unionpay['merid'],
			'signcertpwd' => $unionpay['signcertpwd']
		);
		if($param['switch'] && (empty($param['merid']) || empty($param['signcertpwd']))) {
			itoast('请输入完整的银联支付接口信息.', referer(), 'error');
		}
		if ($param['switch'] && empty($_FILES['unionpay']['tmp_name']['signcertpath']) && !file_exists(IA_ROOT . '/attachment/unionpay/PM_'.$_W['uniacid'].'_acp.pfx')) {
			itoast('请上传银联商户私钥证书.', referer(), 'error');
		}
		if ($param['switch'] && !empty($_FILES['unionpay']['tmp_name']['signcertpath'])) {
			load()->func('file');
			mkdirs(IA_ROOT . '/attachment/unionpay/');
			file_put_contents(IA_ROOT . '/attachment/unionpay/PM_'.$_W['uniacid'].'_acp.pfx', file_get_contents($_FILES['unionpay']['tmp_name']['signcertpath']));
			$public_rsa = '-----BEGIN CERTIFICATE-----
MIIEIDCCAwigAwIBAgIFEDRVM3AwDQYJKoZIhvcNAQEFBQAwITELMAkGA1UEBhMC
Q04xEjAQBgNVBAoTCUNGQ0EgT0NBMTAeFw0xNTEwMjcwOTA2MjlaFw0yMDEwMjIw
OTU4MjJaMIGWMQswCQYDVQQGEwJjbjESMBAGA1UEChMJQ0ZDQSBPQ0ExMRYwFAYD
VQQLEw1Mb2NhbCBSQSBPQ0ExMRQwEgYDVQQLEwtFbnRlcnByaXNlczFFMEMGA1UE
Aww8MDQxQDgzMTAwMDAwMDAwODMwNDBA5Lit5Zu96ZO26IGU6IKh5Lu95pyJ6ZmQ
5YWs5Y+4QDAwMDE2NDkzMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA
tXclo3H4pB+Wi4wSd0DGwnyZWni7+22Tkk6lbXQErMNHPk84c8DnjT8CW8jIfv3z
d5NBpvG3O3jQ/YHFlad39DdgUvqDd0WY8/C4Lf2xyo0+gQRZckMKEAId8Fl6/rPN
HsbPRGNIZgE6AByvCRbriiFNFtuXzP4ogG7vilqBckGWfAYaJ5zJpaGlMBOW1Ti3
MVjKg5x8t1/oFBkpFVsBnAeSGPJYrBn0irfnXDhOz7hcIWPbNDoq2bJ9VwbkKhJq
Vz7j7116pziUcLSFJasnWMnp8CrISj52cXzS/Y1kuaIMPP/1B0pcjVqMNJjowooD
OxID3TZGfk5V7S++4FowVwIDAQABo4HoMIHlMB8GA1UdIwQYMBaAFNHb6YiC5d0a
j0yqAIy+fPKrG/bZMEgGA1UdIARBMD8wPQYIYIEchu8qAQEwMTAvBggrBgEFBQcC
ARYjaHR0cDovL3d3dy5jZmNhLmNvbS5jbi91cy91cy0xNC5odG0wNwYDVR0fBDAw
LjAsoCqgKIYmaHR0cDovL2NybC5jZmNhLmNvbS5jbi9SU0EvY3JsMjI3Mi5jcmww
CwYDVR0PBAQDAgPoMB0GA1UdDgQWBBTEIzenf3VR6CZRS61ARrWMto0GODATBgNV
HSUEDDAKBggrBgEFBQcDAjANBgkqhkiG9w0BAQUFAAOCAQEAHMgTi+4Y9g0yvsUA
p7MkdnPtWLS6XwL3IQuXoPInmBSbg2NP8jNhlq8tGL/WJXjycme/8BKu+Hht6lgN
Zhv9STnA59UFo9vxwSQy88bbyui5fKXVliZEiTUhjKM6SOod2Pnp5oWMVjLxujkk
WKjSakPvV6N6H66xhJSCk+Ref59HuFZY4/LqyZysiMua4qyYfEfdKk5h27+z1MWy
nadnxA5QexHHck9Y4ZyisbUubW7wTaaWFd+cZ3P/zmIUskE/dAG0/HEvmOR6CGlM
55BFCVmJEufHtike3shu7lZGVm2adKNFFTqLoEFkfBO6Y/N6ViraBilcXjmWBJNE
MFF/yA==
-----END CERTIFICATE-----';
			file_put_contents(IA_ROOT . '/attachment/unionpay/UpopRsaCert.cer', trim($public_rsa));
		}
	}
	$pay_setting[$type] = $param;
	$payment = iserializer($pay_setting);
	if ($setting) {
		pdo_update('uni_settings', array('payment' => $payment), array('uniacid' => $_W['uniacid']));
	} else {
		pdo_insert('uni_settings', array('payment' => $payment, 'uniacid' => $_W['uniacid']));
	}
	cache_delete("unisetting:{$_W['uniacid']}");
	if ($type == 'unionpay') {
		header('LOCATION: '.url('profile/payment'));
		exit();
	}
	iajax(0, '');
}

if ($do == 'display') {
	$proxy_wechatpay_account = account_wechatpay_proxy();
	$setting = uni_setting_load('payment', $_W['uniacid']);
	$pay_setting = $setting['payment'];
	if (empty($pay_setting['delivery'])) {
		$pay_setting['delivery'] = array('switch' => false);
	}
	if (empty($pay_setting['credit'])) {
		$pay_setting['delivery'] = array('switch' => false);
	}
	if (empty($pay_setting['alipay'])) {
		$pay_setting['alipay'] = array('switch' => false);
	}
	if (empty($pay_setting['wechat'])) {
		$pay_setting['wechat'] = array('switch' => false);
	}
	if (empty($pay_setting['wechat_facilitator'])) {
		$pay_setting['wechat_facilitator'] = array('switch' => false);
	}
	if (empty($pay_setting['unionpay'])) {
		$pay_setting['unionpay'] = array('switch' => false);
	}
	if (empty($pay_setting['baifubao'])) {
		$pay_setting['baifubao'] = array('switch' => false);
	}
	if (empty($pay_setting['line'])) {
		$pay_setting['line'] = array('switch' => false);
	}
		if (empty($_W['isfounder'])) {
		$user_account_list = pdo_getall('uni_account_users', array('uid' => $_W['uid']), array(), 'uniacid');
		$param['uniacid'] = array_keys($user_account_list);
	}
	$accounts = array();
	$accounts[$_W['acid']] = array_elements(array('name', 'acid', 'key', 'secret', 'level'), $_W['account']);
	$pay_setting['unionpay']['signcertexists'] = file_exists(IA_ROOT . '/attachment/unionpay/PM_'.$_W['uniacid'].'_acp.pfx');
}
template('profile/payment');