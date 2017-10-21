<?php
/**
 * 新媒体矩阵-微信微博矩阵模块处理程序
 *
 * @author weixun
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Wx_xinmeitiModuleProcessor extends WeModuleProcessor {
	public function respond() {
		$content = $this->message['content'];
		//这里定义此模块进行消息处理时的具体过程, 请查看微擎文档来编写你的代码
	}
}