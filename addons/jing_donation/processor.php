<?php
/**
 * 微募捐模块处理程序
 *
 * @author 刘靜
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Jing_donationModuleProcessor extends WeModuleProcessor {
	public $t_reply = 'jing_donation_reply';
	public $t_donation = 'jing_donation';
	public function respond() {
		$content = $this->message['content'];
		//这里定义此模块进行消息处理时的具体过程, 请查看微擎文档来编写你的代码
		$reply = pdo_fetchall("SELECT * FROM ".tablename($this->t_reply)." WHERE rid = :rid", array(':rid' => $this->rule));
		if (!empty($reply)) {
			foreach ($reply as $row) {
				$donationids[$row['donationid']] = $row['donationid'];
			}
			$donation = pdo_fetchall("SELECT id, title, thumb, description FROM ".tablename($this->t_donation)." WHERE id IN (".implode(',', $donationids).")", array(), 'id');
			$response = array();
			foreach ($reply as $row) {
				$row = $donation[$row['donationid']];
				$response[] = array(
					'title' => $row['title'],
					'description' => $row['description'],
					'picurl' => toimage($row['thumb']),
					'url' => $this->buildSiteUrl($this->createMobileUrl('detail', array('id' => $row['id']))),
				);
			}
			return $this->respNews($response);
		}
	}
}