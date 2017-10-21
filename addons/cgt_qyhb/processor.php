<?php
/**
 * 奔跑吧模块处理程序
 *
 * @author cgt_
 * @url http://bbs.weihezi.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Cgt_qyhbModuleProcessor extends WeModuleProcessor {
	public function respond() {
		$content = $this->message['content'];
		return $this->respText($content);
	}
}