<?php
/**
 * Coreball模块处理程序
 */
defined('IN_IA') or exit('Access Denied');
class Lee_coreballModuleSite extends WeModuleSite {
	public function doMobileindex() {
		global $_W,$_GPC;
		$uniacid = $_W['uniacid'];
		$from_user = $_W['fans']['from_user'];
		$qrcode = $this->module['config']['qrcode'];
		include $this->template('index');
	}	
}