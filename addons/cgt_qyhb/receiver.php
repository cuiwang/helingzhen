<?php
/**
 * 奔跑吧模块订阅器
 *
 * @author cgt_
 * @url http://bbs.weihezi.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Cgt_bpbModuleReceiver extends WeModuleReceiver {
	public function receive() {
		$type = $this->message['type'];
		//这里定义此模块进行消息订阅时的, 消息到达以后的具体处理过程, 请查看微盒子文档来编写你的代码
	}
}