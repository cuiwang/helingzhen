<?php
/**
 * 拯救织女
 *
 */
defined('IN_IA') or exit('Access Denied');

class dayu_zhinvModuleProcessor extends WeModuleProcessor {
	public function respond() {
		$content = $this->message['content'];
		$reply = pdo_fetch("SELECT * FROM ".tablename('dayu_zhinv_reply')." WHERE rid = :rid", array(':rid' => $this->rule));
		if (!empty($reply)) {
			$response[] = array(
				'title' => $reply['title'],
				'description' => $reply['description'],
				'picurl' => $reply['cover'],
				'url' => $this->createMobileUrl('index', array('rid' => $reply['rid'])),
			);
			return $this->respNews($response);
		}
	}
}