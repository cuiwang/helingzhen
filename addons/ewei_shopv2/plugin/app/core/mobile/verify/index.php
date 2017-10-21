<?php

if (!defined('IN_IA')) {
	exit('Access Denied');
}

require EWEI_SHOPV2_PLUGIN . 'app/core/page_mobile.php';
class Index_EweiShopV2Page extends AppMobilePage
{
	public function qrcode()
	{
		global $_W;
		global $_GPC;
		$orderid = intval($_GPC['id']);
		$verifycode = $_GPC['verifycode'];
		$query = array('id' => $orderid, 'verifycode' => $verifycode);
		$url = mobileUrl('verify/detail', $query, true);
		app_json(array('url' => m('qrcode')->createQrcode($url)));
	}
}

?>
