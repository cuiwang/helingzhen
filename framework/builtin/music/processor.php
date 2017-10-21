<?php
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
defined('IN_IA') or exit('Access Denied');

class MusicModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W;
		$rid = $this->rule;
		$sql = "SELECT * FROM " . tablename('music_reply') . " WHERE `rid`=:rid ORDER BY RAND()";
		$item = pdo_fetch($sql, array(':rid' => $rid));
		if (empty($item['id'])) {
			return false;
		}
		return $this->respMusic(array(
			'Title'	=> $item['title'],
			'Description' => $item['description'],
			'MusicUrl' => $item['url'],
			'HQMusicUrl' => $item['hqurl'],
		));
	}
}
