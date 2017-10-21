<?php
/**
 * 我要上推荐模块处理程序
 *
 * @author 洛杉矶豪哥
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Hao_recommendModuleProcessor extends WeModuleProcessor {
	public function respond() {
		$content = $this->message['content'];
		//这里定义此模块进行消息处理时的具体过程, 请查看微擎文档来编写你的代码
	}
}