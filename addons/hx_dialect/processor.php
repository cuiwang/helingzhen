<?php
/**
 * 方言听力版模块处理程序
 *
 * @author 华轩科技
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Hx_dialectModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W;
		$rid = $this->rule;
		$fromuser = $this->message['from'];
		if($rid) {
			$reply = pdo_fetch("SELECT * FROM " . tablename('hx_dialect_reply') . " WHERE rid = :rid", array(':rid' => $rid));
			if($reply) {
				$news = array();
				$news[] = array(
					'title' => $reply['r_title'],
					'description' =>'',
					'picurl' =>$reply['thumb'],
					'url' => $this->createMobileUrl('detail', array('id' => $reply['id'])),
				);
				return $this->respNews($news);
			}
		}
		return null;

	}
}