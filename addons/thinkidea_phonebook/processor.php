<?php
/**
 * 电话本模块处理程序
 *
 * @author Thinkidea
 * @url http://bbs.Thinkidea.net/
 */
defined('IN_IA') or exit('Access Denied');

class Thinkidea_phonebookModuleProcessor extends WeModuleProcessor {
	
	private $reply_table = 'thinkidea_phonebook_reply';
	
	public function respond() {
		global $_W, $_GPC;
		$rid = $this->rule;		
		$row = pdo_fetch("SELECT * FROM ".tablename($this->reply_table)." WHERE weid = :acid AND rid = :rid", array(':acid' => $_W['uniacid'], ':rid' => $rid));
		return $this->respNews(
			array(
					'title' => $row['title'],
					'description' => $row['description'],
					'picurl'      => $row['avatar'],
					'url' => $this->createMobileUrl('Index')
			)
		);
	}
	
}