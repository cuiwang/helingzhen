<?php
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
defined('IN_IA') or exit('Access Denied');

class BasicModuleProcessor extends WeModuleProcessor {
	
	public function respond() {
		$sql = "SELECT * FROM " . tablename('basic_reply') . " WHERE `rid` IN ({$this->rule})  ORDER BY RAND() LIMIT 1";
		$reply = pdo_fetch($sql);
		if (empty($reply)) {
			return false;
		}
		$reply['content'] = htmlspecialchars_decode($reply['content']);
				$reply['content'] = str_replace(array('<br>', '&nbsp;'), array("\n", ' '), $reply['content']);
		$reply['content'] = strip_tags($reply['content'], '<a>');
		return $this->respText($reply['content']);
	}
}
