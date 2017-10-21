<?php
/**
 * 微画报模块处理程序
 *
 * @author 微赞科技
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Hx_pictorialModuleProcessor extends WeModuleProcessor {
	public function respond() {
		$content = $this->message['content'];
		//这里定义此模块进行消息处理时的具体过程, 请查看微赞文档来编写你的代码
		$reply = pdo_fetchall("SELECT * FROM ".tablename('hx_pictorial_reply')." WHERE rid = :rid", array(':rid' => $this->rule));
		if (!empty($reply)) {
			foreach ($reply as $row) {
				$huabaoids[$row['huabaoid']] = $row['huabaoid'];
			}
			$huabao = pdo_fetchall("SELECT id, title, thumb, content FROM ".tablename('hx_pictorial')." WHERE id IN (".implode(',', $huabaoids).")", array(), 'id');
			$response = array();
			foreach ($reply as $row) {
				$row = $huabao[$row['huabaoid']];
				$response[] = array(
					'title' => $row['title'],
					'description' => $row['content'],
					'picurl' => $row['thumb'],
					'url' => $this->buildSiteUrl($this->createMobileUrl('detail', array('id' => $row['id']))),
				);
			}
			return $this->respNews($response);
		}
	}
}