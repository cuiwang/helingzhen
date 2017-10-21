<?php
class creat_qrcode {
	public function creategroupQrcode($mid = 0) {
		global $_W, $_GPC;
		$path = IA_ROOT . "/addons/wz_tuan/qrcode/" . $_W['uniacid'] . "/";
		if (!is_dir($path)) {
			load() -> func('file');
			mkdirs($path);
		} 
		$url = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&m=wz_tuan&do=check&mid=' . $mid;
		$file = $mid . '.png';
		$qrcode_file = $path . $file;
		if (!is_file($qrcode_file)) {
			require IA_ROOT . '/framework/library/qrcode/phpqrcode.php';
			QRcode :: png($url, $qrcode_file, QR_ECLEVEL_H, 4);
		} 
		return $_W['siteroot'] . 'addons/wz_tuan/qrcode/' . $_W['uniacid'] . '/' . $file;
	}
	
	public function createverQrcode($mid = 0 , $goodsid = 0, $posterid = 0) {
		global $_W, $_GPC;
		$path = IA_ROOT . "/addons/wz_tuan/qrcode/" . $_W['uniacid'];
		if (!is_dir($path)) {
			load() -> func('file');
			mkdirs($path);
		} 
		$url = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&m=wz_tuan&do=shop&p=detail&id=' . $goodsid . '&mid=' . $mid;
		if (!empty($posterid)) {
			$url .= '&posterid=' . $posterid;
		} 
		$file = 'ver_qrcode_' . $posterid . '_' . $mid . '_' . $goodsid . '.png';
		$qrcode_file = $path . '/' . $file;
		if (!is_file($qrcode_file)) {
			require IA_ROOT . '/framework/library/qrcode/phpqrcode.php';
			QRcode :: png($url, $qrcode_file, QR_ECLEVEL_H, 4);
		} 
		return $_W['siteroot'] . 'addons/wz_tuan/qrcode/' . $_W['uniacid'] . '/' . $file;
	} 
} 
