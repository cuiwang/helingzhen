<?php
/**
 * 关注送红包模块处理程序
 * 鬼 狐 源 码 社 区 www.guifox.com
 */
defined('IN_IA') or exit('Access Denied');

class Cgc_gzredbagModuleReceiver extends WeModuleReceiver {
	public function receive() {
		$type = $this->message['type'];
		//这里定义此模块进行消息订阅时的, 消息到达以后的具体处理过程, 请查看微盒子文档来编写你的代码
	}
}