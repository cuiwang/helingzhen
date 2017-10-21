<?php

/**

 * 摇一摇中奖模块处理程序

 *

 * @author weizan Team

 * @url http://012wz.com

 */

defined('IN_IA') or exit('Access Denied');



class We7_shakeModuleProcessor extends WeModuleProcessor {

	public function respond() {

		$content = $this->message['content'];

		//这里定义此模块进行消息处理时的具体过程, 请查看微赞文档来编写你的代码

		$reply = pdo_fetch("SELECT * FROM ".tablename('shake_reply')." WHERE rid = :rid", array(':rid' => $this->rule));

		if (!empty($reply)) {

			$response[] = array(

				'title' => '点击进入摇一摇活动',

				'description' => $reply['description'],

				'picurl' => $reply['cover'],

				'url' => $this->buildSiteUrl($this->createMobileUrl('welcome', array('rid' => $reply['rid']))),

			);

			return $this->respNews($response);

		}

	}

}