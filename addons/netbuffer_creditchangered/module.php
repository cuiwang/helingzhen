<?php
defined ( 'IN_IA' ) or exit ( 'Access Denied' );
require_once IA_ROOT. '/addons/netbuffer_creditchangered/defines.php';
require_once APP_CLASS_PATH.'setting.php';
class netbuffer_creditchangeredModule extends Setting{
	public function settingsDisplay($settings) {
		$this->sets($settings);
	}
}