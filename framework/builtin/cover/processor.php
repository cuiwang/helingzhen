<?php
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
defined('IN_IA') or exit('Access Denied');

class CoverModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W;
		$content = $this->message['content'];
		$reply = pdo_fetch('SELECT * FROM ' . tablename('cover_reply') . ' WHERE `rid`=:rid', array(':rid' => $this->rule));
		if(!empty($reply)) {
			load()->model('module');
			$module = module_fetch($reply['module']);
			if (empty($module) && !in_array($reply['module'], array('site', 'mc', 'card', 'page', 'clerk'))) {
				return '';
			}
			$url = $reply['url'];
			if(empty($reply['url'])) {
				$entry = pdo_fetch("SELECT eid FROM ".tablename('modules_bindings')." WHERE module = :module AND do = :do", array(':module' => $reply['module'], ':do' => $reply['do']));
				$url = url('entry', array('eid' => $entry['eid']));
			}
			$news = array();
			$news[] = array(
				'title' => $reply['title'],
				'description' => $reply['description'],
				'picurl' => $reply['thumb'],
				'url' => $url
			);
			return $this->respNews($news);
		}
		return '';
	}
}
