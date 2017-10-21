<?php
/**
 * 拯救织女
 *
 */
defined('IN_IA') or exit('Access Denied');

class dayu_zhinvModuleReceiver extends WeModuleReceiver {
	public function receive() {
		$type = $this->message['type'];
	}
}