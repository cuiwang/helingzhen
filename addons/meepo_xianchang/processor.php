<?php
/**
 * 米波现场模块处理程序
 *
 * @author 赞木
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Meepo_xianchangModuleProcessor extends WeModuleProcessor {
	public function respond() {
		$content = $this->message['content'];
		return $this->respText('直接添加链接或是图文消息即可');
	}
}