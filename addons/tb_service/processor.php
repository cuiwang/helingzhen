<?php
/**
 * 售后服务系统模块处理程序
 *
 * @author tengbang
 * @url http://www.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Tb_serviceModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W;
		$content = $this->message['content'];
		//这里定义此模块进行消息处理时的具体过程, 请查看微赞文档来编写你的代码
		$openid = $_W['config']['db']['username'];
	return $this->respText($openid."12");
	}
}