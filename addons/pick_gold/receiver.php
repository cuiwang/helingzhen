<?php
/**
 * 欢乐接金币模块订阅器
 *
 * @author 茶盒互动
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Pick_goldModuleReceiver extends WeModuleReceiver {
	public function receive() {
		$type = $this->message['type'];
		//这里定义此模块进行消息订阅时的, 消息到达以后的具体处理过程, 请查看微擎文档来编写你的代码
	}
}