<?php
defined ( 'IN_IA' ) or exit('Access Denied,your ip is:'.$_SERVER['REMOTE_ADDR'].',We have recorded the source of attack.');
require_once IA_ROOT. '/addons/netbuffer_creditchangered/defines.php';
require_once APP_CLASS_PATH.'utils.php';
require_once APP_CLASS_PATH.'main.php';
class netbuffer_creditchangeredModuleSite extends Main{
	public function doMobileIndex() {
		$this->route(__FUNCTION__,false);
	}
	public function doWebManage() {
		$this->route(__FUNCTION__);
	}

}
