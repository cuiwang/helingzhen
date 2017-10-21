<?php
/**
 * 广告自主投放平台模块订阅器
 *
 * @author Michael Hu
 * @url http://bbs.9ye.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Jy_advModuleReceiver extends WeModuleReceiver {
	public function receive() {
		$type = $this->message['type'];
		//这里定义此模块进行消息订阅时的, 消息到达以后的具体处理过程, 请查看微擎文档来编写你的代码
	}
}