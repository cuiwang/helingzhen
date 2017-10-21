<?php
/**
 * 答题红包模块订阅器
 *
 */
defined('IN_IA') or exit('Access Denied');

class Zofui_dthbModuleReceiver extends WeModuleReceiver {
	public function receive() {
		$type = $this->message['type'];
		//这里定义此模块进行消息订阅时的, 消息到达以后的具体处理过程, 请查看微擎文档来编写你的代码
	}
}