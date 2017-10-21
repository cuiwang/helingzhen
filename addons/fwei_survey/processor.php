<?php
/**
 * 微调研模块处理程序
 *
 * @author fwei.net
 * @url http://www.fwei.net/
 */
defined('IN_IA') or exit('Access Denied');

class Fwei_surveyModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W;
		$rid = $this->rule;
		$row = pdo_fetch("SELECT * FROM " . tablename('fwei_survey') . " WHERE `rid`=:rid LIMIT 1", array(':rid' => $rid));
		if( empty($row) ){
			return array();
		}
		$news = array();
		$news[] = array(
			'title'	=>	$row['title'],
			'description'	=>	$row['description'],
			'picurl'	=>	tomedia($row['thumb']),
			'url'	=>	$this->createMobileUrl('reply', array('sid'=>$row['sid'])),
		);
		return $this->respNews( $news );
		//这里定义此模块进行消息处理时的具体过程, 请查看微赞文档来编写你的代码
	}
}