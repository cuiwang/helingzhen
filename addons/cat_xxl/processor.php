<?php
/**
 * 消消乐模块处理程序
 *
 * @author 猫
 * @url http://www.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Cat_xxlModuleProcessor extends WeModuleProcessor {
	public function respond() {
		$content = $this->message['content'];
		//这里定义此模块进行消息处理时的具体过程, 请查看微赞文档来编写你的代码
	}
}